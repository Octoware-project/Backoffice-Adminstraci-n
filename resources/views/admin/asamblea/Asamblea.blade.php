@extends('layouts.app')

@section('content')
    <div class="main-content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold mb-0">Juntas de Asamblea</h2>
            <a href="{{ route('admin.juntas_asamblea.create') }}" class="btn btn-success shadow-sm">
                <i class="fa fa-plus me-2"></i> Nueva Junta
            </a>
        </div>
        <div class="card shadow-sm">
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th scope="col">Lugar</th>
                            <th scope="col">Fecha</th>
                            <th scope="col">Detalle</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($juntas as $junta)
                            <tr style="cursor:pointer" onclick="window.location='{{ route('admin.juntas_asamblea.show', [$junta->id]) }}';">
                                <td>{{ $junta->lugar }}</td>
                                <td>{{ $junta->fecha }}</td>
                                <td>{{ $junta->detalle }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted">No hay juntas registradas.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection