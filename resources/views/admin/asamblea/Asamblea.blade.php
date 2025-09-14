<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Juntas de Asamblea</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('css/admin-custom.css') }}">
</head>
<body>
    @extends('layouts.app')

    @section('content')
        
        <div class="main-content">
            <div class="asamblea-title">Juntas de Asamblea</div>
            <a href="{{ route('juntas_asamblea.create') }}" class="btn-nueva-junta">Crear nueva junta</a>
            <table class="table asamblea-table">
                <thead>
                    <tr>
                        <th>Lugar</th>
                        <th>Fecha</th>
                        <th>Detalle</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($juntas as $junta)
                        <tr class="junta-row" data-junta-id="{{ $junta->id }}">
                            <td>{{ $junta->lugar }}</td>
                            <td>{{ $junta->fecha }}</td>
                            <td>{{ $junta->detalle }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3">No hay juntas registradas.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    document.querySelectorAll('.junta-row').forEach(function(row) {
                        row.addEventListener('dblclick', function() {
                            var juntaId = this.getAttribute('data-junta-id');
                            window.location.href = "{{ url('/juntas_asamblea') }}/" + juntaId;
                        });
                    });
                });
            </script>
        </div>
    @endsection
</body>
</html>