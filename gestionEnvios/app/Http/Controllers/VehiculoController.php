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
        $vehiculos = vehiculo::orderBy('created_at', 'desc')->get();
        return view('vehiculos.index', compact('vehiculos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // No necesitamos pasar estados al crear, siempre será "Disponible"
        return view('vehiculos.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'marca' => 'required|string|max:100',
            'modelo' => 'required|string|max:100',
            'pesoMaximo' => 'required|numeric|min:0|max:99999999.99',
            'volumenMaximo' => 'required|numeric|min:0|max:99999999.99',
        ], [
            'marca.required' => 'La marca es obligatoria',
            'marca.string' => 'La marca debe ser texto',
            'marca.max' => 'La marca no puede exceder 100 caracteres',
            'modelo.required' => 'El modelo es obligatorio',
            'modelo.string' => 'El modelo debe ser texto',
            'modelo.max' => 'El modelo no puede exceder 100 caracteres',
            'pesoMaximo.required' => 'El peso máximo es obligatorio',
            'pesoMaximo.numeric' => 'El peso máximo debe ser un número',
            'pesoMaximo.min' => 'El peso máximo debe ser mayor o igual a 0',
            'pesoMaximo.max' => 'El peso máximo no puede exceder 99999999.99',
            'volumenMaximo.required' => 'El volumen máximo es obligatorio',
            'volumenMaximo.numeric' => 'El volumen máximo debe ser un número',
            'volumenMaximo.min' => 'El volumen máximo debe ser mayor o igual a 0',
            'volumenMaximo.max' => 'El volumen máximo no puede exceder 99999999.99',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Crear vehículo siempre en estado "Disponible"
        Vehiculo::create([
            'marca' => $request->marca,
            'modelo' => $request->modelo,
            'pesoMaximo' => $request->pesoMaximo,
            'volumenMaximo' => $request->volumenMaximo,
            'estado' => Vehiculo::ESTADO_DISPONIBLE
        ]);

        return redirect()->route('vehiculos.index')
            ->with('success', 'Vehículo creado exitosamente y listo para asignación');
    }

    /**
     * Display the specified resource.
     */
    public function show(vehiculo $vehiculo)
    {
        return view('vehiculos.show', compact('vehiculo'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(vehiculo $vehiculo)
    {
        $estados = vehiculo::getEstados();
        return view('vehiculos.edit', compact('estados', 'vehiculo'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, vehiculo $vehiculo)
    {
        $validator = Validator::make($request->all(), [
            'marca' => 'required|string|max:100',
            'modelo' => 'required|string|max:100',
            'pesoMaximo' => 'required|numeric|min:0|max:99999999.99',
            'volumenMaximo' => 'required|numeric|min:0|max:99999999.99',
            'estado' => 'required|in:' . implode(',', Vehiculo::getEstados())
        ], [
            'marca.required' => 'La marca es obligatoria',
            'marca.string' => 'La marca debe ser texto',
            'marca.max' => 'La marca no puede exceder 100 caracteres',
            'modelo.required' => 'El modelo es obligatorio',
            'modelo.string' => 'El modelo debe ser texto',
            'modelo.max' => 'El modelo no puede exceder 100 caracteres',
            'pesoMaximo.required' => 'El peso máximo es obligatorio',
            'pesoMaximo.numeric' => 'El peso máximo debe ser un número',
            'pesoMaximo.min' => 'El peso máximo debe ser mayor o igual a 0',
            'pesoMaximo.max' => 'El peso máximo no puede exceder 99999999.99',
            'volumenMaximo.required' => 'El volumen máximo es obligatorio',
            'volumenMaximo.numeric' => 'El volumen máximo debe ser un número',
            'volumenMaximo.min' => 'El volumen máximo debe ser mayor o igual a 0',
            'volumenMaximo.max' => 'El volumen máximo no puede exceder 99999999.99',
            'estado.required' => 'El estado es obligatorio',
            'estado.in' => 'El estado seleccionado no es válido'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Validar transiciones de estado lógicas
        $estadoActual = $vehiculo->estado;
        $estadoNuevo = $request->estado;

        // Prevenir cambios ilógicos de estado
        if ($estadoActual === Vehiculo::ESTADO_EN_RUTA && 
            $estadoNuevo !== Vehiculo::ESTADO_DISPONIBLE && 
            $estadoNuevo !== Vehiculo::ESTADO_MANTENIMIENTO) {
            return redirect()->back()
                ->withErrors(['estado' => 'Un vehículo en ruta solo puede pasar a Disponible o Mantenimiento'])
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
        try {
            // Validar que no se pueda eliminar un vehículo en ruta
            if ($vehiculo->estado === Vehiculo::ESTADO_EN_RUTA) {
                return redirect()->route('vehiculos.index')
                    ->with('error', 'No se puede eliminar un vehículo que está en ruta');
            }

            $vehiculo->delete();
            return redirect()->route('vehiculos.index')
                ->with('success', 'Vehículo eliminado exitosamente');
        } catch (\Exception $e) {
            return redirect()->route('vehiculos.index')
                ->with('error', 'Error al eliminar el vehículo: ' . $e->getMessage());
        }
    }

    /**
     * Cambiar estado del vehículo (método adicional para transiciones controladas)
     */
    public function cambiarEstado(Request $request, vehiculo $vehiculo)
    {
        $validator = Validator::make($request->all(), [
            'estado' => 'required|in:' . implode(',', Vehiculo::getEstados())
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Estado no válido'
            ], 400);
        }

        $vehiculo->update(['estado' => $request->estado]);

        return response()->json([
            'success' => true,
            'message' => 'Estado actualizado correctamente',
            'nuevo_estado' => $vehiculo->estado,
            'badge_class' => $vehiculo->estadoBadge
        ]);
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