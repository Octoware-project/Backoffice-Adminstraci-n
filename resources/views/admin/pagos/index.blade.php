<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Pagos Mensuales</title>
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
    <div class="main-content">
        <h1>Pagos Mensuales</h1>

        <form method="GET" action="{{ route('pagos.index') }}" class="mb-3 d-flex align-items-end" id="filtro-fecha-form">
            <div class="me-2">
                <label for="fecha_pago" class="form-label mb-0">Filtrar por fecha:</label>
                <input type="date" name="fecha_pago" id="fecha_pago" class="form-control" value="{{ request('fecha_pago') }}">
            </div>
            <div class="me-2">
                <label for="mes_correspondiente" class="form-label mb-0">Filtrar por mes:</label>
                <select name="mes_correspondiente" id="mes_correspondiente" class="form-control">
                    <option value="">Todos</option>
                    @foreach([
                        'Enero','Febrero','Marzo','Abril','Mayo','Junio',
                        'Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'
                    ] as $mes)
                        <option value="{{ $mes }}" {{ request('mes_correspondiente') == $mes ? 'selected' : '' }}>{{ $mes }}</option>
                    @endforeach
                </select>
            </div>
            <div class="me-2">
                <label for="año_correspondiente" class="form-label mb-0">Filtrar por año:</label>
                <input type="number" name="año_correspondiente" id="año_correspondiente" class="form-control"
                    value="{{ request('año_correspondiente') }}" min="2000" max="2100" placeholder="Año">
            </div>
            <!-- El botón se puede ocultar si solo quieres el filtro automático -->
            <button type="submit" class="btn btn-primary d-none">Filtrar</button>
        </form>
        <script>
            document.getElementById('fecha_pago').addEventListener('change', function() {
                document.getElementById('filtro-fecha-form').submit();
            });
            document.getElementById('mes_correspondiente').addEventListener('change', function() {
                document.getElementById('filtro-fecha-form').submit();
            });
            document.getElementById('año_correspondiente').addEventListener('change', function() {
                document.getElementById('filtro-fecha-form').submit();
            });

            document.addEventListener('DOMContentLoaded', function() {
                document.querySelectorAll('.pago-row').forEach(function(row) {
                    row.addEventListener('dblclick', function() {
                        var personaId = this.getAttribute('data-persona-id');
                        window.location.href = "{{ url('/pagos/persona') }}/" + personaId;
                    });
                });
            });
        </script>

        <table class="table tabla-pagos">
            <thead>
                <tr>
                    <th>Residente</th>
                    <th>Monto</th>
                    <th>Mes</th>
                    <th>Fecha</th>
                    <th>Comprobante</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
            @foreach($pagos as $pago)
                <tr class="pago-row" data-persona-id="{{ $pago->persona->id }}">
                    <td>{{ $pago->persona->nombre }} {{ $pago->persona->apellido }}</td>
                    <td>${{ $pago->monto }}</td>
                    <td>{{ $pago->mes_correspondiente }}</td>
                    <td>{{ $pago->fecha_pago }}</td>
                    <td>
                        @if($pago->comprobante)
                            <a href="{{ asset('storage/'.$pago->comprobante) }}" target="_blank">Ver</a>
                        @else
                            Sin comprobante
                        @endif
                    </td>
                    <td>
                        @if($pago->estado == 'pendiente')
                            <form action="{{ route('pagos.aprobar', $pago->id) }}" method="POST" style="display:inline;">
                                @csrf
                                <button type="submit" class="btn btn-success">Aprobar</button>
                            </form>
                            <form action="{{ route('pagos.rechazar', $pago->id) }}" method="POST" style="display:inline;">
                                @csrf
                                <button type="submit" class="btn btn-danger">Rechazar</button>
                            </form>
                        @else
                            <!-- No mostrar estado -->
                        @endif
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>
</body>
</html>
