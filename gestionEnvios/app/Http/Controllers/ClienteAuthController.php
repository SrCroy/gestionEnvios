<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClienteAuthController extends Controller
{
    
    public function showLoginForm()
    {
        return view('livewire.clientes.login-clientes'); 
    }

    
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

        if (Auth::guard('cliente')->attempt(
            $request->only('email', 'password'),
            $request->has('remember')
        )) {

            $request->session()->regenerate();

            return redirect()->route('clientes.dashboard')
                ->with('success', '¡Bienvenido!');
        }

       
        return back()
        ->withInput($request->only('email')) 
        ->withErrors([
            'email' => 'Las credenciales no coinciden con nuestros registros.',
        ]);
    }

    
    public function logout(Request $request)
    {
        Auth::guard('cliente')->logout(); 

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('cliente.login')
            ->with('success', 'Sesión cerrada correctamente.');
    }
}
