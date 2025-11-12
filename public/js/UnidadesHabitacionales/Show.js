// Auto-hide alerts after 5 seconds
document.addEventListener('DOMContentLoaded', function() {
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        alert.classList.add('auto-hide');
        setTimeout(() => {
            alert.style.display = 'none';
        }, 5000);
    });
});

// Funcionalidad del modal para asignar personas
document.getElementById('btn-asignar-personas').addEventListener('click', function() {
    document.getElementById('modal-asignar-personas').style.display = 'flex';
    cargarPersonasDisponibles();
});

function cerrarModal() {
    document.getElementById('modal-asignar-personas').style.display = 'none';
}

// Cerrar modal al hacer click fuera de él
document.getElementById('modal-asignar-personas').addEventListener('click', function(e) {
    if (e.target === this) {
        cerrarModal();
    }
});

function cargarPersonasDisponibles() {
    const container = document.getElementById('personas-disponibles-container');
    
    fetch(window.unidadData.routes.personasDisponibles)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                mostrarPersonasDisponibles(data.personas);
            } else {
                container.innerHTML = `
                    <div class="empty-state" style="text-align: center; padding: 2rem;">
                        <div class="empty-state-icon" style="font-size: 2rem; margin-bottom: 1rem; color: var(--text-muted);">
                            <i class="fas fa-users-slash"></i>
                        </div>
                        <h4>No hay personas disponibles</h4>
                        <p>No hay personas en estado "Aceptado" o "Inactivo" sin asignar a una unidad.</p>
                    </div>
                `;
            }
        })
        .catch(error => {
            console.error('Error al cargar personas:', error);
            container.innerHTML = `
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle"></i>
                    Error al cargar las personas disponibles. Inténtalo de nuevo.
                </div>
            `;
        });
}

function mostrarPersonasDisponibles(personas) {
    const container = document.getElementById('personas-disponibles-container');
    
    if (personas.length === 0) {
        container.innerHTML = `
            <div class="empty-state" style="text-align: center; padding: 2rem;">
                <div class="empty-state-icon" style="font-size: 2rem; margin-bottom: 1rem; color: var(--text-muted);">
                    <i class="fas fa-users-slash"></i>
                </div>
                <h4>No hay personas disponibles</h4>
                <p>No hay personas en estado "Aceptado" o "Inactivo" sin asignar a una unidad.</p>
            </div>
        `;
        return;
    }
    
    let html = '<div>';
    
    personas.forEach(persona => {
        const iniciales = (persona.name.charAt(0) + (persona.apellido ? persona.apellido.charAt(0) : '')).toUpperCase();
        
        html += `
            <div class="persona-item">
                <div class="persona-info">
                    <div class="persona-avatar">${iniciales}</div>
                    <div class="persona-details">
                        <h4>${persona.name} ${persona.apellido || ''}</h4>
                        <p>CI: ${persona.CI || 'No registrada'} | Estado: ${persona.estadoRegistro}</p>
                        ${persona.user && persona.user.email ? `<p>Email: ${persona.user.email}</p>` : ''}
                    </div>
                </div>
                <button class="btn-asignar" onclick="asignarPersona(${persona.id}, '${persona.name} ${persona.apellido || ''}')">
                    <i class="fas fa-user-plus"></i> Asignar
                </button>
            </div>
        `;
    });
    
    html += '</div>';
    container.innerHTML = html;
}

function asignarPersona(personaId, nombrePersona) {
    ModalConfirmation.create({
        title: 'Confirmar Asignación',
        message: '¿Está seguro que desea asignar a:',
        detail: `"${nombrePersona}" a esta unidad habitacional`,
        warning: 'La persona será asociada a esta unidad.',
        confirmText: 'Asignar',
        cancelText: 'Cancelar',
        iconClass: 'fas fa-user-plus',
        iconColor: '#059669',
        confirmColor: '#059669',
        onConfirm: function() {
            ejecutarAsignacion(personaId, nombrePersona);
        }
    });
}

function ejecutarAsignacion(personaId, nombrePersona) {
    const formData = new FormData();
    formData.append('_token', window.unidadData.csrfToken);
    formData.append('persona_id', personaId);
    
    fetch(window.unidadData.routes.asignarPersona, {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Mostrar mensaje de éxito personalizado y recargar la página
            ModalConfirmation.create({
                title: 'Asignación Exitosa',
                message: data.message,
                warning: null,
                confirmText: 'Continuar',
                cancelText: null,
                iconClass: 'fas fa-check-circle',
                iconColor: '#059669',
                confirmColor: '#059669',
                onConfirm: function() {
                    window.location.reload();
                }
            });
        } else {
            // Mostrar mensaje de error
            ModalConfirmation.create({
                title: 'Error en Asignación',
                message: data.message || 'Error al asignar la persona',
                warning: 'Inténtalo de nuevo.',
                confirmText: 'Entendido',
                cancelText: null,
                iconClass: 'fas fa-exclamation-triangle',
                iconColor: '#dc2626',
                confirmColor: '#dc2626',
                onConfirm: function() {}
            });
        }
    })
    .catch(error => {
        ModalConfirmation.create({
            title: 'Error de Conexión',
            message: 'Error al asignar la persona.',
            warning: 'Verifique su conexión e inténtalo de nuevo.',
            confirmText: 'Entendido',
            cancelText: null,
            iconClass: 'fas fa-wifi',
            iconColor: '#dc2626',
            confirmColor: '#dc2626',
            onConfirm: function() {}
        });
    });
}

// Función para confirmar desasignación de persona
function confirmDesasignarPersona(personaId, nombrePersona, numeroUnidad) {
    ModalConfirmation.create({
        title: 'Confirmar Desasignación',
        message: '¿Está seguro que desea desasignar a:',
        detail: `"${nombrePersona}" de la unidad ${numeroUnidad}`,
        warning: 'La persona ya no estará asociada a esta unidad.',
        confirmText: 'Desasignar',
        cancelText: 'Cancelar',
        iconClass: 'fas fa-user-times',
        iconColor: '#dc2626',
        confirmColor: '#dc2626',
        onConfirm: function() {
            document.getElementById(`desasignar-form-${personaId}`).submit();
        }
    });
}
