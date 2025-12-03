<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Asignacion;
use App\Models\User;
use App\Models\Paquete;
use App\Models\Vehiculo;
use App\Models\historial_envio;
use Illuminate\Support\Facades\DB;

class AsignarRutas extends Component
{
    public $fechaSeleccionada;
    public $motoristaSeleccionado = null;
    public $paquetesSeleccionados = [];
    public $tipoAccion = 'recoger';
    
    public $fechasConAsignaciones = [];
    public $motoristasDelDia = [];
    public $paquetesDisponibles = [];
    public $asignacionesDelMotorista = [];
    
    public $pesoTotal = 0;
    public $capacidadMaxima = 0;
    public $porcentajeUso = 0;
    public $vehiculoAsignado = null;
    public $pesoSeleccionado = 0;

    public function mount()
    {
        $this->cargarFechasConAsignaciones();
        if (!empty($this->fechasConAsignaciones)) {
            $this->fechaSeleccionada = $this->fechasConAsignaciones[0];
            $this->cargarMotoristasDelDia();
        }
    }

    private function cargarFechasConAsignaciones()
    {
        $this->fechasConAsignaciones = Asignacion::selectRaw('DATE(fechaAsignacion) as fecha')
            ->distinct()
            ->orderBy('fecha', 'desc')
            ->pluck('fecha')
            ->toArray();
    }

    public function actualizarFecha($fecha)
    {
        $this->fechaSeleccionada = $fecha;
        $this->resetSelecciones();
        $this->cargarMotoristasDelDia();
    }

    public function updatedFechaSeleccionada()
    {
        $this->actualizarFecha($this->fechaSeleccionada);
    }

    public function seleccionarMotorista($motoristaId)
    {
        $this->motoristaSeleccionado = $motoristaId;
        $this->cargarDatosMotorista();
        $this->cargarPaquetesDisponibles();
    }

    private function cargarMotoristasDelDia()
    {
        if (!$this->fechaSeleccionada) {
            $this->motoristasDelDia = [];
            return;
        }

        $this->motoristasDelDia = User::where('rol', 'Motorista')
            ->whereHas('asignaciones', function ($query) {
                $query->whereDate('fechaAsignacion', $this->fechaSeleccionada);
            })
            ->withCount(['asignaciones' => function ($query) {
                $query->whereDate('fechaAsignacion', $this->fechaSeleccionada)
                      ->whereNotNull('idPaquete');
            }])
            ->get(['id', 'name', 'telefono']);
    }

    private function cargarDatosMotorista()
    {
        if (!$this->motoristaSeleccionado) {
            $this->asignacionesDelMotorista = collect();
            $this->vehiculoAsignado = null;
            $this->resetEstadisticas();
            return;
        }

        $this->asignacionesDelMotorista = Asignacion::where('idMotorista', $this->motoristaSeleccionado)
            ->whereDate('fechaAsignacion', $this->fechaSeleccionada)
            ->with(['vehiculo', 'paquete.destinatario', 'paquete.remitente'])
            ->get();

        $asignacionConVehiculo = $this->asignacionesDelMotorista->first(function ($asignacion) {
            return $asignacion->vehiculo !== null;
        });

        if ($asignacionConVehiculo && $asignacionConVehiculo->vehiculo) {
            $this->vehiculoAsignado = $asignacionConVehiculo->vehiculo;
            $this->capacidadMaxima = $asignacionConVehiculo->vehiculo->pesoMaximo;
        } else {
            $this->vehiculoAsignado = null;
            $this->capacidadMaxima = 0;
        }

        $this->calcularEstadisticas();
    }

    private function cargarPaquetesDisponibles()
    {
        $estadoBuscado = $this->tipoAccion === 'recoger' ? 'Recoger' : 'Entregar';
        
        // Paquetes que YA están asignados en esta fecha
        $paquetesYaAsignadosEnEstaFecha = Asignacion::whereDate('fechaAsignacion', $this->fechaSeleccionada)
            ->whereNotNull('idPaquete')
            ->pluck('idPaquete')
            ->toArray();
        
        // Paquetes disponibles: sin vehículo y no asignados en esta fecha
        $this->paquetesDisponibles = Paquete::where('estadoActual', $estadoBuscado)
            ->whereNull('idVehiculo')
            ->when(!empty($paquetesYaAsignadosEnEstaFecha), function ($query) use ($paquetesYaAsignadosEnEstaFecha) {
                return $query->whereNotIn('id', $paquetesYaAsignadosEnEstaFecha);
            })
            ->with(['destinatario', 'remitente'])
            ->get();
            
        $this->actualizarPesoSeleccionado();
    }

    private function calcularEstadisticas()
    {
        $this->pesoTotal = $this->asignacionesDelMotorista
            ->whereNotNull('idPaquete')
            ->sum(function ($asignacion) {
                return $asignacion->paquete->peso ?? 0;
            });
        
        if ($this->capacidadMaxima > 0) {
            $this->porcentajeUso = min(100, round(($this->pesoTotal / $this->capacidadMaxima) * 100, 2));
        } else {
            $this->porcentajeUso = 0;
        }
    }

    public function updatedPaquetesSeleccionados()
    {
        $this->actualizarPesoSeleccionado();
    }

    private function actualizarPesoSeleccionado()
    {
        $this->pesoSeleccionado = 0;
        foreach ($this->paquetesSeleccionados as $paqueteId) {
            $paquete = Paquete::find($paqueteId);
            if ($paquete) {
                $this->pesoSeleccionado += $paquete->peso;
            }
        }
    }

