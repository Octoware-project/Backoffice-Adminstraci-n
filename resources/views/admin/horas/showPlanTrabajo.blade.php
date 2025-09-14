@extends('layouts.app')

@section('content')
<style>
    .progress {
        height: 32px;
        background: #e9ecef;
        border-radius: 16px;
        overflow: hidden;
        margin-bottom: 1.5rem;
        box-shadow: 0 2px 8px rgba(0,0,0,0.04);
    }
    .progress-bar {
        height: 100%;
        background: linear-gradient(90deg, #2c3e50 60%, #2980b9 100%);
        color: #fff;
        font-weight: bold;
        display: flex;
        align-items: center;
        justify-content: flex-end;
        padding-right: 18px;
        font-size: 1.1rem;
        transition: width 0.5s;
    }
    .detalle-card {
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        padding: 2rem 2.5rem 1.5rem 2.5rem;
        margin-bottom: 2rem;
    }
    .detalle-label {
        color: #34495e;
        font-weight: 500;
        margin-bottom: 0.2rem;
    }
    .detalle-valor {
        font-size: 1.2rem;
        margin-bottom: 1rem;
    }
</style>
<div class="container-fluid px-4">
    <h1 class="mb-4">Detalle del Plan de Trabajo</h1>
    <div class="detalle-card">
        <div class="row mb-2">
            <div class="col-md-6">
                <div class="detalle-label">Usuario</div>
                    <div class="detalle-valor">{{ min($porcentaje, 100) }}%</div>
                <div class="text-muted" style="font-size:0.98rem;">{{ $plan->user->email ?? '' }}</div>
            </div>
            <div class="col-md-3">
                <div class="detalle-label">Mes</div>
                <div class="detalle-valor">{{ $plan->mes }}</div>
            </div>
            <div class="col-md-3">
                <div class="detalle-label">Año</div>
                <div class="detalle-valor">{{ $plan->anio }}</div>
            </div>
        </div>
        <div class="row mb-2">
            <div class="col-md-4">
                <div class="detalle-label">Horas requeridas</div>
                <div class="detalle-valor">{{ $plan->horas_requeridas }}</div>
            </div>
            <div class="col-md-4">
                <div class="detalle-label">Horas trabajadas</div>
                <div class="detalle-valor">{{ $horas_trabajadas }}</div>
            </div>
            <div class="col-md-4">
                <div class="detalle-label">Porcentaje cumplido</div>
                <div class="detalle-valor">{{ $porcentaje }}%</div>
            </div>
        </div>
        <div class="progress" title="{{ $porcentaje }}%">
            <div class="progress-bar" style="width: {{ $porcentaje }}%">
                {{ $porcentaje }}%
            </div>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header">Registros de Horas del Mes</div>
        <div class="card-body p-0">
            @if($horas->isEmpty())
                <div class="p-4 text-center text-muted">No hay registros aun</div>
            @else
                <table class="table table-striped mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Semana</th>
                            <th>Cantidad de Horas</th>
                            <th>Motivo Falla</th>
                            <th>Tipo Justificación</th>
                            <th>Monto Compensatorio</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($horas as $h)
                        <tr>
                            <td>{{ $h->Semana }}</td>
                            <td>{{ $h->Cantidad_Horas }}</td>
                            <td>{{ $h->Motivo_Falla }}</td>
                            <td>{{ $h->Tipo_Justificacion }}</td>
                            <td>{{ $h->Monto_Compensario }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
    <div class="mb-4"></div>
    <a href="{{ route('plan-trabajos.index') }}" class="btn btn-secondary mb-4" style="text-decoration:none; display:inline-block;">Volver</a>
</div>
@endsection
