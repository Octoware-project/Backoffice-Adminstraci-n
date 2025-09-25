/**
 * ===================================
 * CONFIGURACIÓN HORAS - JAVASCRIPT
 * ===================================
 */

document.addEventListener('DOMContentLoaded', function() {
    // Inicializar funcionalidades
    initObservacionesCounter();
    initButtonEnhancements();
    initFormValidation();
});

/**
 * Contador de caracteres para observaciones
 */
function initObservacionesCounter() {
    const textarea = document.getElementById('observaciones');
    const charCount = document.getElementById('char-count');
    const counter = document.querySelector('.observaciones-counter');
    
    if (!textarea || !charCount) return;
    
    // Actualizar contador inicial
    updateCounter();
    
    // Escuchar cambios en el textarea
    textarea.addEventListener('input', updateCounter);
    textarea.addEventListener('paste', function() {
        setTimeout(updateCounter, 10);
    });
    
    function updateCounter() {
        const currentLength = textarea.value.length;
        const maxLength = parseInt(textarea.getAttribute('maxlength'));
        
        charCount.textContent = currentLength;
        
        // Cambiar color del contador según la proximidad al límite
        counter.classList.remove('warning', 'danger');
        
        if (currentLength >= maxLength * 0.9) {
            counter.classList.add('danger');
        } else if (currentLength >= maxLength * 0.7) {
            counter.classList.add('warning');
        }
        
        // Prevenir entrada de más caracteres si se alcanza el límite
        if (currentLength >= maxLength) {
            textarea.value = textarea.value.substring(0, maxLength);
            charCount.textContent = maxLength;
        }
    }
    
    // Auto-resize del textarea
    textarea.addEventListener('keydown', function(e) {
        this.style.height = 'auto';
        this.style.height = Math.min(this.scrollHeight, 150) + 'px';
    });
    
    textarea.addEventListener('input', function() {
        this.style.height = 'auto';
        this.style.height = Math.min(this.scrollHeight, 150) + 'px';
    });
}

/**
 * Mejoras del botón de guardar
 */
function initButtonEnhancements() {
    const btnGuardar = document.getElementById('btn-guardar');
    const form = btnGuardar?.closest('form');
    
    if (!btnGuardar || !form) return;
    
    form.addEventListener('submit', function(e) {
        // Cambiar a estado de carga
        btnGuardar.classList.add('loading');
        
        // Cambiar texto e icono
        const icon = btnGuardar.querySelector('i');
        
        icon.className = 'fas fa-spinner';
        btnGuardar.innerHTML = '<i class="fas fa-spinner"></i> Guardando...';
        
        // Deshabilitar el botón
        btnGuardar.disabled = true;
        
        // Si hay error en la validación, restaurar el botón
        setTimeout(() => {
            if (document.querySelector('.text-danger')) {
                restoreButton(btnGuardar);
            }
        }, 100);
    });
}

/**
 * Validación en tiempo real
 */
function initFormValidation() {
    const valorInput = document.getElementById('valor_por_hora');
    const btnGuardar = document.getElementById('btn-guardar');
    
    if (!valorInput || !btnGuardar) return;
    
    valorInput.addEventListener('input', function() {
        const value = parseFloat(this.value);
        
        if (this.value === '' || value <= 0) {
            btnGuardar.style.opacity = '0.5';
            btnGuardar.style.pointerEvents = 'none';
        } else {
            btnGuardar.style.opacity = '1';
            btnGuardar.style.pointerEvents = 'auto';
        }
    });
    
    // Validar inicial
    valorInput.dispatchEvent(new Event('input'));
}

/**
 * Restaurar botón a su estado original
 */
function restoreButton(btnGuardar) {
    btnGuardar.classList.remove('loading');
    btnGuardar.disabled = false;
    btnGuardar.innerHTML = '<i class="fas fa-save"></i> Guardar Nueva Configuración';
}

/**
 * Mostrar notificación de éxito
 */
function showSuccessNotification(message) {
    // Crear elemento de notificación
    const notification = document.createElement('div');
    notification.className = 'alert alert-success';
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 9999;
        max-width: 400px;
        animation: slideIn 0.3s ease;
    `;
    notification.innerHTML = `
        <i class="fas fa-check-circle"></i>
        ${message}
    `;
    
    // Agregar al DOM
    document.body.appendChild(notification);
    
    // Remover después de 3 segundos
    setTimeout(() => {
        notification.style.animation = 'slideOut 0.3s ease';
        setTimeout(() => {
            notification.remove();
        }, 300);
    }, 3000);
}

// Agregar animaciones CSS
const style = document.createElement('style');
style.textContent = `
    @keyframes slideIn {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    
    @keyframes slideOut {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(100%);
            opacity: 0;
        }
    }
`;
document.head.appendChild(style);