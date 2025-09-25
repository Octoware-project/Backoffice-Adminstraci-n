@extends('layouts.app')

@section('content')
<style>
    /* Background de la página */
    body {
        background-color: #d2d2f1ff !important;
    }

    /* Reset y Variables */
    :root {
        --primary-color: #667eea;
        --primary-light: #764ba2;
        --secondary-color: #f093fb;
        --success-color: #4ecdc4;
        --warning-color: #ffecd2;
        --danger-color: #fc466b;
        --text-primary: #1a202c;
        --text-secondary: #2d3748;
        --text-muted: #4a5568;
        --bg-primary: #ffffff;
        --bg-secondary: #f7fafc;
        --bg-light: #edf2f7;
        --border-color: #a0aec0;
        --shadow-sm: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
        --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        --radius: 12px;
        --radius-sm: 8px;
    }

    /* Container Principal */
    .planes-workspace {
        max-width: 1400px;
        margin: 0 auto;
        padding: 0 1.5rem;
        min-height: 100vh;
        background-color: #d2d2f1ff;
    }

    /* Header Moderno */
    .planes-header {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-light) 100%);
        border-radius: var(--radius);
        padding: 2rem 2.5rem;
        margin-bottom: 2rem;
        box-shadow: var(--shadow-lg);
        color: white;
        position: relative;
        overflow: hidden;
    }

    .planes-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="white" opacity="0.1"/><circle cx="75" cy="75" r="1" fill="white" opacity="0.1"/><circle cx="50" cy="10" r="0.5" fill="white" opacity="0.15"/><circle cx="20" cy="80" r="0.5" fill="white" opacity="0.15"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>') repeat;
        pointer-events: none;
    }

    .header-content {
        position: relative;
        z-index: 1;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .header-title {
        margin: 0;
        font-size: 2.25rem;
        font-weight: 700;
        letter-spacing: -0.025em;
    }

    .header-subtitle {
        margin: 0.5rem 0 0 0;
        font-size: 1.1rem;
        opacity: 0.9;
        font-weight: 400;
    }

    /* Back Button */
    .back-btn {
        display: inline-flex;
        align-items: center;
        gap: 0.75rem;
        padding: 1.1rem 2.2rem;
        border-radius: var(--radius-sm);
        font-weight: 600;
        font-size: 1.125rem;
        text-decoration: none;
        transition: all 0.2s ease;
        background: rgba(255, 255, 255, 0.15);
        color: white;
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .back-btn:hover {
        background: rgba(255, 255, 255, 0.25);
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        color: white;
        text-decoration: none;
    }

    /* Card moderno */
    .card {
        background: var(--bg-primary);
        border-radius: var(--radius);
        box-shadow: var(--shadow-md);
        border: 1px solid var(--border-color);
        margin-bottom: 2rem;
    }

    .card-body {
        padding: 2rem;
    }

    /* Contenedor de Tabla Moderno */
    .table-container {
        background: var(--bg-primary);
        border-radius: var(--radius);
        box-shadow: var(--shadow-md);
        overflow: hidden;
        margin-bottom: 2rem;
        border: 1px solid var(--border-color);
    }

    /* Tabla Moderna */
    .modern-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        font-size: 0.875rem;
        margin-bottom: 0;
    }

    .modern-table thead {
        background: linear-gradient(90deg, #f8fafc 0%, #e2e8f0 100%);
    }

    .modern-table th {
        padding: 1rem 1.5rem;
        font-weight: 700;
        color: var(--text-primary);
        text-align: left;
        border-bottom: 2px solid var(--border-color);
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        position: sticky;
        top: 0;
        z-index: 10;
        background: var(--bg-light);
    }

    .modern-table th:nth-child(1) { width: 25%; }
    .modern-table th:nth-child(2) { width: 20%; }
    .modern-table th:nth-child(3) { width: 15%; }
    .modern-table th:nth-child(4) { width: 40%; }

    .modern-table td {
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid var(--border-color);
        vertical-align: top;
        color: var(--text-primary);
        font-weight: 500;
    }

    .modern-table tbody tr {
        transition: all 0.2s ease;
        cursor: default;
    }

    .modern-table tbody tr:hover {
        background: linear-gradient(90deg, #f8fafc 0%, #f1f5f9 100%);
        transform: translateX(2px);
        box-shadow: inset 4px 0 0 var(--primary-color);
    }

    /* Fila activa/destacada */
    .config-activo {
        background: linear-gradient(90deg, #f0fff4 0%, #e6fffa 100%) !important;
        border-left: 4px solid var(--success-color);
    }

    .config-activo:hover {
        background: linear-gradient(90deg, #e6fffa 0%, #c6f6d5 100%) !important;
        transform: translateX(2px);
        box-shadow: inset 4px 0 0 var(--success-color);
    }

    /* Tabla sin datos */
    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        color: var(--text-secondary);
    }

    .empty-state-icon {
        font-size: 3rem;
        margin-bottom: 1rem;
        opacity: 0.5;
        color: var(--text-muted);
    }

    /* Badges modernos estandarizados */
    .badge {
        font-weight: 600;
        font-size: 0.75rem;
        padding: 0.5rem 1rem;
        border-radius: var(--radius-sm);
        min-width: 85px;
        text-align: center;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.375rem;
    }

    .badge-activo {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%) !important;
        color: white !important;
        border: 1px solid #047857;
        box-shadow: 0 2px 4px rgba(16, 185, 129, 0.2);
    }

    .badge-historico {
        background: linear-gradient(135deg, #6b7280 0%, #4b5563 100%) !important;
        color: white !important;
        border: 1px solid #374151;
        box-shadow: 0 2px 4px rgba(107, 114, 128, 0.2);
    }

    .badge i {
        font-size: 0.75rem;
        flex-shrink: 0;
    }

    /* Paginación */
    .pagination {
        --bs-pagination-border-color: var(--border-color);
        --bs-pagination-hover-bg: var(--bg-light);
        --bs-pagination-active-bg: var(--primary-color);
        --bs-pagination-active-border-color: var(--primary-color);
    }

    /* Estilos para las notas en el historial */
    .nota-container {
        max-width: 100%;
    }

    .nota-content {
        background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
        border-left: 4px solid #f59e0b;
        border-radius: var(--radius-sm);
        padding: 0.75rem 1rem;
        position: relative;
        box-shadow: var(--shadow-sm);
        transition: all 0.2s ease;
    }

    .nota-content:hover {
        box-shadow: var(--shadow-md);
        transform: translateY(-1px);
    }

    .nota-icon {
        position: absolute;
        top: 0.5rem;
        left: 0.75rem;
        font-size: 0.75rem;
        color: #d97706;
        opacity: 0.6;
    }

    .nota-text {
        display: block;
        margin-left: 1.25rem;
        font-size: 0.875rem;
        line-height: 1.4;
        color: #92400e;
        font-weight: 500;
        word-wrap: break-word;
        word-break: break-word;
    }

    .nota-empty {
        display: flex;
        align-items: center;
        justify-content: center;
        height: 100%;
        padding: 1rem 0;
        color: var(--text-muted);
    }

    /* Estado activo con nota destacada */
    .config-activo .nota-content {
        background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
        border-left-color: var(--success-color);
    }

    .config-activo .nota-icon {
        color: #059669;
    }

    .config-activo .nota-text {
        color: #047857;
    }

    /* Responsive para notas */
    @media (max-width: 768px) {
        .nota-content {
            padding: 0.5rem 0.75rem;
        }
        
        .nota-text {
            margin-left: 1rem;
            font-size: 0.8125rem;
        }
        
        .nota-icon {
            left: 0.5rem;
        }
    }

    /* Responsive */
    @media (max-width: 768px) {
        .planes-workspace {
            padding: 0 1rem;
        }
        
        .planes-header {
            padding: 1.5rem 2rem;
        }
        
        .header-title {
            font-size: 1.875rem;
        }
        
        .card-body {
            padding: 1.5rem;
        }
        
        .table th,
        .table td {
            padding: 0.75rem 1rem;
        }
    }
</style>

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