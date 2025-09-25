/**
 * =====================================
 * PLANES DE TRABAJO LISTADO - JAVASCRIPT
 * =====================================
 */

document.addEventListener('DOMContentLoaded', function() {
    initFilters();
    initTableActions();
    initSuccessMessages();
});

/**
 * Sistema de filtros
 */
function initFilters() {
    const filterUser = document.getElementById('filter-user');
    const filterMes = document.getElementById('filter-mes');
    const filterAnio = document.getElementById('filter-anio');
    const filterEstado = document.getElementById('filter-estado');
    const clearFiltersBtn = document.getElementById('clear-filters');
    
    // Aplicar filtros al cambiar cualquier select
    [filterUser, filterMes, filterAnio, filterEstado].forEach(filter => {
        if (filter) {
            filter.addEventListener('change', applyFilters);
        }
    });
    
    // Limpiar filtros
    if (clearFiltersBtn) {
        clearFiltersBtn.addEventListener('click', function() {
            [filterUser, filterMes, filterAnio, filterEstado].forEach(filter => {
                if (filter) {
                    filter.value = '';
                }
            });
            applyFilters();
        });
    }
    
    function applyFilters() {
        const userValue = filterUser ? filterUser.value : '';
        const mesValue = filterMes ? filterMes.value : '';
        const anioValue = filterAnio ? filterAnio.value : '';
        const estadoValue = filterEstado ? filterEstado.value : '';
        
        const tableRows = document.querySelectorAll('tbody tr');
        let visibleCount = 0;
        
        tableRows.forEach(row => {
            const userName = row.cells[0] ? row.cells[0].textContent.toLowerCase() : '';
            const mes = row.cells[1] ? row.cells[1].textContent : '';
            const anio = row.cells[2] ? row.cells[2].textContent : '';
            const estado = row.cells[3] ? row.cells[3].textContent.toLowerCase() : '';
            
            let showRow = true;
            
            // Filtrar por usuario
            if (userValue && !userName.includes(userValue.toLowerCase())) {
                showRow = false;
            }
            
            // Filtrar por mes
            if (mesValue && mes !== mesValue) {
                showRow = false;
            }
            
            // Filtrar por año
            if (anioValue && anio !== anioValue) {
                showRow = false;
            }
            
            // Filtrar por estado
            if (estadoValue && !estado.includes(estadoValue.toLowerCase())) {
                showRow = false;
            }
            
            if (showRow) {
                row.style.display = '';
                visibleCount++;
            } else {
                row.style.display = 'none';
            }
        });
        
        // Mostrar mensaje si no hay resultados
        showEmptyState(visibleCount === 0);
        
        // Actualizar contador de filtros activos
        updateFilterCounter();
    }
    
    function showEmptyState(show) {
        let emptyState = document.getElementById('empty-state');
        
        if (show && !emptyState) {
            const tbody = document.querySelector('tbody');
            emptyState = document.createElement('tr');
            emptyState.id = 'empty-state';
            emptyState.innerHTML = `
                <td colspan="5" class="text-center py-5">
                    <div class="empty-state">
                        <i class="fas fa-filter empty-state-icon"></i>
                        <h4>No se encontraron planes</h4>
                        <p class="text-muted">No hay planes que coincidan con los filtros aplicados</p>
                    </div>
                </td>
            `;
            tbody.appendChild(emptyState);
        } else if (!show && emptyState) {
            emptyState.remove();
        }
    }
    
    function updateFilterCounter() {
        let activeFilters = 0;
        
        [filterUser, filterMes, filterAnio, filterEstado].forEach(filter => {
            if (filter && filter.value) {
                activeFilters++;
            }
        });
        
        let counter = document.getElementById('filter-counter');
        
        if (activeFilters > 0 && !counter) {
            counter = document.createElement('span');
            counter.id = 'filter-counter';
            counter.className = 'badge badge-primary';
            counter.textContent = activeFilters;
            
            const filtersTitle = document.querySelector('.card-header h4');
            if (filtersTitle) {
                filtersTitle.appendChild(counter);
            }
        } else if (activeFilters > 0 && counter) {
            counter.textContent = activeFilters;
        } else if (activeFilters === 0 && counter) {
            counter.remove();
        }
    }
}

/**
 * Acciones de tabla
 */
