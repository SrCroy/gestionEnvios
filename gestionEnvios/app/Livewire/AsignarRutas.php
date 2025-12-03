<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Asignacion;
use App\Models\User;
use App\Models\Paquete;
use App\Models\Vehiculo;
use App\Models\historial_envio;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;

class AsignarRutas extends Component
{
    public $fechaSeleccionada;
    public $motoristaSeleccionado = null;
    public $paquetesSeleccionados = [];
    public $tipoAccion = 'recoger';
    
    // Variables de data
    public $fechasConAsignaciones = [];
    public $motoristasDelDia; // Inicializado en mount
    public $paquetesDisponibles; // Inicializado en mount
    public $asignacionesDelMotorista; // Inicializado en mount
    
    // Variables de estadísticas
    public $pesoTotal = 0.0;
    public $capacidadMaxima = 0.0;
    public $porcentajeUso = 0.0;
    public $vehiculoAsignado = null;
    
    // FIX: Variable que faltaba y causaba el error ErrorException
    public $pesoSeleccionado = 0.0;

    // Método para forzar recarga (Asegúrate de que este método maneje la lógica de inicialización)
    public function recargarTodo()
    {
         if (!Auth::check()) {
             return redirect()->route('login');
         }

        if (Auth::user()->rol !== 'Administrador') {
            abort(403, 'No tienes permiso para ver esta página.');
        }
        $this->cargarFechasConAsignaciones();
        if (!empty($this->fechasConAsignaciones)) {
            if (!$this->fechaSeleccionada || !in_array($this->fechaSeleccionada, $this->fechasConAsignaciones)) {
                $this->fechaSeleccionada = $this->fechasConAsignaciones[0];
            }
            $this->cargarMotoristasDelDia();
            if ($this->motoristaSeleccionado) {
                $this->cargarDatosMotorista();
            }
        }
    }

    public function mount()
    {
        $this->cargarFechasConAsignaciones();
        if (!empty($this->fechasConAsignaciones)) {
            $this->fechaSeleccionada = $this->fechasConAsignaciones[0];
        } else {
            // Si no hay fechas con asignaciones, establece la fecha de hoy
            $this->fechaSeleccionada = now()->toDateString();
        }
        // Inicialización para evitar errores de tipo si las propiedades no son de tipo Collection::class
        $this->motoristasDelDia = new Collection();
        $this->paquetesDisponibles = new Collection();
        $this->asignacionesDelMotorista = new Collection();
        
        $this->cargarMotoristasDelDia();
    }

    private function cargarFechasConAsignaciones()
    {
        // Incluye la fecha de hoy si hay motoristas con asignaciones
        $fechas = Asignacion::selectRaw('DATE(fechaAsignacion) as fecha')
            ->distinct()
            ->orderBy('fecha', 'desc')
            ->pluck('fecha')
            ->toArray();
        
        $today = now()->toDateString();
        if (!in_array($today, $fechas)) {
            array_unshift($fechas, $today);
        }
        
        $this->fechasConAsignaciones = $fechas;
    }

    public function actualizarFecha($fecha)
    {
        $this->fechaSeleccionada = $fecha;
        $this->resetSelecciones();
        $this->cargarMotoristasDelDia();
        $this->cargarPaquetesDisponibles();
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
            $this->motoristasDelDia = new Collection();
            return;
        }

