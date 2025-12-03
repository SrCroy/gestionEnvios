<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\loginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MotoristasController;
use App\Http\Controllers\VehiculoController;
use App\Http\Controllers\ClientesController;
use App\Http\Controllers\AsignacionesController;
use App\Http\Controllers\ClienteAuthController;
use App\Http\Controllers\ClienteRegistro;
use App\Http\Controllers\PaquetesController;


use App\Livewire\Clientes\EditarPerfil;
use App\Livewire\Clientes\LoginClientes;



Route::get('/', function () {


    if (Auth::guard('web')->check()) {
        if (Auth::user()->rol === 'Motorista') {
            return redirect()->route('asignaciones.index');
        }
        return redirect()->route('dashboard.home');
    }


    if (Auth::guard('cliente')->check()) {
        return redirect()->route('clientes.dashboard');
    }

    return view('welcome');
})->name('home');

Route::get('/login', [loginController::class, 'showLoginForm'])->name('login')->middleware('guest');
Route::post('/login', [loginController::class, 'login'])->name('login.store')->middleware('guest');

Route::post('/logout', [loginController::class, 'logout'])->name('logout');


Route::middleware('auth')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.home');


    Route::get('/motoristas', function () {
        return view('motoristas.MotoristasIndex');
    })->name('motoristas.index');
    Route::get('/vehiculos', function () {
        return view('vehiculos.VehiculosIndex');
    })->name('vehiculos.index');

    Route::get('/clientes', [ClientesController::class, 'index'])->name('clientes.index');
    Route::get('/clientes/create', [ClientesController::class, 'store'])->name('clientes.store');
    Route::post('/clientes/create', [ClientesController::class, 'create'])->name('clientes.create');
    Route::get('/clientes/{id}', [ClientesController::class, 'show'])->name('clientes.show');
    Route::get('/clientes/{id}/editar', [ClientesController::class, 'edit'])->name('clientes.edit');
    Route::put('/clientes/{id}', [ClientesController::class, 'update'])->name('clientes.update');
    Route::delete('/clientes/{id}', [ClientesController::class, 'destroy'])->name('clientes.delete');

    Route::get('/asignar-rutas', function () {
        return view('asignar-rutas.index');
    })->name('asignar.rutas');


    Route::get('/rutas', function () {
        return view('livewire.rutas.rutas');
    })->name('rutas.index');


    Route::get('/asignaciones', [AsignacionesController::class, 'index'])->name('asignaciones.index');
    Route::get('/asignaciones/events', [AsignacionesController::class, 'events'])->name('asignaciones.events');
    Route::post('/asignaciones', [AsignacionesController::class, 'store'])->name('asignaciones.store');
    Route::put('/asignaciones/{id}', [AsignacionesController::class, 'update'])->name('asignaciones.update');
    Route::delete('/asignaciones/{id}', [AsignacionesController::class, 'destroy'])->name('asignaciones.destroy');
});


Route::get('/cliente/login', [ClienteAuthController::class, 'showLoginForm'])->name('cliente.login');
Route::post('/cliente/login', [ClienteAuthController::class, 'login'])->name('cliente.login.store');
Route::get('/cliente/registro', [ClienteRegistro::class, 'showRegisterForm'])->name('cliente.register');
Route::post('/cliente/registro', [ClienteRegistro::class, 'register'])->name('cliente.register.store');


// --- RUTAS DEL PANEL DE CLIENTE ---
Route::middleware('auth:cliente')->group(function () {

    // Dashboard
    Route::get('/cliente/dashboard', function () {
        return view('clientes.dashboard');
    })->name('clientes.dashboard');

    Route::get('/cliente/paquetes', function () {
        return view('paquetes.PaquetesIndex');
    })->name('paquetes.index');
});

Route::middleware('auth:cliente')->group(function () {
    Route::get('/cliente/dashboard', function () {
        return view('clientes.dashboard');
    })->name('clientes.dashboard');

    Route::get('/cliente/paquetes', function () {
        return view('paquetes.PaquetesIndex');
    })->name('paquetes.index');

    Route::view('/cliente/perfil', 'clientes.perfil')->name('cliente.perfil');
    Route::post('/cliente/logout', [ClienteAuthController::class, 'logout'])->name('cliente.logout');
});
