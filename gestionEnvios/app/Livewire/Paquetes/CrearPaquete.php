<?php

namespace App\Livewire\Paquetes;

use Livewire\Component;
use Livewire\Attributes\On; // Necesario para escuchar eventos
use App\Models\paquetes;
use App\Models\clientes;
use Illuminate\Support\Facades\Auth;

class CrearPaquete extends Component
{
    // Datos del paquete
    public $descripcionContenido = '';
    public $peso = '';
    public $altura = '';
    
    // Destinatario
    public $buscarDestinatario = '';
    public $destinatarioSeleccionado = null;
    public $resultadosBusqueda = []; 
    public $esAutoenvio = false;
    
    // Direcciones
    public $direccionOrigen = '';
    public $direccionDestino = '';
    
    // UI States
    public $step = 1;
    public $loading = false;
    public $verificandoDireccion = false;

    protected $rules = [
        'descripcionContenido' => 'required|min:5|max:200',
        'peso' => 'required|numeric|min:0.1|max:50',
        'altura' => 'required|numeric|min:1|max:200',
        'direccionOrigen' => 'required|min:10',
        'direccionDestino' => 'required|min:10',
    ];

    protected $messages = [
        'descripcionContenido.required' => 'La descripción es obligatoria',
        'peso.required' => 'El peso es obligatorio',
        'altura.required' => 'La altura es obligatoria',
        'direccionOrigen.required' => 'La dirección de origen es obligatoria',
        'direccionDestino.required' => 'La dirección de destino es obligatoria',
    ];

    // Este método se llama cuando disparas el evento 'abrirModalCrear' desde el botón en la vista principal
    #[On('abrirModalCrear')] 
    public function abrirModal()
    {
        $this->reset(); // Limpia todo el formulario
        $this->step = 1;
        $this->mount(); // Recarga datos iniciales (como dirección del usuario)
        $this->resetValidation(); // Limpia errores rojos anteriores
    }

    public function mount()
    {
        $cliente = Auth::guard('cliente')->user();
        if ($cliente && $cliente->direccion) {
            $this->direccionOrigen = $cliente->direccion;
        }
    }

    public function updatedBuscarDestinatario()
    {
        if (strlen($this->buscarDestinatario) < 3) {
            $this->resultadosBusqueda = [];
            return;
        }

        $clienteActualId = Auth::guard('cliente')->id();
        $termino = $this->buscarDestinatario;

        $this->resultadosBusqueda = clientes::query()
            ->where('id', '!=', $clienteActualId)
            ->where(function($query) use ($termino) {
                $query->where('nombre', 'like', '%' . $termino . '%')
                      ->orWhere('email', 'like', '%' . $termino . '%')
                      ->orWhere('telefono', 'like', '%' . $termino . '%');
            })
            ->limit(5)
            ->get();
    }

    public function seleccionarDestinatario($clienteId)
    {
        $this->destinatarioSeleccionado = clientes::find($clienteId);
        
        if ($this->destinatarioSeleccionado) {
            $this->buscarDestinatario = $this->destinatarioSeleccionado->nombre;
            if ($this->destinatarioSeleccionado->direccion) {
                $this->direccionDestino = $this->destinatarioSeleccionado->direccion;
            }
        }
        $this->resultadosBusqueda = [];
    }

    public function updatedEsAutoenvio()
    {   
        if ($this->esAutoenvio) {
            $cliente = Auth::guard('cliente')->user();
            
            $this->destinatarioSeleccionado = $cliente;
            $this->buscarDestinatario = 'Yo mismo (' . $cliente->nombre . ')';
            
            $this->resultadosBusqueda = [];

            if($cliente->direccion) {
                $this->direccionDestino = $cliente->direccion;
            }
        } else {
            $this->destinatarioSeleccionado = null;
            $this->buscarDestinatario = '';
            $this->direccionDestino = '';
        }
    }

    public function verificarDirecciones()
    {
        $this->validate([
            'direccionOrigen' => 'required|min:10',
            'direccionDestino' => 'required|min:10',
        ]);

        $this->verificandoDireccion = true;
        // Simulación
        // sleep(1); 
        $this->verificandoDireccion = false;
        
        $this->dispatch('toast', [
            'message' => 'Direcciones validadas',
            'type' => 'success'
        ]);

        $this->nextStep();
    }

    public function nextStep()
    {
        if ($this->step === 1) {
            $this->validate([
                'descripcionContenido' => 'required|min:5',
                'peso' => 'required|numeric|min:0.1',
                'altura' => 'required|numeric|min:1',
            ]);
        }
        
        if ($this->step === 2) {
            if (!$this->destinatarioSeleccionado) {
                $this->addError('buscarDestinatario', 'Debe seleccionar un destinatario de la lista.');
                return;
            }
        }
        
        $this->step++;
    }

    public function previousStep()
    {
        if ($this->step > 1) {
            $this->step--;
        }
    }

    public function crearPaquete()
    {
        $this->validate();
    $this->loading = true;

    try {
        $paquete = paquetes::create([
            'idRemitente' => Auth::guard('cliente')->id(),
            'idDestinatario' => $this->destinatarioSeleccionado->id,
            
            'descripcion' => $this->descripcionContenido,
            
            'peso' => $this->peso,
            'altura' => $this->altura,
            'fechaRegistro' => now(),
            'fechaEstimadaEntrega' => now()->addDays(2),
            'estadoActual' => 'Recoger'
        ]);

        $this->dispatch('closeModal', 'crearPaqueteModal');
        
        $this->dispatch('toast', [
            'message' => "Paquete #{$paquete->id} registrado correctamente.",
            'type' => 'success'
        ]);
        
        $this->dispatch('paquete-creado');

        } catch (\Exception $e) {
            $this->dispatch('toast', [
                'message' => 'Error al guardar: ' . $e->getMessage(),
                'type' => 'error'
            ]);
        } finally {
            $this->loading = false;
        }
    }

    public function render()
    {
        return view('livewire.paquetes.crear-paquete');
    }
}