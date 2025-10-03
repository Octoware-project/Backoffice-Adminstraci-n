<style>
    .sidebar {
        position: fixed;
        left: 0;
        top: 0;
        width: 240px;
        height: 100vh;
        background: #1e2a38;
        color: #ffffff;
        padding: 0;
        box-shadow: 2px 0 15px rgba(0, 0, 0, 0.3);
        z-index: 1000;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        font-family: 'Segoe UI', 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
    }
    
    .sidebar-content {
        padding: 30px 0 20px 0;
        flex-grow: 1;
    }
    
    .sidebar ul {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    
    .sidebar ul li {
        margin: 4px 16px;
    }
    
    .sidebar ul li a {
        color: #b0b0b0;
        text-decoration: none;
        padding: 14px 20px;
        display: flex;
        align-items: center;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 500;
        letter-spacing: 0.2px;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    
    .sidebar ul li a::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        width: 3px;
        height: 100%;
        background: #4a4a4a;
        transform: scaleY(0);
        transition: transform 0.3s ease;
        border-radius: 0 2px 2px 0;
    }
    
    .sidebar ul li a:hover {
        background: rgba(255, 255, 255, 0.05);
        color: #ffffff;
        transform: translateX(4px);
    }
    
    .sidebar ul li a:hover::before {
        transform: scaleY(1);
    }
    
    .sidebar ul li a.active {
        background: rgba(255, 255, 255, 0.08);
        color: #ffffff;
    }
    
    .sidebar ul li a.active::before {
        transform: scaleY(1);
        background: #666666;
    }
    
    .sidebar ul li a i {
        width: 20px;
        text-align: center;
        margin-right: 12px;
        font-size: 16px;
        opacity: 0.7;
        transition: opacity 0.3s ease;
    }
    
    .sidebar ul li a:hover i,
    .sidebar ul li a.active i {
        opacity: 1;
    }
    
    /* Submenu styles */
    .sidebar ul li {
        position: relative;
    }
    
    .sidebar ul li.has-submenu > a::after {
        content: '\f107';
        font-family: 'Font Awesome 6 Free';
        font-weight: 900;
        position: absolute;
        right: 20px;
        transition: transform 0.3s ease;
    }
    
    .sidebar ul li.has-submenu:hover > a::after,
    .sidebar ul li.has-submenu.active > a::after {
        transform: rotate(180deg);
    }
    
    .submenu {
        position: absolute;
        left: 100%;
        top: 0;
        min-width: 220px;
        background: #243442;
        border-radius: 8px;
        box-shadow: 4px 0 20px rgba(0, 0, 0, 0.4);
        opacity: 0;
        visibility: hidden;
        transform: translateX(-10px);
        transition: all 0.3s ease;
        z-index: 1200;
        border: 1px solid rgba(255, 255, 255, 0.08);
    }
    
    .sidebar ul li.has-submenu:hover .submenu {
        opacity: 1;
        visibility: visible;
        transform: translateX(0);
    }
    
    .submenu ul {
        list-style: none;
        padding: 8px 0;
        margin: 0;
    }
    
    .submenu ul li {
        margin: 2px 8px;
        position: relative;
    }
    
    .submenu ul li a {
        color: #b0b0b0;
        text-decoration: none;
        padding: 10px 16px;
        display: flex;
        align-items: center;
        border-radius: 6px;
        font-size: 13px;
        font-weight: 500;
        transition: all 0.3s ease;
        border: none;
    }
    
    .submenu ul li a::before {
        display: none;
    }
    
    .submenu ul li a:hover {
        background: rgba(255, 255, 255, 0.08);
        color: #ffffff;
        transform: none;
    }
    
    .submenu ul li a i {
        width: 16px;
        font-size: 14px;
        margin-right: 10px;
        opacity: 0.8;
    }
    
    .user-menu {
        position: relative;
        text-align: center;
        margin: 0;
        padding: 20px;
        border-top: 1px solid rgba(255, 255, 255, 0.08);
        background: rgba(0, 0, 0, 0.2);
    }
    
    .user-menu:hover .dropdown-menu {
        opacity: 1;
        visibility: visible;
        transform: translateX(0);
    }
    
    .user-icon {
        font-size: 20px;
        cursor: pointer;
        color: #b0b0b0;
        background: #333333;
        border: 2px solid rgba(255, 255, 255, 0.08);
        outline: none;
        width: 48px;
        height: 48px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
        margin: 0 auto;
    }
    
    .user-icon:hover {
        transform: scale(1.05);
        background: #404040;
        color: #ffffff;
        border-color: rgba(255, 255, 255, 0.15);
    }
    
    .dropdown-menu {
        position: absolute;
        left: 100%;
        bottom: 0;
        min-width: 200px;
        background: #243442;
        border-radius: 8px;
        box-shadow: 4px 0 20px rgba(0, 0, 0, 0.4);
        opacity: 0;
        visibility: hidden;
        transform: translateX(-10px);
        transition: all 0.3s ease;
        z-index: 1200;
        border: 1px solid rgba(255, 255, 255, 0.08);
        padding: 8px 0;
        overflow: hidden;
        margin-left: 10px;
    }
    
    .dropdown-menu.show {
        opacity: 1;
        visibility: visible;
        transform: translateX(0);
    }
    
    .dropdown-menu a,
    .dropdown-menu button {
        color: #b0b0b0;
        text-decoration: none;
        display: flex;
        align-items: center;
        width: 100%;
        padding: 10px 16px;
        background: none;
        border: none;
        text-align: left;
        font-size: 13px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s ease;
        font-family: inherit;
        border-radius: 6px;
        margin: 2px 8px;
    }
    
    .dropdown-menu a:hover,
    .dropdown-menu button:hover {
        background: rgba(255, 255, 255, 0.08);
        color: #ffffff;
        transform: none;
    }
    
    .dropdown-menu a i,
    .dropdown-menu button i {
        width: 16px;
        text-align: center;
        margin-right: 10px;
        opacity: 0.8;
        font-size: 14px;
    }
    
    /* Responsive adjustments */
    @media (max-width: 768px) {
        .sidebar {
            width: 200px;
        }
        
        .dropdown-menu {
            left: auto;
            right: 100%;
            transform: translateX(10px);
            margin-left: 0;
            margin-right: 10px;
        }
        
        .user-menu:hover .dropdown-menu {
            transform: translateX(0);
        }
        
        .submenu {
            left: auto;
            right: 100%;
            transform: translateX(10px);
        }
        
        .sidebar ul li.has-submenu:hover .submenu {
            transform: translateX(0);
        }
    }
    
    /* Custom scrollbar for sidebar */
    .sidebar::-webkit-scrollbar {
        width: 4px;
    }
    
    .sidebar::-webkit-scrollbar-track {
        background: rgba(255, 255, 255, 0.03);
    }
    
    .sidebar::-webkit-scrollbar-thumb {
        background: rgba(255, 255, 255, 0.1);
        border-radius: 2px;
    }
    
    .sidebar::-webkit-scrollbar-thumb:hover {
        background: rgba(255, 255, 255, 0.15);
    }
    
    /* Badge para contador de pendientes */
    .nav-badge {
        position: absolute;
        top: -2px;
        right: 8px;
        background: #f59e0b;
        color: white;
        font-size: 10px;
        font-weight: 700;
        padding: 2px 6px;
        border-radius: 10px;
        min-width: 16px;
        text-align: center;
        line-height: 1.2;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.3);
        animation: pulse 2s infinite;
    }
    
    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.7; }
    }
    
    .sidebar ul li {
        position: relative;
    }
    
    /* Ocultar badge cuando no hay pendientes */
    .nav-badge.hidden {
        display: none;
    }
