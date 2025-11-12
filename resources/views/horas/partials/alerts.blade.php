{{--
    PARTIAL: Alertas de validación
    Uso: @include('horas.partials.alerts')
--}}

@if(session('success'))
    <div class="alert alert-success alert-dismissible modern-alert success-alert" role="alert">
        <div class="alert-content">
            <i class="fas fa-check-circle alert-icon"></i>
            <div class="alert-message">
                <strong>¡Éxito!</strong>
                {{ session('success') }}
            </div>
        </div>
        <button type="button" class="alert-close" onclick="this.parentElement.remove()">
            <i class="fas fa-times"></i>
        </button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible modern-alert error-alert" role="alert">
        <div class="alert-content">
            <i class="fas fa-exclamation-circle alert-icon"></i>
            <div class="alert-message">
                <strong>Error</strong>
                {{ session('error') }}
            </div>
        </div>
        <button type="button" class="alert-close" onclick="this.parentElement.remove()">
            <i class="fas fa-times"></i>
        </button>
    </div>
@endif

@if(session('warning'))
    <div class="alert alert-warning alert-dismissible modern-alert warning-alert" role="alert">
        <div class="alert-content">
            <i class="fas fa-exclamation-triangle alert-icon"></i>
            <div class="alert-message">
                <strong>Atención</strong>
                {{ session('warning') }}
            </div>
        </div>
        <button type="button" class="alert-close" onclick="this.parentElement.remove()">
            <i class="fas fa-times"></i>
        </button>
    </div>
@endif

@if(session('info'))
    <div class="alert alert-info alert-dismissible modern-alert info-alert" role="alert">
        <div class="alert-content">
            <i class="fas fa-info-circle alert-icon"></i>
            <div class="alert-message">
                <strong>Información</strong>
                {{ session('info') }}
            </div>
        </div>
        <button type="button" class="alert-close" onclick="this.parentElement.remove()">
            <i class="fas fa-times"></i>
        </button>
    </div>
@endif

@if($errors->any())
    <div class="alert alert-danger alert-dismissible modern-alert error-alert" role="alert">
        <div class="alert-content">
            <i class="fas fa-exclamation-circle alert-icon"></i>
            <div class="alert-message">
                <strong>Se encontraron errores:</strong>
                <ul class="alert-list">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
        <button type="button" class="alert-close" onclick="this.parentElement.remove()">
            <i class="fas fa-times"></i>
        </button>
    </div>
@endif

<style>
.modern-alert {
    border: none;
    border-radius: var(--radius-md);
    box-shadow: var(--shadow-sm);
    margin-bottom: 1.5rem;
    padding: 0;
    overflow: hidden;
    position: relative;
    animation: slideInDown 0.3s ease-out;
}

.alert-content {
    display: flex;
    align-items: flex-start;
    padding: 1rem 1.25rem;
    gap: 0.75rem;
}

.alert-icon {
    font-size: 1.25rem;
    flex-shrink: 0;
    margin-top: 0.125rem;
}

.alert-message {
    flex: 1;
    line-height: 1.5;
}

.alert-message strong {
    display: block;
    margin-bottom: 0.25rem;
}

.alert-list {
    margin: 0.5rem 0 0;
    padding-left: 1.25rem;
}

.alert-list li {
    margin-bottom: 0.25rem;
}

.alert-close {
    position: absolute;
    top: 0.75rem;
    right: 1rem;
    background: none;
    border: none;
    cursor: pointer;
    opacity: 0.7;
    transition: opacity 0.2s ease;
    padding: 0.25rem;
    border-radius: var(--radius-sm);
}

.alert-close:hover {
    opacity: 1;
    background: rgba(0, 0, 0, 0.1);
}

/* Colores específicos */
.success-alert {
    background: linear-gradient(135deg, #d4edda, #c3e6cb);
    color: #155724;
    border-left: 4px solid #28a745;
}

.success-alert .alert-icon {
    color: #28a745;
}

.error-alert {
    background: linear-gradient(135deg, #f8d7da, #f5c6cb);
    color: #721c24;
    border-left: 4px solid #dc3545;
}

.error-alert .alert-icon {
    color: #dc3545;
}

.warning-alert {
    background: linear-gradient(135deg, #fff3cd, #ffeaa7);
    color: #856404;
    border-left: 4px solid #ffc107;
}

.warning-alert .alert-icon {
    color: #ffc107;
}

.info-alert {
    background: linear-gradient(135deg, #d1ecf1, #bee5eb);
    color: #0c5460;
    border-left: 4px solid #17a2b8;
}

.info-alert .alert-icon {
    color: #17a2b8;
}

@keyframes slideInDown {
    from {
        opacity: 0;
        transform: translateY(-20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Auto-hide para mensajes de éxito */
.success-alert {
    animation: slideInDown 0.3s ease-out, fadeOut 0.5s ease-in 4.5s forwards;
}

@keyframes fadeOut {
    to {
        opacity: 0;
        transform: translateY(-10px);
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-hide success alerts después de 5 segundos
    const successAlerts = document.querySelectorAll('.success-alert');
    successAlerts.forEach(alert => {
        setTimeout(() => {
            if (alert.parentElement) {
                alert.style.animation = 'fadeOut 0.5s ease-in forwards';
                setTimeout(() => {
                    alert.remove();
                }, 500);
            }
        }, 5000);
    });
});
</script>