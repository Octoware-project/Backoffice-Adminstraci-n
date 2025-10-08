@extends('layouts.app')

@section('content')
    <style>
        /* ===== VARIABLES CSS - OCTOWARE ===== */
        :root {
            --primary-color: #667eea;
            --primary-light: #764ba2;
            --secondary-color: #f093fb;
            --success-color: #4ecdc4;
            --warning-color: #ffecd2;
            --danger-color: #fc466b;
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
            --transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* Background moderno - Sin scrollbar */
        body {
            background-color: #d2d2f1ff !important;
            font-family: 'Segoe UI', Arial, sans-serif;
            margin: 0;
            padding: 0;
            overflow: hidden;
        }

        /* Ocultar scrollbars en todos los navegadores */
        html, body {
            -ms-overflow-style: none;  /* Internet Explorer 10+ */
            scrollbar-width: none;  /* Firefox */
        }

        html::-webkit-scrollbar, 
        body::-webkit-scrollbar {
            display: none;  /* Safari and Chrome */
        }

        /* Container principal - Sin scroll visible */
        .octoware-workspace {
            padding: 1.5rem;
            height: 100vh;
            background-color: #d2d2f1ff;
            width: 100%;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        .octoware-workspace::-webkit-scrollbar {
            display: none;
        }

        /* Header moderno con gradiente */
        .octoware-header {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-light) 100%);
            border-radius: var(--radius);
            padding: 1.5rem 2rem;
            margin-bottom: 1.5rem;
            box-shadow: var(--shadow-lg);
            color: white;
            position: relative;
            overflow: hidden;
            flex-shrink: 0;
        }

        .octoware-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="rgba(255,255,255,0.1)"/><circle cx="75" cy="75" r="1" fill="rgba(255,255,255,0.05)"/><circle cx="50" cy="10" r="0.5" fill="rgba(255,255,255,0.1)"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
            opacity: 0.3;
            pointer-events: none;
        }

        .header-content {
            position: relative;
            z-index: 1;
        }

        .octoware-title {
            margin: 0;
            font-size: 1.875rem;
            font-weight: 700;
            letter-spacing: -0.025em;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .title-icon {
            width: 2.5rem;
            height: 2.5rem;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            backdrop-filter: blur(10px);
            border: 2px solid rgba(255, 255, 255, 0.3);
        }

        .header-subtitle {
            margin: 0;
            font-size: 1.1rem;
            opacity: 0.9;
            font-weight: 400;
        }

        /* Tarjeta de contacto moderna - Sin scroll */
        .octoware-contact {
            background: var(--bg-primary);
            border-radius: var(--radius);
            box-shadow: var(--shadow-md);
            padding: 2rem;
            max-width: 600px;
            margin: 0;
            position: relative;
            overflow: hidden;
            transition: var(--transition);
            flex-shrink: 0;
        }

        .octoware-contact::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--primary-color) 0%, var(--secondary-color) 100%);
        }

        .octoware-contact:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }

        .contact-section-title {
            color: var(--text-primary);
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .contact-section-title::before {
            content: '📞';
            font-size: 1.5rem;
        }

        /* Lista de contacto moderna */
        .contact-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .contact-item {
            display: flex;
            align-items: center;
            padding: 1rem 0;
            border-bottom: 1px solid var(--bg-light);
            color: var(--text-secondary);
            font-weight: 500;
            transition: var(--transition);
        }

        .contact-item:last-child {
            border-bottom: none;
        }

        .contact-item:hover {
            color: var(--primary-color);
            padding-left: 0.5rem;
        }

        .contact-icon {
            width: 2.5rem;
            height: 2.5rem;
            background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 1rem;
            font-size: 1.2rem;
            color: white;
            flex-shrink: 0;
        }

        .contact-info {
            flex: 1;
        }

        .contact-label {
            font-size: 0.875rem;
            color: var(--text-muted);
            margin-bottom: 0.25rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            font-weight: 600;
        }

        .contact-value {
            font-size: 1rem;
            color: var(--text-primary);
            font-weight: 600;
        }

        /* Animaciones suaves */
        .contact-item .contact-icon {
            transition: var(--transition);
        }

        .contact-item:hover .contact-icon {
            transform: scale(1.1);
            box-shadow: var(--shadow-md);
        }

        /* Responsive design */
        @media (max-width: 768px) {
            .octoware-workspace {
                padding: 1rem;
            }
            
            .octoware-header {
                padding: 1.5rem 2rem;
            }
            
            .octoware-title {
                font-size: 1.875rem;
            }

            .title-icon {
                width: 2.5rem;
                height: 2.5rem;
                font-size: 1.25rem;
            }
            
            .octoware-contact {
                padding: 2rem;
            }
        }
    </style>

    <div class="octoware-workspace">
        <!-- Header moderno -->
        <div class="octoware-header">
            <div class="header-content">
                <h1 class="octoware-title">
                    <div class="title-icon">🐙</div>
                    Octoware
                </h1>
                <p class="header-subtitle">Información de contacto y soporte técnico</p>
            </div>
        </div>

        <!-- Tarjeta de contacto moderna -->
        <div class="octoware-contact">
            <h2 class="contact-section-title">Información de Contacto</h2>
            <ul class="contact-list">
                <li class="contact-item">
                    <div class="contact-icon">✉️</div>
                    <div class="contact-info">
                        <div class="contact-label">Email</div>
                        <div class="contact-value">contacto@octoware.com</div>
                    </div>
                </li>
                <li class="contact-item">
                    <div class="contact-icon">📱</div>
                    <div class="contact-info">
                        <div class="contact-label">Teléfono</div>
                        <div class="contact-value">+34 123 456 789</div>
                    </div>
                </li>
                <li class="contact-item">
                    <div class="contact-icon">📍</div>
                    <div class="contact-info">
                        <div class="contact-label">Dirección</div>
                        <div class="contact-value">Calle Ejemplo 123, Ciudad, País</div>
                    </div>
                </li>
            </ul>
        </div>
    </div>
@endsection
