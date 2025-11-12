@extends('layouts.app')
<link rel="stylesheet" href="{{ asset('css/Asamblea/Asamblea.css') }}">
@section('content')


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
    // Pasar datos de Laravel a JavaScript
    window.asambleaConfig = {
        deleteUrl: '{{ url('/juntas_asamblea') }}'
    };
</script>
<script src="{{ asset('js/Asamblea/Asamblea.js') }}"></script>

@endsection