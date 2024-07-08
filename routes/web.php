<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PruebaController;

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

Route::get('/', [PruebaController::class, 'index'])->name('pruebas.index');
Route::post('/guardar-tri', [PruebaController::class, 'guardarTri'])->name('pruebas.guardarTri');
Route::post('/guardar-duo', [PruebaController::class, 'guardarDuo'])->name('pruebas.guardarDuo');
Route::post('/guardar-orden', [PruebaController::class, 'guardarOrden'])->name('pruebas.guardarOrden');
