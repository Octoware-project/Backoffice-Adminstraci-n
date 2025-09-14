<style>
    .sidebar {
        position: fixed;
        left: 0;
        top: 0;
        width: 200px;
        height: 100%;
        background: #2c3e50;
        color: #fff;
        padding-top: 30px;
        box-shadow: 2px 0 5px rgba(0,0,0,0.1);
        z-index: 1000;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }
    .sidebar-content {
        /* Contenedor para el contenido principal de la sidebar */
    }
    .sidebar ul {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    .sidebar ul li {
        margin: 20px 0;
    }
    .sidebar ul li a {
        color: #fff;
        text-decoration: none;
        padding: 10px 20px;
        display: block;
        transition: background 0.2s;
    }
    .sidebar ul li a:hover {
        background: #34495e;
    }
    .user-menu {
        position: relative;
        text-align: center;
        margin-bottom: 20px;
        margin-top: auto;
        padding-bottom: 20px;
        min-height: 60px; /* asegura espacio para el icono */
        display: flex;
        align-items: flex-end;
        justify-content: center;
    }
    .user-icon {
        font-size: 32px;
        cursor: pointer;
        color: #fff;
        background: none;
        border: none;
        outline: none;
    }
    .dropdown-menu {
        display: none;
        position: absolute;
        right: -190px;
        left: auto;
        bottom: 40px;
        background: #34495e;
        border-radius: 5px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.15);
        min-width: 180px;
        max-width: 220px;
        z-index: 1100;
        padding: 6px 0;
        overflow: hidden;
    }
    .dropdown-menu.show {
        display: block;
    }
    .dropdown-menu a,
    .dropdown-menu button {
        color: #fff;
        text-decoration: none;
        display: block;
        width: 100%;
        padding: 10px 20px;
        background: none;
        border: none;
        text-align: left;
        font-size: 1rem;
        cursor: pointer;
        transition: background 0.2s;
        font-family: inherit;
    }
    .dropdown-menu a:hover,
    .dropdown-menu button:hover {
        background: #2c3e50;
    }
</style>
<div class="sidebar">
    <div class="sidebar-content">
        <ul>
            <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li><a href="/usuarios">Usuarios</a></li>
            <li><a href="/facturas">Pagos</a></li>
            <li><a href="{{ route('plan-trabajos.index') }}">Planes de Trabajo</a></li>
            <li><a href="{{ route('admin.asamblea.index') }}">Asamblea</a></li>
        </ul>
    </div>
    <div class="user-menu">
        <button class="user-icon" id="userMenuBtn">
            <!-- Font Awesome user icon (or SVG) -->
            <i class="fa fa-user"></i>
        </button>
        <div class="dropdown-menu" id="userDropdown">
            <form method="POST" action="{{ route('logout') }}" style="margin:0;">
                @csrf
                <button type="submit">
                    Cerrar Sesión
                </button>
            </form>
            <a href="/octoware">Octoware</a>
            <a href="{{ route('admin.list') }}">Administradores</a>
        </div>
    </div>
</div>
<!-- Font Awesome CDN (if not already included) -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<script>
    // Simple JS to toggle dropdown
    document.addEventListener('DOMContentLoaded', function() {
        var btn = document.getElementById('userMenuBtn');
        var dropdown = document.getElementById('userDropdown');
        btn.addEventListener('click', function(e) {
            e.stopPropagation();
            dropdown.classList.toggle('show');
        });
        document.addEventListener('click', function(e) {
            if (!btn.contains(e.target)) {
                dropdown.classList.remove('show');
            }
        });
    });
</script>
