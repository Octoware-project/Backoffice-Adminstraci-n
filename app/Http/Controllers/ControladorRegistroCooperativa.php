<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ControladorRegistroCooperativa extends Controller
{
    public function recibir(Request $request)
    {
        $data = $request->json()->all();

        \Log::info('JSON recibido:', $data);

        
public function recibir(Request $request)
{
    $data = $request->validate([
        'ID' => 'required|string',                       
        'Nombre_Completo' => 'required|string',
        'Cedula' => 'required|string',
        'Celular' => 'required|string',                      
        'Fecha_Nacimiento' => 'required|date',
        'Correo' => 'required|string',
        'Nacionalidad' => 'required|string',
        'Estado_Civil' => 'required|string',
        'IngresosTotales' => 'required|int',                       
    ]);
   $data['Estado'] = 'pendiente';
    
        return response()->json(['mensaje' => 'Solicitud recibida correctamente']);
    }
}
