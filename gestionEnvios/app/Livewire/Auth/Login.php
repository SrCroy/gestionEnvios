<?php

namespace App\Livewire\Auth;

class Login
{
    public $email = '';
    public $password = '';
    public $remember = false;
    public $errors = [];

    public function login()
    {
        // Validar datos
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

        // Aquí iría la autenticación
        return 'success';
    }

    public function render()
    {
        return view('livewire.auth.login');
    }
}
