{{-- resources/views/public/pet.blade.php --}}
@extends('layouts.public')
@section('title', $pet->name)

@php
  use Illuminate\Support\Facades\Storage;

  $photosRel = method_exists($pet,'photos') ? $pet->photos : collect();
  $gallery   = collect();

  if ($photosRel && $photosRel->count() > 0) {
      $gallery = $photosRel->map(fn($ph) => Storage::url($ph->path));
  } elseif ($pet->photo && Storage::disk('public')->exists($pet->photo)) {
      $gallery = collect([ Storage::url($pet->photo) ]);
  } else {
      $gallery = collect(['https://placehold.co/1200x1200?text='.urlencode($pet->name)]);
  }

  $ownerName  = optional($owner)->name;
  $ownerPhone = optional($owner)->phone;
  $digits     = preg_replace('/\D+/', '', (string) $ownerPhone);
  $hasWhats   = !empty($digits);

  $zoneForMaps = $pet->full_location ?: ($pet->zone ?: null);
  $mapsUrl     = $zoneForMaps ? ('https://www.google.com/maps/search/?api=1&query=' . urlencode($zoneForMaps)) : null;

  $sex = $pet->sex ?? 'unknown';
  $sexIcon = $sex === 'male' ? 'fa-mars' : ($sex === 'female' ? 'fa-venus' : 'fa-circle-question');
  $sexText = $sex === 'male' ? 'Macho' : ($sex === 'female' ? 'Hembra' : 'Sexo N/D');
  $sexColor = $sex === 'male' ? '#60a5fa' : ($sex === 'female' ? '#f472b6' : '#94a3b8');

  $neut   = (bool) ($pet->is_neutered ?? false);
  $rabies = (bool) ($pet->rabies_vaccine ?? false);

  // ===== CONTACTO DE EMERGENCIA =====
  $hasEmergency = (bool) ($pet->has_emergency_contact ?? false);
  $emergencyName = $hasEmergency ? ($pet->emergency_contact_name ?? null) : null;
  $emergencyPhone = $hasEmergency ? ($pet->emergency_contact_phone ?? null) : null;
  $emergencyDigits = $emergencyPhone ? preg_replace('/\D+/', '', (string) $emergencyPhone) : '';
  $hasEmergencyWhats = !empty($emergencyDigits);

  $shareUrl = url()->current();
  $slug = $slug ?? ($pet->qrCode->slug ?? null);

  // Características adicionales
  $species = $pet->species ?? 'dog';
  $speciesText = $species === 'dog' ? 'Perro' : ($species === 'cat' ? 'Gato' : 'Otra especie');
  $speciesIcon = $species === 'dog' ? 'fa-dog' : ($species === 'cat' ? 'fa-cat' : 'fa-paw');

  $size = $pet->size ?? null;
  $sizeText = $size === 'small' ? 'Pequeño' : ($size === 'medium' ? 'Mediano' : ($size === 'large' ? 'Grande' : 'N/D'));

  $color = $pet->color ?? null;
  $medicalConditions = $pet->medical_conditions ?? null;
@endphp

@push('styles')
<style>
/* ===== CSS Variables - Paleta del Proyecto ===== */
:root {
  --primary: #4e89e8;
  --primary-dark: #3a6bb8;
  --blue-light: #3466ff;
  --blue-accent: #115DFC;
  --success: #10b981;
  --danger: #ef4444;
  --warning: #f59e0b;
  --info: #06b6d4;
  --purple: #8b5cf6;
  --pink: #ec4899;
  --gray-50: #f9fafb;
  --gray-100: #f3f4f6;
  --gray-200: #e5e7eb;
  --gray-300: #d1d5db;
  --gray-700: #374151;
  --gray-900: #111827;
}

/* ===== Animaciones ===== */
@keyframes fadeIn { 0% { opacity: 0; } 100% { opacity: 1; } }
@keyframes slideUp { 0% { opacity: 0; transform: translateY(40px); } 100% { opacity: 1; transform: translateY(0); } }
@keyframes slideDown { 0% { opacity: 0; transform: translateY(-30px); } 100% { opacity: 1; transform: translateY(0); } }
@keyframes scaleUp { 0% { opacity: 0; transform: scale(0.9); } 100% { opacity: 1; transform: scale(1); } }
@keyframes float { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-12px); } }
@keyframes pulse { 0%, 100% { transform: scale(1); opacity: 0.8; } 50% { transform: scale(1.15); opacity: 1; } }
@keyframes shimmer { 0% { transform: translateX(-100%); } 100% { transform: translateX(100%); } }
@keyframes glow { 0%, 100% { box-shadow: 0 0 20px rgba(17, 93, 252, 0.3); } 50% { box-shadow: 0 0 35px rgba(17, 93, 252, 0.6), 0 0 50px rgba(17, 93, 252, 0.4); } }
@keyframes rotate { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
@keyframes heartbeat { 0%, 100% { transform: scale(1); } 10%, 30% { transform: scale(1.15); } 20%, 40% { transform: scale(1); } }

/* ===== Body & Container ===== */
body {
  background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 50%, #f8f9fa 100%);
  min-height: 100vh;
}

.page-container {
  padding: 2rem 1rem;
  max-width: 1400px;
  margin: 0 auto;
  animation: fadeIn 0.6s ease-out;
}

/* ===== Hero Section - Carousel Mejorado ===== */
.hero-section {
  background: #fff;
  border-radius: 35px;
  overflow: hidden;
  box-shadow: 0 25px 70px rgba(0, 0, 0, 0.15), 0 0 1px rgba(0, 0, 0, 0.1);
  margin-bottom: 2.5rem;
  position: relative;
  animation: scaleUp 0.7s cubic-bezier(0.34, 1.56, 0.64, 1);
}

.carousel-container {
  position: relative;
  width: 100%;
  aspect-ratio: 21/9;
  background: linear-gradient(135deg, var(--gray-100), var(--gray-200));
  overflow: hidden;
}

.carousel-slide {
  position: absolute;
  inset: 0;
  opacity: 0;
  transition: opacity 1s cubic-bezier(0.4, 0, 0.2, 1);
}

.carousel-slide.active {
  opacity: 1;
  z-index: 1;
}

.carousel-slide img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  transition: transform 0.5s ease;
}

