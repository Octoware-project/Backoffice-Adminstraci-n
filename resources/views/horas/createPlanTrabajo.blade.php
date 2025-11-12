@extends('layouts.app')
<link rel="stylesheet" href="{{ asset('css/Horas/Create.css') }}">
@section('content')


<div class="planes-workspace">
    <!-- Header -->
    <div class="planes-header">
        <div class="header-content">
            <div>
                <h1 class="header-title">
                    <i class="fas fa-plus-circle" style="margin-right: 1rem;"></i>
                    Crear Plan de Trabajo
                </h1>
                <p class="header-subtitle">Configura un nuevo plan de trabajo para un usuario específico</p>
            </div>
            <a href="{{ route('plan-trabajos.index') }}" class="back-btn">
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
                    <h4 style="margin: 0; color: #dc2626; font-size: 1rem; font-weight: 600;">Error al crear el plan de trabajo</h4>
                </div>
                @if($errors->has('duplicate'))
                    <p style="margin: 0; color: #7f1d1d; font-weight: 500;">{{ $errors->first('duplicate') }}</p>
                @else
                    <ul style="margin: 0.5rem 0 0 0; padding-left: 1.5rem; color: #7f1d1d;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                @endif
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

        <form action="{{ route('plan-trabajos.store') }}" method="POST">
            @csrf
            
            <div class="form-group">
                <label for="user_id" class="form-label">
                    <i class="fas fa-user" style="margin-right: 0.5rem;"></i>
                    Usuario
                </label>
                
                <div class="user-selector-container">
                    <!-- Controles de filtrado y ordenamiento -->
                    <div class="user-selector-controls">
                        <input type="text" id="user-filter" class="user-filter-input" placeholder="Buscar por nombre o email...">
                        
                        <div class="user-sort-buttons">
                            <button type="button" class="sort-btn active" data-sort="name">
                                <i class="fas fa-sort-alpha-down"></i> Nombre
                            </button>
                            <button type="button" class="sort-btn" data-sort="email">
                                <i class="fas fa-envelope"></i> Email
                            </button>
                        </div>
                    </div>
                    
                    <!-- Select de usuarios -->
                    <select name="user_id" id="user_id" class="form-control" required>
                        <option value="">Seleccione un usuario</option>
                        @foreach($usuarios as $usuario)
                            <option value="{{ $usuario->id }}" 
                                    data-name="{{ strtolower($usuario->name) }}" 
                                    data-email="{{ strtolower($usuario->email) }}"
                                    data-display-name="{{ $usuario->name }}"
                                    data-display-email="{{ $usuario->email }}">
                                {{ $usuario->name }} ({{ $usuario->email }})
                            </option>
                        @endforeach
                    </select>
                    
                    <div class="user-count" id="user-count">
                        Mostrando {{ count($usuarios) }} usuarios
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="mes" class="form-label">
                    <i class="fas fa-calendar" style="margin-right: 0.5rem;"></i>
                    Mes
                </label>
                <select name="mes" id="mes" class="form-control" required>
                    <option value="">Seleccione un mes</option>
                    <option value="1">Enero</option>
                    <option value="2">Febrero</option>
                    <option value="3">Marzo</option>
                    <option value="4">Abril</option>
                    <option value="5">Mayo</option>
                    <option value="6">Junio</option>
                    <option value="7">Julio</option>
                    <option value="8">Agosto</option>
                    <option value="9">Septiembre</option>
                    <option value="10">Octubre</option>
                    <option value="11">Noviembre</option>
                    <option value="12">Diciembre</option>
                </select>
            </div>

            <div class="form-group">
                <label for="anio" class="form-label">
                    <i class="fas fa-calendar-alt" style="margin-right: 0.5rem;"></i>
                    Año
                </label>
                <input type="number" name="anio" id="anio" class="form-control" value="{{ date('Y') }}" min="2020" max="2030" required>
            </div>

            <div class="form-group">
                <label for="horas_requeridas" class="form-label">
                    <i class="fas fa-clock" style="margin-right: 0.5rem;"></i>
                    Horas Requeridas
                </label>
                <input type="number" name="horas_requeridas" id="horas_requeridas" class="form-control" min="1" max="500" required>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-modern btn-primary-modern">
                    <i class="fas fa-save"></i>
                    Crear Plan de Trabajo
                </button>
            </div>
        </form>
    </div>
</div>

<script src="{{ asset('js/Horas/CrearPlanTrabajo.js') }}"></script>
@endsection
