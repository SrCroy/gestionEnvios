<?php

use App\Http\Controllers\VehiculoController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    /* return view('home.app'); */
    return view('home.dashboard');
});

Route::resource('vehiculos', VehiculoController::class);