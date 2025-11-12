// Función para confirmar eliminación de factura
function confirmDeleteFactura(facturaId, monto, fecha) {
    ModalConfirmation.create({
        title: 'Confirmar Eliminación de Factura',
        message: '¿Está seguro que desea eliminar la factura:',
        detail: `"$${monto} - ${fecha}"`,
        warning: 'Esta acción no se puede deshacer. Se perderán todos los datos de la factura.',
        confirmText: 'Eliminar Factura',
        cancelText: 'Cancelar',
        iconClass: 'fas fa-file-invoice-dollar',
        iconColor: '#dc2626',
        confirmColor: '#dc2626',
        onConfirm: function() {
            document.getElementById(`delete-factura-form-${facturaId}`).submit();
        }
    });
}