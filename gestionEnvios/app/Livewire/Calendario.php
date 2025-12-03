<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use App\Models\Vehiculo;
use App\Models\Asignacion;
use Illuminate\Support\Facades\Auth; // <--- 1. IMPORTANTE: Importar Auth

class Calendario extends Component
{
    public $fecha;
    public $idMotorista;
    public $idVehiculo;
    public $idAsignacion;

    public $motoristas = [];
    public $vehiculos = [];

    protected $listeners = [
        'abrirModal' => 'abrirModal',
        'editarAsignacion' => 'editarAsignacion',
    ];

    public function mount()
    {
        // Cargar listas solo si es necesario (Opcional: podrías filtrar aquí también)
        $this->motoristas = User::where('rol', 'motorista')->get();
        $this->vehiculos = Vehiculo::all();
    }

    /** ABRIR MODAL CON NUEVA FECHA (CREAR) */
    public function abrirModal($fecha = null)
    {
        // SEGURIDAD: Si es motorista, no le dejamos abrir el modal para CREAR
        if (Auth::user()->rol !== 'admin') {
            $this->dispatch('toast', ['message' => 'No tienes permiso para crear asignaciones.', 'type' => 'error']);
            return;
        }

        $this->reset(['idAsignacion', 'idMotorista', 'idVehiculo']);
        $this->fecha = $fecha;
        $this->dispatch('abrir-modal-show');
    }

    /** ABRIR MODAL PARA EDITAR (VER DETALLES) */
    public function editarAsignacion($id)
    {
        $asig = Asignacion::find($id);
        if (!$asig) return;

        // Opcional: Si es motorista, cargamos los datos pero quizás quieras
        // bloquear la edición en la vista o aquí mismo.
        // Por ahora permitimos que se abra para que pueda VER el detalle,
        // pero el método 'guardar' lo detendrá si intenta cambiar algo.

        $this->idAsignacion = $asig->id;
        $this->fecha        = $asig->fechaAsignacion->format('Y-m-d');
        $this->idMotorista  = $asig->idMotorista;
        $this->idVehiculo   = $asig->idVehiculo;

        $this->dispatch('abrir-modal-show');
    }

    /** GUARDAR / ACTUALIZAR */
    public function guardar()
    {
        // ---------------------------------------------------------
        // SEGURIDAD: BLOQUEO CRÍTICO
        // ---------------------------------------------------------
        if (Auth::user()->rol !== 'admin') {
            // Enviamos una alerta y detenemos la ejecución
            $this->dispatch('toast', [
                'message' => 'Acceso denegado. Solo administradores pueden guardar.',
                'type' => 'error'
            ]);
            return;
        }

        $this->validate([
            'fecha'       => 'required|date',
            'idMotorista' => 'required|integer',
            'idVehiculo'  => 'required|integer',
        ]);

        $asig = $this->idAsignacion
            ? Asignacion::find($this->idAsignacion)
            : new Asignacion();

        $asig->fechaAsignacion = $this->fecha;
        $asig->idMotorista     = $this->idMotorista;
        $asig->idVehiculo      = $this->idVehiculo;
        $asig->save();

        $this->idAsignacion = $asig->id;

        $this->dispatch('cerrar-modal');
        $this->dispatch('toast', ['message' => 'Asignación guardada.', 'type' => 'success']);
        
        // Emitir evento para recargar el calendario en el frontend si es necesario
        $this->dispatch('recargar-calendario'); 
    }

    /** ELIMINAR */
    public function eliminar()
    {
        // ---------------------------------------------------------
        // SEGURIDAD: BLOQUEO CRÍTICO
        // ---------------------------------------------------------
        if (Auth::user()->rol !== 'admin') {
            $this->dispatch('toast', [
                'message' => 'No tienes permiso para eliminar.',
                'type' => 'error'
            ]);
            return;
        }

        if ($this->idAsignacion) {
            Asignacion::where('id', $this->idAsignacion)->delete();
        }

        $this->reset('idAsignacion', 'idMotorista', 'idVehiculo');
        $this->dispatch('cerrar-modal');
        $this->dispatch('toast', ['message' => 'Asignación eliminada.', 'type' => 'success']);
        $this->dispatch('recargar-calendario');
    }

    public function render()
    {
        return view('livewire.calendario');
    }
}