<?php

use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\CatalogController;
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

Route::get('/contacto', [AppointmentController::class, 'create'])->name('contacto');
Route::post('/contacto', [AppointmentController::class, 'store'])->name('contacto.store');

/*
|--------------------------------------------------------------------------
| Panel de administración (futuro)
|--------------------------------------------------------------------------
| Cuando se habilite Firebase, envolver estas rutas con ->middleware('firebase.auth').
|
| Route::middleware('firebase.auth')->prefix('admin')->group(function () {
|     // gestión de catálogo, precios y solicitudes de cita
| });
*/
