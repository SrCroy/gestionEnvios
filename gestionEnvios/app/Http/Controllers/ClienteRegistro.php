<?php

namespace App\Http\Controllers;

use App\Models\clientes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ClienteRegistro extends Controller
{
    //
    public function showRegisterForm()
    {
        return view('clientes.registro');
    }



    public function register(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'email' => 'required|email|unique:clientes,email|unique:users,email',
            'telefono'  => 'required|string|max:20|unique:clientes,telefono|unique:users,telefono',
            'password' => 'required|min:8|confirmed',
            'direccion' => 'required|string|max:255',
        ], [
            'nombre.required' => 'El nombre completo es obligatorio.',
            'nombre.string'   => 'El nombre debe ser un texto válido.',
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email'    => 'Debes ingresar un correo electrónico válido.',
            'email.unique'   => 'Este correo electrónico ya está registrado en el sistema.',
            'telefono.required' => 'El teléfono es obligatorio.',
            'telefono.unique'   => 'Este número de teléfono ya está registrado.',
            'telefono.max'      => 'El teléfono no puede tener más de 20 caracteres.',
            'direccion.required' => 'La dirección es obligatoria.',
            'password.required'  => 'La contraseña es obligatoria.',
            'password.min'       => 'La contraseña debe tener al menos 8 caracteres.',
            'password.confirmed' => 'Las contraseñas no coinciden.', 
        ]);


       
        $apiKey = env('GOOGLE_MAPS_KEY');

        $direccionEncoded = urlencode($request->direccion);
        $url = "https://maps.googleapis.com/maps/api/geocode/json?address={$direccionEncoded}&region=sv&key={$apiKey}";

        $response = json_decode(file_get_contents($url), true);

        if ($response['status'] !== 'OK') {
            return back()->with('error', 'No se pudo obtener la ubicación. Verifica la dirección.');
        }

        $location = $response['results'][0]['geometry']['location'];

     
        $pais = collect($response['results'][0]['address_components'])
            ->firstWhere('types', ['country'])['long_name'] ?? null;

        $cliente = clientes::create([
            'nombre' => $request->nombre,
            'email' => $request->email,
            'telefono' => $request->telefono,
            'direccion' => $request->direccion,
            'latitud' => $location['lat'],
            'longitud' => $location['lng'],
            'password' => bcrypt($request->password)
        ]);

        return redirect()->route('cliente.login')->with('success', 'Registro exitoso. Ahora puede iniciar sesión.');
    }
}
