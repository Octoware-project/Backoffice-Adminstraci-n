@extends('layouts.app')

@section('content')
<style>
    /* Background de la página */
    body {
        background-color: #d2d2f1ff !important;
        overflow-y: auto;
        overflow-x: hidden;
    }

    /* Ocultar barra de scroll pero mantener funcionalidad */
    body::-webkit-scrollbar {
        width: 0px;
        background: transparent;
    }

    body::-webkit-scrollbar-track {
        background: transparent;
    }

    body::-webkit-scrollbar-thumb {
        background: transparent;
    }

    html {
        scrollbar-width: none;
        -ms-overflow-style: none;
    }

    body {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }

    *::-webkit-scrollbar {
        width: 0px;
        background: transparent;
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
    .junta-workspace {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 1.5rem 2rem;
        background-color: #d2d2f1ff;
        min-height: 100vh;
    }

    /* Header de la junta */
    .junta-header {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-light) 100%);
        border-radius: var(--radius);
        padding: 2rem 2.5rem;
        margin-bottom: 2rem;
        box-shadow: var(--shadow-lg);
        color: white;
        position: relative;
        overflow: hidden;
    }

    .junta-header::before {
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

    .header-info {
        flex: 1;
    }

    .header-title {
        margin: 0 0 0.5rem 0;
        font-size: 2.25rem;
        font-weight: 700;
        letter-spacing: -0.025em;
    }

    .header-subtitle {
        margin: 0;
        font-size: 1.1rem;
        opacity: 0.9;
        font-weight: 400;
    }

    .btn-group {
        display: flex;
        gap: 1rem;
    }

    .btn-modern {
        display: inline-flex;
        align-items: center;
        gap: 0.75rem;
        padding: 1.1rem 2.2rem;
        border-radius: var(--radius-sm);
        font-weight: 600;
        font-size: 1.125rem;
        text-decoration: none;
        transition: all 0.2s ease;
        border: none;
        cursor: pointer;
    }

    .btn-primary-modern {
        background: rgba(255, 255, 255, 0.15);
        color: white;
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .btn-primary-modern:hover {
        background: rgba(255, 255, 255, 0.25);
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        color: white;
        text-decoration: none;
    }

    .btn-secondary-modern {
        background: rgba(255, 255, 255, 0.15);
        color: white;
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .btn-secondary-modern:hover {
        background: rgba(255, 255, 255, 0.25);
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        color: white;
        text-decoration: none;
    }

    /* Contenido principal */
    .junta-content {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 2rem;
        margin-bottom: 2rem;
        align-items: start;
    }

    /* Información de la junta */
    .junta-info-card {
        background: var(--bg-primary);
        border-radius: var(--radius);
        box-shadow: var(--shadow-md);
        overflow: hidden;
        border: 1px solid var(--border-color);
        height: 100%;
    }

    .info-card-header {
        background: linear-gradient(90deg, var(--bg-light) 0%, var(--bg-secondary) 100%);
        padding: 1.5rem 2rem;
        border-bottom: 1px solid var(--border-color);
    }

    .info-card-title {
        margin: 0;
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--text-primary);
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .info-card-body {
        padding: 2rem;
    }

    .info-grid {
        display: grid;
        gap: 1.5rem;
    }

    .info-item {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .info-label {
        font-size: 0.875rem;
        font-weight: 600;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 0.05em;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .info-value {
        font-size: 1.125rem;
        font-weight: 500;
        color: var(--text-primary);
        padding: 1rem;
        background: var(--bg-secondary);
        border-radius: var(--radius-sm);
        border-left: 4px solid var(--primary-color);
    }

    .info-value.large {
        min-height: 120px;
        line-height: 1.6;
    }

    /* Card de acciones rápidas */
    .actions-card {
        background: var(--bg-primary);
        border-radius: var(--radius);
        box-shadow: var(--shadow-md);
        height: 100%;
        border: 1px solid var(--border-color);
        display: flex;
        flex-direction: column;
    }

    .actions-header {
        background: linear-gradient(90deg, var(--bg-light) 0%, var(--bg-secondary) 100%);
        padding: 1.5rem 2rem;
        border-bottom: 1px solid var(--border-color);
    }

    .actions-title {
        margin: 0;
        font-size: 1.125rem;
        font-weight: 700;
        color: var(--text-primary);
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .actions-body {
        padding: 1.5rem;
        flex: 1;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .actions-list {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .action-item {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1rem;
        background: var(--bg-secondary);
        border-radius: var(--radius-sm);
        transition: all 0.2s ease;
        text-decoration: none;
        color: var(--text-primary);
    }

    .action-item:hover {
        background: var(--bg-light);
        transform: translateY(-1px);
        box-shadow: var(--shadow-sm);
        color: var(--text-primary);
        text-decoration: none;
    }

    .action-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
    }

    .action-edit .action-icon {
        background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);
        color: #d97706;
    }

    .action-delete .action-icon {
        background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
        color: #dc2626;
    }

    .action-back .action-icon {
        background: linear-gradient(135deg, #e0f2fe 0%, #b3e5fc 100%);
        color: #0369a1;
    }

    .action-content {
        flex: 1;
    }

    .action-title {
        font-weight: 600;
        margin: 0 0 0.25rem 0;
    }

    .action-description {
        font-size: 0.875rem;
        color: var(--text-muted);
        margin: 0;
    }



    /* Responsive */
    @media (max-width: 768px) {
        .junta-workspace {
            padding: 0 1rem 2rem;
        }
        
        .junta-header {
            padding: 1.5rem 2rem;
        }
        
        .header-content {
            flex-direction: column;
            gap: 1rem;
            text-align: center;
        }
        
        .btn-group {
            flex-wrap: wrap;
            justify-content: center;
        }
        
        .junta-content {
            grid-template-columns: 1fr;
            gap: 1.5rem;
        }
        
        .info-card-body {
            padding: 1.5rem;
        }
    }

    /* Animaciones */
    @keyframes fadeIn {
        from { 
            opacity: 0; 
            transform: translateY(20px); 
        }
        to { 
            opacity: 1; 
            transform: translateY(0); 
        }
    }

    .fade-in {
        animation: fadeIn 0.5s ease-out forwards;
    }
</style>

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
// Función para confirmar eliminación
function confirmDeleteJunta(juntaId, lugar) {
    const modal = document.createElement('div');
    modal.className = 'delete-modal';
    modal.style.cssText = `
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 10000;
        opacity: 0;
        transition: opacity 0.2s ease;
        backdrop-filter: blur(4px);
    `;
    
    modal.innerHTML = `
        <div class="delete-modal-content" style="
            background: white;
            padding: 2rem;
            border-radius: 16px;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            max-width: 400px;
            width: 90%;
            text-align: center;
            transform: scale(0.95) translateY(-10px);
            transition: all 0.2s ease;
        ">
            <div style="
                width: 64px;
                height: 64px;
                background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                margin: 0 auto 1rem;
            ">
                <i class="fas fa-exclamation-triangle" style="font-size: 24px; color: #dc2626;"></i>
            </div>
            <h3 style="margin: 0 0 0.5rem 0; color: #1a202c; font-size: 1.25rem; font-weight: 700;">
                ¿Eliminar Junta?
            </h3>
            <p style="margin: 0 0 2rem 0; color: #4a5568; font-size: 0.875rem; line-height: 1.5;">
                Esta acción eliminará permanentemente la junta "<strong>${lugar}</strong>" del sistema. 
                Esta acción no se puede deshacer.
            </p>
            <div style="display: flex; gap: 0.75rem; justify-content: center;">
                <button onclick="closeDeleteModal()" style="
                    padding: 0.75rem 1.5rem;
                    border: 1px solid #d1d5db;
                    border-radius: 8px;
                    background: #f9fafb;
                    color: #374151;
                    font-weight: 600;
                    cursor: pointer;
                    transition: all 0.2s ease;
                " class="btn-cancel">
                    Cancelar
                </button>
                <button onclick="deleteJunta(${juntaId})" style="
                    padding: 0.75rem 1.5rem;
                    border: none;
                    border-radius: 8px;
                    background: #dc2626;
                    color: white;
                    font-weight: 600;
                    cursor: pointer;
                    transition: all 0.2s ease;
                " class="btn-confirm">
                    Eliminar Junta
                </button>
            </div>
        </div>
    `;
    
    document.body.appendChild(modal);
    document.body.style.overflow = 'hidden';
    
    setTimeout(() => {
        modal.style.opacity = '1';
        const content = modal.querySelector('.delete-modal-content');
        content.style.transform = 'scale(1) translateY(0)';
    }, 10);
    
    // Event listeners para los botones con hover effects
    const cancelBtn = modal.querySelector('.btn-cancel');
    const confirmBtn = modal.querySelector('.btn-confirm');
    
    cancelBtn.addEventListener('mouseenter', function() {
        this.style.background = '#f3f4f6';
    });
    cancelBtn.addEventListener('mouseleave', function() {
        this.style.background = '#f9fafb';
    });
    
    confirmBtn.addEventListener('mouseenter', function() {
        this.style.background = '#b91c1c';
    });
    confirmBtn.addEventListener('mouseleave', function() {
        this.style.background = '#dc2626';
    });
}

function closeDeleteModal() {
    const modal = document.querySelector('.delete-modal');
    
    if (modal) {
        modal.style.opacity = '0';
        const content = modal.querySelector('.delete-modal-content');
        if (content) {
            content.style.transform = 'scale(0.95) translateY(-10px)';
        }
        
        setTimeout(() => {
            if (modal && modal.parentNode) {
                modal.remove();
            }
        }, 200);
    }
    
    document.body.style.overflow = '';
    document.body.removeAttribute('style');
}

function deleteJunta(juntaId) {
    const form = document.getElementById('delete-form');
    form.action = `{{ url('/admin/juntas_asamblea') }}/${juntaId}`;
    form.submit();
}

// Cerrar modal con tecla Escape
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeDeleteModal();
    }
});
</script>
@endsection
