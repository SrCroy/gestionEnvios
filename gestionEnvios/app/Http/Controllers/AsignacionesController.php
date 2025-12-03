<?php

namespace App\Http\Controllers;

use App\Models\Asignacion;
use App\Models\User;
use App\Models\vehiculo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AsignacionesController extends Controller
{
    public function index()
    {
      
        $motoristas = User::where('rol', 'motorista')->get();
        $vehiculos = vehiculo::all();

        return view('asignaciones.index', compact('motoristas', 'vehiculos'));
    }

    public function events(Request $request)
{
    $start = $request->input('start');
    $end = $request->input('end');

  
    $query = Asignacion::with(['motorista', 'vehiculo'])
        ->whereDate('fechaAsignacion', '>=', $start)
        ->whereDate('fechaAsignacion', '<=', $end);

  
    $user = Auth::user();

    if ($user->rol === 'Motorista') {
      
        $query->where('idMotorista', $user->id);
    } 
    else {
     
        if ($request->has('motorista') && $request->motorista) {
            $query->where('idMotorista', $request->motorista);
        }
    }
  

    if ($request->has('vehiculo') && $request->vehiculo) {
        $query->where('idVehiculo', $request->vehiculo);
    }

    $asignaciones = $query->get()->map(function ($asignacion) {
        return $this->formatEvent($asignacion);
    });

    return response()->json($asignaciones);
}

    public function store(Request $request)
    {
      
        if (Auth::user()->rol !== 'admin') {
            return response()->json(['error' => 'No tienes permiso para crear asignaciones.'], 403);
        }

        $validated = $request->validate([
            'fecha' => 'required|date',
            'idMotorista' => 'nullable|exists:users,id',
            'idVehiculo' => 'nullable|exists:vehiculos,id',
            'notas' => 'nullable|string|max:500'
        ]);

      
        if ($validated['idVehiculo']) {
            $vehiculoOcupado = Asignacion::where('idVehiculo', $validated['idVehiculo'])
                ->whereDate('fechaAsignacion', $validated['fecha'])
                ->exists();

            if ($vehiculoOcupado) {
                return response()->json(['error' => 'El vehículo ya está asignado en esta fecha'], 422);
            }
        }

        $asignacion = Asignacion::create([
            'fechaAsignacion' => $validated['fecha'],
            'idMotorista' => $validated['idMotorista'] ?? null,
            'idVehiculo' => $validated['idVehiculo'] ?? null,
            'notas' => $validated['notas'] ?? null,
        ]);

        $asignacion->load(['motorista','vehiculo']);

        return response()->json([
            'success' => true,
            'message' => 'Asignación guardada correctamente',
            'event' => $this->formatEvent($asignacion)
        ]);
    }

    public function update(Request $request, $id)
    {
       
        if (Auth::user()->rol !== 'admin') {
            return response()->json(['error' => 'No tienes permiso para editar asignaciones.'], 403);
        }

        $asignacion = Asignacion::findOrFail($id);

        $validated = $request->validate([
            'idMotorista' => 'nullable|exists:users,id',
            'idVehiculo' => 'nullable|exists:vehiculos,id',
            'notas' => 'nullable|string|max:500'
        ]);

        if (
            isset($validated['idVehiculo']) &&
            $validated['idVehiculo'] != $asignacion->idVehiculo
        ) {
            $vehiculoOcupado = Asignacion::where('idVehiculo', $validated['idVehiculo'])
                ->whereDate('fechaAsignacion', $asignacion->fechaAsignacion)
                ->where('id', '!=', $id)
                ->exists();

            if ($vehiculoOcupado) {
                return response()->json(['error' => 'El vehículo ya está asignado en esta fecha'], 422);
            }
        }

        $asignacion->update($validated);
        $asignacion->load(['motorista','vehiculo']);

        return response()->json([
            'success' => true,
            'message' => 'Asignación actualizada correctamente',
            'event' => $this->formatEvent($asignacion)
        ]);
    }

    public function destroy($id)
    {
      
        if (Auth::user()->rol !== 'admin') {
            return response()->json(['error' => 'No tienes permiso para eliminar asignaciones.'], 403);
        }

        $asignacion = Asignacion::findOrFail($id);
        $asignacion->delete();

        return response()->json([
            'success' => true,
            'message' => 'Asignación eliminada correctamente'
        ]);
    }


    private function formatEvent($asignacion)
    {
        $motorista = $asignacion->motorista->name ?? 'Sin motorista';
        $vehiculo = $asignacion->vehiculo->modelo ?? 'Sin vehículo';

        $estado = 'sin_asignar';
        $color = '#dc3545';
        $title = 'Día de trabajo';

        if ($asignacion->idMotorista && $asignacion->idVehiculo) {
            $estado = 'completo';
            $color = '#28a745';
            $title = "$motorista - $vehiculo";
        } elseif ($asignacion->idMotorista || $asignacion->idVehiculo) {
            $estado = 'parcial';
            $color = '#ffc107';
            $title = $asignacion->idMotorista
                ? "$motorista (Sin vehículo)"
                : "$vehiculo (Sin motorista)";
        }

        return [
            'id' => $asignacion->id,
            'title' => $title,
            'start' => $asignacion->fechaAsignacion->format('Y-m-d'),
            'color' => $color,
            'allDay' => true,
           
            'extendedProps' => [
                'idMotorista' => $asignacion->idMotorista,
                'idVehiculo' => $asignacion->idVehiculo,
                'motorista' => $motorista,
                'vehiculo' => $vehiculo,
                'estado' => $estado,
                'fecha' => $asignacion->fechaAsignacion->format('d/m/Y'),
                'fecha_completa' => $asignacion->fechaAsignacion->format('Y-m-d'),
                'notas' => $asignacion->notas 
            ]
        ];
    }
}