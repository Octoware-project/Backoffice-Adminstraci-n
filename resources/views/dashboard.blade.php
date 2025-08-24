<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    @include('componentes.navbar')
    <div class="main-content container mt-4">
        <h2>Bienvenido, {{ Auth::user()->name ?? Auth::user()->email }}</h2>
        <!-- Contenido del dashboard -->
    </div>
</body>
</html>
                <li class="nav-item">
                    <a class="nav-link" href="/usuarios">Aceptar/Rechazar Usuarios</a>
                </li>
            </ul>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="btn btn-outline-danger" type="submit">Cerrar sesión</button>
            </form>
        </div>
    </div>
</nav>
<div class="container mt-4">
    <h2>Bienvenido, {{ Auth::user()->name ?? Auth::user()->email }}</h2>
    <!-- Contenido del dashboard -->
</div>
</body>
</html>
