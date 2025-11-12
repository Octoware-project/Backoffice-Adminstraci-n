function confirmDelete(juntaId, lugar) {
    // Prevenir propagación del evento click
    event.stopPropagation();
    
    // Verificar si ya existe un modal y eliminarlo
    const existingModal = document.querySelector('.delete-modal');
    if (existingModal) {
        closeDeleteModal();
        return;
    }
    
    // Crear modal de confirmación personalizado con estilos inline
    const modal = document.createElement('div');
    modal.className = 'delete-modal';
    modal.style.cssText = `
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: 9999;
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: opacity 0.2s ease;
    `;
    
    modal.innerHTML = `
        <div class="delete-modal-overlay" onclick="closeDeleteModal()" style="
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(4px);
        "></div>
        <div class="delete-modal-content" style="
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
            <div class="delete-modal-header" style="
                padding: 1.5rem;
                text-align: center;
                border-bottom: 1px solid #f1f5f9;
            ">
                <div class="delete-icon" style="
                    width: 60px;
                    height: 60px;
                    border-radius: 50%;
                    background: #fef2f2;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    margin: 0 auto 1rem;
                ">
                    <i class="fas fa-exclamation-triangle" style="
                        font-size: 1.5rem;
                        color: #dc2626;
                    "></i>
                </div>
                <h3 style="
                    margin: 0;
                    color: #1f2937;
                    font-size: 1.125rem;
                    font-weight: 600;
                    font-family: inherit;
                ">Confirmar Eliminación</h3>
            </div>
            <div class="delete-modal-body" style="
                padding: 1.5rem;
                text-align: center;
            ">
                <p style="
                    margin: 0 0 0.5rem 0;
                    color: #4b5563;
                    font-family: inherit;
                ">¿Está seguro que desea eliminar la junta:</p>
                <strong style="
                    color: #1f2937;
                    font-weight: 600;
                    font-family: inherit;
                ">"${lugar}"</strong>
                <p class="warning-text" style="
                    color: #dc2626;
                    font-size: 0.875rem;
                    margin: 1rem 0 0 0;
                    font-family: inherit;
                ">Esta acción no se puede deshacer.</p>
            </div>
            <div class="delete-modal-actions" style="
                padding: 1rem 1.5rem 1.5rem;
                display: flex;
                gap: 0.75rem;
                justify-content: flex-end;
            ">
                <button class="btn-cancel" onclick="closeDeleteModal()" style="
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
                ">Cancelar</button>
                <button class="btn-confirm" onclick="deleteJunta(${juntaId})" style="
                    padding: 0.75rem 1.5rem;
                    border: none;
                    border-radius: 6px;
                    font-weight: 600;
                    font-size: 0.875rem;
                    cursor: pointer;
                    transition: all 0.2s ease;
                    min-width: 80px;
                    background: #dc2626;
                    color: white;
                    font-family: inherit;
                ">Eliminar</button>
            </div>
        </div>
    `;
    
    // Agregar modal al DOM
    document.body.appendChild(modal);
    document.body.style.overflow = 'hidden';
    
    // Animar entrada del modal
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
        // Animación de salida suave
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
    
    // Restaurar overflow del body completamente
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

// ===== SISTEMA DE FILTROS =====

const FiltersManager = {
    elements: {
        filtersToggle: null,
        filtersContent: null,
        filterIcon: null,
        filterText: null,
        filterForm: null,
        filterSelects: null,
        clearBtn: null,
        applyBtn: null,
        orderToggle: null,
        hiddenSortDirection: null
    },

    init() {
        this.bindElements();
        this.bindEvents();
        this.updateActiveFilters();
        this.showFiltersIfActive();
        this.setupOrderToggle();
    },

    bindElements() {
        this.elements.filtersContent = document.getElementById('filters-content');
        this.elements.filterForm = document.getElementById('filters-form');
        this.elements.filterSelects = document.querySelectorAll('.filter-select');
        this.elements.clearBtn = document.getElementById('clear-filters');
        this.elements.applyBtn = document.getElementById('apply-filters');
        this.elements.orderToggle = document.getElementById('toggle-order');
        this.elements.hiddenSortDirection = document.getElementById('hidden_sort_direction');
    },

    bindEvents() {
        // Limpiar filtros
        if (this.elements.clearBtn) {
            this.elements.clearBtn.addEventListener('click', () => this.clearFilters());
        }

        // Aplicar filtros
        if (this.elements.applyBtn) {
            this.elements.applyBtn.addEventListener('click', () => this.applyFilters());
        }

        // Auto-aplicar filtros cuando cambian los selects
        this.elements.filterSelects.forEach(select => {
            select.addEventListener('change', () => {
                this.updateActiveFilters();
                // Auto-submit cuando cambia un select
                setTimeout(() => {
                    this.applyFilters();
                }, 100);
            });
        });
    },

    clearFilters() {
        // Limpiar todos los selects
        this.elements.filterSelects.forEach(select => {
            select.value = '';
            select.classList.remove('active');
        });
        
        // Resetear orden
        if (this.elements.hiddenSortDirection) {
            this.elements.hiddenSortDirection.value = 'desc';
        }
        
        // Redirigir a la página sin parámetros
        if (this.elements.filterForm) {
            window.location.href = this.elements.filterForm.action;
        }
    },

    applyFilters() {
        if (this.elements.filterForm) {
            this.elements.filterForm.submit();
        }
    },

    updateActiveFilters() {
        this.elements.filterSelects.forEach(select => {
            if (select.value && select.value !== '') {
                select.classList.add('active');
            } else {
                select.classList.remove('active');
            }
        });
    },

    showFiltersIfActive() {
        const urlParams = new URLSearchParams(window.location.search);
        const hasFilters = urlParams.has('filter_mes') ||
                          urlParams.has('filter_anio') ||
                          urlParams.has('sort_field') ||
                          urlParams.has('sort_direction');
        
        if (hasFilters && this.elements.filtersContent) {
            this.elements.filtersContent.classList.add('show');
            const chevron = document.getElementById('filters-chevron');
            if (chevron) {
                chevron.style.transform = 'rotate(180deg)';
            }
        }
    },

    setupOrderToggle() {
        if (this.elements.orderToggle && this.elements.hiddenSortDirection) {
            this.elements.orderToggle.addEventListener('click', () => {
                // Obtener dirección actual
                const currentDirection = this.elements.hiddenSortDirection.value;
                const newDirection = currentDirection === 'desc' ? 'asc' : 'desc';
                
                // Actualizar valor oculto
                this.elements.hiddenSortDirection.value = newDirection;
                
                // Actualizar texto e icono del botón
                if (newDirection === 'desc') {
                    this.elements.orderToggle.innerHTML = '<i class="fas fa-arrow-up"></i> Invertir a Ascendente';
                } else {
                    this.elements.orderToggle.innerHTML = '<i class="fas fa-arrow-down"></i> Invertir a Descendente';
                }
                
                // Enviar formulario automáticamente
                this.applyFilters();
            });
        }
    }
};

// Toggle filtros (función independiente como en unidades)
function toggleFilters() {
    const content = document.getElementById('filters-content');
    const chevron = document.getElementById('filters-chevron');
    
    if (content.classList.contains('show')) {
        content.classList.remove('show');
        chevron.style.transform = 'rotate(0deg)';
    } else {
        content.classList.add('show');
        chevron.style.transform = 'rotate(180deg)';
    }
}

// Inicializar cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', function() {
    FiltersManager.init();
});