@extends('layouts.app')

{{-- CSS Modular --}}
@push('styles')
<link href="{{ asset('admin/horas/assets/css/horas-common.css') }}" rel="stylesheet">
<link href="{{ asset('admin/horas/assets/css/configuracion.css') }}" rel="stylesheet">
<link href="{{ asset('admin/horas/assets/css/configuracion-specific.css') }}" rel="stylesheet">
@endpush

@section('content')
<div class="planes-workspace">
    <!-- Header -->
    <div class="planes-header">
        <div class="header-content">
            <div>
                <h1 class="header-title">
                    <i class="fas fa-cog icon-mg-1"></i>
                    Configuración Valor por Hora
                </h1>
                <p class="header-subtitle">Gestiona el valor por hora para conversión de justificaciones</p>
            </div>
            <a href="{{ route('plan-trabajos.index') }}" class="back-btn">
                <i class="fas fa-arrow-left"></i>
                Volver a Planes
            </a>
        </div>
    </div>

    <!-- Mostrar mensaje de éxito -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle icon-mg-05"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle icon-mg-05"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="config-grid">
        <!-- Configuración Principal -->
        <div>
            <div class="config-card">
                <h3 class="config-title">
                    <i class="fas fa-dollar-sign config-icon-primary"></i>
                    Valor Actual por Hora
                </h3>
                @if($configuracion)
                    <div class="valor-actual">${{ number_format($configuracion->valor_por_hora, 2) }}</div>
                    <small class="text-muted config-text-muted">
                        <i class="fas fa-clock icon-mg-025"></i>
                        Configurado el {{ $configuracion->created_at->format('d/m/Y H:i') }}
                    </small>
                @else
                    <div class="valor-actual text-danger">No configurado</div>
                    <div class="alert alert-warning alert-warning-custom">
                        <i class="fas fa-exclamation-triangle"></i>
                        <strong>Atención:</strong> Debe configurar un valor por hora para que funcionen las conversiones de justificaciones.
                    </div>
                @endif

                <!-- Formulario de Actualización -->
                <hr class="config-divider">
                <h4 class="config-subtitle">
                    <i class="fas fa-edit config-icon-primary"></i>
                    Actualizar Valor
                </h4>
                <form method="POST" action="{{ route('configuracion-horas.update') }}">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-8">
                            <div class="mb-3">
                                <label for="valor_por_hora" class="form-label">
                                    <i class="fas fa-money-bill-wave icon-mg-025"></i>
                                    Nuevo Valor por Hora
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" 
                                           id="valor_por_hora"
                                           name="valor_por_hora" 
                                           class="form-control" 
                                           value="{{ old('valor_por_hora', $configuracion?->valor_por_hora ?? '') }}" 
                                           step="0.01" 
                                           min="0" 
                                           required>
                                </div>
                                @error('valor_por_hora')
                                    <div class="text-danger small validation-error">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <!-- Observaciones Reimaginadas -->
                            <div class="observaciones-container">
                                <label class="observaciones-label">
                                    <i class="fas fa-sticky-note icon-mg-05"></i>
                                    Nota del cambio
                                </label>
                                <div class="observaciones-wrapper">
                                    <textarea id="observaciones"
                                              name="observaciones" 
                                              class="observaciones-textarea" 
                                              placeholder="¿Por qué cambias este valor? (opcional)"
                                              maxlength="200">{{ old('observaciones') }}</textarea>
                                    <div class="observaciones-counter">
                                        <span id="char-count">0</span>/200
                                    </div>
                                </div>
                                @error('observaciones')
                                    <div class="observaciones-error">
                                        <i class="fas fa-exclamation-triangle"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <button type="submit" class="btn-guardar-config" id="btn-guardar">
                        <i class="fas fa-save"></i>
                        Guardar Nueva Configuración
                    </button>
                </form>

                <!-- Explicación -->
                <div class="alert alert-info mt-4">
                    <h5 class="config-title">
                        <i class="fas fa-info-circle"></i> ¿Cómo funciona?
                    </h5>
                    <ul class="mb-0 config-info-list">
                        <li class="config-info-item">Los usuarios pueden registrar <strong>justificaciones con montos</strong> cuando no cumplen sus horas.</li>
                        <li class="config-info-item">Este valor se usa para convertir los montos en <strong>horas equivalentes</strong>.</li>
                        <li class="config-info-item"><strong>Ejemplo:</strong> Si configuras $1000/hora y un usuario se justifica con $3000, se le suman 3 horas a su plan.</li>
                        <li>Cada registro <strong>guarda el valor que se usó</strong>, así los cambios futuros no afectan cálculos anteriores.</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Historial Reciente -->
        @if($historial && $historial->count() > 0)
            <div class="config-card">
                <h4 class="config-subtitle">
                    <i class="fas fa-history" style="color: var(--primary-color); margin-right: 0.5rem;"></i>
                    Historial Reciente
                </h4>
                <div class="list-group list-group-flush">
                    @foreach($historial as $config)
                        <div class="list-group-item config-history-item d-flex justify-content-between align-items-center {{ $config->activo ? 'active' : '' }}">
                            <div>
                                <strong class="config-history-value">${{ number_format($config->valor_por_hora, 2) }}</strong>
                                <br>
                                <small class="text-muted config-text-muted">
                                    <i class="fas fa-calendar icon-mg-025"></i>
                                    {{ $config->created_at->format('d/m H:i') }}
                                </small>
                            </div>
                            @if($config->activo)
                                <span class="badge badge-active">Actual</span>
                            @endif
                        </div>
                    @endforeach
                </div>
                
                <!-- Botón moderno para ver historial completo -->
                <div style="margin-top: 1.5rem;">
                    <a href="{{ route('configuracion-horas.historial') }}" 
                       class="btn-historial-completo">
                        <i class="fas fa-history icon-mg-05"></i>
                        Ver Historial Completo
                        <i class="fas fa-arrow-right" style="margin-left: 0.5rem; font-size: 0.75rem;"></i>
                    </a>
                </div>
            </div>
        @endif
        </div>
    </div>
</div>

{{-- JavaScript Modular --}}
@push('scripts')
<script src="{{ asset('admin/horas/assets/js/configuracion.js') }}"></script>
@endpush
@endsection