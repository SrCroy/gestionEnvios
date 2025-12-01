<?php

use App\Http\Controllers\MotoristasController;
use App\Http\Controllers\VehiculoController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\loginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ClientesController;

Route::get('/', function () {
    return view('home.dashboard');
});

// Rutas públicas (sin autenticación)
Route::get('/login', [loginController::class, 'showLoginForm'])->name('login')->middleware('guest');
Route::post('/login', [loginController::class, 'login'])->name('login.store')->middleware('guest');

// Rutas protegidas (requieren autenticación)
Route::middleware('auth')->group(function () {

    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.home');

    Route::post('/logout', [loginController::class, 'logout'])->name('logout');

    // Motoristas (tu versión)
    Route::get('/motoristas', function () {
        return view('motoristas.MotoristasIndex');
    })->name('motoristas.index');

    // Vehículos (rama-ivan)
    Route::get('/vehiculos', function () {
        return view('vehiculos.index');
    })->name('vehiculos.index');

    Route::get('/clientes', function () {
        return view('clientes.index');
    })->name('clientes.index');

});

//clientes quevedo rama quevedo

//Route::get('/clientes', [ClientesController::class, 'index'])->name('clientes.index');

Route::get('/clientes/create', [ClientesController::class, 'store'])->name('clientes.store');
Route::post('/clientes/create', [ClientesController::class, 'create'])->name('clientes.create');

Route::get('/clientes/{id}', [ClientesController::class, 'show'])->name('clientes.show');

Route::get('/clientes/{id}/editar', [ClientesController::class, 'edit'])->name('clientes.edit');
Route::put('/clientes/{id}', [ClientesController::class, 'update'])->name('clientes.update');

Route::delete('/clientes/{id}', [ClientesController::class, 'destroy'])->name('clientes.delete');
