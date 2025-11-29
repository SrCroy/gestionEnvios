<?php

use App\Http\Controllers\VehiculoController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home.app');
});

Route::resource('vehiculos', VehiculoController::class);