// Archivo para scripts de asamblea
// Script extraído de Asamblea.blade.php
document.addEventListener('DOMContentLoaded', function() {
	document.querySelectorAll('.junta-row').forEach(function(row) {
		row.addEventListener('dblclick', function() {
			var juntaId = this.getAttribute('data-junta-id');
			// La siguiente línea debe ser ajustada si la URL base cambia
			window.location.href = "/juntas_asamblea/" + juntaId;
		});
	});
});
