@extends('layouts.app')

@section('content')
<style>
    /* Background de la página */
    body {
        background-color: #d2d2f1ff !important;
    }

    /* Reset y Variables */
    :root {
        --primary-color: #667eea;
        --primary-light: #764ba2;
        --secondary-color: #f093fb;
        --success-color: #4ecdc4;
        --warning-color: #ffecd2;
        --danger-color: #fc466b;
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
    }

    /* Container Principal */
    .planes-workspace {
        max-width: 1400px;
        margin: 0 auto;
        padding: 0 1.5rem;
        min-height: 100vh;
        background-color: #d2d2f1ff;
    }

    /* Header Moderno */
    .planes-header {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-light) 100%);
        border-radius: var(--radius);
        padding: 2rem 2.5rem;
        margin-bottom: 2rem;
        box-shadow: var(--shadow-lg);
        color: white;
        position: relative;
        overflow: hidden;
    }

    .planes-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="white" opacity="0.1"/><circle cx="75" cy="75" r="1" fill="white" opacity="0.1"/><circle cx="50" cy="10" r="0.5" fill="white" opacity="0.15"/><circle cx="20" cy="80" r="0.5" fill="white" opacity="0.15"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>') repeat;
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
        font-size: 2.25rem;
        font-weight: 700;
        letter-spacing: -0.025em;
    }

    .header-subtitle {
        margin: 0.5rem 0 0 0;
        font-size: 1.1rem;
        opacity: 0.9;
        font-weight: 400;
    }

    /* Botones Modernos */
    .btn-modern {
        display: inline-flex;
        align-items: center;
        gap: 0.75rem;
        padding: 1.1rem 2.2rem;
        border-radius: var(--radius-sm);
        font-weight: 600;
        font-size: 1.125rem;
        text-decoration: none;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        border: none;
        cursor: pointer;
        position: relative;
        overflow: hidden;
    }

    .btn-primary-modern {
        background: rgba(255, 255, 255, 0.15);
        color: white;
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .btn-primary-modern:hover {
        background: rgba(255, 255, 255, 0.25);
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        color: white;
        text-decoration: none;
    }

    .btn-secondary-modern {
        background: rgba(255, 255, 255, 0.9);
        color: var(--text-primary);
        border: 1px solid rgba(255, 255, 255, 0.3);
    }

    .btn-secondary-modern:hover {
        background: white;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        color: var(--text-primary);
        text-decoration: none;
    }

    .btn-group {
        display: flex;
        gap: 1rem;
    }

    /* Contenedor de Tabla Moderno */
    .table-container {
        background: var(--bg-primary);
        border-radius: var(--radius);
        box-shadow: var(--shadow-md);
        overflow: hidden;
        margin-bottom: 2rem;
    }

    /* Tabla Moderna */
    .modern-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        font-size: 0.875rem;
    }

    .modern-table thead {
        background: linear-gradient(90deg, #f8fafc 0%, #e2e8f0 100%);
    }

    .modern-table th {
        padding: 1rem 1.5rem;
        font-weight: 700;
        color: var(--text-primary);
        text-align: left;
        border-bottom: 2px solid var(--border-color);
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        position: sticky;
        top: 0;
        z-index: 10;
        background: var(--bg-light);
    }

    .modern-table td {
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid var(--border-color);
        vertical-align: middle;
        color: var(--text-primary);
        font-weight: 500;
    }

    .modern-table tbody tr {
        transition: all 0.2s ease;
        cursor: pointer;
    }

    .modern-table tbody tr:hover {
        background: linear-gradient(90deg, #f8fafc 0%, #f1f5f9 100%);
        transform: translateX(4px);
        box-shadow: inset 4px 0 0 var(--primary-color);
    }

    /* Estado de Plan Completado */
    .plan-completed {
        background: linear-gradient(90deg, #f0fff4 0%, #e6fffa 100%) !important;
        border-left: 4px solid var(--success-color);
    }

    .plan-completed:hover {
        background: linear-gradient(90deg, #e6fffa 0%, #c6f6d5 100%) !important;
        transform: translateX(4px);
        box-shadow: inset 4px 0 0 var(--success-color);
    }

    /* Badges y Estados */
    .status-badge {
        display: inline-flex;
        align-items: center;
        padding: 0.25rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 600;
        letter-spacing: 0.025em;
    }

    .status-completed {
        background: #d1fae5;
        color: #065f46;
    }

    .status-progress {
        background: #dbeafe;
        color: #1e40af;
    }

    /* Información de Progreso */
    .progress-info {
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
    }

    .progress-main {
        font-weight: 600;
        font-size: 1rem;
    }

    .progress-detail {
        font-size: 0.75rem;
        color: var(--text-secondary);
        font-weight: 400;
    }

    /* Acciones */
    .actions-group {
        display: flex;
        gap: 0.5rem;
        align-items: center;
        flex-wrap: wrap;
    }

    .action-btn {
        padding: 0.5rem 1rem;
        border-radius: var(--radius-sm);
        font-size: 0.75rem;
        font-weight: 600;
        border: none;
        cursor: pointer;
        transition: all 0.2s ease;
        text-transform: uppercase;
        letter-spacing: 0.025em;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        white-space: nowrap;
    }

    .btn-view {
        background: #e0f2fe;
        color: #0369a1;
        border: 1px solid #7dd3fc;
    }

    .btn-view:hover {
        background: #bae6fd;
        color: #0c4a6e;
        transform: translateY(-1px);
        text-decoration: none;
    }

    .btn-danger-modern {
        background: #fef2f2;
        color: #dc2626;
        border: 1px solid #fecaca;
    }

    .btn-danger-modern:hover {
        background: #fee2e2;
        color: #b91c1c;
        transform: translateY(-1px);
    }

    /* Usuario Avatar y Info */
    .user-info {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .user-avatar {
        width: 2.5rem;
        height: 2.5rem;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 600;
        font-size: 0.875rem;
    }

    .user-name {
        font-weight: 500;
        color: var(--text-primary);
    }

    /* Fecha Badge */
    .date-badge {
        background: #f8fafc;
        padding: 0.5rem 1rem;
        border-radius: var(--radius-sm);
        font-weight: 500;
        color: var(--text-secondary);
        border: 1px solid var(--border-color);
    }

    /* Responsivo */
    @media (max-width: 1024px) {
        .planes-workspace {
            padding: 0 1rem;
        }
        
        .planes-header {
            padding: 1.5rem 2rem;
        }
        
        .header-content {
            flex-direction: column;
            gap: 1.5rem;
            text-align: center;
        }
        
        .btn-group {
            flex-wrap: wrap;
            justify-content: center;
        }
    }

    @media (max-width: 768px) {
        .header-title {
            font-size: 1.875rem;
        }
        
        .modern-table th,
        .modern-table td {
            padding: 0.75rem 1rem;
        }
        
        .btn-modern {
            padding: 0.625rem 1.25rem;
            font-size: 0.8rem;
        }

        .actions-group {
            flex-direction: column;
            gap: 0.25rem;
        }

        .action-btn {
            padding: 0.375rem 0.75rem;
            font-size: 0.625rem;
        }
    }

    /* Estados vacíos */
    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        color: var(--text-secondary);
    }

    .empty-state-icon {
        font-size: 3rem;
        margin-bottom: 1rem;
        opacity: 0.5;
    }

    /* Sección de Filtros - Estilo moderno como unidades habitacionales */
    .filters-section {
        background: var(--bg-primary);
        border-radius: var(--radius);
        box-shadow: var(--shadow-md);
        margin-bottom: 2rem;
        overflow: hidden;
    }

    .filters-header {
        background: linear-gradient(90deg, var(--bg-light) 0%, var(--bg-secondary) 100%);
        padding: 1rem 1.5rem;
        border-bottom: 1px solid var(--border-color);
        cursor: pointer;
        user-select: none;
        transition: background 0.2s ease;
    }

    .filters-header:hover {
        background: linear-gradient(90deg, var(--bg-secondary) 0%, #e2e8f0 100%);
    }

    .filters-toggle {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-weight: 600;
        color: var(--text-primary);
        background: none;
        border: none;
        cursor: pointer;
        width: 100%;
    }

    .filters-content {
        padding: 1.5rem;
        display: none;
    }

    .filters-content.show {
        display: block;
        animation: slideDown 0.3s ease-out;
    }

    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .filters-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        margin-bottom: 1rem;
    }

    .filter-group {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .filter-label {
        font-weight: 600;
        color: var(--text-primary);
        font-size: 0.875rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .filter-input, .filter-select {
        padding: 0.75rem;
        border: 2px solid var(--border-color);
        border-radius: var(--radius-sm);
        font-size: 0.875rem;
        transition: border-color 0.2s;
        background: var(--bg-primary);
        color: var(--text-primary);
    }

    .filter-input:focus, .filter-select:focus {
        outline: none;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }

    .filter-select.active {
        background-color: #f0fdf4;
        border-color: #22c55e;
        color: #15803d;
    }

    .filters-actions {
        display: flex;
        gap: 1rem;
        justify-content: flex-end;
    }

    .btn-sm {
        padding: 0.5rem 1rem;
        font-size: 0.875rem;
        border-radius: var(--radius-sm);
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        cursor: pointer;
        transition: all 0.2s ease;
        border: 2px solid transparent;
    }

    .btn-outline-primary {
        background: transparent;
        color: var(--primary-color);
        border: 2px solid var(--primary-color);
    }

    .btn-outline-primary:hover {
        background: var(--primary-color);
        color: white;
        transform: translateY(-1px);
        text-decoration: none;
    }

    .btn-outline-secondary {
        background: transparent;
        color: var(--text-secondary);
        border: 2px solid var(--border-color);
    }

    .btn-outline-secondary:hover {
        background: var(--bg-light);
        border-color: var(--primary-color);
        color: var(--text-primary);
        transform: translateY(-1px);
        text-decoration: none;
    }

    /* Responsive design para filtros */
    @media (max-width: 768px) {
        .filters-grid {
            grid-template-columns: 1fr;
        }
        
        .filters-actions {
            justify-content: center;
        }
    }
</style>

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

<script>
    // Función para mostrar/ocultar filtros
    function toggleFilters() {
        const content = document.getElementById('filters-content');
        const chevron = document.getElementById('filters-chevron');
        
        if (content.classList.contains('show')) {
            content.classList.remove('show');
            chevron.className = 'fas fa-chevron-down';
        } else {
            content.classList.add('show');
            chevron.className = 'fas fa-chevron-up';
        }
    }

    // Función para limpiar filtros
    function clearFilters() {
        const form = document.getElementById('filters-form');
        const selects = form.querySelectorAll('select');
        
        selects.forEach(select => {
            select.value = '';
            select.classList.remove('active');
        });
        
        // Submit el formulario para aplicar el reset
        form.submit();
    }

    // Mostrar filtros si hay parámetros activos
    document.addEventListener('DOMContentLoaded', function() {
        // Función para aplicar estilos a filtros activos
        function updateFilterStyles() {
            const filterSelects = document.querySelectorAll('.filter-select');
            
            filterSelects.forEach(select => {
                if (select.value && select.value !== '') {
                    select.classList.add('active');
                } else {
                    select.classList.remove('active');
                }
            });
        }
        
        // Aplicar estilos al cargar la página
        updateFilterStyles();
        
        // Aplicar estilos cuando cambia el valor
        const filterSelects = document.querySelectorAll('.filter-select');
        filterSelects.forEach(select => {
            select.addEventListener('change', updateFilterStyles);
        });
        
        const urlParams = new URLSearchParams(window.location.search);
        const hasFilters = urlParams.has('filter_user') ||
                          urlParams.has('filter_month') ||
                          urlParams.has('sort_progress') || 
                          urlParams.has('filter_completed') || 
                          urlParams.has('sort_hours');
        
        if (hasFilters) {
            const content = document.getElementById('filters-content');
            const icon = document.getElementById('filter-icon');
            const text = document.getElementById('filter-text');
            
            content.classList.add('show');
            icon.className = 'fas fa-chevron-up';
            text.textContent = 'Ocultar Filtros';
        }
    });

    // Función para confirmar eliminación de plan de trabajo desde la lista
    function confirmDeletePlanFromList(planId, userName, mes, anio) {
        const meses = ['', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 
                       'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
        
        ModalConfirmation.create({
            title: 'Confirmar Eliminación de Plan',
            message: '¿Está seguro que desea eliminar el plan de trabajo:',
            detail: `"${userName} - ${meses[mes]} ${anio}"`,
            warning: 'Esta acción se puede revertir. El plan se marcará como eliminado.',
            confirmText: 'Eliminar Plan',
            cancelText: 'Cancelar',
            iconClass: 'fas fa-calendar-times',
            iconColor: '#dc2626',
            confirmColor: '#dc2626',
            onConfirm: function() {
                document.getElementById(`delete-plan-list-form-${planId}`).submit();
            }
        });
    }
</script>

@endsection
