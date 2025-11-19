<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PlanTrabajo;
use App\Models\User;

class PlanTrabajoController extends Controller
{
    public function index(Request $request)
    {
        try {
            $planes = PlanTrabajo::with('user')->get();
            
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
                
                $plan->horas_trabajadas = $horasEquivalentes;
                $plan->porcentaje = $porcentaje;
                $plan->is_completed = $porcentaje >= 100;
                
                return $plan;
            });
            
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
                        $planes = $planesWithMetrics->values();
            
            return view('horas.planTrabajos', compact('planes'));
        } catch (\Exception $e) {
            \Log::error('Error al listar planes de trabajo: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error al cargar los planes de trabajo.');
        }
    }

    public function create()
    {
        try {
            $usuarios = User::all();
            return view('horas.createPlanTrabajo', compact('usuarios'));
        } catch (\Exception $e) {
            \Log::error('Error al mostrar formulario de creación de plan: ' . $e->getMessage());
            return redirect()->route('plan-trabajos.index')->with('error', 'Error al cargar el formulario.');
        }
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'user_id' => 'required|exists:users,id',
                'mes' => 'required|integer|min:1|max:12',
                'anio' => 'required|integer',
                'horas_requeridas' => 'required|integer|min:1',
            ]);

            $user = User::with('persona.unidadHabitacional')->findOrFail($request->user_id);
            
            if (!$user->persona) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['user_id' => 'El usuario seleccionado no tiene un perfil de persona asociado.']);
            }

            $unidadHabitacionalId = null;
            if (!$user->persona->unidad_habitacional_id) {
                session()->flash('warning', 'ADVERTENCIA: Se ha creado el plan de trabajo, pero la persona no tiene una unidad habitacional asignada. Se recomienda asignar una unidad antes de comenzar a registrar horas.');
            } else {
                $unidadHabitacionalId = $user->persona->unidad_habitacional_id;
            }

            $existingPlan = PlanTrabajo::withTrashed()
                ->where('user_id', $request->user_id)
                ->where('mes', $request->mes)
                ->where('anio', $request->anio)
                ->whereNull('deleted_at')
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

            PlanTrabajo::create([
                'user_id' => $request->user_id,
                'unidad_habitacional_id' => $unidadHabitacionalId,
                'mes' => $request->mes,
                'anio' => $request->anio,
                'horas_requeridas' => $request->horas_requeridas
            ]);

            return redirect()->route('plan-trabajos.index')->with('success', 'Plan de trabajo creado exitosamente.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withInput()
                ->withErrors($e->validator);
        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->getCode() == 23000) {
                try {
                    $softDeletedPlan = PlanTrabajo::onlyTrashed()
                        ->where('user_id', $request->user_id)
                        ->where('mes', $request->mes)
                        ->where('anio', $request->anio)
                        ->first();
                    
                    if ($softDeletedPlan) {
                        $softDeletedPlan->restore();
                        $softDeletedPlan->update([
                            'horas_requeridas' => $request->horas_requeridas,
                            'unidad_habitacional_id' => $unidadHabitacionalId ?? null
                        ]);
                        return redirect()->route('plan-trabajos.index')->with('success', 'Plan de trabajo restaurado y actualizado.');
                    }
                } catch (\Exception $restoreError) {
                    \Log::error('Error al restaurar plan soft deleted: ' . $restoreError->getMessage());
                }
            }
            
            \Log::error('Error al crear plan de trabajo: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al crear el plan de trabajo. Por favor, verifique los datos.');
        } catch (\Exception $e) {
            \Log::error('Error general al crear plan de trabajo: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al crear el plan de trabajo.');
        }
    }


    public function show($id)
    {
        try {
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
            
            return view('horas.showPlanTrabajo', compact(
                'plan', 
                'horas', 
                'horas_trabajadas', 
                'horas_reales',
                'horas_justificadas',
                'porcentaje'
            ));
        } catch (\Exception $e) {
            \Log::error('Error al mostrar plan de trabajo: ' . $e->getMessage());
            return redirect()->route('plan-trabajos.index')->with('error', 'Error al cargar el plan de trabajo.');
        }
    }

    public function destroy($id)
    {
        try {
            $plan = PlanTrabajo::findOrFail($id);
            $plan->delete(); // Soft delete gracias al trait SoftDeletes
            
            return redirect()->route('plan-trabajos.index')->with('success', 'Plan de trabajo eliminado exitosamente');
        } catch (\Exception $e) {
            \Log::error('Error al eliminar plan de trabajo: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error al eliminar el plan de trabajo.');
        }
    }
}
