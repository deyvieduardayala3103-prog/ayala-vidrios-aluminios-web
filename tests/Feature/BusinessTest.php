<?php

namespace Tests\Feature;

use App\Models\ContactMessage;
use App\Models\Customer;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Project;
use App\Models\Quote;
use App\Models\QuoteStatus;
use App\Models\Role;
use App\Models\Service;
use App\Models\Setting;
use App\Models\Specialty;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class BusinessTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
        $this->withoutVite();
    }

    private function account(string $role = 'administrador'): User
    {
        return User::factory()->create(['role_id' => Role::where('name', $role)->firstOrFail()->id, 'active' => true]);
    }

    private function payload(): array
    {
        $s = Specialty::first();

        return ['name' => 'Cliente de prueba', 'phone' => '999111222', 'email' => 'prueba@example.test', 'specialty_id' => $s->id, 'work_type' => $s->services()->first()->name, 'width' => 1.2, 'height' => 2.3, 'unit' => 'm', 'quantity' => 2, 'description' => 'Necesito una instalación para mi espacio de prueba.', 'consent' => 1];
    }

    public function test_public_pages_and_exact_specialties(): void
    {
        foreach (['/', '/nosotros', '/servicios', '/proyectos', '/cotizaciones', '/contacto', '/legal/privacidad', '/legal/reclamaciones', '/sitemap.xml', '/ingresar'] as $path) {
            $this->get($path)->assertOk();
        }
        $this->assertDatabaseCount('specialties', 5);
        $this->assertDatabaseHas('specialties', ['name' => 'Baldosas']);
        $this->assertDatabaseCount('projects', 0);
        $this->assertDatabaseCount('quotes', 0);
        foreach (Specialty::all() as $s) {
            $this->get('/servicios/'.$s->slug)->assertOk()->assertSee($s->name);
        }
        $this->get('/productos')->assertNotFound();
        $this->get('/solicitud/recibida')->assertNotFound();
        $this->get('/')->assertDontSee('https://wa.me/');
    }

    public function test_admin_routes_are_private_and_roles_are_enforced(): void
    {
        $this->get('/admin')->assertRedirect('/ingresar');
        $editor = $this->account('editor');
        $this->actingAs($editor)->get('/admin')->assertOk();
        $this->get('/admin/clientes')->assertForbidden();
        $this->get('/admin/configuracion')->assertForbidden();
        $this->get('/admin/usuarios')->assertForbidden();
        $this->get('/admin/cotizaciones')->assertForbidden();
        $this->get('/admin/servicios')->assertOk();
        $editor->update(['active' => false]);
        $this->get('/admin')->assertForbidden();
    }

    public function test_all_admin_views_render_with_empty_states(): void
    {
        $this->actingAs($this->account());
        $this->get('/admin')->assertOk()->assertSee('0');
        foreach (['especialidades', 'servicios', 'categorias', 'productos', 'proyectos', 'clientes', 'cotizaciones', 'mensajes', 'usuarios'] as $module) {
            $this->get('/admin/'.$module)->assertOk();
            if (! in_array($module, ['cotizaciones', 'mensajes'])) {
                $this->get('/admin/'.$module.'/crear')->assertOk();
            }
        }
        $this->get('/admin/configuracion')->assertOk();
        Setting::where('key', 'sales_enabled')->update(['value' => '1']);
        $this->get('/admin/ventas')->assertOk();
        $this->get('/admin/ventas/crear')->assertOk();
    }

    public function test_login_logout_and_invalid_credentials(): void
    {
        $user = $this->account();
        $this->post('/ingresar', ['email' => $user->email, 'password' => 'incorrecta'])->assertSessionHasErrors('email');
        $this->assertGuest();
        $this->post('/ingresar', ['email' => $user->email, 'password' => 'password'])->assertRedirect('/admin');
        $this->assertAuthenticatedAs($user);
        $this->post('/salir')->assertRedirect('/');
        $this->assertGuest();
    }

    public function test_quote_is_persisted_with_items_private_images_and_history(): void
    {
        Storage::fake('local');
        $this->post('/cotizaciones', [...$this->payload(), 'photos' => [UploadedFile::fake()->image('referencia.jpg')]])->assertRedirect('/solicitud/recibida');
        $quote = Quote::firstOrFail();
        $this->assertDatabaseCount('customers', 1);
        $this->assertEquals('Pendiente', $quote->status->name);
        $this->assertEquals(2, $quote->items->first()->quantity);
        $this->assertNull($quote->amount);
        $this->assertCount(1, $quote->histories);
        $file = $quote->attachments->first();
        Storage::disk('local')->assertExists($file->path);
        $this->get('/solicitud/recibida')->assertOk()->assertSee($quote->reference);
        $this->get('/admin/adjuntos/'.$file->id)->assertRedirect('/ingresar');
        $this->actingAs($this->account('editor'))->get('/admin/adjuntos/'.$file->id)->assertForbidden();
        $this->actingAs($this->account())->get('/admin/adjuntos/'.$file->id)->assertDownload('referencia.jpg');
    }

    public function test_quote_rejects_invalid_files_dimensions_and_cross_specialty_services(): void
    {
        $this->post('/cotizaciones', [])->assertSessionHasErrors(['name', 'phone', 'specialty_id', 'quantity', 'description', 'consent']);
        $this->post('/cotizaciones', [...$this->payload(), 'width' => -1, 'photos' => [UploadedFile::fake()->create('exploit.php', 10, 'application/x-php')]])->assertSessionHasErrors(['width', 'photos.0']);
        $this->post('/cotizaciones', [...$this->payload(), 'work_type' => 'Muebles de cocina'])->assertSessionHasErrors('work_type');
        $this->assertDatabaseCount('quotes', 0);
    }

    public function test_quote_status_and_manual_price_are_audited(): void
    {
        $this->post('/cotizaciones', $this->payload());
        $quote = Quote::firstOrFail();
        $admin = $this->account();
        $this->actingAs($admin)->get('/admin/cotizaciones/'.$quote->id)->assertOk();
        $status = QuoteStatus::where('name', 'Cotizada')->firstOrFail();
        $this->put('/admin/cotizaciones/'.$quote->id, ['quote_status_id' => $status->id, 'amount' => 450.50, 'observations' => 'Observación de prueba', 'note' => 'Importe evaluado manualmente'])->assertSessionHasNoErrors();
        $this->assertEquals('450.50', $quote->fresh()->amount);
        $this->assertEquals($status->id, $quote->fresh()->quote_status_id);
        $this->assertDatabaseHas('quote_status_histories', ['quote_id' => $quote->id, 'user_id' => $admin->id, 'amount' => 450.50]);
        $this->get('/admin/cotizaciones?from=2026-01-01&to=2026-12-31&specialty_id='.$quote->specialty_id.'&status='.$status->id.'&q=Cliente')->assertOk()->assertSee('Cliente de prueba');
    }

    public function test_service_crud_and_referential_integrity(): void
    {
        $this->actingAs($this->account());
        $s = Specialty::first();
        $this->post('/admin/servicios', ['specialty_id' => $s->id, 'name' => 'Servicio de prueba', 'active' => 1])->assertRedirect('/admin/servicios');
        $service = Service::where('name', 'Servicio de prueba')->firstOrFail();
        $this->get('/admin/servicios/'.$service->id.'/editar')->assertOk();
        $this->put('/admin/servicios/'.$service->id, ['specialty_id' => $s->id, 'name' => 'Servicio actualizado', 'active' => 1])->assertSessionHasNoErrors();
        $this->assertDatabaseHas('services', ['name' => 'Servicio actualizado']);
        $this->delete('/admin/especialidades/'.$s->id)->assertSessionHasErrors('record');
        $this->delete('/admin/servicios/'.$service->id)->assertSessionHasNoErrors();
        $this->assertDatabaseMissing('services', ['id' => $service->id]);
    }

    public function test_project_crud_gallery_publication_and_file_cleanup(): void
    {
        Storage::fake('public');
        $this->actingAs($this->account());
        $data = ['specialty_id' => Specialty::first()->id, 'name' => 'Proyecto de prueba', 'slug' => 'proyecto-de-prueba', 'description' => 'Descripción exclusivamente para las pruebas automatizadas.', 'published' => 0, 'featured' => 1, 'image_alt' => 'Fotografía de prueba'];
        $this->post('/admin/proyectos', [...$data, 'photos' => [UploadedFile::fake()->image('proyecto.png')]])->assertRedirect('/admin/proyectos');
        $p = Project::firstOrFail();
        $image = $p->images->first();
        Storage::disk('public')->assertExists($image->path);
        $this->get('/proyectos/'.$p->slug)->assertNotFound();
        $this->get('/admin/proyectos/'.$p->id.'/editar')->assertOk();
        $this->put('/admin/proyectos/'.$p->id, [...$data, 'published' => 1])->assertSessionHasNoErrors();
        $this->get('/proyectos/'.$p->slug)->assertOk()->assertSee('Fotografía de prueba');
        $this->get('/proyectos?especialidad='.$p->specialty->slug)->assertOk()->assertSee($p->name);
        $this->get('/')->assertSee($p->name);
        $this->delete('/admin/proyectos/'.$p->id)->assertSessionHasNoErrors();
        Storage::disk('public')->assertMissing($image->path);
        $this->assertDatabaseCount('project_images', 0);
    }

    public function test_contact_messages_and_read_status(): void
    {
        $this->post('/contacto', ['name' => 'Consulta de prueba', 'phone' => '999111222', 'message' => 'Mensaje de prueba del formulario.', 'consent' => 1])->assertSessionHas('success');
        $message = ContactMessage::firstOrFail();
        $this->actingAs($this->account())->get('/admin/mensajes/'.$message->id.'/editar')->assertOk()->assertSee('Mensaje de prueba');
        $this->put('/admin/mensajes/'.$message->id, ['read_at' => '2026-10-19T12:00'])->assertSessionHasNoErrors();
        $this->assertNotNull($message->fresh()->read_at);
    }

    public function test_products_toggle_settings_logo_preservation_and_whatsapp(): void
    {
        Storage::fake('public');
        $this->actingAs($this->account());
        $file = UploadedFile::fake()->image('logo.png');
        $bytes = file_get_contents($file->getRealPath());
        $this->put('/admin/configuracion', ['business_name' => 'AYALA VIDRIOS Y ALUMINIOS', 'whatsapp' => '51999111222', 'products_enabled' => 1, 'logo' => $file])->assertSessionHasNoErrors();
        $this->assertEquals($bytes, Storage::disk('public')->get(Setting::valueOf('logo')));
        $this->get('/productos')->assertOk();
        $this->get('/')->assertSee('https://wa.me/51999111222');
        $this->get('/sitemap.xml')->assertSee('/productos');
        $this->put('/admin/configuracion', ['business_name' => 'AYALA', 'whatsapp' => 'javascript:alert(1)'])->assertSessionHasErrors('whatsapp');
    }

    public function test_user_password_rules_and_self_protection(): void
    {
        $user = $this->account();
        $this->actingAs($user);
        $role = $user->role_id;
        $this->post('/admin/usuarios', ['name' => 'Otro', 'email' => 'otro@example.test', 'role_id' => $role, 'password' => '1234', 'active' => 1])->assertSessionHasErrors('password');
        $this->put('/admin/usuarios/'.$user->id, ['name' => $user->name, 'email' => $user->email, 'role_id' => $role])->assertSessionHasErrors('active');
        $this->post('/admin/usuarios', ['name' => 'Otro', 'email' => 'otro@example.test', 'role_id' => $role, 'password' => 'SeguraPrueba#2026', 'active' => 1])->assertSessionHasNoErrors();
        $this->assertTrue(Hash::check('SeguraPrueba#2026', User::where('email', 'otro@example.test')->first()->password));
    }

    public function test_public_rate_limit(): void
    {
        for ($i = 0; $i < 5; $i++) {
            $this->post('/contacto', [])->assertStatus(302);
        }$this->post('/contacto', [])->assertStatus(429);
    }

    public function test_database_driver_matches_the_requested_engine(): void
    {
        $this->assertSame(getenv('DB_CONNECTION') ?: 'sqlite', DB::connection()->getDriverName());
    }

    public function test_csrf_rejects_public_posts_without_token(): void
    {
        $this->app->instance('env', 'local');
        $this->post('/contacto', ['name' => 'Intento sin token'])->assertStatus(419);
        $this->app->instance('env', 'testing');
    }

    public function test_home_navigation_links_resolve(): void
    {
        $html = $this->get('/')->assertOk()->getContent();
        preg_match_all('/href="(http:\/\/localhost[^"#]*)"/', $html, $matches);
        foreach (array_unique($matches[1]) as $url) {
            $this->get(html_entity_decode($url))->assertOk();
        }
    }

    public function test_catalog_and_sales_register_real_input(): void
    {
        Storage::fake('public');
        $this->actingAs($this->account());
        $this->post('/admin/categorias', ['name' => 'Categoría de prueba'])->assertSessionHasNoErrors();
        $category = ProductCategory::firstOrFail();
        $data = ['product_category_id' => $category->id, 'name' => 'Producto de prueba', 'description' => 'Descripción de producto para verificación.', 'price' => '120.00', 'unit' => 'unidad', 'available' => 1];
        $this->post('/admin/productos', [...$data, 'image' => UploadedFile::fake()->image('producto.jpg')])->assertSessionHasNoErrors();
        $product = Product::firstOrFail();
        $this->assertStringEndsWith('.webp', $product->image);
        Setting::where('key', 'products_enabled')->update(['value' => '1']);
        $this->get('/productos?categoria='.$category->id)->assertOk()->assertSee($product->name)->assertSee('120.00');
        $this->put('/admin/productos/'.$product->id, [...$data, 'price' => '150'])->assertSessionHasNoErrors();
        $this->post('/admin/clientes', ['name' => 'Cliente ventas', 'phone' => '999888777'])->assertSessionHasNoErrors();
        $customer = Customer::firstOrFail();
        Setting::where('key', 'sales_enabled')->update(['value' => '1']);
        $this->post('/admin/ventas', ['customer_id' => $customer->id, 'amount' => '150', 'sold_at' => '2026-10-19'])->assertSessionHasNoErrors();
        $this->get('/admin/ventas?q=Cliente')->assertOk()->assertSee('150.00');
        $this->delete('/admin/clientes/'.$customer->id)->assertSessionHasErrors('record');
        $this->delete('/admin/productos/'.$product->id)->assertSessionHasNoErrors();
        Storage::disk('public')->assertMissing($product->image);
    }

    public function test_login_rate_limit(): void
    {
        for ($i = 0; $i < 5; $i++) {
            $this->post('/ingresar', ['email' => 'ausente@example.test', 'password' => 'incorrecta'])->assertSessionHasErrors('email');
        }
        $this->post('/ingresar', ['email' => 'ausente@example.test', 'password' => 'incorrecta'])->assertStatus(429);
    }
}
