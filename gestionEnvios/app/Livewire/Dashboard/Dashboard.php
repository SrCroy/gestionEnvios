<?php

namespace App\Livewire\Dashboard;

class Dashboard
{
    public $stats = [
        'total_paquetes' => 1248,
        'entregados_hoy' => 856,
        'en_transito' => 289,
        'vehiculos_activos' => 12,
    ];

    public function render()
    {
        return view('livewire.dashboard.dashboard');
    }
}
