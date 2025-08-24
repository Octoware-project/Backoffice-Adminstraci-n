<!DOCTYPE html>
<html>
<head>
    <title>Listado de Usuarios</title>
</head>
<body>
    <h1>Usuarios Pendientes</h1>
@if($pendientes->isEmpty())
    <p>No hay usuarios pendientes.</p>
    @else
        <ul>
            @foreach ($pendientes as $usuario)
                <li>
                    {{ $usuario->nombre }}  {{$usuario->apellido}} - {{ $usuario->email }}
 
                    <form action="{{ route('usuarios.aceptar', $usuario->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('PUT')
                        <button type="submit">Aceptar</button>
                    </form>
                    <form action="{{ route('usuarios.rechazar', $usuario->id) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('PUT')
                    <button type="submit">Rechazar</button>
                </li>
                
            @endforeach
        </ul>
    @endif

    <hr>

    <h1>Usuarios Aceptados</h1>
    @if($aceptados->isEmpty())
        <p>No hay usuarios aceptados.</p>
    @else
        <ul>
            @foreach ($aceptados as $usuario)
                <li>{{ $usuario->nombre }}  {{$usuario->apellido}} - {{ $usuario->email }}</li>
            @endforeach
        </ul>
    @endif

    <h1>Usuarios Rechazados</h1>
    @if($rechazados->isEmpty())
        <p>No hay usuarios rechazados.</p>
    @else
        <ul>
            @foreach ($rechazados as $usuario)
                <li>{{ $usuario->nombre }}  {{$usuario->apellido}} - {{ $usuario->email }}</li>
            @endforeach
        </ul>
    @endif
</body>
</html>
