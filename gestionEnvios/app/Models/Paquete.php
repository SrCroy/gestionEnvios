<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Paquete extends Model
{
    use HasFactory;

    protected $fillable = [
        'descripcion',
        'peso',
        'altura',
        'idDestinatario',
        'idRemitente',
        'estadoActual',
    ];

    // Relación con Asignacion
    public function asignacion()
    {
        return $this->hasOne(Asignacion::class, 'idPaquete');
    }

    // Relación con el destinatario
    public function destinatario()
    {
        return $this->belongsTo(User::class, 'idDestinatario');
    }

    // Relación con el remitente
    public function remitente()
    {
        return $this->belongsTo(User::class, 'idRemitente');
    }
}
