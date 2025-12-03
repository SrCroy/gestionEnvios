<?php

namespace App\Livewire\Paquetes;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\paquetes; 
use Illuminate\Support\Facades\Auth;

class PaquetesIndex extends Component
{
    use WithPagination;

    // Variables de Filtro y Búsqueda
    public $filtroEstado = 'todos'; 
    public $search = '';
    
    // Variables para Edición
    public $paqueteIdEditar = null;
    public $descripcionEdicion = '';
    public $pesoEdicion = '';
    public $alturaEdicion = '';

    // Variable para Eliminar
    public $paqueteParaEliminar = null;

    // Listeners
    protected $listeners = ['paquete-creado' => '$refresh'];

    // Método para cambiar filtro
    public function setFiltro($estado)
    {
        $this->filtroEstado = $estado;
        $this->resetPage();
    }

    // Abrir Modal de Edición y Cargar Datos
    public function edit($id)
    {
        $paquete = paquetes::find($id);
        
        if($paquete){
            $this->paqueteIdEditar = $paquete->id;
            
            // Cargamos los datos actuales del paquete en las variables del formulario
            $this->descripcionEdicion = $paquete->descripcion; 
            $this->pesoEdicion = $paquete->peso;
            $this->alturaEdicion = $paquete->altura;
            
            $this->dispatch('openModal', modalId: 'editModal');
        }
    }

    // Guardar Cambios de Edición
    public function update()
    {
        // Validamos también peso y altura
        $this->validate([
            'descripcionEdicion' => 'required|min:5',
            'pesoEdicion' => 'required|numeric|min:0.1',
            'alturaEdicion' => 'required|numeric|min:1',
        ]);

        if($this->paqueteIdEditar){
            $paquete = paquetes::find($this->paqueteIdEditar);
            
            // Actualizamos todos los campos
            $paquete->update([
                'descripcion' => $this->descripcionEdicion,
                'peso' => $this->pesoEdicion,
                'altura' => $this->alturaEdicion
            ]);
            
            $this->dispatch('closeModal', modalId: 'editModal');
            $this->dispatch('toast', message: 'Información del paquete actualizada', type: 'success');
        }
    }

    // Abrir Confirmación de Eliminar
    public function confirmDelete($id)
    {
        // Buscamos el paquete y lo guardamos para poder mostrar su nombre en el modal
        $this->paqueteParaEliminar = paquetes::find($id);
        
        if($this->paqueteParaEliminar){
            $this->dispatch('openModal', modalId: 'deleteModal');
        }
    }

    // Ejecutar Eliminación
    public function delete()
    {
        if ($this->paqueteParaEliminar && $this->paqueteParaEliminar->estadoActual === 'Pendiente') {
            $this->paqueteParaEliminar->delete(); 
            
            // Toast rojo (error) para indicar eliminación
            $this->dispatch('toast', message: 'Envío eliminado correctamente', type: 'error');
        } else {
            $this->dispatch('toast', message: 'No se puede cancelar este envío', type: 'error');
        }

        $this->dispatch('closeModal', modalId: 'deleteModal');
    }

    public function render()
    {
        $cliente = Auth::guard('cliente')->user();

        $query = paquetes::query();
        
        if($cliente) {
            $query->where(function($q) use ($cliente) {
                $q->where('idRemitente', $cliente->id)
                  ->orWhere('idDestinatario', $cliente->id);
            });
        }

        // Estadísticas para los botones
        $todosPaquetes = (clone $query)->get();

        $stats = [
            'total'       => $todosPaquetes->count(),
            'pendientes'  => $todosPaquetes->where('estadoActual', 'Pendiente')->count(),
            'en_transito' => $todosPaquetes->where('estadoActual', 'En Tránsito')->count(),
            'entregados'  => $todosPaquetes->where('estadoActual', 'Entregado')->count(),
        ];

        // Filtro
        if ($this->filtroEstado !== 'todos') {
            $query->where('estadoActual', $this->filtroEstado);
        }

        // Paginación
        $paquetes = $query->orderBy('created_at', 'desc')->paginate(6);

        return view('livewire.paquetes.paquetes-index', [
            'paquetes' => $paquetes,
            'todosPaquetes' => $todosPaquetes,
            'stats' => $stats,
        ]);
    }
}