.carousel-container:hover .carousel-slide.active img {
  transform: scale(1.05);
}

/* Overlay con gradiente mejorado */
.pet-hero-overlay {
  position: absolute;
  inset: 0;
  background: linear-gradient(
    180deg,
    rgba(0, 0, 0, 0.75) 0%,
    rgba(0, 0, 0, 0.4) 30%,
    transparent 60%,
    rgba(0, 0, 0, 0.3) 100%
  );
  z-index: 2;
  display: flex;
  flex-direction: column;
  justify-content: space-between;
  padding: 2.5rem;
}

.pet-name-hero {
  font-size: clamp(2.5rem, 5vw, 4.5rem);
  font-weight: 900;
  color: #fff;
  text-shadow: 0 5px 25px rgba(0, 0, 0, 0.6);
  margin: 0;
  display: flex;
  align-items: center;
  gap: 1.2rem;
  animation: slideDown 0.8s ease-out 0.3s both;
  line-height: 1.1;
}

.pet-name-hero i {
  font-size: 0.75em;
  animation: float 3s ease-in-out infinite;
  filter: drop-shadow(0 4px 10px rgba(0, 0, 0, 0.4));
}

.hero-species-badge {
  display: inline-flex;
  align-items: center;
  gap: 0.6rem;
  background: rgba(255, 255, 255, 0.15);
  backdrop-filter: blur(15px);
  border: 2px solid rgba(255, 255, 255, 0.3);
  padding: 0.8rem 1.5rem;
  border-radius: 50px;
  font-size: 1rem;
  font-weight: 700;
  color: #fff;
  text-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
  animation: slideDown 0.8s ease-out 0.5s both;
  margin-top: 1rem;
  width: fit-content;
}

/* Controles del carousel mejorados */
.carousel-nav {
  position: absolute;
  top: 50%;
  transform: translateY(-50%);
  width: 70px;
  height: 70px;
  border: none;
  background: rgba(255, 255, 255, 0.12);
  backdrop-filter: blur(15px);
  border-radius: 50%;
  color: #fff;
  font-size: 26px;
  cursor: pointer;
  transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
  z-index: 10;
  display: flex;
  align-items: center;
  justify-content: center;
  border: 2px solid rgba(255, 255, 255, 0.2);
}

.carousel-nav:hover {
  background: rgba(255, 255, 255, 0.25);
  transform: translateY(-50%) scale(1.15);
  border-color: rgba(255, 255, 255, 0.4);
}

.carousel-nav.prev { left: 25px; }
.carousel-nav.next { right: 25px; }

.carousel-controls {
  position: absolute;
  bottom: 35px;
  left: 0;
  right: 0;
  display: flex;
  justify-content: center;
  gap: 14px;
  z-index: 10;
}

.carousel-dot {
  width: 14px;
  height: 14px;
  border-radius: 50%;
  background: rgba(255, 255, 255, 0.35);
  cursor: pointer;
  transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
  border: 2px solid rgba(255, 255, 255, 0.5);
}

.carousel-dot.active {
  width: 50px;
  border-radius: 10px;
  background: #fff;
  box-shadow: 0 0 25px rgba(255, 255, 255, 0.9);
}

/* ===== Alert Banners Mejorados ===== */
.alert-banners {
  display: flex;
  flex-direction: column;
  gap: 1.2rem;
  margin-bottom: 2.5rem;
  animation: slideUp 0.6s ease-out 0.2s both;
}

.alert-banner {
  padding: 2rem;
  border-radius: 25px;
  box-shadow: 0 15px 50px rgba(0, 0, 0, 0.12);
  display: flex;
  align-items: flex-start;
  gap: 1.5rem;
  position: relative;
  overflow: hidden;
  backdrop-filter: blur(10px);
  border: 2px solid;
}

.alert-banner::before {
  content: '';
  position: absolute;
  left: 0;
  top: 0;
  bottom: 0;
  width: 6px;
}

.alert-banner i {
  font-size: 2.5rem;
  flex-shrink: 0;
  margin-top: 0.2rem;
  filter: drop-shadow(0 2px 8px rgba(0, 0, 0, 0.2));
}

.alert-content {
  flex: 1;
}

.alert-title {
  font-size: 1.5rem;
  font-weight: 900;
  margin-bottom: 0.6rem;
  line-height: 1.3;
}

.alert-text {
  font-size: 1.1rem;
  line-height: 1.7;
  font-weight: 500;
}

.alert-lost {
  background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
  border-color: var(--danger);
  color: #7f1d1d;
}

.alert-lost::before {
  background: var(--danger);
}

.alert-lost i {
  color: var(--danger);
  animation: pulse 2s ease-in-out infinite;
}

.alert-reward {
  background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
  border-color: var(--success);
  color: #14532d;
}

.alert-reward::before {
  background: var(--success);
}

.alert-reward i {
  color: var(--success);
}

.pulse-icon {
  display: inline-block;
  width: 12px;
  height: 12px;
  background: var(--success);
  border-radius: 50%;
  animation: pulse 2s ease-in-out infinite;
  margin-right: 0.4rem;
  vertical-align: middle;
}

/* ===== Grid de Contenido - 3 Columnas ===== */
.content-grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 2rem;
  animation: slideUp 0.7s ease-out 0.5s both;
}

