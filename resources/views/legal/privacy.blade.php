@extends('layouts.app')
@section('title', 'Política de Privacidad — PetScan')

@section('content')
<div class="container py-5 mt-4">
    <div class="row justify-content-center">
        <div class="col-lg-8 bg-white p-5" style="border-radius: 24px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); border: 1px solid #e5e7eb;">
            <h1 class="fw-bold mb-3" style="font-size: 2.5rem; letter-spacing: -1px; color: #0F172A;">Política de Privacidad</h1>
            <p class="text-muted mb-5">Última actualización: {{ date('j \d\e F, Y') }}</p>
            
            <h4 class="fw-bold mt-4" style="color: #0F172A;">1. Información que recopilamos</h4>
            <p style="color: #4B5563; line-height: 1.7;">En PetScan el núcleo de nuestro negocio es la seguridad. Recopilamos únicamente la información que tú voluntariamente decides proporcionar para el perfil médico y de seguridad de tu mascota.</p>

            <h4 class="fw-bold mt-5" style="color: #0F172A;">2. Geolocalización Pasiva</h4>
            <p style="color: #4B5563; line-height: 1.7;">Las alertas generadas al escanear el QR utilizan el hardware (GPS) del celular del tercero que escanea la placa. Este dato es utilizado para enviar una notificación inmediata a tu portal y dispositivo, y no es comercializado ni cedido.</p>

            <h4 class="fw-bold mt-5" style="color: #0F172A;">3. Privacidad y Visibilidad</h4>
            <p style="color: #4B5563; line-height: 1.7;">Nosotros no listamos tu información pública a motores de búsqueda (Google) de forma predeterminada. El perfil público de emergencia solo se activa al escanear la placa física, otorgando protección a la identidad general y asegurando que tu teléfono solo esté visible en un episodio de rescate.</p>

            <h4 class="fw-bold mt-5" style="color: #0F172A;">4. Derechos y Control de Datos</h4>
            <p style="color: #4B5563; line-height: 1.7;">Puedes editar, descargar en formato abierto (CSV/JSON), u obliterar completamente el historial de tu cuenta directamente desde el menú Administrativo de tu portal en un clic.</p>

            <div class="mt-5 pt-4 border-top">
                <a href="{{ url('/') }}" class="btn btn-primary" style="background-color: #0F172A; border: none; padding: 12px 24px; border-radius: 12px; font-weight: 600;"><i class="fa-solid fa-arrow-left me-2"></i> Volver a la portada</a>
            </div>
        </div>
    </div>
</div>
@endsection
