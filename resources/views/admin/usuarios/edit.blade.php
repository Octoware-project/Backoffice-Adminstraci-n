@extends('layouts.app')

@section('content')
    @include('componentes.navbar')
    <style>
        .main-content {
            margin-left: 220px;
            max-width: calc(100vw - 220px);
            width: 100%;
            padding: 40px 30px;
            background: #f4f6f8;
            min-height: 100vh;
            font-family: 'Segoe UI', Arial, sans-serif;
        }
        @media (max-width: 768px) {
            .main-content {
                margin-left: 0;
                max-width: 100vw;
            }
        }
        .usuario-title {
            color: #2c3e50;
            font-size: 2rem;
            margin-bottom: 18px;
            font-weight: 600;
        }
        .usuario-form {
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(44,62,80,0.07);
            padding: 24px 28px;
            max-width: 600px;
            margin-bottom: 30px;
        }
        .usuario-form label {
            color: #34495e;
            margin-bottom: 4px;
        }
        .usuario-form input {
            margin-bottom: 14px;
        }
        .btn-primary {
            font-size: 0.95rem;
            padding: 7px 16px;
            border-radius: 3px;
            border: 1px solid #2980b9;
            background: #2980b9;
            color: #fff;
            transition: background 0.2s;
        }
        .btn-primary:hover {
            background: #3498db;
            color: #fff;
        }
        .btn-secondary {
            font-size: 0.95rem;
            padding: 7px 16px;
            border-radius: 3px;
            border: 1px solid #bbb;
            background: #bbb;
            color: #222;
            transition: background 0.2s;
        }
        .btn-secondary:hover {
            background: #888;
            color: #fff;
        }
        .usuario-actions {
            display: flex;
            gap: 10px;
            margin-top: 10px;
        }
    </style>
    <div class="main-content">
        <div class="usuario-title">Editar Usuario</div>
        <form method="POST" action="{{ route('usuarios.update', $usuario->id) }}" class="usuario-form">
            @csrf
            @method('PUT')
            <div class="row mb-2">
                <div class="col">
                    <label for="nombre" class="form-label">Nombre</label>
                    <input type="text" name="nombre" id="nombre" class="form-control" value="{{ $usuario->name }}" required>
                </div>
                <div class="col">
                    <label for="apellido" class="form-label">Apellido</label>
                    <input type="text" name="apellido" id="apellido" class="form-control" value="{{ $usuario->apellido }}" required>
                </div>
            </div>
            <div class="mb-2">
                <label for="email" class="form-label">Email</label>
                <input type="email" name="email" id="email" class="form-control" value="{{ $usuario->user ? $usuario->user->email : '' }}" required>
            </div>
            <div class="mb-2">
                <label for="CI" class="form-label">CI</label>
                <input type="text" name="CI" id="CI" class="form-control" value="{{ $usuario->CI }}">
            </div>
            <div class="mb-2">
                <label for="Telefono" class="form-label">Teléfono</label>
                <input type="text" name="Telefono" id="Telefono" class="form-control" value="{{ $usuario->Telefono }}">
            </div>
            <div class="mb-2">
                <label for="Direccion" class="form-label">Dirección</label>
                <input type="text" name="Direccion" id="Direccion" class="form-control" value="{{ $usuario->Direccion }}">
            </div>
            <div class="mb-2">
                <label for="Estado_Registro" class="form-label">Estado Registro</label>
                <input type="text" name="Estado_Registro" id="Estado_Registro" class="form-control" value="{{ $usuario->Estado_Registro }}" readonly>
            </div>
            <div class="usuario-actions">
                <button type="submit" class="btn btn-primary">Guardar cambios</button>
                <a href="{{ route('usuarios.show', $usuario->id) }}" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>
@endsection
