<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ControladorRegistroCooperativa extends Controller
{    
public function recibir()
    {
        $data = Persona::where('Estado_Registro', 'Pendiente')->get();                    
        ]);

        $PersonaArray = $data->toArray();
    
    }
}
