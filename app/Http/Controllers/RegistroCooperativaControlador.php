<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RegistroCooperativaController extends Controller
{
    public function recibir(Request $request)
    {
        $data = $request->json()->all();

        \Log::info('JSON recibido:', $data);

        
public function recibir(Request $request)
{
    $data = $request->validate([
        'NombreCompleto' => 'required|string',
        'Cedula' => 'required|string',
        'Celular' => 'required|string',                      
        'fecha_nacimiento' => 'required|date',
        'Correo' => 'required|string',
        'Nacionalidad' => 'required|string',
        'EstadoCivil' => 'required|string',
        'IngresosTotales' => 'required|int',                       
    ]);
   
        return response()->json(['mensaje' => 'Solicitud recibida correctamente']);
    }
}
