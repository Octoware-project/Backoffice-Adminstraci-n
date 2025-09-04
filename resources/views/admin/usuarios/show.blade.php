<!DOCTYPE html>
<html>
<head>
    <title>Datos del Usuario</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('TablaStyles.css') }}">
    <style>
        body {
            overflow-x: hidden;
        }
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
        .usuario-table {
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(44,62,80,0.07);
            padding: 24px 28px;
            margin-bottom: 30px;
            width: 100%;
            max-width: 600px;
        }
        .usuario-table th, .usuario-table td {
            padding: 8px 12px;
            text-align: left;
        }
        .usuario-table th {
            background: #f4f6f8;
            color: #34495e;
            width: 180px;
        }
        .alert-success {
            background: #eafaf1;
            border: 1px solid #27ae60;
            color: #27ae60;
            border-radius: 6px;
            padding: 12px 18px;
            margin-bottom: 18px;
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
</head>
<body>
    @extends('layouts.app')

    @section('content')
        @include('componentes.navbar')
        <div class="main-content">
            <div class="usuario-title">Datos del Usuario</div>
            @if(isset($password) && $password)
                <div class="alert-success">
                    <strong>Contraseña generada:</strong> {{ $password }}
                </div>
            @endif
            <table class="usuario-table">
                <tr><th>Nombre</th><td>{{ $usuario->name }}</td></tr>
                <tr><th>Apellido</th><td>{{ $usuario->apellido }}</td></tr>
                <tr><th>Email</th><td>{{ $usuario->user ? $usuario->user->email : '' }}</td></tr>
                <tr><th>CI</th><td>{{ $usuario->CI }}</td></tr>
                <tr><th>Teléfono</th><td>{{ $usuario->Telefono }}</td></tr>
                <tr><th>Dirección</th><td>{{ $usuario->Direccion }}</td></tr>
                <tr><th>Estado Registro</th><td>{{ $usuario->Estado_Registro }}</td></tr>
            </table>
            <div class="usuario-actions">
                <a href="{{ route('usuarios.edit', $usuario->id) }}" class="btn btn-primary">Editar usuario</a>
                <a href="{{ route('usuarios.index') }}" class="btn btn-secondary">Volver</a>
            </div>
        </div>
    @endsection
</body>
</html>
