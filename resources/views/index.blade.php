<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Personas Pendientes</title>
    <style>
        body {
            font-family: sans-serif;
        }
        table {
            border-collapse: collapse;
            width: 95%;
            margin: 20px auto;
        }
        th, td {
            border: 1px solid #444;
            padding: 8px 10px;
            text-align: center;
        }
        th {
            background-color: #ddd;
        }
        h1 {
            text-align: center;
        }
    </style>
</head>
<body>
    <h1>Personas con Estado Pendiente</h1>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre Completo</th>
                <th>Cédula</th>
                <th>Celular</th>
                <th>Fecha de Nacimiento</th>
                <th>Correo</th>
                <th>Nacionalidad</th>
                <th>Estado Civil</th>
                <th>Ingresos Totales</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($personas as $persona)
                <tr>
                    <td>{{ $persona['ID'] }}</td>
                    <td>{{ $persona['Nombre_Completo'] }}</td>
                    <td>{{ $persona['Cedula'] }}</td>
                    <td>{{ $persona['Celular'] }}</td>
                    <td>{{ $persona['Fecha_nacimiento'] }}</td>
                    <td>{{ $persona['Correo'] }}</td>
                    <td>{{ $persona['Nacionalidad'] }}</td>
                    <td>{{ $persona['Estado_civil'] }}</td>
                    <td>{{ $persona['Ingresos_totales'] }}</td>
                    <td>{{ $persona['Estado'] }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="10">No hay personas pendientes.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
