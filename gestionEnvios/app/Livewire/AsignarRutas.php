<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Asignacion;
use App\Models\User;
use App\Models\paquetes;
use App\Models\vehiculo;
use App\Models\historial_envio;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AsignarRutas extends Component
{
    public $fechaSeleccionada;
    public $motoristaSeleccionado = null;
    public $paqueteSeleccionado = null;
    public $tipoAccion = 'recoger';
    
    // Listas
    public $fechasConAsignaciones = [];
    public $motoristasDelDia = [];
    public $paquetesDisponibles = [];
    public $asignacionesDelMotorista = [];
    
    // Estadísticas
    public $pesoTotal = 0;
    public $capacidadMaxima = 0;
    public $porcentajeUso = 0;
    
    // Vehículo asignado
    public $vehiculoAsignado = null;

    // Método para forzar recarga
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
        $this->recargarTodo();
    }

    private function cargarFechasConAsignaciones()
    {
        // Obtener fechas DISTINTAS de la tabla asignaciones
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
        $this->dispatch('fecha-cambiada', fecha: $fecha);
    }

    public function updatedFechaSeleccionada()
    {
        $this->actualizarFecha($this->fechaSeleccionada);
    }

    public function seleccionarMotorista($motoristaId)
    {
        try {
            $this->motoristaSeleccionado = $motoristaId;

            // Log para depuración
            logger()->info("Motorista seleccionado: {$motoristaId}");

            $this->cargarDatosMotorista();
            $this->dispatch('motorista-seleccionado', motoristaId: $motoristaId);
        } catch (\Exception $e) {
            // Log del error
            logger()->error("Error al seleccionar motorista: " . $e->getMessage());

            // Mostrar mensaje de error al usuario
            $this->dispatch('toast', ['message' => 'Error al cargar datos del motorista.', 'type' => 'error']);
        }
    }

    private function cargarMotoristasDelDia()
    {
        if (!$this->fechaSeleccionada) {
            $this->motoristasDelDia = [];
            return;
        }

        // Motoristas que tienen asignaciones en esta fecha
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
            $this->asignacionesDelMotorista = collect(); // Asegurarse de que sea una colección vacía
            $this->vehiculoAsignado = null;
            $this->resetEstadisticas();
            return;
        }

        // Cargar asignaciones del motorista para este día
        $this->asignacionesDelMotorista = Asignacion::where('idMotorista', $this->motoristaSeleccionado)
            ->whereDate('fechaAsignacion', $this->fechaSeleccionada)
            ->with(['vehiculo', 'paquete'])
            ->get();

        // Obtener el vehículo asignado
        $asignacion = $this->asignacionesDelMotorista->first();
        if ($asignacion && $asignacion->vehiculo) {
            $this->vehiculoAsignado = $asignacion->vehiculo;
        } else {
            $this->vehiculoAsignado = null;
        }

        // Calcular estadísticas
        $this->calcularEstadisticas();
    }

    private function cargarPaquetesDisponibles()
    {
        // Depuración: Verificar motorista seleccionado y fecha
        logger("Motorista seleccionado: {$this->motoristaSeleccionado}, Fecha seleccionada: {$this->fechaSeleccionada}");

        // Filtrar paquetes con los estados 'Recoger' y 'Entregar'
        $this->paquetesDisponibles = paquetes::whereIn('estadoActual', ['Recoger', 'Entregar'])
            ->whereDoesntHave('asignacion', function ($query) {
                $query->whereDate('fechaAsignacion', $this->fechaSeleccionada);
            })
            ->with(['destinatario', 'remitente'])
            ->get();

        // Depuración: Verificar cuántos paquetes se cargaron
        logger("Paquetes disponibles cargados: " . $this->paquetesDisponibles->count());
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

    public function asignarPaquete()
    {
        // Log para depuración
        logger()->info('Estado de las variables antes de asignar paquete:', [
            'paqueteSeleccionado' => $this->paqueteSeleccionado,
            'vehiculoAsignado' => $this->vehiculoAsignado,
            'porcentajeUso' => $this->porcentajeUso,
        ]);

        try {
            // Validaciones iniciales
            if (!$this->motoristaSeleccionado) {
                $this->dispatch('toast', ['message' => 'Debes seleccionar un motorista', 'type' => 'error']);
                return;
            }

            if (!$this->paqueteSeleccionado) {
                $this->dispatch('toast', ['message' => 'Debes seleccionar un paquete', 'type' => 'error']);
                return;
            }

            if (!$this->vehiculoAsignado) {
                $this->dispatch('toast', ['message' => 'No hay vehículo asignado para este motorista', 'type' => 'error']);
                return;
            }

            // Verificar si el paquete existe
            $paquete = paquetes::find($this->paqueteSeleccionado);
            if (!$paquete) {
                $this->dispatch('toast', ['message' => 'El paquete no existe', 'type' => 'error']);
                return;
            }

            // Verificar capacidad
            $pesoNuevoTotal = $this->pesoTotal + $paquete->peso;
            if ($pesoNuevoTotal > $this->capacidadMaxima) {
                $this->dispatch('toast', ['message' => 'No hay capacidad suficiente. Peso excedido.', 'type' => 'error']);
                return;
            }

            // Manejar paquetes sin dirección
            if (!$paquete->destinatario && !$paquete->remitente) {
                logger()->warning("El paquete seleccionado no tiene dirección asociada.", ['paqueteId' => $paquete->id]);
            }

            // Iniciar transacción
            DB::beginTransaction();

            // Buscar una asignación sin paquete
            $asignacionSinPaquete = Asignacion::where('idMotorista', $this->motoristaSeleccionado)
                ->whereDate('fechaAsignacion', $this->fechaSeleccionada)
                ->whereNull('idPaquete')
                ->first();

            if ($asignacionSinPaquete) {
                // Actualizar la asignación existente
                $asignacionSinPaquete->update(['idPaquete' => $this->paqueteSeleccionado]);
            } else {
                // Crear nueva asignación
                Asignacion::create([
                    'idPaquete' => $this->paqueteSeleccionado,
                    'idMotorista' => $this->motoristaSeleccionado,
                    'idVehiculo' => $this->vehiculoAsignado->id,
                    'fechaAsignacion' => $this->fechaSeleccionada . ' ' . now()->format('H:i:s'),
                ]);
            }

            // Actualizar estado del paquete
            $nuevoEstado = $this->tipoAccion === 'recoger' ? 'En camino' : 'Para entregar';
            $paquete->update(['estadoActual' => $nuevoEstado]);

            // Crear registro en historial
            historial_envio::create([
                'idPaquete' => $this->paqueteSeleccionado,
                'idMotorista' => $this->motoristaSeleccionado,
                'estado' => $nuevoEstado,
                'comentarios' => "Asignado para {$this->tipoAccion} el {$this->fechaSeleccionada}",
                'fechaCambio' => now(),
            ]);

            DB::commit();

            // Recargar datos
            $this->cargarDatosMotorista();
            $this->cargarPaquetesDisponibles();
            $this->cargarMotoristasDelDia();

            // Limpiar selección
            $this->paqueteSeleccionado = null;

            $this->dispatch('toast', ['message' => 'paquetes asignado correctamente', 'type' => 'success']);
            $this->dispatch('actualizar-interfaz');
        } catch (\Exception $e) {
            DB::rollBack();
            logger()->error("Error al asignar paquete: " . $e->getMessage());
            $this->dispatch('toast', ['message' => 'Error al asignar paquete: ' . $e->getMessage(), 'type' => 'error']);
        }
    }

    public function quitarPaquete($asignacionId)
    {
        try {
            DB::beginTransaction();

            $asignacion = Asignacion::with('paquete')->find($asignacionId);
            
            if ($asignacion && $asignacion->idPaquete) {
                // Restaurar estado del paquete
                $paquete = $asignacion->paquete;
                $estadoAnterior = $this->tipoAccion === 'recoger' ? 'Pendiente' : 'En camino';
                $paquete->update(['estadoActual' => $estadoAnterior]);
                
                // Registrar en historial
                historial_envio::create([
                    'idPaquete' => $paquete->id,
                    'idMotorista' => $this->motoristaSeleccionado,
                    'estado' => $estadoAnterior,
                    'comentarios' => "Removido de asignación",
                    'fechaCambio' => now(),
                ]);
                
                // Quitar el paquete (poner idPaquete a NULL)
                $asignacion->update(['idPaquete' => null]);
            }

            DB::commit();

            // Recargar datos
            $this->cargarDatosMotorista();
            $this->cargarPaquetesDisponibles();
            $this->cargarMotoristasDelDia();
            
            $this->dispatch('toast', ['message' => 'paquetes removido correctamente', 'type' => 'success']);
            $this->dispatch('actualizar-interfaz');

        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('toast', ['message' => 'Error al remover paquete: ' . $e->getMessage(), 'type' => 'error']);
        }
    }

    public function cambiarTipoAccion($tipo)
    {
        $this->tipoAccion = $tipo;
        $this->paqueteSeleccionado = null;
        $this->cargarPaquetesDisponibles();
        $this->dispatch('tipo-accion-cambiado', tipo: $tipo);
    }

    private function resetSelecciones()
    {
        $this->motoristaSeleccionado = null;
        $this->paqueteSeleccionado = null;
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

    protected $listeners = ['setPaqueteSeleccionado'];

    public function setPaqueteSeleccionado($paqueteId)
    {
        logger()->info("Evento setPaqueteSeleccionado recibido:", ['paqueteId' => $paqueteId]);
        $this->paqueteSeleccionado = $paqueteId;
    }

    public function asignarTodosLosPaquetes()
    {
        try {
            // Validaciones iniciales
            if (!$this->motoristaSeleccionado) {
                $this->dispatch('toast', ['message' => 'Debes seleccionar un motorista', 'type' => 'error']);
                return;
            }

            if (!$this->vehiculoAsignado) {
                $this->dispatch('toast', ['message' => 'No hay vehículo asignado para este motorista', 'type' => 'error']);
                return;
            }

            // Verificar capacidad del vehículo
            $pesoTotal = $this->pesoTotal;
            foreach ($this->paquetesDisponibles as $paquete) {
                $pesoTotal += $paquete->peso;
                if ($pesoTotal > $this->capacidadMaxima) {
                    $this->dispatch('toast', ['message' => 'No hay capacidad suficiente para todos los paquetes.', 'type' => 'error']);
                    return;
                }
            }

            // Iniciar transacción
            DB::beginTransaction();

            foreach ($this->paquetesDisponibles as $paquete) {
                // Crear asignación para cada paquete
                Asignacion::create([
                    'idPaquete' => $paquete->id,
                    'idMotorista' => $this->motoristaSeleccionado,
                    'idVehiculo' => $this->vehiculoAsignado->id,
                    'fechaAsignacion' => $this->fechaSeleccionada . ' ' . now()->format('H:i:s'),
                ]);

                // Actualizar estado del paquete
                $nuevoEstado = $this->tipoAccion === 'recoger' ? 'En camino' : 'Para entregar';
                $paquete->update(['estadoActual' => $nuevoEstado]);

                // Crear registro en historial
                historial_envio::create([
                    'idPaquete' => $paquete->id,
                    'idMotorista' => $this->motoristaSeleccionado,
                    'estado' => $nuevoEstado,
                    'comentarios' => "Asignado para {$this->tipoAccion} el {$this->fechaSeleccionada}",
                    'fechaCambio' => now(),
                ]);
            }

            DB::commit();

            // Recargar datos
            $this->cargarDatosMotorista();
            $this->cargarPaquetesDisponibles();
            $this->cargarMotoristasDelDia();

            $this->dispatch('toast', ['message' => 'Todos los paquetes fueron asignados correctamente', 'type' => 'success']);
            $this->dispatch('actualizar-interfaz');
        } catch (\Exception $e) {
            DB::rollBack();
            logger()->error("Error al asignar todos los paquetes: " . $e->getMessage());
            $this->dispatch('toast', ['message' => 'Error al asignar todos los paquetes: ' . $e->getMessage(), 'type' => 'error']);
        }
    }

    public function render()
    {
        return view('livewire.asignar-rutas');
    }
}