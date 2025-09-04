<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Pagos del usuario</title>
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
        .pagos-title {
            color: #2c3e50;
            font-size: 2rem;
            margin-bottom: 18px;
            font-weight: 600;
        }
        .tabla-pagos {
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(44,62,80,0.07);
            margin-bottom: 30px;
            width: 100%;
            max-width: 900px;
        }
        .tabla-pagos th, .tabla-pagos td {
            padding: 8px 12px;
            text-align: left;
        }
        .tabla-pagos th {
            background: #f4f6f8;
            color: #34495e;
        }
        .btn {
            font-size: 0.95rem;
            padding: 4px 12px;
            border-radius: 3px;
            border: 1px solid #2980b9;
            background: transparent;
            color: #2980b9;
            transition: background 0.2s, color 0.2s;
        }
        .btn-success {
            border-color: #27ae60;
            color: #27ae60;
        }
        .btn-success:hover {
            background: #27ae60;
            color: #fff;
        }
        .btn-danger {
            border-color: #c0392b;
            color: #c0392b;
        }
        .btn-danger:hover {
            background: #c0392b;
            color: #fff;
        }
        .btn-secondary {
            border-color: #bbb;
            color: #222;
        }
        .btn-secondary:hover {
            background: #bbb;
            color: #fff;
        }
        .form-label {
            color: #34495e;
        }
    </style>
</head>
<body>
    @extends('layouts.app')

    @section('content')
        @include('componentes.navbar')
        <div class="main-content">
            <div class="pagos-title">
                Pagos de {{ $pagos->first()->persona->nombre ?? '' }} {{ $pagos->first()->persona->apellido ?? '' }}
            </div>
            <form method="GET" action="" class="mb-3 d-flex align-items-end" id="filtro-ano-form">
                <div class="me-2">
                    <label for="año_correspondiente" class="form-label mb-0">Filtrar por año:</label>
                    <input type="number" name="año_correspondiente" id="año_correspondiente" class="form-control"
                        value="{{ request('año_correspondiente') }}" min="2000" max="2100" placeholder="Año">
                </div>
                <div class="me-2">
                    <label for="mes_correspondiente" class="form-label mb-0">Filtrar por mes:</label>
                    <select name="mes_correspondiente" id="mes_correspondiente" class="form-control">
                        <option value="">Todos</option>
                        @foreach(['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'] as $mes)
                            <option value="{{ $mes }}" {{ request('mes_correspondiente') == $mes ? 'selected' : '' }}>{{ $mes }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn btn-primary d-none">Filtrar</button>
            </form>
            <script>
                document.getElementById('año_correspondiente').addEventListener('change', function() {
                    document.getElementById('filtro-ano-form').submit();
                });
                document.getElementById('mes_correspondiente').addEventListener('change', function() {
                    document.getElementById('filtro-ano-form').submit();
                });
            </script>
            <table class="table tabla-pagos">
                <thead>
                    <tr>
                        <th>Monto</th>
                        <th>Mes</th>
                        <th>Fecha</th>
                        <th>Comprobante</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($pagos as $pago)
                    <tr>
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
                            @if($pago->estado === 'pendiente')
                                <form method="POST" action="{{ route('pagos.aprobar', $pago->id) }}" style="display:inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-success btn-sm">Aprobar</button>
                                </form>
                                <form method="POST" action="{{ route('pagos.rechazar', $pago->id) }}" style="display:inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-danger btn-sm">Rechazar</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5">No hay pagos para este usuario.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
            <a href="{{ route('pagos.index') }}" class="btn btn-secondary">Volver</a>
        </div>
    @endsection
</body>
</html>
