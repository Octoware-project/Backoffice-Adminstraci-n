{{--
    PARTIAL: Card contenedor moderno
    Uso: @include('horas.partials.card', ['title' => 'Título', 'content' => 'contenido'])
--}}
<div class="modern-card {{ $class ?? '' }}">
    @if(isset($title) || isset($actions))
        <div class="card-header-modern">
            @if(isset($title))
                <h4 class="card-title-modern">
                    @if(isset($icon))
                        <i class="{{ $icon }}"></i>
                    @endif
                    {{ $title }}
                </h4>
            @endif
            
            @if(isset($subtitle))
                <p class="card-subtitle-modern">{{ $subtitle }}</p>
            @endif
            
            @if(isset($actions))
                <div class="card-actions-modern">
                    @foreach($actions as $action)
                        <a href="{{ $action['url'] }}" class="{{ $action['class'] ?? 'btn btn-sm btn-outline-primary' }}">
                            @if(isset($action['icon']))
                                <i class="{{ $action['icon'] }}"></i>
                            @endif
                            {{ $action['text'] }}
                        </a>
                    @endforeach
                </div>
            @endif
        </div>
    @endif
    
    <div class="card-body-modern">
        @if(isset($slot))
            {{ $slot }}
        @else
            {!! $content ?? '' !!}
        @endif
    </div>
    
    @if(isset($footer))
        <div class="card-footer-modern">
            {!! $footer !!}
        </div>
    @endif
</div>

<style>
.modern-card {
    background: var(--bg-primary);
    border: 1px solid var(--border-color);
    border-radius: var(--radius-lg);
    box-shadow: var(--shadow-sm);
    overflow: hidden;
    transition: box-shadow 0.2s ease;
    margin-bottom: 1.5rem;
}

.modern-card:hover {
    box-shadow: var(--shadow-md);
}

.card-header-modern {
    background: linear-gradient(135deg, #f8f9fa, #e9ecef);
    border-bottom: 1px solid var(--border-color);
    padding: 1.25rem 1.5rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 1rem;
}

.card-title-modern {
    margin: 0;
    font-size: 1.25rem;
    font-weight: 600;
    color: var(--text-primary);
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.card-subtitle-modern {
    margin: 0.5rem 0 0;
    color: var(--text-secondary);
    font-size: 0.9rem;
    width: 100%;
}

.card-actions-modern {
    display: flex;
    gap: 0.5rem;
    align-items: center;
}

.card-body-modern {
    padding: 1.5rem;
    color: var(--text-primary);
}

.card-footer-modern {
    background: var(--bg-light);
    border-top: 1px solid var(--border-color);
    padding: 1rem 1.5rem;
    color: var(--text-secondary);
}

/* Variantes de card */
.modern-card.card-primary {
    border-color: var(--primary-color);
}

.modern-card.card-primary .card-header-modern {
    background: linear-gradient(135deg, var(--primary-light), var(--primary-color));
    color: white;
}

.modern-card.card-success {
    border-color: #28a745;
}

.modern-card.card-success .card-header-modern {
    background: linear-gradient(135deg, #d4edda, #28a745);
    color: #155724;
}

.modern-card.card-warning {
    border-color: #ffc107;
}

.modern-card.card-warning .card-header-modern {
    background: linear-gradient(135deg, #fff3cd, #ffc107);
    color: #856404;
}

.modern-card.card-info {
    border-color: #17a2b8;
}

.modern-card.card-info .card-header-modern {
    background: linear-gradient(135deg, #d1ecf1, #17a2b8);
    color: #0c5460;
}

.modern-card.card-compact {
    margin-bottom: 1rem;
}

.modern-card.card-compact .card-header-modern {
    padding: 1rem 1.25rem;
}

.modern-card.card-compact .card-body-modern {
    padding: 1.25rem;
}

@media (max-width: 768px) {
    .card-header-modern {
        flex-direction: column;
        align-items: stretch;
    }
    
    .card-actions-modern {
        width: 100%;
        justify-content: flex-end;
    }
    
    .card-body-modern {
        padding: 1.25rem;
    }
}
</style>