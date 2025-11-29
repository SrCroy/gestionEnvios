<?php

use App\Http\Controllers\MotoristasController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home.dashboard');
});

// Reemplaza las rutas resource por esta única ruta
Route::get('/motoristas', App\Livewire\Motoristas\Motoristas::class)->name('motoristas.index');Route::get('/motoristas', function () {
    return view('motoristas.MotoristasIndex');
})->name('motoristas.index');