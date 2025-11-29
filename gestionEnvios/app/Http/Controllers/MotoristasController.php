<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class MotoristasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $motoristas = User::where('rol', 'Motorista')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        return view("motoristas.MotoristasIndex", compact('motoristas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //

        $motoristas = User::where('rol', 'Motorista')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        return view("motoristas.CreateMotorista");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //

        $request->validate(
            [
                'nombreMotorista' => 'required|string|max:255',
                'emailMotorista' => 'required|email|max:255|unique:users,email',
                'direccionMotorista' => 'required|string|max:255',
                'telefonoMotorista' => 'required|string|min:8|max:255|regex:/^[0-9\-\+\(\)\s]+$/',
                'passwordMotorista' => 'required|string|min:8'
            ],
            [
                // Validaciones para nombre
                'nombreMotorista.required' => 'Por favor ingresa el nombre del motorista.',
                'nombreMotorista.string' => 'El nombre debe ser texto válido.',
                'nombreMotorista.max' => 'El nombre no puede tener más de 255 caracteres.',

                // Validaciones para email
                'emailMotorista.required' => 'Por favor ingresa el correo electrónico.',
                'emailMotorista.email' => 'El correo electrónico debe ser válido.',
                'emailMotorista.max' => 'El correo no puede tener más de 255 caracteres.',
                'emailMotorista.unique' => 'Este correo electrónico ya está registrado.',

                // Validaciones para direccion
                'direccionMotorista.required' => 'Por favor ingresa la dirección del motorista.',
                'direccionMotorista.string' => 'La dirección debe ser texto válido.',
                'direccionMotorista.max' => 'La dirección no puede tener más de 255 caracteres.',

                // Validaciones para telefono
                'telefonoMotorista.required' => 'Por favor ingresa el número de teléfono.',
                'telefonoMotorista.string' => 'El teléfono debe ser texto válido.',
                'telefonoMotorista.min' => 'El teléfono debe tener al menos 8 dígitos.',
                'telefonoMotorista.max' => 'El teléfono no puede tener más de 15 caracteres.',
                'telefonoMotorista.regex' => 'El formato del teléfono no es válido.',

                // Validaciones para contraseña
                'passwordMotorista.required' => 'Por favor ingresa una contraseña.',
                'passwordMotorista.string' => 'La contraseña debe ser texto válido.',
                'passwordMotorista.min' => 'La contraseña debe tener al menos 8 caracteres.',
            ]
        );

        $motorista = new User();
        $motorista->name = $request->nombreMotorista;
        $motorista->email = $request->emailMotorista;
        $motorista->telefono = $request->telefonoMotorista;
        $motorista->direccion = $request->direccionMotorista;
        $motorista->password = Hash::make($request->passwordMotorista);
        $motorista->rol = "Motorista";
        $motorista->save();

        return redirect()->route("motoristas.index")->with('success', 'Motorista creado correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
