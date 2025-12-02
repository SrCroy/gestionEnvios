<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use App\Models\Vehiculo;
use App\Models\Asignacion;

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
        $this->motoristas = User::where('rol', 'motorista')->get();
        $this->vehiculos = Vehiculo::all();
    }

    /** ABRIR MODAL CON NUEVA FECHA */
    public function abrirModal($fecha = null)
    {
        $this->reset(['idAsignacion', 'idMotorista', 'idVehiculo']);

        // Asignar la fecha directamente
        $this->fecha = $fecha;

        // Abrir modal
        $this->dispatch('abrir-modal-show');
    }

    /** ABRIR MODAL PARA EDITAR */
    public function editarAsignacion($id)
    {
        $asig = Asignacion::find($id);
        if (!$asig) return;

        $this->idAsignacion = $asig->id;
        $this->fecha        = $asig->fechaAsignacion->format('Y-m-d');
        $this->idMotorista  = $asig->idMotorista;
        $this->idVehiculo   = $asig->idVehiculo;

        $this->dispatch('abrir-modal-show');
    }

    /** GUARDAR / ACTUALIZAR */
    public function guardar()
    {
        $this->validate([
            'fecha'        => 'required|date',
            'idMotorista'  => 'required|integer',
            'idVehiculo'   => 'required|integer',
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
    }

    /** ELIMINAR */
    public function eliminar()
    {
        if ($this->idAsignacion) {
            Asignacion::where('id', $this->idAsignacion)->delete();
        }

        $this->reset('idAsignacion', 'idMotorista', 'idVehiculo');

        $this->dispatch('cerrar-modal');
    }

    public function render()
    {
        return view('livewire.calendario');
    }
}
