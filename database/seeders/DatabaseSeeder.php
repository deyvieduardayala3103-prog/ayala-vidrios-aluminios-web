<?php

namespace Database\Seeders;

use App\Models\QuoteStatus;
use App\Models\Role;
use App\Models\Service;
use App\Models\Setting;
use App\Models\Specialty;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        foreach (['administrador', 'editor'] as $role) {
            Role::firstOrCreate(['name' => $role]);
        }
        $items = [
            'Vidrios y cristales' => ['Ventanas', 'Mamparas', 'Puertas de vidrio', 'Espejos', 'Divisiones', 'Instalaciones personalizadas'],
            'Melamina' => ['Muebles de cocina', 'Clósets', 'Roperos', 'Escritorios', 'Muebles personalizados'],
            'Drywall' => ['Divisiones interiores', 'Cielos rasos', 'Techos', 'Revestimientos', 'Remodelaciones'],
            'Baldosas' => ['Instalación de baldosas', 'Pisos', 'Paredes', 'Baños', 'Cocinas', 'Acabados y revestimientos'],
            'Acero inoxidable' => ['Barandas', 'Pasamanos', 'Estructuras', 'Mesas', 'Fabricaciones personalizadas'],
        ];
        foreach ($items as $name => $services) {
            $sp = Specialty::firstOrCreate(['slug' => Str::slug($name)], ['name' => $name, 'description' => 'Soluciones de '.mb_strtolower($name).' para tus espacios. Cuéntanos tu idea para evaluar el trabajo y preparar una propuesta personalizada.']);
            foreach ($services as $service) {
                Service::firstOrCreate(['specialty_id' => $sp->id, 'name' => $service]);
            }
        }
        foreach (['Pendiente', 'En revisión', 'Cotizada', 'Aprobada', 'Rechazada', 'Finalizada'] as $status) {
            QuoteStatus::firstOrCreate(['name' => $status]);
        }
        foreach (['products_enabled' => '0', 'sales_enabled' => '0', 'business_name' => 'AYALA VIDRIOS Y ALUMINIOS', 'phone' => '', 'whatsapp' => '', 'email' => '', 'address' => '', 'hours' => '', 'history' => '', 'mission' => '', 'vision' => '', 'values' => '', 'privacy' => '', 'claims' => '', 'facebook' => '', 'instagram' => '', 'map_url' => ''] as $key => $value) {
            Setting::firstOrCreate(['key' => $key], ['value' => $value]);
        }
    }
}
