<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class vehiculo extends Model
{
    //
    protected $table = "vehiculos";
    protected $fillable = [
        'marca',
        'modelo',
        'pesoMaximo',
        'volumenMaximo',
        'estado'
    ];

    const ESTADO_DISPONIBLE = 'Disponible';
    const ESTADO_EN_RUTA = 'En Ruta';
    const ESTADO_MANTENIMIENTO = 'Mantenimiento';
    const ESTADO_FUERA_SERVICIO = 'Fuera de Servicio';

    public static function getEstados(){
        return [
            self::ESTADO_DISPONIBLE,
            self::ESTADO_EN_RUTA,
            self::ESTADO_MANTENIMIENTO,
            self::ESTADO_FUERA_SERVICIO
        ];
    }

    public function getEstadoBadgeAttribute(){
        return match($this->estado){
            self::ESTADO_DISPONIBLE => "success",
            self::ESTADO_EN_RUTA => "primary",
            self::ESTADO_MANTENIMIENTO => "warning",
            self::ESTADO_FUERA_SERVICIO => "danger",
            default => 'secondary'
        };
    }
}