</style>
<div class="sidebar">
    <div class="sidebar-content">
        <ul>
            <li><a href="{{ route('dashboard') }}">
                <i class="fas fa-tachometer-alt"></i>
                Dashboard
            </a></li>
            <li class="has-submenu">
                <a href="/usuarios">
                    <i class="fas fa-users"></i>
                    Usuarios
                </a>
                <div class="submenu">
                    <ul>
                        <li><a href="{{ route('usuarios.index') }}">
                            <i class="fas fa-user-check"></i>
                            Residentes
                        </a></li>
                        <li><a href="{{ route('usuarios.pendientes') }}">
                            <i class="fas fa-clock"></i>
                            Usuarios Pendientes
                            @if(isset($usuariosPendientes) && $usuariosPendientes > 0)
                                <span class="nav-badge" style="position: relative; top: 0; right: 0; margin-left: 8px;">{{ $usuariosPendientes }}</span>
                            @endif
                        </a></li>
                    </ul>
                </div>
            </li>
            <li><a href="/facturas">
                <i class="fas fa-credit-card"></i>
                Pagos
            </a></li>
            <li class="has-submenu">
                <a href="{{ route('plan-trabajos.index') }}">
                    <i class="fas fa-tasks"></i>
                    Planes de Trabajo
                </a>
                <div class="submenu">
                    <ul>
                        <li><a href="{{ route('plan-trabajos.index') }}">
                            <i class="fas fa-list"></i>
                            Planes de Trabajo
                        </a></li>
                        <li><a href="{{ route('plan-trabajos.create') }}">
                            <i class="fas fa-plus"></i>
                            Crear nuevo plan
                        </a></li>
                        <li><a href="{{ route('configuracion-horas.index') }}">
                            <i class="fas fa-cog"></i>
                            Configuración
                        </a></li>
                    </ul>
                </div>
            </li>
            <li class="has-submenu">
                <a href="{{ route('unidades.index') }}">
                    <i class="fas fa-building"></i>
                    Unidades Habitacionales
                </a>
                <div class="submenu">
                    <ul>
                        <li><a href="{{ route('unidades.index') }}">
                            <i class="fas fa-list"></i>
                            Ver Unidades
                        </a></li>
                        <li><a href="{{ route('unidades.create') }}">
                            <i class="fas fa-plus"></i>
                            Crear Unidad
                        </a></li>
                    </ul>
                </div>
            </li>
            <li class="has-submenu">
                <a href="{{ route('admin.juntas_asamblea.index') }}">
                    <i class="fas fa-gavel"></i>
                    Asamblea
                </a>
                <div class="submenu">
                    <ul>
                        <li><a href="{{ route('admin.juntas_asamblea.index') }}">
                            <i class="fas fa-list"></i>
                            Ver Juntas
                        </a></li>
                        <li><a href="{{ route('admin.juntas_asamblea.create') }}">
                            <i class="fas fa-plus"></i>
                            Crear Junta
                        </a></li>
                    </ul>
                </div>
            </li>
        </ul>
    </div>
    <div class="user-menu">
        <button class="user-icon" id="userMenuBtn">
            <i class="fas fa-user"></i>
        </button>
        <div class="dropdown-menu" id="userDropdown">
            <form method="POST" action="{{ route('logout') }}" style="margin:0;">
                @csrf
                <button type="submit">
                    <i class="fas fa-sign-out-alt"></i>
                    Cerrar Sesión
                </button>
            </form>
            <a href="/octoware">
                <i class="fas fa-building"></i>
                Octoware
            </a>
            <a href="{{ route('admin.list') }}">
                <i class="fas fa-user-shield"></i>
                Administradores
            </a>
        </div>
    </div>
