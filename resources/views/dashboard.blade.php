@extends('layouts.app')

@php
    \Carbon\Carbon::setLocale('es');
@endphp

@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.min.css">
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@endpush

<link rel="stylesheet" href="{{ asset('css/Dashboard.css') }}">
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
                        Ingresos Mensuales
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
    });
    
    // Actualizar métricas cada 5 minutos
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
            
            // Mostrar mensaje de éxito
            console.log('Métricas actualizadas correctamente');
        })
        .catch(error => {
            console.error('Error al actualizar métricas:', error);
        });
}
</script>
@endsection
