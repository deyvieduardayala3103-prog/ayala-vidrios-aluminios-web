<?php

namespace App\Http\Controllers;

use App\Http\Requests\AdminRecordRequest;
use App\Models\Customer;
use App\Models\Project;
use App\Models\ProjectImage;
use App\Models\Quote;
use App\Models\QuoteAttachment;
use App\Models\QuoteStatus;
use App\Models\Role;
use App\Models\Sale;
use App\Models\Setting;
use App\Models\User;
use App\Services\AdminModules;
use App\Services\PublicImages;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class AdminController extends Controller
{
    private function module(string $module): array
    {
        $config = AdminModules::get($module);
        Gate::authorize($config['gate']);
        if ($module === 'ventas') {
            abort_unless(Setting::valueOf('sales_enabled') === '1', 404);
        }

        return $config;
    }

    public function dashboard()
    {
        $stats = Gate::allows('manage') ? ['Total de cotizaciones' => Quote::count(), 'Pendientes' => Quote::whereHas('status', fn ($q) => $q->where('name', 'Pendiente'))->count(), 'Aprobadas' => Quote::whereHas('status', fn ($q) => $q->where('name', 'Aprobada'))->count(), 'Proyectos registrados' => Project::count(), 'Clientes registrados' => Customer::count()] : ['Proyectos registrados' => Project::count()];
        if (Gate::allows('manage') && Setting::valueOf('sales_enabled') === '1') {
            $stats['Ventas registradas'] = Sale::count();
        }

        return view('admin.dashboard', ['stats' => $stats, 'recent' => Gate::allows('manage') ? Quote::with('customer', 'specialty', 'status')->latest()->take(6)->get() : collect()]);
    }

    public function index(Request $request, string $module)
    {
        $config = $this->module($module);
        $request->validate(['q' => 'nullable|string|max:150']);
        $query = $config['model']::query();
        if ($module === 'cotizaciones') {
            $query->with('customer', 'specialty', 'status');
            $query->when($request->filled('q'), fn ($q) => $q->whereHas('customer', fn ($c) => $c->where('name', 'like', '%'.$request->string('q').'%')));
            $request->validate(['from' => 'nullable|date', 'to' => 'nullable|date|after_or_equal:from', 'specialty_id' => 'nullable|integer', 'status' => 'nullable|integer']);
            $query->when($request->filled('specialty_id'), fn ($q) => $q->where('specialty_id', $request->integer('specialty_id')))->when($request->filled('status'), fn ($q) => $q->where('quote_status_id', $request->integer('status')))->when($request->filled('from'), fn ($q) => $q->whereDate('created_at', '>=', $request->input('from')))->when($request->filled('to'), fn ($q) => $q->whereDate('created_at', '<=', $request->input('to')));
        } elseif ($module === 'ventas') {
            $query->with('customer')->when($request->filled('q'), fn ($q) => $q->whereHas('customer', fn ($c) => $c->where('name', 'like', '%'.$request->string('q').'%')));
        } else {
            $query->when($request->filled('q'), fn ($q) => $q->where('name', 'like', '%'.$request->string('q').'%'));
        }

        return view('admin.index', compact('module', 'config') + ['rows' => $query->latest('id')->paginate(15)->withQueryString()]);
    }

    public function create(string $module)
    {
        $config = $this->module($module);
        abort_if(! empty($config['readonly']) || $module === 'mensajes', 405);

        return $this->form($module, $config, new $config['model']);
    }

    public function edit(string $module, int $id)
    {
        $config = $this->module($module);
        abort_if(! empty($config['readonly']), 405);

        return $this->form($module, $config, $config['model']::findOrFail($id));
    }

    private function form(string $module, array $config, $record)
    {
        $options = [];
        foreach ($config['fields'] as $key => $f) {
            if ($f[1] === 'select') {
                $options[$key] = $f[3]::orderBy('name')->pluck('name', 'id');
            }
        }

        return view('admin.form', compact('module', 'config', 'record', 'options'));
    }

    public function store(AdminRecordRequest $request, string $module, PublicImages $images)
    {
        $config = $this->module($module);
        abort_if($module === 'mensajes', 405);
        $this->persist($request, $module, $config, new $config['model'], $images);

        return redirect()->route('admin.index', $module)->with('success', 'Registro creado.');
    }

    public function update(AdminRecordRequest $request, string $module, int $id, PublicImages $images)
    {
        $config = $this->module($module);
        $this->persist($request, $module, $config, $config['model']::findOrFail($id), $images);

        return redirect()->route('admin.index', $module)->with('success', 'Cambios guardados.');
    }

    private function persist(AdminRecordRequest $request, string $module, array $config, $record, PublicImages $images): void
    {
        $data = $request->validated();
        unset($data['photos'],$data['image_alt']);
        foreach ($config['fields'] as $key => $f) {
            if ($f[1] === 'checkbox') {
                $data[$key] = $request->boolean($key);
            }
            if ($f[1] === 'file') {
                unset($data[$key]);
            }
        }
        if ($module === 'usuarios') {
            if (empty($data['password'])) {
                unset($data['password']);
            }
            $this->protectAdmin($record, $data);
        }
        if ($module === 'ventas' && ! empty($data['quote_id']) && Quote::findOrFail($data['quote_id'])->customer_id != $data['customer_id']) {
            throw ValidationException::withMessages(['quote_id' => 'La cotización debe pertenecer al cliente seleccionado.']);
        }
        $created = [];
        $oldImage = null;
        try {
            DB::transaction(function () use ($request, $record, $data, $images, $module, &$created, &$oldImage) {
                if ($request->hasFile('image')) {
                    $path = $images->store($request->file('image'));
                    $created[] = $path;
                    $oldImage = $record->image;
                    $data['image'] = $path;
                }
                $record->fill($data)->save();
                if ($module === 'proyectos') {
                    foreach ($request->file('photos', []) as $photo) {
                        $path = $images->store($photo, 'projects');
                        $created[] = $path;
                        $record->images()->create(['path' => $path, 'alt' => $request->input('image_alt')]);
                    }
                }
            });
        } catch (\Throwable $e) {
            Storage::disk('public')->delete($created);
            throw $e;
        }
        if ($oldImage) {
            Storage::disk('public')->delete($oldImage);
        }
    }

    private function protectAdmin($record, array $data): void
    {
        if (! $record->exists) {
            return;
        }
        if ($record->id === auth()->id() && (! $data['active'] || $data['role_id'] != $record->role_id)) {
            throw ValidationException::withMessages(['active' => 'No puedes desactivar ni cambiar tu propio rol.']);
        }
        if ($record->isAdmin() && (! $data['active'] || Role::find($data['role_id'])?->name !== 'administrador') && User::whereHas('role', fn ($q) => $q->where('name', 'administrador'))->where('active', true)->count() <= 1) {
            throw ValidationException::withMessages(['role_id' => 'Debe existir al menos un administrador activo.']);
        }
    }

    public function destroy(string $module, int $id)
    {
        $config = $this->module($module);
        abort_if(in_array($module, ['cotizaciones', 'mensajes', 'ventas']), 405);
        $record = $config['model']::findOrFail($id);
        if ($module === 'usuarios') {
            abort_if($record->id === auth()->id(), 422);
            $this->protectAdmin($record, ['active' => false, 'role_id' => $record->role_id]);
        }
        $files = [];
        if (in_array($module, ['especialidades', 'productos']) && $record->image) {
            $files[] = $record->image;
        }
        if ($module === 'proyectos') {
            $files = $record->images->pluck('path')->all();
        }
        try {
            $record->delete();
        } catch (QueryException $e) {
            if (str_starts_with((string) $e->getCode(), '23')) {
                return back()->withErrors(['record' => 'No se puede eliminar: hay registros relacionados.']);
            }throw $e;
        }
        Storage::disk('public')->delete($files);

        return back()->with('success', 'Registro eliminado.');
    }

    public function quote(Quote $quote)
    {
        $quote->load('customer', 'specialty', 'status', 'items', 'attachments', 'histories.status', 'histories.user');

        return view('admin.quote', ['quote' => $quote, 'statuses' => QuoteStatus::all()]);
    }

    public function updateQuote(Request $request, Quote $quote)
    {
        $data = $request->validate(['quote_status_id' => 'required|exists:quote_statuses,id', 'amount' => 'nullable|numeric|min:0|max:9999999999.99', 'observations' => 'nullable|string|max:10000', 'note' => 'required|string|max:2000']);
        DB::transaction(function () use ($data, $quote, $request) {
            $locked = Quote::lockForUpdate()->findOrFail($quote->id);
            $locked->update(collect($data)->except('note')->all());
            $locked->histories()->create(['quote_status_id' => $data['quote_status_id'], 'user_id' => $request->user()->id, 'note' => $data['note'], 'amount' => $data['amount'] ?? null]);
        });

        return back()->with('success', 'Cotización actualizada e historial registrado.');
    }

    public function attachment(QuoteAttachment $attachment)
    {
        return Storage::disk('local')->download($attachment->path, $attachment->original_name, ['X-Content-Type-Options' => 'nosniff']);
    }

    public function deleteImage(ProjectImage $image)
    {
        $path = $image->path;
        $image->delete();
        Storage::disk('public')->delete($path);

        return back()->with('success', 'Imagen eliminada.');
    }

    public function settings()
    {
        return view('admin.settings');
    }

    public function saveSettings(Request $request)
    {
        $rules = ['business_name' => 'required|string|max:150', 'phone' => 'nullable|string|max:30', 'whatsapp' => ['nullable', 'regex:/^[1-9][0-9]{7,14}$/'], 'email' => 'nullable|email|max:254', 'address' => 'nullable|string|max:500', 'hours' => 'nullable|string|max:500', 'history' => 'nullable|string|max:10000', 'mission' => 'nullable|string|max:5000', 'vision' => 'nullable|string|max:5000', 'values' => 'nullable|string|max:5000', 'privacy' => 'nullable|string|max:20000', 'claims' => 'nullable|string|max:20000', 'facebook' => 'nullable|url:http,https|max:500', 'instagram' => 'nullable|url:http,https|max:500', 'map_url' => 'nullable|url:https|max:1000', 'products_enabled' => 'boolean', 'sales_enabled' => 'boolean', 'logo' => 'nullable|image|mimes:jpg,jpeg,png,webp|extensions:jpg,jpeg,png,webp|max:5120', 'hero' => 'nullable|image|mimes:jpg,jpeg,png,webp|extensions:jpg,jpeg,png,webp|max:5120'];
        $data = $request->validate($rules);
        unset($data['logo'],$data['hero']);
        $old = [];
        $new = [];
        try {
            DB::transaction(function () use ($request, $data, &$old, &$new) {
                foreach (['products_enabled', 'sales_enabled'] as $key) {
                    $data[$key] = $request->boolean($key) ? '1' : '0';
                }
                foreach (['logo', 'hero'] as $key) {
                    if ($request->hasFile($key)) {
                        $old[] = Setting::valueOf($key);
                        $data[$key] = $key === 'logo' ? $request->file($key)->store('branding', 'public') : app(PublicImages::class)->store($request->file($key), 'branding');
                        $new[] = $data[$key];
                    }
                }
                foreach ($data as $key => $value) {
                    Setting::updateOrCreate(['key' => $key], ['value' => $value]);
                }
            });
        } catch (\Throwable $e) {
            Storage::disk('public')->delete($new);
            throw $e;
        }
        Storage::disk('public')->delete(array_filter($old));

        return back()->with('success', 'Configuración actualizada.');
    }
}
