<?php

use App\Models\SolicitudCooperativa;

public function index()
{
    $solicitudes = SolicitudCooperativa::all();
    return view('backoffice.index', compact('solicitudes'));
}

public function aceptar($id)
{
    $solicitud = SolicitudCooperativa::findOrFail($id);
    $solicitud->estado = 'aceptado';
    $solicitud->save();
    return redirect('/backoffice');
}

public function rechazar($id)
{
    $solicitud = SolicitudCooperativa::findOrFail($id);
    $solicitud->estado = 'rechazado';
    $solicitud->save();
    return redirect('/backoffice');
}
