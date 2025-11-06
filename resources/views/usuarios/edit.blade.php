@extends('layouts.app')
<link rel="stylesheet" href="{{ asset('css/Usuarios/Edit.css') }}">

@push('styles')
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
