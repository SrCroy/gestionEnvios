<?php

namespace App\Livewire\Clientes;

use Livewire\Component;
use App\Models\Cliente;
use App\Models\clientes;
use Illuminate\Support\Facades\Hash;

class RegisterClientes extends Component
{
    public $nombre;
    public $email;
    public $password;
    public $direccion;
    public $telefono;
    public $latitud;
    public $longitud;

    public function registrar()
    {
        $this->validate([
            'nombre' => 'required|string',
            'email' => 'required|email|unique:clientes,email',
            'password' => 'required|min=6',
            'direccion' => 'required|string',
            'latitud' => 'required',
            'longitud' => 'required',
        ]);

        clientes::create([
            'nombre' => $this->nombre,
            'email' => $this->email,
            'telefono' => $this->telefono,
            'password' => Hash::make($this->password),
            'direccion' => $this->direccion,
            'latitud' => $this->latitud,
            'longitud' => $this->longitud,
        ]);

        return redirect()->route('cliente.login')
            ->with('success', 'Registro completado.');
    }

    public function render()
    {
        return view('clientes.registroCliente');
    }
}