</div>
<!-- Font Awesome CDN (if not already included) -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Handle user menu dropdown similar to submenu
        const userMenu = document.querySelector('.user-menu');
        const userDropdown = document.getElementById('userDropdown');
        let userHoverTimeout;
        
        userMenu.addEventListener('mouseenter', function() {
            clearTimeout(userHoverTimeout);
            userDropdown.classList.add('show');
        });
        
        userMenu.addEventListener('mouseleave', function() {
            userHoverTimeout = setTimeout(() => {
                userDropdown.classList.remove('show');
            }, 100);
        });
        
        // Keep dropdown open when hovering over it
        userDropdown.addEventListener('mouseenter', function() {
            clearTimeout(userHoverTimeout);
            userDropdown.classList.add('show');
        });
        
        userDropdown.addEventListener('mouseleave', function() {
            userHoverTimeout = setTimeout(() => {
                userDropdown.classList.remove('show');
            }, 100);
        });
        
        // Still support click functionality
        const btn = document.getElementById('userMenuBtn');
        btn.addEventListener('click', function(e) {
            e.stopPropagation();
            userDropdown.classList.toggle('show');
        });
        
        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
            if (!btn.contains(e.target) && !userDropdown.contains(e.target)) {
                userDropdown.classList.remove('show');
            }
        });
        
        // Close dropdown on escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                userDropdown.classList.remove('show');
            }
        });
        
        // Add active class to current page link
        const currentPath = window.location.pathname;
        const navLinks = document.querySelectorAll('.sidebar ul li a');
        
        navLinks.forEach(link => {
            const linkPath = new URL(link.href).pathname;
            
            // Manejar específicamente las rutas de usuarios
            if (currentPath === '/usuarios' || currentPath === '/usuarios/pendientes') {
                // Marcar el menú principal de usuarios como activo
                const usuariosMainLink = document.querySelector('a[href="/usuarios"]');
                if (usuariosMainLink) {
                    usuariosMainLink.classList.add('active');
                    const parentLi = usuariosMainLink.parentElement;
                    if (parentLi) {
                        parentLi.classList.add('active');
                    }
                }
                
                // Marcar el submenu específico como activo
                if (currentPath === '/usuarios') {
                    const aceptadosLink = document.querySelector('a[href*="usuarios.index"]');
                    if (aceptadosLink) aceptadosLink.classList.add('active');
                } else if (currentPath === '/usuarios/pendientes') {
                    const pendientesLink = document.querySelector('a[href*="usuarios.pendientes"]');
                    if (pendientesLink) pendientesLink.classList.add('active');
                }
            }
            // Manejar otras rutas normalmente
            else if (currentPath === linkPath || 
                (currentPath.startsWith(linkPath) && linkPath !== '/' && linkPath.length > 1)) {
                link.classList.add('active');
                
                // If it's a submenu item, also mark the parent as active
                const parentLi = link.closest('.submenu')?.parentElement;
                if (parentLi) {
                    parentLi.classList.add('active');
                    parentLi.querySelector('a').classList.add('active');
                }
            }
        });
        
        // Handle submenu interactions
        const hasSubmenuItems = document.querySelectorAll('.has-submenu');
        
        hasSubmenuItems.forEach(item => {
            const submenu = item.querySelector('.submenu');
            let hoverTimeout;
            
            item.addEventListener('mouseenter', function() {
                clearTimeout(hoverTimeout);
                item.classList.add('active');
            });
            
            item.addEventListener('mouseleave', function() {
                hoverTimeout = setTimeout(() => {
                    item.classList.remove('active');
                }, 100);
            });
            
            // Keep submenu open when hovering over it
            if (submenu) {
                submenu.addEventListener('mouseenter', function() {
                    clearTimeout(hoverTimeout);
                    item.classList.add('active');
                });
                
                submenu.addEventListener('mouseleave', function() {
                    hoverTimeout = setTimeout(() => {
                        item.classList.remove('active');
                    }, 100);
                });
            }
        });
    });
</script>
