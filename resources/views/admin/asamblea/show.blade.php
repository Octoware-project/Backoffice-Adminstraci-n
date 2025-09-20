@extends('layouts.app')

@section('content')
    @include('componentes.navbar')
    <link rel="stylesheet" href="{{ asset('css/admin-custom.css') }}">
    <link rel="stylesheet" href="{{ asset('css/asamblea-custom.css') }}">
    <div class="main-content">
        <div class="asamblea-title">Información de la Junta</div>
        <table class="asamblea-table">
            <tr><th>Lugar</th><td>{{ $junta->lugar }}</td></tr>
            <tr><th>Fecha</th><td>{{ $junta->fecha }}</td></tr>
            <tr><th>Detalle</th><td>{{ $junta->detalle }}</td></tr>
        </table>
    <a href="{{ route('admin.juntas_asamblea.index') }}" class="btn btn-secondary">Volver</a>
    </div>
@endsection
