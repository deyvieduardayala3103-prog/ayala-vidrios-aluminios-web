<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PublicController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PublicController::class, 'home'])->name('home');
Route::get('/nosotros', [PublicController::class, 'about'])->name('about');
Route::get('/servicios', [PublicController::class, 'services'])->name('services');
Route::get('/servicios/{slug}', [PublicController::class, 'specialty'])->name('specialty');
Route::get('/proyectos', [PublicController::class, 'projects'])->name('projects');
Route::get('/proyectos/{slug}', [PublicController::class, 'project'])->name('project');
Route::get('/productos', [PublicController::class, 'products'])->name('products');
Route::get('/cotizaciones', [PublicController::class, 'quote'])->name('quote');
Route::post('/cotizaciones', [PublicController::class, 'storeQuote'])->middleware('throttle:public-forms')->name('quote.store');
Route::get('/solicitud/recibida', [PublicController::class, 'receipt'])->name('quote.receipt');
Route::get('/contacto', [PublicController::class, 'contact'])->name('contact');
Route::post('/contacto', [PublicController::class, 'storeContact'])->middleware('throttle:public-forms')->name('contact.store');
Route::get('/legal/{page}', [PublicController::class, 'legal'])->name('legal');
Route::get('/sitemap.xml', [PublicController::class, 'sitemap'])->name('sitemap');
Route::middleware('guest')->group(function () {
    Route::get('/ingresar', [AuthController::class, 'form'])->name('login');
    Route::post('/ingresar', [AuthController::class, 'login'])->middleware('throttle:login')->name('login.store');
});
Route::post('/salir', [AuthController::class, 'logout'])->middleware('auth')->name('logout');
Route::prefix('admin')->name('admin.')->middleware(['auth', 'backoffice'])->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/configuracion', [AdminController::class, 'settings'])->middleware('can:manage')->name('settings');
    Route::put('/configuracion', [AdminController::class, 'saveSettings'])->middleware('can:manage')->name('settings.save');
    Route::get('/cotizaciones/{quote}', [AdminController::class, 'quote'])->middleware('can:manage')->name('quote');
    Route::put('/cotizaciones/{quote}', [AdminController::class, 'updateQuote'])->middleware('can:manage')->name('quote.update');
    Route::get('/adjuntos/{attachment}', [AdminController::class, 'attachment'])->middleware('can:manage')->name('attachment');
    Route::delete('/imagenes/{image}', [AdminController::class, 'deleteImage'])->middleware('can:edit-content')->name('image.delete');
    Route::get('/{module}/crear', [AdminController::class, 'create'])->name('create');
    Route::get('/{module}/{id}/editar', [AdminController::class, 'edit'])->whereNumber('id')->name('edit');
    Route::post('/{module}', [AdminController::class, 'store'])->name('store');
    Route::put('/{module}/{id}', [AdminController::class, 'update'])->whereNumber('id')->name('update');
    Route::delete('/{module}/{id}', [AdminController::class, 'destroy'])->whereNumber('id')->name('destroy');
    Route::get('/{module}', [AdminController::class, 'index'])->name('index');
});
