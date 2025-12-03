<?php

namespace App\Livewire\Vehiculos;

use App\Models\vehiculo;
use Livewire\Component;

class VehiculosIndex extends Component
{
    // Propiedades del formulario
    public $vehiculoId;
    public $marca = '';
    public $modelo = '';
    public $pesoMaximo = '';
    public $volumenMaximo = '';
    public $estado = '';
    
    // Control de modales
    public $vehiculoToDelete;
    
    // Filtro
    public $filtroEstado = 'todos';
    
    // Reglas de validación
    protected function rules()
    {
        return [
            'marca' => 'required|string|max:100',
            'modelo' => 'required|string|max:100',
            'pesoMaximo' => 'required|numeric|min:0|max:99999999.99',
            'volumenMaximo' => 'required|numeric|min:0|max:99999999.99',
            'estado' => 'required|in:' . implode(',', vehiculo::getEstados())
        ];
    }
    
    protected $messages = [
        'marca.required' => 'La marca es obligatoria',
        'marca.max' => 'La marca no puede exceder 100 caracteres',
        'modelo.required' => 'El modelo es obligatorio',
        'modelo.max' => 'El modelo no puede exceder 100 caracteres',
        'pesoMaximo.required' => 'El peso máximo es obligatorio',
        'pesoMaximo.numeric' => 'El peso máximo debe ser un número',
        'pesoMaximo.min' => 'El peso máximo debe ser mayor o igual a 0',
        'volumenMaximo.required' => 'El volumen máximo es obligatorio',
        'volumenMaximo.numeric' => 'El volumen máximo debe ser un número',
        'volumenMaximo.min' => 'El volumen máximo debe ser mayor o igual a 0',
        'estado.required' => 'Debe seleccionar un estado válido', // Mensaje para el select vacío
        'estado.in' => 'El estado seleccionado no es válido'
    ];
    
    public function create()
    {
        $this->resetForm();
        $this->dispatch('openModal', 'createModal');
    }
    
    public function store()
    {
        $this->validate([
            'marca' => 'required|string|max:100',
            'modelo' => 'required|string|max:100',
            'pesoMaximo' => 'required|numeric|min:0',
            'volumenMaximo' => 'required|numeric|min:0',
        ]);
        
        vehiculo::create([
            'marca' => $this->marca,
            'modelo' => $this->modelo,
            'pesoMaximo' => $this->pesoMaximo,
            'volumenMaximo' => $this->volumenMaximo,
            'estado' => vehiculo::ESTADO_DISPONIBLE
        ]);
        
        $this->dispatch('closeModal', 'createModal');
        $this->resetForm();
        
        $this->dispatch('toast', [
            'message' => 'Vehículo agregado', 
            'type' => 'success'
        ]);
    }
    
    public function edit($id)
    {
        $vehiculo = vehiculo::findOrFail($id);
        
        $this->vehiculoId = $vehiculo->id;
        $this->marca = $vehiculo->marca;
        $this->modelo = $vehiculo->modelo;
        $this->pesoMaximo = $vehiculo->pesoMaximo;
        $this->volumenMaximo = $vehiculo->volumenMaximo;
        $this->estado = $vehiculo->estado;
        
        $this->dispatch('openModal', 'editModal');
    }
    
