<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Factura;
use Illuminate\Http\Request;

class FacturaController extends Controller
{
    public function index(Request $request)
    {
        $estado = $request->input('estado');
        $query = Factura::query();
        if ($estado && in_array($estado, ['Pendiente', 'Aceptado', 'Rechazado'])) {
            $query->where('Estado_Pago', $estado);
        }
        $facturas = $query->get();
        return view('admin.facturas.index', compact('facturas', 'estado'));
    }


    public function cancelar($id)
    {
        $factura = Factura::findOrFail($id);
        $factura->Estado_Pago = 'Pendiente';
        $factura->save();
        return redirect()->route('admin.facturas.index')->with('success', 'Estado de la factura restablecido a pendiente.');
    }

    public function porUsuario($email)
    {
        $facturas = Factura::where('email', $email)->get();
        $usuario = \App\Models\User::where('email', $email)->first();
        return view('admin.facturas.usuario', compact('facturas', 'usuario'));
    }

    public function aceptar($id)
    {
        $factura = Factura::findOrFail($id);
        $factura->Estado_Pago = 'Aceptado';
        $factura->save();
        return redirect()->route('admin.facturas.index')->with('success', 'Factura aceptada correctamente.');
    }

    public function rechazar($id)
    {
        $factura = Factura::findOrFail($id);
        $factura->Estado_Pago = 'Rechazado';
        $factura->save();
        return redirect()->route('admin.facturas.index')->with('success', 'Factura rechazada correctamente.');
    }
}
