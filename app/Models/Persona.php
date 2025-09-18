<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Persona extends Model
{
    use HasFactory;

    protected $table = 'personas';

    protected $fillable = [
        'name',
        'apellido',
        "CI",
        "Telefono",
        "Direccion",
        "Estado_Registro",
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
