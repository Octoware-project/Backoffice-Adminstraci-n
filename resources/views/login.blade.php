<!DOCTYPE html>
<html>
<head>
    <title>Login - Backoffice</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('css/Login.css') }}">
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

    {{-- Sistema de Modales de Confirmación --}}
    <script>
// Sistema de Modales de Confirmación - Estilo Asamblea
// Reutilizable para toda la aplicación

const ModalConfirmation = {
    /**
     * Crear modal de confirmación personalizado
     * @param {Object} options - Opciones del modal
     * @param {string} options.title - Título del modal
     * @param {string} options.message - Mensaje principal
     * @param {string} options.detail - Detalle adicional (opcional)
     * @param {string} options.warning - Mensaje de advertencia (opcional)
     * @param {string} options.confirmText - Texto del botón confirmar
     * @param {string} options.cancelText - Texto del botón cancelar
     * @param {string} options.iconClass - Clase del icono
     * @param {string} options.iconColor - Color del icono
     * @param {string} options.confirmColor - Color del botón confirmar
     * @param {Function} options.onConfirm - Función a ejecutar al confirmar
     */
    create(options = {}) {
        // Valores por defecto
        const defaults = {
            title: 'Confirmar Acción',
            message: '¿Está seguro que desea continuar?',
            detail: null,
            warning: 'Esta acción no se puede deshacer.',
            confirmText: 'Confirmar',
            cancelText: 'Cancelar',
            iconClass: 'fas fa-exclamation-triangle',
            iconColor: '#dc2626',
            confirmColor: '#dc2626',
            onConfirm: () => {}
        };

        const config = { ...defaults, ...options };

        // Verificar si ya existe un modal y eliminarlo
        const existingModal = document.querySelector('.confirmation-modal');
        if (existingModal) {
            this.close();
            return;
        }

        // Crear modal de confirmación personalizado con estilos inline
        const modal = document.createElement('div');
        modal.className = 'confirmation-modal';
        modal.style.cssText = `
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 99999;
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: opacity 0.2s ease;
        `;

        modal.innerHTML = `
            <div class="confirmation-modal-overlay" onclick="ModalConfirmation.close()" style="
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0, 0, 0, 0.5);
                backdrop-filter: blur(4px);
            "></div>
            <div class="confirmation-modal-content" style="
                background: white;
                border-radius: 12px;
                padding: 0;
                max-width: 400px;
                width: 90%;
                position: relative;
                z-index: 1;
                box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
                transform: scale(0.95) translateY(-10px);
                transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            ">
                <div class="confirmation-modal-header" style="
                    padding: 1.5rem;
                    text-align: center;
                    border-bottom: 1px solid #f1f5f9;
                ">
                    <div class="confirmation-icon" style="
                        width: 60px;
                        height: 60px;
                        border-radius: 50%;
                        background: #fef2f2;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        margin: 0 auto 1rem;
                    ">
                        <i class="${config.iconClass}" style="
                            font-size: 1.5rem;
                            color: ${config.iconColor};
                        "></i>
                    </div>
                    <h3 style="
                        margin: 0;
                        color: #1f2937;
                        font-size: 1.125rem;
                        font-weight: 600;
                        font-family: inherit;
                    ">${config.title}</h3>
                </div>
                <div class="confirmation-modal-body" style="
                    padding: 1.5rem;
                    text-align: center;
                ">
                    <p style="
                        margin: 0 0 0.5rem 0;
                        color: #4b5563;
                        font-family: inherit;
                    ">${config.message}</p>
                    ${config.detail ? `<strong style="
                        color: #1f2937;
                        font-weight: 600;
                        font-family: inherit;
                    ">${config.detail}</strong>` : ''}
                    ${config.warning ? `<p class="warning-text" style="
                        color: ${config.iconColor};
                        font-size: 0.875rem;
                        margin: 1rem 0 0 0;
                        font-family: inherit;
                    ">${config.warning}</p>` : ''}
                </div>
                <div class="confirmation-modal-actions" style="
                    padding: 1rem 1.5rem 1.5rem;
                    display: flex;
                    gap: 0.75rem;
                    justify-content: flex-end;
                ">
                    <button class="btn-cancel" onclick="ModalConfirmation.close()" style="
                        padding: 0.75rem 1.5rem;
                        border: 1px solid #d1d5db;
                        border-radius: 6px;
                        font-weight: 600;
                        font-size: 0.875rem;
                        cursor: pointer;
                        transition: all 0.2s ease;
                        min-width: 80px;
                        background: #f9fafb;
                        color: #374151;
                        font-family: inherit;
                    ">${config.cancelText}</button>
                    <button class="btn-confirm" onclick="ModalConfirmation.confirm()" style="
                        padding: 0.75rem 1.5rem;
                        border: none;
                        border-radius: 6px;
                        font-weight: 600;
                        font-size: 0.875rem;
                        cursor: pointer;
                        transition: all 0.2s ease;
                        min-width: 80px;
                        background: ${config.confirmColor};
                        color: white;
                        font-family: inherit;
                    ">${config.confirmText}</button>
                </div>
            </div>
        `;

        // Agregar modal al DOM
        document.body.appendChild(modal);
        document.body.style.overflow = 'hidden';

        // Guardar callback
        this._currentCallback = config.onConfirm;

        // Animar entrada del modal
        setTimeout(() => {
            modal.style.opacity = '1';
            const content = modal.querySelector('.confirmation-modal-content');
            content.style.transform = 'scale(1) translateY(0)';
        }, 10);

        // Event listeners para los botones con hover effects
        const cancelBtn = modal.querySelector('.btn-cancel');
        const confirmBtn = modal.querySelector('.btn-confirm');

        cancelBtn.addEventListener('mouseenter', function() {
            this.style.background = '#f3f4f6';
        });
        cancelBtn.addEventListener('mouseleave', function() {
            this.style.background = '#f9fafb';
        });

        confirmBtn.addEventListener('mouseenter', function() {
            const darkerColor = this.style.background === '#dc2626' ? '#b91c1c' :
                              this.style.background === '#059669' ? '#047857' :
                              this.style.background === '#d97706' ? '#b45309' :
                              '#1f2937';
            this.style.background = darkerColor;
        });
        confirmBtn.addEventListener('mouseleave', function() {
            this.style.background = config.confirmColor;
        });

        return modal;
    },

    close() {
        const modal = document.querySelector('.confirmation-modal');

        if (modal) {
            // Animación de salida suave
            modal.style.opacity = '0';
            const content = modal.querySelector('.confirmation-modal-content');
            if (content) {
                content.style.transform = 'scale(0.95) translateY(-10px)';
            }

            setTimeout(() => {
                if (modal && modal.parentNode) {
                    modal.remove();
                }
            }, 200);
        }

        // Restaurar overflow del body
        document.body.style.overflow = '';
        document.body.removeAttribute('style');

        // Limpiar callback
        this._currentCallback = null;
    },

    confirm() {
        if (this._currentCallback && typeof this._currentCallback === 'function') {
            this._currentCallback();
        }
        this.close();
    },

    // Métodos de conveniencia para tipos específicos de confirmación
    confirmDelete(itemName, onConfirm) {
        return this.create({
            title: 'Confirmar Eliminación',
            message: '¿Está seguro que desea eliminar:',
            detail: `"${itemName}"`,
            warning: 'Esta acción no se puede deshacer.',
            confirmText: 'Eliminar',
            cancelText: 'Cancelar',
            iconClass: 'fas fa-trash-alt',
            iconColor: '#dc2626',
            confirmColor: '#dc2626',
            onConfirm: onConfirm
        });
    },

    confirmRestore(itemName, onConfirm) {
        return this.create({
            title: 'Confirmar Restauración',
            message: '¿Está seguro que desea restaurar:',
            detail: `"${itemName}"`,
            warning: 'El elemento volverá a estar disponible en el sistema.',
            confirmText: 'Restaurar',
            cancelText: 'Cancelar',
            iconClass: 'fas fa-undo',
            iconColor: '#059669',
            confirmColor: '#059669',
            onConfirm: onConfirm
        });
    },

    confirmAction(title, message, detail, onConfirm) {
        return this.create({
            title: title,
            message: message,
            detail: detail,
            warning: null,
            confirmText: 'Continuar',
            cancelText: 'Cancelar',
            iconClass: 'fas fa-exclamation-circle',
            iconColor: '#d97706',
            confirmColor: '#d97706',
            onConfirm: onConfirm
        });
    }
};

// Cerrar modal con tecla Escape
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        ModalConfirmation.close();
    }
});

// Exportar para uso global
window.ModalConfirmation = ModalConfirmation;
    </script>

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
        ModalConfirmation.create({
            title: 'Error de Validación',
            message: 'Las contraseñas no coinciden. Por favor, verifica que ambas contraseñas sean iguales.',
            type: 'error',
            showCancelButton: false
        });
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
