/**
 * USUARIOS INDEX - JavaScript Principal
 * Página de gestión de usuarios con filtros y acciones
 */

document.addEventListener('DOMContentLoaded', function() {
    // Aplicar clase al body para estilos específicos
    document.body.classList.add('usuarios-page');
    
    // Inicializar todos los módulos
    if (typeof FiltersManager !== 'undefined') {
        FiltersManager.init();
        FiltersManager.setupOrderToggle(); // Nueva función para el botón de orden
    }
    
    if (typeof TableInteractions !== 'undefined') {
        TableInteractions.init();
    }
    
    ResponsiveManager.init();
    NotificationsManager.init();
});

/**
 * Manager Responsive
 */
const ResponsiveManager = {
    init() {
        this.setupMobileOptimizations();
        this.handleResize();
        window.addEventListener('resize', () => this.handleResize());
    },

    setupMobileOptimizations() {
        if (window.innerWidth <= 768) {
            // Ocultar columnas menos importantes en móvil
            const hideMobileElements = document.querySelectorAll('.hide-mobile');
            hideMobileElements.forEach(el => el.style.display = 'none');
            
            // Ajustar filtros para móvil
            const filtersGrid = document.querySelector('.filters-grid');
            if (filtersGrid) {
                filtersGrid.style.gridTemplateColumns = '1fr';
            }
        }
    },

    handleResize() {
        const isMobile = window.innerWidth <= 768;
        const hideMobileElements = document.querySelectorAll('.hide-mobile');
        
        hideMobileElements.forEach(el => {
            el.style.display = isMobile ? 'none' : '';
        });
    }
};

/**
 * Manager de Notificaciones
 */
const NotificationsManager = {
    init() {
        this.checkForMessages();
        this.setupAutoHide();
    },

    checkForMessages() {
        // Verificar si hay mensajes de éxito de Laravel
        const successMessage = document.querySelector('.success-message');
        if (successMessage) {
            this.showNotification('success');
        }
    },

    setupAutoHide() {
        // Auto-ocultar mensajes después de 5 segundos
        const messages = document.querySelectorAll('.success-message, .error-message');
        messages.forEach(message => {
            setTimeout(() => {
                message.style.opacity = '0';
                setTimeout(() => {
                    if (message.parentNode) {
                        message.parentNode.removeChild(message);
                    }
                }, 300);
            }, 5000);
        });
    },

    showNotification(type, message = null) {
        if (message) {
            const toast = document.createElement('div');
            toast.className = `toast toast-${type}`;
            toast.innerHTML = `
                <div class="toast-content">
                    <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i>
                    <span>${message}</span>
                </div>
            `;
            
            document.body.appendChild(toast);
            
            // Mostrar toast
            setTimeout(() => toast.classList.add('show'), 100);
            
            // Ocultar toast después de 3 segundos
            setTimeout(() => {
                toast.classList.remove('show');
                setTimeout(() => {
                    if (document.body.contains(toast)) {
                        document.body.removeChild(toast);
                    }
                }, 300);
            }, 3000);
        }
    }
};

/**
 * Utilidades
 */
const UsuariosUtils = {
    // Formatear fecha
    formatDate(dateString) {
        const options = { 
            year: 'numeric', 
            month: 'short', 
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        };
        return new Date(dateString).toLocaleDateString('es-ES', options);
    },

    // Generar iniciales para avatar
    getInitials(name, apellido = '') {
        const firstInitial = name ? name.charAt(0).toUpperCase() : 'U';
        const lastInitial = apellido ? apellido.charAt(0).toUpperCase() : '';
        return firstInitial + lastInitial;
    },

    // Validar email
    isValidEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    },

    // Debounce function
    debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }
};

// Exportar utilidades para uso global
window.UsuariosUtils = UsuariosUtils;
window.ResponsiveManager = ResponsiveManager;
window.NotificationsManager = NotificationsManager;