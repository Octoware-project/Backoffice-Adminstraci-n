/**
 * TABLA INTERACTIONS JavaScript - Interacciones de la tabla de usuarios
 */

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
                    window.location.href = `/admin/usuarios/${usuarioId}`;
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
        
        if (confirm(`¿Estás seguro de que quieres ACEPTAR a ${userName}? El usuario pasará al estado "Inactivo".`)) {
            this.showLoading(button, 'Aceptando...');
            this.sendAjaxRequest('aceptar', userId, button);
        }
    },

    handleReject(e) {
        e.preventDefault();
        e.stopPropagation();
        
        const button = e.target.closest('button');
        const userId = button.getAttribute('data-user-id');
        const userName = button.getAttribute('data-user-name') || 'este usuario';
        
        if (confirm(`¿Estás seguro de que quieres RECHAZAR a ${userName}? Esta acción se puede revertir.`)) {
            this.showLoading(button, 'Rechazando...');
            this.sendAjaxRequest('rechazar', userId, button);
        }
    },

    sendAjaxRequest(action, userId, button) {
        // Obtener el token CSRF
        const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        
        if (!token) {
            alert('Error: Token CSRF no encontrado');
            this.restoreButton(button);
            return;
        }

        fetch(`/admin/usuarios/${userId}/${action}`, {
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
            console.error('Error:', error);
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
            window.location.href = `/admin/usuarios/${usuarioId}`;
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