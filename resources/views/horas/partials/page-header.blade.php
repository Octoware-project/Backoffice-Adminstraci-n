{{--
    Componente: Page Header con Breadcrumbs
    Uso: @include('horas.partials.page-header', ['title' => 'Título', 'breadcrumbs' => [...]])
<div class="page-header-modern">
    <div class="header-content">
        <div class="header-left">
            <h1 class="page-title">
                @if(isset($icon))
                    <i class="{{ $icon }}"></i>
                @endif
                {{ $title }}
            </h1>
            
            @if(isset($subtitle))
                <p class="page-subtitle">{{ $subtitle }}</p>
            @endif
        </div>
        
        <div class="header-right">
            @if(isset($actions))
                <div class="header-actions">
                    @foreach($actions as $action)
                        <a href="{{ $action['url'] }}" class="{{ $action['class'] ?? 'btn btn-primary' }}">
                            @if(isset($action['icon']))
                                <i class="{{ $action['icon'] }}"></i>
                            @endif
                            {{ $action['text'] }}
                        </a>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
    
    @if(isset($breadcrumbs) && count($breadcrumbs) > 0)
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                @foreach($breadcrumbs as $breadcrumb)
                    @if($loop->last)
                        <li class="breadcrumb-item active" aria-current="page">{{ $breadcrumb['text'] }}</li>
                    @else
                        <li class="breadcrumb-item">
                            <a href="{{ $breadcrumb['url'] }}">{{ $breadcrumb['text'] }}</a>
                        </li>
                    @endif
                @endforeach
            </ol>
        </nav>
    @endif
</div>

<style>
.page-header-modern {
    background: var(--bg-primary);
    border-bottom: 1px solid var(--border-color);
    padding: 1.5rem 0;
    margin-bottom: 2rem;
}

.header-content {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 1rem;
}

.header-left {
    flex: 1;
}

.page-title {
    margin: 0;
    font-size: 1.75rem;
    font-weight: 600;
    color: var(--text-primary);
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.page-subtitle {
    margin: 0.5rem 0 0;
    color: var(--text-secondary);
    font-size: 0.9rem;
}

.header-actions {
    display: flex;
    gap: 0.75rem;
}

.breadcrumb {
    background: none;
    padding: 0;
    margin: 0;
}

.breadcrumb-item + .breadcrumb-item::before {
    content: "›";
    color: var(--text-secondary);
}

.breadcrumb-item a {
    color: var(--text-secondary);
    text-decoration: none;
}

.breadcrumb-item a:hover {
    color: var(--primary-color);
}

.breadcrumb-item.active {
    color: var(--text-primary);
    font-weight: 500;
}

@media (max-width: 768px) {
    .header-content {
        flex-direction: column;
        gap: 1rem;
    }
    
    .header-actions {
        width: 100%;
        flex-wrap: wrap;
    }
    
    .page-title {
        font-size: 1.5rem;
    }
}
</style>