@extends('layouts.app')
<link rel="stylesheet" href="{{ asset('css/Usuarios/Pendientes.css') }}">
@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
@endpush

<script src="{{ asset('js/Usuarios/Pendientes.js') }}"></script>

@section('content')
    <div class="usuarios-workspace">
        <div class="usuarios-content">
            {{-- Header Principal --}}
            <div class="usuarios-header header-pattern">
                <div class="header-content">
                    <div>
                        <h1 class="header-title">Usuarios Pendientes</h1>
                        <p class="header-subtitle">Gestión de solicitudes de registro pendientes de aprobación</p>
                    </div>
                    <div class="btn-group">
                        <a href="{{ route('usuarios.index') }}" class="btn-modern btn-success" style="background-color: #10b981; color: white;">
                            <i class="fas fa-users"></i>
                            Ver Residentes
                        </a>
                        <a href="{{ route('usuarios.eliminados') }}" class="btn-modern btn-secondary-modern">
                            <i class="fas fa-trash-restore"></i>
                            Ver Eliminados
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

            {{-- Sección de Filtros --}}
            <div class="filters-section">
                <div class="filters-header" onclick="toggleFilters()">
                    <div class="filters-title">
                        <span><i class="fas fa-filter"></i> Filtros de búsqueda</span>
                        <i class="fas fa-chevron-down" id="filter-icon"></i>
                    </div>
                </div>
                
                <div class="filters-content" id="filters-content">
                    <form method="GET" action="{{ route('usuarios.pendientes') }}" id="filters-form">
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
                                    <option value="created_at" {{ request('sort_field', 'created_at') == 'created_at' ? 'selected' : '' }}>
                                        Fecha de Registro
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
                            <a href="{{ route('usuarios.pendientes') }}" class="filter-btn filter-btn-secondary">
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
                            @if(request('sort_field') && request('sort_field') != 'created_at')
                                <span class="filter-tag">
                                    Ordenar por: 
                                    @switch(request('sort_field'))
                                        @case('name') Nombre @break
                                        @case('email') Email @break
                                        @default Fecha de Registro @endswitch
                                </span>
                            @endif
                            @if(request('sort_direction') && request('sort_direction') != 'desc')
                                <span class="filter-tag">Orden: Ascendente</span>
                            @endif
                        </div>
                    </div>
                    <a href="{{ route('usuarios.pendientes') }}" class="clear-filters-link">
                        <i class="fas fa-times"></i> Limpiar todos
                    </a>
                </div>
            </div>
            @endif

            {{-- Estadísticas de Usuarios Pendientes --}}
            <div class="stats-section" style="margin-bottom: 1.5rem;">
                <div class="stat-card">
                    <div class="stat-icon" style="background-color: #f59e0b;">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-number">{{ $usuarios->count() }}</div>
                        <div class="stat-label">Usuario{{ $usuarios->count() != 1 ? 's' : '' }} Pendiente{{ $usuarios->count() != 1 ? 's' : '' }}</div>
                    </div>
                </div>
            </div>

            {{-- Tabla de Usuarios Pendientes --}}
            <div class="table-container">
                @if($usuarios->count() > 0)
                    <table class="modern-table">
                        <thead>
                            <tr>
                                <th>Usuario</th>
                                <th>CI</th>
                                <th class="hide-mobile">Fecha Registro</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($usuarios as $usuario)
                            <tr class="usuario-row usuario-pendiente" data-usuario-id="{{ $usuario->id }}">
                                
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

                                {{-- Fecha de Registro --}}
                                <td class="hide-mobile">
                                    <div class="date-badge">
                                        {{ $usuario->created_at ? $usuario->created_at->format('d/m/Y H:i') : 'N/A' }}
                                    </div>
                                </td>

                                {{-- Estado --}}
                                <td>
                                    <span class="status-badge status-pendiente">
                                        Pendiente
                                        <i class="fas fa-clock" style="margin-left: 0.25rem;"></i>
                                    </span>
                                </td>

                                {{-- Acciones --}}
                                <td onclick="event.stopPropagation();">
                                    <div class="actions-group">
                                        <button type="button" class="action-btn btn-accept" 
                                                data-user-id="{{ $usuario->id }}" 
                                                data-user-name="{{ $usuario->name }} {{ $usuario->apellido }}">
                                            <i class="fas fa-check"></i>
                                            Aceptar
                                        </button>
                                        <button type="button" class="action-btn btn-reject" 
                                                data-user-id="{{ $usuario->id }}" 
                                                data-user-name="{{ $usuario->name }} {{ $usuario->apellido }}">
                                            <i class="fas fa-times"></i>
                                            Rechazar
                                        </button>
                                        <a href="{{ route('usuarios.show', $usuario->id) }}" class="action-btn btn-view">
                                            <i class="fas fa-eye"></i>
                                            Ver
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="empty-state">
                        <div class="empty-state-icon">
                            <i class="fas fa-user-check" style="color: #10b981;"></i>
                        </div>
                        <h3>¡Excelente!</h3>
                        <p>No hay usuarios pendientes de aprobación en este momento.</p>
                        <a href="{{ route('usuarios.index') }}" class="btn-modern btn-primary-modern" style="margin-top: 1rem;">
                            <i class="fas fa-users"></i>
                            Ver Residentes
                        </a>
                    </div>
                @endif
            </div>

        </div> {{-- Cierre de usuarios-content --}}
    </div> {{-- Cierre de usuarios-workspace --}}
@endsection
