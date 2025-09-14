@extends('layouts.app')

@section('content')
    
    <link rel="stylesheet" href="{{ asset('css/admin-custom.css') }}">
    <div class="main-content">
        <div class="admin-title">Administradores</div>
        @if(session('success'))
            <div class="message-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="message-error">{{ session('error') }}</div>
        @endif
        <div class="admin-list-table">
            <table>
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Email</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($admins as $adm)
                        <tr>
                            <td>{{ $adm->name }}</td>
                            <td>{{ $adm->email }}</td>
                            <td>
                                <div class="admin-actions">
                                    <a href="{{ route('admin.edit', $adm->id) }}" class="btn-edit">Modificar</a>
                                    <form method="POST" action="{{ route('admin.destroy', $adm->id) }}" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-delete" onclick="return confirm('¿Seguro que deseas eliminar este administrador?')">Eliminar</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="admin-form">
            @if(isset($admin))
                <form method="POST" action="{{ route('admin.update', $admin->id) }}" autocomplete="off">
                    @csrf
                    @method('PUT')
                    <label for="name">Nombre</label>
                    <input type="text" name="name" id="name" required value="{{ old('name', $admin->name) }}" autocomplete="off">
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" required value="{{ old('email', $admin->email) }}" autocomplete="off">
                    <label for="password">Contraseña (dejar vacío para no cambiar)</label>
                    <input type="password" name="password" id="password" autocomplete="new-password">
                    <label for="password_confirmation">Confirmar Contraseña</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" autocomplete="new-password">
                    <button type="submit" class="btn-edit">Actualizar Administrador</button>
                    <a href="{{ route('admin.list') }}" class="btn-cancel">Cancelar</a>
                </form>
            @else
                <form method="POST" action="{{ route('admin.store') }}" autocomplete="off">
                    @csrf
                    <label for="name">Nombre</label>
                    <input type="text" name="name" id="name" required value="" autocomplete="off">
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" required value="" autocomplete="off">
                    <label for="password">Contraseña</label>
                    <input type="password" name="password" id="password" required autocomplete="new-password">
                    <label for="password_confirmation">Confirmar Contraseña</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" required autocomplete="new-password">
                    <button type="submit" class="btn-edit">Crear Administrador</button>
                </form>
            @endif
        </div>
    </div>
@endsection
      
    <div class="main-content">
        <div class="admin-title">Administradores</div>
        @if(session('success'))
            <div class="message-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="message-error">{{ session('error') }}</div>
        @endif
        <div class="admin-list-table">
            <table>
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Email</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($admins as $adm)
                        <tr>
                            <td>{{ $adm->name }}</td>
                            <td>{{ $adm->email }}</td>
                            <td>
                                <div class="admin-actions">
                                    <a href="{{ route('admin.edit', $adm->id) }}" class="btn-edit">Modificar</a>
                                    <form method="POST" action="{{ route('admin.destroy', $adm->id) }}" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-delete" onclick="return confirm('¿Seguro que deseas eliminar este administrador?')">Eliminar</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="admin-form">
            @if(isset($admin))
                <form method="POST" action="{{ route('admin.update', $admin->id) }}" autocomplete="off">
                    @csrf
                    @method('PUT')
                    <label for="name">Nombre</label>
                    <input type="text" name="name" id="name" required value="{{ old('name', $admin->name) }}" autocomplete="off">
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" required value="{{ old('email', $admin->email) }}" autocomplete="off">
                    <label for="password">Contraseña (dejar vacío para no cambiar)</label>
                    <input type="password" name="password" id="password" autocomplete="new-password">
                    <label for="password_confirmation">Confirmar Contraseña</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" autocomplete="new-password">
                    <button type="submit" class="btn-edit">Actualizar Administrador</button>
                    <a href="{{ route('admin.list') }}" class="btn-cancel">Cancelar</a>
                </form>
            @else
                <form method="POST" action="{{ route('admin.store') }}" autocomplete="off">
                    @csrf
                    <label for="name">Nombre</label>
                    <input type="text" name="name" id="name" required value="" autocomplete="off">
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" required value="" autocomplete="off">
                    <label for="password">Contraseña</label>
                    <input type="password" name="password" id="password" required autocomplete="new-password">
                    <label for="password_confirmation">Confirmar Contraseña</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" required autocomplete="new-password">
                    <button type="submit" class="btn-edit">Crear Administrador</button>
                </form>
            @endif
        </div>
    </div>
@endsection
