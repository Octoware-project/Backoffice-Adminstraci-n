@extends('layouts.app')

@section('content')
<style>
    /* Background de la página */
    body {
        background-color: #d2d2f1ff !important;
    }

    /* Variables CSS */
    :root {
        --primary-color: #667eea;
        --primary-light: #764ba2;
        --secondary-color: #f093fb;
        --success-color: #4ecdc4;
        --warning-color: #ffb74d;
        --danger-color: #fc466b;
        --info-color: #54a0ff;
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
        --radius-lg: 16px;
    }

    /* Container Principal */
    .detail-workspace {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 1.5rem;
        min-height: 100vh;
        background-color: #d2d2f1ff;
    }

    /* Header del Detalle */
    .detail-header {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-light) 100%);
        border-radius: var(--radius);
        padding: 2rem 2.5rem;
        margin-bottom: 2rem;
        box-shadow: var(--shadow-lg);
        color: white;
        position: relative;
        overflow: hidden;
    }

    .detail-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="white" opacity="0.1"/><circle cx="75" cy="75" r="1" fill="white" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>') repeat;
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
        font-size: 2rem;
        font-weight: 700;
        letter-spacing: -0.025em;
    }

    .header-subtitle {
        margin: 0.5rem 0 0 0;
        font-size: 1rem;
        opacity: 0.9;
        font-weight: 400;
    }

    .header-actions {
        display: flex;
        gap: 1rem;
    }

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

    /* Cards de Información */
    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .info-card {
        background: var(--bg-primary);
        border-radius: var(--radius);
        padding: 1.5rem;
        box-shadow: var(--shadow-md);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        border: 1px solid var(--border-color);
    }

    .info-card:hover {
        transform: translateY(-1px);
        box-shadow: var(--shadow-lg);
    }

    .card-icon {
        width: 3rem;
        height: 3rem;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1rem;
        font-size: 1.25rem;
        color: white;
    }

    .card-title {
        font-size: 0.75rem;
        color: var(--text-secondary);
        text-transform: uppercase;
        letter-spacing: 0.05em;
        font-weight: 600;
        margin-bottom: 0.25rem;
    }

    .card-value {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--text-primary);
        margin-bottom: 0.5rem;
    }

    .card-subtitle {
        font-size: 0.875rem;
        color: var(--text-muted);
    }

    /* Tabla Moderna */
    .records-container {
        background: var(--bg-primary);
        border-radius: var(--radius);
        box-shadow: var(--shadow-md);
        overflow: hidden;
        margin-bottom: 2rem;
    }

    .records-header {
        background: linear-gradient(90deg, var(--bg-light) 0%, var(--bg-secondary) 100%);
        padding: 1.5rem 2rem;
        border-bottom: 1px solid var(--border-color);
    }

    .records-title {
        margin: 0;
        font-size: 1.25rem;
        font-weight: 600;
        color: var(--text-primary);
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .modern-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }

    .modern-table th {
        padding: 1rem 1.5rem;
        background: var(--bg-light);
        color: var(--text-primary);
        font-weight: 700;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        border-bottom: 2px solid var(--border-color);
        text-align: left;
    }

    .modern-table td {
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid var(--border-color);
        color: var(--text-primary);
        vertical-align: middle;
        font-weight: 500;
    }

    .modern-table tbody tr {
        transition: all 0.2s ease;
        cursor: pointer;
    }

    .modern-table tbody tr:hover {
        background: linear-gradient(90deg, #f8fafc 0%, #f1f5f9 100%);
        transform: translateX(4px);
        box-shadow: inset 4px 0 0 var(--primary-color);
    }

    /* Estilo para badges clickeables */
    .badge-modern:hover {
        transform: scale(1.05);
        box-shadow: var(--shadow-sm);
        transition: all 0.2s ease;
    }

    /* Modal Styles */
    .modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        z-index: 1000;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .modal-content {
        background: var(--bg-primary);
        border-radius: var(--radius);
        width: 90%;
        max-width: 600px;
        max-height: 80vh;
        overflow: hidden;
        box-shadow: var(--shadow-lg);
        animation: modalSlideIn 0.3s ease;
    }

    @keyframes modalSlideIn {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .modal-header {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-light) 100%);
        color: white;
        padding: 1.5rem 2rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .modal-header h3 {
        margin: 0;
        font-size: 1.25rem;
        font-weight: 600;
    }

    .modal-close {
        background: none;
        border: none;
        color: white;
        font-size: 1.5rem;
        cursor: pointer;
        padding: 0;
        width: 30px;
        height: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        transition: background 0.2s ease;
    }

    .modal-close:hover {
        background: rgba(255, 255, 255, 0.2);
    }

    .modal-body {
        padding: 2rem;
        max-height: 60vh;
        overflow-y: auto;
    }

    .loading-spinner {
        text-align: center;
        color: var(--text-muted);
        padding: 2rem;
    }

    .persona-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem;
        border: 1px solid var(--border-color);
        border-radius: var(--radius-sm);
        margin-bottom: 1rem;
        transition: all 0.2s ease;
    }

    .persona-item:hover {
        border-color: var(--primary-color);
        background: var(--bg-light);
    }

    .persona-info {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .persona-avatar {
        width: 2.5rem;
        height: 2.5rem;
        border-radius: 50%;
        background: var(--primary-color);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 600;
    }

    .persona-details h4 {
        margin: 0;
        font-size: 1rem;
        color: var(--text-primary);
    }

    .persona-details p {
        margin: 0.25rem 0 0 0;
        font-size: 0.875rem;
        color: var(--text-muted);
    }

    .btn-asignar {
        background: var(--success-color);
        color: white;
        border: none;
        padding: 0.5rem 1rem;
        border-radius: var(--radius-sm);
        cursor: pointer;
        font-weight: 600;
        transition: all 0.2s ease;
    }

    .btn-asignar:hover {
        background: #3ba89a;
        transform: translateY(-1px);
    }

    .btn-success {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
        border: none;
    }

    .btn-success:hover {
        background: linear-gradient(135deg, #059669 0%, #047857 100%);
        color: white;
        text-decoration: none;
    }

    /* Badges exactos de planTrabajos */
    .badge-modern {
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        padding: 0.25rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 600;
        letter-spacing: 0.025em;
    }

    .badge-primary {
        background: #dbeafe;
        color: #1e40af;
    }

    .badge-warning {
        background: #fef3c7;
        color: #d97706;
    }

    .badge-success {
        background: #d1fae5;
        color: #065f46;
    }

    .badge-info {
        background: #e0f2fe;
        color: #0369a1;
    }
    }

    .badge-success {
        background: #d1fae5;
        color: #065f46;
    }

    .badge-info {
        background: #e0f2fe;
        color: #0369a1;
    }

    /* Botones Modernos */
    .btn-modern {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem 1.5rem;
        border-radius: var(--radius-sm);
        font-weight: 600;
        font-size: 0.875rem;
        text-decoration: none;
        transition: all 0.2s ease;
        border: none;
        cursor: pointer;
    }

    .btn-warning {
        background: linear-gradient(135deg, var(--warning-color) 0%, #ff8f00 100%);
        color: white;
    }

    .btn-warning:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(255, 183, 77, 0.4);
        color: white;
        text-decoration: none;
    }

    /* Botones adicionales */
    .btn {
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        padding: 0.5rem 1rem;
        border-radius: var(--radius-sm);
        font-weight: 600;
        font-size: 0.75rem;
        text-decoration: none;
        transition: all 0.2s ease;
        border: 1px solid;
        cursor: pointer;
    }

    .btn-sm {
        padding: 0.375rem 0.75rem;
        font-size: 0.7rem;
    }

    .btn-outline-danger {
        background: transparent;
        color: var(--danger-color);
        border-color: var(--danger-color);
    }

    .btn-outline-danger:hover {
        background: var(--danger-color);
        color: white;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(252, 70, 107, 0.4);
    }

    /* Estados exactos de planTrabajos */
    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        padding: 0.25rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 600;
        letter-spacing: 0.025em;
    }

    .status-en-construccion {
        background: #fef3c7;
        color: #d97706;
    }

    .status-finalizado {
        background: #d1fae5;
        color: #065f46;
    }

    /* Empty State exacto de planTrabajos */
    .empty-state {
        text-align: center;
        padding: 3rem 2rem;
        color: var(--text-muted);
    }

    .empty-state-icon {
        width: 4rem;
        height: 4rem;
        border-radius: 50%;
        background: linear-gradient(135deg, #f1f5f9, #e2e8f0);
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1rem;
        color: var(--text-muted);
        font-size: 1.5rem;
    }

    .empty-state h4 {
        margin: 0 0 0.5rem;
        font-weight: 600;
        font-size: 1.125rem;
    }

    .empty-state p {
        margin: 0;
        font-size: 0.875rem;
        opacity: 0.8;
    }

    .empty-state-icon {
        font-size: 3rem;
        margin-bottom: 1rem;
        opacity: 0.5;
    }

    .empty-state h4 {
        margin-bottom: 0.5rem;
        color: var(--text-secondary);
    }

    /* Alertas */
    .alert {
        padding: 1rem 1.5rem;
        border-radius: var(--radius);
        margin-bottom: 2rem;
        border: 1px solid;
        position: relative;
        overflow: hidden;
        box-shadow: var(--shadow-md);
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .alert::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 4px;
    }

    .alert-success {
        background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
        border-color: #10b981;
        color: #065f46;
    }

    .alert-success::before {
        background: #10b981;
    }

    .alert-success .alert-icon {
        color: #10b981;
        font-size: 1.25rem;
    }

    .alert-info {
        background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
        border-color: #3b82f6;
        color: #1e40af;
    }

    .alert-info::before {
        background: #3b82f6;
    }

    .alert-info .alert-icon {
        color: #3b82f6;
        font-size: 1.25rem;
    }

    /* Animación para alertas */
    .alert {
        animation: slideInDown 0.5s ease-out;
    }

    @keyframes slideInDown {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Auto-hide para alertas después de 5 segundos */
    .alert.auto-hide {
        animation: slideInDown 0.5s ease-out, fadeOut 0.5s ease-out 4.5s forwards;
    }

    @keyframes fadeOut {
        from {
            opacity: 1;
            transform: translateY(0);
        }
        to {
            opacity: 0;
            transform: translateY(-20px);
        }
    }

    /* Animaciones exactas de planTrabajos */
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .fade-in {
        animation: fadeIn 0.5s ease-out forwards;
    }

    /* Layout responsivo */
    @media (max-width: 768px) {
        .detail-workspace {
            padding: 0 1rem;
        }
        
        .header-content {
            flex-direction: column;
            gap: 1rem;
            text-align: center;
        }
        
        .info-grid {
            grid-template-columns: 1fr;
        }
        
        .modern-table {
            font-size: 0.875rem;
        }
        
        .modern-table th,
        .modern-table td {
            padding: 0.75rem;
        }
    }
</style>

{{-- FontAwesome para iconos --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<div class="detail-workspace">
    <!-- Header del Detalle -->
    <div class="detail-header">
        <div class="header-content">
            <div>
                <h1 class="header-title">
                    <i class="fas fa-home"></i>
                    Unidad {{ $unidad->numero_departamento }}
                </h1>
                <p class="header-subtitle">
                    Piso {{ $unidad->piso }} - Estado: {{ ucfirst($unidad->estado) }}
                </p>
            </div>
            <div class="header-actions">
                <button type="button" class="btn-modern btn-success" id="btn-asignar-personas">
                    <i class="fas fa-user-plus"></i> Asignar Personas
                </button>
                <a href="{{ route('unidades.edit', $unidad) }}" class="btn-modern btn-warning">
                    <i class="fas fa-edit"></i> Editar
                </a>
                <a href="{{ route('unidades.index') }}" class="back-btn">
                    <i class="fas fa-arrow-left"></i> Volver
                </a>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            <div class="alert-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <div>
                <strong>¡Éxito!</strong>
                {{ session('success') }}
            </div>
        </div>
    @endif

    @if(session('info'))
        <div class="alert alert-info">
            <div class="alert-icon">
                <i class="fas fa-info-circle"></i>
            </div>
            <div>
                <strong>Información:</strong>
                {{ session('info') }}
            </div>
        </div>
    @endif

    <!-- Grid de Información Principal -->
    <div class="info-grid">
        <div class="info-card">
            <div class="card-icon" style="background: linear-gradient(135deg, var(--primary-color), var(--primary-light))">
                <i class="fas fa-home"></i>
            </div>
            <div class="card-title">Número de Departamento</div>
            <div class="card-value">{{ $unidad->numero_departamento }}</div>
            <div class="card-subtitle">Identificador único</div>
        </div>

        <div class="info-card">
            <div class="card-icon" style="background: linear-gradient(135deg, var(--info-color), #2563eb)">
                <i class="fas fa-building"></i>
            </div>
            <div class="card-title">Piso</div>
            <div class="card-value">{{ $unidad->piso }}</div>
            <div class="card-subtitle">Nivel del edificio</div>
        </div>

        <div class="info-card">
            <div class="card-icon" style="background: linear-gradient(135deg, var(--success-color), #059669)">
                <i class="fas fa-wrench"></i>
            </div>
            <div class="card-title">Estado Construcción</div>
            <div class="card-value">{{ $unidad->estado }}</div>
            <div class="card-subtitle">Estado de la obra</div>
        </div>

        <div class="info-card">
            <div class="card-icon" style="background: linear-gradient(135deg, var(--warning-color), #d97706)">
                <i class="fas fa-users"></i>
            </div>
            <div class="card-title">Personas Asignadas</div>
            <div class="card-value">{{ $unidad->personas->count() }}</div>
            <div class="card-subtitle">Total de residentes</div>
        </div>
    </div>

    <!-- Personas Asignadas -->
    <div class="records-container fade-in" id="personas-asignadas">
        <div class="records-header">
            <h3 class="records-title">
                <i class="fas fa-users"></i>
                Personas Asignadas
            </h3>
        </div>
        
        @if($unidad->personas && $unidad->personas->count() > 0)
            <table class="modern-table">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Email</th>
                        <th>Teléfono</th>
                        <th>Fecha de Asignación</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($unidad->personas as $persona)
                        <tr onclick="window.location='{{ route('usuarios.show', $persona->id) }}'" style="cursor: pointer;">
                            <td>
                                <div style="display: flex; align-items: center; gap: 0.75rem;">
                                    <div style="width: 2.5rem; height: 2.5rem; border-radius: 50%; background: #dbeafe; display: flex; align-items: center; justify-content: center;">
                                        <i class="fas fa-user" style="color: #1e40af; font-size: 0.875rem;"></i>
                                    </div>
                                    <div>
                                        <div style="font-weight: 600;">{{ $persona->name }} {{ $persona->apellido }}</div>
                                        <div style="font-size: 0.75rem; color: var(--text-muted);">
                                            CI: {{ $persona->CI }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                @if($persona->user && $persona->user->email)
                                    <span class="badge-modern badge-info">
                                        <i class="fas fa-envelope"></i>
                                        {{ $persona->user->email }}
                                    </span>
                                @else
                                    <span style="color: var(--text-muted);">Sin email</span>
                                @endif
                            </td>
                            <td>
                                @if($persona->telefono)
                                    <span class="badge-modern badge-success">
                                        <i class="fas fa-phone"></i>
                                        {{ $persona->telefono }}
                                    </span>
                                @else
                                    <span style="color: var(--text-muted);">Sin teléfono</span>
                                @endif
                            </td>
                            <td>
                                @if($persona->fecha_asignacion_unidad)
                                    <div style="font-weight: 500;">
                                        {{ \Carbon\Carbon::parse($persona->fecha_asignacion_unidad)->format('d/m/Y') }}
                                    </div>
                                    <div style="font-size: 0.75rem; color: var(--text-muted);">
                                        {{ \Carbon\Carbon::parse($persona->fecha_asignacion_unidad)->format('H:i') }}
                                    </div>
                                @else
                                    <span style="color: var(--text-muted);">N/A</span>
                                @endif
                            </td>
                            <td onclick="event.stopPropagation();">
                                <form method="POST" action="{{ route('unidades.desasignar-persona', [$unidad->id, $persona->id]) }}" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('¿Estás seguro de desasignar esta persona?')">>
                                        <i class="fas fa-user-times"></i> Desasignar
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="empty-state">
                <div class="empty-state-icon">
                    <i class="fas fa-users"></i>
                </div>
                <h4>No hay personas asignadas</h4>
                <p>Los residentes aparecerán aquí cuando sean asignados a esta unidad.</p>
            </div>
        @endif
    </div>
</div>

<!-- Modal para asignar personas -->
<div id="modal-asignar-personas" class="modal-overlay" style="display: none;">
    <div class="modal-content">
        <div class="modal-header">
            <h3><i class="fas fa-user-plus"></i> Asignar Personas a la Unidad</h3>
            <button type="button" class="modal-close" onclick="cerrarModal()">&times;</button>
        </div>
        <div class="modal-body">
            <div id="personas-disponibles-container">
                <div class="loading-spinner">
                    <i class="fas fa-spinner fa-spin"></i> Cargando personas disponibles...
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Auto-hide alerts after 5 seconds
document.addEventListener('DOMContentLoaded', function() {
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        alert.classList.add('auto-hide');
        setTimeout(() => {
            alert.style.display = 'none';
        }, 5000);
    });
});

// Funcionalidad del modal para asignar personas
document.getElementById('btn-asignar-personas').addEventListener('click', function() {
    document.getElementById('modal-asignar-personas').style.display = 'flex';
    cargarPersonasDisponibles();
});

function cerrarModal() {
    document.getElementById('modal-asignar-personas').style.display = 'none';
}

// Cerrar modal al hacer click fuera de él
document.getElementById('modal-asignar-personas').addEventListener('click', function(e) {
    if (e.target === this) {
        cerrarModal();
    }
});

function cargarPersonasDisponibles() {
    const container = document.getElementById('personas-disponibles-container');
    
    fetch(`{{ route('unidades.personas-disponibles') }}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                mostrarPersonasDisponibles(data.personas);
            } else {
                container.innerHTML = `
                    <div class="empty-state" style="text-align: center; padding: 2rem;">
                        <div class="empty-state-icon" style="font-size: 2rem; margin-bottom: 1rem; color: var(--text-muted);">
                            <i class="fas fa-users-slash"></i>
                        </div>
                        <h4>No hay personas disponibles</h4>
                        <p>No hay personas en estado "Aceptado" o "Inactivo" sin asignar a una unidad.</p>
                    </div>
                `;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            container.innerHTML = `
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle"></i>
                    Error al cargar las personas disponibles. Inténtalo de nuevo.
                </div>
            `;
        });
}

function mostrarPersonasDisponibles(personas) {
    const container = document.getElementById('personas-disponibles-container');
    
    if (personas.length === 0) {
        container.innerHTML = `
            <div class="empty-state" style="text-align: center; padding: 2rem;">
                <div class="empty-state-icon" style="font-size: 2rem; margin-bottom: 1rem; color: var(--text-muted);">
                    <i class="fas fa-users-slash"></i>
                </div>
                <h4>No hay personas disponibles</h4>
                <p>No hay personas en estado "Aceptado" o "Inactivo" sin asignar a una unidad.</p>
            </div>
        `;
        return;
    }
    
    let html = '<div>';
    
    personas.forEach(persona => {
        const iniciales = (persona.name.charAt(0) + (persona.apellido ? persona.apellido.charAt(0) : '')).toUpperCase();
        
        html += `
            <div class="persona-item">
                <div class="persona-info">
                    <div class="persona-avatar">${iniciales}</div>
                    <div class="persona-details">
                        <h4>${persona.name} ${persona.apellido || ''}</h4>
                        <p>CI: ${persona.CI || 'No registrada'} | Estado: ${persona.estadoRegistro}</p>
                        ${persona.user && persona.user.email ? `<p>Email: ${persona.user.email}</p>` : ''}
                    </div>
                </div>
                <button class="btn-asignar" onclick="asignarPersona(${persona.id}, '${persona.name} ${persona.apellido || ''}')">
                    <i class="fas fa-user-plus"></i> Asignar
                </button>
            </div>
        `;
    });
    
    html += '</div>';
    container.innerHTML = html;
}

function asignarPersona(personaId, nombrePersona) {
    if (!confirm(`¿Estás seguro de asignar a ${nombrePersona} a esta unidad habitacional?`)) {
        return;
    }
    
    const formData = new FormData();
    formData.append('_token', '{{ csrf_token() }}');
    formData.append('persona_id', personaId);
    
    fetch(`{{ route('unidades.asignar-persona', $unidad->id) }}`, {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Mostrar mensaje de éxito y recargar la página
            alert(data.message);
            window.location.reload();
        } else {
            alert(data.message || 'Error al asignar la persona');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error al asignar la persona. Inténtalo de nuevo.');
    });
}
</script>
@endsection