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

    /* Back Button Style */
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

    /* Form Container */
    .form-container {
        background: var(--bg-primary);
        border-radius: var(--radius);
        padding: 2.5rem;
        box-shadow: var(--shadow-md);
        margin-bottom: 2rem;
        border: 1px solid var(--border-color);
    }

    /* Form Groups */
    .form-group {
        margin-bottom: 2rem;
    }

    .form-label {
        display: block;
        font-size: 0.875rem;
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .form-control {
        width: 100%;
        padding: 1rem 1.25rem;
        border: 2px solid var(--border-color);
        border-radius: var(--radius-sm);
        background: var(--bg-primary);
        color: var(--text-primary);
        font-size: 1rem;
        font-weight: 500;
        transition: all 0.2s ease;
    }

    .form-control:focus {
        outline: none;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        background: white;
    }

    .form-control.is-invalid {
        border-color: var(--danger-color);
    }

    /* Textarea específico */
    .form-control[name="detalle"] {
        min-height: 120px;
        resize: vertical;
    }

    /* Botones Modernos */
    .btn-modern {
        display: inline-flex;
        align-items: center;
        gap: 0.75rem;
        padding: 1.1rem 2.2rem;
        border-radius: var(--radius-sm);
        font-weight: 600;
        font-size: 1.125rem;
        text-decoration: none;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        border: none;
        cursor: pointer;
        position: relative;
        overflow: hidden;
    }

    .btn-primary-modern {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
        border: none;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.025em;
        position: relative;
        overflow: hidden;
    }

    .btn-primary-modern::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
        transition: left 0.6s ease;
    }

    .btn-primary-modern:hover {
        background: linear-gradient(135deg, #059669 0%, #047857 100%);
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(16, 185, 129, 0.4);
        color: white;
        text-decoration: none;
    }

    .btn-primary-modern:hover::before {
        left: 100%;
    }

    .btn-primary-modern:active {
        transform: translateY(0);
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
    }

    .btn-primary-modern:focus {
        outline: none;
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3), 0 0 0 3px rgba(16, 185, 129, 0.1);
    }

    .btn-secondary-modern {
        background: var(--bg-primary);
        color: var(--text-primary);
        border: 2px solid var(--border-color);
        box-shadow: var(--shadow-sm);
    }

    .btn-secondary-modern:hover {
        background: var(--bg-light);
        transform: translateY(-1px);
        box-shadow: var(--shadow-md);
        color: var(--text-primary);
        text-decoration: none;
    }

    /* Actions */
    .form-actions {
        display: flex;
        gap: 1rem;
        justify-content: flex-start;
        padding-top: 1.5rem;
        border-top: 2px solid var(--bg-light);
        margin-top: 2rem;
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
        
        .form-container {
            padding: 1.5rem;
        }
        
        .form-actions {
            flex-direction: column;
        }
        
        .btn-modern {
            justify-content: center;
        }

        .header-content {
            flex-direction: column;
            gap: 1rem;
            align-items: flex-start;
        }
    }
</style>

<div class="planes-workspace">
    <!-- Header -->
    <div class="planes-header">
        <div class="header-content">
            <div>
                <h1 class="header-title">
                    <i class="fas fa-plus-circle" style="margin-right: 1rem;"></i>
                    Crear Nueva Junta
                </h1>
                <p class="header-subtitle">Complete la información para programar una nueva junta de asamblea</p>
            </div>
            <a href="{{ route('admin.juntas_asamblea.index') }}" class="back-btn">
                <i class="fas fa-arrow-left"></i>
                Volver
            </a>
        </div>
    </div>

    <!-- Form Container -->
    <div class="form-container">
        <!-- Mostrar errores de validación -->
        @if($errors->any())
            <div style="background: #fee2e2; border: 1px solid #fca5a5; border-radius: var(--radius-sm); padding: 1rem; margin-bottom: 2rem;">
                <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.5rem;">
                    <i class="fas fa-exclamation-triangle" style="color: #dc2626;"></i>
                    <h4 style="margin: 0; color: #dc2626; font-size: 1rem; font-weight: 600;">Error al crear la junta</h4>
                </div>
                <ul style="margin: 0.5rem 0 0 0; padding-left: 1.5rem; color: #7f1d1d;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Mostrar mensaje de éxito -->
        @if(session('success'))
            <div style="background: #d1fae5; border: 1px solid #6ee7b7; border-radius: var(--radius-sm); padding: 1rem; margin-bottom: 2rem;">
                <div style="display: flex; align-items: center; gap: 0.5rem;">
                    <i class="fas fa-check-circle" style="color: #059669;"></i>
                    <p style="margin: 0; color: #047857; font-weight: 500;">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.juntas_asamblea.store') }}">
            @csrf
            
            <div class="form-group">
                <label for="lugar" class="form-label">
                    <i class="fas fa-map-marker-alt" style="margin-right: 0.5rem;"></i>
                    Lugar de la Junta
                </label>
                <input type="text" name="lugar" id="lugar" class="form-control" 
                       placeholder="Ingrese el lugar donde se realizará la junta" 
                       value="{{ old('lugar') }}" required>
            </div>

            <div class="form-group">
                <label for="fecha" class="form-label">
                    <i class="fas fa-calendar" style="margin-right: 0.5rem;"></i>
                    Fecha de Realización
                </label>
                <input type="date" name="fecha" id="fecha" class="form-control" 
                       value="{{ old('fecha') }}" required>
            </div>

            <div class="form-group">
                <label for="detalle" class="form-label">
                    <i class="fas fa-file-alt" style="margin-right: 0.5rem;"></i>
                    Detalle de la Junta
                </label>
                <textarea name="detalle" id="detalle" class="form-control" 
                          placeholder="Describa los temas a tratar, objetivos o información adicional relevante...">{{ old('detalle') }}</textarea>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-modern btn-primary-modern">
                    <i class="fas fa-save"></i>
                    Crear Junta
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Configurar fecha mínima como hoy
    const fechaInput = document.getElementById('fecha');
    const today = new Date().toISOString().split('T')[0];
    fechaInput.min = today;
    
    // Efectos de focus modernos en los inputs
    const inputs = document.querySelectorAll('.form-control');
    inputs.forEach(input => {
        input.addEventListener('focus', function() {
            this.parentElement.classList.add('focused');
        });
        
        input.addEventListener('blur', function() {
            this.parentElement.classList.remove('focused');
        });
    });

    // Validación en tiempo real
    const form = document.querySelector('form');
    form.addEventListener('submit', function(e) {
        const lugar = document.getElementById('lugar').value.trim();
        const fecha = document.getElementById('fecha').value;
        
        if (!lugar || !fecha) {
            e.preventDefault();
            alert('Por favor complete todos los campos obligatorios.');
            return false;
        }
        
        // Validar que la fecha no sea anterior a hoy
        const selectedDate = new Date(fecha);
        const todayDate = new Date(today);
        
        if (selectedDate < todayDate) {
            e.preventDefault();
            alert('La fecha de la junta no puede ser anterior a hoy.');
            return false;
        }
    });
});
</script>

@endsection