<?php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class clientes extends Authenticatable
{
    protected $table = 'clientes';

    protected $fillable = [
        'nombre',
        'telefono',
        'direccion',
        'email',
        'latitud',
        'longitud',
        'password'
    ];

    protected $hidden = ['password'];
}
