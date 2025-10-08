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
        $query = Factura::where('Estado_Pago', 'Pendiente');
        
        // Aplicar filtros de año y mes
        if ($request->filled('año')) {
            $query->whereYear('fecha_pago', $request->año);
        }
        
        if ($request->filled('mes')) {
            $query->whereMonth('fecha_pago', $request->mes);
        }
        
        $facturas = $query->get();
        
        // Obtener el total de facturas pendientes (sin filtros) para la alerta
        $totalFacturasPendientes = Factura::where('Estado_Pago', 'Pendiente')->count();
        
        return view('admin.facturas.index', compact('facturas', 'totalFacturasPendientes'));
    }

    public function archivadas(Request $request)
    {
        $query = Factura::whereIn('Estado_Pago', ['Aceptado', 'Rechazado']);
        
        // Aplicar filtros
        if ($request->filled('año')) {
            $query->whereYear('fecha_pago', $request->año);
        }
        
        if ($request->filled('mes')) {
            $query->whereMonth('fecha_pago', $request->mes);
        }
        
        if ($request->filled('estado')) {
            $query->where('Estado_Pago', $request->estado);
        }
        
        $facturas = $query->get();
        return view('admin.facturas.archivadas', compact('facturas'));
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

    public function porUsuario(Request $request, $email)
    {
        $query = Factura::where('email', $email);
        
        // Filtrar por rechazadas si se especifica
        $mostrarRechazadas = $request->has('rechazadas') && $request->get('rechazadas') == '1';
        
        // Contar facturas rechazadas antes de aplicar filtros
        $facturasRechazadas = Factura::where('email', $email)->where('Estado_Pago', 'Rechazado')->count();
        
        // Si se solicita mostrar rechazadas pero no hay ninguna, redirigir a la vista normal
        if ($mostrarRechazadas && $facturasRechazadas == 0) {
            return redirect()->route('admin.facturas.usuario', $email)
                ->with('info', 'No hay facturas rechazadas para este usuario.');
        }
        
        if ($mostrarRechazadas) {
            $query->where('Estado_Pago', 'Rechazado');
        }
        
        // Ordenar por fecha de creación descendente (más nuevas primero)
        $facturas = $query->orderBy('created_at', 'desc')->get();
        $usuario = \App\Models\User::where('email', $email)->first();
        
        // Calcular estado de pagos (siempre con todas las facturas)
        $todasLasFacturas = Factura::where('email', $email)->get();
        $estadoPagos = $this->calcularEstadoPagos($todasLasFacturas);
        
        return view('admin.facturas.usuario', compact('facturas', 'usuario', 'estadoPagos', 'mostrarRechazadas', 'facturasRechazadas'));
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

    public function eliminar(Request $request, $id)
    {
        $factura = Factura::findOrFail($id);
        $email = $factura->email; // Guardar email antes de eliminar
        
        // Usar soft delete
        $factura->delete();
        
        // Verificar si viene desde la vista de usuario específico
        if ($request->has('from_user') && $request->get('from_user')) {
            // Si está filtrando rechazadas, mantener el filtro
            $params = [];
            if ($request->has('rechazadas')) {
                $params['rechazadas'] = $request->get('rechazadas');
            }
            
            return redirect()->route('admin.facturas.usuario', array_merge(['email' => $email], $params))
                ->with('success', 'Factura eliminada correctamente.');
        }
        
        return redirect()->route('admin.facturas.index')->with('success', 'Factura eliminada correctamente.');
    }
}
