@extends('layouts.app')

@section('content')
<style>
    .factura-table-container {
        max-width: 900px;
        margin: 40px auto;
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 2px 16px 0 rgba(0,0,0,0.07);
        padding: 2rem 1rem;
    }
    .factura-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 1rem;
        background: transparent;
    }
    .factura-table th, .factura-table td {
        padding: 0.9rem 1rem;
        text-align: left;
    }
    .factura-table th {
        background: #f6f8fa;
        color: #222;
        font-weight: 600;
        border-bottom: 2px solid #eaeaea;
    }
    .factura-table tr {
        transition: background 0.2s;
    }
    .factura-table tbody tr:hover {
        background: #f0f4f8;
    }
    .factura-table td {
        border-bottom: 1px solid #f0f0f0;
        color: #444;
    }
    .estado-pago {
        display: inline-block;
        padding: 0.35em 1em;
        border-radius: 1em;
        font-weight: 600;
        font-size: 0.98em;
        letter-spacing: 0.02em;
        background: #f6f6f6;
        color: #888;
        border: none;
        min-width: 90px;
        text-align: center;
    }
    .estado-pago.pendiente {
        background: #ffeecf;
        color: #b88a2a;
    }
    .estado-pago.aceptado {
        background: #d6f5e7;
        color: #2e8b57;
    }
    .estado-pago.rechazado {
        background: #ffe0e0;
        color: #c0392b;
    }
    @media (max-width: 600px) {
        .factura-table-container {
            padding: 0.5rem 0.2rem;
        }
        .factura-table th, .factura-table td {
            padding: 0.5rem 0.3rem;
            font-size: 0.95rem;
        }
        h1 {
            font-size: 1.2rem;
        }
    }
    .btn {
        border: none;
        padding: 0.4rem 0.8rem;
        border-radius: 4px;
        font-size: 0.85rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s ease;
        text-decoration: none;
        display: inline-block;
    }
    .btn-success {
        background-color: #28a745;
        color: white;
    }
    .btn-success:hover {
        background-color: #218838;
    }
    .btn-danger {
        background-color: #dc3545;
        color: white;
    }
    .btn-danger:hover {
        background-color: #c82333;
    }
    .btn-warning {
        background-color: #ffc107;
        color: #212529;
    }
    .btn-warning:hover {
        background-color: #e0a800;
    }
    .btn-secondary {
        background-color: #6c757d;
        color: white;
    }
    .btn-secondary:hover {
        background-color: #5a6268;
    }
    .btn-sm {
        padding: 0.3rem 0.6rem;
        font-size: 0.8rem;
    }
    .action-buttons {
        display: flex;
        justify-content: center;
        gap: 5px;
        flex-wrap: wrap;
    }
    .action-buttons form {
        display: inline-block;
        margin: 0;
    }
    @media (max-width: 480px) {
        .action-buttons {
            gap: 3px;
        }
        .btn-sm {
            padding: 0.25rem 0.5rem;
            font-size: 0.75rem;
        }
    }
    .estado-pagos-container {
        background: #f8f9fa;
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 2rem;
        border-left: 4px solid #6c757d;
    }
    .estado-pagos-container.success {
        border-left-color: #28a745;
        background: #f8fff8;
    }
    .estado-pagos-container.warning {
        border-left-color: #ffc107;
        background: #fffef8;
    }
    .estado-pagos-container.danger {
        border-left-color: #dc3545;
        background: #fff8f8;
    }
    .estado-badge {
        display: inline-block;
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-weight: 600;
        font-size: 1rem;
        margin-bottom: 0.5rem;
    }
    .estado-badge.success {
        background: #d4edda;
        color: #155724;
    }
    .estado-badge.warning {
        background: #fff3cd;
        color: #856404;
    }
    .estado-badge.danger {
        background: #f8d7da;
        color: #721c24;
    }
    .estado-detalle {
        color: #6c757d;
        font-size: 0.9rem;
        margin: 0;
    }
    @media (max-width: 768px) {
        .estado-pagos-container {
            padding: 1rem;
        }
        .estado-pagos-container > div {
            flex-direction: column;
            text-align: center;
            gap: 0.5rem !important;
        }
        .estado-badge {
            font-size: 0.9rem;
        }
    }
</style>
<div class="factura-table-container">
    @php \Carbon\Carbon::setLocale('es'); @endphp
    <h1 style="text-align:center; font-weight:700; margin-bottom:1.5rem;">
        @php
            $persona = $usuario && $usuario->persona ? $usuario->persona : null;
            $nombreCompleto = $persona ? trim(($persona->name ?? '') . ' ' . ($persona->apellido ?? '')) : ($usuario->name ?? null);
        @endphp
        Facturas de {{ $nombreCompleto ? $nombreCompleto : 'Usuario desconocido' }}
    </h1>
    
    <!-- Estado de Pagos -->
    <div class="estado-pagos-container {{ $estadoPagos['color'] }}">
        <div style="display: flex; align-items: center; gap: 1rem;">
            <div>
                <span class="estado-badge {{ $estadoPagos['color'] }}">
                    {{ $estadoPagos['estado'] }}
                </span>
                <p class="estado-detalle">{{ $estadoPagos['detalle'] }}</p>
            </div>
            @if($estadoPagos['color'] == 'success')
                <div style="color: #28a745; font-size: 2rem;">✓</div>
            @elseif($estadoPagos['color'] == 'warning')
                <div style="color: #ffc107; font-size: 2rem;">⚠</div>
            @else
                <div style="color: #dc3545; font-size: 2rem;">⚠</div>
            @endif
        </div>
    </div>
    
    <table class="factura-table">
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
            @foreach($facturas as $factura)
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
                        <span class="estado-pago {{ $estado }}">
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
                        @if($factura->Estado_Pago === 'Pendiente')
                            <div class="action-buttons">
                                <form action="{{ route('admin.facturas.aceptar', $factura->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="from_user" value="1">
                                    <button type="submit" class="btn btn-success btn-sm" title="Aceptar factura">Aceptar</button>
                                </form>
                                <form action="{{ route('admin.facturas.rechazar', $factura->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="from_user" value="1">
                                    <button type="submit" class="btn btn-danger btn-sm" title="Rechazar factura">Rechazar</button>
                                </form>
                            </div>
                        @elseif($factura->Estado_Pago === 'Aceptado' || $factura->Estado_Pago === 'Rechazado')
                            <form action="{{ route('admin.facturas.cancelar', $factura->id) }}" method="POST" style="display:inline-block;">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="from_user" value="1">
                                <button type="submit" class="btn btn-warning btn-sm" title="Restablecer a pendiente">Restablecer</button>
                            </form>
                        @else
                            <span style="color:#888;font-size:0.9em;">Sin acciones</span>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
<div style="margin-top:2.2rem; text-align:left;">
    <a href="{{ route('admin.facturas.index') }}" class="btn btn-secondary">Volver</a>
</div>
</div>
@endsection
