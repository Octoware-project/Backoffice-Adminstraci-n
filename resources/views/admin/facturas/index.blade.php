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
    .btn-sm {
        padding: 0.3rem 0.6rem;
        font-size: 0.8rem;
    }
</style>
<div class="factura-table-container">
    @php \Carbon\Carbon::setLocale('es'); @endphp
    <h1 style="text-align:center; font-weight:700; margin-bottom:1.5rem;">Listado de Facturas</h1>
    <form method="GET" action="{{ route('admin.facturas.index') }}" class="filtro-estado-form">
        <label for="estado">Estado:</label>
        <select name="estado" id="estado" onchange="this.form.submit()">
            <option value="">Todos</option>
            <option value="Pendiente" @if(isset($estado) && $estado==='Pendiente') selected @endif>Pendiente</option>
            <option value="Aceptado" @if(isset($estado) && $estado==='Aceptado') selected @endif>Aceptado</option>
            <option value="Rechazado" @if(isset($estado) && $estado==='Rechazado') selected @endif>Rechazado</option>
        </select>
    </form>
    <style>
        .filtro-estado-form {
            display: flex;
            align-items: center;
            gap: 0.7rem;
            margin-bottom: 1.5rem;
            margin-left: 0;
            margin-right: auto;
            max-width: 260px;
            background: #f8f9fa;
            border-radius: 8px;
            padding: 0.5rem 1rem;
            box-shadow: 0 1px 6px 0 rgba(0,0,0,0.03);
        }
        .filtro-estado-form label {
            font-weight: 500;
            color: #444;
            font-size: 1rem;
        }
        .filtro-estado-form select {
            border: none;
            background: #fff;
            border-radius: 6px;
            padding: 0.35em 1.2em 0.35em 0.7em;
            font-size: 1rem;
            color: #333;
            box-shadow: 0 1px 3px 0 rgba(0,0,0,0.04);
            outline: none;
            transition: box-shadow 0.2s;
        }
        .filtro-estado-form select:focus {
            box-shadow: 0 0 0 2px #b5e0d3;
        }
    </style>
    <table class="factura-table">
        <thead>
            <tr>
                <th>Residente</th>
                <th>Monto</th>
                <th>Estado de Pago</th>
                <th>Mes</th>
                <th>Acciones</th>
                <th>Comprobante</th>
            </tr>
        </thead>
        <tbody>
            @foreach($facturas as $factura)
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
                        <span class="estado-pago {{ $estado }}">
                            {{ ucfirst($factura->Estado_Pago) }}
                        </span>
                    </td>
                    <td>
                        {{ $factura->fecha_pago ? \Carbon\Carbon::parse($factura->fecha_pago)->translatedFormat('F Y') : '-' }}
                    </td>
                    <!-- Botones de acción y comprobante solo una vez -->
                    <td style="text-align:center;">
                        @if($factura->Archivo_Comprobante)
                            <a href="#" onclick="abrirComprobanteApi({{ $factura->id }}); return false;" title="Ver comprobante">
                                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" viewBox="0 0 16 16" style="color:#007bff;vertical-align:middle;">
                                    <path d="M4.5 9.5A.5.5 0 0 1 5 9h6a.5.5 0 0 1 0 1H5a.5.5 0 0 1-.5-.5zm0-2A.5.5 0 0 1 5 7h6a.5.5 0 0 1 0 1H5a.5.5 0 0 1-.5-.5zm0-2A.5.5 0 0 1 5 5h6a.5.5 0 0 1 0 1H5a.5.5 0 0 1-.5-.5z"/>
                                    <path d="M14 4.5V14a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2h5.5L14 4.5zm-3-.5a.5.5 0 0 1-.5-.5V1H4a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V5h-2a1 1 0 0 1-1-1V4z"/>
                                </svg>
                            </a>
                        @else
                            <span style="color:#bbb;">-</span>
                        @endif
                    </td>
                    <td>
                        @if($factura->Estado_Pago === 'Pendiente')
                            <form action="{{ route('admin.facturas.aceptar', $factura->id) }}" method="POST" style="display:inline-block;">
                                @csrf
                                @method('PUT')
                                <button type="submit" class="btn btn-success btn-sm">Aceptar</button>
                            </form>
                            <form action="{{ route('admin.facturas.rechazar', $factura->id) }}" method="POST" style="display:inline-block; margin-left:5px;">
                                @csrf
                                @method('PUT')
                                <button type="submit" class="btn btn-danger btn-sm">Rechazar</button>
                            </form>
                        @else
                            <form action="{{ route('admin.facturas.cancelar', $factura->id) }}" method="POST" style="display:inline-block;">
                                @csrf
                                @method('PUT')
                                <button type="submit" class="btn btn-warning btn-sm">Restablecer</button>
                            </form>
                        @endif
                    </td>
                </tr>
            @endforeach
</div>
<script>
    // URL base correcta de la API Cooperativa
    const API_COOPERATIVA_BASE = 'http://localhost:8001/api';
    const API_COOPERATIVA_WEB = 'http://localhost:8001';

    function abrirComprobanteApi(facturaId) {
        // Usar la ruta web directa para mejor compatibilidad
        const url = `${API_COOPERATIVA_WEB}/comprobantes/${facturaId}`;
        window.open(url, '_blank');
    }
    document.querySelectorAll('.factura-row').forEach(function(row) {
        let clickCount = 0;
        let timer = null;
        row.addEventListener('click', function() {
            clickCount++;
            if (clickCount === 2) {
                clearTimeout(timer);
                const email = row.getAttribute('data-email');
                if(email) {
                    let base = "{{ url('/facturas/usuario') }}";
                    if (base.endsWith('/')) base = base.slice(0, -1);
                    window.location.href = base + '/' + encodeURIComponent(email);
                }
                clickCount = 0;
            } else {
                timer = setTimeout(function() {
                    clickCount = 0;
                }, 350);
            }
        });
    });
</script>
        </tbody>
    </table>
</div>
@endsection
