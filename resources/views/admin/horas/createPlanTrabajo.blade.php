@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Crear Plan de Trabajo</h1>
    <form action="{{ route('plan-trabajos.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="user_id" class="form-label">Usuario</label>
            <select name="user_id" id="user_id" class="form-control" required>
                <option value="">Seleccione un usuario</option>
                @foreach($usuarios as $usuario)
                    <option value="{{ $usuario->id }}">{{ $usuario->name }} ({{ $usuario->email }})</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="mes" class="form-label">Mes</label>
            <input type="number" name="mes" id="mes" class="form-control" min="1" max="12" required>
        </div>
        <div class="mb-3">
            <label for="anio" class="form-label">Año</label>
            <input type="number" name="anio" id="anio" class="form-control" value="{{ date('Y') }}" required>
        </div>
        <div class="mb-3">
            <label for="horas_requeridas" class="form-label">Horas Requeridas</label>
            <input type="number" name="horas_requeridas" id="horas_requeridas" class="form-control" min="1" required>
        </div>
        <button type="submit" class="btn btn-success">Crear</button>
        <a href="{{ route('plan-trabajos.index') }}" class="btn btn-secondary">Volver</a>
    </form>
</div>
@endsection
