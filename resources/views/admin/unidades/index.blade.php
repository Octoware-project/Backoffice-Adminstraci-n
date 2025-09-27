@extends('layouts.app')

@section('content')
<style>
    /* Background de la página */
    body {
        background-color: #d2d2f1ff !important;
    }

    /* Variables CSS */
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
    .unidades-workspace {
        max-width: 1400px;
        margin: 0 auto;
        padding: 0 1.5rem;
        min-height: 100vh;
        background-color: #d2d2f1ff;
    }

    /* Header Moderno */
    .unidades-header {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-light) 100%);
        border-radius: var(--radius);
        padding: 2rem 2.5rem;
        margin-bottom: 2rem;
        box-shadow: var(--shadow-lg);
        color: white;
        position: relative;
        overflow: hidden;
    }

    .unidades-header::before {
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
        font-size: 2.5rem;
        font-weight: 700;
        letter-spacing: -0.025em;
    }

    .header-subtitle {
        margin: 0.5rem 0 0 0;
        font-size: 1.125rem;
        opacity: 0.9;
        font-weight: 400;
    }

    .btn-group {
        display: flex;
        gap: 1rem;
    }

    .btn-modern {
        display: inline-flex;
        align-items: center;
        gap: 0.75rem;
        padding: 1.1rem 2.2rem;
        border-radius: var(--radius-sm);
        font-weight: 600;
        font-size: 1.125rem;
        text-decoration: none;
        transition: all 0.2s ease;
        border: none;
        cursor: pointer;
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

    /* Filtros */
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
    }

    .filter-input:focus, .filter-select:focus {
        outline: none;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
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
    }

    .btn-outline-primary:hover {
        background: var(--primary-color);
        color: white;
    }

    .btn-outline-secondary {
        background: transparent;
        color: var(--text-muted);
        border: 2px solid var(--border-color);
    }

    .btn-outline-secondary:hover {
        background: var(--bg-light);
        color: var(--text-primary);
    }

    /* Tabla */
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
        cursor: pointer;
    }

    .modern-table tbody tr:hover {
        background: linear-gradient(90deg, #f8fafc 0%, #f1f5f9 100%);
        transform: translateX(4px);
        box-shadow: inset 4px 0 0 var(--primary-color);
    }

    /* Estilo para badges clickeables */
    .badge-modern:hover {
        transform: scale(1.05);
        box-shadow: var(--shadow-sm);
        transition: all 0.2s ease;
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

    /* Estados exactos de planTrabajos */
    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        padding: 0.25rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 600;
        letter-spacing: 0.025em;
    }

    .status-en-construccion {
        background: #fef3c7;
        color: #d97706;
    }

    .status-finalizado {
        background: #d1fae5;
        color: #065f46;
    }

    /* Botones de acción */
    .action-buttons {
        display: flex;
        gap: 0.5rem;
    }

    .btn-action {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 2.5rem;
        height: 2.5rem;
        border-radius: 50%;
        text-decoration: none;
        font-size: 0.875rem;
        transition: all 0.2s ease;
        border: none;
        cursor: pointer;
    }

    .btn-view {
        background: linear-gradient(135deg, var(--info-color) 0%, #2563eb 100%);
        color: white;
    }

    .btn-view:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(84, 160, 255, 0.4);
        color: white;
    }

    .btn-edit {
        background: linear-gradient(135deg, var(--warning-color) 0%, #ff8f00 100%);
        color: white;
    }

    .btn-edit:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(255, 183, 77, 0.4);
        color: white;
    }

    .btn-delete {
        background: linear-gradient(135deg, var(--danger-color) 0%, #dc2626 100%);
        color: white;
    }

    .btn-delete:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(252, 70, 107, 0.4);
        color: white;
    }

    /* Paginación */
    .pagination-wrapper {
        display: flex;
        justify-content: center;
        margin-top: 2rem;
    }

    /* Empty State exacto de planTrabajos */
    .empty-state {
        text-align: center;
        padding: 3rem 2rem;
        color: var(--text-muted);
    }

    .empty-state-icon {
        font-size: 3rem;
        margin-bottom: 1rem;
        opacity: 0.5;
    }

    .empty-state h4 {
        margin-bottom: 0.5rem;
        color: var(--text-secondary);
    }

    /* Animaciones exactas de planTrabajos */
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .fade-in {
        animation: fadeIn 0.5s ease-out forwards;
    }

    /* Responsivo */
    @media (max-width: 768px) {
        .unidades-workspace {
            padding: 0 1rem;
        }
        
        .header-content {
            flex-direction: column;
            gap: 1rem;
            text-align: center;
        }
        
        .filters-grid {
            grid-template-columns: 1fr;
        }
        
        .filters-actions {
            justify-content: center;
        }
        
        .modern-table {
            font-size: 0.75rem;
        }
        
        .modern-table th,
        .modern-table td {
            padding: 0.75rem 0.5rem;
        }
        
        .action-buttons {
            flex-direction: column;
        }
        
        .btn-action {
            width: 100%;
            height: 2rem;
            border-radius: var(--radius-sm);
        }
    }
</style>

{{-- FontAwesome para iconos --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<div class="unidades-workspace">
    <!-- Header Moderno -->
    <div class="unidades-header">
        <div class="header-content">
            <div>
                <h1 class="header-title">
                    <i class="fas fa-home"></i>
                    Unidades Habitacionales
                </h1>
                <p class="header-subtitle">
                    Gestiona las unidades habitacionales de la cooperativa
                </p>
            </div>
            <div class="btn-group">
                <a href="{{ route('unidades.create') }}" class="btn-modern btn-primary-modern">
                    <i class="fas fa-plus"></i> Nueva Unidad
                </a>
            </div>
        </div>
    </div>

    <!-- Filtros Colapsables -->
    <div class="filters-container">
        <div class="filters-header" onclick="toggleFilters()">
            <div class="filters-toggle">
                <span><i class="fas fa-filter"></i> Filtros de búsqueda</span>
                <i class="fas fa-chevron-down" id="filters-chevron"></i>
            </div>
        </div>
        <div class="filters-content" id="filters-content">
            <form method="GET" action="{{ route('unidades.index') }}">
                <div class="filters-grid">
                    <div class="filter-group">
                        <label class="filter-label">Número de Departamento</label>
                        <input type="text" name="numero_departamento" class="filter-input" 
                               value="{{ request('numero_departamento') }}" placeholder="Buscar por número...">
                    </div>
                    <div class="filter-group">
                        <label class="filter-label">Piso</label>
                        <input type="number" name="piso" class="filter-input" 
                               value="{{ request('piso') }}" placeholder="Buscar por piso...">
                    </div>
                    <div class="filter-group">
                        <label class="filter-label">Estado</label>
                        <select name="estado" class="filter-select">
                            <option value="">Todos los estados</option>
                            <option value="En construccion" {{ request('estado') == 'En construccion' ? 'selected' : '' }}>En construcción</option>
                            <option value="Finalizado" {{ request('estado') == 'Finalizado' ? 'selected' : '' }}>Finalizado</option>
                        </select>
                    </div>
                    <div class="filter-group">
                        <label class="filter-label">Ordenar por</label>
                        <select name="sort" class="filter-select">
                            <option value="numero_departamento" {{ request('sort') == 'numero_departamento' ? 'selected' : '' }}>Número de Departamento</option>
                            <option value="piso" {{ request('sort') == 'piso' ? 'selected' : '' }}>Piso</option>
                            <option value="estado" {{ request('sort') == 'estado' ? 'selected' : '' }}>Estado</option>
                            <option value="created_at" {{ request('sort') == 'created_at' ? 'selected' : '' }}>Fecha de Creación</option>
                        </select>
                    </div>
                </div>
                <div class="filters-actions">
                    <a href="{{ route('unidades.index') }}" class="btn-modern btn-sm btn-outline-secondary">
                        <i class="fas fa-times"></i> Limpiar
                    </a>
                    <button type="submit" class="btn-modern btn-sm btn-outline-primary">
                        <i class="fas fa-search"></i> Aplicar Filtros
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabla de Unidades -->
    @if($unidades->count() > 0)
        <div class="table-container fade-in">
            <table class="modern-table">
                <thead>
                    <tr>
                        <th>Número</th>
                        <th>Piso</th>
                        <th>Estado</th>
                        <th>Personas Asignadas</th>
                        <th>Fecha Creación</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($unidades as $unidad)
                        <tr onclick="window.location='{{ route('unidades.show', $unidad) }}'" style="cursor: pointer;">
                            <td>
                                <div style="display: flex; align-items: center; gap: 0.75rem;">
                                    <div style="width: 2.5rem; height: 2.5rem; border-radius: 50%; background: {{ $unidad->estado == 'Finalizado' ? '#d1fae5' : '#fef3c7' }}; display: flex; align-items: center; justify-content: center;">
                                        <i class="fas fa-{{ $unidad->estado == 'Finalizado' ? 'check' : 'hammer' }}" style="color: {{ $unidad->estado == 'Finalizado' ? '#065f46' : '#d97706' }}; font-size: 0.875rem;"></i>
                                    </div>
                                    <div>
                                        <div style="font-weight: 600;">{{ $unidad->numero_departamento }}</div>
                                        <div style="font-size: 0.75rem; color: var(--text-muted);">
                                            Unidad habitacional
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge-modern badge-info">
                                    <i class="fas fa-building"></i>
                                    Piso {{ $unidad->piso }}
                                </span>
                            </td>
                            <td>
                                <span class="status-badge status-{{ strtolower(str_replace(' ', '-', $unidad->estado)) }}">
                                    <i class="fas fa-{{ $unidad->estado == 'Finalizado' ? 'check-circle' : 'clock' }}"></i>
                                    {{ $unidad->estado }}
                                </span>
                            </td>
                            <td>
                                @if($unidad->personas->count() > 0)
                                    <span class="badge-modern badge-success" 
                                          onclick="event.stopPropagation(); window.location='{{ route('unidades.show', $unidad) }}#personas-asignadas';" 
                                          style="cursor: pointer;" 
                                          title="Ver personas asignadas">
                                        <i class="fas fa-users"></i>
                                        {{ $unidad->personas->count() }} persona(s)
                                    </span>
                                @else
                                    <span style="color: var(--text-muted);">Sin asignar</span>
                                @endif
                            </td>
                            <td>
                                <div style="font-weight: 500;">
                                    {{ $unidad->created_at->format('d/m/Y') }}
                                </div>
                                <div style="font-size: 0.75rem; color: var(--text-muted);">
                                    {{ $unidad->created_at->format('H:i') }}
                                </div>
                            </td>
                            <td onclick="event.stopPropagation();">
                                <div class="action-buttons">
                                    <a href="{{ route('unidades.show', $unidad) }}" 
                                       class="btn-action btn-view" title="Ver detalles">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('unidades.edit', $unidad) }}" 
                                       class="btn-action btn-edit" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form method="POST" action="{{ route('unidades.destroy', $unidad) }}" 
                                          style="display: inline-block;"
                                          onsubmit="return confirm('¿Estás seguro de eliminar esta unidad habitacional?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-action btn-delete" title="Eliminar">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Paginación -->
        <div class="pagination-wrapper">
            {{ $unidades->links() }}
        </div>
    @else
        <div class="empty-state">
            <div class="empty-state-icon">
                <i class="fas fa-home"></i>
            </div>
            <h4>No hay unidades habitacionales</h4>
            <p>Las unidades aparecerán aquí cuando sean creadas.</p>
            <a href="{{ route('unidades.create') }}" class="btn-modern btn-primary-modern">
                <i class="fas fa-plus"></i> Crear Primera Unidad
            </a>
        </div>
    @endif
</div>

<script>
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
        ['numero_departamento', 'piso', 'estado', 'sort'].includes(key) && urlParams.get(key)
    );
    
    if (hasFilters) {
        toggleFilters();
    }
});
</script>
@endsection