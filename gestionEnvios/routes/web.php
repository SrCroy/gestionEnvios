<?php

use App\Http\Controllers\MotoristasController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\loginController;
use App\Http\Controllers\DashboardController;

// Rutas públicas (sin autenticación)
Route::get('/login', [loginController::class, 'showLoginForm'])->name('login')->middleware('guest');
Route::post('/login', [loginController::class, 'login'])->name('login.store')->middleware('guest');

// Rutas protegidas (requieren autenticación)
Route::middleware('auth')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.home');
    Route::post('/logout', [loginController::class, 'logout'])->name('logout');
});

// Reemplaza las rutas resource por esta única ruta
Route::get('/motoristas', App\Livewire\Motoristas\Motoristas::class)->name('motoristas.index');Route::get('/motoristas', function () {
    return view('motoristas.MotoristasIndex');
})->name('motoristas.index');