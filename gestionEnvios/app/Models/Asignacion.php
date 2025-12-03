<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;      
use App\Models\vehiculo;  

class Asignacion extends Model
{
    use HasFactory;

    protected $table = 'asignaciones';

    protected $fillable = [
        'idPaquete',
        'idMotorista',
        'idVehiculo',
        'fechaAsignacion',
    ];

    protected $casts = [
        'fechaAsignacion' => 'date',
    ];

    public function paquete()
    {
        return $this->belongsTo(Paquete::class, 'idPaquete');
    }

    // Relación con el modelo Vehiculo
    public function vehiculo()
    {
        return $this->belongsTo(Vehiculo::class, 'idVehiculo');
    }

    // Relación con el modelo User (Motorista)
    public function motorista()
    {
        return $this->belongsTo(User::class, 'idMotorista');
    }
}