    public function update()
    {
        $this->validate()
        
        $vehiculo = vehiculo::findOrFail($this->vehiculoId);
        $estadoActual = $vehiculo->estado;
        $nuevoEstado = $this->estado;

        // --- INICIO DE VALIDACIONES DE ESTADO ---

        // Validar transición desde "En Ruta"
        // Un vehículo En Ruta solo puede pasar a Disponible o Mantenimiento
        if ($estadoActual === vehiculo::ESTADO_EN_RUTA) {
            if ($nuevoEstado !== vehiculo::ESTADO_DISPONIBLE && $nuevoEstado !== vehiculo::ESTADO_MANTENIMIENTO && $nuevoEstado !== vehiculo::ESTADO_EN_RUTA) {
                $this->addError('estado', 'Un vehículo "En Ruta" solo puede pasar a "Disponible" o "Mantenimiento".');
                return;
            }
        }

        // Validar transición desde "Mantenimiento" hacia "En Ruta"
        // Un vehículo en Mantenimiento NO puede pasar a En Ruta
        if ($estadoActual === vehiculo::ESTADO_MANTENIMIENTO && $nuevoEstado === vehiculo::ESTADO_EN_RUTA) {
            $this->addError('estado', 'Un vehículo en "Mantenimiento" no puede pasar directamente a "En Ruta".');
            return;
        }

        // Validar transición desde "Fuera de Servicio" hacia "En Ruta"
        // Un vehículo Fuera de Servicio NO puede pasar a En Ruta
        if ($estadoActual === vehiculo::ESTADO_FUERA_SERVICIO && $nuevoEstado === vehiculo::ESTADO_EN_RUTA) {
            $this->addError('estado', 'Un vehículo "Fuera de Servicio" no puede pasar a "En Ruta".');
            return;
        }

        // --- FIN DE VALIDACIONES DE ESTADO ---
        
        $vehiculo->update([
            'marca' => $this->marca,
            'modelo' => $this->modelo,
            'pesoMaximo' => $this->pesoMaximo,
            'volumenMaximo' => $this->volumenMaximo,
            'estado' => $this->estado
        ]);
        
        $this->dispatch('closeModal', 'editModal');
        $this->resetForm();
        
        $this->dispatch('toast', [
            'message' => 'Vehículo Actualizado',
            'type' => 'success'
        ]);
    }
    
    public function confirmDelete($id)
    {
        $this->vehiculoToDelete = vehiculo::findOrFail($id);
        $this->dispatch('openModal', 'deleteModal');
    }
    
    public function delete()
    {
        try {
            if ($this->vehiculoToDelete->estado === vehiculo::ESTADO_EN_RUTA) {
                $this->dispatch('toast', [
                    'message' => 'No se puede eliminar un vehículo que está en ruta',
                    'type' => 'error'
                ]);
                $this->dispatch('closeModal', 'deleteModal');
                return;
            }
            
            $this->vehiculoToDelete->delete();
            $this->dispatch('closeModal', 'deleteModal');
            
            $this->dispatch('toast', [
                'message' => 'Vehículo Eliminado',
                'type' => 'error'
            ]);
            
        } catch (\Exception $e) {
            $this->dispatch('toast', [
                'message' => 'Error al eliminar el vehículo',
                'type' => 'error'
            ]);
        }
    }
    
    public function setFiltro($filtro)
    {
        $this->filtroEstado = $filtro;
    }
    
    private function resetForm()
    {
        $this->vehiculoId = null;
        $this->marca = '';
        $this->modelo = '';
        $this->pesoMaximo = '';
        $this->volumenMaximo = '';
        $this->estado = '';
        $this->resetErrorBag();
    }
    
    public function render()
    {
        $query = vehiculo::orderBy('created_at', 'desc');
        
        if ($this->filtroEstado !== 'todos') {
            $query->where('estado', $this->filtroEstado);
        }
        
        $vehiculos = $query->get();
        
        $stats = [
            'total' => vehiculo::count(),
            'disponibles' => vehiculo::where('estado', vehiculo::ESTADO_DISPONIBLE)->count(),
            'en_ruta' => vehiculo::where('estado', vehiculo::ESTADO_EN_RUTA)->count(),
            'mantenimiento' => vehiculo::where('estado', vehiculo::ESTADO_MANTENIMIENTO)->count(),
            'fuera_servicio' => vehiculo::where('estado', vehiculo::ESTADO_FUERA_SERVICIO)->count(),
        ];
        
        $estados = vehiculo::getEstados();
        
        return view('livewire.vehiculos.vehiculos-index', compact('vehiculos', 'stats', 'estados'));
    }
}