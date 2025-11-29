<?php

use App\Http\Controllers\MotoristasController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home.dashboard');
});

Route::resource("motoristas", MotoristasController::class);
