@extends('layouts.app')

@section('content')
<style>
    /* Background de la página */
    body {
        background-color: #d2d2f1ff !important;
    }

    .badge-primary {
        background: #dbeafe;
        color: #1e40af;
    }    /* Variables CSS */
    :root {
        --primary-color: #667eea;
        --primary-light: #764ba2;
        --secondary-color: #f093fb;
        --success-color: #4ecdc4;
        --warning-color: #ffb74d;
        --danger-color: #fc466b;
        --info-color: #54a0ff;
        --text-primary: #1a202c;
        --text-secondary: #2d3748;
        --text-muted: #4a5568;
        --bg-primary: #ffffff;
        --bg-secondary: #f7fafc;
        --bg-light: #edf2f7;
        --border-color: #a0aec0;
        --shadow-sm: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
        --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        --radius: 12px;
        --radius-sm: 8px;
        --radius-lg: 16px;
    }

    /* Container Principal */
    .detail-workspace {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 1.5rem;
        min-height: 100vh;
        background-color: #d2d2f1ff;
    }

    /* Header del Detalle */
    .detail-header {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-light) 100%);
        border-radius: var(--radius);
        padding: 2rem 2.5rem;
        margin-bottom: 2rem;
        box-shadow: var(--shadow-lg);
        color: white;
        position: relative;
        overflow: hidden;
    }

    .detail-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="white" opacity="0.1"/><circle cx="75" cy="75" r="1" fill="white" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>') repeat;
        pointer-events: none;
    }

    .header-content {
        position: relative;
        z-index: 1;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .header-title {
        margin: 0;
        font-size: 2rem;
        font-weight: 700;
        letter-spacing: -0.025em;
    }

    .header-subtitle {
        margin: 0.5rem 0 0 0;
        font-size: 1rem;
        opacity: 0.9;
        font-weight: 400;
    }

    .back-btn {
        display: inline-flex;
        align-items: center;
        gap: 0.75rem;
        padding: 1.1rem 2.2rem;
        border-radius: var(--radius-sm);
        font-weight: 600;
        font-size: 1.125rem;
        text-decoration: none;
        transition: all 0.2s ease;
        background: rgba(255, 255, 255, 0.15);
        color: white;
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .back-btn:hover {
        background: rgba(255, 255, 255, 0.25);
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        color: white;
        text-decoration: none;
    }

    .delete-btn {
        display: inline-flex;
        align-items: center;
        gap: 0.75rem;
        padding: 1.1rem 2.2rem;
        border-radius: var(--radius-sm);
        font-weight: 600;
        font-size: 1.125rem;
        text-decoration: none;
        transition: all 0.2s ease;
        background: var(--danger-color);
        color: white;
        border: 1px solid var(--danger-color);
        cursor: pointer;
    }

    .delete-btn:hover {
        background: #e73463;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(252, 70, 107, 0.3);
        color: white;
        text-decoration: none;
        border-color: #e73463;
    }

    .header-buttons {
        display: flex;
        gap: 1rem;
        align-items: center;
    }

    /* Cards de Información */
    .info-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .info-card {
        background: var(--bg-primary);
        border-radius: var(--radius);
        padding: 1.5rem;
        box-shadow: var(--shadow-md);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        border: 1px solid var(--border-color);
    }

    .info-card:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-lg);
    }

    .card-icon {
        width: 3rem;
        height: 3rem;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1rem;
        font-size: 1.25rem;
        color: white;
    }

    .card-title {
        font-size: 0.75rem;
        color: var(--text-secondary);
        text-transform: uppercase;
        letter-spacing: 0.05em;
        font-weight: 600;
        margin-bottom: 0.25rem;
    }

    .card-value {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--text-primary);
        margin-bottom: 0.5rem;
    }

    .card-subtitle {
        font-size: 0.875rem;
        color: var(--text-muted);
    }

    /* Progreso Circular */
    .progress-container {
        background: var(--bg-primary);
        border-radius: var(--radius);
        padding: 2rem;
        box-shadow: var(--shadow-md);
        text-align: center;
        margin-bottom: 2rem;
        position: relative;
        overflow: hidden;
    }

    .progress-circle {
        width: 120px;
        height: 120px;
        margin: 0 auto 1rem;
        position: relative;
    }

    .progress-svg {
        transform: rotate(-90deg);
    }

    .progress-text {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--text-primary);
    }

    /* Tabla Moderna */
    .records-container {
        background: var(--bg-primary);
        border-radius: var(--radius);
        box-shadow: var(--shadow-md);
        overflow: hidden;
        margin-bottom: 2rem;
    }

    .records-header {
        background: linear-gradient(90deg, var(--bg-light) 0%, var(--bg-secondary) 100%);
        padding: 1.5rem 2rem;
        border-bottom: 1px solid var(--border-color);
    }

    .records-title {
        margin: 0;
        font-size: 1.25rem;
        font-weight: 600;
        color: var(--text-primary);
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .modern-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }

    .modern-table th {
        padding: 1rem 1.5rem;
        background: var(--bg-light);
        color: var(--text-primary);
        font-weight: 700;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        border-bottom: 2px solid var(--border-color);
        text-align: left;
    }

    .modern-table td {
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid var(--border-color);
        color: var(--text-primary);
        vertical-align: middle;
        font-weight: 500;
    }

    .modern-table tbody tr {
        transition: all 0.2s ease;
    }

    .modern-table tbody tr:hover {
        background: linear-gradient(90deg, #f8fafc 0%, #f1f5f9 100%);
    }

    /* Tipos de registros */
    .record-real {
        border-left: 4px solid var(--info-color);
        background: #f0f9ff !important;
    }

    .record-justified {
        border-left: 4px solid var(--warning-color);
        background: #fffbeb !important;
    }

    .record-mixed {
        border-left: 4px solid var(--success-color);
        background: #f0fdf4 !important;
    }

    /* Badges modernos */
    .badge-modern {
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        padding: 0.25rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 600;
        letter-spacing: 0.025em;
    }

    .badge-primary {
        background: #dbeafe;
        color: #1e40af;
    }

    .badge-warning {
        background: #fef3c7;
        color: #d97706;
    }

    .badge-success {
        background: #d1fae5;
        color: #065f46;
    }

    .badge-info {
        background: #e0f2fe;
        color: #0369a1;
    }

    /* Métricas y estadísticas */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        margin-bottom: 2rem;
    }

    .stat-card {
        background: var(--bg-primary);
        border-radius: var(--radius-sm);
        padding: 1.25rem;
        text-align: center;
        box-shadow: var(--shadow-sm);
        border: 1px solid var(--border-color);
        transition: transform 0.2s ease;
    }

    .stat-card:hover {
        transform: translateY(-1px);
    }

    .stat-value {
        font-size: 1.5rem;
        font-weight: 700;
        margin-bottom: 0.25rem;
    }

    .stat-label {
        font-size: 0.75rem;
        color: var(--text-secondary);
        text-transform: uppercase;
        letter-spacing: 0.05em;
        font-weight: 600;
    }

    /* Estado vacío */
    .empty-state {
        text-align: center;
        padding: 3rem 2rem;
        color: var(--text-muted);
    }

    .empty-icon {
        font-size: 3rem;
        margin-bottom: 1rem;
        opacity: 0.5;
    }

    /* Responsive Design */
    @media (max-width: 1200px) {
        .info-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 768px) {
        .detail-workspace {
            padding: 0 1rem;
        }
        
        .detail-header {
            padding: 1.5rem 1.75rem;
        }
        
        .header-content {
            flex-direction: column;
            gap: 1rem;
            text-align: center;
        }

        .header-buttons {
            flex-direction: column;
            width: 100%;
        }

        .back-btn, .delete-btn {
            padding: 0.875rem 1.5rem;
            font-size: 1rem;
            width: 100%;
            justify-content: center;
        }
        
        .info-grid {
            grid-template-columns: 1fr;
        }
        
        .modern-table {
            font-size: 0.8rem;
        }
        
        .modern-table th,
        .modern-table td {
            padding: 0.75rem 1rem;
        }
    }

    /* Animaciones */
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .fade-in {
        animation: fadeIn 0.5s ease-out forwards;
    }
</style>

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

<script>
    // Animación del progreso circular
    document.addEventListener('DOMContentLoaded', function() {
        const cards = document.querySelectorAll('.fade-in');
        cards.forEach((card, index) => {
            setTimeout(() => {
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, index * 100);
        });
    });

    // Función para confirmar eliminación de plan de trabajo
    function confirmDeletePlan(planId, userName, mes, anio) {
        const meses = ['', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 
                       'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
        
        ModalConfirmation.create({
            title: 'Confirmar Eliminación de Plan',
            message: '¿Está seguro que desea eliminar el plan de trabajo:',
            detail: `"${userName} - ${meses[mes]} ${anio}"`,
            warning: 'Esta acción se puede revertir desde la lista de planes eliminados.',
            confirmText: 'Eliminar Plan',
            cancelText: 'Cancelar',
            iconClass: 'fas fa-calendar-times',
            iconColor: '#dc2626',
            confirmColor: '#dc2626',
            onConfirm: function() {
                document.getElementById(`delete-plan-form-${planId}`).submit();
            }
        });
    }
</script>
@endsection
