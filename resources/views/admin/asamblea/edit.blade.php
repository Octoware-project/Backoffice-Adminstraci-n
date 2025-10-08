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
    .edit-workspace {
        max-width: 1000px;
        margin: 0 auto;
        padding: 0 1.5rem 2rem;
        background-color: #d2d2f1ff;
        min-height: 100vh;
    }

    /* Header moderno */
    .edit-header {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-light) 100%);
        border-radius: var(--radius);
        padding: 2rem 2.5rem;
        margin-bottom: 2rem;
        box-shadow: var(--shadow-lg);
        color: white;
        position: relative;
        overflow: hidden;
    }

    .edit-header::before {
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

    .btn-back {
        display: inline-flex;
        align-items: center;
        gap: 0.75rem;
        padding: 1rem 2rem;
        border-radius: var(--radius-sm);
        font-weight: 600;
        font-size: 1rem;
        text-decoration: none;
        transition: all 0.2s ease;
        border: none;
        cursor: pointer;
        background: rgba(255, 255, 255, 0.15);
        color: white;
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .btn-back:hover {
        background: rgba(255, 255, 255, 0.25);
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        color: white;
        text-decoration: none;
    }

    /* Formulario */
    .form-container {
        background: var(--bg-primary);
        border-radius: var(--radius);
        box-shadow: var(--shadow-md);
        overflow: hidden;
    }

    .form-header {
        background: linear-gradient(90deg, var(--bg-light) 0%, var(--bg-secondary) 100%);
        padding: 1.5rem 2rem;
        border-bottom: 1px solid var(--border-color);
    }

    .form-title {
        margin: 0;
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--text-primary);
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .form-body {
        padding: 2rem;
    }

    .form-grid {
        display: grid;
        gap: 1.5rem;
    }

    .form-group {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .form-label {
        font-size: 0.875rem;
        font-weight: 700;
        color: var(--text-primary);
        text-transform: uppercase;
        letter-spacing: 0.05em;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .form-input, .form-textarea {
        padding: 1rem 1.25rem;
        border: 2px solid var(--border-color);
        border-radius: var(--radius-sm);
        font-size: 1rem;
        font-weight: 500;
        color: var(--text-primary);
        background: var(--bg-primary);
        transition: all 0.2s ease;
    }

    .form-input:focus, .form-textarea:focus {
        outline: none;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        transform: translateY(-1px);
    }

    .form-textarea {
        min-height: 120px;
        resize: vertical;
        font-family: inherit;
        line-height: 1.6;
    }

    .form-actions {
        margin-top: 2rem;
        padding-top: 2rem;
        border-top: 1px solid var(--border-color);
        display: flex;
        gap: 1rem;
        justify-content: flex-end;
    }

    .btn-submit {
        display: inline-flex;
        align-items: center;
        gap: 0.75rem;
        padding: 1rem 2rem;
        border-radius: var(--radius-sm);
        font-weight: 600;
        font-size: 1rem;
        text-decoration: none;
        transition: all 0.2s ease;
        border: none;
        cursor: pointer;
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-light) 100%);
        color: white;
        box-shadow: var(--shadow-sm);
    }

    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-lg);
        color: white;
    }

    .btn-cancel {
        display: inline-flex;
        align-items: center;
        gap: 0.75rem;
        padding: 1rem 2rem;
        border-radius: var(--radius-sm);
        font-weight: 600;
        font-size: 1rem;
        text-decoration: none;
        transition: all 0.2s ease;
        border: 2px solid var(--border-color);
        cursor: pointer;
        background: var(--bg-primary);
        color: var(--text-primary);
    }

    .btn-cancel:hover {
        background: var(--bg-secondary);
        border-color: var(--primary-color);
        color: var(--primary-color);
        text-decoration: none;
        transform: translateY(-1px);
    }

    /* Validación de errores */
    .form-error {
        color: var(--danger-color);
        font-size: 0.875rem;
        font-weight: 500;
        margin-top: 0.25rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .form-input.error, .form-textarea.error {
        border-color: var(--danger-color);
        box-shadow: 0 0 0 3px rgba(252, 70, 107, 0.1);
    }

    /* Responsive */
    @media (max-width: 768px) {
        .edit-workspace {
            padding: 0 1rem 2rem;
        }
        
        .edit-header {
            padding: 1.5rem 2rem;
        }
        
        .header-content {
            flex-direction: column;
            gap: 1rem;
            text-align: center;
        }
        
        .form-body {
            padding: 1.5rem;
        }
        
        .form-actions {
            flex-direction: column-reverse;
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

<div class="edit-workspace">
    <!-- Header -->
    <div class="edit-header fade-in">
        <div class="header-content">
            <div class="header-info">
                <h1 class="header-title">
                    <i class="fas fa-edit"></i>
                    Editar Junta de Asamblea
                </h1>
                <p class="header-subtitle">
                    Modifica la información de la junta "{{ $junta->lugar }}"
                </p>
            </div>
            <a href="{{ route('admin.juntas_asamblea.show', $junta->id) }}" class="btn-back">
                <i class="fas fa-arrow-left"></i>
                Volver
            </a>
        </div>
    </div>

    <!-- Formulario de edición -->
    <div class="form-container fade-in">
        <div class="form-header">
            <h2 class="form-title">
                <i class="fas fa-gavel"></i>
                Información de la Junta
            </h2>
        </div>
        <div class="form-body">
            <form method="POST" action="{{ route('admin.juntas_asamblea.update', $junta->id) }}" id="edit-form">
                @csrf
                @method('PUT')
                
                <div class="form-grid">
                    <!-- Lugar -->
                    <div class="form-group">
                        <label for="lugar" class="form-label">
                            <i class="fas fa-map-marker-alt"></i>
                            Lugar de Realización
                        </label>
                        <input 
                            type="text" 
                            name="lugar" 
                            id="lugar" 
                            class="form-input @error('lugar') error @enderror" 
                            value="{{ old('lugar', $junta->lugar) }}" 
                            placeholder="Ej: Sala de juntas, Auditorium principal..."
                            required
                        >
                        @error('lugar')
                            <div class="form-error">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Fecha -->
                    <div class="form-group">
                        <label for="fecha" class="form-label">
                            <i class="fas fa-calendar-alt"></i>
                            Fecha de la Junta
                        </label>
                        <input 
                            type="date" 
                            name="fecha" 
                            id="fecha" 
                            class="form-input @error('fecha') error @enderror" 
                            value="{{ old('fecha', $junta->fecha) }}" 
                            required
                        >
                        @error('fecha')
                            <div class="form-error">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Detalle -->
                    <div class="form-group">
                        <label for="detalle" class="form-label">
                            <i class="fas fa-file-alt"></i>
                            Detalles y Observaciones
                            <span style="font-weight: 400; color: var(--text-muted); text-transform: none; font-size: 0.75rem;">(Opcional)</span>
                        </label>
                        <textarea 
                            name="detalle" 
                            id="detalle" 
                            class="form-textarea @error('detalle') error @enderror"
                            placeholder="Describe los temas a tratar, observaciones importantes o cualquier información adicional relevante para esta junta..."
                        >{{ old('detalle', $junta->detalle) }}</textarea>
                        @error('detalle')
                            <div class="form-error">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn-submit">
                        <i class="fas fa-save"></i>
                        Actualizar Junta
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Validación del formulario
document.getElementById('edit-form').addEventListener('submit', function(e) {
    const lugar = document.getElementById('lugar');
    const fecha = document.getElementById('fecha');
    
    // Limpiar errores previos
    document.querySelectorAll('.form-input, .form-textarea').forEach(input => {
        input.classList.remove('error');
    });
    
    let hasError = false;
    
    // Validar lugar
    if (!lugar.value.trim()) {
        lugar.classList.add('error');
        hasError = true;
    }
    
    // Validar fecha
    if (!fecha.value) {
        fecha.classList.add('error');
        hasError = true;
    }
    
    if (hasError) {
        e.preventDefault();
        // Scroll al primer error
        const firstError = document.querySelector('.form-input.error, .form-textarea.error');
        if (firstError) {
            firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
            firstError.focus();
        }
    }
});

// Efecto de focus en inputs
document.querySelectorAll('.form-input, .form-textarea').forEach(input => {
    input.addEventListener('focus', function() {
        this.classList.remove('error');
    });
});
</script>
@endsection
