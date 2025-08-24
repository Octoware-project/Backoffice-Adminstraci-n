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
    .main-content {
        margin-left: 200px;
        padding: 20px;
    }
</style>
<div class="sidebar">
    <ul>
        <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li><a href="/usuarios">Usuarios</a></li>
        <li><a href="/pagos">Pagos</a></li>
    </ul>
</div>
