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
        ['numero_departamento', 'piso', 'estado', 'sort'].includes(key) && urlParams.get(key)
    );
    
    if (hasFilters) {
        toggleFilters();
    }
});

// Función para confirmar eliminación de unidad
// Función para confirmar eliminación de unidad
function confirmDeleteUnidad(unidadId, numeroDepartamento, piso) {
    ModalConfirmation.create({
        title: 'Confirmar Eliminación de Unidad',
        message: '¿Está seguro que desea eliminar la unidad:',
        detail: `"Departamento ${numeroDepartamento} - Piso ${piso}"`,
        warning: 'NOTA: No se puede eliminar si tiene personas asignadas. Primero debe desasignar todas las personas de la unidad.',
        confirmText: 'Eliminar Unidad',
        cancelText: 'Cancelar',
        iconClass: 'fas fa-building',
        iconColor: '#dc2626',
        confirmColor: '#dc2626',
        onConfirm: function() {
            document.getElementById(`delete-form-unidad-${unidadId}`).submit();
        }
    });
}