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
        return view('vehiculos.VehiculosIndex');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(vehiculo $vehiculo)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(vehiculo $vehiculo)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, vehiculo $vehiculo)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(vehiculo $vehiculo)
    {
        //
    }

    /**
     * Obtener vehículos disponibles para asignación
     */
    public function disponibles()
    {
        $vehiculosDisponibles = Vehiculo::where('estado', Vehiculo::ESTADO_DISPONIBLE)
            ->orderBy('marca')
            ->orderBy('modelo')
            ->get();

        return response()->json([
            'success' => true,
            'vehiculos' => $vehiculosDisponibles
        ]);
    }
}