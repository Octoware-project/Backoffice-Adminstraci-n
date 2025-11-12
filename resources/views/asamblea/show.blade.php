@extends('layouts.app')
<link rel="stylesheet" href="{{ asset('css/Asamblea/Show.css') }}">
@section('content')

{{-- FontAwesome para iconos --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<div class="junta-workspace">
    <!-- Header de la Junta -->
    <div class="junta-header fade-in">
        <div class="header-content">
            <div class="header-info">
                <h1 class="header-title">
                    <i class="fas fa-gavel"></i>
                    {{ $junta->lugar }}
                </h1>
                <p class="header-subtitle">
                    Junta de Asamblea - {{ \Carbon\Carbon::parse($junta->fecha)->locale('es')->translatedFormat('d \d\e F \d\e Y') }}
                </p>
            </div>
            <div class="btn-group">
                <a href="{{ route('admin.juntas_asamblea.edit', $junta->id) }}" class="btn-modern btn-primary-modern">
                    <i class="fas fa-edit"></i>
                    Editar Junta
                </a>
                <a href="{{ route('admin.juntas_asamblea.index') }}" class="btn-modern btn-secondary-modern">
                    <i class="fas fa-arrow-left"></i>
                    Volver
                </a>
            </div>
        </div>
    </div>

    <!-- Contenido principal -->
    <div class="junta-content fade-in">
        <!-- Información de la junta -->
        <div class="junta-info-card">
            <div class="info-card-header">
                <h2 class="info-card-title">
                    <i class="fas fa-info-circle"></i>
                    Detalles de la Junta
                </h2>
            </div>
            <div class="info-card-body">
                <div class="info-grid">
                    <div class="info-item">
                        <label class="info-label">
                            <i class="fas fa-map-marker-alt"></i>
                            Lugar de Realización
                        </label>
                        <div class="info-value">
                            {{ $junta->lugar }}
                        </div>
                    </div>

                    <div class="info-item">
                        <label class="info-label">
                            <i class="fas fa-calendar-alt"></i>
                            Fecha de la Junta
                        </label>
                        <div class="info-value">
                            {{ \Carbon\Carbon::parse($junta->fecha)->locale('es')->translatedFormat('l, d \d\e F \d\e Y') }}
                        </div>
                    </div>

                    @if($junta->detalle)
                    <div class="info-item">
                        <label class="info-label">
                            <i class="fas fa-file-alt"></i>
                            Detalles y Observaciones
                        </label>
                        <div class="info-value large">
                            {{ $junta->detalle }}
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Acciones rápidas -->
        <div class="actions-card">
            <div class="actions-header">
                <h3 class="actions-title">
                    <i class="fas fa-bolt"></i>
                    Acciones Rápidas
                </h3>
            </div>
            <div class="actions-body">
                <div class="actions-list">
                    <a href="{{ route('admin.juntas_asamblea.edit', $junta->id) }}" class="action-item action-edit">
                        <div class="action-icon">
                            <i class="fas fa-edit"></i>
                        </div>
                        <div class="action-content">
                            <div class="action-title">Editar Información</div>
                            <div class="action-description">Modificar los datos de la junta</div>
                        </div>
                    </a>

                    <a href="javascript:void(0)" onclick="confirmDeleteJunta({{ $junta->id }}, '{{ $junta->lugar }}')" class="action-item action-delete">
                        <div class="action-icon">
                            <i class="fas fa-trash"></i>
                        </div>
                        <div class="action-content">
                            <div class="action-title">Eliminar Junta</div>
                            <div class="action-description">Eliminar permanentemente esta junta</div>
                        </div>
                    </a>

                    <a href="{{ route('admin.juntas_asamblea.index') }}" class="action-item action-back">
                        <div class="action-icon">
                            <i class="fas fa-arrow-left"></i>
                        </div>
                        <div class="action-content">
                            <div class="action-title">Volver al Listado</div>
                            <div class="action-description">Regresar a la lista de juntas</div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>


</div>

<!-- Modal de confirmación de eliminación -->
<form method="POST" id="delete-form" style="display: none;">
    @csrf
    @method('DELETE')
</form>

<script>
    // Pasar datos de Laravel a JavaScript
    window.asambleaConfig = {
        juntaId: {{ $junta->id }},
        deleteUrl: '{{ url('/juntas_asamblea') }}',
        indexUrl: '{{ route('admin.juntas_asamblea.index') }}'
    };
</script>
<script src="{{ asset('js/Asamblea/Show.js') }}"></script>
@endsection
