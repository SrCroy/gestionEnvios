<?php

use App\Http\Controllers\ClientesController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home.index');
});

//-- RUTAS CLIENTES --
Route::get('/clientes', [ClientesController::class, 'index'])->name('clientes.index');
Route::get('/clientes/{id}', [ClientesController::class, 'show'])->name('clientes.show');

Route::get('/clientes/{id}/editar', [ClientesController::class, 'edit'])->name('clientes.edit');
Route::put('/clientes/{id}', [ClientesController::class, 'update'])->name('clientes.update');

Route::get('/clientes/create', [ClientesController::class, 'create'])->name('clientes.create');
Route::post('/clientes/create', [ClientesController::class, 'store'])->name('clientes.store');
