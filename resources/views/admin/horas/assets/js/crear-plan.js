/**
 * ===================================
 * CREAR PLAN DE TRABAJO - JAVASCRIPT
 * ===================================
 */

document.addEventListener('DOMContentLoaded', function() {
    initUserSelector();
    initFormValidation();
    initDateValidation();
});

/**
 * Selector avanzado de usuarios
 */
function initUserSelector() {
    const searchInput = document.getElementById('user-search');
    const sortSelect = document.getElementById('user-sort');
    const usersList = document.getElementById('users-list');
    const selectedUserId = document.getElementById('user_id');
    const selectedUserName = document.getElementById('selected-user-name');
    
    if (!searchInput || !sortSelect || !usersList) return;
    
    const originalUsers = Array.from(usersList.children);
    
    // Búsqueda de usuarios
    searchInput.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        filterAndSortUsers(searchTerm, sortSelect.value);
    });
    
    // Ordenamiento de usuarios
    sortSelect.addEventListener('change', function() {
        const searchTerm = searchInput.value.toLowerCase();
        filterAndSortUsers(searchTerm, this.value);
    });
    
    // Selección de usuario
    usersList.addEventListener('click', function(e) {
        const userItem = e.target.closest('.user-item');
        if (!userItem) return;
        
        // Remover selección anterior
        usersList.querySelectorAll('.user-item').forEach(item => {
            item.classList.remove('selected');
        });
        
        // Seleccionar nuevo usuario
        userItem.classList.add('selected');
        selectedUserId.value = userItem.dataset.userId;
        selectedUserName.textContent = userItem.querySelector('.user-name').textContent;
        
        // Validar formulario
        validateForm();
    });
    
    function filterAndSortUsers(searchTerm, sortBy) {
        let filteredUsers = originalUsers.filter(user => {
            const name = user.querySelector('.user-name').textContent.toLowerCase();
            const email = user.querySelector('.user-email').textContent.toLowerCase();
            return name.includes(searchTerm) || email.includes(searchTerm);
        });
        
        // Ordenar usuarios
        filteredUsers.sort((a, b) => {
            const aName = a.querySelector('.user-name').textContent;
            const bName = b.querySelector('.user-name').textContent;
            const aEmail = a.querySelector('.user-email').textContent;
            const bEmail = b.querySelector('.user-email').textContent;
            
            switch(sortBy) {
                case 'name-asc':
                    return aName.localeCompare(bName);
                case 'name-desc':
                    return bName.localeCompare(aName);
                case 'email-asc':
                    return aEmail.localeCompare(bEmail);
                case 'email-desc':
                    return bEmail.localeCompare(aEmail);
                default:
                    return 0;
            }
        });
        
        // Limpiar y mostrar usuarios filtrados
        usersList.innerHTML = '';
        filteredUsers.forEach(user => {
            usersList.appendChild(user);
        });
        
        // Mostrar mensaje si no hay resultados
        if (filteredUsers.length === 0) {
            usersList.innerHTML = `
                <div class="empty-state" style="padding: 2rem;">
                    <i class="fas fa-search empty-state-icon"></i>
                    <p>No se encontraron usuarios que coincidan con "${searchTerm}"</p>
                </div>
            `;
        }
    }
}

/**
 * Validación del formulario
 */
function initFormValidation() {
    const form = document.querySelector('form');
    const submitBtn = document.querySelector('.btn-primary-modern');
    
    if (!form || !submitBtn) return;
    
    const requiredFields = [
        document.getElementById('user_id'),
        document.getElementById('mes'),
        document.getElementById('anio'),
        document.getElementById('horas_requeridas')
    ];
    
    // Validar en cada cambio
    requiredFields.forEach(field => {
        if (field) {
            field.addEventListener('input', validateForm);
            field.addEventListener('change', validateForm);
        }
    });
    
    function validateForm() {
        let isValid = true;
        
        requiredFields.forEach(field => {
            if (field && (!field.value || field.value.trim() === '')) {
                isValid = false;
            }
        });
        
        // Validar horas requeridas
        const horasField = document.getElementById('horas_requeridas');
        if (horasField) {
            const horas = parseInt(horasField.value);
            if (isNaN(horas) || horas < 1 || horas > 500) {
                isValid = false;
            }
        }
        
        // Habilitar/deshabilitar botón
        if (isValid) {
            submitBtn.style.opacity = '1';
            submitBtn.style.pointerEvents = 'auto';
            submitBtn.disabled = false;
        } else {
            submitBtn.style.opacity = '0.5';
            submitBtn.style.pointerEvents = 'none';
            submitBtn.disabled = true;
        }
    }
    
    // Validación inicial
    validateForm();
    
    // Manejar envío del formulario
    form.addEventListener('submit', function(e) {
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Creando...';
        submitBtn.disabled = true;
    });
}

/**
 * Validación de fechas
 */
function initDateValidation() {
    const mesSelect = document.getElementById('mes');
    const anioSelect = document.getElementById('anio');
    
    if (!mesSelect || !anioSelect) return;
    
    // Validar que no sea fecha futura
    function validateDate() {
        const mes = parseInt(mesSelect.value);
        const anio = parseInt(anioSelect.value);
        
        if (!mes || !anio) return;
        
        const selectedDate = new Date(anio, mes - 1, 1);
        const currentDate = new Date();
        currentDate.setDate(1); // Primer día del mes actual
        
        const warning = document.getElementById('date-warning');
        
        if (selectedDate > currentDate) {
            if (!warning) {
                const warningDiv = document.createElement('div');
                warningDiv.id = 'date-warning';
                warningDiv.className = 'alert alert-warning mt-2';
                warningDiv.innerHTML = `
                    <i class="fas fa-exclamation-triangle"></i>
                    <strong>Atención:</strong> Estás creando un plan para un mes futuro.
                `;
                anioSelect.parentNode.appendChild(warningDiv);
            }
        } else {
            if (warning) {
                warning.remove();
            }
        }
    }
    
    mesSelect.addEventListener('change', validateDate);
    anioSelect.addEventListener('change', validateDate);
}

/**
 * Mostrar tooltip de usuario
 */
function showUserTooltip(userElement, userData) {
    const tooltip = document.createElement('div');
    tooltip.className = 'user-tooltip';
    tooltip.innerHTML = `
        <div class="tooltip-content">
            <strong>${userData.name}</strong>
            <br>
            <small>${userData.email}</small>
            <br>
            <small class="text-muted">ID: ${userData.id}</small>
        </div>
    `;
    
    document.body.appendChild(tooltip);
    
    // Posicionar tooltip
    const rect = userElement.getBoundingClientRect();
    tooltip.style.cssText = `
        position: fixed;
        top: ${rect.top - tooltip.offsetHeight - 10}px;
        left: ${rect.left + (rect.width / 2) - (tooltip.offsetWidth / 2)}px;
        z-index: 9999;
        background: var(--bg-primary);
        border: 1px solid var(--border-color);
        border-radius: var(--radius-sm);
        padding: 0.5rem;
        box-shadow: var(--shadow-md);
        font-size: 0.875rem;
    `;
    
    // Remover tooltip después de 3 segundos
    setTimeout(() => {
        if (tooltip.parentNode) {
            tooltip.remove();
        }
    }, 3000);
}