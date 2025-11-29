<?php

use App\Http\Controllers\VehiculoController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    /* return view('home.app'); */
    return view('home.dashboard');
});

Route::get('/vehiculos', function () {
    return view('vehiculos.index');
})->name('vehiculos.index');