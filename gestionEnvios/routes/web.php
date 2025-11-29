<?php

use App\Http\Controllers\VehiculoController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\loginController;
use App\Http\Controllers\DashboardController;

Route::get('/', function () {
    /* return view('home.app'); */
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
});

Route::get('/vehiculos', function () {
    return view('vehiculos.index');
})->name('vehiculos.index');