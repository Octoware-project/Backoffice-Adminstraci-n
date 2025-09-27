<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Cooperativa ESI</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        * {
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background: #f4f6f8;
            margin: 0;
            overflow-x: hidden; /* Prevenir scroll horizontal */
        }
        
        .main-content {
            margin-left: 240px;
            padding: 20px 30px;
            min-height: 100vh;
            transition: margin-left 0.3s ease;
            width: calc(100% - 240px); /* Ancho específico para evitar overflow */
            max-width: calc(100% - 240px);
            overflow-x: hidden; /* Prevenir scroll horizontal en el contenido */
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
        
        /* Responsive design */
        @media (max-width: 1200px) {
            .main-content {
                padding: 15px 20px;
            }
        }
        
        @media (max-width: 768px) {
            .main-content {
                margin-left: 0;
                padding: 15px;
                width: 100%;
                max-width: 100%;
            }
        }
    </style>
    <!-- Puedes agregar aquí tus estilos globales -->
    
    {{-- Stack para estilos adicionales --}}
    @stack('styles')
    
    {{-- Stack para scripts en el head --}}
    @stack('head-scripts')
</head>
<body>
    @include('componentes.navbar')
    <div class="main-content">
        @yield('content')
    </div>
    
    {{-- Stack para scripts al final del body --}}
    @stack('scripts')
</body>
</html>
