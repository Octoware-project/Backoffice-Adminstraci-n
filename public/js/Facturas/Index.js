 // URL base correcta de la API Cooperativa
    const API_COOPERATIVA_BASE = 'http://localhost:8001/api';
    const API_COOPERATIVA_WEB = 'http://localhost:8001';

    function abrirComprobanteApi(facturaId) {
        // Usar la ruta web directa para mejor compatibilidad
        const url = `${API_COOPERATIVA_WEB}/comprobantes/${facturaId}`;
        window.open(url, '_blank');
    }
    document.querySelectorAll('.factura-row').forEach(function(row) {
        row.addEventListener('click', function(event) {
            // Verificar si el clic fue en un botón, enlace o formulario
            if (event.target.closest('button, a, form')) {
                return; // No redirigir si se hizo clic en un elemento interactivo
            }
            
            const email = row.getAttribute('data-email');
            if(email) {
                let base = "{{ url('/facturas/usuario') }}";
                if (base.endsWith('/')) base = base.slice(0, -1);
                window.location.href = base + '/' + encodeURIComponent(email);
            }
        });
    });

    // Toggle filtros
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

    // Mostrar filtros si hay parámetros de búsqueda
    document.addEventListener('DOMContentLoaded', function() {
        const urlParams = new URLSearchParams(window.location.search);
        const hasFilters = Array.from(urlParams.keys()).some(key => 
            ['año', 'mes'].includes(key) && urlParams.get(key)
        );
        
        if (hasFilters) {
            toggleFilters();
        }
    });
