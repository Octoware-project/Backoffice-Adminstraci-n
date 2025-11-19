@extends('layouts.app')
<link rel="stylesheet" href="{{ asset('css/Asamblea/Create.css') }}">
@section('content')


<div class="planes-workspace">
    <!-- Header -->
    <div class="planes-header">
        <div class="header-content">
            <div>
                <h1 class="header-title">
                    <i class="fas fa-plus-circle" style="margin-right: 1rem;"></i>
                    Crear Nueva Junta
                </h1>
                <p class="header-subtitle">Complete la información para programar una nueva junta de asamblea</p>
            </div>
            <a href="{{ route('admin.juntas_asamblea.index') }}" class="back-btn">
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
                    <h4 style="margin: 0; color: #dc2626; font-size: 1rem; font-weight: 600;">Error al crear la junta</h4>
                </div>
                <ul style="margin: 0.5rem 0 0 0; padding-left: 1.5rem; color: #7f1d1d;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Mostrar mensaje de éxito -->
        @if(session('success'))
            <div style="background: #d1fae5; border: 1px solid #6ee7b7; border-radius: var(--radius-sm); padding: 1rem; margin-bottom: 2rem;">
                <div style="display: flex; align-items: center; gap: 0.5rem;">
                    <i class="fas fa-check-circle" style="color: #059669;"></i>
                    <p style="margin: 0; color: #047857; font-weight: 500;">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.juntas_asamblea.store') }}">
            @csrf
            
            <div class="form-group">
                <label for="lugar" class="form-label">
                    <i class="fas fa-map-marker-alt" style="margin-right: 0.5rem;"></i>
                    Lugar de la Junta
                </label>
                <input type="text" name="lugar" id="lugar" class="form-control" 
                       placeholder="Ingrese el lugar donde se realizará la junta" 
                       value="{{ old('lugar') }}" required>
            </div>

            <div class="form-group">
                <label for="fecha" class="form-label">
                    <i class="fas fa-calendar" style="margin-right: 0.5rem;"></i>
                    Fecha de Realización
                </label>
                <input type="date" name="fecha" id="fecha" class="form-control" 
                       value="{{ old('fecha') }}" required>
            </div>

            <div class="form-group">
                <label for="detalle" class="form-label">
                    <i class="fas fa-file-alt" style="margin-right: 0.5rem;"></i>
                    Detalle de la Junta
                </label>
                <textarea name="detalle" id="detalle" class="form-control" 
                          placeholder="Describa los temas a tratar, objetivos o información adicional relevante...">{{ old('detalle') }}</textarea>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-modern btn-primary-modern">
                    <i class="fas fa-save"></i>
                    Crear Junta
                </button>
            </div>
        </form>
    </div>
</div>

<script src="{{ asset('js/Asamblea/Create.js') }}"></script>
</script>

@endsection