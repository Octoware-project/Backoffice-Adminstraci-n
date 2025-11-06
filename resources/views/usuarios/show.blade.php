@extends('layouts.app')

@php
    \Carbon\Carbon::setLocale('es');
@endphp

<link rel="stylesheet" href="{{ asset('css/Usuarios/Show.css') }}">
@push('styles')

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
            
            ModalConfirmation.create({
                title: 'Confirmar Aceptación de Usuario',
                message: '¿Está seguro que desea ACEPTAR al usuario:',
                detail: `"${userName}"`,
                warning: 'El usuario pasará al estado "Inactivo" y podrá acceder al sistema.',
                confirmText: 'Aceptar Usuario',
                cancelText: 'Cancelar',
                iconClass: 'fas fa-user-check',
                iconColor: '#059669',
                confirmColor: '#059669',
                onConfirm: () => {
                    handleUserAction('aceptar', userId, this);
                }
            });
        });
    }

    // Manejar click en botón Rechazar
    const btnReject = document.getElementById('btn-reject-user');
    if (btnReject) {
        btnReject.addEventListener('click', function(e) {
            e.preventDefault();
            const userId = this.getAttribute('data-user-id');
            const userName = this.getAttribute('data-user-name');
            
            ModalConfirmation.create({
                title: 'Confirmar Rechazo de Usuario',
                message: '¿Está seguro que desea RECHAZAR al usuario:',
                detail: `"${userName}"`,
                warning: 'El usuario no podrá acceder al sistema y se marcará como rechazado.',
                confirmText: 'Rechazar Usuario',
                cancelText: 'Cancelar',
                iconClass: 'fas fa-user-times',
                iconColor: '#dc2626',
                confirmColor: '#dc2626',
                onConfirm: () => {
                    handleUserAction('rechazar', userId, this);
                }
            });
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
