// Función para confirmar eliminación
function confirmDeleteJunta(juntaId, lugar) {
    const modal = document.createElement('div');
    modal.className = 'delete-modal';
    modal.style.cssText = `
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 10000;
        opacity: 0;
        transition: opacity 0.2s ease;
        backdrop-filter: blur(4px);
    `;
    
    modal.innerHTML = `
        <div class="delete-modal-content" style="
            background: white;
            padding: 2rem;
            border-radius: 16px;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            max-width: 400px;
            width: 90%;
            text-align: center;
            transform: scale(0.95) translateY(-10px);
            transition: all 0.2s ease;
        ">
            <div style="
                width: 64px;
                height: 64px;
                background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                margin: 0 auto 1rem;
            ">
                <i class="fas fa-exclamation-triangle" style="font-size: 24px; color: #dc2626;"></i>
            </div>
            <h3 style="margin: 0 0 0.5rem 0; color: #1a202c; font-size: 1.25rem; font-weight: 700;">
                ¿Eliminar Junta?
            </h3>
            <p style="margin: 0 0 2rem 0; color: #4a5568; font-size: 0.875rem; line-height: 1.5;">
                Esta acción eliminará permanentemente la junta "<strong>${lugar}</strong>" del sistema. 
                Esta acción no se puede deshacer.
            </p>
            <div style="display: flex; gap: 0.75rem; justify-content: center;">
                <button onclick="closeDeleteModal()" style="
                    padding: 0.75rem 1.5rem;
                    border: 1px solid #d1d5db;
                    border-radius: 8px;
                    background: #f9fafb;
                    color: #374151;
                    font-weight: 600;
                    cursor: pointer;
                    transition: all 0.2s ease;
                " class="btn-cancel">
                    Cancelar
                </button>
                <button onclick="deleteJunta(${juntaId})" style="
                    padding: 0.75rem 1.5rem;
                    border: none;
                    border-radius: 8px;
                    background: #dc2626;
                    color: white;
                    font-weight: 600;
                    cursor: pointer;
                    transition: all 0.2s ease;
                " class="btn-confirm">
                    Eliminar Junta
                </button>
            </div>
        </div>
    `;
    
    document.body.appendChild(modal);
    document.body.style.overflow = 'hidden';
    
    setTimeout(() => {
        modal.style.opacity = '1';
        const content = modal.querySelector('.delete-modal-content');
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
        this.style.background = '#b91c1c';
    });
    confirmBtn.addEventListener('mouseleave', function() {
        this.style.background = '#dc2626';
    });
}

function closeDeleteModal() {
    const modal = document.querySelector('.delete-modal');
    
    if (modal) {
        modal.style.opacity = '0';
        const content = modal.querySelector('.delete-modal-content');
        if (content) {
            content.style.transform = 'scale(0.95) translateY(-10px)';
        }
        
        setTimeout(() => {
            if (modal && modal.parentNode) {
                modal.remove();
            }
        }, 200);
    }
    
    document.body.style.overflow = '';
    document.body.removeAttribute('style');
}

function deleteJunta(juntaId) {
    const form = document.getElementById('delete-form');
    form.action = `${window.asambleaConfig.deleteUrl}/${juntaId}`;
    form.submit();
}

// Cerrar modal con tecla Escape
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeDeleteModal();
    }
});