        // Buscar motoristas que tengan asignaciones (con o sin paquete, pero sí vehículo) O que tengan un vehículo asignado directamente
        // NOTA: Para simplificar, aquí buscamos motoristas que ya tienen alguna asignación.
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
            $this->asignacionesDelMotorista = new Collection();
            $this->vehiculoAsignado = null;
            $this->resetEstadisticas();
            return;
        }

        $this->asignacionesDelMotorista = Asignacion::where('idMotorista', $this->motoristaSeleccionado)
            ->whereDate('fechaAsignacion', $this->fechaSeleccionada)
            ->with(['vehiculo', 'paquete.destinatario', 'paquete.remitente'])
            ->get();

        // **Lógica Clave para el Vehículo**
        // Intenta obtener el vehículo de la primera asignación no nula
        $asignacionConVehiculo = $this->asignacionesDelMotorista->first(function ($asignacion) {
            return $asignacion->idVehiculo !== null && $asignacion->vehiculo !== null;
        });

        if ($asignacionConVehiculo) {
            $this->vehiculoAsignado = $asignacionConVehiculo->vehiculo;
            $this->capacidadMaxima = $this->vehiculoAsignado->pesoMaximo ?? 0.0;
        } else {
            $this->vehiculoAsignado = null;
            $this->capacidadMaxima = 0.0;
        }

        $this->calcularEstadisticas();
    }

    private function cargarPaquetesDisponibles()
    {
        if (!$this->motoristaSeleccionado) {
            $this->paquetesDisponibles = new Collection();
            return;
        }

        $estadoBuscado = $this->tipoAccion === 'recoger' ? 'Recoger' : 'Entregar';
        
        // Paquetes que YA están asignados a CUALQUIER motorista en esta fecha
        $paquetesYaAsignadosEnEstaFecha = Asignacion::whereDate('fechaAsignacion', $this->fechaSeleccionada)
            ->whereNotNull('idPaquete')
            ->pluck('idPaquete')
            ->toArray();
        
        // Paquetes disponibles: en el estado correcto y no asignados en esta fecha
        $this->paquetesDisponibles = Paquete::where('estadoActual', $estadoBuscado)
            ->when(!empty($paquetesYaAsignadosEnEstaFecha), function ($query) use ($paquetesYaAsignadosEnEstaFecha) {
                return $query->whereNotIn('id', $paquetesYaAsignadosEnEstaFecha);
            })
            ->with(['destinatario', 'remitente'])
            ->get();
            
        $this->paquetesSeleccionados = []; // Limpiar la selección al recargar
        $this->actualizarPesoSeleccionado();
    }

    private function calcularEstadisticas()
    {
        $this->pesoTotal = $this->asignacionesDelMotorista
            ->whereNotNull('idPaquete')
            ->sum(function ($asignacion) {
                return $asignacion->paquete->peso ?? 0.0;
            });
        
        if ($this->capacidadMaxima > 0) {
            $this->porcentajeUso = min(100, round(($this->pesoTotal / $this->capacidadMaxima) * 100, 2));
        } else {
            $this->porcentajeUso = 0.0;
        }
    }

    public function updatedPaquetesSeleccionados()
    {
        $this->actualizarPesoSeleccionado();
    }

    private function actualizarPesoSeleccionado()
    {
        $this->pesoSeleccionado = 0.0;
        $paqueteIds = $this->paquetesSeleccionados;

        if (empty($paqueteIds)) {
            return;
        }
        
        // Cargar los pesos de todos los paquetes seleccionados en una sola consulta
        $pesoData = Paquete::whereIn('id', $paqueteIds)
                         ->sum('peso');

        $this->pesoSeleccionado = $pesoData;

        // Si la capacidad es 0 (no hay vehículo), forzar un mensaje de error si se seleccionó algo
        if ($this->capacidadMaxima == 0 && $this->pesoSeleccionado > 0) {
            // Esto forzará el mensaje de capacidad excedida.
        }
    }

    public function asignarPaquetes()
    {
        // Re-validación estricta para asegurar que el botón no fue presionado por accidente
        if (!$this->motoristaSeleccionado || empty($this->paquetesSeleccionados) || !$this->vehiculoAsignado || ($this->pesoSeleccionado + $this->pesoTotal > $this->capacidadMaxima)) {
            $this->dispatch('toast', ['message' => '❌ Error de validación: Revisa motorista, paquetes y capacidad.', 'type' => 'error']);
            return;
        }

        try {
            DB::beginTransaction();

            $idMotorista = $this->motoristaSeleccionado;
            $idVehiculo = $this->vehiculoAsignado->id;
            $fecha = $this->fechaSeleccionada;
            $nuevoEstado = $this->tipoAccion === 'recoger' ? 'En camino' : 'Para entregar';

            foreach ($this->paquetesSeleccionados as $paqueteId) {
                $paquete = Paquete::find($paqueteId);
                if (!$paquete) continue;

                // 1. Crear o actualizar la Asignación
                Asignacion::updateOrCreate(
                    [
                        'idMotorista' => $idMotorista,
                        'fechaAsignacion' => $fecha,
                        'idPaquete' => null // Buscar una ranura vacía
                    ],
                    [
                        'idPaquete' => $paqueteId,
                        'idVehiculo' => $idVehiculo,
                    ]
                );

                // 2. Actualizar estado del paquete
                $paquete->update([
                    'estadoActual' => $nuevoEstado,
                    'idVehiculo' => $idVehiculo,
                ]);

                // 3. Registrar en historial
                historial_envio::create([
                    'idPaquete' => $paqueteId,
                    'idMotorista' => $idMotorista,
                    'estado' => $nuevoEstado,
                    'comentarios' => "Asignado para {$this->tipoAccion} en vehículo {$idVehiculo}",
                    'fechaCambio' => now(),
                ]);
            }

            DB::commit();

            // Recargar datos y limpiar
            $this->cargarDatosMotorista();
            $this->cargarPaquetesDisponibles();
            $this->cargarMotoristasDelDia();

            $this->paquetesSeleccionados = [];
            $this->pesoSeleccionado = 0.0;

            $this->dispatch('toast', ['message' => '✅ Paquetes asignados correctamente', 'type' => 'success']);
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('toast', ['message' => '❌ Error al asignar: ' . $e->getMessage(), 'type' => 'error']);
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
                
                // Actualizar paquete
                $paquete->update([
                    'estadoActual' => $estadoAnterior,
                    'idVehiculo' => null
                ]);
                
                // Poner idPaquete a NULL en la asignación
                $asignacion->update(['idPaquete' => null]);
                
                // Registrar en historial
                historial_envio::create([
                    'idPaquete' => $paquete->id,
                    'idMotorista' => $this->motoristaSeleccionado,
                    'estado' => $estadoAnterior,
                    'comentarios' => "Removido de asignación del motorista",
                    'fechaCambio' => now(),
                ]);
            } else {
                // Si la asignación es vacía, la eliminamos para limpiar
                $asignacion->delete();
            }

            DB::commit();

            $this->cargarDatosMotorista();
            $this->cargarPaquetesDisponibles();
            $this->cargarMotoristasDelDia();
            
            $this->dispatch('toast', ['message' => '✅ Paquete removido correctamente', 'type' => 'success']);
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('toast', ['message' => '❌ Error al remover: ' . $e->getMessage(), 'type' => 'error']);
        }
    }

    public function cambiarTipoAccion($tipo)
    {
        $this->tipoAccion = $tipo;
        $this->paquetesSeleccionados = [];
        $this->pesoSeleccionado = 0.0;
        $this->cargarPaquetesDisponibles();
    }

    public function resetSelecciones()
    {
        $this->motoristaSeleccionado = null;
        $this->paquetesSeleccionados = [];
        $this->pesoSeleccionado = 0.0;
        $this->resetDatosMotorista();
    }

    private function resetDatosMotorista()
    {
        $this->vehiculoAsignado = null;
        $this->asignacionesDelMotorista = new Collection();
        $this->paquetesDisponibles = new Collection();
        $this->resetEstadisticas();
    }

    private function resetEstadisticas()
    {
        $this->pesoTotal = 0.0;
        $this->porcentajeUso = 0.0;
        $this->capacidadMaxima = 0.0;
    }

    public function render()
    {
        return view('livewire.asignar-rutas');
    }
}