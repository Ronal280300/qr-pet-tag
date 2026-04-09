@extends('layouts.app')
@section('title', 'Centro de Ayuda — PetScan')

@section('content')
<div class="container py-5 mt-4">
    <div class="row justify-content-center">
        <div class="col-lg-8 bg-white p-5 text-center" style="border-radius: 24px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); border: 1px solid #e5e7eb;">
            <div class="mb-5">
                <div style="width: 80px; height: 80px; background: #EFF6FF; color: #2563EB; border-radius: 24px; display: inline-flex; align-items: center; justify-content: center; font-size: 32px; margin-bottom: 24px;">
                    <i class="fa-solid fa-life-ring"></i>
                </div>
                <h1 class="fw-bold" style="font-size: 2.5rem; letter-spacing: -1px; color: #0F172A;">Centro de Ayuda Rápido</h1>
                <p class="text-muted fs-5">Resolución inmediata para proteger a tu manada.</p>
            </div>
            
            <div class="text-start mt-5">
                <div class="d-flex gap-3 mb-5">
                    <div style="font-size: 24px; color: #2563EB; margin-top: 4px;"><i class="fa-solid fa-qrcode"></i></div>
                    <div>
                        <h4 class="fw-bold" style="color: #0F172A;">La placa de mi mascota no escanea</h4>
                        <p style="color: #4B5563; line-height: 1.6; margin: 0;">Limpia sutilmente la resina con un paño húmedo libre de químicos abrasivos. Si hubo una mordedura que destruyó la integridad visual de los cuadrados del QR, puedes solicitar una reposición sin costo si cuentas con Suscripción.</p>
                    </div>
                </div>

                <div class="d-flex gap-3 mb-5">
                    <div style="font-size: 24px; color: #2563EB; margin-top: 4px;"><i class="fa-solid fa-location-dot"></i></div>
                    <div>
                        <h4 class="fw-bold" style="color: #0F172A;">El escaneo fue reportado pero sin GPS exacto</h4>
                        <p style="color: #4B5563; line-height: 1.6; margin: 0;">El teléfono del transeúnte que realiza el escaneo necesita otorgar el permiso de ubicación al navegador. Si el usuario lo declina, el sistema aún registrará el rescate, la hora y te avisará, pero por protocolos de seguridad de Apple/Google, la ubicación provendrá solamente de la antena celular genérica (zona aproximada).</p>
                    </div>
                </div>

                <div class="d-flex gap-3">
                    <div style="font-size: 24px; color: #2563EB; margin-top: 4px;"><i class="fa-solid fa-envelope"></i></div>
                    <div>
                        <h4 class="fw-bold" style="color: #0F172A;">Asistencia Humana VIP</h4>
                        <p style="color: #4B5563; line-height: 1.6; margin: 0;">¿Necesitas escalar un problema o asistencia gestionando tus datos? Estamos 24/7 en <a href="mailto:soporte@petscan.com" class="fw-bold text-decoration-none" style="color: #2563EB;">soporte@petscan.com</a> y por WhatsApp.</p>
                    </div>
                </div>
            </div>

            <div class="mt-5 pt-4 border-top">
                <a href="{{ url('/') }}" class="btn btn-primary" style="background-color: #0F172A; border: none; padding: 12px 24px; border-radius: 12px; font-weight: 600;"><i class="fa-solid fa-arrow-left me-2"></i> Volver a la portada</a>
            </div>
        </div>
    </div>
</div>
@endsection
