<?php

namespace App\Livewire\Clientes;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash; 
use Illuminate\Validation\Rule;

class EditarPerfil extends Component
{
    public $nombre, $email, $telefono, $direccion, $latitud, $longitud;

    public $password_actual;
    public $password_nueva;
    public $password_nueva_confirmation;

    public function mount()
    {
        
        $cliente = Auth::guard('cliente')->user();
        if ($cliente) {
            $this->nombre = $cliente->nombre;
            $this->email = $cliente->email;
            $this->telefono = $cliente->telefono;
            $this->direccion = $cliente->direccion;
            $this->latitud = $cliente->latitud;
            $this->longitud = $cliente->longitud;
        }
    }

    public function guardar()
    {
        $cliente = Auth::guard('cliente')->user();

       
        $this->validate([
            'nombre'    => 'required|string|max:255',
            'telefono'  => 'required|string|max:20',
            'direccion' => 'required|string',
            'email'     => ['required', 'email', Rule::unique('clientes')->ignore($cliente->id)],
        ]);

        $cliente->update([
            'nombre'    => $this->nombre,
            'email'     => $this->email,
            'telefono'  => $this->telefono,
            'direccion' => $this->direccion,
            'latitud'   => $this->latitud ?: null,
            'longitud'  => $this->longitud ?: null,
        ]);

      
        if (!empty($this->password_actual)) {
            
            $this->validate([
                'password_actual' => 'required|current_password:cliente',
                'password_nueva'  => 'required|min:8|confirmed|different:password_actual',
            ], [
                'password_actual.current_password' => 'La contraseña actual es incorrecta.',
                'password_nueva.confirmed' => 'Las contraseñas nuevas no coinciden.',
                'password_nueva.different' => 'La nueva contraseña no puede ser igual a la anterior.',
            ]);

           
            $cliente->update([
                'password' => Hash::make($this->password_nueva)
            ]);

            
            $this->reset(['password_actual', 'password_nueva', 'password_nueva_confirmation']);
        }

        session()->flash('success', 'Perfil actualizado correctamente.');
    }

    public function render()
    {
        return view('livewire.clientes.editar-perfil');
    }
}