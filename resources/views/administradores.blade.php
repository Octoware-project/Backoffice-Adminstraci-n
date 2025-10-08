@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/admin-administradores.css') }}">
@endpush

@section('content')
<div class="planes-workspace">
    <div class="admin-header">
        <div class="admin-header-content">
            <h1 class="header-title">
                <i class="fas fa-users-cog icon-mg-1"></i>
                Administradores
            </h1>
            <p class="header-subtitle">Gestión de usuarios administradores del sistema</p>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle icon-mg-05"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle icon-mg-05"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="config-grid">
        <div class="admin-list-card">
            <h3 class="config-title">
                <i class="fas fa-list"></i>
                Lista de Administradores
            </h3>
            <div class="table-container">
                <table class="modern-table">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Email</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($admins as $adm)
                            <tr>
                                <td>{{ $adm->name }}</td>
                                <td>{{ $adm->email }}</td>
                                <td>
                                    <div class="admin-actions">
                                        <a href="{{ route('admin.edit', $adm->id) }}" class="btn-guardar-config btn-sm">
                                            <i class="fas fa-edit"></i> Modificar
                                        </a>
                                        <button class="btn-delete btn-sm" onclick="confirmarEliminacion('{{ $adm->id }}', '{{ $adm->name }}')">
                                            <i class="fas fa-trash"></i> Eliminar
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="admin-form-card">
            <h3 class="config-title">
                <i class="fas fa-user-plus"></i>
                @if(isset($admin)) Editar Administrador @else Nuevo Administrador @endif
            </h3>
            @if(isset($admin))
                <form method="POST" action="{{ route('admin.update', $admin->id) }}" autocomplete="off">
                    @csrf
                    @method('PUT')
                    <label for="name" class="form-label">Nombre</label>
                    <input type="text" name="name" id="name" required value="{{ old('name', $admin->name) }}" class="form-control" autocomplete="off">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" name="email" id="email" required value="{{ old('email', $admin->email) }}" class="form-control" autocomplete="off">
                    <label for="password" class="form-label">Contraseña (dejar vacío para no cambiar)</label>
                    <input type="password" name="password" id="password" class="form-control" autocomplete="new-password">
                    <label for="password_confirmation" class="form-label">Confirmar Contraseña</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" autocomplete="new-password">
                    <div style="display: flex; gap: 0.5rem; align-items: center;">
                        <button type="submit" class="btn-guardar-config">
                            <i class="fas fa-save"></i> Actualizar Administrador
                        </button>
                        <a href="{{ route('admin.list') }}" class="btn-cancel">Cancelar</a>
                    </div>
                </form>
            @else
                <form method="POST" action="{{ route('admin.store') }}" autocomplete="off">
                    @csrf
                    <label for="name" class="form-label">Nombre</label>
                    <input type="text" name="name" id="name" required value="" class="form-control" autocomplete="off">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" name="email" id="email" required value="" class="form-control" autocomplete="off">
                    <label for="password" class="form-label">Contraseña</label>
                    <input type="password" name="password" id="password" required class="form-control" autocomplete="new-password">
                    <label for="password_confirmation" class="form-label">Confirmar Contraseña</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" required class="form-control" autocomplete="new-password">
                    <button type="submit" class="btn-guardar-config" id="btn-guardar">
                        <i class="fas fa-save"></i> Crear Administrador
                    </button>
                </form>
            @endif
        </div>
    </div>
</div>

<!-- Modal de Confirmación Personalizado -->
<div id="confirmation-modal" class="confirmation-modal-overlay">
    <div class="confirmation-modal">
        <div class="confirmation-modal-header">
            <h3 id="modal-title">Confirmar Eliminación</h3>
        </div>
        <div class="confirmation-modal-body">
            <p id="modal-message">¿Estás seguro de que deseas eliminar este administrador?</p>
        </div>
        <div class="confirmation-modal-footer">
            <button type="button" class="btn-cancel" onclick="cerrarModal()">Cancelar</button>
            <button type="button" class="btn-delete" id="confirm-delete-btn">Eliminar</button>
        </div>
    </div>
</div>

@push('scripts')
<script>
    let adminIdAEliminar = null;

    function confirmarEliminacion(adminId, adminName) {
        adminIdAEliminar = adminId;
        
        document.getElementById('modal-title').textContent = 'Confirmar Eliminación';
        document.getElementById('modal-message').textContent = 
            `¿Estás seguro de que deseas eliminar al administrador "${adminName}"? Esta acción no se puede deshacer.`;
        
        document.getElementById('confirmation-modal').style.display = 'flex';
        
        // Configurar el botón de confirmación
        const confirmBtn = document.getElementById('confirm-delete-btn');
        confirmBtn.onclick = function() {
            eliminarAdministrador(adminId);
        };
    }

    function cerrarModal() {
        document.getElementById('confirmation-modal').style.display = 'none';
        adminIdAEliminar = null;
    }

    async function eliminarAdministrador(adminId) {
        try {
            const response = await fetch(`/administradores/${adminId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                }
            });

            const data = await response.json();

            if (data.success) {
                // Mostrar mensaje de éxito y recargar página
                cerrarModal();
                window.location.reload();
            } else {
                // Mostrar alerta personalizada para el error
                mostrarAlertaError(data.message);
            }
        } catch (error) {
            console.error('Error:', error);
            mostrarAlertaError('Ocurrió un error al eliminar el administrador.');
        }
    }

    function mostrarAlertaError(mensaje) {
        // Cerrar modal de confirmación
        cerrarModal();
        
        // Cambiar el modal a modo de alerta de error
        document.getElementById('modal-title').innerHTML = '<i class="fas fa-exclamation-triangle" style="color: #dc3545;"></i> Error';
        document.getElementById('modal-message').textContent = mensaje;
        
        // Ocultar botón de eliminar y cambiar texto del botón cancelar
        document.getElementById('confirm-delete-btn').style.display = 'none';
        document.querySelector('.confirmation-modal-footer .btn-cancel').textContent = 'Cerrar';
        document.querySelector('.confirmation-modal-footer .btn-cancel').onclick = function() {
            // Restaurar estado original del modal
            document.getElementById('confirm-delete-btn').style.display = 'inline-block';
            document.querySelector('.confirmation-modal-footer .btn-cancel').textContent = 'Cancelar';
            document.querySelector('.confirmation-modal-footer .btn-cancel').onclick = cerrarModal;
            cerrarModal();
        };
        
        document.getElementById('confirmation-modal').style.display = 'flex';
    }

    // Cerrar modal al hacer clic fuera de él
    document.getElementById('confirmation-modal').addEventListener('click', function(e) {
        if (e.target === this) {
            cerrarModal();
        }
    });
</script>
@endpush

@endsection
