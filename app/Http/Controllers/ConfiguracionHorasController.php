<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ConfiguracionHoras;
use App\Models\Horas_Mensuales;

class ConfiguracionHorasController extends Controller
{

    public function index()
    {
        $configuracion = ConfiguracionHoras::getConfiguracionActual();
        $historial = ConfiguracionHoras::getHistorial(5);
        
        $totalRegistrosConJustificacion = Horas_Mensuales::whereNotNull('Monto_Compensario')
            ->where('Monto_Compensario', '>', 0)
            ->count();
            
        $totalRegistrosCalculados = Horas_Mensuales::whereNotNull('horas_equivalentes_calculadas')
            ->count();
        
        return view('admin.horas.configuracion', compact(
            'configuracion', 
            'historial', 
            'totalRegistrosConJustificacion',
            'totalRegistrosCalculados'
        ));
    }

    public function update(Request $request)
    {
        $request->validate([
            'valor_por_hora' => 'required|numeric|min:0',
            'observaciones' => 'nullable|string|max:500'
        ]);

        try {
            ConfiguracionHoras::where('activo', true)->update(['activo' => false]);
            
            $nuevaConfig = ConfiguracionHoras::create([
                'valor_por_hora' => $request->valor_por_hora,
                'activo' => true,
                'observaciones' => $request->observaciones
            ]);

            return redirect()->back()->with('success', 
                'Valor por hora actualizado a $' . number_format($request->valor_por_hora, 2) . 
                '. Los nuevos registros usarán este valor.'
            );

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 
                'Error al actualizar la configuración: ' . $e->getMessage()
            );
        }
    }


    public function recalcularRegistros()
    {
        try {
            $valorActual = ConfiguracionHoras::getValorActual();
            
            if ($valorActual <= 0) {
                return redirect()->back()->with('error', 
                    'Debe configurar un valor por hora antes de recalcular registros.'
                );
            }

            $registros = Horas_Mensuales::whereNotNull('Monto_Compensario')
                ->where('Monto_Compensario', '>', 0)
                ->whereNull('valor_hora_al_momento')
                ->get();

            $contadorActualizados = 0;
            
            foreach ($registros as $registro) {
                $registro->valor_hora_al_momento = $valorActual;
                $horasReales = $registro->Cantidad_Horas ?? 0;
                $horasDeJustificacion = $registro->Monto_Compensario / $valorActual;
                $registro->horas_equivalentes_calculadas = $horasReales + $horasDeJustificacion;
                $registro->save();
                $contadorActualizados++;
            }

            return redirect()->back()->with('success', 
                "Se recalcularon {$contadorActualizados} registros usando el valor actual de $" . 
                number_format($valorActual, 2) . " por hora."
            );

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 
                'Error al recalcular registros: ' . $e->getMessage()
            );
        }
    }

    public function historial()
    {
        $configuraciones = ConfiguracionHoras::orderBy('created_at', 'desc')
            ->paginate(10);
            
        return view('admin.horas.historialConfiguracion', compact('configuraciones'));
    }
}