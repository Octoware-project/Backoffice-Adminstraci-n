<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JuntasAsamblea extends Model
{
    use HasFactory;

    protected $fillable = [
        'lugar',
        'fecha',
        'detalle',
    ];
}