    public function asignarPaquetes()
    {
        try {
            if (!$this->motoristaSeleccionado) {
                $this->dispatch('toast', ['message' => 'Debes seleccionar un motorista', 'type' => 'error']);
                return;
            }

            if (empty($this->paquetesSeleccionados)) {
                $this->dispatch('toast', ['message' => 'Debes seleccionar al menos un paquete', 'type' => 'error']);
                return;
            }

            if (!$this->vehiculoAsignado) {
                $this->dispatch('toast', ['message' => 'No hay vehículo asignado para este motorista', 'type' => 'error']);
                return;
            }

            // Verificar capacidad
            if ($this->pesoSeleccionado + $this->pesoTotal > $this->capacidadMaxima) {
                $this->dispatch('toast', ['message' => 'No hay capacidad suficiente en el vehículo', 'type' => 'error']);
                return;
            }

            DB::beginTransaction();

            foreach ($this->paquetesSeleccionados as $paqueteId) {
                $paquete = Paquete::find($paqueteId);
                if (!$paquete) continue;

                // Buscar si ya existe una asignación vacía para este motorista en esta fecha
                $asignacionExistente = Asignacion::where('idMotorista', $this->motoristaSeleccionado)
                    ->whereDate('fechaAsignacion', $this->fechaSeleccionada)
                    ->whereNull('idPaquete')
                    ->first();

                if ($asignacionExistente) {
                    // Usar asignación existente
                    $asignacionExistente->update([
                        'idPaquete' => $paqueteId,
                        'idVehiculo' => $this->vehiculoAsignado->id,
                        'fechaAsignacion' => now(),
                    ]);
                } else {
                    // Crear nueva asignación
                    Asignacion::create([
                        'idPaquete' => $paqueteId,
                        'idMotorista' => $this->motoristaSeleccionado,
                        'idVehiculo' => $this->vehiculoAsignado->id,
                        'fechaAsignacion' => now(),
                    ]);
                }

                // Actualizar estado del paquete
                $nuevoEstado = $this->tipoAccion === 'recoger' ? 'En camino' : 'Para entregar';
                $paquete->update([
                    'estadoActual' => $nuevoEstado,
                    'idVehiculo' => $this->vehiculoAsignado->id,
                ]);

                // Registrar en historial
                historial_envio::create([
                    'idPaquete' => $paqueteId,
                    'idMotorista' => $this->motoristaSeleccionado,
                    'estado' => $nuevoEstado,
                    'comentarios' => "Asignado para {$this->tipoAccion}",
                    'fechaCambio' => now(),
                ]);
            }

            DB::commit();

            // Recargar datos
            $this->cargarDatosMotorista();
            $this->cargarPaquetesDisponibles();
            $this->cargarMotoristasDelDia();

            // Limpiar selección
            $this->paquetesSeleccionados = [];
            $this->pesoSeleccionado = 0;

            $this->dispatch('toast', ['message' => '✅ Paquetes asignados correctamente', 'type' => 'success']);
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('toast', ['message' => '❌ Error: ' . $e->getMessage(), 'type' => 'error']);
        }
    }

    public function quitarPaquete($asignacionId)
    {
        try {
            DB::beginTransaction();

            $asignacion = Asignacion::with('paquete')->find($asignacionId);
            
            if ($asignacion && $asignacion->idPaquete) {
                $paquete = $asignacion->paquete;
                $estadoAnterior = $this->tipoAccion === 'recoger' ? 'Recoger' : 'Entregar';
                
                // Poner idPaquete a NULL (mantener la asignación)
                $asignacion->update(['idPaquete' => null]);
                
                $paquete->update([
                    'estadoActual' => $estadoAnterior,
                    'idVehiculo' => null
                ]);
                
                historial_envio::create([
                    'idPaquete' => $paquete->id,
                    'idMotorista' => $this->motoristaSeleccionado,
                    'estado' => $estadoAnterior,
                    'comentarios' => "Removido de asignación",
                    'fechaCambio' => now(),
                ]);
            }

            DB::commit();

            $this->cargarDatosMotorista();
            $this->cargarPaquetesDisponibles();
            $this->cargarMotoristasDelDia();
            
            $this->dispatch('toast', ['message' => '✅ Paquete removido correctamente', 'type' => 'success']);
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('toast', ['message' => '❌ Error: ' . $e->getMessage(), 'type' => 'error']);
        }
    }

    public function cambiarTipoAccion($tipo)
    {
        $this->tipoAccion = $tipo;
        $this->paquetesSeleccionados = [];
        $this->pesoSeleccionado = 0;
        $this->cargarPaquetesDisponibles();
    }

    private function resetSelecciones()
    {
        $this->motoristaSeleccionado = null;
        $this->paquetesSeleccionados = [];
        $this->pesoSeleccionado = 0;
        $this->resetDatosMotorista();
    }

    private function resetDatosMotorista()
    {
        $this->vehiculoAsignado = null;
        $this->asignacionesDelMotorista = [];
        $this->paquetesDisponibles = [];
        $this->resetEstadisticas();
    }

    private function resetEstadisticas()
    {
        $this->pesoTotal = 0;
        $this->porcentajeUso = 0;
        $this->capacidadMaxima = 0;
    }

    public function render()
    {
        return view('livewire.asignar-rutas');
    }
}