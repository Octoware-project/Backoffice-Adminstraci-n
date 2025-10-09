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