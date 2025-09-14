<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Cooperativa ESI</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background: #f4f6f8;
            margin: 0;
        }
        .main-content {
            margin-left: 200px;
            padding: 30px 40px;
        }
        h1 {
            color: #2c3e50;
            margin-bottom: 24px;
        }
        .btn, button.btn {
            background: #2c3e50;
            color: #fff;
            border: none;
            padding: 8px 18px;
            border-radius: 4px;
            cursor: pointer;
            transition: background 0.2s;
        }
        .btn:hover, button.btn:hover {
            background: #34495e;
        }
        .table {
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
        }
        .mb-3 { margin-bottom: 1rem; }
    </style>
    <!-- Puedes agregar aquí tus estilos globales -->
</head>
<body>
    @include('componentes.navbar')
    <div class="main-content">
        @yield('content')
    </div>
</body>
</html>
