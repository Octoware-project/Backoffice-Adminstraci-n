document.addEventListener('DOMContentLoaded', function() {
    const userSelect = document.getElementById('user_id');
    const filterInput = document.getElementById('user-filter');
    const sortButtons = document.querySelectorAll('.sort-btn');
    const userCount = document.getElementById('user-count');
    
    // Obtener todas las opciones (excepto la primera que es el placeholder)
    const allOptions = Array.from(userSelect.options).slice(1);
    let filteredOptions = [...allOptions];
    let currentSort = 'name';
    
    // Función para actualizar el select
    function updateSelect() {
        // Limpiar opciones actuales (mantener placeholder)
        userSelect.innerHTML = '<option value="">Seleccione un usuario</option>';
        
        // Agregar opciones filtradas y ordenadas
        filteredOptions.forEach(option => {
            const newOption = document.createElement('option');
            newOption.value = option.value;
            
            // Copiar todos los atributos data
            newOption.dataset.name = option.dataset.name;
            newOption.dataset.email = option.dataset.email;
            newOption.dataset.displayName = option.dataset.displayName;
            newOption.dataset.displayEmail = option.dataset.displayEmail;
            
            // Cambiar formato según el ordenamiento actual
            if (currentSort === 'email') {
                // Formato: email (nombre) - email en negrita
                newOption.innerHTML = `<strong>${option.dataset.displayEmail}</strong> (${option.dataset.displayName})`;
            } else {
                // Formato: nombre (email) - nombre en negrita
                newOption.innerHTML = `<strong>${option.dataset.displayName}</strong> (${option.dataset.displayEmail})`;
            }
            
            userSelect.appendChild(newOption);
        });
        
        // Actualizar contador
        userCount.textContent = `Mostrando ${filteredOptions.length} de ${allOptions.length} usuarios`;
    }
    
    // Función para filtrar usuarios
    function filterUsers() {
        const searchTerm = filterInput.value.toLowerCase().trim();
        
        if (searchTerm === '') {
            filteredOptions = [...allOptions];
        } else {
            filteredOptions = allOptions.filter(option => {
                const name = option.dataset.name || '';
                const email = option.dataset.email || '';
                return name.includes(searchTerm) || email.includes(searchTerm);
            });
        }
        
        sortUsers(currentSort);
    }
    
    // Función para ordenar usuarios
    function sortUsers(sortBy) {
        currentSort = sortBy;
        
        filteredOptions.sort((a, b) => {
            let valueA, valueB;
            
            if (sortBy === 'name') {
                valueA = a.dataset.name || '';
                valueB = b.dataset.name || '';
            } else if (sortBy === 'email') {
                valueA = a.dataset.email || '';
                valueB = b.dataset.email || '';
            }
            
            return valueA.localeCompare(valueB);
        });
        
        updateSelect();
    }
    
    // Event listeners
    filterInput.addEventListener('input', filterUsers);
    
    sortButtons.forEach(button => {
        button.addEventListener('click', function() {
            // Remover clase active de todos los botones
            sortButtons.forEach(btn => btn.classList.remove('active'));
            
            // Agregar clase active al botón clickeado
            this.classList.add('active');
            
            // Ordenar por el criterio seleccionado
            const sortBy = this.dataset.sort;
            sortUsers(sortBy);
        });
    });
    
    // Inicializar con ordenamiento por nombre
    sortUsers('name');
});
