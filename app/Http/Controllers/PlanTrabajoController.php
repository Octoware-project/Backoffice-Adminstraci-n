<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PlanTrabajo;
use App\Models\User;

class PlanTrabajoController extends Controller
{
    public function index()
    {
        $planes = PlanTrabajo::with('user')->get();
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
        PlanTrabajo::create($request->only('user_id', 'mes', 'anio', 'horas_requeridas'));
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
        $horas_trabajadas = $horas->sum('Cantidad_Horas');
    $porcentaje = $plan->horas_requeridas > 0 ? round(($horas_trabajadas / $plan->horas_requeridas) * 100, 2) : 0;
    $porcentaje = min($porcentaje, 100);
        return view('admin.horas.showPlanTrabajo', compact('plan', 'horas', 'horas_trabajadas', 'porcentaje'));
    }

    public function destroy($id)
    {
        PlanTrabajo::destroy($id);
        return redirect()->route('plan-trabajos.index')->with('success', 'Plan de trabajo eliminado');
    }
}
