<?php

namespace App\Http\Controllers;

use App\Models\vehiculo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class VehiculoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $vehiculos = vehiculo::orderBy('created_at', 'desc')->get();
        return view('vehiculos.index', compact('vehiculos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $estados = vehiculo::getEstados();
        return view('vehiculos.create', compact('estados'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $validator = Validator::make($request->all(), [
            'marca' => 'required|string|max:100',
            'modelo' => 'required|string|max:100',
            'pesoMaximo' => 'required|numeric|min:0|max:99999999.99',
            'volumenMaximo' => 'required|numeric|min:0|max:99999999.99',
            'estado' => 'required|in:' . implode(',', Vehiculo::getEstados())
        ], [
            'marca.required' => 'La marca es obligatoria',
            'modelo.required' => 'El modelo es obligatorio',
            'pesoMaximo.required' => 'El peso máximo es obligatorio',
            'pesoMaximo.numeric' => 'El peso máximo debe ser un número',
            'volumenMaximo.required' => 'El volumen máximo es obligatorio',
            'volumenMaximo.numeric' => 'El volumen máximo debe ser un número',
            'estado.required' => 'El estado es obligatorio',
            'estado.in' => 'El estado seleccionado no es válido'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        Vehiculo::create($request->all());

        return redirect()->route('vehiculos.index')
            ->with('success', 'Vehículo creado exitosamente');
    }

    /**
     * Display the specified resource.
     */
    public function show(vehiculo $vehiculo)
    {
        //
        return view('vehiculos.show', compact('vehiculo'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(vehiculo $vehiculo)
    {
        //
        $estados = vehiculo::getEstados();
        return view('vehiculos.edit', compact('estados', 'vehiculo'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, vehiculo $vehiculo)
    {
        //
        $validator = Validator::make($request->all(), [
            'marca' => 'required|string|max:100',
            'modelo' => 'required|string|max:100',
            'pesoMaximo' => 'required|numeric|min:0|max:99999999.99',
            'volumenMaximo' => 'required|numeric|min:0|max:99999999.99',
            'estado' => 'required|in:' . implode(',', Vehiculo::getEstados())
        ], [
            'marca.required' => 'La marca es obligatoria',
            'modelo.required' => 'El modelo es obligatorio',
            'pesoMaximo.required' => 'El peso máximo es obligatorio',
            'pesoMaximo.numeric' => 'El peso máximo debe ser un número',
            'volumenMaximo.required' => 'El volumen máximo es obligatorio',
            'volumenMaximo.numeric' => 'El volumen máximo debe ser un número',
            'estado.required' => 'El estado es obligatorio',
            'estado.in' => 'El estado seleccionado no es válido'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $vehiculo->update($request->all());

        return redirect()->route('vehiculos.index')
            ->with('success', 'Vehículo actualizado exitosamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(vehiculo $vehiculo)
    {
        //
        try {
            $vehiculo->delete();
            return redirect()->route('vehiculos.index')
                ->with('success', 'Vehículo eliminado exitosamente');
        } catch (\Exception $e) {
            return redirect()->route('vehiculos.index')
                ->with('error', 'Error al eliminar el vehículo: ' . $e->getMessage());
        }
    }
}
