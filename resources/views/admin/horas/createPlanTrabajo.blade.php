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

    /* Back Button Style (like detail page) */
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

    /* Select styling */
    select.form-control {
        appearance: none;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3e%3c/svg%3e");
        background-position: right 1rem center;
        background-repeat: no-repeat;
        background-size: 1.5em 1.5em;
        padding-right: 3rem;
    }

    /* Option styling for better visibility of bold text */
    select.form-control option {
        padding: 0.5rem;
        font-size: 0.875rem;
    }

    select.form-control option strong {
        font-weight: 700;
        color: var(--text-primary);
    }

    /* User Selector Styles */
    .user-selector-container {
        position: relative;
    }

    .user-selector-controls {
        display: flex;
        gap: 1rem;
        margin-bottom: 1rem;
        flex-wrap: wrap;
    }

    .user-filter-input {
        flex: 1;
        min-width: 200px;
        padding: 0.75rem 1rem;
        border: 2px solid var(--border-color);
        border-radius: var(--radius-sm);
        background: var(--bg-primary);
        color: var(--text-primary);
        font-size: 0.875rem;
        transition: all 0.2s ease;
    }

    .user-filter-input:focus {
        outline: none;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }

    .user-sort-buttons {
        display: flex;
        gap: 0.5rem;
    }

    .sort-btn {
        padding: 0.75rem 1rem;
        border: 2px solid var(--border-color);
        border-radius: var(--radius-sm);
        background: var(--bg-primary);
        color: var(--text-primary);
        font-size: 0.75rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        transform: translateY(0);
        box-shadow: var(--shadow-sm);
    }

    .sort-btn.active {
        background: var(--primary-color);
        color: white;
        border-color: var(--primary-color);
        box-shadow: var(--shadow-md);
    }

    .sort-btn:hover {
        background: var(--bg-light);
        border-color: var(--primary-color);
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
    }

    .sort-btn.active:hover {
        background: var(--primary-light);
        border-color: var(--primary-light);
        transform: translateY(-2px);
        box-shadow: var(--shadow-lg);
    }

    .user-count {
        font-size: 0.75rem;
        color: var(--text-muted);
        margin-top: 0.5rem;
        font-weight: 500;
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
    }
</style>

