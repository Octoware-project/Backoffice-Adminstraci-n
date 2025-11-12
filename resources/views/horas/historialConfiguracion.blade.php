@extends('layouts.app')
<link rel="stylesheet" href="{{ asset('css/Horas/Historial.css') }}">
@section('content')

<div class="planes-workspace">
    <!-- Header -->
    <div class="planes-header">
        <div class="header-content">
            <div>
                <h1 class="header-title">
                    <i class="fas fa-history" style="margin-right: 1rem;"></i>
                    Historial de Configuración
                </h1>
                <p class="header-subtitle">Registro completo de cambios en el valor por hora</p>
            </div>
            <a href="{{ route('configuracion-horas.index') }}" class="back-btn">
                <i class="fas fa-arrow-left"></i>
                Volver
            </a>
        </div>
    </div>

    <div class="table-container">
        <table class="modern-table">
            <thead>
                <tr>
                    <th>
                        <i class="fas fa-calendar-alt" style="margin-right: 0.5rem; color: var(--primary-color);"></i>
                        Fecha/Hora
                    </th>
                    <th>
                        <i class="fas fa-dollar-sign" style="margin-right: 0.5rem; color: var(--primary-color);"></i>
                        Valor por Hora
                    </th>
                    <th>
                        <i class="fas fa-info-circle" style="margin-right: 0.5rem; color: var(--primary-color);"></i>
                        Estado
                    </th>
                    <th>
                        <i class="fas fa-sticky-note" style="margin-right: 0.5rem; color: var(--primary-color);"></i>
                        Nota del Cambio
                    </th>
                </tr>
            </thead>
            <tbody>
                @forelse($configuraciones as $config)
                    <tr class="{{ $config->activo ? 'config-activo' : '' }}">
                        <td>
                            <div style="display: flex; flex-direction: column;">
                                <strong style="color: var(--text-primary); font-size: 1rem; font-weight: 600;">{{ $config->created_at->format('d/m/Y') }}</strong>
                                <small class="text-muted" style="font-weight: 500; color: var(--text-muted); margin-top: 0.25rem;">
                                    <i class="fas fa-clock" style="margin-right: 0.25rem; font-size: 0.75rem;"></i>
                                    {{ $config->created_at->format('H:i') }}h
                                </small>
                            </div>
                        </td>
                        <td>
                            <div style="display: flex; align-items: center;">
                                <span style="font-weight: 700; font-size: 1.125rem; color: var(--primary-color);">
                                    ${{ number_format($config->valor_por_hora, 2) }}
                                </span>
                            </div>
                        </td>
                        <td>
                            @if($config->activo)
                                <span class="badge badge-activo">
                                    <i class="fas fa-check-circle"></i>
                                    Activo
                                </span>
                            @else
                                <span class="badge badge-historico">
                                    <i class="fas fa-history"></i>
                                    Histórico
                                </span>
                            @endif
                        </td>
                        <td>
                            @if($config->observaciones)
                                <div class="nota-container">
                                    <div class="nota-content">
                                        <i class="fas fa-quote-left nota-icon"></i>
                                        <span class="nota-text">{{ $config->observaciones }}</span>
                                    </div>
                                </div>
                            @else
                                <div class="nota-empty">
                                    <i class="fas fa-minus" style="margin-right: 0.5rem; font-size: 0.75rem; opacity: 0.3;"></i>
                                    <em style="font-style: italic; font-weight: 400; color: var(--text-muted); font-size: 0.875rem;">Sin nota</em>
                                </div>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="empty-state">
                            <div>
                                <i class="fas fa-inbox empty-state-icon"></i>
                                <br>
                                <strong style="color: var(--text-primary); font-size: 1.1rem;">No hay configuraciones registradas</strong>
                                <br>
                                <span style="color: var(--text-muted); font-size: 0.9rem; margin-top: 0.5rem; display: inline-block;">
                                    El historial aparecerá cuando se realicen cambios en la configuración
                                </span>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($configuraciones->hasPages())
        <div class="d-flex justify-content-center" style="margin-top: 2rem;">
            {{ $configuraciones->links() }}
        </div>
    @endif
</div>
@endsection