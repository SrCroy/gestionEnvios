<?php

use App\Http\Controllers\MotoristasController;
use App\Http\Controllers\VehiculoController;
use App\Livewire\Clientes\LoginClientes;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\loginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ClientesController;
use App\Http\Controllers\AsignacionesController;
use App\Http\Controllers\ClienteAuthController;
use App\Http\Controllers\ClienteRegistro;

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

    // Motoristas
    Route::get('/motoristas', function () {
        return view('motoristas.MotoristasIndex');
    })->name('motoristas.index');

    // Vehículos
    Route::get('/vehiculos', function () {
        return view('vehiculos.VehiculosIndex');
    })->name('vehiculos.index');

    // Rutas
    Route::get('/rutas', function () {
        return view('livewire.rutas.rutas');
    })->name('rutas.index');

    // Clientes (de la rama quevedo-rama-la-buena-quiero-pensar)
    Route::get('/clientes', [ClientesController::class, 'index'])->name('clientes.index');

    Route::get('/clientes/create', [ClientesController::class, 'store'])->name('clientes.store');
    Route::post('/clientes/create', [ClientesController::class, 'create'])->name('clientes.create');

    Route::get('/clientes/{id}', [ClientesController::class, 'show'])->name('clientes.show');

    Route::get('/clientes/{id}/editar', [ClientesController::class, 'edit'])->name('clientes.edit');
    Route::put('/clientes/{id}', [ClientesController::class, 'update'])->name('clientes.update');

    Route::delete('/clientes/{id}', [ClientesController::class, 'destroy'])->name('clientes.delete');


    // Asignaciones (todas protegidas)
    Route::get('/asignaciones', [AsignacionesController::class, 'index'])->name('asignaciones.index');
    Route::get('/asignaciones/events', [AsignacionesController::class, 'events'])->name('asignaciones.events');
    Route::post('/asignaciones', [AsignacionesController::class, 'store'])->name('asignaciones.store');
    Route::put('/asignaciones/{id}', [AsignacionesController::class, 'update'])->name('asignaciones.update');
    Route::delete('/asignaciones/{id}', [AsignacionesController::class, 'destroy'])->name('asignaciones.destroy');
});
Route::get('/cliente/login', [ClienteAuthController::class, 'showLoginForm'])->name('cliente.login');
Route::post('/cliente/login', [ClienteAuthController::class, 'login'])->name('cliente.login.store');


Route::middleware('auth:cliente')->group(function () {
    Route::get('/cliente/dashboard', function () {
        return view('clientes.dashboard');
    })->name('clientes.dashboard');
    Route::get('/cliente/paquetes', function () {
        return view('clientes.paquete');
    })->name('clientes.paquete');
});

// routes/web.php
Route::get('/cliente/registro', [ClienteRegistro::class, 'showRegisterForm'])->name('cliente.register');
Route::post('/cliente/registro', [ClienteRegistro::class, 'register'])->name('cliente.register.store');

