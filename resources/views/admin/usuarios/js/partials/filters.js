/**
 * FILTROS JavaScript - Sistema de filtros para usuarios
 */

const FiltersManager = {
    elements: {
        filtersToggle: null,
        filtersContent: null,
        filterIcon: null,
        filterText: null,
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
        this.elements.filtersToggle = document.getElementById('filters-toggle');
        this.elements.filtersContent = document.getElementById('filters-content');
        this.elements.filterIcon = document.getElementById('filter-icon');
        this.elements.filterText = document.getElementById('filter-text');
        this.elements.filterForm = document.getElementById('filters-form');
        this.elements.filterInputs = document.querySelectorAll('.filter-input, .filter-select');
        this.elements.clearBtn = document.getElementById('clear-filters');
        this.elements.applyBtn = document.getElementById('apply-filters');
    },

    bindEvents() {
        // Toggle de mostrar/ocultar filtros
        if (this.elements.filtersToggle) {
            this.elements.filtersToggle.addEventListener('click', () => this.toggleFilters());
        }

        // Limpiar filtros
        if (this.elements.clearBtn) {
            this.elements.clearBtn.addEventListener('click', () => this.clearFilters());
        }

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

    toggleFilters() {
        const isShown = this.elements.filtersContent.classList.contains('show');
        
        if (isShown) {
            this.elements.filtersContent.classList.remove('show');
            this.elements.filterIcon.className = 'fas fa-chevron-down';
            this.elements.filterText.textContent = 'Mostrar Filtros';
        } else {
            this.elements.filtersContent.classList.add('show');
            this.elements.filterIcon.className = 'fas fa-chevron-up';
            this.elements.filterText.textContent = 'Ocultar Filtros';
        }
    },

    clearFilters() {
        this.elements.filterInputs.forEach(input => {
            input.value = '';
            input.classList.remove('active');
        });
        
        // Enviar formulario para aplicar el reset
        if (this.elements.filterForm) {
            this.elements.filterForm.submit();
        }
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
        const urlParams = new URLSearchParams(window.location.search);
        const hasFilters = urlParams.has('filter_estado') ||
                          urlParams.has('filter_nombre') ||
                          urlParams.has('filter_email') || 
                          urlParams.has('sort_fecha');
        
        if (hasFilters && this.elements.filtersContent && this.elements.filterIcon && this.elements.filterText) {
            this.elements.filtersContent.classList.add('show');
            this.elements.filterIcon.className = 'fas fa-chevron-up';
            this.elements.filterText.textContent = 'Ocultar Filtros';
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