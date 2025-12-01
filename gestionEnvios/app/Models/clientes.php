<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class clientes extends Model
{
    /** @use HasFactory<\Database\Factories\clientesFactory> */
    use HasFactory;
    protected $table = "clientes";

    public $filleable = [
        "nombre",
        "telefono",
        "direccion",
        "email",
        "latitud",
        "longitud"
    ];
}