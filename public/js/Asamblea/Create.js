document.addEventListener('DOMContentLoaded', function() {
    // Configurar fecha mínima como hoy
    const fechaInput = document.getElementById('fecha');
    const today = new Date().toISOString().split('T')[0];
    fechaInput.min = today;
    
    // Efectos de focus modernos en los inputs
    const inputs = document.querySelectorAll('.form-control');
    inputs.forEach(input => {
        input.addEventListener('focus', function() {
            this.parentElement.classList.add('focused');
        });
        
        input.addEventListener('blur', function() {
            this.parentElement.classList.remove('focused');
        });
    });

    // Validación en tiempo real
    const form = document.querySelector('form');
    form.addEventListener('submit', function(e) {
        const lugar = document.getElementById('lugar').value.trim();
        const fecha = document.getElementById('fecha').value;
        
        if (!lugar || !fecha) {
            e.preventDefault();
            ModalConfirmation.create({
                title: 'Error de Validación',
                message: 'Por favor complete todos los campos obligatorios.',
                type: 'error',
                showCancelButton: false
            });
            return false;
        }
        
        // Validar que la fecha no sea anterior a hoy
        const selectedDate = new Date(fecha);
        const todayDate = new Date(today);
        
        if (selectedDate < todayDate) {
            e.preventDefault();
            ModalConfirmation.create({
                title: 'Error de Validación',
                message: 'La fecha de la junta no puede ser anterior a hoy.',
                type: 'error',
                showCancelButton: false
            });
            return false;
        }
    });
});