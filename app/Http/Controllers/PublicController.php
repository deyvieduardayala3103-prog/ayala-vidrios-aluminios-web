<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactRequest;
use App\Http\Requests\QuoteRequest;
use App\Models\ContactMessage;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Project;
use App\Models\Setting;
use App\Models\Specialty;
use App\Services\QuoteIntake;
use Illuminate\Http\Request;

class PublicController extends Controller
{
    public function home()
    {
        return view('public.home', ['specialties' => Specialty::where('active', true)->with('services')->get(), 'projects' => Project::where('published', true)->where('featured', true)->with('images', 'specialty')->latest()->take(3)->get()]);
    }

    public function about()
    {
        return view('public.about');
    }

    public function services()
    {
        return view('public.services', ['specialties' => Specialty::where('active', true)->with('services')->get()]);
    }

    public function specialty(string $slug)
    {
        $specialty = Specialty::where('active', true)->where('slug', $slug)->with(['services' => fn ($q) => $q->where('active', true), 'projects' => fn ($q) => $q->where('published', true)->with('images')])->firstOrFail();

        return view('public.specialty', compact('specialty'));
    }

    public function projects(Request $request)
    {
        $projects = Project::where('published', true)->when($request->filled('especialidad'), fn ($q) => $q->whereHas('specialty', fn ($s) => $s->where('slug', $request->string('especialidad'))))->with('images', 'specialty')->latest()->paginate(12)->withQueryString();

        return view('public.projects', ['projects' => $projects, 'specialties' => Specialty::where('active', true)->get()]);
    }

    public function project(string $slug)
    {
        return view('public.project', ['project' => Project::where('published', true)->where('slug', $slug)->with('images', 'specialty')->firstOrFail()]);
    }

    public function quote()
    {
        return view('public.quote', ['specialties' => Specialty::where('active', true)->with(['services' => fn ($q) => $q->where('active', true)])->get()]);
    }

    public function storeQuote(QuoteRequest $request, QuoteIntake $intake)
    {
        $quote = $intake->create($request->validated(), $request->file('photos', []));
        $request->session()->put('quote_receipt', $quote->reference);

        return redirect()->route('quote.receipt');
    }

    public function receipt(Request $request)
    {
        abort_unless($request->session()->has('quote_receipt'), 404);

        return view('public.receipt', ['reference' => $request->session()->get('quote_receipt')]);
    }

    public function contact()
    {
        return view('public.contact');
    }

    public function storeContact(ContactRequest $request)
    {
        ContactMessage::create([...$request->safe()->only(['name', 'phone', 'email', 'message']), 'consented_at' => now()]);

        return back()->with('success', 'Recibimos tu mensaje. Podrás recibir una respuesta a través de los datos que indicaste.');
    }

    public function products(Request $request)
    {
        abort_unless(Setting::valueOf('products_enabled') === '1', 404);

        return view('public.products', ['categories' => ProductCategory::all(), 'products' => Product::with('category')->when($request->filled('categoria'), fn ($q) => $q->where('product_category_id', $request->integer('categoria')))->latest()->paginate(12)->withQueryString()]);
    }

    public function legal(string $page)
    {
        abort_unless(in_array($page, ['privacidad', 'reclamaciones']), 404);

        return view('public.legal', compact('page'));
    }

    public function sitemap()
    {
        return response()->view('public.sitemap', ['specialties' => Specialty::where('active', true)->get(), 'projects' => Project::where('published', true)->get(), 'catalog' => Setting::valueOf('products_enabled') === '1'])->header('Content-Type', 'application/xml');
    }
}
