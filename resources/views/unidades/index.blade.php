@extends('layouts.app')
<link rel="stylesheet" href="{{ asset('css/UnidadesHabitacionales/Index.css') }}">

@section('content')

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

    {{-- Mensajes de sesión --}}
    @if(session('success'))
        <div class="alert alert-success">
            <div class="alert-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <div>
                <strong>¡Éxito!</strong>
                {{ session('success') }}
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-error">
            <div class="alert-icon">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <div>
                <strong>Error:</strong>
                {{ session('error') }}
            </div>
        </div>
    @endif

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
                            <option value="En construcción" {{ request('estado') == 'En construcción' ? 'selected' : '' }}>En construcción</option>
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
                                       class="btn-view" title="Ver detalles">
                                        <i class="fas fa-eye"></i> Ver
                                    </a>
                                    <a href="{{ route('unidades.edit', $unidad) }}" 
                                       class="btn-edit" title="Editar">
                                        <i class="fas fa-edit"></i> Editar
                                    </a>
                                    <form id="delete-form-unidad-{{ $unidad->id }}" method="POST" action="{{ route('unidades.destroy', $unidad) }}" 
                                          style="display: inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn-delete" title="Eliminar"
                                                onclick="confirmDeleteUnidad({{ $unidad->id }}, '{{ $unidad->numero_departamento }}', '{{ $unidad->piso }}')">
                                            <i class="fas fa-trash"></i> Eliminar
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
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

<script src="{{ asset('js/UnidadesHabitacionales/Index.js') }}"></script>
@endsection