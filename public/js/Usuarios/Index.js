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

const TableInteractions = {
    init() {
        this.bindRowClicks();
        this.setupHoverEffects();
        this.bindActionButtons();
    },

    bindRowClicks() {
        const rows = document.querySelectorAll('.usuario-row');
        rows.forEach(row => {
            row.addEventListener('click', (e) => {
                // No navegar si se hizo click en un botón o formulario
                if (e.target.closest('.action-btn') || 
                    e.target.closest('button') || 
                    e.target.closest('form')) {
                    return;
                }
                
                const usuarioId = row.getAttribute('data-usuario-id');
                if (usuarioId) {
                    window.location.href = `/usuarios/${usuarioId}`;
                }
            });
        });
    },

    setupHoverEffects() {
        const rows = document.querySelectorAll('.modern-table tbody tr');
        rows.forEach(row => {
            row.addEventListener('mouseenter', function() {
                this.style.cursor = 'pointer';
            });
        });
    },

    bindActionButtons() {
        // Botones de aceptar
        const acceptButtons = document.querySelectorAll('.btn-accept');
        acceptButtons.forEach(btn => {
            btn.addEventListener('click', (e) => this.handleAccept(e));
        });

        // Botones de rechazar
        const rejectButtons = document.querySelectorAll('.btn-reject');
        rejectButtons.forEach(btn => {
            btn.addEventListener('click', (e) => this.handleReject(e));
        });

        // Botones de ver
        const viewButtons = document.querySelectorAll('.btn-view');
        viewButtons.forEach(btn => {
            btn.addEventListener('click', (e) => this.handleView(e));
        });
    },

    handleAccept(e) {
        e.preventDefault();
        e.stopPropagation();
        
        const button = e.target.closest('button');
        const userId = button.getAttribute('data-user-id');
        const userName = button.getAttribute('data-user-name') || 'este usuario';
        
        ModalConfirmation.create({
            title: 'Confirmar Aceptación',
            message: `¿Estás seguro de que quieres ACEPTAR a ${userName}? El usuario pasará al estado "Inactivo".`,
            type: 'success',
            iconClass: 'fas fa-user-check',
            iconColor: '#059669',
            confirmColor: '#059669',
            confirmText: 'Aceptar Usuario',
            onConfirm: () => {
                this.showLoading(button, 'Aceptando...');
                this.sendAjaxRequest('aceptar', userId, button);
            }
        });
    },

    handleReject(e) {
        e.preventDefault();
        e.stopPropagation();
        
        const button = e.target.closest('button');
        const userId = button.getAttribute('data-user-id');
        const userName = button.getAttribute('data-user-name') || 'este usuario';
        
        ModalConfirmation.create({
            title: 'Confirmar Rechazo',
            message: `¿Estás seguro de que quieres RECHAZAR a ${userName}? Esta acción se puede revertir.`,
            type: 'warning',
            onConfirm: () => {
                this.showLoading(button, 'Rechazando...');
                this.sendAjaxRequest('rechazar', userId, button);
            }
        });
    },

    sendAjaxRequest(action, userId, button) {
        // Obtener el token CSRF
        const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        
        if (!token) {
            ModalConfirmation.create({
                title: 'Error del Sistema',
                message: 'Error: Token CSRF no encontrado',
                type: 'error',
                showCancelButton: false
            });
            this.restoreButton(button);
            return;
        }

        fetch(`/usuarios/${userId}/${action}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': token,
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({})
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Mostrar mensaje de éxito
                this.showNotification('success', data.message);
                // Recargar la página para actualizar la vista
                setTimeout(() => {
                    window.location.reload();
                }, 1500);
            } else {
                this.showNotification('error', data.message || 'Error al procesar la solicitud');
                this.restoreButton(button);
            }
        })
        .catch(error => {
            this.showNotification('error', 'Error de conexión. Intenta nuevamente.');
            this.restoreButton(button);
        });
    },

    restoreButton(button) {
        button.disabled = false;
        const originalContent = button.getAttribute('data-original-content');
        if (originalContent) {
            button.innerHTML = originalContent;
            button.removeAttribute('data-original-content');
        }
        button.style.opacity = '1';
    },

    showNotification(type, message) {
        // Crear notificación temporal
        const notification = document.createElement('div');
        notification.className = `notification notification-${type}`;
        notification.innerHTML = `
            <div class="notification-content">
                <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i>
                <span>${message}</span>
            </div>
        `;
        
        // Estilos inline para la notificación
        Object.assign(notification.style, {
            position: 'fixed',
            top: '20px',
            right: '20px',
            padding: '12px 20px',
            borderRadius: '6px',
            color: 'white',
            backgroundColor: type === 'success' ? '#10B981' : '#EF4444',
            boxShadow: '0 4px 6px rgba(0, 0, 0, 0.1)',
            zIndex: '9999',
            fontSize: '14px',
            fontWeight: '500'
        });
        
        document.body.appendChild(notification);
        
        // Remover después de 4 segundos
        setTimeout(() => {
            if (document.body.contains(notification)) {
                document.body.removeChild(notification);
            }
        }, 4000);
    },

    handleView(e) {
        e.preventDefault();
        e.stopPropagation();
        
        const usuarioId = e.target.getAttribute('data-usuario-id') || 
                         e.target.closest('a').getAttribute('href').split('/').pop();
        if (usuarioId) {
            window.location.href = `/usuarios/${usuarioId}`;
        }
    },

    showLoading(button, message = 'Procesando...') {
        button.disabled = true;
        const originalContent = button.innerHTML;
        button.setAttribute('data-original-content', originalContent);
        button.innerHTML = `<i class="fas fa-spinner fa-spin"></i> ${message}`;
        button.style.opacity = '0.6';
    }
};

// Exportar para uso global
window.TableInteractions = TableInteractions;
function toggleFilters() {
    const content = document.getElementById('filters-content');
    const chevron = document.getElementById('filter-icon');
    
    if (content.classList.contains('show')) {
        content.classList.remove('show');
        chevron.style.transform = 'rotate(0deg)';
    } else {
        content.classList.add('show');
        chevron.style.transform = 'rotate(180deg)';
    }
}

const FiltersManager = {
    elements: {
        filtersContent: null,
        filterIcon: null,
        filterForm: null,
        filterInputs: null,
        clearBtn: null,
        applyBtn: null
    },

    init() {
        this.bindElements();
        this.bindEvents();
        this.updateActiveFilters();
        this.showFiltersIfActive();
        this.setupDebounce();
    },

    bindElements() {
        this.elements.filtersContent = document.getElementById('filters-content');
        this.elements.filterIcon = document.getElementById('filter-icon');
        this.elements.filterForm = document.getElementById('filters-form');
        this.elements.filterInputs = document.querySelectorAll('.filter-input, .filter-select');
        this.elements.clearBtn = document.getElementById('clear-filters');
        this.elements.applyBtn = document.getElementById('apply-filters');
    },

    bindEvents() {
        // Limpiar filtros - ya no necesitamos evento del botón aquí
        
        // Aplicar filtros
        if (this.elements.applyBtn) {
            this.elements.applyBtn.addEventListener('click', () => this.applyFilters());
        }

        // Actualizar estilos cuando cambian los filtros
        this.elements.filterInputs.forEach(input => {
            input.addEventListener('change', () => this.updateActiveFilters());
            input.addEventListener('input', () => this.updateActiveFilters());
        });
    },

    clearFilters() {
        this.elements.filterInputs.forEach(input => {
            input.value = '';
            input.classList.remove('active');
        });
        
        // Redirigir a la URL sin parámetros
        window.location.href = window.location.pathname;
    },

    applyFilters() {
        if (this.elements.filterForm) {
            this.elements.filterForm.submit();
        }
    },

    updateActiveFilters() {
        this.elements.filterInputs.forEach(input => {
            if (input.value && input.value !== '') {
                input.classList.add('active');
            } else {
                input.classList.remove('active');
            }
        });
    },

    showFiltersIfActive() {
        // Mostrar filtros automáticamente si hay parámetros de búsqueda
        const urlParams = new URLSearchParams(window.location.search);
        const hasActiveFilters = Array.from(urlParams.keys()).some(key => 
            ['filter_nombre', 'filter_email', 'sort_field', 'sort_direction'].includes(key) && urlParams.get(key)
        );
        
        if (hasActiveFilters) {
            // Usar la función global toggleFilters si los filtros están cerrados
            const content = document.getElementById('filters-content');
            if (content && !content.classList.contains('show')) {
                toggleFilters();
            }
        }
    },

    setupDebounce() {
        // Aplicar debounce a los inputs de texto
        const textInputs = document.querySelectorAll('input[name="filter_nombre"], input[name="filter_email"]');
        textInputs.forEach(input => {
            let timeoutId;
            
            input.addEventListener('input', function() {
                clearTimeout(timeoutId);
                timeoutId = setTimeout(() => {
                    // Auto-submit después de 1 segundo de inactividad
                    if (input.value.length >= 2 || input.value.length === 0) {
                        // Solo auto-submit si hay al menos 2 caracteres o está vacío
                        const form = document.getElementById('filters-form');
                        if (form) {
                            form.submit();
                        }
                    }
                }, 1000); // 1 segundo de delay
            });
        });
    },

    // Nueva funcionalidad para el botón de invertir orden
    setupOrderToggle() {
        const orderButton = document.getElementById('toggle-order');
        const hiddenInput = document.getElementById('hidden_sort_direction');
        
        if (orderButton && hiddenInput) {
            orderButton.addEventListener('click', () => {
                // Obtener dirección actual
                const currentDirection = hiddenInput.value;
                const newDirection = currentDirection === 'desc' ? 'asc' : 'desc';
                
                // Actualizar valor oculto
                hiddenInput.value = newDirection;
                
                // Actualizar texto e icono del botón
                if (newDirection === 'desc') {
                    orderButton.innerHTML = '<i class="fas fa-arrow-up"></i> Invertir a Ascendente';
                } else {
                    orderButton.innerHTML = '<i class="fas fa-arrow-down"></i> Invertir a Descendente';
                }
                
                // Enviar formulario automáticamente
                const form = document.getElementById('filters-form');
                if (form) {
                    form.submit();
                }
            });
        }
    }
};

// Exportar para uso global
window.FiltersManager = FiltersManager;

// Mostrar filtros si hay parámetros de búsqueda (similar a facturas)
document.addEventListener('DOMContentLoaded', function() {
    const urlParams = new URLSearchParams(window.location.search);
    const hasFilters = Array.from(urlParams.keys()).some(key => 
        ['filter_nombre', 'filter_email', 'sort_field', 'sort_direction'].includes(key) && urlParams.get(key)
    );
    
    if (hasFilters) {
        const content = document.getElementById('filters-content');
        if (content && !content.classList.contains('show')) {
            toggleFilters();
        }
    }
});
    

        function confirmDeleteUsuario(usuarioId, nombreCompleto) {
            // Prevenir propagación del evento click
            event.stopPropagation();
            
            ModalConfirmation.create({
                title: 'Confirmar Eliminación de Usuario',
                message: '¿Está seguro que desea eliminar al usuario:',
                detail: `"${nombreCompleto}"`,
                warning: 'NOTA: No se puede eliminar si tiene una unidad habitacional asignada. Esta acción se puede revertir desde usuarios eliminados.',
                confirmText: 'Eliminar Usuario',
                cancelText: 'Cancelar',
                iconClass: 'fas fa-user-times',
                iconColor: '#dc2626',
                confirmColor: '#dc2626',
                onConfirm: function() {
                    document.getElementById(`delete-form-${usuarioId}`).submit();
                }
            });
        }