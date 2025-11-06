@extends('layouts.app')
<link rel="stylesheet" href="{{ asset('css/Usuarios/Eliminados.css') }}">

@push('styles')   
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
@endpush

<script sr></script>

@section('content')
    <div class="usuarios-workspace usuarios-eliminados">
        <div class="usuarios-content">
            {{-- Header Principal --}}
            <div class="usuarios-header header-pattern">
                <div class="header-content">
                    <div>
                        <h1 class="header-title">Usuarios Eliminados</h1>
                        <p class="header-subtitle">Listado de usuarios eliminados del sistema con opción de restaurar</p>
                    </div>
                    <div class="btn-group">
                        <a href="{{ route('usuarios.index') }}" class="btn-modern btn-secondary-modern">
                            <i class="fas fa-users"></i>
                            Ver Activos
                        </a>
                        <a href="{{ route('usuarios.pendientes') }}" class="btn-modern btn-primary-modern">
                            <i class="fas fa-clock"></i>
                            Ver Pendientes
                        </a>
                    </div>
                </div>
            </div>

            {{-- Mostrar mensaje de éxito --}}
            @if(session('success'))
                <div class="success-message">
                    <div class="success-content">
                        <i class="fas fa-check-circle"></i>
                        <p>{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            {{-- Mostrar mensaje de error --}}
            @if(session('error'))
                <div class="error-message" style="background: #fee2e2; border: 1px solid #fca5a5; border-radius: var(--radius-sm); padding: 1rem; margin-bottom: 2rem; box-shadow: var(--shadow-sm);">
                    <div class="error-content" style="display: flex; align-items: center; gap: 0.75rem;">
                        <i class="fas fa-exclamation-triangle" style="color: #dc2626; font-size: 1.25rem;"></i>
                        <p style="margin: 0; color: #991b1b; font-weight: 600; font-size: 1rem;">{{ session('error') }}</p>
                    </div>
                </div>
            @endif

            {{-- Sección de Filtros Moderna --}}
            <div class="filters-section">
                <div class="filters-header" onclick="toggleFilters()">
                    <div class="filters-title">
                        <span><i class="fas fa-filter"></i> Filtros de búsqueda</span>
                        <i class="fas fa-chevron-down" id="filter-icon"></i>
                    </div>
                </div>
                
                <div class="filters-content" id="filters-content">
                    <form method="GET" action="{{ route('usuarios.eliminados') }}" id="filters-form">
                        <div class="filters-grid">
                            {{-- Filtro por Nombre --}}
                            <div class="filter-group">
                                <label class="filter-label">
                                    <i class="fas fa-signature"></i>
                                    Buscar por Nombre
                                </label>
                                <input 
                                    type="text" 
                                    name="filter_nombre" 
                                    id="filter_nombre"
                                    class="filter-input" 
                                    placeholder="Ingresa nombre o apellido..."
                                    value="{{ request('filter_nombre') }}"
                                >
                            </div>

                            {{-- Filtro por Email --}}
                            <div class="filter-group">
                                <label class="filter-label">
                                    <i class="fas fa-at"></i>
                                    Buscar por Email
                                </label>
                                <input 
                                    type="email" 
                                    name="filter_email" 
                                    id="filter_email"
                                    class="filter-input" 
                                    placeholder="Ingresa email..."
                                    value="{{ request('filter_email') }}"
                                >
                            </div>

                            {{-- Campo de Ordenamiento --}}
                            <div class="filter-group">
                                <label class="filter-label">
                                    <i class="fas fa-sort"></i>
                                    Ordenar por
                                </label>
                                <select name="sort_field" id="sort_field" class="filter-select">
                                    <option value="deleted_at" {{ request('sort_field', 'deleted_at') == 'deleted_at' ? 'selected' : '' }}>
                                        Fecha de Eliminación
                                    </option>
                                    <option value="name" {{ request('sort_field') == 'name' ? 'selected' : '' }}>
                                        Orden Alfabético (Nombre)
                                    </option>
                                    <option value="email" {{ request('sort_field') == 'email' ? 'selected' : '' }}>
                                        Email
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
                            <a href="{{ route('usuarios.eliminados') }}" class="filter-btn filter-btn-secondary">
                                <i class="fas fa-times"></i> Limpiar
                            </a>
                            <button type="submit" class="filter-btn filter-btn-primary">
                                <i class="fas fa-search"></i> Aplicar Filtros
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Indicador de Filtros Activos --}}
            @if(request()->filled('filter_nombre') || request()->filled('filter_email') || request()->filled('sort_field') || request()->filled('sort_direction'))
            <div class="active-filters-indicator">
                <div class="active-filters-content">
                    <div class="active-filters-info">
                        <i class="fas fa-info-circle" style="color: #0369a1;"></i>
                        <span style="color: #0369a1; font-weight: 600; font-size: 0.875rem;">Filtros activos:</span>
                        <div class="active-filters-tags">
                            @if(request('filter_nombre'))
                                <span class="filter-tag">Nombre: {{ request('filter_nombre') }}</span>
                            @endif
                            @if(request('filter_email'))
                                <span class="filter-tag">Email: {{ request('filter_email') }}</span>
                            @endif
                            @if(request('sort_field') && request('sort_field') != 'deleted_at')
                                <span class="filter-tag">
                                    Ordenar por: 
                                    @switch(request('sort_field'))
                                        @case('name') Nombre @break
                                        @case('email') Email @break
                                        @default Fecha de Eliminación @endswitch
                                </span>
                            @endif
                            @if(request('sort_direction') && request('sort_direction') != 'desc')
                                <span class="filter-tag">Orden: Ascendente</span>
                            @endif
                        </div>
                    </div>
                    <a href="{{ route('usuarios.eliminados') }}" class="clear-filters-link">
                        <i class="fas fa-times"></i> Limpiar todos
                    </a>
                </div>
            </div>
            @endif

            {{-- Tabla de Usuarios Eliminados --}}
            <div class="table-container">
                @if($usuarios->count() > 0)
                    <table class="modern-table">
                        <thead>
                            <tr>
                                <th>Usuario</th>
                                <th>CI</th>
                                <th class="hide-mobile">Fecha Eliminación</th>
                                <th>Estado Original</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($usuarios as $usuario)
                            @php
                                $estadoClass = match($usuario->estadoRegistro) {
                                    'Pendiente' => 'usuario-pendiente',
                                    'Aceptado' => 'usuario-aceptado',
                                    'Rechazado' => 'usuario-rechazado',
                                    'Inactivo' => 'usuario-inactivo',
                                    default => ''
                                };
                                $statusClass = match($usuario->estadoRegistro) {
                                    'Pendiente' => 'status-pendiente',
                                    'Aceptado' => 'status-aceptado',
                                    'Rechazado' => 'status-rechazado',
                                    'Inactivo' => 'status-inactivo',
                                    default => 'status-inactivo'
                                };
                            @endphp
                            <tr class="usuario-row {{ $estadoClass }}" data-usuario-id="{{ $usuario->id }}" style="opacity: 0.7; cursor: default;">
                                
                                {{-- Usuario --}}
                                <td>
                                    <div class="user-info">
                                        <div class="user-avatar">
                                            {{ strtoupper(substr($usuario->name ?? 'U', 0, 1) . substr($usuario->apellido ?? '', 0, 1)) }}
                                        </div>
                                        <div class="user-details">
                                            <div class="user-name">
                                                {{ $usuario->name }} {{ $usuario->apellido }}
                                            </div>
                                            <div class="user-email">
                                                {{ $usuario->user ? $usuario->user->email : 'Sin email' }}
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                {{-- CI --}}
                                <td>
                                    <strong>{{ $usuario->CI ?? 'N/A' }}</strong>
                                </td>

                                {{-- Fecha de Eliminación --}}
                                <td class="hide-mobile">
                                    <div class="date-badge" style="background-color: #fef2f2; color: #dc2626; border-color: #fecaca;">
                                        {{ $usuario->deleted_at ? $usuario->deleted_at->format('d/m/Y H:i') : 'N/A' }}
                                    </div>
                                </td>

                                {{-- Estado Original --}}
                                <td>
                                    <span class="status-badge {{ $statusClass }}">
                                        {{ $usuario->estadoRegistro }}
                                        @if($usuario->estadoRegistro == 'Aceptado')
                                            <i class="fas fa-check-circle" style="margin-left: 0.25rem;"></i>
                                        @elseif($usuario->estadoRegistro == 'Pendiente')
                                            <i class="fas fa-clock" style="margin-left: 0.25rem;"></i>
                                        @elseif($usuario->estadoRegistro == 'Rechazado')
                                            <i class="fas fa-times-circle" style="margin-left: 0.25rem;"></i>
                                        @endif
                                    </span>
                                </td>

                                {{-- Acciones --}}
                                <td onclick="event.stopPropagation();">
                                    <div class="actions-group">
                                        <form id="restore-form-{{ $usuario->id }}" action="{{ route('usuarios.restaurar', $usuario->id) }}" method="POST" style="display:inline-block;">
                                            @csrf
                                            @method('PUT')
                                            <button type="button" class="action-btn" 
                                                    onclick="confirmRestoreUsuario({{ $usuario->id }}, '{{ $usuario->name }} {{ $usuario->apellido }}')"
                                                    style="background: #d1fae5; color: #065f46; border: 1px solid #6ee7b7;">
                                                <i class="fas fa-undo"></i>
                                                Restaurar
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
                            <i class="fas fa-trash-alt" style="opacity: 0.3;"></i>
                        </div>
                        <h3>No hay usuarios eliminados</h3>
                        <p>No se encontraron usuarios eliminados que coincidan con los filtros aplicados.</p>
                        <a href="{{ route('usuarios.index') }}" class="btn-modern btn-primary-modern" style="margin-top: 1rem;">
                            <i class="fas fa-users"></i>
                            Ver Usuarios Activos
                        </a>
                    </div>
                @endif
            </div>

        </div> {{-- Cierre de usuarios-content --}}
    </div> {{-- Cierre de usuarios-workspace --}}
    
@endsection