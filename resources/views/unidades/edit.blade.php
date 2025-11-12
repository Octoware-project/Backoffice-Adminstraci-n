@extends('layouts.app')
<link rel="stylesheet" href="{{ asset('css/UnidadesHabitacionales/Edit.css') }}">   
@section('content')

<div class="planes-workspace">
    <!-- Header -->
    <div class="planes-header">
        <div class="header-content">
            <div>
                <h1 class="header-title">
                    <i class="fas fa-edit" style="margin-right: 1rem;"></i>
                    Editar Unidad Habitacional
                </h1>
                <p class="header-subtitle">Modifica los datos de la unidad "{{ $unidad->numero_departamento }}"</p>
            </div>
            <a href="{{ route('unidades.show', $unidad) }}" class="back-btn">
                <i class="fas fa-arrow-left"></i>
                Volver
            </a>
        </div>
    </div>

    <!-- Form Container -->
    <div class="form-container">
        <!-- Mostrar errores de validación -->
        @if($errors->any())
            <div style="background: #fee2e2; border: 1px solid #fca5a5; border-radius: var(--radius-sm); padding: 1rem; margin-bottom: 2rem;">
                <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.5rem;">
                    <i class="fas fa-exclamation-triangle" style="color: #dc2626;"></i>
                    <h4 style="margin: 0; color: #dc2626; font-size: 1rem; font-weight: 600;">Error al actualizar la unidad habitacional</h4>
                </div>
                <ul style="margin: 0; padding-left: 1.5rem; color: #dc2626;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('unidades.update', $unidad) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="form-group">
                <label for="numero_departamento" class="form-label">
                    <i class="fas fa-home"></i>
                    Número de Departamento
                </label>
                <input 
                    type="text" 
                    class="form-control @error('numero_departamento') is-invalid @enderror" 
                    id="numero_departamento" 
                    name="numero_departamento" 
                    value="{{ old('numero_departamento', $unidad->numero_departamento) }}"
                    placeholder="Ej: 101, A-15, Torre 1 - Apto 205"
                    required
                >
                @error('numero_departamento')
                    <div style="color: var(--danger-color); font-size: 0.875rem; margin-top: 0.5rem;">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="form-group">
                <label for="piso" class="form-label">
                    <i class="fas fa-layer-group"></i>
                    Piso
                </label>
                <input 
                    type="number" 
                    class="form-control @error('piso') is-invalid @enderror" 
                    id="piso" 
                    name="piso" 
                    value="{{ old('piso', $unidad->piso) }}"
                    placeholder="Ej: 1, 2, 3..."
                    min="1"
                    required
                >
                @error('piso')
                    <div style="color: var(--danger-color); font-size: 0.875rem; margin-top: 0.5rem;">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="form-group">
                <label for="estado" class="form-label">
                    <i class="fas fa-info-circle"></i>
                    Estado
                </label>
                <select class="form-control @error('estado') is-invalid @enderror" id="estado" name="estado" required>
                    <option value="">Seleccione un estado</option>
                    <option value="En construccion" {{ (old('estado', $unidad->estado) == 'En construccion') ? 'selected' : '' }}>
                        <i class="fas fa-hammer"></i> En Construcción - Actualmente en proceso de construcción
                    </option>
                    <option value="Finalizado" {{ (old('estado', $unidad->estado) == 'Finalizado') ? 'selected' : '' }}>
                        <i class="fas fa-check-circle"></i> Finalizado - Lista para ocupar
                    </option>
                </select>
                @error('estado')
                    <div style="color: var(--danger-color); font-size: 0.875rem; margin-top: 0.5rem;">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="form-group">
                <label for="descripcion" class="form-label">
                    <i class="fas fa-file-alt"></i>
                    Descripción (Opcional)
                </label>
                <textarea 
                    class="form-control @error('descripcion') is-invalid @enderror" 
                    id="descripcion" 
                    name="descripcion" 
                    rows="4"
                    placeholder="Descripción adicional sobre la unidad habitacional..."
                >{{ old('descripcion', $unidad->descripcion) }}</textarea>
                @error('descripcion')
                    <div style="color: var(--danger-color); font-size: 0.875rem; margin-top: 0.5rem;">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-modern btn-warning-modern">
                    <i class="fas fa-save"></i>
                    Actualizar Unidad
                </button>
                <a href="{{ route('unidades.show', $unidad) }}" class="btn-modern btn-secondary-modern">
                    <i class="fas fa-times"></i>
                    Cancelar
                </a>
            </div>
        </form>
    </div>
</div>
@endsection