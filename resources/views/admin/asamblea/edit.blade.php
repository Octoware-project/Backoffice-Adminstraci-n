@extends('layouts.app')

<link rel="stylesheet" href="{{ asset('css/admin-custom.css') }}">
<link rel="stylesheet" href="{{ asset('css/asamblea-custom.css') }}">
@section('content')
    <h1>Editar Junta de Asamblea</h1>
    <form method="POST" action="{{ route('juntas_asamblea.update', $junta->id) }}">
        @csrf
        @method('PUT')
        <label for="lugar">Lugar</label>
        <input type="text" name="lugar" id="lugar" value="{{ old('lugar', $junta->lugar) }}" required>
        <label for="fecha">Fecha</label>
        <input type="date" name="fecha" id="fecha" value="{{ old('fecha', $junta->fecha) }}" required>
        <label for="detalle">Detalle</label>
        <textarea name="detalle" id="detalle">{{ old('detalle', $junta->detalle) }}</textarea>
        <button type="submit">Actualizar</button>
    </form>
@endsection
