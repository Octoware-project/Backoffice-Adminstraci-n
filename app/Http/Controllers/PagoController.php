<?php

// app/Http/Controllers/PagoController.php
namespace App\Http\Controllers;

use App\Models\PagoMensual;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PagoController extends Controller
{
    
    // Residente sube su comprobante
    public function Insertar(Request $request)
    {
        $request->validate([
            'persona_id' => 'required|exists:personas,id',
            'monto' => 'required|numeric',
            'fecha_pago' => 'required|date',
            'mes_correspondiente' => 'required|string',
            'comprobante' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $comprobantePath = null;
        if ($request->hasFile('comprobante')) {
            $comprobantePath = $request->file('comprobante')->store('comprobantes', 'public');
        }

        PagoMensual::create([
            'persona_id' => $request->persona_id,
            'monto' => $request->monto,
            'fecha_pago' => $request->fecha_pago,
            'mes_correspondiente' => $request->mes_correspondiente,
            'comprobante' => $comprobantePath,
            'estado' => 'pendiente',
        ]);

        return response()->json(['message' => 'Pago registrado con éxito'], 201);
    }

    // Admin: ver todos los pagos pendientes
    public function index(Request $request)
    {
        $query = PagoMensual::with('persona')->orderBy('created_at', 'desc');
        if ($request->filled('fecha_pago')) {
            $query->whereDate('fecha_pago', $request->fecha_pago);
        }
        if ($request->filled('mes_correspondiente')) {
            $query->where('mes_correspondiente', $request->mes_correspondiente);
        }
        if ($request->filled('año_correspondiente')) {
            $query->where('año_correspondiente', $request->año_correspondiente);
        }
        $pagos = $query->get();
        return view('admin.pagos.index', compact('pagos'));
    }

    // Admin: aprobar pago
    public function aprobar($id)
    {
        $pago = PagoMensual::findOrFail($id);
        $pago->update(['estado' => 'aprobado']);
        return redirect()->back()->with('success', 'Pago aprobado');
    }

    // Admin: rechazar pago
    public function rechazar($id)
    {
        $pago = PagoMensual::findOrFail($id);
        $pago->update(['estado' => 'rechazado']);
        return redirect()->back()->with('success', 'Pago rechazado');
    }

    public function pagosPorPersona(Request $request, $persona_id)
    {
        $query = PagoMensual::with('persona')
            ->where('persona_id', $persona_id)
            ->orderBy('fecha_pago', 'desc');
        if ($request->filled('año_correspondiente')) {
            $query->where('año_correspondiente', $request->año_correspondiente);
        }
        if ($request->filled('mes_correspondiente')) {
            $query->where('mes_correspondiente', $request->mes_correspondiente);
        }
        $pagos = $query->get();
        return view('admin.pagos.por_persona', compact('pagos'));
    }
}
