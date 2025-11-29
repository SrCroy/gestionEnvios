<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class vehiculo extends Model
{
    protected $table = "vehiculos";
    
    protected $fillable = [
        'marca',
        'modelo',
        'pesoMaximo',
        'volumenMaximo',
        'estado'
    ];

    // Constantes de estados
    const ESTADO_DISPONIBLE = 'Disponible';
    const ESTADO_EN_RUTA = 'En Ruta';
    const ESTADO_MANTENIMIENTO = 'Mantenimiento';
    const ESTADO_FUERA_SERVICIO = 'Fuera de Servicio';

    // Atributos por defecto
    protected $attributes = [
        'estado' => self::ESTADO_DISPONIBLE
    ];

    // Casting de atributos
    protected $casts = [
        'pesoMaximo' => 'decimal:2',
        'volumenMaximo' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * Obtener array de estados disponibles
     */
    public static function getEstados()
    {
        return [
            self::ESTADO_DISPONIBLE,
            self::ESTADO_EN_RUTA,
            self::ESTADO_MANTENIMIENTO,
            self::ESTADO_FUERA_SERVICIO
        ];
    }

    /**
     * Obtener clase CSS del badge según el estado
     */
    public function getEstadoBadgeAttribute()
    {
        return match($this->estado) {
            self::ESTADO_DISPONIBLE => 'success',
            self::ESTADO_EN_RUTA => 'primary',
            self::ESTADO_MANTENIMIENTO => 'warning',
            self::ESTADO_FUERA_SERVICIO => 'danger',
            default => 'secondary'
        };
    }

    /**
     * Obtener icono según el estado
     */
    public function getEstadoIconoAttribute()
    {
        return match($this->estado) {
            self::ESTADO_DISPONIBLE => 'check-circle-fill',
            self::ESTADO_EN_RUTA => 'truck',
            self::ESTADO_MANTENIMIENTO => 'wrench',
            self::ESTADO_FUERA_SERVICIO => 'x-circle-fill',
            default => 'circle'
        };
    }
}