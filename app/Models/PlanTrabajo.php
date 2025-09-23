<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class PlanTrabajo extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'plan_trabajos';
    protected $fillable = ['user_id', 'mes', 'anio', 'horas_requeridas'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
