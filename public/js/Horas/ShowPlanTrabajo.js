
    // Animación del progreso circular
    document.addEventListener('DOMContentLoaded', function() {
        const cards = document.querySelectorAll('.fade-in');
        cards.forEach((card, index) => {
            setTimeout(() => {
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, index * 100);
        });
    });

    // Función para confirmar eliminación de plan de trabajo
    function confirmDeletePlan(planId, userName, mes, anio) {
        const meses = ['', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 
                       'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
        
        ModalConfirmation.create({
            title: 'Confirmar Eliminación de Plan',
            message: '¿Está seguro que desea eliminar el plan de trabajo:',
            detail: `"${userName} - ${meses[mes]} ${anio}"`,
            warning: 'Esta acción se puede revertir desde la lista de planes eliminados.',
            confirmText: 'Eliminar Plan',
            cancelText: 'Cancelar',
            iconClass: 'fas fa-calendar-times',
            iconColor: '#dc2626',
            confirmColor: '#dc2626',
            onConfirm: function() {
                document.getElementById(`delete-plan-form-${planId}`).submit();
            }
        });
    }