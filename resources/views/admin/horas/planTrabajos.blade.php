@extends('layouts.app')

@section('content')
<style>
    .planes-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 2rem;
    }
    .planes-table-container {
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        padding: 2rem 2.5rem 1.5rem 2.5rem;
        max-width: 1200px;
        margin: 0 auto;
    }
    .planes-table {
        width: 100%;
        min-width: 900px;
        font-size: 1.05rem;
    }
    .planes-table th, .planes-table td {
        vertical-align: middle;
    }
    .planes-table th {
        background: #2c3e50;
        color: #fff;
        font-weight: 500;
    }
    .planes-table tr {
        transition: background 0.15s;
    }
    .planes-table tr:hover {
        background: #f0f4fa;
    }
</style>
<div class="container-fluid px-4">
    <div class="planes-header">
        <h1 class="mb-0">Planes de Trabajo</h1>
        <a href="{{ route('plan-trabajos.create') }}" class="btn btn-primary">Crear Nuevo Plan</a>
    </div>
    <div class="planes-table-container">
        <div class="table-responsive">
            <table class="table planes-table table-striped table-bordered align-middle">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Usuario</th>
                        <th>Mes</th>
                        <th>Año</th>
                        <th>Horas Requeridas</th>
                        <th>Porcentaje</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($planes as $plan)
                    @php
                        $horas = \App\Models\Horas_Mensuales::where('email', $plan->user->email)
                            ->where('anio', $plan->anio)
                            ->where('mes', $plan->mes)
                            ->whereNull('deleted_at')
                            ->sum('Cantidad_Horas');
                        $porcentaje = $plan->horas_requeridas > 0 ? round(($horas / $plan->horas_requeridas) * 100, 2) : 0;
                        $porcentaje = min($porcentaje, 100);
                    @endphp
                    <tr ondblclick="window.location='{{ route('plan-trabajos.show', $plan->id) }}'" style="cursor:pointer;{{ $porcentaje == 100 ? 'background:#eafaf1;' : '' }}">
                        <td>{{ $plan->id }}</td>
                        <td>{{ $plan->user->name ?? $plan->user_id }}</td>
                        <td>{{ $plan->mes }}</td>
                        <td>{{ $plan->anio }}</td>
                        <td>{{ $plan->horas_requeridas }}</td>
                        <td>
                            @php
                                $horas = \App\Models\Horas_Mensuales::where('email', $plan->user->email)
                                    ->where('anio', $plan->anio)
                                    ->where('mes', $plan->mes)
                                    ->whereNull('deleted_at')
                                    ->sum('Cantidad_Horas');
                                $porcentaje = $plan->horas_requeridas > 0 ? round(($horas / $plan->horas_requeridas) * 100, 2) : 0;
                                $porcentaje = min($porcentaje, 100);
                            @endphp
                            <span style="font-weight:600; color: {{ ($porcentaje >= 100) ? '#27ae60' : '#2980b9' }}">{{ $porcentaje }}%</span>
                        </td>
                        <td>
                            <form action="{{ route('plan-trabajos.destroy', $plan->id) }}" method="POST" style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
