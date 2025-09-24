<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Factura;
use Illuminate\Http\Request;
use Carbon\Carbon;

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


    public function cancelar(Request $request, $id)
    {
        $factura = Factura::findOrFail($id);
        $factura->Estado_Pago = 'Pendiente';
        $factura->save();
        
        // Verificar si viene desde la vista de usuario específico
        if ($request->has('from_user') && $request->get('from_user')) {
            return redirect()->route('admin.facturas.usuario', $factura->email)->with('success', 'Estado de la factura restablecido a pendiente.');
        }
        
        return redirect()->route('admin.facturas.index')->with('success', 'Estado de la factura restablecido a pendiente.');
    }

    public function porUsuario($email)
    {
        $facturas = Factura::where('email', $email)->get();
        $usuario = \App\Models\User::where('email', $email)->first();
        
        // Calcular estado de pagos
        $estadoPagos = $this->calcularEstadoPagos($facturas);
        
        return view('admin.facturas.usuario', compact('facturas', 'usuario', 'estadoPagos'));
    }

    /**
     * Calcula el estado de los pagos del usuario
     */
    private function calcularEstadoPagos($facturas)
    {
        if ($facturas->isEmpty()) {
            return [
                'estado' => 'Sin facturas',
                'color' => 'secondary',
                'detalle' => 'No hay facturas registradas'
            ];
        }

        // Obtener facturas aceptadas y pendientes/rechazadas
        $facturasAceptadas = $facturas->where('Estado_Pago', 'Aceptado');
        $facturasPendientes = $facturas->where('Estado_Pago', 'Pendiente');
        $facturasRechazadas = $facturas->where('Estado_Pago', 'Rechazado');
        
        $totalFacturas = $facturas->count();
        $facturasAceptadasCount = $facturasAceptadas->count();
        $facturasPendientesCount = $facturasPendientes->count();
        $facturasRechazadasCount = $facturasRechazadas->count();
        
        // Calcular facturas no pagadas (pendientes + rechazadas)
        $facturasNoPagadas = $facturasPendientesCount + $facturasRechazadasCount;
        
        // Determinar estado y color basado en las facturas registradas
        if ($facturasNoPagadas == 0) {
            return [
                'estado' => 'Al día',
                'color' => 'success', // verde
                'detalle' => 'Todas las facturas están aceptadas (' . $facturasAceptadasCount . '/' . $totalFacturas . ')'
            ];
        } elseif ($facturasNoPagadas == 1) {
            $detalle = $facturasPendientesCount > 0 ? 
                '1 factura pendiente de aprobación' : 
                '1 factura rechazada';
            return [
                'estado' => 'Pendiente',
                'color' => 'warning', // amarillo
                'detalle' => $detalle
            ];
        } else {
            $pendientesText = $facturasPendientesCount > 0 ? $facturasPendientesCount . ' pendientes' : '';
            $rechazadasText = $facturasRechazadasCount > 0 ? $facturasRechazadasCount . ' rechazadas' : '';
            
            $detalle = trim($pendientesText . ($pendientesText && $rechazadasText ? ', ' : '') . $rechazadasText);
            
            return [
                'estado' => $facturasNoPagadas . ' facturas sin aprobar',
                'color' => 'danger', // rojo
                'detalle' => $detalle
            ];
        }
    }

    public function aceptar(Request $request, $id)
    {
        $factura = Factura::findOrFail($id);
        $factura->Estado_Pago = 'Aceptado';
        $factura->save();
        
        // Verificar si viene desde la vista de usuario específico
        if ($request->has('from_user') && $request->get('from_user')) {
            return redirect()->route('admin.facturas.usuario', $factura->email)->with('success', 'Factura aceptada correctamente.');
        }
        
        return redirect()->route('admin.facturas.index')->with('success', 'Factura aceptada correctamente.');
    }

    public function rechazar(Request $request, $id)
    {
        $factura = Factura::findOrFail($id);
        $factura->Estado_Pago = 'Rechazado';
        $factura->save();
        
        // Verificar si viene desde la vista de usuario específico
        if ($request->has('from_user') && $request->get('from_user')) {
            return redirect()->route('admin.facturas.usuario', $factura->email)->with('success', 'Factura rechazada correctamente.');
        }
        
        return redirect()->route('admin.facturas.index')->with('success', 'Factura rechazada correctamente.');
    }
}