function initTableActions() {
    // Confirmación para eliminar
    document.addEventListener('click', function(e) {
        if (e.target.closest('.btn-eliminar')) {
            e.preventDefault();
            
            const btn = e.target.closest('.btn-eliminar');
            const planName = btn.dataset.planName || 'este plan';
            
            Swal.fire({
                title: '¿Estás seguro?',
                text: `Se eliminará el plan "${planName}". Esta acción se puede deshacer.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
                    window.location.href = btn.href;
                }
            });
        }
    });
    
    // Tooltip para botones
    const tooltips = document.querySelectorAll('[data-toggle="tooltip"]');
    tooltips.forEach(tooltip => {
        new bootstrap.Tooltip(tooltip);
    });
    
    // Resaltar fila al hacer hover
    const tableRows = document.querySelectorAll('tbody tr');
    tableRows.forEach(row => {
        row.addEventListener('mouseenter', function() {
            this.style.backgroundColor = 'var(--bg-light)';
        });
        
        row.addEventListener('mouseleave', function() {
            this.style.backgroundColor = '';
        });
    });
}

/**
 * Mensajes de éxito
 */
function initSuccessMessages() {
    const successAlert = document.querySelector('.alert-success');
    
    if (successAlert) {
        // Auto-hide después de 5 segundos
        setTimeout(() => {
            successAlert.style.opacity = '0';
            setTimeout(() => {
                successAlert.remove();
            }, 300);
        }, 5000);
        
        // Botón de cerrar manual
        const closeBtn = document.createElement('button');
        closeBtn.type = 'button';
        closeBtn.className = 'btn-close';
        closeBtn.setAttribute('aria-label', 'Cerrar');
        closeBtn.innerHTML = '&times;';
        closeBtn.style.cssText = `
            position: absolute;
            top: 10px;
            right: 15px;
            background: none;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
            color: #155724;
        `;
        
        closeBtn.addEventListener('click', () => {
            successAlert.remove();
        });
        
        successAlert.style.position = 'relative';
        successAlert.appendChild(closeBtn);
    }
}

/**
 * Exportar tabla a Excel
 */
function exportToExcel() {
    const table = document.querySelector('table');
    const visibleRows = Array.from(document.querySelectorAll('tbody tr')).filter(row => 
        row.style.display !== 'none' && row.id !== 'empty-state'
    );
    
    if (visibleRows.length === 0) {
        Swal.fire({
            icon: 'warning',
            title: 'Sin datos',
            text: 'No hay datos para exportar con los filtros aplicados'
        });
        return;
    }
    
    let csvContent = 'Usuario,Mes,Año,Estado,Horas Requeridas\n';
    
    visibleRows.forEach(row => {
        const cells = Array.from(row.cells).slice(0, -1); // Excluir columna de acciones
        const rowData = cells.map(cell => `"${cell.textContent.trim()}"`).join(',');
        csvContent += rowData + '\n';
    });
    
    const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
    const link = document.createElement('a');
    link.href = URL.createObjectURL(blob);
    link.download = `planes_trabajo_${new Date().toISOString().split('T')[0]}.csv`;
    link.click();
}

/**
 * Estadísticas rápidas
 */
function showQuickStats() {
    const allRows = document.querySelectorAll('tbody tr');
    const visibleRows = Array.from(allRows).filter(row => 
        row.style.display !== 'none' && row.id !== 'empty-state'
    );
    
    if (visibleRows.length === 0) return;
    
    let totalHoras = 0;
    let estadosCount = {
        'activo': 0,
        'inactivo': 0,
        'completado': 0
    };
    
    visibleRows.forEach(row => {
        const horasText = row.cells[4] ? row.cells[4].textContent : '0';
        const horas = parseInt(horasText) || 0;
        totalHoras += horas;
        
        const estado = row.cells[3] ? row.cells[3].textContent.toLowerCase().trim() : '';
        if (estadosCount.hasOwnProperty(estado)) {
            estadosCount[estado]++;
        }
    });
    
    const modal = document.createElement('div');
    modal.className = 'modal fade';
    modal.innerHTML = `
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Estadísticas Rápidas</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="stat-card">
                                <h3>${visibleRows.length}</h3>
                                <p>Total Planes</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="stat-card">
                                <h3>${totalHoras}</h3>
                                <p>Total Horas</p>
                            </div>
                        </div>
                    </div>
                    <div class="mt-3">
                        <h6>Por Estado:</h6>
                        <ul class="list-unstyled">
                            <li><span class="badge bg-success">Activo:</span> ${estadosCount.activo}</li>
                            <li><span class="badge bg-secondary">Inactivo:</span> ${estadosCount.inactivo}</li>
                            <li><span class="badge bg-info">Completado:</span> ${estadosCount.completado}</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    document.body.appendChild(modal);
    const bsModal = new bootstrap.Modal(modal);
    bsModal.show();
    
    modal.addEventListener('hidden.bs.modal', () => {
        modal.remove();
    });
}