<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use App\Models\Vehiculo;
use App\Models\Asignacion;
use Illuminate\Support\Facades\Auth;

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
    
        $this->motoristas = User::where('rol', 'Motorista')->get(); // Asegúrate de la mayúscula aquí también si aplica
        $this->vehiculos = Vehiculo::all();
    }

 
    public function abrirModal($fecha = null)
    {
        if (Auth::user()->rol !== 'Administrador') {
            $this->dispatch('toast', [
                'message' => 'No tienes permiso para crear asignaciones.', 
                'type' => 'error'
            ]);
            return;
        }

       
        if ($fecha && $fecha < now()->format('Y-m-d')) {
            $this->dispatch('toast', [
                'message' => 'No puedes generar asignaciones en fechas pasadas.', 
                'type' => 'error'
            ]);
            return;
        }
        // --------------------------------------------------

        $this->reset(['idAsignacion', 'idMotorista', 'idVehiculo']);
        
        $this->fecha = $fecha ?? now()->format('Y-m-d');
        
        $this->dispatch('abrir-modal-show');
    }

   
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


   public function guardar()
    {
        if (Auth::user()->rol !== 'Administrador') {
            $this->dispatch('toast', [
                'message' => 'Acceso denegado. Solo administradores pueden guardar.',
                'type' => 'error'
            ]);
            return;
        }

  
        $rules = [
            'idMotorista' => 'required|integer',
            'idVehiculo'  => 'required|integer',
        ];

     
        if (!$this->idAsignacion) {
            $rules['fecha'] = 'required|date|after_or_equal:today'; 
        } else {
           
            $rules['fecha'] = 'required|date';
        }
        
        $messages = [
            'fecha.after_or_equal' => 'No se pueden generar asignaciones en fechas pasadas.',
        ];

        $this->validate($rules, $messages);
        // ----------------------------------

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
        
        $this->dispatch('recargar-calendario'); 
    }

    /** ELIMINAR */
    public function eliminar()
    {
        if (Auth::user()->rol !== 'Administrador') {
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