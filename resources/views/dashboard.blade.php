<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    @extends('layouts.app')

    @section('content')
        
        <style>
            .main-content {
                margin-left: 200px;
                padding: 40px 30px;
                background: #f4f6f8;
                min-height: 100vh;
                font-family: 'Segoe UI', Arial, sans-serif;
            }
            .dashboard-title {
                color: #2c3e50;
                font-size: 2rem;
                margin-bottom: 18px;
                font-weight: 600;
            }
        </style>
        <div class="main-content">
            <div class="dashboard-title">
                Bienvenido, {{ Auth::user()->name ?? Auth::user()->email }}
            </div>
            <!-- Contenido del dashboard -->
        </div>
    @endsection
</body>
</html>
