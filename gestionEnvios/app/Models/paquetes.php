<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class paquetes extends Model
{
    protected $table = 'paquetes';
    
    protected $fillable = [
        'idDestinatario', 
        'idRemitente', 
        'idVehiculo', 
        'descripcion', 
        'peso', 
        'altura', 
        'fechaRegistro', 
        'fechaEstimadaEntrega', 
        'estadoActual'
    ];

    protected $casts = [
        'fechaRegistro' => 'date',
        'fechaEstimadaEntrega' => 'date',
        'peso' => 'decimal:2',
        'altura' => 'decimal:2',
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
}