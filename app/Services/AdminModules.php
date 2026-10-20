<?php

namespace App\Services;

use App\Models\ContactMessage;
use App\Models\Customer;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Project;
use App\Models\Quote;
use App\Models\Role;
use App\Models\Sale;
use App\Models\Service;
use App\Models\Specialty;
use App\Models\User;

class AdminModules
{
    // A closed registry keeps route input out of class names and validation rules.
    public static function all(): array
    {
        return [
            'especialidades' => [
                'title' => 'Especialidades',
                'model' => Specialty::class,
                'gate' => 'edit-content',
                'fields' => [
                    'name' => [
                        'Nombre',
                        'text',
                        'required|string|max:100',
                    ],
                    'slug' => [
                        'URL amigable',
                        'text',
                        'required|alpha_dash|max:150|unique:specialties,slug',
                    ],
                    'description' => [
                        'Descripción',
                        'textarea',
                        'nullable|string|max:5000',
                    ],
                    'image' => [
                        'Imagen principal',
                        'file',
                        'nullable|image|mimes:jpg,jpeg,png,webp|extensions:jpg,jpeg,png,webp|max:5120',
                    ],
                    'active' => [
                        'Visible',
                        'checkbox',
                        'boolean',
                    ],
                ],
            ],
            'servicios' => [
                'title' => 'Servicios',
                'model' => Service::class,
                'gate' => 'edit-content',
                'fields' => [
                    'specialty_id' => [
                        'Especialidad',
                        'select',
                        'required|exists:specialties,id',
                        Specialty::class,
                    ],
                    'name' => [
                        'Nombre',
                        'text',
                        'required|string|max:150',
                    ],
                    'description' => [
                        'Descripción',
                        'textarea',
                        'nullable|string|max:5000',
                    ],
                    'active' => [
                        'Visible',
                        'checkbox',
                        'boolean',
                    ],
                ],
            ],
            'categorias' => [
                'title' => 'Categorías',
                'model' => ProductCategory::class,
                'gate' => 'edit-content',
                'fields' => [
                    'name' => [
                        'Nombre',
                        'text',
                        'required|string|max:150',
                    ],
                ],
            ],
            'productos' => [
                'title' => 'Productos',
                'model' => Product::class,
                'gate' => 'edit-content',
                'fields' => [
                    'product_category_id' => [
                        'Categoría',
                        'select',
                        'required|exists:product_categories,id',
                        ProductCategory::class,
                    ],
                    'name' => [
                        'Nombre',
                        'text',
                        'required|string|max:150',
                    ],
                    'description' => [
                        'Descripción',
                        'textarea',
                        'required|string|max:5000',
                    ],
                    'image' => [
                        'Fotografía',
                        'file',
                        'nullable|image|mimes:jpg,jpeg,png,webp|extensions:jpg,jpeg,png,webp|max:5120',
                    ],
                    'price' => [
                        'Precio S/ (opcional)',
                        'number',
                        'nullable|numeric|min:0|max:9999999999.99',
                    ],
                    'unit' => [
                        'Unidad de venta',
                        'text',
                        'required|string|max:30',
                    ],
                    'available' => [
                        'Disponible',
                        'checkbox',
                        'boolean',
                    ],
                ],
            ],
            'proyectos' => [
                'title' => 'Proyectos',
                'model' => Project::class,
                'gate' => 'edit-content',
                'fields' => [
                    'specialty_id' => [
                        'Especialidad',
                        'select',
                        'required|exists:specialties,id',
                        Specialty::class,
                    ],
                    'name' => [
                        'Título',
                        'text',
                        'required|string|max:150',
                    ],
                    'slug' => [
                        'URL amigable',
                        'text',
                        'required|alpha_dash|max:150|unique:projects,slug',
                    ],
                    'description' => [
                        'Descripción',
                        'textarea',
                        'required|string|max:10000',
                    ],
                    'completed_at' => [
                        'Fecha (opcional)',
                        'date',
                        'nullable|date',
                    ],
                    'featured' => [
                        'Destacado',
                        'checkbox',
                        'boolean',
                    ],
                    'published' => [
                        'Publicado: trabajo real confirmado',
                        'checkbox',
                        'boolean',
                    ],
                ],
            ],
            'clientes' => [
                'title' => 'Clientes',
                'model' => Customer::class,
                'gate' => 'manage',
                'fields' => [
                    'name' => [
                        'Nombre',
                        'text',
                        'required|string|max:150',
                    ],
                    'phone' => [
                        'Teléfono',
                        'text',
                        'required|string|max:30',
                    ],
                    'email' => [
                        'Correo',
                        'email',
                        'nullable|email|max:254',
                    ],
                    'address' => [
                        'Dirección',
                        'textarea',
                        'nullable|string|max:1000',
                    ],
                ],
            ],
            'cotizaciones' => [
                'title' => 'Cotizaciones',
                'model' => Quote::class,
                'gate' => 'manage',
                'readonly' => true,
                'fields' => [

                ],
            ],
            'mensajes' => [
                'title' => 'Mensajes de contacto',
                'model' => ContactMessage::class,
                'gate' => 'manage',
                'fields' => [
                    'name' => [
                        'Nombre',
                        'display',
                        '',
                    ],
                    'phone' => [
                        'Teléfono',
                        'display',
                        '',
                    ],
                    'email' => [
                        'Correo',
                        'display',
                        '',
                    ],
                    'message' => [
                        'Mensaje',
                        'display',
                        '',
                    ],
                    'read_at' => [
                        'Fecha de lectura (vacío = no leído)',
                        'datetime-local',
                        'nullable|date',
                    ],
                ],
            ],
            'ventas' => [
                'title' => 'Ventas registradas',
                'model' => Sale::class,
                'gate' => 'manage',
                'fields' => [
                    'customer_id' => [
                        'Cliente',
                        'select',
                        'required|exists:customers,id',
                        Customer::class,
                    ],
                    'quote_id' => [
                        'Cotización (ID opcional)',
                        'number',
                        'nullable|exists:quotes,id',
                    ],
                    'amount' => [
                        'Importe S/',
                        'number',
                        'required|numeric|min:0|max:9999999999.99',
                    ],
                    'sold_at' => [
                        'Fecha de venta',
                        'date',
                        'required|date',
                    ],
                    'notes' => [
                        'Observaciones',
                        'textarea',
                        'nullable|string|max:5000',
                    ],
                ],
            ],
            'usuarios' => [
                'title' => 'Usuarios autorizados',
                'model' => User::class,
                'gate' => 'manage',
                'fields' => [
                    'name' => [
                        'Nombre',
                        'text',
                        'required|string|max:150',
                    ],
                    'email' => [
                        'Correo',
                        'email',
                        'required|email|max:254|unique:users,email',
                    ],
                    'role_id' => [
                        'Rol',
                        'select',
                        'required|exists:roles,id',
                        Role::class,
                    ],
                    'password' => [
                        'Nueva contraseña',
                        'password',
                        'nullable|string',
                    ],
                    'active' => [
                        'Acceso activo',
                        'checkbox',
                        'boolean',
                    ],
                ],
            ],

        ];
    }

    public static function get(string $key): array
    {
        $all = self::all();
        abort_unless(isset($all[$key]), 404);

        return $all[$key];
    }
}
