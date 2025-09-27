@extends('layouts.app')

@push('styles')
    {{-- CSS específicos para usuarios - misma estructura que index --}}
    <style>
        {!! file_get_contents(resource_path('views/admin/usuarios/css/variables.css')) !!}
        {!! file_get_contents(resource_path('views/admin/usuarios/css/index.css')) !!}
    </style>
    
    {{-- Estilos específicos para la página de edición --}}
    <style>
        /* Container Principal - igual que index */
        .usuarios-workspace {
            max-width: 1000px;
            margin: 0 auto;
            padding: 0 1.5rem;
            min-height: 100vh;
        }

        /* Formulario de edición */
        .usuario-form-section {
            background: var(--bg-primary);
            border-radius: var(--radius);
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: var(--shadow-md);
            border: 1px solid rgba(160, 174, 192, 0.2);
        }

        /* Campos del formulario */
        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            display: block;
            color: var(--text-secondary);
            font-weight: 600;
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .form-label i {
            color: var(--primary-color);
            width: 16px;
        }

        .form-control {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 2px solid var(--bg-light);
            border-radius: var(--radius-sm);
            font-size: 0.9rem;
            transition: var(--transition);
            background: var(--bg-primary);
            color: var(--text-primary);
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .form-control:hover {
            border-color: var(--primary-light);
        }

        /* Filas del formulario */
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
            margin-bottom: 1.5rem;
        }

        @media (max-width: 768px) {
            .form-row {
                grid-template-columns: 1fr;
                gap: 1rem;
            }
        }

        /* Botones de acción */
        .form-actions {
            display: flex;
            gap: 1rem;
            margin-top: 2rem;
            padding-top: 2rem;
            border-top: 2px solid var(--bg-light);
        }

        .btn-primary {
            background: #22c55e; /* Verde success */
            color: white;
            padding: 0.75rem 2rem;
            border: none;
            border-radius: var(--radius-sm);
            text-decoration: none;
            font-weight: 600;
            transition: var(--transition);
            box-shadow: var(--shadow-sm);
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            cursor: pointer;
            font-size: 0.9rem;
        }

        .btn-primary:hover {
            background: #16a34a; /* Verde más oscuro en hover */
            transform: translateY(-1px);
            box-shadow: var(--shadow-md);
            color: white;
            text-decoration: none;
        }

        .btn-secondary {
            background: var(--bg-secondary);
            color: var(--text-primary);
            padding: 0.75rem 2rem;
            border: 2px solid var(--border-color);
            border-radius: var(--radius-sm);
            text-decoration: none;
            font-weight: 600;
            transition: var(--transition);
            box-shadow: var(--shadow-sm);
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.9rem;
        }

        .btn-secondary:hover {
            background: white;
            transform: translateY(-1px);
            box-shadow: var(--shadow-md);
            color: var(--text-primary);
            text-decoration: none;
            border-color: var(--primary-color);
        }

        /* Select personalizado */
        .form-select {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 2px solid var(--bg-light);
            border-radius: var(--radius-sm);
            font-size: 0.9rem;
            transition: var(--transition);
            background: var(--bg-primary);
            color: var(--text-primary);
            cursor: pointer;
        }

        .form-select:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .form-select:hover {
            border-color: var(--primary-light);
        }

        /* Animaciones */
        .usuario-content {
            opacity: 0;
            animation: fadeInContent 0.4s ease 0.1s forwards;
        }
        
        @keyframes fadeInContent {
            from {
                opacity: 0;
                transform: translateY(15px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Sección de título */
        .section-title {
            color: var(--text-primary);
            font-size: 1.5rem;
            font-weight: 600;
            margin: 0 0 2rem 0;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .section-title i {
            color: var(--primary-color);
        }
    </style>
    
    {{-- FontAwesome para iconos --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
@endpush

@section('content')
    <div class="usuarios-workspace">
        <div class="usuarios-content">
            {{-- Header Principal - igual que en otras páginas --}}
            <div class="usuarios-header header-pattern">
                <div class="header-content">
                    <div>
                        <h1 class="header-title">Editar Usuario</h1>
                        <p class="header-subtitle">Actualizar información del residente</p>
                    </div>
                    <div class="btn-group">
                        <a href="{{ route('usuarios.show', $usuario->id) }}" class="btn-modern btn-secondary-modern">
                            <i class="fas fa-eye"></i>
                            Ver Datos
                        </a>
                        <a href="{{ route('usuarios.index') }}" class="btn-modern btn-secondary-modern">
                            <i class="fas fa-arrow-left"></i>
                            Volver
                        </a>
                    </div>
                </div>
            </div>

            {{-- Formulario de edición --}}
            <div class="usuario-form-section">
                <h2 class="section-title">
                    <i class="fas fa-user-edit"></i>
                    Información del Usuario
                </h2>
                
                <form method="POST" action="{{ route('usuarios.update', $usuario->id) }}">
                    @csrf
                    @method('PUT')
                    
                    {{-- Información Personal --}}
                    <div class="form-row">
                        <div class="form-group">
                            <label for="nombre" class="form-label">
                                <i class="fas fa-user"></i>
                                Nombre
                            </label>
                            <input type="text" name="nombre" id="nombre" class="form-control" value="{{ $usuario->name }}" required>
                        </div>
                        <div class="form-group">
                            <label for="apellido" class="form-label">
                                <i class="fas fa-user"></i>
                                Apellido
                            </label>
                            <input type="text" name="apellido" id="apellido" class="form-control" value="{{ $usuario->apellido }}" required>
                        </div>
                    </div>

                    {{-- Email y CI --}}
                    <div class="form-row">
                        <div class="form-group">
                            <label for="email" class="form-label">
                                <i class="fas fa-envelope"></i>
                                Email
                            </label>
                            <input type="email" name="email" id="email" class="form-control" value="{{ $usuario->user ? $usuario->user->email : '' }}" required>
                        </div>
                        <div class="form-group">
                            <label for="CI" class="form-label">
                                <i class="fas fa-id-card"></i>
                                Cédula de Identidad
                            </label>
                            <input type="text" name="CI" id="CI" class="form-control" value="{{ $usuario->CI }}">
                        </div>
                    </div>

                    {{-- Información de Contacto --}}
                    <div class="form-row">
                        <div class="form-group">
                            <label for="telefono" class="form-label">
                                <i class="fas fa-phone"></i>
                                Teléfono
                            </label>
                            <input type="text" name="telefono" id="telefono" class="form-control" value="{{ $usuario->telefono }}">
                        </div>
                        <div class="form-group">
                            <label for="direccion" class="form-label">
                                <i class="fas fa-home"></i>
                                Dirección
                            </label>
                            <input type="text" name="direccion" id="direccion" class="form-control" value="{{ $usuario->direccion }}">
                        </div>
                    </div>

                    {{-- Campos adicionales --}}
                    <div class="form-row">
                        <div class="form-group">
                            <label for="fechaNacimiento" class="form-label">
                                <i class="fas fa-birthday-cake"></i>
                                Fecha de Nacimiento
                            </label>
                            <input type="date" name="fechaNacimiento" id="fechaNacimiento" class="form-control" value="{{ $usuario->fechaNacimiento }}">
                        </div>
                        <div class="form-group">
                            <label for="genero" class="form-label">
                                <i class="fas fa-venus-mars"></i>
                                Género
                            </label>
                            <select name="genero" id="genero" class="form-select">
                                <option value="">Seleccionar...</option>
                                <option value="Masculino" {{ $usuario->genero == 'Masculino' ? 'selected' : '' }}>Masculino</option>
                                <option value="Femenino" {{ $usuario->genero == 'Femenino' ? 'selected' : '' }}>Femenino</option>
                                <option value="Otro" {{ $usuario->genero == 'Otro' ? 'selected' : '' }}>Otro</option>
                                <option value="Prefiero no decir" {{ $usuario->genero == 'Prefiero no decir' ? 'selected' : '' }}>Prefiero no decir</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="estadoCivil" class="form-label">
                                <i class="fas fa-heart"></i>
                                Estado Civil
                            </label>
                            <select name="estadoCivil" id="estadoCivil" class="form-select">
                                <option value="">Seleccionar...</option>
                                <option value="Soltero/a" {{ $usuario->estadoCivil == 'Soltero/a' ? 'selected' : '' }}>Soltero/a</option>
                                <option value="Casado/a" {{ $usuario->estadoCivil == 'Casado/a' ? 'selected' : '' }}>Casado/a</option>
                                <option value="Divorciado/a" {{ $usuario->estadoCivil == 'Divorciado/a' ? 'selected' : '' }}>Divorciado/a</option>
                                <option value="Viudo/a" {{ $usuario->estadoCivil == 'Viudo/a' ? 'selected' : '' }}>Viudo/a</option>
                                <option value="Unión libre" {{ $usuario->estadoCivil == 'Unión libre' ? 'selected' : '' }}>Unión libre</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="nacionalidad" class="form-label">
                                <i class="fas fa-flag"></i>
                                Nacionalidad
                            </label>
                            <input type="text" name="nacionalidad" id="nacionalidad" class="form-control" value="{{ $usuario->nacionalidad }}">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="ocupacion" class="form-label">
                                <i class="fas fa-briefcase"></i>
                                Ocupación
                            </label>
                            <input type="text" name="ocupacion" id="ocupacion" class="form-control" value="{{ $usuario->ocupacion }}">
                        </div>
                        <div class="form-group">
                            <label for="estadoRegistro" class="form-label">
                                <i class="fas fa-user-check"></i>
                                Estado de Registro
                            </label>
                            <select name="estadoRegistro" id="estadoRegistro" class="form-select" required>
                                <option value="Pendiente" {{ $usuario->estadoRegistro == 'Pendiente' ? 'selected' : '' }}>Pendiente</option>
                                <option value="Aceptado" {{ $usuario->estadoRegistro == 'Aceptado' ? 'selected' : '' }}>Aceptado</option>
                                <option value="Rechazado" {{ $usuario->estadoRegistro == 'Rechazado' ? 'selected' : '' }}>Rechazado</option>
                                <option value="Inactivo" {{ $usuario->estadoRegistro == 'Inactivo' ? 'selected' : '' }}>Inactivo</option>
                            </select>
                        </div>
                    </div>

                    {{-- Botones de acción --}}
                    <div class="form-actions">
                        <button type="submit" class="btn-primary">
                            <i class="fas fa-save"></i>
                            Guardar Cambios
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
