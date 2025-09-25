<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PlanTrabajo;
use App\Models\User;

class PlanTrabajoController extends Controller
{
    public function index(Request $request)
    {
        $planes = PlanTrabajo::with('user')->get();
        
        // Calcular métricas para cada plan y almacenar en la colección
        $planesWithMetrics = $planes->map(function($plan) {
            $horasRegistros = \App\Models\Horas_Mensuales::where('email', $plan->user->email)
                ->where('anio', $plan->anio)
                ->where('mes', $plan->mes)
                ->whereNull('deleted_at')
                ->get();
            
            $horasEquivalentes = $horasRegistros->sum(function($hora) {
                return $hora->getHorasEquivalentes();
            });
            
            $porcentaje = $plan->horas_requeridas > 0 ? round(($horasEquivalentes / $plan->horas_requeridas) * 100, 2) : 0;
            $porcentaje = min($porcentaje, 100);
            
            // Agregar métricas calculadas al plan
            $plan->horas_trabajadas = $horasEquivalentes;
            $plan->porcentaje = $porcentaje;
            $plan->is_completed = $porcentaje >= 100;
            
            return $plan;
        });
        
        // Aplicar filtros
        if ($request->filled('filter_user')) {
            $planesWithMetrics = $planesWithMetrics->filter(function($plan) use ($request) {
                return $plan->user->email === $request->filter_user;
            });
        }
        
        if ($request->filled('filter_month')) {
            $planesWithMetrics = $planesWithMetrics->filter(function($plan) use ($request) {
                return $plan->mes == $request->filter_month;
            });
        }
        
        if ($request->filled('filter_completed')) {
            if ($request->filter_completed === 'completed') {
                $planesWithMetrics = $planesWithMetrics->filter(function($plan) {
                    return $plan->is_completed;
                });
            } elseif ($request->filter_completed === 'incomplete') {
                $planesWithMetrics = $planesWithMetrics->filter(function($plan) {
                    return !$plan->is_completed;
                });
            }
        }
        
        // Aplicar ordenamientos
        if ($request->filled('sort_progress')) {
            if ($request->sort_progress === 'asc') {
                $planesWithMetrics = $planesWithMetrics->sortBy('porcentaje');
            } elseif ($request->sort_progress === 'desc') {
                $planesWithMetrics = $planesWithMetrics->sortByDesc('porcentaje');
            }
        }
        
        if ($request->filled('sort_hours')) {
            if ($request->sort_hours === 'asc') {
                $planesWithMetrics = $planesWithMetrics->sortBy('horas_trabajadas');
            } elseif ($request->sort_hours === 'desc') {
                $planesWithMetrics = $planesWithMetrics->sortByDesc('horas_trabajadas');
            }
        }
        
        // Convertir de nuevo a colección indexada
        $planes = $planesWithMetrics->values();
        
        return view('admin.horas.planTrabajos', compact('planes'));
    }

    public function create()
    {
        $usuarios = User::all();
        return view('admin.horas.createPlanTrabajo', compact('usuarios'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'mes' => 'required|integer|min:1|max:12',
            'anio' => 'required|integer',
            'horas_requeridas' => 'required|integer|min:1',
        ]);

        // Verificar si ya existe un plan activo (no soft deleted) para este usuario, mes y año
        $existingPlan = PlanTrabajo::withTrashed()
            ->where('user_id', $request->user_id)
            ->where('mes', $request->mes)
            ->where('anio', $request->anio)
            ->whereNull('deleted_at') // Solo planes que no están soft deleted
            ->first();

        if ($existingPlan) {
            $usuario = User::find($request->user_id);
            $meses = [
                1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril',
                5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto',
                9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'
            ];
            
            return redirect()->back()
                ->withInput()
                ->withErrors([
                    'duplicate' => "Ya existe un plan de trabajo activo para {$usuario->name} en {$meses[$request->mes]} {$request->anio}."
                ]);
        }

        // Usar try-catch para manejar el error de restricción única de la base de datos
        try {
            PlanTrabajo::create($request->only('user_id', 'mes', 'anio', 'horas_requeridas'));
        } catch (\Illuminate\Database\QueryException $e) {
            // Si hay error de restricción única, verificar si hay un plan soft deleted
            if ($e->getCode() == 23000) {
                // Buscar si existe un plan soft deleted para este usuario/mes/año
                $softDeletedPlan = PlanTrabajo::onlyTrashed()
                    ->where('user_id', $request->user_id)
                    ->where('mes', $request->mes)
                    ->where('anio', $request->anio)
                    ->first();
                
                if ($softDeletedPlan) {
                    // Si existe un plan soft deleted, lo restauramos y actualizamos
                    $softDeletedPlan->restore();
                    $softDeletedPlan->update(['horas_requeridas' => $request->horas_requeridas]);
                } else {
                    // Si no es por soft delete, relanzar el error
                    throw $e;
                }
            } else {
                throw $e;
            }
        }
        return redirect()->route('plan-trabajos.index')->with('success', 'Plan de trabajo creado');
    }


    public function show($id)
    {
        $plan = PlanTrabajo::with('user')->findOrFail($id);
        // Buscar horas mensuales del usuario para el mes/año del plan
        $horas = \App\Models\Horas_Mensuales::where('email', $plan->user->email)
            ->where('anio', $plan->anio)
            ->where('mes', $plan->mes)
            ->whereNull('deleted_at')
            ->get();
        
        // Calcular horas trabajadas usando horas equivalentes
        $horas_trabajadas = $horas->sum(function($hora) {
            return $hora->getHorasEquivalentes();
        });
        
        // Separar horas reales y justificadas para mostrar desglose
        $horas_reales = $horas->sum('Cantidad_Horas');
        $horas_justificadas = $horas_trabajadas - $horas_reales;
        
        $porcentaje = $plan->horas_requeridas > 0 ? round(($horas_trabajadas / $plan->horas_requeridas) * 100, 2) : 0;
        $porcentaje = min($porcentaje, 100);
        
        return view('admin.horas.showPlanTrabajo', compact(
            'plan', 
            'horas', 
            'horas_trabajadas', 
            'horas_reales',
            'horas_justificadas',
            'porcentaje'
        ));
    }

    public function destroy($id)
    {
        $plan = PlanTrabajo::findOrFail($id);
        $plan->delete(); // Soft delete gracias al trait SoftDeletes
        return redirect()->route('plan-trabajos.index')->with('success', 'Plan de trabajo eliminado exitosamente');
    }
}
