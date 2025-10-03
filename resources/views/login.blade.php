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
            justify-content: center;
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
        .password-toggle {
            position: relative;
        }
        .password-toggle .toggle-password {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #667eea;
            user-select: none;
            z-index: 10;
            width: 20px;
            height: 20px;
            opacity: 0.7;
            transition: opacity 0.2s ease;
        }
        .password-toggle .toggle-password:hover {
            opacity: 1;
        }
        .password-toggle .toggle-password svg {
            width: 100%;
            height: 100%;
            fill: currentColor;
        }
        #password-match-message {
            font-size: 0.875rem;
            font-weight: 500;
        }
        #password-match-message.match {
            color: #28a745;
        }
        #password-match-message.no-match {
            color: #dc3545;
        }
    </style>
</head>
<body>
            <div class="login-container">
                <h2>Backoffice Login</h2>
                <form method="POST" action="{{ url('/login') }}" id="loginForm" onsubmit="return validateForm(event)">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <div class="password-toggle">
                            <input type="password" name="password" id="password" class="form-control" required>
                            <span class="toggle-password" onclick="togglePassword()">
                                <svg id="eye-open" viewBox="0 0 24 24">
                                    <path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/>
                                </svg>
                                <svg id="eye-closed" viewBox="0 0 24 24" style="display: none;">
                                    <path d="M12 7c2.76 0 5 2.24 5 5 0 .65-.13 1.26-.36 1.83l2.92 2.92c1.51-1.26 2.7-2.89 3.43-4.75-1.73-4.39-6-7.5-11-7.5-1.4 0-2.74.25-3.98.7l2.16 2.16C10.74 7.13 11.35 7 12 7zM2 4.27l2.28 2.28.46.46C3.08 8.3 1.78 10.02 1 12c1.73 4.39 6 7.5 11 7.5 1.55 0 3.03-.3 4.38-.84l.42.42L19.73 22 21 20.73 3.27 3 2 4.27zM7.53 9.8l1.55 1.55c-.05.21-.08.43-.08.65 0 1.66 1.34 3 3 3 .22 0 .44-.03.65-.08l1.55 1.55c-.67.33-1.41.53-2.2.53-2.76 0-5-2.24-5-5 0-.79.2-1.53.53-2.2zm4.31-.78l3.15 3.15.02-.16c0-1.66-1.34-3-3-3l-.17.01z"/>
                                </svg>
                            </span>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Confirmar Password</label>
                        <div class="password-toggle">
                            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" required>
                            <span class="toggle-password" onclick="togglePasswordConfirmation()">
                                <svg id="eye-open-confirm" viewBox="0 0 24 24">
                                    <path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/>
                                </svg>
                                <svg id="eye-closed-confirm" viewBox="0 0 24 24" style="display: none;">
                                    <path d="M12 7c2.76 0 5 2.24 5 5 0 .65-.13 1.26-.36 1.83l2.92 2.92c1.51-1.26 2.7-2.89 3.43-4.75-1.73-4.39-6-7.5-11-7.5-1.4 0-2.74.25-3.98.7l2.16 2.16C10.74 7.13 11.35 7 12 7zM2 4.27l2.28 2.28.46.46C3.08 8.3 1.78 10.02 1 12c1.73 4.39 6 7.5 11 7.5 1.55 0 3.03-.3 4.38-.84l.42.42L19.73 22 21 20.73 3.27 3 2 4.27zM7.53 9.8l1.55 1.55c-.05.21-.08.43-.08.65 0 1.66 1.34 3 3 3 .22 0 .44-.03.65-.08l1.55 1.55c-.67.33-1.41.53-2.2.53-2.76 0-5-2.24-5-5 0-.79.2-1.53.53-2.2zm4.31-.78l3.15 3.15.02-.16c0-1.66-1.34-3-3-3l-.17.01z"/>
                                </svg>
                            </span>
                        </div>
                        <div id="password-match-message" class="mt-1" style="display: none;"></div>
                    </div>
                    @if($errors->any())
                        <div class="alert alert-danger">{{ $errors->first() }}</div>
                    @endif
                    <button type="submit" class="btn btn-primary w-100">Ingresar</button>
                </form>
            </div>

<script>
// Función para mostrar/ocultar contraseña
function togglePassword() {
    const passwordInput = document.getElementById('password');
    const eyeOpen = document.getElementById('eye-open');
    const eyeClosed = document.getElementById('eye-closed');
    
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        eyeOpen.style.display = 'none';
        eyeClosed.style.display = 'block';
    } else {
        passwordInput.type = 'password';
        eyeOpen.style.display = 'block';
        eyeClosed.style.display = 'none';
    }
}

// Función para mostrar/ocultar confirmación de contraseña
function togglePasswordConfirmation() {
    const passwordInput = document.getElementById('password_confirmation');
    const eyeOpen = document.getElementById('eye-open-confirm');
    const eyeClosed = document.getElementById('eye-closed-confirm');
    
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        eyeOpen.style.display = 'none';
        eyeClosed.style.display = 'block';
    } else {
        passwordInput.type = 'password';
        eyeOpen.style.display = 'block';
        eyeClosed.style.display = 'none';
    }
}

// Función para validar que las contraseñas coincidan
function validatePasswordMatch() {
    const password = document.getElementById('password').value;
    const passwordConfirmation = document.getElementById('password_confirmation').value;
    const message = document.getElementById('password-match-message');
    
    if (passwordConfirmation === '') {
        message.style.display = 'none';
        return;
    }
    
    message.style.display = 'block';
    
    if (password === passwordConfirmation) {
        message.textContent = 'Las contraseñas coinciden';
        message.className = 'mt-1 match';
    } else {
        message.textContent = 'Las contraseñas no coinciden';
        message.className = 'mt-1 no-match';
    }
}

// Función para validar el formulario antes de enviar
function validateForm(event) {
    const password = document.getElementById('password').value;
    const passwordConfirmation = document.getElementById('password_confirmation').value;
    
    if (password !== passwordConfirmation) {
        event.preventDefault();
        alert('Las contraseñas no coinciden. Por favor, verifica que ambas contraseñas sean iguales.');
        return false;
    }
    return true;
}

// Verificación adicional del lado del cliente
document.addEventListener('DOMContentLoaded', function() {
    // Agregar event listeners para validación en tiempo real
    const passwordInput = document.getElementById('password');
    const passwordConfirmationInput = document.getElementById('password_confirmation');
    
    passwordInput.addEventListener('input', validatePasswordMatch);
    passwordConfirmationInput.addEventListener('input', validatePasswordMatch);
    
    // Si hay indicadores de sesión activa en el navegador, intentar redirección
    @auth
        // El usuario está autenticado según Laravel, redirigir inmediatamente
        window.location.href = '{{ route("dashboard") }}';
    @endauth
});
</script>

</body>
</html>