<div class="planes-workspace">
    <!-- Header -->
    <div class="planes-header">
        <div class="header-content">
            <div>
                <h1 class="header-title">
                    <i class="fas fa-plus-circle" style="margin-right: 1rem;"></i>
                    Crear Plan de Trabajo
                </h1>
                <p class="header-subtitle">Configura un nuevo plan de trabajo para un usuario específico</p>
            </div>
            <a href="{{ route('plan-trabajos.index') }}" class="back-btn">
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
                    <h4 style="margin: 0; color: #dc2626; font-size: 1rem; font-weight: 600;">Error al crear el plan de trabajo</h4>
                </div>
                @if($errors->has('duplicate'))
                    <p style="margin: 0; color: #7f1d1d; font-weight: 500;">{{ $errors->first('duplicate') }}</p>
                @else
                    <ul style="margin: 0.5rem 0 0 0; padding-left: 1.5rem; color: #7f1d1d;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                @endif
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

        <form action="{{ route('plan-trabajos.store') }}" method="POST">
            @csrf
            
            <div class="form-group">
                <label for="user_id" class="form-label">
                    <i class="fas fa-user" style="margin-right: 0.5rem;"></i>
                    Usuario
                </label>
                
                <div class="user-selector-container">
                    <!-- Controles de filtrado y ordenamiento -->
                    <div class="user-selector-controls">
                        <input type="text" id="user-filter" class="user-filter-input" placeholder="Buscar por nombre o email...">
                        
                        <div class="user-sort-buttons">
                            <button type="button" class="sort-btn active" data-sort="name">
                                <i class="fas fa-sort-alpha-down"></i> Nombre
                            </button>
                            <button type="button" class="sort-btn" data-sort="email">
                                <i class="fas fa-envelope"></i> Email
                            </button>
                        </div>
                    </div>
                    
                    <!-- Select de usuarios -->
                    <select name="user_id" id="user_id" class="form-control" required>
                        <option value="">Seleccione un usuario</option>
                        @foreach($usuarios as $usuario)
                            <option value="{{ $usuario->id }}" 
                                    data-name="{{ strtolower($usuario->name) }}" 
                                    data-email="{{ strtolower($usuario->email) }}"
                                    data-display-name="{{ $usuario->name }}"
                                    data-display-email="{{ $usuario->email }}">
                                {{ $usuario->name }} ({{ $usuario->email }})
                            </option>
                        @endforeach
                    </select>
                    
                    <div class="user-count" id="user-count">
                        Mostrando {{ count($usuarios) }} usuarios
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="mes" class="form-label">
                    <i class="fas fa-calendar" style="margin-right: 0.5rem;"></i>
                    Mes
                </label>
                <select name="mes" id="mes" class="form-control" required>
                    <option value="">Seleccione un mes</option>
                    <option value="1">Enero</option>
                    <option value="2">Febrero</option>
                    <option value="3">Marzo</option>
                    <option value="4">Abril</option>
                    <option value="5">Mayo</option>
                    <option value="6">Junio</option>
                    <option value="7">Julio</option>
                    <option value="8">Agosto</option>
                    <option value="9">Septiembre</option>
                    <option value="10">Octubre</option>
                    <option value="11">Noviembre</option>
                    <option value="12">Diciembre</option>
                </select>
            </div>

            <div class="form-group">
                <label for="anio" class="form-label">
                    <i class="fas fa-calendar-alt" style="margin-right: 0.5rem;"></i>
                    Año
                </label>
                <input type="number" name="anio" id="anio" class="form-control" value="{{ date('Y') }}" min="2020" max="2030" required>
            </div>

            <div class="form-group">
                <label for="horas_requeridas" class="form-label">
                    <i class="fas fa-clock" style="margin-right: 0.5rem;"></i>
                    Horas Requeridas
                </label>
                <input type="number" name="horas_requeridas" id="horas_requeridas" class="form-control" min="1" max="500" required>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-modern btn-primary-modern">
                    <i class="fas fa-save"></i>
                    Crear Plan de Trabajo
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const userSelect = document.getElementById('user_id');
    const filterInput = document.getElementById('user-filter');
    const sortButtons = document.querySelectorAll('.sort-btn');
    const userCount = document.getElementById('user-count');
    
    // Obtener todas las opciones (excepto la primera que es el placeholder)
    const allOptions = Array.from(userSelect.options).slice(1);
    let filteredOptions = [...allOptions];
    let currentSort = 'name';
    
    // Función para actualizar el select
    function updateSelect() {
        // Limpiar opciones actuales (mantener placeholder)
        userSelect.innerHTML = '<option value="">Seleccione un usuario</option>';
        
        // Agregar opciones filtradas y ordenadas
        filteredOptions.forEach(option => {
            const newOption = document.createElement('option');
            newOption.value = option.value;
            
            // Copiar todos los atributos data
            newOption.dataset.name = option.dataset.name;
            newOption.dataset.email = option.dataset.email;
            newOption.dataset.displayName = option.dataset.displayName;
            newOption.dataset.displayEmail = option.dataset.displayEmail;
            
            // Cambiar formato según el ordenamiento actual
            if (currentSort === 'email') {
                // Formato: email (nombre) - email en negrita
                newOption.innerHTML = `<strong>${option.dataset.displayEmail}</strong> (${option.dataset.displayName})`;
            } else {
                // Formato: nombre (email) - nombre en negrita
                newOption.innerHTML = `<strong>${option.dataset.displayName}</strong> (${option.dataset.displayEmail})`;
            }
            
            userSelect.appendChild(newOption);
        });
        
        // Actualizar contador
        userCount.textContent = `Mostrando ${filteredOptions.length} de ${allOptions.length} usuarios`;
    }
    
    // Función para filtrar usuarios
    function filterUsers() {
        const searchTerm = filterInput.value.toLowerCase().trim();
        
        if (searchTerm === '') {
            filteredOptions = [...allOptions];
        } else {
            filteredOptions = allOptions.filter(option => {
                const name = option.dataset.name || '';
                const email = option.dataset.email || '';
                return name.includes(searchTerm) || email.includes(searchTerm);
            });
        }
        
        sortUsers(currentSort);
    }
    
    // Función para ordenar usuarios
    function sortUsers(sortBy) {
        currentSort = sortBy;
        
        filteredOptions.sort((a, b) => {
            let valueA, valueB;
            
            if (sortBy === 'name') {
                valueA = a.dataset.name || '';
                valueB = b.dataset.name || '';
            } else if (sortBy === 'email') {
                valueA = a.dataset.email || '';
                valueB = b.dataset.email || '';
            }
            
            return valueA.localeCompare(valueB);
        });
        
        updateSelect();
    }
    
    // Event listeners
    filterInput.addEventListener('input', filterUsers);
    
    sortButtons.forEach(button => {
        button.addEventListener('click', function() {
            // Remover clase active de todos los botones
            sortButtons.forEach(btn => btn.classList.remove('active'));
            
            // Agregar clase active al botón clickeado
            this.classList.add('active');
            
            // Ordenar por el criterio seleccionado
            const sortBy = this.dataset.sort;
            sortUsers(sortBy);
        });
    });
    
    // Inicializar con ordenamiento por nombre
    sortUsers('name');
});
</script>

@endsection
