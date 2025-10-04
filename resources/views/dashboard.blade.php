@extends('layouts.app')

@php
    \Carbon\Carbon::setLocale('es');
@endphp

@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.min.css">
    
    <style>
        /* Variables CSS del sistema */
        :root {
            --primary-color: #667eea;
            --primary-light: #764ba2;
            --secondary-color: #f093fb;
            --success-color: #4ecdc4;
            --warning-color: #ffb74d;
            --danger-color: #fc466b;
            --info-color: #54a0ff;
            --text-primary: #1a202c;
            --text-secondary: #2d3748;
            --text-muted: #4a5568;
            --bg-primary: #ffffff;
            --bg-secondary: #f7fafc;
            --bg-light: #edf2f7;
            --border-color: #a0aec0;
            --shadow-sm: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            --radius: 12px;
            --radius-sm: 8px;
        }

        /* Background moderno */
        body {
            background-color: #d2d2f1ff !important;
            overflow-x: hidden;
        }

        /* Ocultar barra de scroll pero mantener funcionalidad */
        body::-webkit-scrollbar {
            display: none;
        }

        body {
            -ms-overflow-style: none;  /* Internet Explorer 10+ */
            scrollbar-width: none;  /* Firefox */
        }

        /* Aplicar a todos los elementos con scroll */
        *::-webkit-scrollbar {
            display: none;
        }

        * {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        /* Container principal del dashboard */
        .dashboard-workspace {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 1.5rem;
            min-height: 100vh;
            background-color: #d2d2f1ff;
        }

        /* Header del dashboard */
        .dashboard-header {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-light) 100%);
            border-radius: var(--radius);
            padding: 2rem 2.5rem;
            margin-bottom: 2rem;
            box-shadow: var(--shadow-lg);
            color: white;
            position: relative;
            overflow: hidden;
        }

        .dashboard-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -20%;
            width: 300px;
            height: 300px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            transform: rotate(45deg);
        }

        .dashboard-header .header-content {
            position: relative;
            z-index: 2;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .dashboard-title {
            font-size: 2.5rem;
            font-weight: 700;
            margin: 0;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .dashboard-subtitle {
            font-size: 1.1rem;
            opacity: 0.9;
            margin-top: 0.5rem;
        }

        .dashboard-time {
            background: rgba(255, 255, 255, 0.2);
            padding: 0.75rem 1.5rem;
            border-radius: 50px;
            font-weight: 500;
            backdrop-filter: blur(10px);
        }

        /* Grid de métricas - Por defecto 2 filas x 3 columnas */
        .metrics-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            grid-template-rows: repeat(2, 1fr);
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        /* Cards de métricas */
        .metric-card {
            background: var(--bg-primary);
            border-radius: var(--radius);
            padding: 1.5rem;
            box-shadow: var(--shadow-md);
            border: 1px solid rgba(255, 255, 255, 0.8);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .metric-card:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }

        .metric-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
        }

        .metric-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }

        .metric-icon {
            width: 48px;
            height: 48px;
            border-radius: var(--radius-sm);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.25rem;
        }

        .metric-title {
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .metric-value {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--text-primary);
            margin: 0.5rem 0;
        }

        .metric-change {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.875rem;
            font-weight: 500;
        }

        .metric-change.positive {
            color: var(--success-color);
        }

        .metric-change.negative {
            color: var(--danger-color);
        }

        .metric-change.warning {
            color: var(--warning-color);
        }

        .metric-change.neutral {
            color: var(--text-muted);
        }

        /* Grid de secciones principales - Una columna siempre */
        .main-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 2rem;
            margin-bottom: 2rem;
        }

        /* Charts container */
        .chart-container {
            background: var(--bg-primary);
            border-radius: var(--radius);
            padding: 1.5rem;
            box-shadow: var(--shadow-md);
            border: 1px solid rgba(255, 255, 255, 0.8);
            margin-bottom: 1.5rem;
        }

        .chart-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid var(--border-color);
        }

        .chart-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--text-primary);
        }

        .chart-canvas {
            max-height: 400px;
            width: 100%;
        }

        /* Para pantallas muy grandes - mantener 2x3 */
        @media (min-width: 1400px) {
            .metrics-grid {
                grid-template-columns: repeat(3, 1fr);
                grid-template-rows: repeat(2, 1fr);
                max-width: 1200px;
                margin: 0 auto 2rem auto;
            }
            
            .chart-canvas {
                max-height: 450px;
            }
        }



        /* Accesos rápidos - Siempre 2 filas de 3 columnas */
        .quick-actions {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            grid-template-rows: repeat(2, 1fr);
            gap: 1rem;
            margin-top: 2rem;
        }

        .quick-action {
            background: var(--bg-primary);
            border-radius: var(--radius);
            padding: 1.5rem;
            text-align: center;
            box-shadow: var(--shadow-sm);
            border: 1px solid rgba(255, 255, 255, 0.8);
            transition: all 0.3s ease;
            text-decoration: none;
            color: var(--text-primary);
        }

        .quick-action:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
            text-decoration: none;
            color: var(--primary-color);
        }

        .quick-action-icon {
            font-size: 2rem;
            margin-bottom: 0.5rem;
            color: var(--primary-color);
            transition: transform 0.3s ease;
        }

        .quick-action:hover .quick-action-icon {
            transform: scale(1.1);
        }

        .quick-action-title {
            font-weight: 600;
            margin-bottom: 0.25rem;
        }

        .quick-action-desc {
            font-size: 0.85rem;
            color: var(--text-muted);
        }

        /* Chart responsive */
        .chart-canvas {
            max-height: 300px;
        }

        /* Animaciones */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .metric-card,
        .chart-container,
        .alerts-container,
        .activity-container,
        .quick-action {
            animation: fadeInUp 0.6s ease-out;
        }

        /* Staggered animations */
        .metric-card:nth-child(1) { animation-delay: 0.1s; }
        .metric-card:nth-child(2) { animation-delay: 0.2s; }
        .metric-card:nth-child(3) { animation-delay: 0.3s; }
        .metric-card:nth-child(4) { animation-delay: 0.4s; }
        .metric-card:nth-child(5) { animation-delay: 0.5s; }
        .metric-card:nth-child(6) { animation-delay: 0.6s; }

        /* Responsive design */
        @media (max-width: 1024px) {
            .main-grid {
                gap: 1rem;
            }
            
            .metrics-grid {
                grid-template-columns: repeat(2, 1fr);
                grid-template-rows: repeat(3, 1fr);
            }
            
            .chart-canvas {
                max-height: 320px;
            }
        }

        @media (max-width: 1200px) {
            .dashboard-workspace {
                padding: 0 1rem;
            }
        }

        @media (max-width: 768px) {
            .dashboard-header {
                padding: 1.5rem 1rem;
            }
            
            .dashboard-header .header-content {
                flex-direction: column;
                text-align: center;
                gap: 1.5rem;
            }
            
            .dashboard-title {
                font-size: 2rem;
            }
            
            .metrics-grid {
                grid-template-columns: 1fr;
                gap: 1rem;
            }
            
            .quick-actions {
                grid-template-columns: repeat(2, 1fr);
                grid-template-rows: repeat(3, 1fr);
            }
            
            .chart-canvas {
                max-height: 280px;
            }
            
            .chart-container {
                padding: 1rem;
                margin-bottom: 1rem;
            }
        }

        @media (max-width: 480px) {
            .quick-actions {
                grid-template-columns: 1fr;
                grid-template-rows: repeat(6, 1fr);
            }
            
            .dashboard-workspace {
                padding: 0 0.5rem;
            }
            
            .metric-value {
                font-size: 2rem;
            }
            
            .chart-canvas {
                max-height: 220px;
            }
            
            .chart-container {
                padding: 0.75rem;
                margin-bottom: 1rem;
            }
            
            .chart-title {
                font-size: 1rem;
            }
        }

        @media (max-width: 360px) {
            .chart-canvas {
                max-height: 200px;
            }
            
            .chart-container {
                padding: 0.5rem;
            }
            
            .main-grid {
                gap: 0.75rem;
            }
        }
    </style>
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@endpush

