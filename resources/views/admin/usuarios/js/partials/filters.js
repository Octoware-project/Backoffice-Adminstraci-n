/**
 * FILTROS JavaScript - Sistema moderno de filtros para usuarios (Estilo Facturas)
 */

// Función global para toggle de filtros (llamada desde HTML)
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