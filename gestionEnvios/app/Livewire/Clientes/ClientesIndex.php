<?php

namespace App\Livewire\Clientes;

use App\Models\clientes;
use Livewire\Component;
 
class ClientesIndex extends Component
{
    public $cliente_id;
    public $nombre = '';
    public $direccion = '';
    public $telefono = '';
    public $email = '';
    public $latitud = '';
    public $longitud = '';

    public $clienteDelete;

    public function delete()
    {
        try {
            
            $nombre = $this->clienteDelete->nombre;
            $email = $this->clienteDelete->email;
            
            $this->clienteDelete->delete();
            $this->dispatch('closeModal', 'deleteModal');
            
            // ✅ Notificación roja al eliminar
            $this->dispatch('toast', [
                'message' => "Cliente {$nombre} {$email} eliminado",
                'type' => 'error'
            ]);
            
        } catch (\Exception $e) {
            $this->dispatch('toast', [
                'message' => 'Error al eliminar el cliente',
                'type' => 'error'
            ]);
        }
    }

    public function render()
    {
        $clientes = clientes::all();

        $titulo = 'gestion clientes';

        return view('livewire.clientes.clientes-index', compact('clientes', 'titulo'));
    }
}