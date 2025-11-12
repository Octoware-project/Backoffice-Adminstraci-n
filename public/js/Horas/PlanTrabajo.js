    // Función para mostrar/ocultar filtros
    function toggleFilters() {
        const content = document.getElementById('filters-content');
        const chevron = document.getElementById('filters-chevron');
        
        if (content.classList.contains('show')) {
            content.classList.remove('show');
            chevron.className = 'fas fa-chevron-down';
        } else {
            content.classList.add('show');
            chevron.className = 'fas fa-chevron-up';
        }
    }

    // Función para limpiar filtros
    function clearFilters() {
        const form = document.getElementById('filters-form');
        const selects = form.querySelectorAll('select');
        
        selects.forEach(select => {
            select.value = '';
            select.classList.remove('active');
        });
        
        // Submit el formulario para aplicar el reset
        form.submit();
    }

    // Mostrar filtros si hay parámetros activos
    document.addEventListener('DOMContentLoaded', function() {
        // Función para aplicar estilos a filtros activos
        function updateFilterStyles() {
            const filterSelects = document.querySelectorAll('.filter-select');
            
            filterSelects.forEach(select => {
                if (select.value && select.value !== '') {
                    select.classList.add('active');
                } else {
                    select.classList.remove('active');
                }
            });
        }
        
        // Aplicar estilos al cargar la página
        updateFilterStyles();
        
        // Aplicar estilos cuando cambia el valor
        const filterSelects = document.querySelectorAll('.filter-select');
        filterSelects.forEach(select => {
            select.addEventListener('change', updateFilterStyles);
        });
        
        const urlParams = new URLSearchParams(window.location.search);
        const hasFilters = urlParams.has('filter_user') ||
                          urlParams.has('filter_month') ||
                          urlParams.has('sort_progress') || 
                          urlParams.has('filter_completed') || 
                          urlParams.has('sort_hours');
        
        if (hasFilters) {
            const content = document.getElementById('filters-content');
            const icon = document.getElementById('filter-icon');
            const text = document.getElementById('filter-text');
            
            content.classList.add('show');
            icon.className = 'fas fa-chevron-up';
            text.textContent = 'Ocultar Filtros';
        }
    });

    // Función para confirmar eliminación de plan de trabajo desde la lista
    function confirmDeletePlanFromList(planId, userName, mes, anio) {
        const meses = ['', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 
                       'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
        
        ModalConfirmation.create({
            title: 'Confirmar Eliminación de Plan',
            message: '¿Está seguro que desea eliminar el plan de trabajo:',
            detail: `"${userName} - ${meses[mes]} ${anio}"`,
            warning: 'Esta acción se puede revertir. El plan se marcará como eliminado.',
            confirmText: 'Eliminar Plan',
            cancelText: 'Cancelar',
            iconClass: 'fas fa-calendar-times',
            iconColor: '#dc2626',
            confirmColor: '#dc2626',
            onConfirm: function() {
                document.getElementById(`delete-plan-list-form-${planId}`).submit();
            }
        });
    }