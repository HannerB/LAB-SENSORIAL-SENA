<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\ConfiguracionController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\MuestraController;
use App\Http\Controllers\CalificacionesController;
use App\Http\Controllers\ResultadosController;
use App\Http\Controllers\PanelistaController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider  and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('index'); // Esta es la vista index.blade.php
})->name('index');

Route::get('login', [ConfiguracionController::class, 'showLoginForm'])->name('login');

// Ruta para procesar el login y autenticación
Route::post('login', [ConfiguracionController::class, 'authenticate'])->name('authenticate');

// Ruta para el panel administrativo
Route::get('admin/resultados', function () {
    if (Session::has('accesoadmin') && Session::get('accesoadmin') === true) {
        // Retornar la vista del panel administrativo
        return view('src.panel_resultados'); // Asegúrate de tener esta vista creada
    } else {
        return redirect()->route('login')->with('alerta', 'Debes iniciar sesión primero.');
    }
})->name('admin.resultados');

Route::get('admin/panel', [ProductoController::class, 'index'])->name('admin.panel');

// Rutas para Configuracion
Route::get('configuracion', [ConfiguracionController::class, 'index'])->name('configuracion.index');
Route::get('configuracion/create', [ConfiguracionController::class, 'create'])->name('configuracion.create');
Route::post('configuracion', [ConfiguracionController::class, 'store'])->name('configuracion.store');
Route::get('configuracion/{configuracion}/edit', [ConfiguracionController::class, 'edit'])->name('configuracion.edit');
Route::put('configuracion/{id}', [ConfiguracionController::class, 'update'])->name('configuracion.update');
Route::delete('configuracion/{configuracion}', [ConfiguracionController::class, 'destroy'])->name('configuracion.destroy');
Route::get('admin/resultados', [ConfiguracionController::class, 'showResultados'])->name('admin.resultados');
Route::get('/', [ConfiguracionController::class, 'formIndex'])->name('index');


// Rutas para Producto
Route::get('producto', [ProductoController::class, 'index'])->name('producto.index');
Route::get('producto/create', [ProductoController::class, 'create'])->name('producto.create');
Route::get('producto/{producto}/edit', [ProductoController::class, 'edit'])->name('producto.edit');
Route::delete('producto/{producto}', [ProductoController::class, 'destroy'])->name('producto.destroy');
Route::post('producto', [ProductoController::class, 'store'])->name('producto.store');
Route::put('productos/{id}', [ProductoController::class, 'update'])->name('productos.update');

// Rutas para Muestra
Route::get('muestra', [MuestraController::class, 'index'])->name('muestra.index');
Route::get('muestra/create', [MuestraController::class, 'create'])->name('muestra.create');
Route::post('muestra', [MuestraController::class, 'store'])->name('muestra.store');
Route::get('muestra/{muestra}/edit', [MuestraController::class, 'edit'])->name('muestra.edit');
Route::put('muestra/{muestra}', [MuestraController::class, 'update'])->name('muestra.update');
Route::delete('muestra/{id}', [MuestraController::class, 'destroy'])->name('muestra.destroy');
Route::get('/muestras/{id}', [MuestraController::class, 'getMuestrasByProducto']);

// Rutas para Calificacion
Route::get('calificacion', [CalificacionesController::class, 'index'])->name('calificacion.index');
Route::get('calificacion/create', [CalificacionesController::class, 'create'])->name('calificacion.create');
Route::post('calificacion', [CalificacionesController::class, 'store'])->name('calificacion.store');
Route::get('calificacion/{calificacion}/edit', [CalificacionesController::class, 'edit'])->name('calificacion.edit');
Route::put('calificacion/{calificacion}', [CalificacionesController::class, 'update'])->name('calificacion.update');
Route::delete('calificacion/{calificacion}', [CalificacionesController::class, 'destroy'])->name('calificacion.destroy');

// Rutas para Resultado
Route::get('/panel/resultados', function () {
    $productoHabilitado = App\Models\Producto::where('habilitado', true)->first();
    return view('panel_resultados', compact('productoHabilitado'));
})->name('panel.resultados');
Route::get('resultado/create', [ResultadosController::class, 'create'])->name('resultado.create');
route::post('resultado/generar', [ResultadosController::class, 'generarResultados'])->name('resultado.generar');
route::get('/mostrar-resultados', [ResultadosController::class, 'mostrarResultados']);
route::get('/mostrar-resultados-panelistas', [ResultadosController::class, 'mostrarResultadosPanelistas']);

// Rutas para Exportar
Route::get('/resultados/exportar', [ResultadosController::class, 'exportar'])->name('resultados.exportar');

// Rutas para Panelista
Route::resource('panelistas', PanelistaController::class);
