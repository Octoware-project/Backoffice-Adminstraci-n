@extends('layouts.app')
<link rel="stylesheet" href="{{ asset('css/Horas/PlanTrabajos.css') }}">
@section('content')

<div class="planes-workspace">
    <!-- Header Principal -->
    <div class="planes-header">
        <div class="header-content">
            <div>
                <h1 class="header-title">Planes de Trabajo</h1>
                <p class="header-subtitle">Gestión y seguimiento de planes de trabajo asignados</p>
            </div>
            <div class="btn-group">
                <a href="{{ route('configuracion-horas.index') }}" class="btn-modern btn-secondary-modern">
                    <i class="fas fa-cog"></i>
                    Configurar Valor/Hora
                </a>
                <a href="{{ route('plan-trabajos.create') }}" class="btn-modern btn-primary-modern">
                    <i class="fas fa-plus"></i>
                    Crear Nuevo Plan
                </a>
            </div>
        </div>
    </div>

    <!-- Mostrar mensaje de éxito -->
    @if(session('success'))
        <div style="background: #d1fae5; border: 1px solid #6ee7b7; border-radius: var(--radius-sm); padding: 1rem; margin-bottom: 2rem; box-shadow: var(--shadow-sm);">
            <div style="display: flex; align-items: center; gap: 0.75rem;">
                <i class="fas fa-check-circle" style="color: #059669; font-size: 1.25rem;"></i>
                <p style="margin: 0; color: #047857; font-weight: 600; font-size: 1rem;">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    <!-- Sección de Filtros -->
    <div class="filters-section">
        <div class="filters-header" onclick="toggleFilters()">
            <div class="filters-toggle">
                <span><i class="fas fa-filter"></i> Filtros de búsqueda</span>
                <i class="fas fa-chevron-down" id="filters-chevron"></i>
            </div>
        </div>
        
        <div class="filters-content" id="filters-content">
            <form method="GET" action="{{ route('plan-trabajos.index') }}" id="filters-form">
                <div class="filters-grid">
                    <!-- Filtro por Persona -->
                    <div class="filter-group">
                        <label class="filter-label">
                            <i class="fas fa-user"></i>
                            Filtrar por Usuario
                        </label>
                        <select name="filter_user" class="filter-select">
                            <option value="">Todos los usuarios</option>
                            @foreach($planes->unique('user.email')->sortBy('user.name') as $plan)
                                @if($plan->user)
                                    <option value="{{ $plan->user->email }}" {{ request('filter_user') == $plan->user->email ? 'selected' : '' }}>
                                        {{ $plan->user->name }} {{ $plan->user->apellido ?? '' }}
                                    </option>
                                @endif
                            @endforeach
                        </select>
                    </div>

                    <!-- Filtro por Mes -->
                    <div class="filter-group">
                        <label class="filter-label">
                            <i class="fas fa-calendar"></i>
                            Filtrar por Mes
                        </label>
                        <select name="filter_month" class="filter-select">
                            <option value="">Todos los meses</option>
                            @php
                                $meses = [
                                    1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril',
                                    5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto',
                                    9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'
                                ];
                                $mesesUnicos = $planes->pluck('mes')->unique()->sort();
                            @endphp
                            @foreach($mesesUnicos as $mes)
                                <option value="{{ $mes }}" {{ request('filter_month') == $mes ? 'selected' : '' }}>
                                    {{ $meses[$mes] ?? $mes }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Filtro por Porcentaje -->
                    <div class="filter-group">
                        <label class="filter-label">
                            <i class="fas fa-percentage"></i>
                            Ordenar por Progreso
                        </label>
                        <select name="sort_progress" class="filter-select">
                            <option value="">Sin ordenar</option>
                            <option value="asc" {{ request('sort_progress') == 'asc' ? 'selected' : '' }}>
                                Ascendente (0% → 100%)
                            </option>
                            <option value="desc" {{ request('sort_progress') == 'desc' ? 'selected' : '' }}>
                                Descendente (100% → 0%)
                            </option>
                        </select>
                    </div>

                    <!-- Filtro por Estado -->
                    <div class="filter-group">
                        <label class="filter-label">
                            <i class="fas fa-check-circle"></i>
                            Estado de Completado
                        </label>
                        <select name="filter_completed" class="filter-select">
                            <option value="">Todos los estados</option>
                            <option value="completed" {{ request('filter_completed') == 'completed' ? 'selected' : '' }}>
                                Solo Completados (≥100%)
                            </option>
                            <option value="incomplete" {{ request('filter_completed') == 'incomplete' ? 'selected' : '' }}>
                                Solo Incompletos (<100%)
                            </option>
                        </select>
                    </div>

                    <!-- Filtro por Horas Totales -->
                    <div class="filter-group">
                        <label class="filter-label">
                            <i class="fas fa-clock"></i>
                            Ordenar por Horas Trabajadas
                        </label>
                        <select name="sort_hours" class="filter-select">
                            <option value="">Sin ordenar</option>
                            <option value="asc" {{ request('sort_hours') == 'asc' ? 'selected' : '' }}>
                                Ascendente (Menos → Más horas)
                            </option>
                            <option value="desc" {{ request('sort_hours') == 'desc' ? 'selected' : '' }}>
                                Descendente (Más → Menos horas)
                            </option>
                        </select>
                    </div>
                </div>

                <div class="filters-actions">
                    <a href="{{ route('plan-trabajos.index') }}" class="btn-modern btn-sm btn-outline-secondary">
                        <i class="fas fa-times"></i> Limpiar
                    </a>
                    <button type="submit" class="btn-modern btn-sm btn-outline-primary">
                        <i class="fas fa-search"></i> Aplicar Filtros
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Indicador de Filtros Activos -->
    @if(request()->filled('filter_user') || request()->filled('filter_month') || request()->filled('sort_progress') || request()->filled('filter_completed') || request()->filled('sort_hours'))
    <div style="background: #e0f2fe; border: 1px solid #0369a1; border-radius: var(--radius-sm); padding: 1rem; margin-bottom: 1rem;">
        <div style="display: flex; align-items: center; justify-content: space-between;">
            <div style="display: flex; align-items: center; gap: 0.5rem;">
                <i class="fas fa-info-circle" style="color: #0369a1;"></i>
                <span style="color: #0369a1; font-weight: 600; font-size: 0.875rem;">Filtros activos:</span>
                <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                    @if(request('filter_user'))
                        @php
                            $selectedUser = $planes->where('user.email', request('filter_user'))->first();
                        @endphp
                        <span style="background: #0369a1; color: white; padding: 0.25rem 0.75rem; border-radius: 12px; font-size: 0.75rem;">
                            Usuario: {{ $selectedUser->user->name ?? request('filter_user') }}
                        </span>
                    @endif
                    @if(request('filter_month'))
                        @php
                            $meses = [
                                1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril',
                                5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto',
                                9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'
                            ];
                        @endphp
                        <span style="background: #0369a1; color: white; padding: 0.25rem 0.75rem; border-radius: 12px; font-size: 0.75rem;">
                            Mes: {{ $meses[request('filter_month')] ?? request('filter_month') }}
                        </span>
                    @endif
                    @if(request('sort_progress'))
                        <span style="background: #0369a1; color: white; padding: 0.25rem 0.75rem; border-radius: 12px; font-size: 0.75rem;">
                            Progreso: {{ request('sort_progress') == 'asc' ? 'Ascendente' : 'Descendente' }}
                        </span>
                    @endif
                    @if(request('filter_completed'))
                        <span style="background: #0369a1; color: white; padding: 0.25rem 0.75rem; border-radius: 12px; font-size: 0.75rem;">
                            Estado: {{ request('filter_completed') == 'completed' ? 'Completados' : 'Incompletos' }}
                        </span>
                    @endif
                    @if(request('sort_hours'))
                        <span style="background: #0369a1; color: white; padding: 0.25rem 0.75rem; border-radius: 12px; font-size: 0.75rem;">
                            Horas: {{ request('sort_hours') == 'asc' ? 'Ascendente' : 'Descendente' }}
                        </span>
                    @endif
                </div>
            </div>
            <a href="{{ route('plan-trabajos.index') }}" style="color: #0369a1; text-decoration: none; font-size: 0.875rem; font-weight: 600;">
                <i class="fas fa-times"></i> Limpiar todos
            </a>
        </div>
    </div>
    @endif

    <!-- Tabla de Planes -->
    <div class="table-container">
        @if($planes->count() > 0)
            <table class="modern-table">
                <thead>
                    <tr>
                        <th>Usuario</th>
                        <th>Período</th>
                        <th>Horas Requeridas</th>
                        <th>Progreso</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($planes as $plan)
                    @php
                        $horasRegistros = \App\Models\Horas_Mensuales::where('email', $plan->user->email)
                            ->where('anio', $plan->anio)
                            ->where('mes', $plan->mes)
                            ->whereNull('deleted_at')
                            ->get();
                        
                        // Calcular horas equivalentes (reales + justificadas)
                        $horasEquivalentes = $horasRegistros->sum(function($hora) {
                            return $hora->getHorasEquivalentes();
                        });
                        
                        $porcentaje = $plan->horas_requeridas > 0 ? round(($horasEquivalentes / $plan->horas_requeridas) * 100, 2) : 0;
                        $porcentaje = min($porcentaje, 100);
                        $isCompleted = $porcentaje >= 100;
                        
                        $horasReales = $horasRegistros->sum('Cantidad_Horas') ?? 0;
                        $horasJustificadas = $horasEquivalentes - $horasReales;
                        
                        $meses = ['', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 
                                 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
                    @endphp
                    <tr onclick="window.location='{{ route('plan-trabajos.show', $plan->id) }}'" 
                        class="{{ $isCompleted ? 'plan-completed' : '' }}">
                        
                        <!-- Usuario -->
                        <td>
                            <div class="user-info">
                                <div class="user-avatar">
                                    {{ strtoupper(substr($plan->user->name ?? 'U', 0, 2)) }}
                                </div>
                                <div class="user-name">
                                    {{ $plan->user->name ?? $plan->user_id }}
                                </div>
                            </div>
                        </td>

                        <!-- Período -->
                        <td>
                            <div class="date-badge">
                                {{ $meses[$plan->mes] ?? $plan->mes }} {{ $plan->anio }}
                            </div>
                        </td>

                        <!-- Horas Requeridas -->
                        <td>
                            <strong>{{ $plan->horas_requeridas }}h</strong>
                        </td>

                        <!-- Progreso -->
                        <td>
                            <div class="progress-info">
                                <div class="progress-main" style="color: {{ $isCompleted ? '#059669' : '#2563eb' }}">
                                    {{ number_format($horasEquivalentes, 1) }}h / {{ $plan->horas_requeridas }}h
                                </div>
                                @if($horasJustificadas > 0)
                                    <div class="progress-detail">
                                        {{ number_format($horasReales, 1) }}h reales + {{ number_format($horasJustificadas, 1) }}h justif.
                                    </div>
                                @endif
                            </div>
                        </td>

                        <!-- Estado -->
                        <td>
                            <span class="status-badge {{ $isCompleted ? 'status-completed' : 'status-progress' }}">
                                {{ $porcentaje }}%
                                @if($isCompleted)
                                    <i class="fas fa-check-circle" style="margin-left: 0.25rem;"></i>
                                @endif
                            </span>
                        </td>

                        <!-- Acciones -->
                        <td onclick="event.stopPropagation();">
                            <div class="actions-group">
                                <a href="{{ route('plan-trabajos.show', $plan->id) }}" class="action-btn btn-view">
                                    <i class="fas fa-eye"></i>
                                    Ver
                                </a>
                                <form id="delete-plan-list-form-{{ $plan->id }}" action="{{ route('plan-trabajos.destroy', $plan->id) }}" method="POST" style="display:inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="action-btn btn-danger-modern" 
                                            onclick="confirmDeletePlanFromList({{ $plan->id }}, '{{ $plan->user->name ?? $plan->user_id }}', {{ $plan->mes }}, {{ $plan->anio }})">
                                        <i class="fas fa-trash-alt"></i>
                                        Eliminar
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="empty-state">
                <div class="empty-state-icon">
                    <i class="fas fa-calendar-times"></i>
                </div>
                <h3>No hay planes de trabajo</h3>
                <p>Comienza creando tu primer plan de trabajo.</p>
                <a href="{{ route('plan-trabajos.create') }}" class="btn-modern btn-primary-modern" style="margin-top: 1rem;">
                    <i class="fas fa-plus"></i>
                    Crear Primer Plan
                </a>
            </div>
        @endif
    </div>
</div>

<script src="{{ asset('js/Horas/PlanTrabajo.js') }}"></script>

@endsection
