<?php

namespace App\Livewire\Clientes;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class LoginClientes extends Component
{
    public $email = '';
    public $password = '';
    public $remember = false;
    public $errors = [];

    public function login()
    {
        $this->errors = []; 

       
        if (empty($this->email)) {
            $this->errors['email'] = 'El email es requerido';
        } elseif (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            $this->errors['email'] = 'El email no es válido';
        }

        if (empty($this->password)) {
            $this->errors['password'] = 'La contraseña es requerida';
        } elseif (strlen($this->password) < 6) {
            $this->errors['password'] = 'La contraseña debe tener al menos 6 caracteres';
        }

        
        if (!empty($this->errors)) {
            return;
        }

        
        if (Auth::guard('cliente')->attempt(
            ['email' => $this->email, 'password' => $this->password],
            $this->remember
        )) {
            session()->regenerate();
            return redirect()->route('cliente.dashboard');
        }

       
        $this->errors['email'] = 'Credenciales incorrectas.';
    }

    public function render()
    {
        return view('livewire.clientes.login-clientes');
    }
}
