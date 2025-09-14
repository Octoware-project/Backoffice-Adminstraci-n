<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Factura extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'Factura';

    protected $fillable = [
        'persona_id',
        'email',
        'Monto',
        'Archivo_Comprobante',
        'Estado_Pago',
        'tipo_pago'
    ];

    public function persona()
    {
        return $this->belongsTo(Persona::class, 'persona_id');
    }

    // Relación con User por email
    public function user()
    {
        return $this->belongsTo(User::class, 'email', 'email');
    }
}
