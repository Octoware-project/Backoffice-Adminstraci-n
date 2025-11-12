@extends('layouts.app')
<link rel="stylesheet" href="{{ asset('css/Horas/ShowPlanTrabajo.css') }}">
@section('content')


<div class="detail-workspace">
    <!-- Header -->
    <div class="detail-header">
        <div class="header-content">
            <div>
                <h1 class="header-title">Plan de Trabajo</h1>
                <p class="header-subtitle">
                    {{ $plan->user->persona->name ?? $plan->user->name ?? '' }} {{ $plan->user->persona->apellido ?? '' }} - 
                    @php
                        $meses = ['', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 
                                 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
                    @endphp
                    {{ $meses[$plan->mes] ?? $plan->mes }} {{ $plan->anio }}
                </p>
            </div>
            <div class="header-buttons">
                <form id="delete-plan-form-{{ $plan->id }}" action="{{ route('plan-trabajos.destroy', $plan->id) }}" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="button" class="delete-btn" 
                            onclick="confirmDeletePlan({{ $plan->id }}, '{{ $plan->user->name }}', '{{ $plan->mes }}', {{ $plan->anio }})">
                        <i class="fas fa-trash-alt"></i>
                        Eliminar
                    </button>
                </form>
                <a href="{{ route('plan-trabajos.index') }}" class="back-btn">
                    <i class="fas fa-arrow-left"></i>
                    Volver
                </a>
            </div>
        </div>
    </div>

    <!-- Información Principal -->
    <div class="info-grid fade-in">
        <!-- Usuario -->
        <div class="info-card">
            <div class="card-icon" style="background: linear-gradient(135deg, var(--primary-color), var(--primary-light));">
                <i class="fas fa-user"></i>
            </div>
            <div class="card-title">Usuario</div>
            <div class="card-value">{{ $plan->user->persona->name ?? $plan->user->name ?? '' }} {{ $plan->user->persona->apellido ?? '' }}</div>
            <div class="card-subtitle">{{ $plan->user->email ?? '' }}</div>
        </div>

        <!-- Horas Requeridas -->
        <div class="info-card">
            <div class="card-icon" style="background: linear-gradient(135deg, var(--info-color), #74b9ff);">
                <i class="fas fa-calendar-check"></i>
            </div>
            <div class="card-title">Horas Requeridas</div>
            <div class="card-value">{{ $plan->horas_requeridas }}h</div>
            <div class="card-subtitle">Meta mensual</div>
        </div>

        <!-- Horas Trabajadas -->
        <div class="info-card">
            <div class="card-icon" style="background: linear-gradient(135deg, var(--success-color), #00d2d3);">
                <i class="fas fa-clock"></i>
            </div>
            <div class="card-title">Horas Completadas</div>
            <div class="card-value">{{ number_format($horas_trabajadas, 1) }}h</div>
            <div class="card-subtitle">
                @if($horas_justificadas > 0)
                    {{ number_format($horas_reales, 1) }}h reales + {{ number_format($horas_justificadas, 1) }}h justif.
                @else
                    Horas reales trabajadas
                @endif
            </div>
        </div>

        <!-- Porcentaje -->
        <div class="info-card">
            <div class="card-icon" style="background: linear-gradient(135deg, {{ $porcentaje >= 100 ? '#4ecdc4' : '#fc466b' }}, {{ $porcentaje >= 100 ? '#44a08d' : '#f9ca24' }});">
                <i class="fas fa-{{ $porcentaje >= 100 ? 'check-circle' : 'percentage' }}"></i>
            </div>
            <div class="card-title">Progreso</div>
            <div class="card-value" style="color: {{ $porcentaje >= 100 ? '#059669' : '#dc2626' }};">
                {{ $porcentaje }}%
            </div>
            <div class="card-subtitle">
                {{ $porcentaje >= 100 ? 'Plan completado' : 'En progreso' }}
            </div>
        </div>
    </div>

    <!-- Progreso Visual -->
    <div class="progress-container fade-in">
        <div class="progress-circle">
            <svg class="progress-svg" width="120" height="120">
                <circle cx="60" cy="60" r="54" stroke="#e2e8f0" stroke-width="8" fill="transparent"/>
                <circle cx="60" cy="60" r="54" stroke="{{ $porcentaje >= 100 ? '#4ecdc4' : '#667eea' }}" stroke-width="8" 
                        fill="transparent" stroke-linecap="round"
                        stroke-dasharray="{{ 2 * 3.14159 * 54 }}"
                        stroke-dashoffset="{{ 2 * 3.14159 * 54 * (1 - min($porcentaje, 100) / 100) }}"
                        style="transition: stroke-dashoffset 1s ease-in-out;"/>
            </svg>
            <div class="progress-text">{{ $porcentaje }}%</div>
        </div>
        
        @if($horas_justificadas > 0)
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-value" style="color: var(--info-color);">{{ number_format($horas_reales, 1) }}h</div>
                    <div class="stat-label">Horas Reales</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value" style="color: var(--warning-color);">{{ number_format($horas_justificadas, 1) }}h</div>
                    <div class="stat-label">Horas Justificadas</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value" style="color: var(--success-color);">{{ number_format($horas_trabajadas, 1) }}h</div>
                    <div class="stat-label">Total Equivalente</div>
                </div>
            </div>
        @endif
    </div>

    <!-- Tabla de Registros -->
    <div class="records-container fade-in">
        <div class="records-header">
            <h3 class="records-title">
                <i class="fas fa-list"></i>
                Registros de Horas del Mes
            </h3>
        </div>
        
        @if($horas->isEmpty())
            <div class="empty-state">
                <div class="empty-icon">
                    <i class="fas fa-calendar-times"></i>
                </div>
                <h4>No hay registros aún</h4>
                <p>Los registros de horas aparecerán aquí cuando sean ingresados.</p>
            </div>
        @else
            <table class="modern-table">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Horas</th>
                        <th>Motivo/Tipo</th>
                        <th>Compensación</th>
                        <th>Total Equivalente</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($horas as $h)
                    @php
                        $esJustificacion = $h->Monto_Compensario && $h->Monto_Compensario > 0;
                        $horasEquivalentes = $h->getHorasEquivalentes();
                        $horasReales = $h->Cantidad_Horas ?? 0;
                        $horasJustificadas = $horasEquivalentes - $horasReales;
                        
                        $claseRow = '';
                        if ($horasReales > 0 && $esJustificacion) {
                            $claseRow = 'record-mixed';
                        } elseif ($horasReales > 0) {
                            $claseRow = 'record-real';
                        } elseif ($esJustificacion) {
                            $claseRow = 'record-justified';
                        }
                    @endphp
                    <tr class="{{ $claseRow }}">
                        <!-- Fecha -->
                        <td>
                            <div style="display: flex; align-items: center; gap: 0.75rem;">
                                <div style="width: 2.5rem; height: 2.5rem; border-radius: 50%; background: {{ $esJustificacion ? '#fef3c7' : '#dbeafe' }}; display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-{{ $esJustificacion ? 'receipt' : 'clock' }}" style="color: {{ $esJustificacion ? '#d97706' : '#1e40af' }}; font-size: 0.875rem;"></i>
                                </div>
                                <div>
                                    <div style="font-weight: 600;">
                                        {{ str_pad($h->dia ?? 1, 2, '0', STR_PAD_LEFT) }}/{{ str_pad($h->mes, 2, '0', STR_PAD_LEFT) }}/{{ $h->anio }}
                                    </div>
                                    <div style="font-size: 0.75rem; color: var(--text-muted);">
                                        {{ $h->created_at->format('H:i') }}
                                    </div>
                                </div>
                            </div>
                        </td>

                        <!-- Horas -->
                        <td>
                            @if($horasReales > 0)
                                <span class="badge-modern badge-primary">
                                    <i class="fas fa-clock"></i>
                                    {{ $horasReales }}h reales
                                </span>
                            @endif
                            @if($esJustificacion && $horasJustificadas > 0)
                                @if($horasReales > 0)<br style="margin-bottom: 0.25rem;">@endif
                                <span class="badge-modern badge-warning">
                                    <i class="fas fa-receipt"></i>
                                    {{ number_format($horasJustificadas, 1) }}h justif.
                                </span>
                            @endif
                            @if(!$horasReales && !$esJustificacion)
                                <span style="color: var(--text-muted);">Sin horas</span>
                            @endif
                        </td>

                        <!-- Motivo/Tipo -->
                        <td>
                            @if($h->Motivo_Falla)
                                <div style="color: var(--danger-color); font-weight: 500;">{{ $h->Motivo_Falla }}</div>
                            @endif
                            @if($h->Tipo_Justificacion)
                                <span class="badge-modern badge-info">{{ $h->Tipo_Justificacion }}</span>
                            @endif
                            @if(!$h->Motivo_Falla && !$h->Tipo_Justificacion)
                                <span style="color: var(--text-muted);">Sin Motivo</span>
                            @endif
                        </td>

                        <!-- Compensación -->
                        <td>
                            @if($h->Monto_Compensario && $h->Monto_Compensario > 0)
                                <div>
                                    <div style="font-weight: 600; color: var(--success-color);">
                                        ${{ number_format($h->Monto_Compensario, 2) }}
                                    </div>
                                    @if($h->valor_hora_al_momento)
                                        <div style="font-size: 0.75rem; color: var(--text-muted);">
                                            ÷ ${{ number_format($h->valor_hora_al_momento, 0) }}/hr
                                        </div>
                                    @endif
                                </div>
                            @else
                                <span style="color: var(--text-muted);">Sin Compensación</span>
                            @endif
                        </td>

                        <!-- Total Equivalente -->
                        <td>
                            <div style="text-align: center;">
                                <div style="font-size: 1.125rem; font-weight: 700; color: var(--success-color);">
                                    {{ number_format($horasEquivalentes, 2) }}h
                                </div>
                                @if($horasJustificadas > 0 && $horasReales > 0)
                                    <div style="font-size: 0.75rem; color: var(--text-muted);">
                                        {{ number_format($horasReales, 1) }}h + {{ number_format($horasJustificadas, 1) }}h
                                    </div>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

    <!-- Resumen de Conversiones -->
    @if($horas_justificadas > 0)
    <div class="records-container fade-in">
        <div class="records-header">
            <h3 class="records-title">
                <i class="fas fa-calculator"></i>
                Resumen de Conversiones
            </h3>
        </div>
        <div style="padding: 2rem;">
            @php
                $registrosConJustificacion = $horas->filter(function($h) {
                    return $h->Monto_Compensario && $h->Monto_Compensario > 0;
                });
                $totalMontoJustificaciones = $registrosConJustificacion->sum('Monto_Compensario');
                $valorHoraPromedio = $registrosConJustificacion->avg('valor_hora_al_momento');
            @endphp
            
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-value" style="color: var(--success-color);">${{ number_format($totalMontoJustificaciones, 2) }}</div>
                    <div class="stat-label">Total Justificaciones</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value" style="color: var(--info-color);">{{ $registrosConJustificacion->count() }}</div>
                    <div class="stat-label">Registros con Justificación</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value" style="color: var(--warning-color);">${{ number_format($valorHoraPromedio, 0) }}</div>
                    <div class="stat-label">Valor/Hora Promedio</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value" style="color: var(--primary-color);">{{ number_format($horas_justificadas, 1) }}h</div>
                    <div class="stat-label">Horas Convertidas</div>
                </div>
            </div>
            
            <div style="background: #f0f9ff; border: 1px solid #bfdbfe; border-radius: var(--radius-sm); padding: 1rem; margin-top: 1.5rem;">
                <div style="display: flex; align-items: flex-start; gap: 0.5rem;">
                    <i class="fas fa-info-circle" style="color: var(--info-color); margin-top: 0.125rem;"></i>
                    <div style="font-size: 0.875rem; color: var(--text-secondary); line-height: 1.5;">
                        <strong>Explicación:</strong> Se convirtieron ${{ number_format($totalMontoJustificaciones, 2) }} 
                        en {{ number_format($horas_justificadas, 1) }} horas equivalentes usando los valores por hora 
                        vigentes al momento de cada registro.
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

<script src="{{ asset('js/Horas/ShowPlanTrabajo.js') }}"></script>
@endsection
