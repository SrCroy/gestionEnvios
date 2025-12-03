<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class loginController extends Controller
{
   
    public function showLoginForm()
    {
        return view('livewire.auth.login');
    }

    
    public function login(Request $request)
    {
      
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ], [
            'email.required' => 'El email es requerido',
            'email.email' => 'El email no es válido',
            'password.required' => 'La contraseña es requerida',
            'password.min' => 'La contraseña debe tener al menos 6 caracteres',
        ]);

       
        if (Auth::attempt($credentials, $request->has('remember'))) {
            
            $request->session()->regenerate();
            
            
            $usuario = Auth::user(); 

          
            if ($usuario->rol === 'motorista' || $usuario->rol === 'Motorista') {
             return redirect()->route('asignaciones.index')
            ->with('success', 'Bienvenido, Motorista.');
            }

            
            if ($usuario->rol === 'admin' || $usuario->rol === 'Administrador') {
               return redirect()->route('dashboard.home')
            ->with('success', 'Bienvenido al panel de Administración.');
            }

           
            Auth::logout();
            return back()->with('error', 'Tu usuario no tiene un rol autorizado para ingresar.');
        }

        return back()
            ->withInput($request->only('email'))
            ->with('error', 'Las credenciales no son válidas.');
    }

  
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('login') 
            ->with('success', 'Sesión cerrada correctamente.');
    }
}