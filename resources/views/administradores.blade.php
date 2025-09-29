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
                                        <form method="POST" action="{{ route('admin.destroy', $adm->id) }}" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-delete btn-sm" onclick="return confirm('¿Seguro que deseas eliminar este administrador?')">
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
@endsection
