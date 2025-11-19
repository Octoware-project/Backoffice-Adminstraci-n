// Validación del formulario
document.getElementById('edit-form').addEventListener('submit', function(e) {
    const lugar = document.getElementById('lugar');
    const fecha = document.getElementById('fecha');
    
    // Limpiar errores previos
    document.querySelectorAll('.form-input, .form-textarea').forEach(input => {
        input.classList.remove('error');
    });
    
    let hasError = false;
    
    // Validar lugar
    if (!lugar.value.trim()) {
        lugar.classList.add('error');
        hasError = true;
    }
    
    // Validar fecha
    if (!fecha.value) {
        fecha.classList.add('error');
        hasError = true;
    }
    
    if (hasError) {
        e.preventDefault();
        // Scroll al primer error
        const firstError = document.querySelector('.form-input.error, .form-textarea.error');
        if (firstError) {
            firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
            firstError.focus();
        }
    }
});

// Efecto de focus en inputs
document.querySelectorAll('.form-input, .form-textarea').forEach(input => {
    input.addEventListener('focus', function() {
        this.classList.remove('error');
    });
});