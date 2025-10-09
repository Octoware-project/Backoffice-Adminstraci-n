@extends('layouts.app')

@section('content')
<style>
    /* Variables CSS del sistema planTrabajo */
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

    /* Background moderno */
    body {
        background-color: #d2d2f1ff !important;
        overflow-x: hidden;
        scrollbar-width: none; /* Firefox */
        -ms-overflow-style: none; /* Internet Explorer and Edge */
    }

    /* Hide scrollbar completely */
    body::-webkit-scrollbar {
        display: none; /* Chrome, Safari, and Opera */
    }

    html {
        scrollbar-width: none; /* Firefox */
        -ms-overflow-style: none; /* Internet Explorer and Edge */
    }

    html::-webkit-scrollbar {
        display: none; /* Chrome, Safari, and Opera */
    }

    /* Container principal moderno */
    .facturas-workspace {
        max-width: 1400px;
        margin: 0 auto;
        padding: 0 1.5rem;
        min-height: 100vh;
        background-color: #d2d2f1ff;
    }

    /* Header moderno estilo planTrabajo */
    .facturas-header {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-light) 100%);
        border-radius: var(--radius);
        padding: 2rem 2.5rem;
        margin-bottom: 2rem;
        box-shadow: var(--shadow-lg);
        color: white;
        position: relative;
        overflow: hidden;
    }

    .facturas-header::before {
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

    /* Container de tabla moderno */
    .table-container {
        background: var(--bg-primary);
        border-radius: var(--radius);
        box-shadow: var(--shadow-md);
        overflow: hidden;
        margin-bottom: 2rem;
    }

    /* Tabla moderna estilo planTrabajo */
    .modern-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        font-size: 0.875rem;
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

    .modern-table td {
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid var(--border-color);
        vertical-align: middle;
        color: var(--text-primary);
        font-weight: 500;
    }

    .modern-table tbody tr {
        transition: all 0.2s ease;
    }

    .modern-table tbody tr:hover {
        background: linear-gradient(90deg, #f8fafc 0%, #f1f5f9 100%);
        transform: translateX(4px);
        box-shadow: inset 4px 0 0 var(--primary-color);
    }

    /* Estados modernos */
    .status-badge {
        display: inline-flex;
        align-items: center;
        padding: 0.25rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 600;
        letter-spacing: 0.025em;
        min-width: 90px;
        justify-content: center;
    }

    .status-badge.pendiente {
        background: #fff3cd;
        color: #856404;
    }

    .status-badge.aceptado {
        background: #d1fae5;
        color: #065f46;
    }

    .status-badge.rechazado {
        background: #fee2e2;
        color: #dc2626;
    }

    /* Botones modernos estilo planTrabajo */
    .btn-modern {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        border-radius: var(--radius-sm);
        font-weight: 600;
        font-size: 0.75rem;
        text-decoration: none;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        border: none;
        cursor: pointer;
        position: relative;
        overflow: hidden;
        text-transform: uppercase;
        letter-spacing: 0.025em;
    }

    .btn-success-modern {
        background: #f0fdf4;
        color: #15803d;
        border: 1px solid #bbf7d0;
    }

    .btn-success-modern:hover {
        background: #dcfce7;
        color: #166534;
        transform: translateY(-1px);
        text-decoration: none;
    }

    .btn-danger-modern {
        background: #fef2f2;
        color: #dc2626;
        border: 1px solid #fecaca;
    }

    .btn-danger-modern:hover {
        background: #fee2e2;
        color: #b91c1c;
        transform: translateY(-1px);
    }

    .btn-warning-modern {
        background: #fffbeb;
        color: #d97706;
        border: 1px solid #fcd34d;
    }

    .btn-warning-modern:hover {
        background: #fef3c7;
        color: #b45309;
        transform: translateY(-1px);
        text-decoration: none;
    }

    .btn-secondary-modern {
        background: var(--bg-primary);
        color: var(--text-primary);
        border: 2px solid var(--border-color);
    }

    .btn-secondary-modern:hover {
        background: var(--bg-light);
        border-color: var(--primary-color);
        transform: translateY(-1px);
        box-shadow: var(--shadow-md);
        color: var(--text-primary);
        text-decoration: none;
    }

    .btn-delete-modern {
        background: #fdf2f8;
        color: #be185d;
        border: 1px solid #f9a8d4;
    }

    .btn-delete-modern:hover {
        background: #fce7f3;
        color: #a21caf;
        transform: translateY(-1px);
        text-decoration: none;
    }

    .btn-resolve-modern {
        background: #fef2f2;
        color: #dc2626;
        border: 1px solid #fecaca;
    }

    .btn-resolve-modern:hover {
        background: #fee2e2;
        color: #b91c1c;
        transform: translateY(-1px);
        text-decoration: none;
    }

    /* Botones en el header */
    .btn-group {
        display: flex;
        gap: 1rem;
        align-items: center;
    }

    .btn-header {
        background: rgba(255, 255, 255, 0.15);
        color: white;
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        display: inline-flex;
        align-items: center;
        gap: 0.75rem;
        padding: 1rem 2rem;
        border-radius: var(--radius-sm);
        font-weight: 600;
        font-size: 1rem;
        text-decoration: none;
        transition: all 0.2s ease;
    }

    .btn-header:hover {
        background: rgba(255, 255, 255, 0.25);
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        color: white;
        text-decoration: none;
    }

    .btn-info-header {
        background: rgba(52, 152, 219, 0.2);
        border: 1px solid rgba(52, 152, 219, 0.4);
    }

    .btn-info-header:hover {
        background: rgba(52, 152, 219, 0.3);
        border: 1px solid rgba(52, 152, 219, 0.6);
    }
    /* Card de estado de pagos moderno */
    .estado-pagos-card {
        background: var(--bg-primary);
        border-radius: var(--radius);
        box-shadow: var(--shadow-md);
        border: 1px solid var(--border-color);
        margin-bottom: 2rem;
        padding: 2rem;
        position: relative;
        overflow: hidden;
    }

    .estado-pagos-card.success {
        border-left: 4px solid var(--success-color);
        background: linear-gradient(90deg, #f0fff4 0%, #e6fffa 100%);
    }

    .estado-pagos-card.warning {
        border-left: 4px solid #f59e0b;
        background: linear-gradient(90deg, #fffbeb 0%, #fef3c7 100%);
    }

    .estado-pagos-card.danger {
        border-left: 4px solid var(--danger-color);
        background: linear-gradient(90div, #fef2f2 0%, #fee2e2 100%);
    }

    .estado-info {
        display: flex;
        align-items: center;
        gap: 1.5rem;
    }

    .estado-content {
        flex: 1;
    }

    .estado-title {
        display: inline-flex;
        align-items: center;
        padding: 0.5rem 1rem;
        border-radius: 9999px;
        font-size: 1rem;
        font-weight: 600;
        letter-spacing: 0.025em;
        margin-bottom: 0.75rem;
    }

    .estado-title.success {
        background: #d1fae5;
        color: #065f46;
    }

    .estado-title.warning {
        background: #fef3c7;
        color: #92400e;
    }

    .estado-title.danger {
        background: #fee2e2;
        color: #dc2626;
    }

    .estado-descripcion {
        color: var(--text-secondary);
        font-size: 0.875rem;
        margin: 0;
        line-height: 1.5;
    }

    .estado-icon {
        font-size: 2.5rem;
        opacity: 0.8;
    }

    .estado-icon.success { color: var(--success-color); }
    .estado-icon.warning { color: #f59e0b; }
    .estado-icon.danger { color: var(--danger-color); }

    /* Action buttons container */
    .action-buttons {
        display: flex;
        justify-content: center;
        gap: 0.5rem;
        flex-wrap: wrap;
    }

    .action-buttons form {
        display: inline-block;
        margin: 0;
    }



    /* Responsive */
    @media (max-width: 768px) {
        .facturas-workspace {
            padding: 0 1rem;
        }
        
        .facturas-header {
            padding: 1.5rem 2rem;
        }
        
        .header-title {
            font-size: 1.875rem;
        }
        
        .modern-table th,
        .modern-table td {
            padding: 0.75rem 1rem;
        }
        
        .btn-modern {
            padding: 0.375rem 0.75rem;
            font-size: 0.7rem;
        }

        .estado-info {
            flex-direction: column;
            text-align: center;
            gap: 1rem;
        }

        .estado-icon {
            font-size: 2rem;
        }
    }

    @media (max-width: 1024px) {
        .header-content {
            flex-direction: column;
            gap: 1.5rem;
            text-align: center;
        }
        
        .action-buttons {
            gap: 0.25rem;
        }
    }

    @media (max-width: 480px) {
        .action-buttons {
            flex-direction: column;
            align-items: center;
        }
        
        .btn-modern {
            width: 100%;
            max-width: 200px;
        }

        .btn-group {
            flex-direction: column;
            gap: 0.5rem;
            align-items: stretch;
        }

        .btn-header {
            padding: 0.75rem 1.5rem;
            font-size: 0.875rem;
        }
    }

    /* Alertas */
    .alert {
        border-radius: var(--radius-sm);
        padding: 1rem 1.5rem;
        margin-bottom: 2rem;
        box-shadow: var(--shadow-sm);
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .alert-success {
        background: #d1fae5;
        border: 1px solid #6ee7b7;
        color: #065f46;
    }

    .alert-info {
        background: #dbeafe;
        border: 1px solid #93c5fd;
        color: #1e40af;
    }

    .alert i {
        font-size: 1.25rem;
    }
</style>

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

<script>
// Función para confirmar eliminación de factura
function confirmDeleteFactura(facturaId, monto, fecha) {
    ModalConfirmation.create({
        title: 'Confirmar Eliminación de Factura',
        message: '¿Está seguro que desea eliminar la factura:',
        detail: `"$${monto} - ${fecha}"`,
        warning: 'Esta acción no se puede deshacer. Se perderán todos los datos de la factura.',
        confirmText: 'Eliminar Factura',
        cancelText: 'Cancelar',
        iconClass: 'fas fa-file-invoice-dollar',
        iconColor: '#dc2626',
        confirmColor: '#dc2626',
        onConfirm: function() {
            document.getElementById(`delete-factura-form-${facturaId}`).submit();
        }
    });
}
</script>

@endsection