/* ===== Cards Base ===== */
.info-card {
  background: #fff;
  border-radius: 30px;
  padding: 2.5rem;
  box-shadow: 0 15px 50px rgba(0, 0, 0, 0.08);
  transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
  position: relative;
  overflow: hidden;
  border: 1px solid var(--gray-200);
}

.info-card::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  height: 5px;
  background: linear-gradient(90deg, var(--primary), var(--blue-light), var(--blue-accent));
  opacity: 0;
  transition: opacity 0.4s ease;
}

.info-card:hover {
  transform: translateY(-8px);
  box-shadow: 0 25px 70px rgba(0, 0, 0, 0.15);
  border-color: var(--primary);
}

.info-card:hover::before {
  opacity: 1;
}

.card-title {
  font-size: 1.8rem;
  font-weight: 900;
  color: var(--gray-900);
  margin-bottom: 2rem;
  display: flex;
  align-items: center;
  gap: 1rem;
  line-height: 1.2;
}

.card-title i {
  font-size: 1.5em;
  background: linear-gradient(135deg, var(--primary), var(--blue-accent));
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
}

/* ===== Info Items ===== */
.info-items {
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

.info-item {
  padding: 1.3rem 1.5rem;
  background: linear-gradient(135deg, var(--gray-50) 0%, var(--gray-100) 100%);
  border-radius: 18px;
  display: flex;
  align-items: center;
  gap: 1.2rem;
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  border: 2px solid transparent;
}

.info-item:hover {
  background: linear-gradient(135deg, #e0e7ff 0%, #dbeafe 100%);
  border-color: var(--primary);
  transform: translateX(10px);
}

.info-item i {
  font-size: 2rem;
  color: var(--primary);
  min-width: 40px;
  text-align: center;
  transition: transform 0.3s ease;
}

.info-item:hover i {
  transform: scale(1.2) rotate(8deg);
}

.info-item-content {
  flex: 1;
}

.info-item-label {
  font-size: 0.75rem;
  text-transform: uppercase;
  color: var(--gray-700);
  font-weight: 800;
  letter-spacing: 1.2px;
  margin-bottom: 0.3rem;
}

.info-item-value {
  font-size: 1.15rem;
  font-weight: 800;
  color: var(--gray-900);
}

/* ===== Badges de Estado ===== */
.status-badges {
  display: flex;
  flex-wrap: wrap;
  gap: 0.8rem;
  margin-top: 1rem;
}

.status-badge {
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
  padding: 0.7rem 1.2rem;
  border-radius: 50px;
  font-size: 0.9rem;
  font-weight: 700;
  border: 2px solid;
}

.badge-success {
  background: linear-gradient(135deg, #d1fae5, #a7f3d0);
  color: #14532d;
  border-color: var(--success);
}

.badge-danger {
  background: linear-gradient(135deg, #fee2e2, #fecaca);
  color: #7f1d1d;
  border-color: var(--danger);
}

.badge-info {
  background: linear-gradient(135deg, #dbeafe, #bfdbfe);
  color: #1e3a8a;
  border-color: var(--info);
}

/* ===== Contact Cards - Destacadas con Glassmorphism ===== */
.contact-primary-card {
  background: linear-gradient(135deg, var(--primary) 0%, var(--blue-accent) 100%);
  color: #fff;
  animation: glow 4s ease-in-out infinite;
  border: none;
  box-shadow: 0 20px 60px rgba(17, 93, 252, 0.4);
}

.contact-primary-card::before {
  display: none;
}

.contact-primary-card .card-title {
  color: #fff;
}

.contact-primary-card .card-title i {
  background: #fff;
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
}

.contact-emergency-card {
  background: linear-gradient(135deg, var(--warning) 0%, #fb923c 100%);
  color: #fff;
  border: 3px solid rgba(255, 255, 255, 0.3);
  box-shadow: 0 20px 60px rgba(245, 158, 11, 0.4);
}

.contact-emergency-card::before {
  display: none;
}

.contact-emergency-card .card-title {
  color: #fff;
}

.contact-emergency-card .card-title i {
  background: #fff;
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
}

.emergency-badge {
  display: inline-flex;
  align-items: center;
  gap: 0.6rem;
  background: rgba(255, 255, 255, 0.2);
  backdrop-filter: blur(10px);
  padding: 0.7rem 1.3rem;
  border-radius: 50px;
  font-size: 0.9rem;
  font-weight: 800;
  text-transform: uppercase;
  letter-spacing: 1px;
  margin-bottom: 1.5rem;
  border: 2px solid rgba(255, 255, 255, 0.3);
}

.emergency-name {
  font-size: 1.4rem;
  font-weight: 900;
  margin: 1.5rem 0;
  display: flex;
  align-items: center;
  gap: 0.8rem;
}

.contact-info-text {
  background: rgba(255, 255, 255, 0.15);
  backdrop-filter: blur(10px);
  padding: 1.2rem;
  border-radius: 16px;
  font-size: 1rem;
  line-height: 1.6;
  margin-bottom: 1.5rem;
  border: 2px solid rgba(255, 255, 255, 0.2);
}

/* ===== Botones de Contacto ===== */
.contact-buttons {
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

.btn-contact {
  padding: 1.4rem 2rem;
  border-radius: 20px;
  font-weight: 800;
  font-size: 1.1rem;
  border: none;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 0.9rem;
  transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
  text-decoration: none;
  position: relative;
  overflow: hidden;
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
}

.btn-contact::before {
  content: '';
  position: absolute;
  top: 50%;
  left: 50%;
  width: 0;
  height: 0;
  background: rgba(255, 255, 255, 0.3);
  border-radius: 50%;
  transition: width 0.6s, height 0.6s, top 0.6s, left 0.6s;
  transform: translate(-50%, -50%);
}

.btn-contact:hover::before {
  width: 500px;
  height: 500px;
}

.btn-contact:hover {
  transform: translateY(-5px);
  box-shadow: 0 20px 50px rgba(0, 0, 0, 0.35);
}

.btn-contact i {
  font-size: 1.3em;
  position: relative;
  z-index: 1;
}

.btn-contact span {
  position: relative;
  z-index: 1;
}

.btn-whatsapp {
  background: linear-gradient(135deg, #25d366, #128c7e);
  color: #fff;
}

.btn-whatsapp:hover {
  box-shadow: 0 20px 50px rgba(37, 211, 102, 0.6);
}

.btn-call {
  background: #fff;
  color: var(--primary);
}

.btn-secondary {
  background: rgba(255, 255, 255, 0.15);
  color: #fff;
  backdrop-filter: blur(10px);
  border: 2px solid rgba(255, 255, 255, 0.3);
}

.btn-secondary:hover {
  background: rgba(255, 255, 255, 0.25);
  border-color: rgba(255, 255, 255, 0.5);
}

.btn-contact.disabled {
  opacity: 0.5;
  cursor: not-allowed;
  pointer-events: none;
}

/* ===== Health Card ===== */
.health-card {
  background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
  border-color: var(--info);
}

/* ===== Location Card ===== */
.location-card {
  background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
  border-color: var(--warning);
}

.location-card .card-title i {
  background: linear-gradient(135deg, var(--warning), #f59e0b);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
}

/* ===== Medical Card ===== */
.medical-card {
  grid-column: 1 / -1;
  background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%);
  border-color: var(--danger);
}

.medical-content {
  background: #fff;
  padding: 1.5rem;
  border-radius: 18px;
  border: 2px solid #fecaca;
  font-size: 1.05rem;
  line-height: 1.7;
  color: var(--gray-900);
}

/* ===== Reward Card ===== */
.reward-card {
  grid-column: 1 / -1;
  background: linear-gradient(135deg, #ecfdf5 0%, #d1fae5 100%);
  border-color: var(--success);
}

.reward-amount {
  font-size: 2.5rem;
  font-weight: 900;
  color: var(--success);
  margin: 1rem 0;
  text-shadow: 0 2px 10px rgba(16, 185, 129, 0.3);
}

.reward-message {
  background: #fff;
  padding: 1.5rem;
  border-radius: 18px;
  border: 2px solid #a7f3d0;
  font-size: 1.05rem;
  line-height: 1.7;
  color: var(--gray-900);
  margin-top: 1rem;
}

/* ===== Share Section ===== */
.share-section {
  grid-column: 1 / -1;
  background: linear-gradient(135deg, #f5f3ff 0%, #ede9fe 100%);
  border-color: var(--purple);
  text-align: center;
}

.share-buttons {
  display: flex;
  justify-content: center;
  gap: 1rem;
  margin-top: 1.5rem;
  flex-wrap: wrap;
}

.btn-share {
  padding: 1.2rem 2rem;
  border-radius: 18px;
  font-weight: 800;
  font-size: 1rem;
  border: 2px solid;
  cursor: pointer;
  display: inline-flex;
  align-items: center;
  gap: 0.7rem;
  transition: all 0.3s ease;
  text-decoration: none;
}

.btn-share:hover {
  transform: translateY(-4px);
  box-shadow: 0 15px 40px rgba(0, 0, 0, 0.2);
}

.btn-share-primary {
  background: var(--purple);
  color: #fff;
  border-color: var(--purple);
}

.btn-share-secondary {
  background: #fff;
  color: var(--purple);
  border-color: var(--purple);
}

/* ===== Lightbox Mejorado ===== */
.lightbox {
  position: fixed;
  inset: 0;
  background: rgba(0, 0, 0, 0.96);
  backdrop-filter: blur(25px);
  z-index: 9999;
  display: none;
  align-items: center;
  justify-content: center;
  animation: fadeIn 0.3s ease-out;
}

.lightbox.active {
  display: flex;
}

.lightbox-content {
  position: relative;
  max-width: 95vw;
  max-height: 95vh;
  animation: scaleUp 0.5s cubic-bezier(0.34, 1.56, 0.64, 1);
}

.lightbox-img {
  max-width: 100%;
  max-height: 95vh;
  object-fit: contain;
  border-radius: 25px;
  box-shadow: 0 40px 120px rgba(0, 0, 0, 0.9);
}

.lightbox-close {
  position: absolute;
  top: -60px;
  right: 0;
  width: 55px;
  height: 55px;
  border: none;
  background: rgba(255, 255, 255, 0.12);
  backdrop-filter: blur(15px);
  border-radius: 50%;
  color: #fff;
  font-size: 26px;
  cursor: pointer;
  transition: all 0.3s ease;
  border: 2px solid rgba(255, 255, 255, 0.2);
}

.lightbox-close:hover {
  background: var(--danger);
  transform: rotate(90deg) scale(1.15);
  border-color: var(--danger);
}

.lightbox-nav {
  position: absolute;
  top: 50%;
  transform: translateY(-50%);
  width: 70px;
  height: 70px;
  border: none;
  background: rgba(255, 255, 255, 0.12);
  backdrop-filter: blur(15px);
  border-radius: 50%;
  color: #fff;
  font-size: 30px;
  cursor: pointer;
  transition: all 0.3s ease;
  border: 2px solid rgba(255, 255, 255, 0.2);
}

.lightbox-nav:hover {
  background: rgba(255, 255, 255, 0.25);
  transform: translateY(-50%) scale(1.2);
}

.lightbox-prev {
  left: -90px;
}

.lightbox-next {
  right: -90px;
}

/* ===== Responsive Design ===== */
@media (max-width: 1200px) {
  .content-grid {
    grid-template-columns: repeat(2, 1fr);
  }

  .medical-card,
  .reward-card,
  .share-section {
    grid-column: 1 / -1;
  }
}

@media (max-width: 768px) {
  .page-container {
    padding: 1rem 0.5rem;
  }

  .carousel-container {
    aspect-ratio: 4/3;
    border-radius: 25px;
  }

  .pet-hero-overlay {
    padding: 1.5rem;
  }

  .pet-name-hero {
    font-size: 2rem;
    gap: 0.8rem;
  }

  .hero-species-badge {
    font-size: 0.85rem;
    padding: 0.6rem 1.2rem;
  }

  .carousel-nav {
    width: 55px;
    height: 55px;
    font-size: 22px;
  }

  .carousel-nav.prev {
    left: 15px;
  }

  .carousel-nav.next {
    right: 15px;
  }

  .carousel-controls {
    bottom: 25px;
    gap: 10px;
  }

  .carousel-dot {
    width: 10px;
    height: 10px;
  }

  .carousel-dot.active {
    width: 35px;
  }

  .content-grid {
    grid-template-columns: 1fr;
    gap: 1.5rem;
  }

  .info-card {
    padding: 1.8rem;
    border-radius: 25px;
  }

  .card-title {
    font-size: 1.5rem;
    margin-bottom: 1.5rem;
  }

  .info-item {
    padding: 1.1rem 1.3rem;
  }

  .info-item i {
    font-size: 1.7rem;
  }

  .btn-contact {
    padding: 1.2rem 1.6rem;
    font-size: 1rem;
  }

  .alert-banner {
    padding: 1.5rem;
    gap: 1.2rem;
    border-radius: 20px;
  }

  .alert-banner i {
    font-size: 2rem;
  }

  .alert-title {
    font-size: 1.25rem;
  }

  .alert-text {
    font-size: 1rem;
  }

  .lightbox-nav {
    display: none;
  }

  .reward-amount {
    font-size: 2rem;
  }

  .share-buttons {
    flex-direction: column;
  }

  .btn-share {
    width: 100%;
  }
}

@media (max-width: 480px) {
  .pet-name-hero {
    font-size: 1.75rem;
    gap: 0.6rem;
  }

  .hero-species-badge {
    font-size: 0.8rem;
    padding: 0.5rem 1rem;
  }

  .carousel-nav {
    width: 45px;
    height: 45px;
    font-size: 18px;
  }

  .carousel-nav.prev {
    left: 10px;
  }

  .carousel-nav.next {
    right: 10px;
  }

  .info-card {
    padding: 1.5rem;
    border-radius: 20px;
  }

  .card-title {
    font-size: 1.3rem;
  }

  .info-item {
    padding: 1rem;
  }

  .info-item i {
    font-size: 1.5rem;
  }

  .info-item-value {
    font-size: 1rem;
  }

  .btn-contact {
    padding: 1rem 1.3rem;
    font-size: 0.95rem;
  }

  .alert-banner {
    padding: 1.2rem;
  }

  .alert-banner i {
    font-size: 1.7rem;
  }

  .alert-title {
    font-size: 1.1rem;
  }

  .alert-text {
    font-size: 0.95rem;
  }

  .emergency-name {
    font-size: 1.2rem;
  }

  .reward-amount {
    font-size: 1.8rem;
  }
}
</style>
@endpush

@section('content')
<div class="page-container">

  {{-- ===== BANNERS DE ALERTA ===== --}}
  @if($pet->is_lost || optional($pet->reward)->active)
  <div class="alert-banners">
    @if($pet->is_lost)
      <div class="alert-banner alert-lost">
        <i class="fa-solid fa-triangle-exclamation"></i>
        <div class="alert-content">
          <div class="alert-title">¡Mascota reportada como perdida!</div>
          <div class="alert-text">Si tienes información sobre su paradero, por favor contacta a su dueño de inmediato. Tu ayuda puede reunir a esta familia.</div>
        </div>
      </div>
    @endif

    @if(optional($pet->reward)->active)
      <div class="alert-banner alert-reward">
        <i class="fa-solid fa-medal"></i>
        <div class="alert-content">
          <div class="alert-title">
            <span class="pulse-icon"></span>
            Recompensa activa
            @if(optional($pet->reward)->amount)
              - ₡{{ number_format((float)optional($pet->reward)->amount, 2) }}
            @endif
          </div>
          @if(optional($pet->reward)->message)
            <div class="alert-text">{{ optional($pet->reward)->message }}</div>
          @endif
        </div>
      </div>
    @endif
  </div>
  @endif

  {{-- ===== HERO SECTION CON CAROUSEL ===== --}}
  <div class="hero-section">
    <div class="carousel-container" id="carousel">
      @foreach($gallery as $i => $src)
        <div class="carousel-slide {{ $i===0 ? 'active' : '' }}" data-index="{{ $i }}">
          <img src="{{ $src }}" alt="{{ $pet->name }} foto {{ $i+1 }}" loading="{{ $i===0 ? 'eager' : 'lazy' }}">
        </div>
      @endforeach

      {{-- Overlay con nombre --}}
      <div class="pet-hero-overlay">
        <div>
          <h1 class="pet-name-hero">
            <i class="fa-solid {{ $sexIcon }}" style="color: {{ $sexColor }}"></i>
            {{ $pet->name }}
          </h1>
          <div class="hero-species-badge">
            <i class="fa-solid {{ $speciesIcon }}"></i>
            {{ $speciesText }}
          </div>
        </div>
      </div>

      {{-- Controles del carousel --}}
      @if($gallery->count() > 1)
        <button class="carousel-nav prev" id="carouselPrev" aria-label="Foto anterior">
          <i class="fa-solid fa-chevron-left"></i>
        </button>
        <button class="carousel-nav next" id="carouselNext" aria-label="Foto siguiente">
          <i class="fa-solid fa-chevron-right"></i>
        </button>

        <div class="carousel-controls">
          @foreach($gallery as $i => $src)
            <div class="carousel-dot {{ $i===0 ? 'active' : '' }}" data-index="{{ $i }}" role="button" aria-label="Ir a foto {{ $i+1 }}"></div>
          @endforeach
        </div>
      @endif
    </div>
  </div>

  {{-- ===== CONTENT GRID - 3 COLUMNAS ===== --}}
  <div class="content-grid">

    {{-- ===== COLUMNA 1: INFORMACIÓN DE LA MASCOTA ===== --}}
    <div class="info-card">
      <h2 class="card-title">
        <i class="fa-solid fa-paw"></i>
        Información
      </h2>

      <div class="info-items">
        @if($pet->breed)
        <div class="info-item">
          <i class="fa-solid fa-bone"></i>
          <div class="info-item-content">
            <div class="info-item-label">Raza</div>
            <div class="info-item-value">{{ $pet->breed }}</div>
          </div>
        </div>
        @endif

        <div class="info-item">
          <i class="fa-solid {{ $sexIcon }}"></i>
          <div class="info-item-content">
            <div class="info-item-label">Sexo</div>
            <div class="info-item-value">{{ $sexText }}</div>
          </div>
        </div>

        @if($pet->age !== null)
        <div class="info-item">
          <i class="fa-solid fa-cake-candles"></i>
          <div class="info-item-content">
            <div class="info-item-label">Edad</div>
            <div class="info-item-value">{{ $pet->age }} {{ Str::plural('año', $pet->age) }}</div>
          </div>
        </div>
        @endif

        @if($size)
        <div class="info-item">
          <i class="fa-solid fa-ruler-vertical"></i>
          <div class="info-item-content">
            <div class="info-item-label">Tamaño</div>
            <div class="info-item-value">{{ $sizeText }}</div>
          </div>
        </div>
        @endif

        @if($color)
        <div class="info-item">
          <i class="fa-solid fa-palette"></i>
          <div class="info-item-content">
            <div class="info-item-label">Color</div>
            <div class="info-item-value">{{ $color }}</div>
          </div>
        </div>
        @endif

        @if($ownerName)
        <div class="info-item">
          <i class="fa-solid fa-user"></i>
          <div class="info-item-content">
            <div class="info-item-label">Dueño</div>
            <div class="info-item-value">{{ $ownerName }}</div>
          </div>
        </div>
        @endif
      </div>
    </div>

    {{-- ===== COLUMNA 2: CONTACTO PRINCIPAL (DUEÑO) ===== --}}
    <div class="info-card contact-primary-card">
      <h2 class="card-title">
        <i class="fa-solid fa-phone"></i>
        Contacto Principal
      </h2>

      <div class="contact-info-text">
        <i class="fa-solid fa-info-circle"></i>
        Contacta al dueño de {{ $pet->name }} si lo has encontrado o tienes información.
      </div>

      <div class="contact-buttons">
        <a href="{{ $hasWhats ? 'https://wa.me/'.$digits : '#' }}"
           class="btn-contact btn-whatsapp {{ $hasWhats ? '' : 'disabled' }}"
           target="_blank" rel="noopener"
           aria-label="Contactar por WhatsApp">
          <i class="fa-brands fa-whatsapp"></i>
          <span>WhatsApp</span>
        </a>

        @if($ownerPhone)
        <a href="tel:{{ $digits }}" class="btn-contact btn-call" aria-label="Llamar al dueño">
          <i class="fa-solid fa-phone"></i>
          <span>Llamar ahora</span>
        </a>
        @endif

        <button class="btn-contact btn-secondary" id="shareBtn" aria-label="Compartir perfil">
          <i class="fa-solid fa-share-nodes"></i>
          <span>Compartir perfil</span>
        </button>
      </div>
    </div>

    {{-- ===== COLUMNA 3: CONTACTO DE EMERGENCIA (Condicional) ===== --}}
    @if($hasEmergency && $emergencyName && $emergencyPhone)
    <div class="info-card contact-emergency-card">
      <div class="emergency-badge">
        <i class="fa-solid fa-user-nurse"></i>
        Contacto de Emergencia
      </div>

      <h2 class="card-title">
        <i class="fa-solid fa-phone-volume"></i>
        Si el dueño no responde
      </h2>

      <div class="emergency-name">
        <i class="fa-solid fa-user"></i>
        {{ $emergencyName }}
      </div>

      <div class="contact-buttons">
        <a href="{{ $hasEmergencyWhats ? 'https://wa.me/'.$emergencyDigits : '#' }}"
           class="btn-contact btn-whatsapp {{ $hasEmergencyWhats ? '' : 'disabled' }}"
           target="_blank" rel="noopener"
           aria-label="Contactar emergencia por WhatsApp">
          <i class="fa-brands fa-whatsapp"></i>
          <span>WhatsApp</span>
        </a>

        <a href="tel:{{ $emergencyDigits }}" class="btn-contact btn-call" aria-label="Llamar contacto emergencia">
          <i class="fa-solid fa-phone"></i>
          <span>Llamar ahora</span>
        </a>
      </div>
    </div>
    @else
    {{-- Si no hay contacto de emergencia, mostrar salud --}}
    <div class="info-card health-card">
      <h2 class="card-title">
        <i class="fa-solid fa-heart-pulse"></i>
        Estado de Salud
      </h2>

      <div class="status-badges">
        <div class="status-badge {{ $neut ? 'badge-success' : 'badge-danger' }}">
          <i class="fa-solid fa-scissors"></i>
          {{ $neut ? 'Esterilizado' : 'No esterilizado' }}
        </div>

        <div class="status-badge {{ $rabies ? 'badge-success' : 'badge-danger' }}">
          <i class="fa-solid fa-syringe"></i>
          Antirrábica: {{ $rabies ? 'Al día' : 'No' }}
        </div>
      </div>

      @if($zoneForMaps)
      <div class="info-item" style="margin-top: 1.5rem;">
        <i class="fa-solid fa-location-dot"></i>
        <div class="info-item-content">
          <div class="info-item-label">Ubicación</div>
          <div class="info-item-value">{{ $zoneForMaps }}</div>
        </div>
      </div>

      @if($mapsUrl)
      <a href="{{ $mapsUrl }}" target="_blank" rel="noopener" class="btn-contact btn-secondary" style="margin-top: 1rem;">
        <i class="fa-solid fa-map-location-dot"></i>
        <span>Ver en Google Maps</span>
      </a>
      @endif
      @endif
    </div>
    @endif

    {{-- ===== SI HAY CONTACTO DE EMERGENCIA, MOSTRAR SALUD + UBICACIÓN EN NUEVA FILA ===== --}}
    @if($hasEmergency && $emergencyName && $emergencyPhone)
    <div class="info-card health-card">
      <h2 class="card-title">
        <i class="fa-solid fa-heart-pulse"></i>
        Estado de Salud
      </h2>

      <div class="status-badges">
        <div class="status-badge {{ $neut ? 'badge-success' : 'badge-danger' }}">
          <i class="fa-solid fa-scissors"></i>
          {{ $neut ? 'Esterilizado' : 'No esterilizado' }}
        </div>

        <div class="status-badge {{ $rabies ? 'badge-success' : 'badge-danger' }}">
          <i class="fa-solid fa-syringe"></i>
          Antirrábica: {{ $rabies ? 'Al día' : 'No' }}
        </div>
      </div>
    </div>

    @if($zoneForMaps)
    <div class="info-card location-card">
      <h2 class="card-title">
        <i class="fa-solid fa-location-dot"></i>
        Ubicación
      </h2>

      <div class="info-item">
        <i class="fa-solid fa-map-marker-alt"></i>
        <div class="info-item-content">
          <div class="info-item-label">Zona</div>
          <div class="info-item-value">{{ $zoneForMaps }}</div>
        </div>
      </div>

      @if($mapsUrl)
      <a href="{{ $mapsUrl }}" target="_blank" rel="noopener" class="btn-contact btn-secondary" style="margin-top: 1rem;">
        <i class="fa-solid fa-map-location-dot"></i>
        <span>Ver en Google Maps</span>
      </a>
      @endif
    </div>
    @endif
    @endif

    {{-- ===== CONDICIONES MÉDICAS (Full Width) ===== --}}
    @if($medicalConditions)
    <div class="info-card medical-card">
      <h2 class="card-title">
        <i class="fa-solid fa-notes-medical"></i>
        Observaciones Médicas
      </h2>
      <div class="medical-content">
        {{ $medicalConditions }}
      </div>
    </div>
    @endif

    {{-- ===== RECOMPENSA (Full Width) ===== --}}
    @if(optional($pet->reward)->active)
    <div class="info-card reward-card">
      <h2 class="card-title">
        <i class="fa-solid fa-medal"></i>
        Recompensa Activa
      </h2>

      @if(optional($pet->reward)->amount)
      <div class="reward-amount">
        <i class="fa-solid fa-coins"></i>
        ₡{{ number_format((float)optional($pet->reward)->amount, 2) }}
      </div>
      @endif

      @if(optional($pet->reward)->message)
      <div class="reward-message">
        {{ optional($pet->reward)->message }}
      </div>
      @endif
    </div>
    @endif

    {{-- ===== COMPARTIR (Full Width) ===== --}}
    <div class="info-card share-section">
      <h2 class="card-title" style="justify-content: center;">
        <i class="fa-solid fa-share-nodes"></i>
        Ayuda a difundir
      </h2>
      <p style="font-size: 1.1rem; color: var(--gray-700); margin-bottom: 0;">
        Comparte este perfil en tus redes sociales para ayudar a reunir a {{ $pet->name }} con su familia
      </p>
      <div class="share-buttons">
        <button class="btn-share btn-share-primary" id="shareBtn2" aria-label="Compartir">
          <i class="fa-solid fa-share"></i>
          Compartir perfil
        </button>
        <a href="{{ $mapsUrl ?? '#' }}"
           target="_blank"
           rel="noopener"
           class="btn-share btn-share-secondary {{ $mapsUrl ? '' : 'disabled' }}"
           aria-label="Ver ubicación">
          <i class="fa-solid fa-map"></i>
          Ver ubicación
        </a>
      </div>
    </div>

  </div>

</div>

{{-- ===== LIGHTBOX ===== --}}
<div class="lightbox" id="lightbox" role="dialog" aria-modal="true" aria-label="Visor de imágenes">
  <button class="lightbox-close" id="lightboxClose" aria-label="Cerrar">
    <i class="fa-solid fa-xmark"></i>
  </button>
  <button class="lightbox-nav lightbox-prev" id="lightboxPrev" aria-label="Imagen anterior">
    <i class="fa-solid fa-chevron-left"></i>
  </button>
  <div class="lightbox-content">
    <img src="" alt="Foto ampliada" class="lightbox-img" id="lightboxImg">
  </div>
  <button class="lightbox-nav lightbox-next" id="lightboxNext" aria-label="Imagen siguiente">
    <i class="fa-solid fa-chevron-right"></i>
  </button>
</div>

@endsection

@push('scripts')
<script>
// ===== CAROUSEL AUTOMÁTICO =====
(function(){
  const slides = document.querySelectorAll('.carousel-slide');
  const dots = document.querySelectorAll('.carousel-dot');
  const prevBtn = document.getElementById('carouselPrev');
  const nextBtn = document.getElementById('carouselNext');
  const carousel = document.getElementById('carousel');

  if(slides.length <= 1) return;

  let current = 0;
  let autoplayInterval;

  function goTo(index){
    current = (index + slides.length) % slides.length;
    slides.forEach((s, i) => s.classList.toggle('active', i === current));
    dots.forEach((d, i) => d.classList.toggle('active', i === current));
  }

  function next(){ goTo(current + 1); }
  function prev(){ goTo(current - 1); }

  function startAutoplay(){
    stopAutoplay();
    autoplayInterval = setInterval(next, 5000);
  }

  function stopAutoplay(){
    if(autoplayInterval) clearInterval(autoplayInterval);
  }

  if(prevBtn) prevBtn.addEventListener('click', () => { prev(); stopAutoplay(); setTimeout(startAutoplay, 10000); });
  if(nextBtn) nextBtn.addEventListener('click', () => { next(); stopAutoplay(); setTimeout(startAutoplay, 10000); });

  dots.forEach((dot, i) => {
    dot.addEventListener('click', () => {
      goTo(i);
      stopAutoplay();
      setTimeout(startAutoplay, 10000);
    });
  });

  // Swipe en móviles
  let touchStart = null;
  carousel.addEventListener('touchstart', e => { touchStart = e.touches[0].clientX; stopAutoplay(); });
  carousel.addEventListener('touchend', e => {
    if(!touchStart) return;
    const diff = e.changedTouches[0].clientX - touchStart;
    if(Math.abs(diff) > 50) diff > 0 ? prev() : next();
    touchStart = null;
    setTimeout(startAutoplay, 10000);
  });

  // Click para abrir lightbox
  slides.forEach((slide, i) => {
    slide.addEventListener('click', () => {
      const lightbox = document.getElementById('lightbox');
      const lightboxImg = document.getElementById('lightboxImg');
      lightboxImg.src = slide.querySelector('img').src;
      lightbox.classList.add('active');
      stopAutoplay();
    });
  });

  startAutoplay();
})();

// ===== LIGHTBOX =====
(function(){
  const lightbox = document.getElementById('lightbox');
  const lightboxImg = document.getElementById('lightboxImg');
  const closeBtn = document.getElementById('lightboxClose');
  const prevBtn = document.getElementById('lightboxPrev');
  const nextBtn = document.getElementById('lightboxNext');
  const slides = Array.from(document.querySelectorAll('.carousel-slide img')).map(img => img.src);

  let currentIndex = 0;

  function show(index){
    currentIndex = (index + slides.length) % slides.length;
    lightboxImg.src = slides[currentIndex];
  }

  if(closeBtn) closeBtn.addEventListener('click', () => lightbox.classList.remove('active'));
  if(prevBtn) prevBtn.addEventListener('click', () => show(currentIndex - 1));
  if(nextBtn) nextBtn.addEventListener('click', () => show(currentIndex + 1));

  lightbox.addEventListener('click', e => {
    if(e.target === lightbox) lightbox.classList.remove('active');
  });

  document.addEventListener('keydown', e => {
    if(!lightbox.classList.contains('active')) return;
    if(e.key === 'Escape') lightbox.classList.remove('active');
    if(e.key === 'ArrowLeft') show(currentIndex - 1);
    if(e.key === 'ArrowRight') show(currentIndex + 1);
  });
})();

// ===== COMPARTIR =====
(function(){
  const btns = [document.getElementById('shareBtn'), document.getElementById('shareBtn2')].filter(Boolean);
  if(btns.length === 0) return;

  const url = @json($shareUrl);
  const title = @json($pet->name . ' - Perfil de Mascota');

  function copyToClipboard(text){
    if(navigator.clipboard && window.isSecureContext){
      return navigator.clipboard.writeText(text);
    }
    const textarea = document.createElement('textarea');
    textarea.value = text;
    textarea.style.position = 'fixed';
    textarea.style.left = '-999999px';
    document.body.appendChild(textarea);
    textarea.select();
    return new Promise((res,rej) => {
      document.execCommand('copy') ? res() : rej();
      textarea.remove();
    });
  }

  btns.forEach(btn => {
    btn.addEventListener('click', async (e) => {
      e.preventDefault();

      if(navigator.share){
        try{
          await navigator.share({ title, url });
          return;
        } catch{}
      }

      try{
        await copyToClipboard(url);
        const orig = btn.innerHTML;
        btn.innerHTML = '<i class="fa-solid fa-check"></i> ¡Copiado!';
        setTimeout(() => btn.innerHTML = orig, 2500);
      } catch{
        alert('Copia este enlace:\n' + url);
      }
    });
  });
})();
</script>

{{-- ===== AUTO-PING CON GEOLOCALIZACIÓN ===== --}}
<script>
(function autoPing(){
  const url  = @json(route('public.pet.ping', ['slug' => $slug], false));
  const csrf = document.querySelector('meta[name="csrf-token"]')?.content || '';

  function send(body){
    fetch(url, {
      method: 'POST',
      headers: {
        'Content-Type':'application/json',
        'X-CSRF-TOKEN': csrf,
        'Accept':'application/json'
      },
      body: JSON.stringify(body)
    }).catch(()=>{});
  }

  if (navigator.geolocation && (window.isSecureContext || location.protocol === 'https:' || ['localhost','127.0.0.1'].includes(location.hostname))) {
    let done = false;
    const timer = setTimeout(() => { if (!done) { done = true; send({ method:'ip' }); } }, 6000);

    navigator.geolocation.getCurrentPosition(
      pos => {
        if (done) return; done = true; clearTimeout(timer);
        const c = pos.coords || {};
        send({ method:'gps', lat:c.latitude, lng:c.longitude, accuracy: Math.round(c.accuracy || 0) });
      },
      _ => { if (done) return; done = true; clearTimeout(timer); send({ method:'ip' }); },
      { enableHighAccuracy:true, timeout:12000, maximumAge:0 }
    );
  } else {
    send({ method:'ip' });
  }
})();
</script>
@endpush
