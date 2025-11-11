<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Factura;
use Illuminate\Http\Request;
use Carbon\Carbon;

class FacturaController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = Factura::where('Estado_Pago', 'Pendiente');
            
            if ($request->filled('año')) {
                $query->whereYear('fecha_pago', $request->año);
            }
            
            if ($request->filled('mes')) {
                $query->whereMonth('fecha_pago', $request->mes);
            }
            
            $facturas = $query->orderBy('created_at', 'desc')->get();
            
            $totalFacturasPendientes = Factura::where('Estado_Pago', 'Pendiente')->count();
            
            return view('facturas.index', compact('facturas', 'totalFacturasPendientes'));
        } catch (\Exception $e) {
            \Log::error('Error al listar facturas: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error al cargar las facturas.');
        }
    }

    public function archivadas(Request $request)
    {
        try {
            $query = Factura::whereIn('Estado_Pago', ['Aceptado', 'Rechazado']);
            
            if ($request->filled('año')) {
                $query->whereYear('fecha_pago', $request->año);
            }
            
            if ($request->filled('mes')) {
                $query->whereMonth('fecha_pago', $request->mes);
            }
            
            if ($request->filled('estado')) {
                $query->where('Estado_Pago', $request->estado);
            }
            
            $facturas = $query->orderBy('created_at', 'desc')->get();
            return view('facturas.archivadas', compact('facturas'));
        } catch (\Exception $e) {
            \Log::error('Error al listar facturas archivadas: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error al cargar las facturas archivadas.');
        }
    }


    public function cancelar(Request $request, $id)
    {
        try {
            $factura = Factura::findOrFail($id);
            $factura->Estado_Pago = 'Pendiente';
            $factura->save();
            
            if ($request->has('from_user') && $request->get('from_user')) {
                return redirect()->route('admin.facturas.usuario', $factura->email)
                    ->with('success', 'Estado de la factura restablecido a pendiente.');
            }
            
            return redirect()->route('admin.facturas.index')
                ->with('success', 'Estado de la factura restablecido a pendiente.');
        } catch (\Exception $e) {
            \Log::error('Error al cancelar factura: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error al restablecer el estado de la factura.');
        }
    }

    public function porUsuario(Request $request, $email)
    {
        try {
            $query = Factura::where('email', $email);
            
            $mostrarRechazadas = $request->has('rechazadas') && $request->get('rechazadas') == '1';
            
            $facturasRechazadas = Factura::where('email', $email)
                ->where('Estado_Pago', 'Rechazado')
                ->count();
            
            if ($mostrarRechazadas && $facturasRechazadas == 0) {
                return redirect()->route('admin.facturas.usuario', $email)
                    ->with('info', 'No hay facturas rechazadas para este usuario.');
            }
            
            if ($mostrarRechazadas) {
                $query->where('Estado_Pago', 'Rechazado');
            }
            
            $facturas = $query->orderBy('created_at', 'desc')->get();
            $usuario = \App\Models\User::where('email', $email)->first();
            
            $todasLasFacturas = Factura::where('email', $email)->get();
            $estadoPagos = $this->calcularEstadoPagos($todasLasFacturas);
            
            return view('facturas.usuario', compact('facturas', 'usuario', 'estadoPagos', 'mostrarRechazadas', 'facturasRechazadas'));
        } catch (\Exception $e) {
            \Log::error('Error al listar facturas por usuario: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error al cargar las facturas del usuario.');
        }
    }

    private function calcularEstadoPagos($facturas)
    {
        if ($facturas->isEmpty()) {
            return [
                'estado' => 'Sin facturas',
                'color' => 'secondary',
                'detalle' => 'No hay facturas registradas'
            ];
        }

        $facturasAceptadas = $facturas->where('Estado_Pago', 'Aceptado');
        $facturasPendientes = $facturas->where('Estado_Pago', 'Pendiente');
        $facturasRechazadas = $facturas->where('Estado_Pago', 'Rechazado');
        
        $totalFacturas = $facturas->count();
        $facturasAceptadasCount = $facturasAceptadas->count();
        $facturasPendientesCount = $facturasPendientes->count();
        $facturasRechazadasCount = $facturasRechazadas->count();
        
        $facturasNoPagadas = $facturasPendientesCount + $facturasRechazadasCount;
        
        if ($facturasNoPagadas == 0) {
            return [
                'estado' => 'Al día',
                'color' => 'success',
                'detalle' => 'Todas las facturas están aceptadas (' . $facturasAceptadasCount . '/' . $totalFacturas . ')'
            ];
        } elseif ($facturasNoPagadas == 1) {
            $detalle = $facturasPendientesCount > 0 ? 
                '1 factura pendiente de aprobación' : 
                '1 factura rechazada';
            return [
                'estado' => 'Pendiente',
                'color' => 'warning',
                'detalle' => $detalle
            ];
        } else {
            $pendientesText = $facturasPendientesCount > 0 ? $facturasPendientesCount . ' pendientes' : '';
            $rechazadasText = $facturasRechazadasCount > 0 ? $facturasRechazadasCount . ' rechazadas' : '';
            
            $detalle = trim($pendientesText . ($pendientesText && $rechazadasText ? ', ' : '') . $rechazadasText);
            
            return [
                'estado' => $facturasNoPagadas . ' facturas sin aprobar',
                'color' => 'danger',
                'detalle' => $detalle
            ];
        }
    }

    public function aceptar(Request $request, $id)
    {
        try {
            $factura = Factura::findOrFail($id);
            $factura->Estado_Pago = 'Aceptado';
            $factura->save();
            
            if ($request->has('from_user') && $request->get('from_user')) {
                return redirect()->route('admin.facturas.usuario', $factura->email)->with('success', 'Factura aceptada correctamente.');
            }
            
            return redirect()->route('admin.facturas.index')->with('success', 'Factura aceptada correctamente.');
        } catch (\Exception $e) {
            \Log::error('Error al aceptar factura: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error al aceptar la factura.');
        }
    }

    public function rechazar(Request $request, $id)
    {
        try {
            $factura = Factura::findOrFail($id);
            $factura->Estado_Pago = 'Rechazado';
            $factura->save();
            
            if ($request->has('from_user') && $request->get('from_user')) {
                return redirect()->route('admin.facturas.usuario', $factura->email)->with('success', 'Factura rechazada correctamente.');
            }
            
            return redirect()->route('admin.facturas.index')->with('success', 'Factura rechazada correctamente.');
        } catch (\Exception $e) {
            \Log::error('Error al rechazar factura: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error al rechazar la factura.');
        }
    }

    public function eliminar(Request $request, $id)
    {
        try {
            $factura = Factura::findOrFail($id);
            $email = $factura->email;
            
            $factura->delete();
            
            if ($request->has('from_user') && $request->get('from_user')) {
                $params = [];
                if ($request->has('rechazadas')) {
                    $params['rechazadas'] = $request->get('rechazadas');
                }
                
                return redirect()->route('admin.facturas.usuario', array_merge(['email' => $email], $params))
                    ->with('success', 'Factura eliminada correctamente.');
            }
            
            return redirect()->route('admin.facturas.index')->with('success', 'Factura eliminada correctamente.');
        } catch (\Exception $e) {
            \Log::error('Error al eliminar factura: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error al eliminar la factura.');
        }
    }
}
