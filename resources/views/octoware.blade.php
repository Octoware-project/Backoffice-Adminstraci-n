@extends('layouts.app')

<link rel="stylesheet" href="{{ asset('css/Octoware.css') }}">
@section('content')

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
