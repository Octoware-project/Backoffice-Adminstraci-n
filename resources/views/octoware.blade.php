@extends('layouts.app')

@section('content')
    @include('componentes.navbar')
    <style>
        body, .main-content {
            font-family: 'Segoe UI', Arial, sans-serif;
        }
        .main-content {
            margin-left: 200px;
            padding: 40px 30px;
            background: #f4f6f8;
            min-height: 100vh;
        }
        .octoware-title {
            color: #2c3e50;
            font-size: 2.2rem;
            margin-bottom: 18px;
            font-weight: 600;
        }
        .octoware-contact {
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(44,62,80,0.07);
            padding: 24px 28px;
            max-width: 420px;
        }
        .octoware-contact ul {
            padding-left: 18px;
        }
        .octoware-contact li {
            margin-bottom: 10px;
            color: #34495e;
        }
    </style>
    <div class="main-content">
        <div class="octoware-contact">
            <div class="octoware-title">Octoware</div>
            <p>Información de contacto:</p>
            <ul>
                <li>Email: contacto@octoware.com</li>
                <li>Teléfono: +34 123 456 789</li>
                <li>Dirección: Calle Ejemplo 123, Ciudad, País</li>
            </ul>
        </div>
    </div>
@endsection
