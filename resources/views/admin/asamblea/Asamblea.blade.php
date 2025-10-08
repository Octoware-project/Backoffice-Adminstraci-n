@extends('layouts.app')

@section('content')
<style>
    /* Background de la página */
    body {
        background-color: #d2d2f1ff !important;
        overflow-y: auto;
        overflow-x: hidden;
    }

    /* Ocultar barra de scroll pero mantener funcionalidad */
    /* Para navegadores Webkit (Chrome, Safari, Edge) */
    body::-webkit-scrollbar {
        width: 0px;
        background: transparent;
    }

    body::-webkit-scrollbar-track {
        background: transparent;
    }

    body::-webkit-scrollbar-thumb {
        background: transparent;
    }

    /* Para Firefox */
    html {
        scrollbar-width: none;
        -ms-overflow-style: none;
    }

    /* Fallback para otros navegadores */
    body {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }

    /* Aplicar a cualquier elemento con scroll en la página */
    .asamblea-workspace::-webkit-scrollbar,
    .table-container::-webkit-scrollbar,
    *::-webkit-scrollbar {
        width: 0px;
        background: transparent;
    }

    .asamblea-workspace::-webkit-scrollbar-track,
    .table-container::-webkit-scrollbar-track,
    *::-webkit-scrollbar-track {
        background: transparent;
    }

    .asamblea-workspace::-webkit-scrollbar-thumb,
    .table-container::-webkit-scrollbar-thumb,
    *::-webkit-scrollbar-thumb {
        background: transparent;
    }

    /* Para elementos específicos en Firefox */
    .asamblea-workspace,
    .table-container {
        scrollbar-width: none;
        -ms-overflow-style: none;
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
    .asamblea-workspace {
        max-width: 1400px;
        margin: 0 auto;
        padding: 0 1.5rem 2rem;
        background-color: #d2d2f1ff;
    }

    /* Header Moderno */
    .asamblea-header {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-light) 100%);
        border-radius: var(--radius);
        padding: 2rem 2.5rem;
        margin-bottom: 2rem;
        box-shadow: var(--shadow-lg);
        color: white;
        position: relative;
        overflow: hidden;
    }

    .asamblea-header::before {
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

    /* Tabla Moderna para Juntas */
    .table-container {
        background: var(--bg-primary);
        border-radius: var(--radius);
        box-shadow: var(--shadow-md);
        overflow: hidden;
        margin-bottom: 2rem;
    }

    .modern-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        font-size: 0.875rem;
        table-layout: fixed;
    }

    .modern-table thead {
        background: linear-gradient(90deg, #f8fafc 0%, #e2e8f0 100%);
    }

    .modern-table thead th {
        padding: 1.25rem 1.5rem;
        font-weight: 700;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: var(--text-secondary);
        border-bottom: 2px solid var(--border-color);
        position: relative;
    }

    /* Distribución específica de columnas */
    .modern-table th:nth-child(1), /* Lugar */
    .modern-table td:nth-child(1) {
        width: 20%;
        min-width: 120px;
    }

    .modern-table th:nth-child(2), /* Fecha */
    .modern-table td:nth-child(2) {
        width: 15%;
        min-width: 100px;
    }

    .modern-table th:nth-child(3), /* Detalle */
    .modern-table td:nth-child(3) {
        width: 40%;
        min-width: 180px;
        word-wrap: break-word;
        overflow-wrap: break-word;
    }

    .modern-table th:nth-child(4), /* Acciones */
    .modern-table td:nth-child(4) {
        width: 25%;
        min-width: 260px;
    }

    .modern-table tbody tr {
        transition: all 0.2s ease;
        cursor: pointer;
        border-bottom: 1px solid #f1f5f9;
    }

    .modern-table tbody tr:hover {
        background: linear-gradient(90deg, #f8fafc 0%, #f1f5f9 100%);
        transform: translateX(4px);
        box-shadow: inset 4px 0 0 var(--primary-color);
    }

    .modern-table tbody td {
        padding: 1.25rem 1.5rem;
        color: var(--text-primary);
        font-weight: 500;
        vertical-align: middle;
    }

    .modern-table tbody td:first-child {
        font-weight: 600;
        color: var(--primary-color);
    }

    /* Celdas clickeables */
    .clickable-cell {
        cursor: pointer;
    }

    /* Columna de acciones */
    .actions-cell {
        text-align: center;
        padding: 0.75rem 0.5rem !important;
        vertical-align: middle;
        width: auto;
    }

    /* Botones de acción - Estilo igual a unidades habitacionales */
    .action-buttons {
        display: flex;
        flex-direction: row;
        gap: 0.25rem;
        justify-content: center;
        flex-wrap: wrap;
        align-items: center;
        width: 100%;
        padding: 0;
        margin: 0;
    }

    .btn-view, .btn-edit, .btn-delete {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.25rem;
        padding: 0.5rem 0.75rem;
        border-radius: 8px;
        font-size: 0.7rem;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.2s ease;
        border: 1px solid;
        cursor: pointer;
        text-transform: uppercase;
        letter-spacing: 0.025em;
        white-space: nowrap;
        flex: 1;
        max-width: 80px;
        min-width: 60px;
    }

    .btn-view {
        background: #e0f2fe;
        color: #0369a1;
        border-color: #7dd3fc;
    }

    .btn-view:hover {
        background: #bae6fd;
        color: #0c4a6e;
        transform: translateY(-1px);
        text-decoration: none;
    }

    .btn-edit {
        background: #fef3c7;
        color: #d97706;
        border-color: #fde68a;
    }

    .btn-edit:hover {
        background: #fde68a;
        color: #b45309;
        transform: translateY(-1px);
        text-decoration: none;
    }

    .btn-delete {
        background: #fef2f2;
        color: #dc2626;
        border-color: #fecaca;
    }

    .btn-delete:hover {
        background: #fee2e2;
        color: #b91c1c;
        transform: translateY(-1px);
    }

    /* Evitar propagación del hover de la fila en la columna de acciones */
    .modern-table tbody tr:hover .actions-cell {
        transform: none !important;
        background: transparent !important;
    }

    .actions-cell {
        padding: 0.5rem !important;
        vertical-align: middle !important;
    }

    /* Estado vacío */
    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        color: var(--text-muted);
    }

    .empty-state-icon {
        font-size: 3rem;
        margin-bottom: 1rem;
        opacity: 0.5;
    }

    .empty-state-text {
        font-size: 1.125rem;
        font-weight: 500;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .asamblea-workspace {
            padding: 0 1rem 2rem;
        }
        
        .asamblea-header {
            padding: 1.5rem 2rem;
        }
        
        .header-title {
            font-size: 1.875rem;
        }
        
        .modern-table thead th,
        .modern-table tbody td {
            padding: 0.75rem 0.5rem;
        }

        /* Redistribución de columnas en móvil */
        .modern-table th:nth-child(1), 
        .modern-table td:nth-child(1) {
            width: 25%;
            min-width: 80px;
        }

        .modern-table th:nth-child(2), 
        .modern-table td:nth-child(2) {
            width: 18%;
            min-width: 70px;
        }

        .modern-table th:nth-child(3), 
        .modern-table td:nth-child(3) {
            width: 32%;
            min-width: 120px;
        }

        .modern-table th:nth-child(4), 
        .modern-table td:nth-child(4) {
            width: 45% !important;
            min-width: 320px !important;
        }
    }

    @media (max-width: 900px) {
        /* Cuando no hay espacio suficiente - Media query intermedio */
        .modern-table th:nth-child(4), 
        .modern-table td:nth-child(4) {
            width: 45%;
            min-width: 250px;
        }
        
        .action-buttons {
            gap: 0.2rem;
        }
        
        .btn-view, .btn-edit, .btn-delete {
            font-size: 0.6rem;
            padding: 0.4rem 0.6rem;
            flex: 1;
            max-width: 70px;
            min-width: 50px;
        }
    }

    @media (max-width: 640px) {
        /* Mantener botones horizontales incluso en pantallas pequeñas */
        .action-buttons {
            flex-direction: row;
            gap: 0.15rem;
            flex-wrap: nowrap;
            justify-content: space-between;
        }
        
        .btn-view, .btn-edit, .btn-delete {
            flex: 1;
            min-width: 45px;
            max-width: 65px;
            font-size: 0.55rem;
            padding: 0.3rem 0.4rem;
            gap: 0.2rem;
        }

        .modern-table th:nth-child(4), 
        .modern-table td:nth-child(4) {
            width: 50%;
            min-width: 200px;
        }

        .modern-table {
            font-size: 0.75rem;
        }

        .modern-table thead th {
            font-size: 0.65rem;
            padding: 0.75rem 0.5rem;
        }

        .actions-cell {
            padding: 0.5rem 0.25rem !important;
        }
    }

    @media (max-width: 1024px) {
        .header-content {
            flex-direction: column;
            gap: 1.5rem;
            text-align: center;
        }
    }

    /* ===== ESTILOS DE FILTROS ===== */

    /* Sección de Filtros */
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
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
        margin-bottom: 1rem;
    }

    .filter-group {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .filter-label {
        font-size: 0.875rem;
        font-weight: 600;
        color: var(--text-primary);
        display: flex;
        align-items: center;
        gap: 0.25rem;
    }

    .filter-select, .filter-input {
        padding: 0.75rem 1rem;
        border: 2px solid var(--border-color);
        border-radius: var(--radius-sm);
        background: var(--bg-primary);
        color: var(--text-primary);
        font-size: 0.875rem;
        transition: all 0.2s ease;
    }

    .filter-select:focus, .filter-input:focus {
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
        padding-top: 1rem;
        border-top: 1px solid var(--border-color);
    }

    .filter-btn {
        padding: 0.75rem 1.5rem;
        border-radius: var(--radius-sm);
        font-weight: 600;
        font-size: 0.875rem;
        border: none;
        cursor: pointer;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .filter-btn-primary {
        background: var(--primary-color);
        color: white;
    }

    .filter-btn-primary:hover {
        background: var(--primary-light);
        transform: translateY(-1px);
    }

    .filter-btn-secondary {
        background: var(--bg-secondary);
        color: var(--text-primary);
        border: 1px solid var(--border-color);
    }

    .filter-btn-secondary:hover {
        background: var(--bg-light);
        transform: translateY(-1px);
    }

    /* Botón para invertir orden */
    .filter-btn-order {
        width: 100%;
        padding: 0.75rem 1rem;
        border: 2px solid var(--border-color);
        border-radius: var(--radius-sm);
        background: var(--bg-primary);
        color: var(--text-primary);
        font-size: 0.875rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }

    .filter-btn-order:hover {
        background: var(--bg-secondary);
        border-color: var(--primary-color);
        color: var(--primary-color);
        transform: translateY(-1px);
    }

    .filter-btn-order:active {
        transform: translateY(0);
        background: var(--primary-color);
        color: white;
    }

    .filter-btn-order i {
        font-size: 0.75rem;
    }

    /* Indicador de Filtros Activos */
    .active-filters-indicator {
        background: #e0f2fe;
        border: 1px solid #0369a1;
        border-radius: var(--radius-sm);
        padding: 1rem;
        margin-bottom: 1rem;
    }

    .active-filters-content {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .active-filters-info {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        flex-wrap: wrap;
    }

    .active-filters-tags {
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
    }

    .filter-tag {
        background: #0369a1;
        color: white;
        padding: 0.25rem 0.75rem;
        border-radius: 12px;
        font-size: 0.75rem;
    }

    .clear-filters-link {
        color: #0369a1;
        text-decoration: none;
        font-size: 0.875rem;
        font-weight: 600;
    }

    .clear-filters-link:hover {
        color: #0c4a6e;
        text-decoration: underline;
    }

    /* Responsive para filtros */
    @media (max-width: 1024px) {
        .filters-grid {
            grid-template-columns: 1fr;
        }
        
        .active-filters-content {
            flex-direction: column;
            align-items: flex-start;
        }
    }

    @media (max-width: 768px) {
        .filters-section {
            padding: 1rem;
        }
        
        .filters-header {
            flex-direction: column;
            gap: 1rem;
            align-items: flex-start;
        }
        
        .filters-actions {
            flex-direction: column;
            gap: 0.5rem;
        }
        
        .filter-btn {
            width: 100%;
            justify-content: center;
        }
    }
</style>

<div class="asamblea-workspace">
    <!-- Header Moderno -->
    <div class="asamblea-header">
        <div class="header-content">
            <div>
                <h1 class="header-title">Juntas de Asamblea</h1>
                <p class="header-subtitle">Gestión y seguimiento de asambleas cooperativas</p>
            </div>
            <div class="btn-group">
                <a href="{{ route('admin.juntas_asamblea.create') }}" class="btn-modern btn-primary-modern">
                    <i class="fas fa-plus"></i>
                    Nueva Junta
                </a>
            </div>
        </div>
    </div>

    <!-- Sección de Filtros -->
    <div class="filters-section">
        <div class="filters-header" onclick="toggleFilters()" style="cursor: pointer;">
            <div class="filters-toggle">
                <span><i class="fas fa-filter"></i> Filtros de búsqueda</span>
                <i class="fas fa-chevron-down" id="filters-chevron"></i>
            </div>
        </div>
        
        <div class="filters-content" id="filters-content">
            <form method="GET" action="{{ route('admin.juntas_asamblea.index') }}" id="filters-form">
                <div class="filters-grid">
                    {{-- Filtro por Mes --}}
                    <div class="filter-group">
                        <label class="filter-label">
                            <i class="fas fa-calendar-alt"></i>
                            Filtrar por Mes
                        </label>
                        <select name="filter_mes" id="filter_mes" class="filter-select">
                            <option value="">Todos los meses</option>
                            <option value="1" {{ request('filter_mes') == '1' ? 'selected' : '' }}>Enero</option>
                            <option value="2" {{ request('filter_mes') == '2' ? 'selected' : '' }}>Febrero</option>
                            <option value="3" {{ request('filter_mes') == '3' ? 'selected' : '' }}>Marzo</option>
                            <option value="4" {{ request('filter_mes') == '4' ? 'selected' : '' }}>Abril</option>
                            <option value="5" {{ request('filter_mes') == '5' ? 'selected' : '' }}>Mayo</option>
                            <option value="6" {{ request('filter_mes') == '6' ? 'selected' : '' }}>Junio</option>
                            <option value="7" {{ request('filter_mes') == '7' ? 'selected' : '' }}>Julio</option>
                            <option value="8" {{ request('filter_mes') == '8' ? 'selected' : '' }}>Agosto</option>
                            <option value="9" {{ request('filter_mes') == '9' ? 'selected' : '' }}>Septiembre</option>
                            <option value="10" {{ request('filter_mes') == '10' ? 'selected' : '' }}>Octubre</option>
                            <option value="11" {{ request('filter_mes') == '11' ? 'selected' : '' }}>Noviembre</option>
                            <option value="12" {{ request('filter_mes') == '12' ? 'selected' : '' }}>Diciembre</option>
                        </select>
                    </div>

                    {{-- Filtro por Año --}}
                    <div class="filter-group">
                        <label class="filter-label">
                            <i class="fas fa-calendar"></i>
                            Filtrar por Año
                        </label>
                        <input type="number" 
                               name="filter_anio" 
                               id="filter_anio" 
                               class="filter-select" 
                               placeholder="Ingrese el año..."
                               min="2000" 
                               max="2030" 
                               value="{{ request('filter_anio') }}">
                    </div>

                    {{-- Campo de Ordenamiento --}}
                    <div class="filter-group">
                        <label class="filter-label">
                            <i class="fas fa-sort"></i>
                            Ordenar por
                        </label>
                        <select name="sort_field" id="sort_field" class="filter-select">
                            <option value="fecha" {{ request('sort_field', 'fecha') == 'fecha' ? 'selected' : '' }}>
                                Fecha de Junta
                            </option>
                            <option value="lugar" {{ request('sort_field') == 'lugar' ? 'selected' : '' }}>
                                Lugar Alfabético
                            </option>
                            <option value="created_at" {{ request('sort_field') == 'created_at' ? 'selected' : '' }}>
                                Fecha de Creación
                            </option>
                        </select>
                    </div>

                    {{-- Botón para Invertir Orden --}}
                    <div class="filter-group">
                        <label class="filter-label">
                            <i class="fas fa-exchange-alt"></i>
                            Invertir Orden
                        </label>
                        <button type="button" class="filter-btn-order" id="toggle-order">
                            @if(request('sort_direction', 'desc') == 'desc')
                                <i class="fas fa-arrow-up"></i>
                                Invertir a Ascendente
                            @else
                                <i class="fas fa-arrow-down"></i>
                                Invertir a Descendente
                            @endif
                        </button>
                        <input type="hidden" name="sort_direction" id="hidden_sort_direction" value="{{ request('sort_direction', 'desc') }}">
                    </div>
                </div>

                <div class="filters-actions">
                    <button type="button" class="filter-btn filter-btn-secondary" id="clear-filters">
                        <i class="fas fa-times"></i>
                        Limpiar Filtros
                    </button>
                    <button type="submit" class="filter-btn filter-btn-primary" id="apply-filters">
                        <i class="fas fa-search"></i>
                        Aplicar Filtros
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Indicador de Filtros Activos -->
    @if(request()->filled('filter_mes') || request()->filled('filter_anio') || (request()->filled('sort_field') && request('sort_field') != 'fecha') || (request()->filled('sort_direction') && request('sort_direction') != 'desc'))
    <div class="active-filters-indicator">
        <div class="active-filters-content">
            <div class="active-filters-info">
                <i class="fas fa-info-circle" style="color: #0369a1;"></i>
                <span style="color: #0369a1; font-weight: 600; font-size: 0.875rem;">Filtros activos:</span>
                <div class="active-filters-tags">
                    @if(request('filter_mes'))
                        @php
                            $meses = ['', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 
                                     'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
                        @endphp
                        <span class="filter-tag">Mes: {{ $meses[request('filter_mes')] ?? request('filter_mes') }}</span>
                    @endif
                    @if(request('filter_anio'))
                        <span class="filter-tag">Año: {{ request('filter_anio') }}</span>
                    @endif
                    @if(request('sort_field') && request('sort_field') != 'fecha')
                        <span class="filter-tag">
                            Ordenar por: 
                            @switch(request('sort_field'))
                                @case('lugar') Lugar @break
                                @case('created_at') Fecha de Creación @break
                                @default Fecha de Junta @endswitch
                        </span>
                    @endif
                    @if(request('sort_direction') && request('sort_direction') != 'desc')
                        <span class="filter-tag">Orden: Ascendente</span>
                    @endif
                </div>
            </div>
            <a href="{{ route('admin.juntas_asamblea.index') }}" class="clear-filters-link">
                <i class="fas fa-times"></i> Limpiar todos
            </a>
        </div>
    </div>
    @endif

    <!-- Tabla de Juntas -->
    <div class="table-container">
        <table class="modern-table">
            <thead>
                <tr>
                    <th>Lugar</th>
                    <th>Fecha</th>
                    <th>Detalle</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($juntas as $junta)
                    <tr>
                        <td class="clickable-cell" onclick="window.location='{{ route('admin.juntas_asamblea.show', [$junta->id]) }}';">
                            {{ $junta->lugar }}
                        </td>
                        <td class="clickable-cell" onclick="window.location='{{ route('admin.juntas_asamblea.show', [$junta->id]) }}';">
                            {{ \Carbon\Carbon::parse($junta->fecha)->format('d/m/Y') }}
                        </td>
                        <td class="clickable-cell" onclick="window.location='{{ route('admin.juntas_asamblea.show', [$junta->id]) }}';">
                            {{ Str::limit($junta->detalle, 80) }}
                        </td>
                        <td class="actions-cell" onclick="event.stopPropagation();">
                            <div class="action-buttons">
                                <a href="{{ route('admin.juntas_asamblea.show', $junta->id) }}" 
                                   class="btn-view" 
                                   title="Ver detalles">
                                    <i class="fas fa-eye"></i>
                                    Ver
                                </a>
                                <a href="{{ route('admin.juntas_asamblea.edit', $junta->id) }}" 
                                   class="btn-edit" 
                                   title="Editar junta">
                                    <i class="fas fa-edit"></i>
                                    Editar
                                </a>
                                <button class="btn-delete" onclick="confirmDelete({{ $junta->id }}, '{{ $junta->lugar }}')" title="Eliminar junta">
                                    <i class="fas fa-trash-alt"></i>
                                    Eliminar
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="empty-state">
                            <div class="empty-state-icon">📋</div>
                            <div class="empty-state-text">No hay juntas registradas</div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Formulario oculto para eliminación -->
<form id="delete-form" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

<script>
function confirmDelete(juntaId, lugar) {
    // Prevenir propagación del evento click
    event.stopPropagation();
    
    // Verificar si ya existe un modal y eliminarlo
    const existingModal = document.querySelector('.delete-modal');
    if (existingModal) {
        closeDeleteModal();
        return;
    }
    
    // Crear modal de confirmación personalizado con estilos inline
    const modal = document.createElement('div');
    modal.className = 'delete-modal';
    modal.style.cssText = `
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: 9999;
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: opacity 0.2s ease;
    `;
    
    modal.innerHTML = `
        <div class="delete-modal-overlay" onclick="closeDeleteModal()" style="
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(4px);
        "></div>
        <div class="delete-modal-content" style="
            background: white;
            border-radius: 12px;
            padding: 0;
            max-width: 400px;
            width: 90%;
            position: relative;
            z-index: 1;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            transform: scale(0.95) translateY(-10px);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        ">
            <div class="delete-modal-header" style="
                padding: 1.5rem;
                text-align: center;
                border-bottom: 1px solid #f1f5f9;
            ">
                <div class="delete-icon" style="
                    width: 60px;
                    height: 60px;
                    border-radius: 50%;
                    background: #fef2f2;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    margin: 0 auto 1rem;
                ">
                    <i class="fas fa-exclamation-triangle" style="
                        font-size: 1.5rem;
                        color: #dc2626;
                    "></i>
                </div>
                <h3 style="
                    margin: 0;
                    color: #1f2937;
                    font-size: 1.125rem;
                    font-weight: 600;
                    font-family: inherit;
                ">Confirmar Eliminación</h3>
            </div>
            <div class="delete-modal-body" style="
                padding: 1.5rem;
                text-align: center;
            ">
                <p style="
                    margin: 0 0 0.5rem 0;
                    color: #4b5563;
                    font-family: inherit;
                ">¿Está seguro que desea eliminar la junta:</p>
                <strong style="
                    color: #1f2937;
                    font-weight: 600;
                    font-family: inherit;
                ">"${lugar}"</strong>
                <p class="warning-text" style="
                    color: #dc2626;
                    font-size: 0.875rem;
                    margin: 1rem 0 0 0;
                    font-family: inherit;
                ">Esta acción no se puede deshacer.</p>
            </div>
            <div class="delete-modal-actions" style="
                padding: 1rem 1.5rem 1.5rem;
                display: flex;
                gap: 0.75rem;
                justify-content: flex-end;
            ">
                <button class="btn-cancel" onclick="closeDeleteModal()" style="
                    padding: 0.75rem 1.5rem;
                    border: 1px solid #d1d5db;
                    border-radius: 6px;
                    font-weight: 600;
                    font-size: 0.875rem;
                    cursor: pointer;
                    transition: all 0.2s ease;
                    min-width: 80px;
                    background: #f9fafb;
                    color: #374151;
                    font-family: inherit;
                ">Cancelar</button>
                <button class="btn-confirm" onclick="deleteJunta(${juntaId})" style="
                    padding: 0.75rem 1.5rem;
                    border: none;
                    border-radius: 6px;
                    font-weight: 600;
                    font-size: 0.875rem;
                    cursor: pointer;
                    transition: all 0.2s ease;
                    min-width: 80px;
                    background: #dc2626;
                    color: white;
                    font-family: inherit;
                ">Eliminar</button>
            </div>
        </div>
    `;
    
    // Agregar modal al DOM
    document.body.appendChild(modal);
    document.body.style.overflow = 'hidden';
    
    // Animar entrada del modal
    setTimeout(() => {
        modal.style.opacity = '1';
        const content = modal.querySelector('.delete-modal-content');
        content.style.transform = 'scale(1) translateY(0)';
    }, 10);
    
    // Event listeners para los botones con hover effects
    const cancelBtn = modal.querySelector('.btn-cancel');
    const confirmBtn = modal.querySelector('.btn-confirm');
    
    cancelBtn.addEventListener('mouseenter', function() {
        this.style.background = '#f3f4f6';
    });
    cancelBtn.addEventListener('mouseleave', function() {
        this.style.background = '#f9fafb';
    });
    
    confirmBtn.addEventListener('mouseenter', function() {
        this.style.background = '#b91c1c';
    });
    confirmBtn.addEventListener('mouseleave', function() {
        this.style.background = '#dc2626';
    });
}

function closeDeleteModal() {
    const modal = document.querySelector('.delete-modal');
    
    if (modal) {
        // Animación de salida suave
        modal.style.opacity = '0';
        const content = modal.querySelector('.delete-modal-content');
        if (content) {
            content.style.transform = 'scale(0.95) translateY(-10px)';
        }
        
        setTimeout(() => {
            if (modal && modal.parentNode) {
                modal.remove();
            }
        }, 200);
    }
    
    // Restaurar overflow del body completamente
    document.body.style.overflow = '';
    document.body.removeAttribute('style');
}

function deleteJunta(juntaId) {
    const form = document.getElementById('delete-form');
    form.action = `{{ url('/admin/juntas_asamblea') }}/${juntaId}`;
    form.submit();
}

// Cerrar modal con tecla Escape
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeDeleteModal();
    }
});

// ===== SISTEMA DE FILTROS =====

const FiltersManager = {
    elements: {
        filtersToggle: null,
        filtersContent: null,
        filterIcon: null,
        filterText: null,
        filterForm: null,
        filterSelects: null,
        clearBtn: null,
        applyBtn: null,
        orderToggle: null,
        hiddenSortDirection: null
    },

    init() {
        this.bindElements();
        this.bindEvents();
        this.updateActiveFilters();
        this.showFiltersIfActive();
        this.setupOrderToggle();
    },

    bindElements() {
        this.elements.filtersContent = document.getElementById('filters-content');
        this.elements.filterForm = document.getElementById('filters-form');
        this.elements.filterSelects = document.querySelectorAll('.filter-select');
        this.elements.clearBtn = document.getElementById('clear-filters');
        this.elements.applyBtn = document.getElementById('apply-filters');
        this.elements.orderToggle = document.getElementById('toggle-order');
        this.elements.hiddenSortDirection = document.getElementById('hidden_sort_direction');
    },

    bindEvents() {
        // Limpiar filtros
        if (this.elements.clearBtn) {
            this.elements.clearBtn.addEventListener('click', () => this.clearFilters());
        }

        // Aplicar filtros
        if (this.elements.applyBtn) {
            this.elements.applyBtn.addEventListener('click', () => this.applyFilters());
        }

        // Auto-aplicar filtros cuando cambian los selects
        this.elements.filterSelects.forEach(select => {
            select.addEventListener('change', () => {
                this.updateActiveFilters();
                // Auto-submit cuando cambia un select
                setTimeout(() => {
                    this.applyFilters();
                }, 100);
            });
        });
    },

    clearFilters() {
        // Limpiar todos los selects
        this.elements.filterSelects.forEach(select => {
            select.value = '';
            select.classList.remove('active');
        });
        
        // Resetear orden
        if (this.elements.hiddenSortDirection) {
            this.elements.hiddenSortDirection.value = 'desc';
        }
        
        // Redirigir a la página sin parámetros
        if (this.elements.filterForm) {
            window.location.href = this.elements.filterForm.action;
        }
    },

    applyFilters() {
        if (this.elements.filterForm) {
            this.elements.filterForm.submit();
        }
    },

    updateActiveFilters() {
        this.elements.filterSelects.forEach(select => {
            if (select.value && select.value !== '') {
                select.classList.add('active');
            } else {
                select.classList.remove('active');
            }
        });
    },

    showFiltersIfActive() {
        const urlParams = new URLSearchParams(window.location.search);
        const hasFilters = urlParams.has('filter_mes') ||
                          urlParams.has('filter_anio') ||
                          urlParams.has('sort_field') ||
                          urlParams.has('sort_direction');
        
        if (hasFilters && this.elements.filtersContent) {
            this.elements.filtersContent.classList.add('show');
            const chevron = document.getElementById('filters-chevron');
            if (chevron) {
                chevron.style.transform = 'rotate(180deg)';
            }
        }
    },

    setupOrderToggle() {
        if (this.elements.orderToggle && this.elements.hiddenSortDirection) {
            this.elements.orderToggle.addEventListener('click', () => {
                // Obtener dirección actual
                const currentDirection = this.elements.hiddenSortDirection.value;
                const newDirection = currentDirection === 'desc' ? 'asc' : 'desc';
                
                // Actualizar valor oculto
                this.elements.hiddenSortDirection.value = newDirection;
                
                // Actualizar texto e icono del botón
                if (newDirection === 'desc') {
                    this.elements.orderToggle.innerHTML = '<i class="fas fa-arrow-up"></i> Invertir a Ascendente';
                } else {
                    this.elements.orderToggle.innerHTML = '<i class="fas fa-arrow-down"></i> Invertir a Descendente';
                }
                
                // Enviar formulario automáticamente
                this.applyFilters();
            });
        }
    }
};

// Toggle filtros (función independiente como en unidades)
function toggleFilters() {
    const content = document.getElementById('filters-content');
    const chevron = document.getElementById('filters-chevron');
    
    if (content.classList.contains('show')) {
        content.classList.remove('show');
        chevron.style.transform = 'rotate(0deg)';
    } else {
        content.classList.add('show');
        chevron.style.transform = 'rotate(180deg)';
    }
}

// Inicializar cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', function() {
    FiltersManager.init();
});
</script>

@endsection