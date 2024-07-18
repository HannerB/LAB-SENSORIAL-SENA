<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ConfiguracionController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\MuestraController;
use App\Http\Controllers\CalificacionesController;
use App\Http\Controllers\ResultadoController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('index');
});

// ADMIN CONTROLLER 
Route::get('login', [AdminController::class, 'showLoginForm'])->name('login');
Route::post('/admin/authenticate', [AdminController::class, 'authenticate'])->name('admin.authenticate');
Route::get('/admin/panel', function () {
    return view('admin.panel');
})->name('admin.panel');

// Rutas para Configuracion
Route::get('configuracion', [ConfiguracionController::class, 'index'])->name('configuracion.index');
Route::get('configuracion/create', [ConfiguracionController::class, 'create'])->name('configuracion.create');
Route::post('configuracion', [ConfiguracionController::class, 'store'])->name('configuracion.store');
Route::get('configuracion/{configuracion}/edit', [ConfiguracionController::class, 'edit'])->name('configuracion.edit');
Route::put('configuracion/{configuracion}', [ConfiguracionController::class, 'update'])->name('configuracion.update');
Route::delete('configuracion/{configuracion}', [ConfiguracionController::class, 'destroy'])->name('configuracion.destroy');

// Rutas para Producto
Route::get('producto', [ProductoController::class, 'index'])->name('producto.index');
Route::get('producto/create', [ProductoController::class, 'create'])->name('producto.create');
Route::post('producto', [ProductoController::class, 'store'])->name('producto.store');
Route::get('producto/{producto}/edit', [ProductoController::class, 'edit'])->name('producto.edit');
Route::put('producto/{producto}', [ProductoController::class, 'update'])->name('producto.update');
Route::delete('producto/{producto}', [ProductoController::class, 'destroy'])->name('producto.destroy');

// Rutas para Muestra
Route::get('muestra', [MuestraController::class, 'index'])->name('muestra.index');
Route::get('muestra/create', [MuestraController::class, 'create'])->name('muestra.create');
Route::post('muestra', [MuestraController::class, 'store'])->name('muestra.store');
Route::get('muestra/{muestra}/edit', [MuestraController::class, 'edit'])->name('muestra.edit');
Route::put('muestra/{muestra}', [MuestraController::class, 'update'])->name('muestra.update');
Route::delete('muestra/{muestra}', [MuestraController::class, 'destroy'])->name('muestra.destroy');

// Rutas para Calificacion
Route::get('calificacion', [CalificacionesController::class, 'index'])->name('calificacion.index');
Route::get('calificacion/create', [CalificacionesController::class, 'create'])->name('calificacion.create');
Route::post('calificacion', [CalificacionesController::class, 'store'])->name('calificacion.store');
Route::get('calificacion/{calificacion}/edit', [CalificacionesController::class, 'edit'])->name('calificacion.edit');
Route::put('calificacion/{calificacion}', [CalificacionesController::class, 'update'])->name('calificacion.update');
Route::delete('calificacion/{calificacion}', [CalificacionesController::class, 'destroy'])->name('calificacion.destroy');

// Rutas para Resultado
Route::get('resultado', [ResultadoController::class, 'index'])->name('resultado.index');
Route::get('resultado/create', [ResultadoController::class, 'create'])->name('resultado.create');
Route::post('resultado', [ResultadoController::class, 'store'])->name('resultado.store');
Route::get('resultado/{resultado}/edit', [ResultadoController::class, 'edit'])->name('resultado.edit');
Route::put('resultado/{resultado}', [ResultadoController::class, 'update'])->name('resultado.update');
Route::delete('resultado/{resultado}', [ResultadoController::class, 'destroy'])->name('resultado.destroy');
