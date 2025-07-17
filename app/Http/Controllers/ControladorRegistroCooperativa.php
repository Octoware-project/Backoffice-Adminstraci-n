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
}
