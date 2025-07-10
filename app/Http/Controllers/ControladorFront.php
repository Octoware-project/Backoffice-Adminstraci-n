<?php

use App\Models\SolicitudRegistro;

public function index()
{
    $solicitudes = SolicitudRegistro::all();
    return view('backoffice.index', compact('solicitudes'));
}

public function aceptar($ID)
{
    $solicitud = SolicitudRegistro::findOrFail($ID);
    $solicitud->estado = 'aceptado';
    $solicitud->save();
    return redirect('/backoffice');
}

public function rechazar($ID)
{
    $solicitud = SolicitudRegistro::findOrFail($ID);
    $solicitud->estado = 'rechazado';
    $solicitud->save();
    return redirect('/backoffice');
}
