<!DOCTYPE html>
<html>
<head>
    <title>Listado de Usuarios</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('TablaStyles.css') }}">
    <style>
        body {
            overflow-x: hidden;
        }
        .main-content {
            margin-left: 220px; /* Ajusta según el ancho de tu navbar lateral */
            max-width: calc(100vw - 220px);
            width: 100%;
        }
        @media (max-width: 768px) {
            .main-content {
                margin-left: 0;
                max-width: 100vw;
            }
        }
        table {
            width: 100%;
            table-layout: auto;
            word-break: break-word;
        }
    </style>
</head>
<body>
    @include('componentes.navbar')
    <div class="main-content container mt-5">
        <h1 class="mb-4">Listado de Usuarios</h1>

        <div class="mb-4">
            <label for="tabla-select" class="form-label fw-bold">Mostrar:</label>
            <select id="tabla-select" class="form-select w-auto d-inline-block ms-2">
                <option value="pendientes">Pendientes</option>
                <option value="aceptados" selected>Aceptados</option>
                <option value="rechazados">Rechazados</option>
                <option value="inactivos">Inactivos</option>
            </select>
        </div>

        <div id="tabla-pendientes" style="display:none;">
            <h2>Usuarios Pendientes</h2>
            @if($pendientes->isEmpty())
                <div class="alert alert-info">No hay usuarios pendientes.</div>
            @else
                <table class="table tabla-pagos">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Apellido</th>
                            <th>Email</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pendientes as $usuario)
                            <tr class="usuario-row" data-usuario-id="{{ $usuario->id }}">
                                <td>{{ $usuario->name }}</td>
                                <td>{{ $usuario->apellido }}</td>
                                <td>{{ $usuario->user ? $usuario->user->email : '' }}</td>
                                <td>
                                    <form action="{{ route('usuarios.aceptar', $usuario->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="btn btn-success btn-sm">Aceptar</button>
                                    </form>
                                    <form action="{{ route('usuarios.rechazar', $usuario->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="btn btn-danger btn-sm">Rechazar</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>

        <div id="tabla-aceptados">
            <h2>Usuarios Aceptados</h2>
            @if($aceptados->isEmpty())
                <div class="alert alert-info">No hay usuarios aceptados.</div>
            @else
                <table class="table tabla-pagos">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Apellido</th>
                            <th>Email</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($aceptados as $usuario)
                            <tr class="usuario-row" data-usuario-id="{{ $usuario->id }}">
                                <td>{{ $usuario->name }}</td>
                                <td>{{ $usuario->apellido }}</td>
                                <td>{{ $usuario->user ? $usuario->user->email : '' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>

        <div id="tabla-inactivos" style="display:none;">
            <h2>Usuarios Inactivos</h2>
            @if($inactivos->isEmpty())
                <div class="alert alert-info">No hay usuarios inactivos.</div>
            @else
                <table class="table tabla-pagos">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Apellido</th>
                            <th>Email</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($inactivos as $usuario)
                            <tr class="usuario-row" data-usuario-id="{{ $usuario->id }}">
                                <td>{{ $usuario->name }}</td>
                                <td>{{ $usuario->apellido }}</td>
                                <td>{{ $usuario->user ? $usuario->user->email : '' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>

        <div id="tabla-rechazados" style="display:none;">
            <h2>Usuarios Rechazados</h2>
            @if($rechazados->isEmpty())
                <div class="alert alert-info">No hay usuarios rechazados.</div>
            @else
                <table class="table tabla-pagos">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Apellido</th>
                            <th>Email</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($rechazados as $usuario)
                            <tr class="usuario-row" data-usuario-id="{{ $usuario->id }}">
                                <td>{{ $usuario->name }}</td>
                                <td>{{ $usuario->apellido }}</td>
                                <td>{{ $usuario->user ? $usuario->user->email : '' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.usuario-row').forEach(function(row) {
                row.addEventListener('dblclick', function() {
                    var usuarioId = this.getAttribute('data-usuario-id');
                    window.location.href = "{{ url('/admin/usuarios') }}/" + usuarioId;
                });
            });

            const select = document.getElementById('tabla-select');
            const tablas = {
                pendientes: document.getElementById('tabla-pendientes'),
                aceptados: document.getElementById('tabla-aceptados'),
                rechazados: document.getElementById('tabla-rechazados'),
                inactivos: document.getElementById('tabla-inactivos')
            };
            function mostrarTabla() {
                Object.values(tablas).forEach(tabla => tabla.style.display = 'none');
                tablas[select.value].style.display = '';
            }
            // Mostrar aceptados por defecto
            select.value = 'aceptados';
            mostrarTabla();
            select.addEventListener('change', mostrarTabla);
        });
    </script>
</body>
</html>
