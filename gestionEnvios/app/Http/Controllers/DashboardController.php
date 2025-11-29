<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Mostrar dashboard (Livewire)
     */
    public function index()
    {
        $stats = [
            'total_paquetes' => 1248,
            'entregados_hoy' => 856,
            'en_transito' => 289,
            'vehiculos_activos' => 12,
        ];

        return view('livewire.dashboard.dashboard', compact('stats'));
    }
}
