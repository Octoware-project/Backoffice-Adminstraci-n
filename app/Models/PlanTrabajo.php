<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlanTrabajo extends Model
{
    protected $table = 'plan_trabajos';
    protected $fillable = ['user_id', 'mes', 'anio', 'horas_requeridas'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
