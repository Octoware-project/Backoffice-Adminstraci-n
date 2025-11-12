@extends('layouts.app')
<link rel="stylesheet" href="{{ asset('css/Facturas/Index.css') }}">
@section('content')


<div class="facturas-workspace">
    <!-- Header moderno -->
    <div class="facturas-header">
        <div class="header-content">
            <div>
                <h1 class="header-title">Facturas Pendientes</h1>
                <p class="header-subtitle">Facturas en espera de aprobación o rechazo</p>
            </div>
            <div class="btn-group">
                <a href="{{ route('admin.facturas.archivadas') }}" class="btn-modern btn-primary-modern">
                    <i class="fas fa-archive"></i> Ver Archivadas
                </a>
            </div>
        </div>
    </div>

    <!-- Filtros Desplegables -->
    <div class="filters-container">
        <div class="filters-header" onclick="toggleFilters()">
            <div class="filters-toggle">
                <span><i class="fas fa-filter"></i> Filtros de búsqueda</span>
                <i class="fas fa-chevron-down" id="filters-chevron"></i>
            </div>
        </div>
        <div class="filters-content" id="filters-content">
            <form method="GET" action="{{ route('admin.facturas.index') }}">
                <div class="filters-grid">
                    <div class="filter-group">
                        <label class="filter-label">Año</label>
                        <input type="number" name="año" class="filter-input" 
                               value="{{ request('año') }}" placeholder="Año" min="2020" max="{{ date('Y') + 1 }}">
                    </div>
                    <div class="filter-group">
                        <label class="filter-label">Mes</label>
                        <select name="mes" class="filter-select">
                            <option value="">Todos los meses</option>
                            <option value="1" {{ request('mes') == '1' ? 'selected' : '' }}>Enero</option>
                            <option value="2" {{ request('mes') == '2' ? 'selected' : '' }}>Febrero</option>
                            <option value="3" {{ request('mes') == '3' ? 'selected' : '' }}>Marzo</option>
                            <option value="4" {{ request('mes') == '4' ? 'selected' : '' }}>Abril</option>
                            <option value="5" {{ request('mes') == '5' ? 'selected' : '' }}>Mayo</option>
                            <option value="6" {{ request('mes') == '6' ? 'selected' : '' }}>Junio</option>
                            <option value="7" {{ request('mes') == '7' ? 'selected' : '' }}>Julio</option>
                            <option value="8" {{ request('mes') == '8' ? 'selected' : '' }}>Agosto</option>
                            <option value="9" {{ request('mes') == '9' ? 'selected' : '' }}>Septiembre</option>
                            <option value="10" {{ request('mes') == '10' ? 'selected' : '' }}>Octubre</option>
                            <option value="11" {{ request('mes') == '11' ? 'selected' : '' }}>Noviembre</option>
                            <option value="12" {{ request('mes') == '12' ? 'selected' : '' }}>Diciembre</option>
                        </select>
                    </div>

                </div>
                <div class="filters-actions">
                    <a href="{{ route('admin.facturas.index') }}" class="btn-modern btn-sm btn-outline-secondary">
                        <i class="fas fa-times"></i> Limpiar
                    </a>
                    <button type="submit" class="btn-modern btn-sm btn-outline-primary">
                        <i class="fas fa-search"></i> Aplicar Filtros
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabla moderna de facturas -->
    <div class="table-container">
        @php \Carbon\Carbon::setLocale('es'); @endphp
        <table class="modern-table">
        <thead>
            <tr>
                <th>Residente</th>
                <th>Monto</th>
                <th>Estado de Pago</th>
                <th>Mes</th>
                <th>Comprobante</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse($facturas as $factura)
                <tr class="factura-row" data-email="{{ $factura->email }}">
                    <td>
                        @php
                            $persona = optional(optional($factura->user)->persona);
                            $nombreCompleto = trim(($persona->name ?? '') . ' ' . ($persona->apellido ?? ''));
                        @endphp
                        {{ $nombreCompleto !== '' ? $nombreCompleto : 'Sin residente' }}
                    </td>
                    <td>{{ $factura->Monto }}</td>
                    <td>
                        @php
                            $estado = strtolower($factura->Estado_Pago);
                        @endphp
                        <span class="status-badge {{ $estado }}">
                            {{ ucfirst($factura->Estado_Pago) }}
                        </span>
                    </td>
                    <td>
                        {{ $factura->fecha_pago ? \Carbon\Carbon::parse($factura->fecha_pago)->translatedFormat('F Y') : '-' }}
                    </td>
                    <!-- Botones de comprobante y acción intercambiados -->
                    <td style="text-align:center;">
                        @if($factura->Archivo_Comprobante)
                            <a href="#" onclick="abrirComprobanteApi({{ $factura->id }}); return false;" title="Ver comprobante">
                                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" viewBox="0 0 16 16" style="color:#007bff;vertical-align:middle;">
                                    <path d="M4.5 9.5A.5.5 0 0 1 5 9h6a.5.5 0 0 1 0 1H5a.5.5 0 0 1-.5-.5zm0-2A.5.5 0 0 1 5 7h6a.5.5 0 0 1 0 1H5a.5.5 0 0 1-.5-.5zm0-2A.5.5 0 0 1 5 5h6a.5.5 0 0 1 0 1H5a.5.5 0 0 1-.5-.5z"/>
                                    <path d="M14 4.5V14a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V2a2 2 0 0 1-2-2h5.5L14 4.5zm-3-.5a.5.5 0 0 1-.5-.5V1H4a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V5h-2a1 1 0 0 1-1-1V4z"/>
                                </svg>
                            </a>
                        @else
                            <span style="color:#bbb;">-</span>
                        @endif
                    </td>
                    <td onclick="event.stopPropagation();" style="text-align:center;">
                        <div class="actions-group">
                            <a href="{{ route('admin.facturas.usuario', $factura->email) }}" class="action-btn btn-view">
                                <i class="fas fa-eye"></i>
                                Ver
                            </a>
                            @if($factura->Estado_Pago === 'Pendiente')
                                <form action="{{ route('admin.facturas.aceptar', $factura->id) }}" method="POST" style="display:inline-block;">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn-modern btn-success-modern">Aceptar</button>
                                </form>
                                <form action="{{ route('admin.facturas.rechazar', $factura->id) }}" method="POST" style="display:inline-block;">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn-modern btn-danger-modern">Rechazar</button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="empty-state">
                        <div class="empty-state-icon">💳</div>
                        <div class="empty-state-text">No hay facturas pendientes</div>
                        <div class="empty-state-subtext">
                            @if(request()->hasAny(['año', 'mes']))
                                No se encontraron facturas con los filtros aplicados.
                            @else
                                Las facturas pendientes aparecerán aquí cuando sean creadas.
                            @endif
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
</div>
<script src="{{ asset('js/Facturas/Index.js') }}"></script>

{{-- FontAwesome para iconos --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

@endsection
