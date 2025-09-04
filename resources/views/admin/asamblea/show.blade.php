@extends('layouts.app')

@section('content')
    @include('componentes.navbar')
    <link rel="stylesheet" href="{{ asset('css/admin-custom.css') }}">
    <div class="main-content">
        <div class="asamblea-title">Información de la Junta</div>
        <table class="asamblea-table">
            <tr><th>Lugar</th><td>{{ $junta->lugar }}</td></tr>
            <tr><th>Fecha</th><td>{{ $junta->fecha }}</td></tr>
            <tr><th>Detalle</th><td>{{ $junta->detalle }}</td></tr>
        </table>
        <a href="{{ route('admin.asamblea.index') }}" class="btn btn-secondary">Volver</a>
    </div>
@endsection
            color: #2c3e50;
            font-size: 2rem;
            margin-bottom: 18px;
            font-weight: 600;
        }
        .asamblea-table {
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(44,62,80,0.07);
            padding: 24px 28px;
            max-width: 600px;
            margin-bottom: 30px;
        }
        .asamblea-table th, .asamblea-table td {
            padding: 8px 12px;
            text-align: left;
        }
        .asamblea-table th {
            background: #f4f6f8;
            color: #34495e;
            width: 180px;
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
    </style>
    <div class="main-content">
        <div class="asamblea-title">Información de la Junta</div>
        <table class="asamblea-table">
            <tr><th>Lugar</th><td>{{ $junta->lugar }}</td></tr>
            <tr><th>Fecha</th><td>{{ $junta->fecha }}</td></tr>
            <tr><th>Detalle</th><td>{{ $junta->detalle }}</td></tr>
        </table>
        <a href="{{ route('admin.asamblea.index') }}" class="btn btn-secondary">Volver</a>
    </div>
@endsection
