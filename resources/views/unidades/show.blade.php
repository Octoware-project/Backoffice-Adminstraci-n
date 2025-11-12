@extends('layouts.app')
<link rel="stylesheet" href="{{ asset('css/UnidadesHabitacionales/Show.css') }}">
@section('content')

{{-- FontAwesome para iconos --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<div class="detail-workspace">
    <!-- Header del Detalle -->
    <div class="detail-header">
        <div class="header-content">
            <div>
                <h1 class="header-title">
                    <i class="fas fa-home"></i>
                    Unidad {{ $unidad->numero_departamento }}
                </h1>
                <p class="header-subtitle">
                    Piso {{ $unidad->piso }} - Estado: {{ ucfirst($unidad->estado) }}
                </p>
            </div>
            <div class="header-actions">
                <button type="button" class="btn-modern btn-success" id="btn-asignar-personas">
                    <i class="fas fa-user-plus"></i> Asignar Personas
                </button>
                <a href="{{ route('unidades.edit', $unidad) }}" class="btn-modern btn-warning">
                    <i class="fas fa-edit"></i> Editar
                </a>
                <a href="{{ route('unidades.index') }}" class="back-btn">
                    <i class="fas fa-arrow-left"></i> Volver
                </a>
            </div>
        </div>
    </div>

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

    @if(session('info'))
        <div class="alert alert-info">
            <div class="alert-icon">
                <i class="fas fa-info-circle"></i>
            </div>
            <div>
                <strong>Información:</strong>
                {{ session('info') }}
            </div>
        </div>
    @endif

    <!-- Grid de Información Principal -->
    <div class="info-grid">
        <div class="info-card">
            <div class="card-icon" style="background: linear-gradient(135deg, var(--primary-color), var(--primary-light))">
                <i class="fas fa-home"></i>
            </div>
            <div class="card-title">Número de Departamento</div>
            <div class="card-value">{{ $unidad->numero_departamento }}</div>
            <div class="card-subtitle">Identificador único</div>
        </div>

        <div class="info-card">
            <div class="card-icon" style="background: linear-gradient(135deg, var(--info-color), #2563eb)">
                <i class="fas fa-building"></i>
            </div>
            <div class="card-title">Piso</div>
            <div class="card-value">{{ $unidad->piso }}</div>
            <div class="card-subtitle">Nivel del edificio</div>
        </div>

        <div class="info-card">
            <div class="card-icon" style="background: linear-gradient(135deg, var(--success-color), #059669)">
                <i class="fas fa-wrench"></i>
            </div>
            <div class="card-title">Estado Construcción</div>
            <div class="card-value">{{ $unidad->estado }}</div>
            <div class="card-subtitle">Estado de la obra</div>
        </div>

        <div class="info-card">
            <div class="card-icon" style="background: linear-gradient(135deg, var(--warning-color), #d97706)">
                <i class="fas fa-users"></i>
            </div>
            <div class="card-title">Personas Asignadas</div>
            <div class="card-value">{{ $unidad->personas->count() }}</div>
            <div class="card-subtitle">Total de residentes</div>
        </div>
    </div>

    <!-- Personas Asignadas -->
    <div class="records-container fade-in" id="personas-asignadas">
        <div class="records-header">
            <h3 class="records-title">
                <i class="fas fa-users"></i>
                Personas Asignadas
            </h3>
        </div>
        
        @if($unidad->personas && $unidad->personas->count() > 0)
            <table class="modern-table">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Email</th>
                        <th>Teléfono</th>
                        <th>Fecha de Asignación</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($unidad->personas as $persona)
                        <tr onclick="window.location='{{ route('usuarios.show', $persona->id) }}'" style="cursor: pointer;">
                            <td>
                                <div style="display: flex; align-items: center; gap: 0.75rem;">
                                    <div style="width: 2.5rem; height: 2.5rem; border-radius: 50%; background: #dbeafe; display: flex; align-items: center; justify-content: center;">
                                        <i class="fas fa-user" style="color: #1e40af; font-size: 0.875rem;"></i>
                                    </div>
                                    <div>
                                        <div style="font-weight: 600;">{{ $persona->name }} {{ $persona->apellido }}</div>
                                        <div style="font-size: 0.75rem; color: var(--text-muted);">
                                            CI: {{ $persona->CI }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                @if($persona->user && $persona->user->email)
                                    <span class="badge-modern badge-info">
                                        <i class="fas fa-envelope"></i>
                                        {{ $persona->user->email }}
                                    </span>
                                @else
                                    <span style="color: var(--text-muted);">Sin email</span>
                                @endif
                            </td>
                            <td>
                                @if($persona->telefono)
                                    <span class="badge-modern badge-success">
                                        <i class="fas fa-phone"></i>
                                        {{ $persona->telefono }}
                                    </span>
                                @else
                                    <span style="color: var(--text-muted);">Sin teléfono</span>
                                @endif
                            </td>
                            <td>
                                @if($persona->fecha_asignacion_unidad)
                                    <div style="font-weight: 500;">
                                        {{ \Carbon\Carbon::parse($persona->fecha_asignacion_unidad)->format('d/m/Y') }}
                                    </div>
                                    <div style="font-size: 0.75rem; color: var(--text-muted);">
                                        {{ \Carbon\Carbon::parse($persona->fecha_asignacion_unidad)->format('H:i') }}
                                    </div>
                                @else
                                    <span style="color: var(--text-muted);">N/A</span>
                                @endif
                            </td>
                            <td onclick="event.stopPropagation();">
                                <form id="desasignar-form-{{ $persona->id }}" method="POST" action="{{ route('unidades.desasignar-persona', [$unidad->id, $persona->id]) }}" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-sm btn-outline-danger" 
                                            onclick="confirmDesasignarPersona({{ $persona->id }}, '{{ $persona->name }} {{ $persona->apellido }}', '{{ $unidad->numero }}')">
                                        <i class="fas fa-user-times"></i> Desasignar
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="empty-state">
                <div class="empty-state-icon">
                    <i class="fas fa-users"></i>
                </div>
                <h4>No hay personas asignadas</h4>
                <p>Los residentes aparecerán aquí cuando sean asignados a esta unidad.</p>
            </div>
        @endif
    </div>
</div>

<!-- Modal para asignar personas -->
<div id="modal-asignar-personas" class="modal-overlay" style="display: none;">
    <div class="modal-content">
        <div class="modal-header">
            <h3><i class="fas fa-user-plus"></i> Asignar Personas a la Unidad</h3>
            <button type="button" class="modal-close" onclick="cerrarModal()">&times;</button>
        </div>
        <div class="modal-body">
            <div id="personas-disponibles-container">
                <div class="loading-spinner">
                    <i class="fas fa-spinner fa-spin"></i> Cargando personas disponibles...
                </div>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('js/UnidadesHabitacionales/Show.js') }}"></script>
@endsection