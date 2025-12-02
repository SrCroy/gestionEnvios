<?php

namespace App\Http\Controllers;

use App\Models\Asignacion; // cambiado
use App\Models\User;
use App\Models\vehiculo;
use Illuminate\Http\Request;

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
        $motorista = $request->input('motorista');
        $vehiculo = $request->input('vehiculo');

        $query = Asignacion::with(['motorista', 'vehiculo']) // cambiado
            ->whereDate('fechaAsignacion', '>=', $start)
            ->whereDate('fechaAsignacion', '<=', $end);

        if ($motorista) {
            $query->where('idMotorista', $motorista);
        }

        if ($vehiculo) {
            $query->where('idVehiculo', $vehiculo);
        }

        $asignaciones = $query->get()->map(function ($asignacion) {

            $motorista = $asignacion->motorista->name ?? 'Sin motorista';
            $vehiculo  = $asignacion->vehiculo->modelo ?? 'Sin vehículo'; // <- usar nombre/modelo

            $estado = 'sin_asignar';
            $color = '#dc3545';

            if ($asignacion->idMotorista && $asignacion->idVehiculo) {
                $estado = 'completo';
                $color = '#28a745';
            } elseif ($asignacion->idMotorista || $asignacion->idVehiculo) {
                $estado = 'parcial';
                $color = '#ffc107';
            }

            $title = "{$motorista} - {$vehiculo}";

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
                    'vehiculo' => $vehiculo, // <- enviar modelo/nombre
                    'estado' => $estado,
                    'fecha' => $asignacion->fechaAsignacion->format('d/m/Y'),
                    'fecha_completa' => $asignacion->fechaAsignacion->format('Y-m-d'),
                ]
            ];
        });

        return response()->json($asignaciones);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'fecha' => 'required|date|after:yesterday',
            'idMotorista' => 'nullable|exists:users,id',
            'idVehiculo' => 'nullable|exists:vehiculos,id',
            'notas' => 'nullable|string|max:500'
        ]);

        // Validación: el mismo vehículo no puede repetirse en la misma fecha
        if ($validated['idVehiculo']) {
            $vehiculoOcupado = Asignacion::where('idVehiculo', $validated['idVehiculo'])
                ->whereDate('fechaAsignacion', $validated['fecha'])
                ->exists();

            if ($vehiculoOcupado) {
                return response()->json(['error' => 'El vehículo ya está asignado en esta fecha'], 422);
            }
        }

        // Permitir múltiples motoristas por día: crear siempre un nuevo registro
        $asignacion = Asignacion::create([
            'fechaAsignacion' => $validated['fecha'],
            'idMotorista' => $validated['idMotorista'] ?? null,
            'idVehiculo' => $validated['idVehiculo'] ?? null,
            'notas' => $validated['notas'] ?? null,
        ]);

        // Asegurar relaciones cargadas para título correcto
        $asignacion->load(['motorista','vehiculo']);

        return response()->json([
            'success' => true,
            'message' => 'Asignación guardada correctamente',
            'event' => $this->formatEvent($asignacion)
        ]);
    }

    public function update(Request $request, $id)
    {
        $asignacion = Asignacion::findOrFail($id);

        $validated = $request->validate([
            'idMotorista' => 'nullable|exists:users,id',
            'idVehiculo' => 'nullable|exists:vehiculos,id',
            'notas' => 'nullable|string|max:500'
        ]);

        // Permitir cambiar motorista sin restricción por fecha (múltiples motoristas en el mismo día)
        // Mantener vehículo único por fecha
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
        $asignacion->load(['motorista','vehiculo']); // cargar relaciones

        return response()->json([
            'success' => true,
            'message' => 'Asignación actualizada correctamente',
            'event' => $this->formatEvent($asignacion)
        ]);
    }

    public function destroy($id)
    {
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
        $vehiculo = $asignacion->vehiculo->modelo ?? 'Sin vehículo'; // <- usar nombre/modelo

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
            'allDay' => true
        ];
    }
}
