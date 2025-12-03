<?php

namespace App\Livewire\Paquetes;

use Livewire\Component;
use App\Models\Paquete;
use App\Models\clientes;
use App\Models\historial_envio;
use Illuminate\Support\Facades\Auth;
use Livewire\WithPagination;

class PaquetesTracking extends Component
{
    use WithPagination;

    // Estados de filtro
    public $estadoFiltro = 'todos';
    public $busqueda = '';
    
    // Modal y datos
    public $showModal = false;
    public $showDetalleModal = false;
    public $paqueteSeleccionado = null;
    public $clientes = [];
    
    // Formulario
    public $destinatario_id = '';
    public $remitente_id = '';
    public $descripcion = '';
    public $peso = '';
    public $altura = '';
    public $estadoActual = 'Recoger'; // Estado inicial fijo
    public $comentarios = '';
    
    // Estados para filtros
    protected $queryString = [
        'estadoFiltro' => ['except' => 'todos'],
        'busqueda' => ['except' => ''],
    ];

    // Definir estados disponibles aquí mismo
    private $estadosDisponibles = ['Recoger', 'Almacén', 'Entregar'];

    public function mount()
    {
        $this->clientes = clientes::orderBy('nombre')->get();
    }

    public function render()
    {
         if (!Auth::check()) {
            return redirect()->route('login');
        }


        if (Auth::user()->rol !== 'Administrador') {
            abort(403, 'No tienes permiso para ver esta página.');
        }
        $query = Paquete::with(['destinatario', 'remitente', 'historiales'])
            ->when($this->estadoFiltro != 'todos', function ($q) {
                $q->where('estadoActual', $this->estadoFiltro);
            })
            ->when($this->busqueda, function ($q) {
                $q->where(function ($query) {
                    $query->where('descripcion', 'like', '%'.$this->busqueda.'%')
                        ->orWhereHas('destinatario', function ($q) {
                            $q->where('nombre', 'like', '%'.$this->busqueda.'%');
                        })
                        ->orWhereHas('remitente', function ($q) {
                            $q->where('nombre', 'like', '%'.$this->busqueda.'%');
                        });
                });
            })
            ->orderBy('created_at', 'desc');

        return view('livewire.paquetes.paquetes-tracking', [
            'paquetes' => $query->paginate(10),
            'estados' => $this->estadosDisponibles,
            'contadores' => $this->getContadoresEstados()
        ]);
    }

    public function getContadoresEstados()
    {
        return [
            'todos' => Paquete::count(),
            'recoger' => Paquete::where('estadoActual', 'Recoger')->count(),
            'almacen' => Paquete::where('estadoActual', 'Almacén')->count(),
            'entregar' => Paquete::where('estadoActual', 'Entregar')->count(),
        ];
    }

    public function seleccionarPaquete($id)
    {
        $this->paqueteSeleccionado = Paquete::with(['destinatario', 'remitente', 'historiales.motorista'])
            ->find($id);
        
        // Pre-cargar datos en el formulario
        $this->destinatario_id = $this->paqueteSeleccionado->idDestinatario;
        $this->remitente_id = $this->paqueteSeleccionado->idRemitente;
        $this->descripcion = $this->paqueteSeleccionado->descripcion;
        $this->peso = $this->paqueteSeleccionado->peso;
        $this->altura = $this->paqueteSeleccionado->altura;
        $this->estadoActual = $this->paqueteSeleccionado->estadoActual;
        
        $this->showModal = true;
    }

    public function verDetalle($id)
    {
        $this->paqueteSeleccionado = Paquete::with([
            'destinatario', 
            'remitente', 
            'historiales' => function($query) {
                $query->with('motorista')->orderBy('fechaCambio', 'desc');
            }
        ])->find($id);
        
        $this->showDetalleModal = true;
    }

    public function actualizarPaquete()
    {
        $this->validate([
            'destinatario_id' => 'required|exists:clientes,id',
            'remitente_id' => 'required|exists:clientes,id',
            'descripcion' => 'required|string|max:500',
            'peso' => 'required|numeric|min:0.1',
            'altura' => 'required|numeric|min:0.1',
            'estadoActual' => 'required|in:Recoger,Almacén,Entregar',
            'comentarios' => 'nullable|string|max:1000'
        ]);

        // Guardar cambios del paquete
        $this->paqueteSeleccionado->update([
            'idDestinatario' => $this->destinatario_id,
            'idRemitente' => $this->remitente_id,
            'descripcion' => $this->descripcion,
            'peso' => $this->peso,
            'altura' => $this->altura,
            'estadoActual' => $this->estadoActual
        ]);

        // Si hay comentarios, crear historial
        if ($this->comentarios) {
            historial_envio::create([
                'idPaquete' => $this->paqueteSeleccionado->id,
                'idMotorista' => auth()->id(),
                'estado' => $this->estadoActual,
                'comentarios' => $this->comentarios,
                'fechaCambio' => now()
            ]);
        }

        // Limpiar y cerrar
        $this->reset(['comentarios']);
        $this->showModal = false;
        $this->dispatch('paqueteActualizado');
        
        session()->flash('success', 'Paquete actualizado correctamente');
    }

    public function cambiarEstadoRapido($paqueteId, $nuevoEstado)
    {
        $paquete = Paquete::find($paqueteId);
        $paquete->update(['estadoActual' => $nuevoEstado]);

        // Registrar en historial
        historial_envio::create([
            'idPaquete' => $paqueteId,
            'idMotorista' => auth()->id(),
            'estado' => $nuevoEstado,
            'comentarios' => 'Cambio de estado rápido',
            'fechaCambio' => now()
        ]);

        $this->dispatch('estadoActualizado');
    }

    public function crearNuevoPaquete()
    {
        $this->validate([
            'destinatario_id' => 'required|exists:clientes,id',
            'remitente_id' => 'required|exists:clientes,id',
            'descripcion' => 'required|string|max:500',
            'peso' => 'required|numeric|min:0.1',
            'altura' => 'required|numeric|min:0.1',
        ]);

        $paquete = Paquete::create([
            'idDestinatario' => $this->destinatario_id,
            'idRemitente' => $this->remitente_id,
            'descripcion' => $this->descripcion,
            'peso' => $this->peso,
            'altura' => $this->altura,
            'fechaRegistro' => null,
            'fechaEstimadaEntrega' => null,
            'estadoActual' => 'Recoger'
        ]);

        // Crear primer registro de historial
        historial_envio::create([
            'idPaquete' => $paquete->id,
            'idMotorista' => auth()->id(),
            'estado' => 'Recoger',
            'comentarios' => 'Paquete creado y listo para recoger',
            'fechaCambio' => now()
        ]);

        $this->reset(['destinatario_id', 'remitente_id', 'descripcion', 'peso', 'altura', 'comentarios']);
        $this->showModal = false;
        
        session()->flash('success', 'Paquete creado exitosamente');
    }

    public function reasignarCliente($tipo, $nuevoClienteId)
    {
        if ($tipo === 'destinatario') {
            $this->paqueteSeleccionado->update(['idDestinatario' => $nuevoClienteId]);
        } else {
            $this->paqueteSeleccionado->update(['idRemitente' => $nuevoClienteId]);
        }

        historial_envio::create([
            'idPaquete' => $this->paqueteSeleccionado->id,
            'idMotorista' => auth()->id(),
            'estado' => $this->paqueteSeleccionado->estadoActual,
            'comentarios' => "Cliente $tipo reasignado",
            'fechaCambio' => now()
        ]);

        $this->dispatch('clienteReasignado');
    }
}