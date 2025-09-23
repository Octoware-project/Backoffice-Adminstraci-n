<!DOCTYPE html>
<html>
<head>
    <title>Login - Backoffice</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
        }
        .login-container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            padding: 2rem;
            max-width: 400px;
            width: 100%;
        }
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 10px;
            padding: 12px;
        }
        .form-control {
            border-radius: 10px;
            border: 2px solid #e3f2fd;
            padding: 12px;
        }
        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
        h2 {
            color: #333;
            font-weight: 700;
            margin-bottom: 1.5rem;
            text-align: center;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="login-container">
                <h2>Backoffice Login</h2>
                <form method="POST" action="{{ url('/login') }}" id="loginForm">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    @if($errors->any())
                        <div class="alert alert-danger">{{ $errors->first() }}</div>
                    @endif
                    <button type="submit" class="btn btn-primary w-100">Ingresar</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
// Verificación adicional del lado del cliente
document.addEventListener('DOMContentLoaded', function() {
    // Si hay indicadores de sesión activa en el navegador, intentar redirección
    @auth
        // El usuario está autenticado según Laravel, redirigir inmediatamente
        window.location.href = '{{ route("dashboard") }}';
    @endauth
});
</script>

</body>
</html>
