<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;      // agregado
use App\Models\vehiculo;  // agregado

class Asignacion extends Model
{
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

    public function vehiculo(): BelongsTo
    {
        return $this->belongsTo(vehiculo::class, 'idVehiculo');
    }
}
