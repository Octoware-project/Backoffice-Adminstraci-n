@extends('layouts.app')
<link rel="stylesheet" href="{{ asset('css/Facturas/Usuario.css') }}">
@section('content')


{{-- FontAwesome para iconos --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<div class="facturas-workspace">
    <!-- Header moderno -->
    <div class="facturas-header">
        <div class="header-content">
            <div>
                @php
                    $persona = $usuario && $usuario->persona ? $usuario->persona : null;
                    $nombreCompleto = $persona ? trim(($persona->name ?? '') . ' ' . ($persona->apellido ?? '')) : ($usuario->name ?? null);
                @endphp
                <h1 class="header-title">Facturas de {{ $nombreCompleto ? $nombreCompleto : 'Usuario desconocido' }}</h1>
                <p class="header-subtitle">Detalle de pagos y estado de cuenta del residente</p>
            </div>
            <div class="btn-group">
                <a href="{{ route('admin.facturas.usuario', $usuario->email ?? request()->route('email')) }}" class="btn-header">
                    <i class="fas fa-arrow-left"></i> Volver
                </a>
                @if($usuario)
                    <a href="{{ route('usuarios.show', $usuario->id) }}" class="btn-header btn-info-header">
                        <i class="fas fa-user"></i> Ver Datos del Usuario
                    </a>
                @endif
            </div>
        </div>
    </div>

    @php \Carbon\Carbon::setLocale('es'); @endphp

    <!-- Mostrar mensajes de información -->
    @if(session('info'))
        <div class="alert alert-info">
            <i class="fas fa-info-circle"></i>
            {{ session('info') }}
        </div>
    @endif

    @if(session('success'))
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i>
            {{ session('success') }}
        </div>
    @endif

    <!-- Estado de Pagos Card Moderno -->
    <div class="estado-pagos-card {{ $estadoPagos['color'] }}">
        <div class="estado-info">
            <div class="estado-content">
                <div class="estado-title {{ $estadoPagos['color'] }}">
                    {{ $estadoPagos['estado'] }}
                </div>
                <p class="estado-descripcion">{{ $estadoPagos['detalle'] }}</p>
            </div>
            <div class="estado-icon {{ $estadoPagos['color'] }}">
                @if($estadoPagos['color'] == 'success')
                    ✓
                @elseif($estadoPagos['color'] == 'warning')
                    ⚠
                @else
                    ⚠
                @endif
            </div>
        </div>
    </div>

    <!-- Botón Resolver Rechazados -->
    @if($facturasRechazadas > 0)
        <div style="margin-bottom: 2rem; text-align: left;">
            @if(!$mostrarRechazadas)
                <a href="{{ route('admin.facturas.usuario', ['email' => $usuario->email ?? request()->route('email'), 'rechazadas' => '1']) }}" 
                   class="btn-modern btn-resolve-modern">
                    <i class="fas fa-exclamation-triangle"></i> 
                    Resolver Rechazados ({{ $facturasRechazadas }})
                </a>
            @endif
        </div>
    @endif

    <!-- Tabla moderna de facturas -->
    <div class="table-container">
        @if($mostrarRechazadas)
            <div style="padding: 1rem 2rem; background: linear-gradient(90deg, #fef3c7 0%, #fed7aa 100%); border-bottom: 1px solid #f59e0b;">
                <h3 style="margin: 0; color: #92400e; font-size: 1.1rem; font-weight: 600;">
                    <i class="fas fa-exclamation-triangle"></i> 
                    Facturas Rechazadas ({{ $facturas->count() }})
                </h3>
                <p style="margin: 0.5rem 0 0 0; color: #b45309; font-size: 0.875rem;">
                    Estas facturas han sido rechazadas y pueden ser eliminadas del sistema
                </p>
            </div>
        @endif
        <table class="modern-table">
        <thead>
            <tr>
                <th>Residente</th>
                <th>Monto</th>
                <th>Estado de Pago</th>
                <th>Mes</th>
                <th>Fecha de pago</th>
                <th>Comprobante</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse($facturas as $factura)
                <tr>
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
                    <td>{{ $factura->created_at ? $factura->created_at->format('d/m/Y') : '-' }}</td>
                    <td style="text-align:center;">
                        @if($factura->Archivo_Comprobante)
                            <a href="http://localhost:8001/comprobantes/{{ $factura->id }}" target="_blank" title="Ver comprobante">
                                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" viewBox="0 0 16 16" style="color:#007bff;vertical-align:middle;">
                                    <path d="M4.5 9.5A.5.5 0 0 1 5 9h6a.5.5 0 0 1 0 1H5a.5.5 0 0 1-.5-.5zm0-2A.5.5 0 0 1 5 7h6a.5.5 0 0 1 0 1H5a.5.5 0 0 1-.5-.5zm0-2A.5.5 0 0 1 5 5h6a.5.5 0 0 1 0 1H5a.5.5 0 0 1-.5-.5z"/>
                                    <path d="M14 4.5V14a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2h5.5L14 4.5zm-3-.5a.5.5 0 0 1-.5-.5V1H4a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V5h-2a1 1 0 0 1-1-1V4z"/>
                                </svg>
                            </a>
                        @else
                            <span style="color:#bbb;">-</span>
                        @endif
                    </td>
                    <td style="text-align:center;">
                        <div class="action-buttons">
                            @if($mostrarRechazadas)
                                {{-- En modo resolver rechazados, mostrar botón restablecer y eliminar --}}
                                <form action="{{ route('admin.facturas.cancelar', $factura->id) }}" method="POST" style="display:inline-block;">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="from_user" value="1">
                                    <input type="hidden" name="rechazadas" value="1">
                                    <button type="submit" class="btn-modern btn-warning-modern" title="Restablecer a pendiente">
                                        <i class="fas fa-undo"></i> Restablecer
                                    </button>
                                </form>
                                <form id="delete-factura-form-{{ $factura->id }}" action="{{ route('admin.facturas.eliminar', $factura->id) }}" method="POST" style="display:inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <input type="hidden" name="from_user" value="1">
                                    <input type="hidden" name="rechazadas" value="1">
                                    <button type="button" class="btn-modern btn-delete-modern" title="Eliminar factura"
                                            onclick="confirmDeleteFactura({{ $factura->id }}, '{{ $factura->Monto }}', '{{ $factura->created_at->format('d/m/Y') }}')">
                                        <i class="fas fa-trash"></i> Eliminar
                                    </button>
                                </form>
                            @else
                                {{-- Modo normal con todas las acciones --}}
                                @if($factura->Estado_Pago === 'Pendiente')
                                    <form action="{{ route('admin.facturas.aceptar', $factura->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="from_user" value="1">
                                        <button type="submit" class="btn-modern btn-success-modern" title="Aceptar factura">Aceptar</button>
                                    </form>
                                    <form action="{{ route('admin.facturas.rechazar', $factura->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="from_user" value="1">
                                        <button type="submit" class="btn-modern btn-danger-modern" title="Rechazar factura">Rechazar</button>
                                    </form>
                                @elseif($factura->Estado_Pago === 'Aceptado' || $factura->Estado_Pago === 'Rechazado')
                                    <form action="{{ route('admin.facturas.cancelar', $factura->id) }}" method="POST" style="display:inline-block;">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="from_user" value="1">
                                        <button type="submit" class="btn-modern btn-warning-modern" title="Restablecer a pendiente">Restablecer</button>
                                    </form>
                                @else
                                    <span style="color:var(--text-muted);font-size:0.875rem;">Sin acciones</span>
                                @endif
                            @endif
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align: center; padding: 3rem; color: var(--text-muted);">
                        @if($mostrarRechazadas)
                            <div>
                                <i class="fas fa-check-circle" style="font-size: 3rem; color: #10b981; margin-bottom: 1rem;"></i>
                                <h3 style="margin: 0; font-size: 1.25rem;">No hay facturas rechazadas</h3>
                                <p style="margin: 0.5rem 0 0 0; font-size: 0.875rem;">
                                    Todas las facturas de este usuario han sido procesadas correctamente
                                </p>
                            </div>
                        @else
                            <div>
                                <i class="fas fa-receipt" style="font-size: 3rem; opacity: 0.5; margin-bottom: 1rem;"></i>
                                <h3 style="margin: 0; font-size: 1.25rem;">No hay facturas registradas</h3>
                                <p style="margin: 0.5rem 0 0 0; font-size: 0.875rem;">
                                    Este usuario no tiene facturas en el sistema
                                </p>
                            </div>
                        @endif
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

</div>

<script src="{{ asset('js/Facturas/Usuario.js') }}"></script>


@endsection
