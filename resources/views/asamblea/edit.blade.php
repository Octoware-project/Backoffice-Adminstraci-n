@extends('layouts.app')
<link rel="stylesheet" href="{{ asset('css/Asamblea/Edit.css') }}">
@section('content')


{{-- FontAwesome para iconos --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<div class="edit-workspace">
    <!-- Header -->
    <div class="edit-header fade-in">
        <div class="header-content">
            <div class="header-info">
                <h1 class="header-title">
                    <i class="fas fa-edit"></i>
                    Editar Junta de Asamblea
                </h1>
                <p class="header-subtitle">
                    Modifica la información de la junta "{{ $junta->lugar }}"
                </p>
            </div>
            <a href="{{ route('admin.juntas_asamblea.show', $junta->id) }}" class="btn-back">
                <i class="fas fa-arrow-left"></i>
                Volver
            </a>
        </div>
    </div>

    <!-- Formulario de edición -->
    <div class="form-container fade-in">
        <div class="form-header">
            <h2 class="form-title">
                <i class="fas fa-gavel"></i>
                Información de la Junta
            </h2>
        </div>
        <div class="form-body">
            <form method="POST" action="{{ route('admin.juntas_asamblea.update', $junta->id) }}" id="edit-form">
                @csrf
                @method('PUT')
                
                <div class="form-grid">
                    <!-- Lugar -->
                    <div class="form-group">
                        <label for="lugar" class="form-label">
                            <i class="fas fa-map-marker-alt"></i>
                            Lugar de Realización
                        </label>
                        <input 
                            type="text" 
                            name="lugar" 
                            id="lugar" 
                            class="form-input @error('lugar') error @enderror" 
                            value="{{ old('lugar', $junta->lugar) }}" 
                            placeholder="Ej: Sala de juntas, Auditorium principal..."
                            required
                        >
                        @error('lugar')
                            <div class="form-error">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Fecha -->
                    <div class="form-group">
                        <label for="fecha" class="form-label">
                            <i class="fas fa-calendar-alt"></i>
                            Fecha de la Junta
                        </label>
                        <input 
                            type="date" 
                            name="fecha" 
                            id="fecha" 
                            class="form-input @error('fecha') error @enderror" 
                            value="{{ old('fecha', $junta->fecha) }}" 
                            required
                        >
                        @error('fecha')
                            <div class="form-error">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Detalle -->
                    <div class="form-group">
                        <label for="detalle" class="form-label">
                            <i class="fas fa-file-alt"></i>
                            Detalles y Observaciones
                            <span style="font-weight: 400; color: var(--text-muted); text-transform: none; font-size: 0.75rem;">(Opcional)</span>
                        </label>
                        <textarea 
                            name="detalle" 
                            id="detalle" 
                            class="form-textarea @error('detalle') error @enderror"
                            placeholder="Describe los temas a tratar, observaciones importantes o cualquier información adicional relevante para esta junta..."
                        >{{ old('detalle', $junta->detalle) }}</textarea>
                        @error('detalle')
                            <div class="form-error">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn-submit">
                        <i class="fas fa-save"></i>
                        Actualizar Junta
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="{{ asset('js/Asamblea/Edit.js') }}"></script>
@endsection
