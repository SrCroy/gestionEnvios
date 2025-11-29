<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class loginController extends Controller
{
    /**
     * Mostrar formulario de login (Livewire)
     */
    public function showLoginForm()
    {
        return view('livewire.auth.login');
    }

    /**
     * Procesar login
     */
    public function login(Request $request)
    {
        // Validar datos
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ], [
            'email.required' => 'El email es requerido',
            'email.email' => 'El email no es válido',
            'password.required' => 'La contraseña es requerida',
            'password.min' => 'La contraseña debe tener al menos 6 caracteres',
        ]);

        // Intentar autenticar
        if (Auth::attempt($credentials, $request->has('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended(route('dashboard'))->with('success', '¡Bienvenido al panel!');
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
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login')->with('success', 'Sesión cerrada correctamente.');
    }
}
