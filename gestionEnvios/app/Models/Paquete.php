<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Paquete extends Model
{
    protected $table = 'paquetes';
    protected $fillable = [
        'idDestinatario', 'idRemitente', 'idVehiculo', 
        'descripcion', 'peso', 'altura', 'fechaRegistro', 
        'fechaEstimadaEntrega', 'estadoActual'
    ];

    // Casts
    protected $casts = [
        'peso' => 'decimal:2',
        'altura' => 'decimal:2',
        'fechaCambio' => 'datetime'
    ];

    // Relaciones
    public function destinatario()
    {
        return $this->belongsTo(clientes::class, 'idDestinatario');
    }

    public function remitente()
    {
        return $this->belongsTo(clientes::class, 'idRemitente');
    }

    public function vehiculo()
    {
        return $this->belongsTo(vehiculo::class, 'idVehiculo');
    }

    public function historiales(): HasMany
    {
        return $this->hasMany(historial_envio::class, 'idPaquete');
    }

    // Métodos de utilidad
    public static function getEstados()
    {
        return ['Recoger', 'Almacén', 'Entregar'];
    }

    public function getBadgeColorAttribute()
    {
        return match($this->estadoActual) {
            'Recoger' => 'info',
            'Almacén' => 'secondary',
            'Entregar' => 'success',
            default => 'light'
        };
    }
}