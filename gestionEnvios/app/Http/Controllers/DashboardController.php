<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Mostrar dashboard (Livewire)
     */
    public function index()
    {
         if (!Auth::check()) {
            return redirect()->route('login');
        }


        if (Auth::user()->rol !== 'Administrador') {
            abort(403, 'No tienes permiso para ver esta página.');
        }
        $stats = [
            'total_paquetes' => 1248,
            'entregados_hoy' => 856,
            'en_transito' => 289,
            'vehiculos_activos' => 12,
        ];

        return view('home.dashboard');
    }
}
