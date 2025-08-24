<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PagoMensual extends Model
{
    use HasFactory;

    protected $table = 'pagos_mensuales';

    protected $fillable = [
        'persona_id',
        'monto',
        'fecha_pago',
        'mes_correspondiente',
        'comprobante',  
        'estado'
    ];

    public function persona(): BelongsTo
    {
        return $this->belongsTo(Persona::class);
    }
}
