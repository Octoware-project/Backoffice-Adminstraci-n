<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Pagos del usuario</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('TablaStyles.css') }}">
</head>
<body>
    @include('componentes.navbar')
    <div class="main-content">
        <h2>Pagos de {{ $pagos->first()->persona->nombre ?? '' }} {{ $pagos->first()->persona->apellido ?? '' }}</h2>
        <form method="GET" action="" class="mb-3 d-flex align-items-end" id="filtro-ano-form">
            <div class="me-2">
                <label for="año_correspondiente" class="form-label mb-0">Filtrar por año:</label>
                <input type="number" name="año_correspondiente" id="año_correspondiente" class="form-control"
                    value="{{ request('año_correspondiente') }}" min="2000" max="2100" placeholder="Año">
            </div>
            <button type="submit" class="btn btn-primary d-none">Filtrar</button>
        </form>
        <script>
            document.getElementById('año_correspondiente').addEventListener('change', function() {
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
                    <td>{{ ucfirst($pago->estado) }}</td>
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
</body>
</html>