@section('content')
<div class="dashboard-workspace">
    <!-- Header del Dashboard -->
    <div class="dashboard-header">
        <div class="header-content">
            <div>
                <h1 class="dashboard-title">
                    <i class="fas fa-tachometer-alt me-3"></i>
                    Dashboard
                </h1>
                <p class="dashboard-subtitle">
                    Bienvenido, {{ Auth::user()->name ?? Auth::user()->email }}
                </p>
            </div>
            <div class="dashboard-time">
                <i class="fas fa-calendar-alt me-2"></i>
                <span id="current-date"></span>
            </div>
        </div>
    </div>

    <!-- Métricas Principales -->
    <div class="metrics-grid">
        <!-- Widget de Usuarios Totales -->
        <div class="metric-card">
            <div class="metric-header">
                <div>
                    <div class="metric-title">Total de Residentes</div>
                    <div class="metric-value" id="total-usuarios">{{ $metrics['total_usuarios'] }}</div>
                    <div class="metric-change positive">
                        <i class="fas fa-user-check"></i>
                        <span>+{{ $metrics['usuarios_aceptados_mes'] }} aceptados este mes</span>
                    </div>
                </div>
                <div class="metric-icon" style="background: linear-gradient(135deg, #667eea, #764ba2);">
                    <i class="fas fa-users"></i>
                </div>
            </div>
        </div>

        <!-- Widget de Usuarios Pendientes -->
        <div class="metric-card">
            <div class="metric-header">
                <div>
                    <div class="metric-title">Usuarios Pendientes</div>
                    <div class="metric-value" id="usuarios-pendientes" style="color: {{ $metrics['usuarios_pendientes'] > 5 ? '#fc466b' : '#4ecdc4' }}">
                        {{ $metrics['usuarios_pendientes'] }}
                    </div>
                    <div class="metric-change {{ $metrics['usuarios_pendientes'] > 0 ? 'warning' : 'positive' }}">
                        <i class="fas fa-{{ $metrics['usuarios_pendientes'] > 0 ? 'exclamation-triangle' : 'check-circle' }}"></i>
                        <span>{{ $metrics['usuarios_pendientes'] > 0 ? 'Requiere atención' : 'Todo al día' }}</span>
                    </div>
                </div>
                <div class="metric-icon" style="background: linear-gradient(135deg, #ffb74d, #ffa726);">
                    <i class="fas fa-user-clock"></i>
                </div>
            </div>
        </div>

        <!-- Widget de Ingresos -->
        <div class="metric-card">
            <div class="metric-header">
                <div>
                    <div class="metric-title">Ingresos del Mes</div>
                    <div class="metric-value">${{ number_format($metrics['ingresos_mes'], 0, ',', '.') }}</div>
                    <div class="metric-change positive">
                        <i class="fas fa-chart-line"></i>
                        <span>{{ $metrics['porcentaje_cobro'] }}% de cobro</span>
                    </div>
                </div>
                <div class="metric-icon" style="background: linear-gradient(135deg, #4ecdc4, #44a08d);">
                    <i class="fas fa-dollar-sign"></i>
                </div>
            </div>
        </div>

        <!-- Widget de Facturas Pendientes -->
        <div class="metric-card">
            <div class="metric-header">
                <div>
                    <div class="metric-title">Facturas Pendientes</div>
                    <div class="metric-value" style="color: {{ $metrics['facturas_pendientes'] > 10 ? '#fc466b' : '#4ecdc4' }}">
                        {{ $metrics['facturas_pendientes'] }}
                    </div>
                    <div class="metric-change neutral">
                        <i class="fas fa-clock"></i>
                        <span>Por procesar</span>
                    </div>
                </div>
                <div class="metric-icon" style="background: linear-gradient(135deg, #fc466b, #ff6b9d);">
                    <i class="fas fa-file-invoice"></i>
                </div>
            </div>
        </div>

        <!-- Widget de Unidades -->
        <div class="metric-card">
            <div class="metric-header">
                <div>
                    <div class="metric-title">Unidades Ocupadas</div>
                    <div class="metric-value">{{ $metrics['unidades_ocupadas'] }}/{{ $metrics['total_unidades'] }}</div>
                    <div class="metric-change positive">
                        <i class="fas fa-percentage"></i>
                        <span>{{ $metrics['porcentaje_ocupacion'] }}% ocupación</span>
                    </div>
                </div>
                <div class="metric-icon" style="background: linear-gradient(135deg, #54a0ff, #2e86de);">
                    <i class="fas fa-home"></i>
                </div>
            </div>
        </div>

        <!-- Widget de Planes de Trabajo -->
        <div class="metric-card">
            <div class="metric-header">
                <div>
                    <div class="metric-title">Planes Activos</div>
                    <div class="metric-value">{{ $metrics['planes_activos'] }}</div>
                    <div class="metric-change neutral">
                        <i class="fas fa-calendar-alt"></i>
                        <span>En ejecución</span>
                    </div>
                </div>
                <div class="metric-icon" style="background: linear-gradient(135deg, #f093fb, #f5576c);">
                    <i class="fas fa-tasks"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Grid Principal: Gráficos y Información Lateral -->
    <div class="main-grid">
        <!-- Sección de Gráficos -->
        <div>
            <!-- Gráfico de Ingresos -->
            <div class="chart-container">
                <div class="chart-header">
                    <h3 class="chart-title">
                        <i class="fas fa-chart-area me-2"></i>
                        Ingresos Mensuales (Últimos 6 meses)
                    </h3>
                </div>
                <canvas id="incomeChart" class="chart-canvas"></canvas>
            </div>

            <!-- Gráfico de Usuarios -->
            <div class="chart-container">
                <div class="chart-header">
                    <h3 class="chart-title">
                        <i class="fas fa-user-check me-2"></i>
                        Usuarios Aceptados por Mes
                    </h3>
                </div>
                <canvas id="usersChart" class="chart-canvas"></canvas>
            </div>
        </div>

    </div>

    <!-- Accesos Rápidos -->
    <div class="quick-actions">
        <a href="{{ route('usuarios.pendientes') }}" class="quick-action">
            <div class="quick-action-icon">
                <i class="fas fa-user-check"></i>
            </div>
            <div class="quick-action-title">Aprobar Usuarios</div>
            <div class="quick-action-desc">Gestionar usuarios pendientes</div>
        </a>

        <a href="{{ route('admin.facturas.index') }}" class="quick-action">
            <div class="quick-action-icon">
                <i class="fas fa-file-invoice-dollar"></i>
            </div>
            <div class="quick-action-title">Gestionar Facturas</div>
            <div class="quick-action-desc">Ver y administrar facturas</div>
        </a>

        <a href="{{ route('plan-trabajos.create') }}" class="quick-action">
            <div class="quick-action-icon">
                <i class="fas fa-plus-circle"></i>
            </div>
            <div class="quick-action-title">Nuevo Plan</div>
            <div class="quick-action-desc">Crear plan de trabajo</div>
        </a>

        <a href="{{ route('unidades.create') }}" class="quick-action">
            <div class="quick-action-icon">
                <i class="fas fa-home"></i>
            </div>
            <div class="quick-action-title">Agregar Unidad</div>
            <div class="quick-action-desc">Registrar nueva unidad</div>
        </a>

        <a href="{{ route('admin.juntas_asamblea.create') }}" class="quick-action">
            <div class="quick-action-icon">
                <i class="fas fa-calendar-plus"></i>
            </div>
            <div class="quick-action-title">Nueva Asamblea</div>
            <div class="quick-action-desc">Programar reunión</div>
        </a>

        <button class="quick-action" onclick="updateMetrics()">
            <div class="quick-action-icon">
                <i class="fas fa-sync-alt"></i>
            </div>
            <div class="quick-action-title">Actualizar Datos</div>
            <div class="quick-action-desc">Refrescar métricas</div>
        </button>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Actualizar fecha
    function updateDate() {
        const now = new Date();
        const dateString = now.toLocaleString('es-ES', {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        });
        document.getElementById('current-date').textContent = dateString;
    }
    
    updateDate();

    // Configuración base para gráficos (ingresos - con decimales)
    const chartOptions = {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: false
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                grid: {
                    color: '#f1f5f9'
                }
            },
            x: {
                grid: {
                    display: false
                }
            }
        }
    };

    // Configuración para gráfico de usuarios (solo enteros)
    const usersChartOptions = {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: false
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                grid: {
                    color: '#f1f5f9'
                },
                ticks: {
                    stepSize: 1,
                    callback: function(value) {
                        return Number.isInteger(value) ? value : '';
                    }
                }
            },
            x: {
                grid: {
                    display: false
                }
            }
        }
    };

    // Función para obtener número de meses según el tamaño de pantalla
    function getMonthsToShow() {
        const screenWidth = window.innerWidth;
        if (screenWidth <= 480) return 3; // Móviles pequeños: 3 meses
        if (screenWidth <= 768) return 4; // Móviles: 4 meses
        if (screenWidth <= 1024) return 5; // Tablets: 5 meses
        return 6; // Desktop: 6 meses
    }

    // Datos completos del servidor
    const fullLabels = ['{{ \Carbon\Carbon::now()->locale('es')->subMonths(5)->translatedFormat("M") }}', '{{ \Carbon\Carbon::now()->locale('es')->subMonths(4)->translatedFormat("M") }}', '{{ \Carbon\Carbon::now()->locale('es')->subMonths(3)->translatedFormat("M") }}', '{{ \Carbon\Carbon::now()->locale('es')->subMonths(2)->translatedFormat("M") }}', '{{ \Carbon\Carbon::now()->locale('es')->subMonths(1)->translatedFormat("M") }}', '{{ \Carbon\Carbon::now()->locale('es')->translatedFormat("M") }}'];
    const fullIngresoData = [{{ implode(',', $metrics['ingresos_mensuales']) }}];
    const fullUsuariosData = [{{ implode(',', $metrics['usuarios_mensuales']) }}];

    // Función para obtener datos filtrados según el tamaño de pantalla
    function getFilteredData() {
        const monthsToShow = getMonthsToShow();
        const startIndex = 6 - monthsToShow;
        
        return {
            labels: fullLabels.slice(startIndex),
            ingresos: fullIngresoData.slice(startIndex),
            usuarios: fullUsuariosData.slice(startIndex)
        };
    }

    // Crear gráficos con datos adaptivos
    let incomeChart, usersChart;

    function createCharts() {
        const filteredData = getFilteredData();

        // Destruir gráficos existentes si existen
        if (incomeChart) incomeChart.destroy();
        if (usersChart) usersChart.destroy();

        // Gráfico de Ingresos
        const incomeCtx = document.getElementById('incomeChart').getContext('2d');
        incomeChart = new Chart(incomeCtx, {
            type: 'line',
            data: {
                labels: filteredData.labels,
                datasets: [{
                    label: 'Ingresos',
                    data: filteredData.ingresos,
                    borderColor: '#667eea',
                    backgroundColor: 'rgba(102, 126, 234, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: chartOptions
        });

        // Gráfico de Usuarios
        const usersCtx = document.getElementById('usersChart').getContext('2d');
        usersChart = new Chart(usersCtx, {
            type: 'bar',
            data: {
                labels: filteredData.labels,
                datasets: [{
                    label: 'Usuarios Aceptados',
                    data: filteredData.usuarios,
                    backgroundColor: 'rgba(102, 126, 234, 0.8)',
                    borderColor: '#667eea',
                    borderWidth: 2,
                    borderRadius: 8
                }]
            },
            options: usersChartOptions
        });
    }

    // Crear gráficos inicialmente
    createCharts();

    // Recrear gráficos cuando cambie el tamaño de pantalla
    let resizeTimeout;
    window.addEventListener('resize', function() {
        clearTimeout(resizeTimeout);
        resizeTimeout = setTimeout(function() {
            createCharts();
        }, 250);
    });    // Actualizar métricas cada 5 minutos
    setInterval(function() {
        updateMetrics();
    }, 300000);
});

// Función para actualizar métricas
function updateMetrics() {
    fetch('/dashboard/metrics')
        .then(response => response.json())
        .then(data => {
            document.getElementById('total-usuarios').textContent = data.total_usuarios || 0;
            document.getElementById('usuarios-pendientes').textContent = data.usuarios_pendientes || 0;
            
            // Actualizar colores según valores
            const pendientesElement = document.getElementById('usuarios-pendientes');
            if (pendientesElement) {
                pendientesElement.style.color = data.usuarios_pendientes > 5 ? '#fc466b' : '#4ecdc4';
            }
            
            console.log('Métricas actualizadas');
        })
        .catch(error => console.log('Error al actualizar métricas:', error));
}
</script>
@endsection
