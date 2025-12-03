<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClienteAuthController extends Controller
{
    /**
     * Mostrar formulario de login (Livewire)
     */
    public function showLoginForm()
    {
        return view('livewire.clientes.login-clientes');
    }

    /**
     * Procesar login
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:8',
        ], [
            'email.required' => 'El email es requerido',
            'email.email' => 'El email no es válido',
            'password.required' => 'La contraseña es requerida',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres',
        ]);

        // Intentar autenticar
        if (Auth::guard('cliente')->attempt(
            $request->only('email', 'password'),
            $request->has('remember')
        )) {

            $request->session()->regenerate();

            return redirect()->intended(route('clientes.dashboard'))
                ->with('success', '¡Bienvenido!');
        }

        // Error de autenticación
        return back()
            ->withInput($request->only('email'))
            ->with('error', 'Las credenciales no son válidas.');
    }

    /**
     * Logout
     */
    public function logout(Request $request)
    {
        Auth::guard('cliente')->logout(); 

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('cliente.login')
            ->with('success', 'Sesión cerrada correctamente.');
    }
}
