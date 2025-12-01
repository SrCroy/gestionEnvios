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

    public function render()
    {
        $clientes = clientes::all();

        $titulo = 'gestion clientes';

        return view('livewire.clientes.clientes-index', compact('clientes', 'titulo'));
    }
}