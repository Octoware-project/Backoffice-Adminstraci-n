@extends('layouts.app')

@section('content')
    @include('componentes.navbar')
    <link rel="stylesheet" href="{{ asset('css/admin-custom.css') }}">
    <link rel="stylesheet" href="{{ asset('css/asamblea-custom.css') }}">
    <div class="formulario-asamblea-container">
        <div class="asamblea-title">Crear Nueva Junta</div>
    <form method="POST" action="{{ route('admin.juntas_asamblea.store') }}" class="asamblea-form">
            @csrf
            <label for="lugar">Lugar</label>
            <input type="text" name="lugar" id="lugar" class="form-control" required>
            <label for="fecha">Fecha</label>
            <input type="date" name="fecha" id="fecha" class="form-control" required>
            <label for="detalle">Detalle</label>
            <textarea name="detalle" id="detalle" class="form-control" rows="3"></textarea>
            <button type="submit" class="btn btn-primary">Guardar</button>
            <a href="{{ route('admin.juntas_asamblea.index') }}" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
@endsection