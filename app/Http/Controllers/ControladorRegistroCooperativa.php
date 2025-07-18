<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ControladorRegistroCooperativa extends Controller
{    
public function recibir()
    {
    $personas = Persona::where('Estado', 'pendiente')->get()->toArray();   
    return view('persona', ['personas' => $personas]);
    }
    
    public function AceptarPersona($id)
    {
    $persona = persona::findOrFail($id);
    $persona->Estado = 'Aceptado';
    $persona->save();
    }
    
    public function RechazarPersona($id){
    $persona = persona::findOrFail($id);
    $persona->Estado = 'Rechazado';    
    $persona->delete();
    }
}
