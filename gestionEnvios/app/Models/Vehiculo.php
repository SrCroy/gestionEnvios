<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehiculo extends Model
{
    use HasFactory;

    protected $table = 'vehiculos'; // Asegúrate de que el nombre de la tabla sea correcto

    protected $fillable = [
        'marca',
        'modelo',
        'pesoMaximo',
        'placa',
        'estado',
    ];

    // Relación con las asignaciones
    public function asignaciones()
    {
        return $this->hasMany(Asignacion::class, 'idVehiculo');
    }
}
