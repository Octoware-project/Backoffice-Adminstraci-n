@extends('layouts.app')

@section('content')
<style>
    /* Variables CSS del sistema planTrabajo */
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

    /* Background moderno */
    body {
        background-color: #d2d2f1ff !important;
        overflow-x: hidden;
        scrollbar-width: none; /* Firefox */
        -ms-overflow-style: none; /* Internet Explorer and Edge */
    }

    /* Hide scrollbar completely */
    body::-webkit-scrollbar {
        display: none; /* Chrome, Safari, and Opera */
    }

    html {
        scrollbar-width: none; /* Firefox */
        -ms-overflow-style: none; /* Internet Explorer and Edge */
    }

    html::-webkit-scrollbar {
        display: none; /* Chrome, Safari, and Opera */
    }

    /* Container principal moderno */
    .facturas-workspace {
        max-width: 1400px;
        margin: 0 auto;
        padding: 0 1.5rem;
        min-height: 100vh;
        background-color: #d2d2f1ff;
    }

    /* Header moderno estilo planTrabajo */
    .facturas-header {
        background: linear-gradient(135deg, #6b46c1 0%, #9333ea 100%);
        border-radius: var(--radius);
        padding: 2rem 2.5rem;
        margin-bottom: 2rem;
        box-shadow: var(--shadow-lg);
        color: white;
        position: relative;
        overflow: hidden;
    }

    .facturas-header::before {
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

    /* Container de tabla moderno */
    .table-container {
        background: var(--bg-primary);
        border-radius: var(--radius);
        box-shadow: var(--shadow-md);
        overflow: hidden;
        margin-bottom: 2rem;
    }

    /* Tabla moderna estilo planTrabajo */
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

    /* Estados modernos */
    .status-badge {
        display: inline-flex;
        align-items: center;
        padding: 0.25rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 600;
        letter-spacing: 0.025em;
        min-width: 90px;
        justify-content: center;
    }

    .status-badge.aceptado {
        background: #d1fae5;
        color: #065f46;
    }

    .status-badge.rechazado {
        background: #fee2e2;
        color: #dc2626;
    }

    /* Botones modernos estilo planTrabajo */
    .btn-modern {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        border-radius: var(--radius-sm);
        font-weight: 600;
        font-size: 0.75rem;
        text-decoration: none;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        border: none;
        cursor: pointer;
        position: relative;
        overflow: hidden;
        text-transform: uppercase;
        letter-spacing: 0.025em;
    }

    .btn-warning-modern {
        background: #fffbeb;
        color: #d97706;
        border: 1px solid #fcd34d;
    }

    .btn-warning-modern:hover {
        background: #fef3c7;
        color: #b45309;
        transform: translateY(-1px);
        text-decoration: none;
    }

    .btn-secondary-modern {
        background: var(--bg-primary);
        color: var(--text-primary);
        border: 2px solid var(--border-color);
    }

    .btn-secondary-modern:hover {
        background: var(--bg-light);
        border-color: var(--primary-color);
        transform: translateY(-1px);
        box-shadow: var(--shadow-md);
        color: var(--text-primary);
        text-decoration: none;
    }

    /* Filtros desplegables */
    .filters-container {
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
    }

    .filter-input, .filter-select {
        padding: 0.75rem;
        border: 2px solid var(--border-color);
        border-radius: var(--radius-sm);
        font-size: 0.875rem;
        transition: border-color 0.2s;
        background: var(--bg-secondary);
        color: var(--text-primary);
    }

    .filter-input:focus, .filter-select:focus {
        outline: none;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        background: var(--bg-primary);
    }

    .filters-actions {
        display: flex;
        gap: 1rem;
        justify-content: flex-end;
    }

    .btn-sm {
        padding: 0.5rem 1rem;
        font-size: 0.875rem;
    }

    .btn-outline-primary {
        background: transparent;
        color: var(--primary-color);
        border: 2px solid var(--primary-color);
        border-radius: var(--radius-sm);
        text-decoration: none;
        font-weight: 600;
        transition: all 0.2s ease;
    }

    .btn-outline-primary:hover {
        background: var(--primary-color);
        color: white;
        text-decoration: none;
    }

    .btn-outline-secondary {
        background: transparent;
        color: var(--text-muted);
        border: 2px solid var(--border-color);
        border-radius: var(--radius-sm);
        text-decoration: none;
        font-weight: 600;
        transition: all 0.2s ease;
    }

    .btn-outline-secondary:hover {
        background: var(--bg-light);
        color: var(--text-primary);
        text-decoration: none;
    }

    .btn-group {
        display: flex;
        gap: 1rem;
    }

    .btn-primary-modern {
        background: rgba(255, 255, 255, 0.15);
        color: white;
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        display: inline-flex;
        align-items: center;
        gap: 0.75rem;
        padding: 1rem 2rem;
        border-radius: var(--radius-sm);
        font-weight: 600;
        font-size: 1rem;
        text-decoration: none;
        transition: all 0.2s ease;
    }

    .btn-primary-modern:hover {
        background: rgba(255, 255, 255, 0.25);
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        color: white;
        text-decoration: none;
    }

    /* Botón Ver estilo usuarios */
    .action-btn {
        padding: 0.5rem 1rem;
        border-radius: var(--radius-sm);
        font-size: 0.75rem;
        font-weight: 600;
        border: none;
        cursor: pointer;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
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

    .actions-group {
        display: flex;
        gap: 0.5rem;
        align-items: center;
        justify-content: center;
        flex-wrap: wrap;
    }

    .btn-warning-modern {
        background: #fffbeb;
        color: #d97706;
        border: 1px solid #fcd34d;
        padding: 0.5rem 1rem;
        border-radius: var(--radius-sm);
        font-weight: 600;
        font-size: 0.75rem;
        text-decoration: none;
        transition: all 0.2s ease;
        text-transform: uppercase;
        letter-spacing: 0.025em;
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
    }

    .btn-warning-modern:hover {
        background: #fef3c7;
        color: #b45309;
        transform: translateY(-1px);
        text-decoration: none;
    }

    /* Back button container */
    .back-button-container {
        margin-top: 2rem;
        text-align: left;
    }

    /* Estados vacíos */
    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        color: var(--text-secondary);
    }

    .empty-state-icon {
        font-size: 4rem;
        margin-bottom: 1rem;
        opacity: 0.6;
    }

    .empty-state-text {
        font-size: 1.5rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
        color: var(--text-primary);
    }

    .empty-state-subtext {
        font-size: 1rem;
        opacity: 0.7;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .facturas-workspace {
            padding: 0 1rem;
        }
        
        .facturas-header {
            padding: 1.5rem 2rem;
        }
        
        .header-title {
            font-size: 1.875rem;
        }
        
        .modern-table th,
        .modern-table td {
            padding: 0.75rem 1rem;
        }
        
        .btn-modern {
            padding: 0.375rem 0.75rem;
            font-size: 0.7rem;
        }

        .header-content {
            flex-direction: column;
            gap: 1rem;
            text-align: center;
        }
    }

    @media (max-width: 1024px) {
        .header-content {
            flex-direction: column;
            gap: 1.5rem;
            text-align: center;
        }
    }
</style>

<div class="facturas-workspace">
    <!-- Header moderno -->
    <div class="facturas-header">
        <div class="header-content">
            <div>
                <h1 class="header-title">Facturas Archivadas</h1>
                <p class="header-subtitle">Facturas aceptadas y rechazadas</p>
            </div>
            <div class="btn-group">
                <a href="{{ route('admin.facturas.index') }}" class="btn-modern btn-primary-modern">
                    <i class="fas fa-clock"></i> Ver Pendientes
                </a>
            </div>
        </div>
    </div>

    <!-- Filtros Desplegables -->
    <div class="filters-container">
        <div class="filters-header" onclick="toggleFilters()">
            <div class="filters-toggle">
                <span><i class="fas fa-filter"></i> Filtros de búsqueda</span>
                <i class="fas fa-chevron-down" id="filters-chevron"></i>
            </div>
        </div>
        <div class="filters-content" id="filters-content">
            <form method="GET" action="{{ route('admin.facturas.archivadas') }}">
                <div class="filters-grid">
                    <div class="filter-group">
                        <label class="filter-label">Año</label>
                        <input type="number" name="año" class="filter-input" 
                               value="{{ request('año') }}" placeholder="Año" min="2020" max="{{ date('Y') + 1 }}">
                    </div>
                    <div class="filter-group">
                        <label class="filter-label">Mes</label>
                        <select name="mes" class="filter-select">
                            <option value="">Todos los meses</option>
                            <option value="1" {{ request('mes') == '1' ? 'selected' : '' }}>Enero</option>
                            <option value="2" {{ request('mes') == '2' ? 'selected' : '' }}>Febrero</option>
                            <option value="3" {{ request('mes') == '3' ? 'selected' : '' }}>Marzo</option>
                            <option value="4" {{ request('mes') == '4' ? 'selected' : '' }}>Abril</option>
                            <option value="5" {{ request('mes') == '5' ? 'selected' : '' }}>Mayo</option>
                            <option value="6" {{ request('mes') == '6' ? 'selected' : '' }}>Junio</option>
                            <option value="7" {{ request('mes') == '7' ? 'selected' : '' }}>Julio</option>
                            <option value="8" {{ request('mes') == '8' ? 'selected' : '' }}>Agosto</option>
                            <option value="9" {{ request('mes') == '9' ? 'selected' : '' }}>Septiembre</option>
                            <option value="10" {{ request('mes') == '10' ? 'selected' : '' }}>Octubre</option>
                            <option value="11" {{ request('mes') == '11' ? 'selected' : '' }}>Noviembre</option>
                            <option value="12" {{ request('mes') == '12' ? 'selected' : '' }}>Diciembre</option>
                        </select>
                    </div>
                    <div class="filter-group">
                        <label class="filter-label">Estado</label>
                        <select name="estado" class="filter-select">
                            <option value="">Todos los estados</option>
                            <option value="Aceptado" {{ request('estado') == 'Aceptado' ? 'selected' : '' }}>Aceptado</option>
                            <option value="Rechazado" {{ request('estado') == 'Rechazado' ? 'selected' : '' }}>Rechazado</option>
                        </select>
                    </div>
                </div>
                <div class="filters-actions">
                    <a href="{{ route('admin.facturas.archivadas') }}" class="btn-modern btn-sm btn-outline-secondary">
                        <i class="fas fa-times"></i> Limpiar
                    </a>
                    <button type="submit" class="btn-modern btn-sm btn-outline-primary">
                        <i class="fas fa-search"></i> Aplicar Filtros
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabla moderna de facturas archivadas -->
    <div class="table-container">
        @php \Carbon\Carbon::setLocale('es'); @endphp
        <table class="modern-table">
            <thead>
                <tr>
                    <th>Residente</th>
                    <th>Monto</th>
                    <th>Estado de Pago</th>
                    <th>Mes</th>
                    <th>Comprobante</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($facturas as $factura)
                    <tr class="factura-row" data-email="{{ $factura->email }}">
                        <td>
                            @php
                                $persona = optional(optional($factura->user)->persona);
                                $nombreCompleto = trim(($persona->name ?? '') . ' ' . ($persona->apellido ?? ''));
                            @endphp
                            {{ $nombreCompleto !== '' ? $nombreCompleto : 'Sin residente' }}
                        </td>
                        <td>{{ $factura->Monto }}</td>
                        <td>
                            @php
                                $estado = strtolower($factura->Estado_Pago);
                            @endphp
                            <span class="status-badge {{ $estado }}">
                                {{ ucfirst($factura->Estado_Pago) }}
                            </span>
                        </td>
                        <td>
                            {{ $factura->fecha_pago ? \Carbon\Carbon::parse($factura->fecha_pago)->translatedFormat('F Y') : '-' }}
                        </td>
                        <!-- Columna Comprobante -->
                        <td style="text-align:center;">
                            @if($factura->Archivo_Comprobante)
                                <a href="#" onclick="abrirComprobanteApi({{ $factura->id }}); return false;" title="Ver comprobante">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" viewBox="0 0 16 16" style="color:#007bff;vertical-align:middle;">
                                        <path d="M4.5 9.5A.5.5 0 0 1 5 9h6a.5.5 0 0 1 0 1H5a.5.5 0 0 1-.5-.5zm0-2A.5.5 0 0 1 5 7h6a.5.5 0 0 1 0 1H5a.5.5 0 0 1-.5-.5zm0-2A.5.5 0 0 1 5 5h6a.5.5 0 0 1 0 1H5a.5.5 0 0 1-.5-.5z"/>
                                        <path d="M14 4.5V14a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2h5.5L14 4.5zm-3-.5a.5.5 0 0 1-.5-.5V1H4a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V5h-2a1 1 0 0 1-1-1V4z"/>
                                    </svg>
                                </a>
                            @else
                                <span style="color:#bbb;">-</span>
                            @endif
                        </td>
                        <!-- Columna Acciones -->
                        <td onclick="event.stopPropagation();" style="text-align:center;">
                            <div class="actions-group">
                                <a href="{{ route('admin.facturas.usuario', $factura->email) }}" class="action-btn btn-view">
                                    <i class="fas fa-eye"></i>
                                    Ver
                                </a>
                                <form action="{{ route('admin.facturas.cancelar', $factura->id) }}" method="POST" style="display:inline-block;">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn-warning-modern">Restablecer</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="empty-state">
                            <div class="empty-state-icon">📁</div>
                            <div class="empty-state-text">No hay facturas archivadas</div>
                            <div class="empty-state-subtext">
                                @if(request()->hasAny(['año', 'mes', 'estado']))
                                    No se encontraron facturas con los filtros aplicados.
                                @else
                                    Las facturas aceptadas y rechazadas aparecerán aquí.
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>

<script>
    // URL base correcta de la API Cooperativa
    const API_COOPERATIVA_BASE = 'http://localhost:8001/api';
    const API_COOPERATIVA_WEB = 'http://localhost:8001';

    function abrirComprobanteApi(facturaId) {
        // Usar la ruta web directa para mejor compatibilidad
        const url = `${API_COOPERATIVA_WEB}/comprobantes/${facturaId}`;
        window.open(url, '_blank');
    }
    
    document.querySelectorAll('.factura-row').forEach(function(row) {
        row.addEventListener('click', function(event) {
            // Verificar si el clic fue en un botón, enlace o formulario
            if (event.target.closest('button, a, form')) {
                return; // No redirigir si se hizo clic en un elemento interactivo
            }
            
            const email = row.getAttribute('data-email');
            if(email) {
                let base = "{{ url('/facturas/usuario') }}";
                if (base.endsWith('/')) base = base.slice(0, -1);
                window.location.href = base + '/' + encodeURIComponent(email);
            }
        });
    });

    // Toggle filtros
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

    // Mostrar filtros si hay parámetros de búsqueda
    document.addEventListener('DOMContentLoaded', function() {
        const urlParams = new URLSearchParams(window.location.search);
        const hasFilters = Array.from(urlParams.keys()).some(key => 
            ['año', 'mes', 'estado'].includes(key) && urlParams.get(key)
        );
        
        if (hasFilters) {
            toggleFilters();
        }
    });
</script>

{{-- FontAwesome para iconos --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

@endsection