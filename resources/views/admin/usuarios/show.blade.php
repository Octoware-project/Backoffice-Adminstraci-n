@extends('layouts.app')

@php
    \Carbon\Carbon::setLocale('es');
@endphp

@push('styles')
    {{-- CSS específicos para usuarios - misma estructura que index --}}
    <style>
        {!! file_get_contents(resource_path('views/admin/usuarios/css/variables.css')) !!}
        {!! file_get_contents(resource_path('views/admin/usuarios/css/index.css')) !!}
    </style>
    
    {{-- Estilos específicos para la página de detalles --}}
    <style>
        /* Container Principal - igual que index */
        .usuarios-workspace {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1.5rem;
            min-height: 100vh;
        }

        /* Sección de datos del usuario */
        .usuario-details-section {
            background: var(--bg-primary);
            border-radius: var(--radius);
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: var(--shadow-md);
            border: 1px solid rgba(160, 174, 192, 0.2);
        }

        .section-title {
            color: var(--text-primary);
            font-size: 1.5rem;
            font-weight: 600;
            margin: 0 0 1.5rem 0;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .section-title i {
            color: var(--primary-color);
        }

        /* Tabla de datos */
        .usuario-table {
            width: 100%;
            border-collapse: collapse;
            background: var(--bg-primary);
            border-radius: var(--radius-sm);
            overflow: hidden;
            box-shadow: var(--shadow-sm);
            margin-bottom: 2rem;
        }

        .usuario-table th,
        .usuario-table td {
            padding: 1rem 1.5rem;
            text-align: left;
            border-bottom: 1px solid var(--bg-light);
        }

        .usuario-table th {
            background: var(--bg-secondary);
            color: var(--text-secondary);
            font-weight: 600;
            width: 200px;
            font-size: 0.9rem;
        }

        .usuario-table th i {
            color: var(--primary-color);
            margin-right: 0.5rem;
            width: 16px;
        }

        .usuario-table td {
            color: var(--text-primary);
            font-weight: 500;
        }

        .usuario-table tr:last-child th,
        .usuario-table tr:last-child td {
            border-bottom: none;
        }

        /* Headers de secciones */
        .usuario-table th[colspan="2"] {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
            color: white;
            text-align: center;
            font-size: 1.1rem;
            font-weight: 600;
            padding: 1.2rem;
        }

        /* Texto auxiliar */
        .text-muted {
            color: var(--text-muted) !important;
            font-size: 0.85rem;
            font-weight: 400;
        }

        /* Botones de acción específicos */
        .btn-modern.btn-success {
            background: linear-gradient(135deg, #10b981, #059669);
            color: white;
            border: none;
        }

        .btn-modern.btn-success:hover {
            background: linear-gradient(135deg, #059669, #047857);
            transform: translateY(-2px);
        }

        .btn-modern.btn-danger {
            background: linear-gradient(135deg, #ef4444, #dc2626);
            color: white;
            border: none;
        }

        .btn-modern.btn-danger:hover {
            background: linear-gradient(135deg, #dc2626, #b91c1c);
            transform: translateY(-2px);
        }

        /* Loading state para botones */
        .btn-loading {
            opacity: 0.6;
            cursor: not-allowed;
            pointer-events: none;
        }

        .btn-loading i {
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        /* Badges */
        .badge {
            display: inline-block;
            padding: 0.35rem 0.65rem;
            font-size: 0.75rem;
            font-weight: 600;
            line-height: 1;
            text-align: center;
            white-space: nowrap;
            vertical-align: baseline;
            border-radius: var(--radius-sm);
            margin-left: 0.5rem;
        }

        .badge-success {
            background-color: #10b981;
            color: white;
        }

        .badge-warning {
            background-color: #f59e0b;
            color: white;
        }

        .badge-danger {
            background-color: #ef4444;
            color: white;
        }

        .badge-secondary {
            background-color: #6b7280;
            color: white;
        }

        /* Estilos específicos para facturas */
        .facturas-section-header {
            background: linear-gradient(135deg, #10b981, #059669) !important;
        }

        /* Badge con mejor espaciado */
        .badge {
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
            white-space: nowrap;
        }

        /* Contenedor de información de facturas */
        .facturas-info-container {
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 100%;
            gap: 1rem;
        }

        .facturas-status-group {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            flex: 1;
        }

        /* Responsive para móviles */
        @media (max-width: 768px) {
            .facturas-info-container {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.75rem;
            }
            
            .facturas-status-group {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.5rem;
            }
        }

        /* Códigos */
        code {
            background: var(--bg-light);
            padding: 0.25rem 0.5rem;
            border-radius: 3px;
            font-family: 'Monaco', 'Menlo', 'Ubuntu Mono', monospace;
            font-size: 0.85rem;
            color: var(--text-secondary);
        }

        /* Botones de acciones */
        .usuario-actions {
            display: flex;
            gap: 1rem;
            margin-top: 1.5rem;
        }

        .btn-primary {
            background: var(--primary-color);
            color: white;
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: var(--radius-sm);
            text-decoration: none;
            font-weight: 500;
            transition: var(--transition);
            box-shadow: var(--shadow-sm);
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-primary:hover {
            background: var(--primary-light);
            transform: translateY(-1px);
            box-shadow: var(--shadow-md);
            color: white;
            text-decoration: none;
        }

        .btn-secondary {
            background: var(--bg-secondary);
            color: var(--text-primary);
            padding: 0.75rem 1.5rem;
            border: 1px solid var(--border-color);
            border-radius: var(--radius-sm);
            text-decoration: none;
            font-weight: 500;
            transition: var(--transition);
            box-shadow: var(--shadow-sm);
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-secondary:hover {
            background: white;
            transform: translateY(-1px);
            box-shadow: var(--shadow-md);
            color: var(--text-primary);
            text-decoration: none;
        }

        /* Alert success */
        .alert-success {
            background: #d1fae5;
            border: 1px solid #6ee7b7;
            color: #065f46;
            border-radius: var(--radius-sm);
            padding: 1rem 1.5rem;
            margin-bottom: 2rem;
            box-shadow: var(--shadow-sm);
        }

        /* Badges para unidades habitacionales */
        .badge {
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
            padding: 0.375rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.8rem;
            font-weight: 600;
            letter-spacing: 0.025em;
        }

        .badge-success {
            background: #d1fae5;
            color: #065f46;
        }

        .badge-info {
            background: #dbeafe;
            color: #1e40af;
        }

        .badge-warning {
            background: #fef3c7;
            color: #d97706;
        }

        .badge-danger {
            background: #fee2e2;
            color: #dc2626;
        }

        .badge-secondary {
            background: #f3f4f6;
            color: #6b7280;
        }

        /* Botón pequeño para ver detalles */
        .btn-sm {
            padding: 0.375rem 0.75rem;
            font-size: 0.875rem;
            border-radius: var(--radius-sm);
            text-decoration: none;
            font-weight: 500;
            transition: var(--transition);
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
        }

        /* Animaciones */
        .usuario-content {
            opacity: 0;
            animation: fadeInContent 0.4s ease 0.1s forwards;
        }
        
        @keyframes fadeInContent {
            from {
                opacity: 0;
                transform: translateY(15px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
    
    {{-- FontAwesome para iconos --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
@endpush

@section('content')
    <div class="usuarios-workspace">
        <div class="usuarios-content">
            {{-- Header Principal - igual que en index --}}
            <div class="usuarios-header header-pattern">
                <div class="header-content">
                    <div>
                        <h1 class="header-title">Datos del Usuario</h1>
                        <p class="header-subtitle">Información completa del residente registrado</p>
                    </div>
                    <div class="btn-group">
                        @if($usuario->estadoRegistro === 'Pendiente')
                            <button type="button" class="btn-modern btn-success" id="btn-accept-user" 
                                    data-user-id="{{ $usuario->id }}" 
                                    data-user-name="{{ $usuario->name }} {{ $usuario->apellido }}">
                                <i class="fas fa-check"></i>
                                Aceptar Usuario
                            </button>
                            <button type="button" class="btn-modern btn-danger" id="btn-reject-user" 
                                    data-user-id="{{ $usuario->id }}" 
                                    data-user-name="{{ $usuario->name }} {{ $usuario->apellido }}">
                                <i class="fas fa-times"></i>
                                Rechazar Usuario
                            </button>
                        @endif
                        <a href="{{ route('usuarios.edit', $usuario->id) }}" class="btn-modern btn-primary-modern">
                            <i class="fas fa-edit"></i>
                            Editar Usuario
                        </a>
                        <a href="{{ route('usuarios.index') }}" class="btn-modern btn-secondary-modern">
                            <i class="fas fa-arrow-left"></i>
                            Volver
                        </a>
                    </div>
                </div>
            </div>

            {{-- Mostrar mensaje de éxito si existe contraseña --}}
            @if(isset($password) && $password)
                <div class="success-message">
                    <div class="success-content">
                        <i class="fas fa-key"></i>
                        <p><strong>Contraseña generada:</strong> {{ $password }}</p>
                    </div>
                </div>
            @endif

            {{-- Sección de datos del usuario --}}
            <div class="usuario-details-section">
                <table class="usuario-table">
                    {{-- Información Personal Básica --}}
                    <tr><th colspan="2" style="background: linear-gradient(135deg, #667eea, #764ba2); color: white; text-align: center; font-size: 1.1rem;"><i class="fas fa-user"></i> Información Personal</th></tr>
                    <tr><th><i class="fas fa-signature"></i> Nombre Completo</th><td>{{ $usuario->name }} {{ $usuario->apellido }}</td></tr>
                    <tr><th><i class="fas fa-id-card"></i> Cédula de Identidad</th><td>{{ $usuario->CI ?? 'No registrada' }}</td></tr>
                    <tr><th><i class="fas fa-birthday-cake"></i> Fecha de Nacimiento</th><td>
                        @if($usuario->fechaNacimiento)
                            {{ \Carbon\Carbon::parse($usuario->fechaNacimiento)->format('d/m/Y') }}
                            <small class="text-muted">({{ \Carbon\Carbon::parse($usuario->fechaNacimiento)->age }} años)</small>
                        @else
                            No registrada
                        @endif
                    </td></tr>
                    <tr><th><i class="fas fa-venus-mars"></i> Género</th><td>{{ $usuario->genero ?? 'No especificado' }}</td></tr>
                    <tr><th><i class="fas fa-heart"></i> Estado Civil</th><td>{{ $usuario->estadoCivil ?? 'No especificado' }}</td></tr>
                    <tr><th><i class="fas fa-flag"></i> Nacionalidad</th><td>{{ $usuario->nacionalidad ?? 'No especificada' }}</td></tr>
                    <tr><th><i class="fas fa-briefcase"></i> Ocupación</th><td>{{ $usuario->ocupacion ?? 'No especificada' }}</td></tr>
                    
                    {{-- Información de Contacto --}}
                    <tr><th colspan="2" style="background: linear-gradient(135deg, #4ecdc4, #44a08d); color: white; text-align: center; font-size: 1.1rem;"><i class="fas fa-address-book"></i> Información de Contacto</th></tr>
                    <tr><th><i class="fas fa-envelope"></i> Email</th><td>
                        {{ $usuario->user ? $usuario->user->email : 'Sin email registrado' }}
                    </td></tr>
                    <tr><th><i class="fas fa-phone"></i> Teléfono</th><td>{{ $usuario->telefono ?? 'No registrado' }}</td></tr>
                    <tr><th><i class="fas fa-home"></i> Dirección</th><td>{{ $usuario->direccion ?? 'No registrada' }}</td></tr>
                    

                    
                    {{-- Información de Unidad Habitacional --}}
                    <tr><th colspan="2" style="background: linear-gradient(135deg, #667eea, #764ba2); color: white; text-align: center; font-size: 1.1rem;"><i class="fas fa-home"></i> Unidad Habitacional</th></tr>
                    @if($usuario->unidadHabitacional)
                        <tr><th><i class="fas fa-building"></i> Unidad Asignada</th><td>
                            <div style="display: flex; align-items: center; justify-content: space-between; width: 100%;">
                                <span class="badge badge-success">
                                    <i class="fas fa-home"></i>
                                    Departamento {{ $usuario->unidadHabitacional->numero_departamento }}
                                </span>
                                <a href="{{ route('unidades.show', $usuario->unidadHabitacional->id) }}" 
                                   class="btn btn-sm btn-primary" 
                                   style="padding: 0.375rem 0.75rem; border-radius: 0.375rem; text-decoration: none; font-size: 0.875rem;">
                                    <i class="fas fa-eye"></i> Ver Detalles
                                </a>
                            </div>
                        </td></tr>
                        <tr><th><i class="fas fa-layer-group"></i> Piso</th><td>
                            <span class="badge badge-info">
                                <i class="fas fa-sort-numeric-up"></i>
                                Piso {{ $usuario->unidadHabitacional->piso }}
                            </span>
                        </td></tr>
                        <tr><th><i class="fas fa-info-circle"></i> Estado de la Unidad</th><td>
                            @php
                                $estadoClass = match($usuario->unidadHabitacional->estado) {
                                    'Finalizado' => 'badge-success',
                                    'En Construcción' => 'badge-warning', 
                                    'En Pausa' => 'badge-danger',
                                    default => 'badge-secondary'
                                };
                            @endphp
                            <span class="badge {{ $estadoClass }}">
                                <i class="fas fa-{{ $usuario->unidadHabitacional->estado == 'Finalizado' ? 'check-circle' : 'clock' }}"></i>
                                {{ $usuario->unidadHabitacional->estado }}
                            </span>
                        </td></tr>
                        @if($usuario->fecha_asignacion_unidad)
                        <tr><th><i class="fas fa-calendar-check"></i> Fecha de Asignación</th><td>
                            {{ \Carbon\Carbon::parse($usuario->fecha_asignacion_unidad)->format('d/m/Y H:i') }}
                            <small class="text-muted">({{ \Carbon\Carbon::parse($usuario->fecha_asignacion_unidad)->locale('es')->diffForHumans() }})</small>
                        </td></tr>
                        @endif
                    @else
                        <tr><th><i class="fas fa-home"></i> Unidad Asignada</th><td>
                            <div style="display: flex; align-items: center; justify-content: space-between; width: 100%;">
                                <span class="badge badge-secondary">
                                    <i class="fas fa-times-circle"></i>
                                    Sin unidad asignada
                                </span>
                                <a href="{{ route('unidades.index') }}" 
                                   class="btn btn-sm btn-primary" 
                                   style="padding: 0.375rem 0.75rem; border-radius: 0.375rem; text-decoration: none; font-size: 0.875rem;">
                                    <i class="fas fa-plus"></i> Asignar Unidad
                                </a>
                            </div>
                        </td></tr>
                    @endif

                    {{-- Información de Facturas --}}
                    <tr><th colspan="2" class="facturas-section-header"><i class="fas fa-credit-card"></i> Estado de Facturas</th></tr>
                    @if($usuario->user && $usuario->user->email)
                        @if($totalFacturas > 0 && $estadoFacturas)
                            <tr><th><i class="fas fa-chart-line"></i> Estado de Pagos</th><td>
                                <div class="facturas-info-container">
                                    <div class="facturas-status-group">
                                        <span class="badge badge-{{ $estadoFacturas['color'] }}">
                                            <i class="{{ $estadoFacturas['icono'] }}"></i>
                                            {{ $estadoFacturas['estado'] }}
                                        </span>
                                        <small class="text-muted">{{ $estadoFacturas['detalle'] }}</small>
                                    </div>
                                    <a href="{{ route('admin.facturas.usuario', $usuario->user->email) }}" 
                                       class="btn btn-sm btn-primary">
                                        <i class="fas fa-eye"></i> Ver Facturas
                                    </a>
                                </div>
                            </td></tr>
                            <tr><th><i class="fas fa-receipt"></i> Total de Facturas</th><td>
                                <span class="badge badge-info">
                                    <i class="fas fa-hashtag"></i>
                                    {{ $totalFacturas }} {{ $totalFacturas == 1 ? 'factura' : 'facturas' }}
                                </span>
                            </td></tr>
                        @else
                            <tr><th><i class="fas fa-credit-card"></i> Estado de Facturas</th><td>
                                <div class="facturas-info-container">
                                    <span class="badge badge-secondary">
                                        <i class="fas fa-info-circle"></i>
                                        Sin facturas registradas
                                    </span>
                                    <a href="{{ route('admin.facturas.usuario', $usuario->user->email) }}" 
                                       class="btn btn-sm btn-primary">
                                        <i class="fas fa-plus"></i> Gestionar Facturas
                                    </a>
                                </div>
                            </td></tr>
                        @endif
                    @else
                        <tr><th><i class="fas fa-credit-card"></i> Estado de Facturas</th><td>
                            <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                                <span class="badge badge-warning">
                                    <i class="fas fa-exclamation-triangle"></i>
                                    Usuario sin email registrado
                                </span>
                                <small class="text-muted">No se pueden gestionar facturas sin email asociado</small>
                            </div>
                        </td></tr>
                    @endif

                    {{-- Información del Sistema --}}
                    <tr><th colspan="2" style="background: linear-gradient(135deg, #a8edea, #fed6e3); color: #2c3e50; text-align: center; font-size: 1.1rem;"><i class="fas fa-cogs"></i> Información del Sistema</th></tr>
                    <tr><th><i class="fas fa-user-check"></i> Estado de Registro</th><td>
                        @php
                            $badgeClass = match($usuario->estadoRegistro) {
                                'Pendiente' => 'badge-warning',
                                'Aceptado' => 'badge-success',
                                'Rechazado' => 'badge-danger',
                                'Inactivo' => 'badge-secondary',
                                default => 'badge-secondary'
                            };
                        @endphp
                        <span class="badge {{ $badgeClass }}">
                            {{ $usuario->estadoRegistro }}
                        </span>
                    </td></tr>
                    <tr><th><i class="fas fa-calendar-plus"></i> Fecha de Registro</th><td>
                        @if($usuario->created_at)
                            {{ $usuario->created_at->format('d/m/Y H:i') }}
                            <small class="text-muted">({{ $usuario->created_at->locale('es')->diffForHumans() }})</small>
                        @else
                            No disponible
                        @endif
                    </td></tr>
                    <tr><th><i class="fas fa-clock"></i> Última Actualización</th><td>
                        @if($usuario->updated_at && $usuario->updated_at != $usuario->created_at)
                            {{ $usuario->updated_at->format('d/m/Y H:i') }}
                            <small class="text-muted">({{ $usuario->updated_at->locale('es')->diffForHumans() }})</small>
                        @else
                            No actualizado
                        @endif
                    </td></tr>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Manejar click en botón Aceptar
    const btnAccept = document.getElementById('btn-accept-user');
    if (btnAccept) {
        btnAccept.addEventListener('click', function(e) {
            e.preventDefault();
            const userId = this.getAttribute('data-user-id');
            const userName = this.getAttribute('data-user-name');
            
            if (confirm(`¿Estás seguro de que quieres ACEPTAR a ${userName}? El usuario pasará al estado "Inactivo".`)) {
                handleUserAction('aceptar', userId, this);
            }
        });
    }

    // Manejar click en botón Rechazar
    const btnReject = document.getElementById('btn-reject-user');
    if (btnReject) {
        btnReject.addEventListener('click', function(e) {
            e.preventDefault();
            const userId = this.getAttribute('data-user-id');
            const userName = this.getAttribute('data-user-name');
            
            if (confirm(`¿Estás seguro de que quieres RECHAZAR a ${userName}?`)) {
                handleUserAction('rechazar', userId, this);
            }
        });
    }

    function handleUserAction(action, userId, button) {
        // Mostrar estado de carga
        showLoading(button, action === 'aceptar' ? 'Aceptando...' : 'Rechazando...');
        
        // Crear formulario para enviar la acción
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/usuarios/${userId}/${action}`;
        form.style.display = 'none';
        
        // Agregar token CSRF
        const csrfToken = document.querySelector('meta[name="csrf-token"]');
        if (csrfToken) {
            const tokenInput = document.createElement('input');
            tokenInput.type = 'hidden';
            tokenInput.name = '_token';
            tokenInput.value = csrfToken.content;
            form.appendChild(tokenInput);
        }
        
        // Agregar método PUT
        const methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        methodInput.value = 'PUT';
        form.appendChild(methodInput);
        
        document.body.appendChild(form);
        form.submit();
    }

    function showLoading(button, text) {
        const originalContent = button.innerHTML;
        button.classList.add('btn-loading');
        button.innerHTML = `<i class="fas fa-spinner"></i> ${text}`;
        button.disabled = true;
    }
});
</script>
@endpush
