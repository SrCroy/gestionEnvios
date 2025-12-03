<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Asignacion extends Model
{
    use HasFactory;

    protected $table = 'asignaciones';

    protected $fillable = [
        'fechaAsignacion',
        'idMotorista',
        'idVehiculo',
        'notas',
    ];

    protected $casts = [
        'fechaAsignacion' => 'date',
    ];

    public function motorista(): BelongsTo
    {
        return $this->belongsTo(User::class, 'idMotorista');
    }

    /**
     * Relación con el modelo vehiculo.
     */
    public function vehiculo()
    {
        return $this->belongsTo(vehiculo::class, 'idVehiculo', 'id');
    }
}
