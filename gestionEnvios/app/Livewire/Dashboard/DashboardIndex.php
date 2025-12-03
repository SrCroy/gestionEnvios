<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;
use App\Models\paquetes;
use App\Models\vehiculo;
use Carbon\Carbon;

class DashboardIndex extends Component
{
    public function render()
    {
        // Total de Paquetes Históricos
        $totalPaquetes = paquetes::count();

        // Entregados HOY (Compara fecha de actualización con hoy)
        $entregadosHoy = paquetes::where('estadoActual', 'Entregado')
            ->whereDate('updated_at', Carbon::today())
            ->count();

        // Paquetes En Tránsito (Actualmente en la calle)
        $enTransito = paquetes::where('estadoActual', 'En Tránsito')->count();

        // Vehículos Disponibles (Solo los que están listos para usar)
        $vehiculosActivos = vehiculo::where('estado', 'Disponible')->count(); 

        return view('livewire.dashboard.dashboard-index', [
            'totalPaquetes' => $totalPaquetes,
            'entregadosHoy' => $entregadosHoy,
            'enTransito' => $enTransito,
            'vehiculosActivos' => $vehiculosActivos,
        ]);
    }
}