<!DOCTYPE html>
<html>
<head>
    <title>Back Office - Solicitudes</title>
</head>
<body>
    <h1>Solicitudes de Registro</h1>
    <table border="1" cellpadding="10">
        <tr>
            <th>Nombre Completo</th>
            <th>Cédula</th>
            <th>Celular</th>
            <th>Fecha de Nacimiento</th>
            <th>Correo</th>
            <th>Nacionalidad</th>
            <th>Estado Civil</th>
            <th>Ingresos Totales</th>
            <th>Estado</th>
            <th>Acciones</th>
        </tr>
        @foreach($solicitudes as $solicitud)
            <tr>
                <td>{{ $solicitud->nombre_completo }}</td>
                <td>{{ $solicitud->cedula }}</td>
                <td>{{ $solicitud->celular }}</td>
                <td>{{ $solicitud->fecha_nacimiento }}</td>
                <td>{{ $solicitud->correo }}</td>
                <td>{{ $solicitud->nacionalidad }}</td>
                <td>{{ $solicitud->estado_civil }}</td>
                <td>{{ $solicitud->ingresos_totales }}</td>
                <td>{{ $solicitud->estado }}</td>
                <td>
                    @if($solicitud->estado === 'pendiente')
                        <form action="/backoffice/{{ $solicitud->id }}/aceptar" method="POST" style="display:inline;">
                            @csrf
                            <button type="submit">Aceptar</button>
                        </form>
                        <form action="/backoffice/{{ $solicitud->id }}/rechazar" method="POST" style="display:inline;">
                            @csrf
                            <button type="submit">Rechazar</button>
                        </form>
                    @else
                        {{ ucfirst($solicitud->estado) }}
                    @endif
                </td>
            </tr>
        @endforeach
    </table>
</body>
</html>
