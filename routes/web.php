<?php

use App\Http\Controllers\Admin\CatalogNodeController;
use App\Http\Controllers\Admin\ContactLensBrandController;
use App\Http\Controllers\Admin\ContactLensController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\FrameController;
use App\Http\Controllers\Admin\PackageController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\CompanyRequestController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Rutas públicas — JReyes Ópticos
|--------------------------------------------------------------------------
| Sitio de catálogo y referencia de precios. Sin login por ahora.
*/

Route::get('/', [CatalogController::class, 'home'])->name('home');

Route::get('/lentes', [CatalogController::class, 'lentes'])->name('lentes');
Route::get('/marcas', [CatalogController::class, 'marcas'])->name('marcas');
Route::get('/lentes-de-contacto', [CatalogController::class, 'contacto'])->name('lentes-contacto');
Route::get('/paquetes', [CatalogController::class, 'paquetes'])->name('paquetes');

Route::get('/empresa', [CatalogController::class, 'empresa'])->name('empresa');
Route::get('/programas', [CatalogController::class, 'programas'])->name('programas');

Route::get('/contacto', [AppointmentController::class, 'create'])->name('contacto');
Route::post('/contacto', [AppointmentController::class, 'store'])->name('contacto.store');

// Contacto para empresas (convenios / cotizaciones corporativas).
Route::get('/contacto-empresas', [CompanyRequestController::class, 'create'])->name('contacto.empresas');
Route::post('/contacto-empresas', [CompanyRequestController::class, 'store'])->name('contacto.empresas.store');

// Página para compartir: hero + código QR al sitio, pantalla completa,
// sin barra de navegación. Pensada para imprimir/mostrar y que el cliente
// escanee para visitar el sitio.
Route::get('/share', [CatalogController::class, 'share'])->name('share');

/*
|--------------------------------------------------------------------------
| Acceso y panel de administración
|--------------------------------------------------------------------------
| Login nativo de Laravel (sesión). El panel vive bajo /admin y queda
| protegido por el middleware 'auth'. El usuario admin se crea con
| AdminUserSeeder (credenciales en .env).
*/

// Acceso (público).
Route::get('/admin/login', [LoginController::class, 'show'])->name('login');
Route::post('/admin/login', [LoginController::class, 'login'])->name('login.store');
Route::post('/admin/logout', [LoginController::class, 'logout'])->name('logout');

// Panel protegido.
Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Editor del árbol de catálogo.
    Route::get('/catalogo', [CatalogNodeController::class, 'index'])->name('catalogo');
    Route::get('/catalogo/{node}/editar', [CatalogNodeController::class, 'edit'])->name('catalogo.edit');
    Route::put('/catalogo/{node}', [CatalogNodeController::class, 'update'])->name('catalogo.update');

    // Administración de armazones (CRUD).
    Route::get('/armazones', [FrameController::class, 'index'])->name('armazones');
    Route::get('/armazones/nuevo', [FrameController::class, 'create'])->name('armazones.create');
    Route::post('/armazones', [FrameController::class, 'store'])->name('armazones.store');
    Route::get('/armazones/{armazon}/editar', [FrameController::class, 'edit'])->name('armazones.edit');
    Route::put('/armazones/{armazon}', [FrameController::class, 'update'])->name('armazones.update');
    Route::delete('/armazones/{armazon}', [FrameController::class, 'destroy'])->name('armazones.destroy');

    // Ajustes del sitio (contacto, ubicación, horario, redes sociales).
    Route::get('/ajustes', [SettingsController::class, 'edit'])->name('ajustes');
    Route::put('/ajustes', [SettingsController::class, 'update'])->name('ajustes.update');

    // Lentes de contacto: marcas y, dentro de cada marca, sus lentes.
    Route::get('/lentes-contacto', [ContactLensBrandController::class, 'index'])->name('lentes-contacto');
    Route::get('/lentes-contacto/nueva', [ContactLensBrandController::class, 'create'])->name('lentes-contacto.create');
    Route::post('/lentes-contacto', [ContactLensBrandController::class, 'store'])->name('lentes-contacto.store');
    Route::get('/lentes-contacto/{brand}/editar', [ContactLensBrandController::class, 'edit'])->name('lentes-contacto.edit');
    Route::put('/lentes-contacto/{brand}', [ContactLensBrandController::class, 'update'])->name('lentes-contacto.update');
    Route::delete('/lentes-contacto/{brand}', [ContactLensBrandController::class, 'destroy'])->name('lentes-contacto.destroy');

    Route::get('/lentes-contacto/{brand}/lentes', [ContactLensController::class, 'index'])->name('lentes-contacto.lentes');
    Route::get('/lentes-contacto/{brand}/lentes/nuevo', [ContactLensController::class, 'create'])->name('lentes-contacto.lentes.create');
    Route::post('/lentes-contacto/{brand}/lentes', [ContactLensController::class, 'store'])->name('lentes-contacto.lentes.store');
    Route::get('/lentes-contacto/{brand}/lentes/{lente}/editar', [ContactLensController::class, 'edit'])->name('lentes-contacto.lentes.edit');
    Route::put('/lentes-contacto/{brand}/lentes/{lente}', [ContactLensController::class, 'update'])->name('lentes-contacto.lentes.update');
    Route::delete('/lentes-contacto/{brand}/lentes/{lente}', [ContactLensController::class, 'destroy'])->name('lentes-contacto.lentes.destroy');

    // Paquetes "lentes completos".
    Route::get('/paquetes', [PackageController::class, 'index'])->name('paquetes');
    Route::get('/paquetes/nuevo', [PackageController::class, 'create'])->name('paquetes.create');
    Route::post('/paquetes', [PackageController::class, 'store'])->name('paquetes.store');
    Route::get('/paquetes/{paquete}/editar', [PackageController::class, 'edit'])->name('paquetes.edit');
    Route::put('/paquetes/{paquete}', [PackageController::class, 'update'])->name('paquetes.update');
    Route::delete('/paquetes/{paquete}', [PackageController::class, 'destroy'])->name('paquetes.destroy');
});
