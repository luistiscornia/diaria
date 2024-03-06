<?php

use App\Http\Livewire\SeleccionPuesto;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HistorialController;



/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// INICIO
Route::get('/', [\App\Http\Controllers\Auth\LoginController::class, 'viewLogin']);

//desabilitando registro
Auth::routes(["register" => false,'reset' => false]);


Route::get('/home', [\App\Http\Controllers\HomeController::class, 'index'])->name('home');


Route::post('/home', [\App\Http\Controllers\HomeController::class, 'storeSector']);


Route::post('/home/seleccion-puesto', [\App\Http\Controllers\HomeController::class, 'seleccionPuesto'])->name('seleccion-puesto');

Route::get('/mostrarHistorial', 'HistorialController@mostrarHistorial');

Route::get('/historial', [HistorialController::class, 'mostrarHistorial']);



//login
Route::middleware(['auth', 'verified'])->group(function () {
});

