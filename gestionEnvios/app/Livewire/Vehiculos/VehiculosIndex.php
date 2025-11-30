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
    
    // Control de modales - Usaremos eventos JS
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
            'estado' => 'nullable|in:' . implode(',', vehiculo::getEstados())
        ];
    }
    
    // Mensajes de validación personalizados
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
        'estado.in' => 'El estado seleccionado no es válido'
    ];
    
    // Abrir modal de creación
    public function create()
    {
        $this->resetForm();
        $this->dispatch('openModal', 'createModal');
    }
    
    // Guardar nuevo vehículo
    public function store()
    {
        $this->validate();
        
        vehiculo::create([
            'marca' => $this->marca,
            'modelo' => $this->modelo,
            'pesoMaximo' => $this->pesoMaximo,
            'volumenMaximo' => $this->volumenMaximo,
            'estado' => vehiculo::ESTADO_DISPONIBLE
        ]);
        
        $this->dispatch('closeModal', 'createModal');
        $this->resetForm();
        
        // ✅ Notificación verde al crear
        $this->dispatch('toast', [
            'message' => 'Vehículo agregado exitosamente',
            'type' => 'success'
        ]);
    }
    
    // Abrir modal de edición
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
    
    // Actualizar vehículo
    public function update()
    {
        $this->validate();
        
        $vehiculo = vehiculo::findOrFail($this->vehiculoId);
        
        // Validar transiciones de estado lógicas
        if ($vehiculo->estado === vehiculo::ESTADO_EN_RUTA && 
            $this->estado !== vehiculo::ESTADO_DISPONIBLE && 
            $this->estado !== vehiculo::ESTADO_MANTENIMIENTO) {
            
            $this->addError('estado', 'Un vehículo en ruta solo puede pasar a Disponible o Mantenimiento');
            
            $this->dispatch('toast', [
                'message' => 'Transición de estado no permitida',
                'type' => 'error'
            ]);
            return;
        }
        
        $vehiculo->update([
            'marca' => $this->marca,
            'modelo' => $this->modelo,
            'pesoMaximo' => $this->pesoMaximo,
            'volumenMaximo' => $this->volumenMaximo,
            'estado' => $this->estado
        ]);
        
        $this->dispatch('closeModal', 'editModal');
        $this->resetForm();
        
        // ✅ Notificación verde al actualizar
        $this->dispatch('toast', [
            'message' => 'Vehículo actualizado correctamente',
            'type' => 'success'
        ]);
    }
    
    // Confirmar eliminación
    public function confirmDelete($id)
    {
        $this->vehiculoToDelete = vehiculo::findOrFail($id);
        $this->dispatch('openModal', 'deleteModal');
    }
    
    // Eliminar vehículo
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
            
            $marca = $this->vehiculoToDelete->marca;
            $modelo = $this->vehiculoToDelete->modelo;
            
            $this->vehiculoToDelete->delete();
            $this->dispatch('closeModal', 'deleteModal');
            
            // ✅ Notificación roja al eliminar
            $this->dispatch('toast', [
                'message' => "Vehículo {$marca} {$modelo} eliminado",
                'type' => 'error'
            ]);
            
        } catch (\Exception $e) {
            $this->dispatch('toast', [
                'message' => 'Error al eliminar el vehículo',
                'type' => 'error'
            ]);
        }
    }
    
    // Cambiar filtro de estado (SIN NOTIFICACIÓN)
    public function setFiltro($filtro)
    {
        $this->filtroEstado = $filtro;
        // ❌ NO mostrar notificación al filtrar
    }
    
    // Resetear formulario
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
    
    // Render del componente
    public function render()
    {
        // Obtener vehículos con filtro
        $query = vehiculo::orderBy('created_at', 'desc');
        
        if ($this->filtroEstado !== 'todos') {
            $query->where('estado', $this->filtroEstado);
        }
        
        $vehiculos = $query->get();
        
        // Estadísticas
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