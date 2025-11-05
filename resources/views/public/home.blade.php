@extends('layouts.app')
@section('title', 'QR-Pet Tag — Protege siempre a tu mascota')

@push('styles')
{{-- Fuentes modernas --}}
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>

<style>
  :root {
    --primary: #4e89e8;
    --primary-dark: #3a6bb8;
    --secondary: #ff7e30;
    --secondary-dark: #e66a1f;
    --ink: #0f172a;
    --ink-light: #1e293b;
    --muted: #64748b;
    --muted-light: #94a3b8;
    --bg: #ffffff;
    --bg-subtle: #f8fafc;
    --success: #10b981;
    --success-light: #d1fae5;
    --border: #e2e8f0;
    --shadow-sm: 0 2px 8px rgba(15, 23, 42, 0.04);
    --shadow-md: 0 8px 24px rgba(15, 23, 42, 0.08);
    --shadow-lg: 0 16px 48px rgba(15, 23, 42, 0.12);
    --shadow-xl: 0 24px 64px rgba(15, 23, 42, 0.16);
  }

  * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
  }

  body {
    font-family: "Inter", -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
    background: var(--bg);
    color: var(--ink);
    overflow-x: hidden;
    line-height: 1.6;
  }

  /* ============================================
     HERO SECTION - Diseño moderno sin background
     ============================================ */
  .hero-modern {
    position: relative;
    min-height: 90vh;
    display: flex;
    align-items: center;
    padding: 100px 0 80px;
    background: var(--bg);
    overflow: hidden;
  }

  /* Decoración con círculos flotantes */
  .hero-decoration {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    pointer-events: none;
    overflow: hidden;
    z-index: 0;
  }

  .floating-circle {
    position: absolute;
    border-radius: 50%;
    opacity: 0.05;
    animation: float 20s ease-in-out infinite;
  }

  .floating-circle:nth-child(1) {
    width: 500px;
    height: 500px;
    background: linear-gradient(135deg, var(--primary), var(--secondary));
    top: -200px;
    right: -150px;
    animation-delay: 0s;
  }

  .floating-circle:nth-child(2) {
    width: 300px;
    height: 300px;
    background: linear-gradient(135deg, var(--secondary), var(--primary));
    bottom: -100px;
    left: -100px;
    animation-delay: 3s;
  }

  .floating-circle:nth-child(3) {
    width: 200px;
    height: 200px;
    background: linear-gradient(135deg, var(--primary), var(--success));
    top: 40%;
    left: 10%;
    animation-delay: 6s;
  }

  @keyframes float {
    0%, 100% { transform: translate(0, 0) scale(1); }
    25% { transform: translate(30px, -30px) scale(1.05); }
    50% { transform: translate(-20px, 20px) scale(0.95); }
    75% { transform: translate(20px, 30px) scale(1.02); }
  }

  .hero-content {
    position: relative;
    z-index: 2;
  }

  .hero-badge {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 10px 20px;
    background: linear-gradient(135deg, rgba(78, 137, 232, 0.1), rgba(255, 126, 48, 0.1));
    border: 1px solid rgba(78, 137, 232, 0.2);
    border-radius: 50px;
    font-size: 14px;
    font-weight: 600;
    color: var(--primary);
    margin-bottom: 24px;
    backdrop-filter: blur(10px);
    animation: slideDown 0.6s ease-out, pulse 3s ease-in-out infinite 1s;
  }

  @keyframes slideDown {
    from {
      opacity: 0;
      transform: translateY(-20px);
    }
    to {
      opacity: 1;
      transform: translateY(0);
    }
  }

  @keyframes pulse {
    0%, 100% { box-shadow: 0 0 0 0 rgba(78, 137, 232, 0.4); }
    50% { box-shadow: 0 0 0 15px rgba(78, 137, 232, 0); }
  }

  .hero-title {
    font-weight: 900;
    font-size: clamp(2.5rem, 5vw, 4.5rem);
    line-height: 1.1;
    color: var(--ink);
    margin-bottom: 24px;
    letter-spacing: -0.02em;
    word-wrap: break-word;
    overflow-wrap: break-word;
    hyphens: auto;
  }

  .hero-title-animated {
    opacity: 1;
    min-height: 120px;
  }

  .hero-title-text {
    display: inline-block;
  }

  @keyframes fadeInUp {
    to {
      opacity: 1;
      transform: translateY(0);
    }
    from {
      opacity: 0;
      transform: translateY(30px);
    }
  }

  .gradient-text {
    background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
    background-clip: text;
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    position: relative;
    display: inline-block;
  }

  .hero-subtitle {
    font-size: clamp(1.1rem, 2vw, 1.4rem);
    color: var(--muted);
    margin-bottom: 40px;
    max-width: 600px;
    line-height: 1.7;
    opacity: 0;
    animation: fadeInUp 0.8s ease-out 0.4s forwards;
  }

  .hero-cta {
    display: flex;
    gap: 16px;
    flex-wrap: wrap;
    opacity: 0;
    animation: fadeInUp 0.8s ease-out 0.6s forwards;
  }

  .btn-hero {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    padding: 16px 32px;
    font-size: 16px;
    font-weight: 600;
    border-radius: 12px;
    text-decoration: none;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    border: none;
    cursor: pointer;
    position: relative;
    overflow: hidden;
  }

  .btn-hero::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
    transition: left 0.5s;
  }

  .btn-hero:hover::before {
    left: 100%;
  }

  .btn-primary {
    background: linear-gradient(135deg, var(--primary), var(--primary-dark));
    color: white;
    box-shadow: 0 8px 24px rgba(78, 137, 232, 0.3);
  }

  .btn-primary:hover {
    transform: translateY(-3px);
    box-shadow: 0 12px 32px rgba(78, 137, 232, 0.4);
    color: white;
  }

  .btn-secondary {
    background: white;
    color: var(--ink);
    border: 2px solid var(--border);
    box-shadow: var(--shadow-sm);
  }

  .btn-secondary:hover {
    transform: translateY(-3px);
    box-shadow: var(--shadow-md);
    border-color: var(--primary);
    color: var(--primary);
  }

  .hero-image {
    position: relative;
    z-index: 2;
    opacity: 0;
    animation: fadeInScale 1s ease-out 0.4s forwards;
  }

  @keyframes fadeInScale {
    from {
      opacity: 0;
      transform: scale(0.9) translateY(20px);
    }
    to {
      opacity: 1;
      transform: scale(1) translateY(0);
    }
  }

  .hero-image-wrapper {
    position: relative;
    animation: floatImage 6s ease-in-out infinite;
  }

  @keyframes floatImage {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-20px); }
  }

  .hero-image-inner {
    position: relative;
    border-radius: 24px;
    overflow: hidden;
    box-shadow: var(--shadow-xl);
    transition: transform 0.4s ease;
  }

  .hero-image-inner:hover {
    transform: scale(1.02) rotate(1deg);
  }

  .hero-image-inner img {
    width: 100%;
    height: auto;
    display: block;
  }

  /* Efecto de brillo en la imagen */
  .hero-image-glow {
    position: absolute;
    top: -50%;
    left: -50%;
    width: 200%;
    height: 200%;
    background: radial-gradient(circle, rgba(78, 137, 232, 0.15), transparent 70%);
    animation: rotate 10s linear infinite;
    pointer-events: none;
  }

  @keyframes rotate {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
  }

  /* ============================================
     TRUST BADGES - Rediseño moderno
     ============================================ */
  .trust-section {
    padding: 60px 0;
    background: var(--bg-subtle);
    border-top: 1px solid var(--border);
    border-bottom: 1px solid var(--border);
  }

  .trust-badge-modern {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 16px 24px;
    background: white;
    border-radius: 16px;
    box-shadow: var(--shadow-sm);
    transition: all 0.3s ease;
    border: 1px solid var(--border);
  }

  .trust-badge-modern:hover {
    transform: translateY(-4px);
    box-shadow: var(--shadow-md);
    border-color: var(--primary);
  }

  .trust-badge-icon {
    width: 48px;
    height: 48px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 12px;
    font-size: 24px;
    background: linear-gradient(135deg, rgba(78, 137, 232, 0.1), rgba(78, 137, 232, 0.05));
    color: var(--primary);
  }

  .trust-badge-content h4 {
    font-size: 16px;
    font-weight: 700;
    color: var(--ink);
    margin: 0 0 2px 0;
  }

  .trust-badge-content p {
    font-size: 14px;
    color: var(--muted);
    margin: 0;
  }

  /* ============================================
     METRICS SECTION - Estadísticas animadas
     ============================================ */
  .metrics-section {
    padding: 80px 0;
    background: white;
  }

  .metric-card-modern {
    text-align: center;
    padding: 40px 24px;
    background: white;
    border: 2px solid var(--border);
    border-radius: 20px;
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    overflow: hidden;
  }

  .metric-card-modern::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, var(--primary), var(--secondary));
    transform: scaleX(0);
    transition: transform 0.4s ease;
  }

  .metric-card-modern:hover {
    transform: translateY(-8px);
    border-color: var(--primary);
    box-shadow: var(--shadow-lg);
  }

  .metric-card-modern:hover::before {
    transform: scaleX(1);
  }

  .metric-icon-modern {
    width: 64px;
    height: 64px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 16px;
    font-size: 28px;
    margin-bottom: 20px;
    background: linear-gradient(135deg, var(--primary), var(--primary-dark));
    color: white;
    box-shadow: 0 8px 24px rgba(78, 137, 232, 0.25);
    animation: bounceIn 0.6s ease-out;
  }

  @keyframes bounceIn {
    0% { transform: scale(0); }
    50% { transform: scale(1.1); }
    100% { transform: scale(1); }
  }

  .metric-number {
    font-size: clamp(2.5rem, 4vw, 3.5rem);
    font-weight: 900;
    color: var(--ink);
    margin: 0 0 8px 0;
    line-height: 1;
  }

  .metric-label {
    font-size: 16px;
    color: var(--muted);
    font-weight: 500;
    margin: 0;
  }

  /* ============================================
     FEATURES SECTION - Características
     ============================================ */
  .features-section {
    padding: 100px 0;
    background: var(--bg-subtle);
    position: relative;
  }

  .section-header {
    text-align: center;
    margin-bottom: 64px;
  }

  .section-badge {
    display: inline-block;
    padding: 8px 20px;
    background: linear-gradient(135deg, rgba(78, 137, 232, 0.1), rgba(255, 126, 48, 0.1));
    border: 1px solid rgba(78, 137, 232, 0.2);
    border-radius: 50px;
    font-size: 14px;
    font-weight: 600;
    color: var(--primary);
    margin-bottom: 16px;
  }

  .section-title {
    font-size: clamp(2rem, 4vw, 3rem);
    font-weight: 900;
    color: var(--ink);
    margin: 0 0 16px 0;
    letter-spacing: -0.02em;
  }

  .section-subtitle {
    font-size: 18px;
    color: var(--muted);
    max-width: 600px;
    margin: 0 auto;
  }

  .feature-card-modern {
    background: white;
    border: 2px solid var(--border);
    border-radius: 24px;
    padding: 40px 32px;
    height: 100%;
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    overflow: hidden;
  }

  .feature-card-modern::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, rgba(78, 137, 232, 0.02), rgba(255, 126, 48, 0.02));
    opacity: 0;
    transition: opacity 0.4s ease;
  }

  .feature-card-modern:hover::before {
    opacity: 1;
  }

  .feature-card-modern:hover {
    transform: translateY(-12px);
    border-color: var(--primary);
    box-shadow: var(--shadow-xl);
  }

  .feature-icon-modern {
    width: 72px;
    height: 72px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 18px;
    font-size: 32px;
    margin-bottom: 24px;
    background: linear-gradient(135deg, rgba(78, 137, 232, 0.1), rgba(78, 137, 232, 0.05));
    color: var(--primary);
    transition: all 0.4s ease;
  }

  .feature-card-modern:hover .feature-icon-modern {
    transform: scale(1.1) rotate(5deg);
    box-shadow: 0 8px 24px rgba(78, 137, 232, 0.2);
  }

  .feature-title {
    font-size: 22px;
    font-weight: 700;
    color: var(--ink);
    margin: 0 0 12px 0;
  }

  .feature-description {
    font-size: 15px;
    color: var(--muted);
    line-height: 1.7;
    margin: 0;
  }

  /* ============================================
     BENEFITS SECTION - Beneficios
     ============================================ */
  .benefits-section {
    padding: 100px 0;
    background: white;
  }

  .benefit-card-modern {
    display: flex;
    gap: 24px;
    padding: 32px;
    background: white;
    border: 2px solid var(--border);
    border-radius: 20px;
    transition: all 0.3s ease;
    height: 100%;
  }

  .benefit-card-modern:hover {
    transform: translateX(8px);
    border-color: var(--success);
    box-shadow: var(--shadow-md);
  }

  .benefit-icon-modern {
    width: 56px;
    height: 56px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 14px;
    font-size: 24px;
    background: linear-gradient(135deg, rgba(16, 185, 129, 0.1), rgba(16, 185, 129, 0.05));
    color: var(--success);
    flex-shrink: 0;
  }

  .benefit-content h3 {
    font-size: 18px;
    font-weight: 700;
    color: var(--ink);
    margin: 0 0 8px 0;
  }

  .benefit-content p {
    font-size: 15px;
    color: var(--muted);
    margin: 0;
    line-height: 1.6;
  }

  /* ============================================
     HOW IT WORKS - Paso a paso
     ============================================ */
  .how-it-works {
    padding: 100px 0;
    background: var(--bg-subtle);
  }

  .step-card {
    position: relative;
    text-align: center;
    padding: 40px 24px;
  }

  .step-number {
    width: 64px;
    height: 64px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    font-size: 24px;
    font-weight: 900;
    background: linear-gradient(135deg, var(--primary), var(--primary-dark));
    color: white;
    margin-bottom: 24px;
    box-shadow: 0 8px 24px rgba(78, 137, 232, 0.3);
    position: relative;
    z-index: 2;
  }

  .step-connector {
    position: absolute;
    top: 72px;
    left: 50%;
    transform: translateX(-50%);
    width: 2px;
    height: calc(100% - 144px);
    background: linear-gradient(180deg, var(--primary), transparent);
    z-index: 1;
  }

  .step-card:last-child .step-connector {
    display: none;
  }

  .step-title {
    font-size: 20px;
    font-weight: 700;
    color: var(--ink);
    margin: 0 0 12px 0;
  }

  .step-description {
    font-size: 15px;
    color: var(--muted);
    margin: 0;
    line-height: 1.6;
  }

  /* ============================================
     PETS GALLERY - Galería de mascotas
     ============================================ */
  .pets-gallery-section {
    padding: 100px 0;
    background: white;
  }

  .pets-gallery {
    margin-top: 48px;
  }

  .pet-card {
    position: relative;
    cursor: pointer;
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
  }

  .pet-card:hover {
    transform: translateY(-12px);
  }

  .pet-image {
    position: relative;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: var(--shadow-md);
    transition: all 0.4s ease;
    aspect-ratio: 1;
  }

  .pet-card:hover .pet-image {
    box-shadow: var(--shadow-xl);
  }

  .pet-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
    transition: transform 0.4s ease;
  }

  .pet-card:hover .pet-image img {
    transform: scale(1.05);
  }

  .pet-badge {
    position: absolute;
    top: 12px;
    right: 12px;
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, var(--success), #059669);
    border-radius: 50%;
    color: white;
    font-size: 18px;
    box-shadow: 0 4px 12px rgba(16, 185, 129, 0.4);
    animation: badgePop 0.6s ease-out 0.3s both;
  }

  @keyframes badgePop {
    0% {
      transform: scale(0) rotate(-180deg);
      opacity: 0;
    }
    70% {
      transform: scale(1.2) rotate(10deg);
    }
    100% {
      transform: scale(1) rotate(0deg);
      opacity: 1;
    }
  }

  .pet-name {
    text-align: center;
    margin-top: 16px;
    font-size: 18px;
    font-weight: 700;
    color: var(--ink);
    transition: color 0.3s ease;
  }

  .pet-card:hover .pet-name {
    color: var(--primary);
  }

  /* ============================================
     FAQ SECTION - Preguntas frecuentes
     ============================================ */
  .faq-modern {
    padding: 100px 0;
    background: white;
    position: relative;
  }

  .faq-container {
    max-width: 800px;
    margin: 0 auto;
  }

  .faq-item {
    background: white;
    border: 2px solid var(--border);
    border-radius: 20px;
    margin-bottom: 20px;
    overflow: hidden;
    transition: all 0.3s ease;
  }

  .faq-item:hover {
    border-color: var(--primary);
    box-shadow: var(--shadow-md);
  }

  .faq-question {
    display: flex;
    align-items: center;
    gap: 20px;
    padding: 24px 28px;
    cursor: pointer;
    transition: all 0.3s ease;
  }

  .faq-question:hover {
    background: rgba(78, 137, 232, 0.02);
  }

  .faq-icon {
    width: 48px;
    height: 48px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 12px;
    font-size: 20px;
    background: linear-gradient(135deg, var(--primary), var(--primary-dark));
    color: white;
    flex-shrink: 0;
  }

  .faq-question-text {
    flex: 1;
  }

  .faq-question-text h3 {
    font-size: 18px;
    font-weight: 600;
    color: var(--ink);
    margin: 0;
  }

  .faq-toggle {
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 8px;
    background: var(--bg-subtle);
    color: var(--primary);
    transition: all 0.3s ease;
    flex-shrink: 0;
  }

  .faq-item.active .faq-toggle {
    transform: rotate(180deg);
    background: var(--primary);
    color: white;
  }

  .faq-content {
    max-height: 0;
    overflow: hidden;
    transition: max-height 0.4s ease, padding 0.4s ease;
    padding: 0 28px;
  }

  .faq-item.active .faq-content {
    max-height: 1000px;
    padding: 0 28px 24px 96px;
  }

  .faq-content p {
    color: var(--muted);
    line-height: 1.7;
    margin: 0 0 16px 0;
  }

  .faq-features {
    display: flex;
    flex-direction: column;
    gap: 12px;
  }

  .faq-feature {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px 16px;
    background: var(--bg-subtle);
    border-radius: 12px;
  }

  .faq-feature i {
    color: var(--primary);
    font-size: 18px;
  }

  .faq-feature span {
    color: var(--ink);
    font-size: 14px;
    font-weight: 500;
  }

  /* ============================================
     SOCIAL CTA - Redes sociales
     ============================================ */
  .social-cta {
    padding: 80px 0;
    background: var(--bg-subtle);
    text-align: center;
  }

  .social-buttons {
    display: flex;
    justify-content: center;
    gap: 16px;
    flex-wrap: wrap;
  }

  .social-btn {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    padding: 14px 28px;
    border-radius: 12px;
    font-size: 16px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.3s ease;
    border: 2px solid var(--border);
    background: white;
    color: var(--ink);
  }

  .social-btn:hover {
    transform: translateY(-4px);
    box-shadow: var(--shadow-md);
    color: var(--ink);
  }

  .social-btn.wa:hover {
    border-color: #25d366;
    color: #25d366;
  }

  .social-btn.fb:hover {
    border-color: #1877f2;
    color: #1877f2;
  }

  .social-btn.tt:hover {
    border-color: #000000;
    color: #000000;
  }

  .social-btn i {
    font-size: 20px;
  }

  /* ============================================
     FINAL CTA - Llamada a la acción
     ============================================ */
  .cta-final {
    padding: 100px 0;
    background: linear-gradient(135deg, var(--primary), var(--primary-dark));
    color: white;
    text-align: center;
    position: relative;
    overflow: hidden;
  }

  .cta-final::before {
    content: '';
    position: absolute;
    top: -50%;
    left: -50%;
    width: 200%;
    height: 200%;
    background: radial-gradient(circle, rgba(255,255,255,0.1), transparent 70%);
    animation: rotate 20s linear infinite;
  }

  .cta-final-content {
    position: relative;
    z-index: 2;
  }

  .cta-final h2 {
    font-size: clamp(2rem, 4vw, 3rem);
    font-weight: 900;
    margin: 0 0 16px 0;
  }

  .cta-final p {
    font-size: 18px;
    margin: 0 0 32px 0;
    opacity: 0.9;
  }

  .btn-cta-final {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    padding: 18px 40px;
    background: white;
    color: var(--primary);
    font-size: 18px;
    font-weight: 700;
    border-radius: 14px;
    text-decoration: none;
    transition: all 0.3s ease;
    box-shadow: 0 12px 32px rgba(0, 0, 0, 0.2);
  }

  .btn-cta-final:hover {
    transform: translateY(-4px) scale(1.05);
    box-shadow: 0 16px 48px rgba(0, 0, 0, 0.3);
    color: var(--primary);
  }

  /* ============================================
     WHATSAPP FLOAT BUTTON - Rediseñado
     ============================================ */
  .whatsapp-float {
    position: fixed;
    bottom: 30px;
    right: 30px;
    width: 64px;
    height: 64px;
    background: linear-gradient(135deg, #25d366, #20c45a);
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 32px;
    box-shadow: 0 8px 24px rgba(37, 211, 102, 0.4);
    z-index: 1000;
    transition: all 0.3s ease;
    text-decoration: none;
    animation: floatWhatsApp 3s ease-in-out infinite;
  }

  .whatsapp-float:hover {
    transform: scale(1.1);
    box-shadow: 0 12px 32px rgba(37, 211, 102, 0.6);
    color: white;
  }

  @keyframes floatWhatsApp {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-10px); }
  }

  .whatsapp-tooltip {
    position: absolute;
    right: 80px;
    background: white;
    color: var(--ink);
    padding: 12px 20px;
    border-radius: 12px;
    font-size: 14px;
    font-weight: 600;
    white-space: nowrap;
    box-shadow: var(--shadow-lg);
    opacity: 0;
    pointer-events: none;
    transition: all 0.3s ease;
  }

  .whatsapp-float:hover .whatsapp-tooltip {
    opacity: 1;
    right: 75px;
  }

  /* ============================================
     REVEAL ANIMATIONS - Animaciones al scroll
     ============================================ */
  .reveal {
    opacity: 0;
    transform: translateY(40px);
    transition: all 0.8s cubic-bezier(0.4, 0, 0.2, 1);
  }

  .reveal.show {
    opacity: 1;
    transform: translateY(0);
  }

  /* ============================================
     RESPONSIVE DESIGN - Móviles
     ============================================ */
  @media (max-width: 768px) {
    .hero-modern {
      min-height: auto;
      padding: 80px 0 60px;
    }

    .hero-title {
      font-size: clamp(1.75rem, 6vw, 2.5rem);
      line-height: 1.2;
      word-break: break-word;
    }

    .hero-title-animated {
      min-height: 100px;
    }

    .hero-subtitle {
      font-size: 1rem;
      line-height: 1.6;
    }

    .hero-cta {
      flex-direction: column;
      align-items: stretch;
    }

    .btn-hero {
      justify-content: center;
      width: 100%;
      padding: 14px 24px;
    }

    .floating-circle:nth-child(1) {
      width: 300px;
      height: 300px;
      top: -100px;
      right: -100px;
    }

    .floating-circle:nth-child(2) {
      width: 200px;
      height: 200px;
    }

    .floating-circle:nth-child(3) {
      display: none;
    }

    .metrics-section,
    .features-section,
    .benefits-section,
    .how-it-works,
    .pets-gallery-section,
    .faq-modern {
      padding: 60px 0;
    }

    .section-title {
      font-size: 1.75rem;
    }

    .section-header {
      margin-bottom: 40px;
    }

    .metric-card-modern {
      padding: 32px 20px;
    }

    .feature-card-modern,
    .benefit-card-modern {
      padding: 28px 20px;
    }

    .faq-question {
      padding: 20px;
      gap: 12px;
    }

    .faq-icon {
      width: 40px;
      height: 40px;
      font-size: 18px;
    }

    .faq-question-text h3 {
      font-size: 16px;
    }

    .faq-item.active .faq-content {
      padding: 0 20px 20px 20px;
    }

    .step-connector {
      display: none;
    }

    .cta-final {
      padding: 60px 0;
    }

    .social-buttons {
      flex-direction: column;
      align-items: stretch;
    }

    .social-btn {
      justify-content: center;
      width: 100%;
    }

    .whatsapp-float {
      width: 56px;
      height: 56px;
      bottom: 20px;
      right: 20px;
      font-size: 28px;
    }

    .whatsapp-tooltip {
      display: none;
    }

    .trust-badge-modern {
      flex-direction: column;
      text-align: center;
      padding: 20px;
    }
  }

  @media (max-width: 576px) {
    .hero-title {
      font-size: clamp(1.5rem, 7vw, 2rem);
      line-height: 1.3;
      margin-bottom: 20px;
      padding: 0 10px;
    }

    .hero-title-animated {
      min-height: 90px;
    }

    .hero-subtitle {
      font-size: 0.95rem;
      padding: 0 10px;
    }

    .hero-badge {
      font-size: 12px;
      padding: 8px 16px;
    }

    .btn-hero {
      padding: 14px 24px;
      font-size: 14px;
    }

    .metric-number {
      font-size: 2rem;
    }

    .feature-icon-modern,
    .benefit-icon-modern {
      width: 56px;
      height: 56px;
      font-size: 24px;
    }

    .step-number {
      width: 56px;
      height: 56px;
      font-size: 20px;
    }

    .section-title {
      font-size: 1.5rem;
      padding: 0 10px;
    }

    .section-subtitle {
      font-size: 0.95rem;
      padding: 0 10px;
    }

    /* Galería de mascotas en móvil */
    .pet-card {
      margin-bottom: 8px;
    }

    .pet-badge {
      width: 32px;
      height: 32px;
      font-size: 14px;
      top: 8px;
      right: 8px;
    }

    .pet-name {
      font-size: 16px;
      margin-top: 12px;
    }

    /* Asegurar que nada se salga */
    body {
      overflow-x: hidden;
    }

    .container {
      padding-left: 15px;
      padding-right: 15px;
    }

    * {
      max-width: 100%;
    }
  }

  /* ============================================
     PERFORMANCE OPTIMIZATIONS
     ============================================ */
  .will-change-transform {
    will-change: transform;
  }

  img {
    max-width: 100%;
    height: auto;
  }

  /* Reducir animaciones para usuarios que prefieren menos movimiento */
  @media (prefers-reduced-motion: reduce) {
    *,
    *::before,
    *::after {
      animation-duration: 0.01ms !important;
      animation-iteration-count: 1 !important;
      transition-duration: 0.01ms !important;
    }
  }
</style>
@endpush

@section('content')

{{-- ====== HERO SECTION ====== --}}
<section class="hero-modern">
  <div class="hero-decoration">
    <div class="floating-circle"></div>
    <div class="floating-circle"></div>
    <div class="floating-circle"></div>
  </div>

  <div class="container">
    <div class="row align-items-center">
      <div class="col-lg-6 hero-content">
        <div class="hero-badge">
          <i class="fa-solid fa-shield-check"></i>
          <span>Tecnología de protección inteligente</span>
        </div>
        
        <h1 class="hero-title">
          <span class="hero-title-animated">
            <span class="hero-title-text" id="heroTitle">Nunca más pierdas a tu mejor amigo 🐾</span> 
            <span class="gradient-text"></span>
          </span>
        </h1>
        
        <p class="hero-subtitle">
          Con QR-Pet Tag, tu mascota lleva un código QR único que permite a cualquier persona escanear y contactarte al instante si la encuentran. Protección 24/7 en segundos.
        </p>

        <div class="hero-cta">
          @guest
            <a href="{{ route('plans.index') }}" class="btn-hero btn-primary will-change-transform">
              <i class="fa-solid fa-tags"></i>
              Ver Planes
            </a>
          @else
            <a href="{{ route('plans.index') }}" class="btn-hero btn-primary will-change-transform">
              <i class="fa-solid fa-tags"></i>
              Ver Planes
            </a>
          @endguest

          <a href="#como-funciona" class="btn-hero btn-secondary will-change-transform">
            <i class="fa-solid fa-circle-play"></i>
            Ver cómo funciona
          </a>
        </div>
      </div>

      <div class="col-lg-6 hero-image">
        <div class="hero-image-wrapper will-change-transform">
          <div class="hero-image-inner">
            <div class="hero-image-glow"></div>
            <img src="https://images.unsplash.com/photo-1507146426996-ef05306b995a?q=80&w=1200&auto=format&fit=crop" 
                 alt="Mascota con placa QR"
                 width="520"
                 height="360"
                 loading="eager">
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

{{-- ====== TRUST BADGES ====== --}}
<section class="trust-section">
  <div class="container">
    <div class="row g-4">
      <div class="col-md-4 reveal">
        <div class="trust-badge-modern">
          <div class="trust-badge-icon">
            <i class="fa-solid fa-lock"></i>
          </div>
          <div class="trust-badge-content">
            <h4>100% Seguro</h4>
            <p>Tus datos protegidos</p>
          </div>
        </div>
      </div>
      <div class="col-md-4 reveal">
        <div class="trust-badge-modern">
          <div class="trust-badge-icon">
            <i class="fa-solid fa-bolt"></i>
          </div>
          <div class="trust-badge-content">
            <h4>Activación Instantánea</h4>
            <p>Rápida activación</p>
          </div>
        </div>
      </div>
      <div class="col-md-4 reveal">
        <div class="trust-badge-modern">
          <div class="trust-badge-icon">
            <i class="fa-solid fa-heart"></i>
          </div>
          <div class="trust-badge-content">
            <h4>Seguridad en mascotas</h4>
            <p>Ya protegidas</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

{{-- ===== SECCIÓN DE PLANES ===== --}}
@include('public.partials.plans-section')


{{-- ====== METRICS SECTION ====== --}}
<section class="metrics-section">
</section>

{{-- ====== FEATURES SECTION ====== --}}
<section class="features-section">
  <div class="container">
    <div class="section-header reveal">
      <span class="section-badge">
        <i class="fa-solid fa-star"></i> Características
      </span>
      <h2 class="section-title">Todo lo que necesitas en un <span class="gradient-text">solo lugar</span></h2>
      <p class="section-subtitle">Herramientas poderosas para mantener a tu mascota segura y conectada contigo</p>
    </div>

    <div class="row g-4">
      <div class="col-md-6 col-lg-4 reveal">
        <div class="feature-card-modern will-change-transform">
          <div class="feature-icon-modern">
            <i class="fa-solid fa-qrcode"></i>
          </div>
          <h3 class="feature-title">Código QR único</h3>
          <p class="feature-description">Cada mascota tiene su propio código QR inviolable que dirige a su perfil de contacto.</p>
        </div>
      </div>

      <div class="col-md-6 col-lg-4 reveal">
        <div class="feature-card-modern will-change-transform">
          <div class="feature-icon-modern">
            <i class="fa-solid fa-mobile-screen"></i>
          </div>
          <h3 class="feature-title">Sin app necesaria</h3>
          <p class="feature-description">Cualquier persona puede escanear el QR con la cámara de su teléfono. Simple y rápido.</p>
        </div>
      </div>

      <div class="col-md-6 col-lg-4 reveal">
        <div class="feature-card-modern will-change-transform">
          <div class="feature-icon-modern">
            <i class="fa-solid fa-bell"></i>
          </div>
          <h3 class="feature-title">Alertas instantáneas</h3>
          <p class="feature-description">Recibe notificación al momento cuando alguien escanea el QR de tu mascota.</p>
        </div>
      </div>

      <div class="col-md-6 col-lg-4 reveal">
        <div class="feature-card-modern will-change-transform">
          <div class="feature-icon-modern">
            <i class="fa-solid fa-shield-halved"></i>
          </div>
          <h3 class="feature-title">Privacidad total</h3>
          <p class="feature-description">Tú decides qué información compartir. Tus datos personales siempre protegidos.</p>
        </div>
      </div>

      <div class="col-md-6 col-lg-4 reveal">
        <div class="feature-card-modern will-change-transform">
          <div class="feature-icon-modern">
            <i class="fa-solid fa-pen-to-square"></i>
          </div>
          <h3 class="feature-title">Actualización fácil</h3>
          <p class="feature-description">Cambia la información de contacto cuando quieras desde tu panel de control.</p>
        </div>
      </div>

      <div class="col-md-6 col-lg-4 reveal">
        <div class="feature-card-modern will-change-transform">
          <div class="feature-icon-modern">
            <i class="fa-solid fa-medal"></i>
          </div>
          <h3 class="feature-title">Sistema de recompensas</h3>
          <p class="feature-description">Ofrece una recompensa para incentivar el retorno seguro de tu mascota.</p>
        </div>
      </div>
    </div>
  </div>
</section>

{{-- ====== BENEFITS SECTION ====== --}}
<section class="benefits-section">
  <div class="container">
    <div class="section-header reveal">
      <span class="section-badge">
        <i class="fa-solid fa-paw"></i> Beneficios
      </span>
      <h2 class="section-title">Descubre las ventajas de <span class="gradient-text">QR-Pet Tag</span></h2>
      <p class="section-subtitle">Diseñado para mantener a tu mascota siempre identificada y protegida</p>
    </div>

    <div class="row g-4">
      <div class="col-md-6 reveal">
        <div class="benefit-card-modern">
          <div class="benefit-icon-modern">
            <i class="fa-solid fa-id-badge"></i>
          </div>
          <div class="benefit-content">
            <h3>Identificación inteligente</h3>
            <p>Cada etiqueta QR contiene la información esencial de tu mascota, accesible en segundos desde cualquier dispositivo.</p>
          </div>
        </div>
      </div>

      <div class="col-md-6 reveal">
        <div class="benefit-card-modern">
          <div class="benefit-icon-modern">
            <i class="fa-solid fa-shield-heart"></i>
          </div>
          <div class="benefit-content">
            <h3>Seguridad y tranquilidad</h3>
            <p>Reduce el riesgo de pérdida al permitir que cualquier persona pueda contactarte fácilmente si encuentra a tu mascota.</p>
          </div>
        </div>
      </div>

      <div class="col-md-6 reveal">
        <div class="benefit-card-modern">
          <div class="benefit-icon-modern">
            <i class="fa-solid fa-globe"></i>
          </div>
          <div class="benefit-content">
            <h3>Disponible en cualquier lugar</h3>
            <p>Funciona globalmente sin necesidad de aplicaciones adicionales ni suscripciones.</p>
          </div>
        </div>
      </div>

      <div class="col-md-6 reveal">
        <div class="benefit-card-modern">
          <div class="benefit-icon-modern">
            <i class="fa-solid fa-headset"></i>
          </div>
          <div class="benefit-content">
            <h3>Soporte y acompañamiento</h3>
            <p>Te brindamos asistencia continua para que puedas aprovechar al máximo tu sistema QR-Pet Tag.</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>


{{-- ====== HOW IT WORKS ====== --}}
<section class="how-it-works" id="como-funciona">
  <div class="container">
    <div class="section-header reveal">
      <span class="section-badge">
        <i class="fa-solid fa-lightbulb"></i> Proceso
      </span>
      <h2 class="section-title">Activa la protección <span class="gradient-text">QR-Pet Tag</span> en 3 pasos</h2>
      <p class="section-subtitle">Empieza hoy y mantén a tu mascota identificada y segura en menos de un día</p>
    </div>

    <div class="row g-4">
      <div class="col-md-4 reveal">
        <div class="step-card">
          <div class="step-number">1</div>
          <div class="step-connector"></div>
          <h3 class="step-title">Elige tu plan</h3>
          <p class="step-description">
            Selecciona el plan que mejor se adapte a tus necesidades y registra la cantidad de mascotas que deseas proteger.
          </p>
        </div>
      </div>

      <div class="col-md-4 reveal">
        <div class="step-card">
          <div class="step-number">2</div>
          <div class="step-connector"></div>
          <h3 class="step-title">Sube tu comprobante</h3>
          <p class="step-description">
            Realiza el pago y carga el comprobante directamente en el sistema. Nuestro equipo verificará tu solicitud en menos de 24 horas.
          </p>
        </div>
      </div>

      <div class="col-md-4 reveal">
        <div class="step-card">
          <div class="step-number">3</div>
          <h3 class="step-title">Activa tu código QR</h3>
          <p class="step-description">
            Una vez verificado el pago, podrás acceder a tu panel, ver tus mascotas y activar sus códigos QR personalizados.
          </p>
        </div>
      </div>
    </div>
  </div>
</section>


{{-- ====== MASCOTAS PROTEGIDAS - Galería ====== --}}
<section class="pets-gallery-section">
  <div class="container">
    <div class="section-header reveal">
      <span class="section-badge">
        <i class="fa-solid fa-heart"></i> Mascotas Protegidas
      </span>
      <h2 class="section-title">Ellos ya están <span class="gradient-text">protegidos</span></h2>
      <p class="section-subtitle">Miles de mascotas ya confían en QR-Pet Tag para su seguridad</p>
    </div>

    <div class="pets-gallery reveal">
      <div class="row g-4">
        <div class="col-6 col-md-3">
          <div class="pet-card will-change-transform">
            <div class="pet-image">
              <img src="{{ asset('storage/images/asha.jpeg') }}" alt="Asha - Mascota protegida" loading="lazy">
            </div>
            <div class="pet-name"></div>
          </div>
        </div>
        
        <div class="col-6 col-md-3">
          <div class="pet-card will-change-transform">
            <div class="pet-image">
              <img src="{{ asset('storage/images/coqueta.jpeg') }}" alt="Coqueta - Mascota protegida" loading="lazy">
            </div>
            <div class="pet-name"></div>
          </div>
        </div>
        
        <div class="col-6 col-md-3">
          <div class="pet-card will-change-transform">
            <div class="pet-image">
              <img src="{{ asset('storage/images/morgan.jpeg') }}" alt="Morgan - Mascota protegida" loading="lazy">
            </div>
            <div class="pet-name"></div>
          </div>
        </div>
        
        <div class="col-6 col-md-3">
          <div class="pet-card will-change-transform">
            <div class="pet-image">
              <img src="{{ asset('storage/images/negro.jpeg') }}" alt="Negro - Mascota protegida" loading="lazy">
            </div>
            <div class="pet-name"></div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

{{-- ====== FAQ SECTION ====== --}}
<section class="faq-modern">
  <div class="container">
    <div class="section-header reveal">
      <span class="section-badge">
        <i class="fa-solid fa-circle-question"></i> FAQ
      </span>
      <h2 class="section-title">Preguntas <span class="gradient-text">frecuentes</span></h2>
      <p class="section-subtitle">Resolvemos tus dudas sobre QR-Pet Tag</p>
    </div>

    <div class="faq-container">
      <div class="faq-item reveal">
        <div class="faq-question" onclick="toggleFaq(this)">
          <div class="faq-icon">
            <i class="fa-solid fa-qrcode"></i>
          </div>
          <div class="faq-question-text">
            <h3>¿Cómo funciona el código QR?</h3>
          </div>
          <div class="faq-toggle">
            <i class="fa-solid fa-chevron-down"></i>
          </div>
        </div>
        <div class="faq-content">
          <p>El código QR es único para cada mascota y está vinculado a su perfil. Cuando alguien lo escanea con la cámara de su teléfono, accede instantáneamente a la información de contacto que decidiste compartir.</p>
          <p>No se necesita ninguna aplicación especial: cualquier smartphone moderno puede escanearlo directamente desde la cámara.</p>
        </div>
      </div>

      <div class="faq-item reveal">
        <div class="faq-question" onclick="toggleFaq(this)">
          <div class="faq-icon">
            <i class="fa-solid fa-shield"></i>
          </div>
          <div class="faq-question-text">
            <h3>¿Mis datos personales están seguros?</h3>
          </div>
          <div class="faq-toggle">
            <i class="fa-solid fa-chevron-down"></i>
          </div>
        </div>
        <div class="faq-content">
          <p>Absolutamente. Tú tienes control total sobre qué información se muestra en el perfil público de tu mascota.</p>
          <p>Puedes elegir mostrar solo un número de teléfono, un email alternativo, o cualquier método de contacto que prefieras. Tu información personal completa nunca se comparte públicamente.</p>
        </div>
      </div>

      <div class="faq-item reveal">
        <div class="faq-question" onclick="toggleFaq(this)">
          <div class="faq-icon">
            <i class="fa-solid fa-credit-card"></i>
          </div>
          <div class="faq-question-text">
            <h3>¿Cuánto cuesta el servicio?</h3>
          </div>
          <div class="faq-toggle">
            <i class="fa-solid fa-chevron-down"></i>
          </div>
        </div>
        <div class="faq-content">
          <p>Registrarte en la plataforma es completamente gratis. Puedes crear el perfil digital de tu mascota y generar su código QR sin costo alguno.</p>
          <p>Si deseas adquirir una placa física personalizada para el collar, esta tiene un costo único. ¡No hay mensualidades ni suscripciones!</p>
        </div>
      </div>

      <div class="faq-item reveal">
        <div class="faq-question" onclick="toggleFaq(this)">
          <div class="faq-icon">
            <i class="fa-solid fa-bell"></i>
          </div>
          <div class="faq-question-text">
            <h3>¿Recibo alertas cuando escanean el QR?</h3>
          </div>
          <div class="faq-toggle">
            <i class="fa-solid fa-chevron-down"></i>
          </div>
        </div>
        <div class="faq-content">
          <p>Sí, recibes una notificación inmediata cada vez que alguien escanea el código QR de tu mascota.</p>
          <p>Esto te permite saber al instante que tu mascota ha sido encontrada y alguien está intentando contactarte.</p>
        </div>
      </div>

      <div class="faq-item reveal">
        <div class="faq-question" onclick="toggleFaq(this)">
          <div class="faq-icon">
            <i class="fa-solid fa-pen"></i>
          </div>
          <div class="faq-question-text">
            <h3>¿Puedo actualizar la información después?</h3>
          </div>
          <div class="faq-toggle">
            <i class="fa-solid fa-chevron-down"></i>
          </div>
        </div>
        <div class="faq-content">
          <p>¡Por supuesto! Una de las grandes ventajas es que puedes actualizar toda la información desde tu panel de control en cualquier momento.</p>
          <p>Los cambios se reflejan inmediatamente en el perfil, sin necesidad de cambiar la placa física ni el código QR.</p>
        </div>
      </div>

      <div class="faq-item reveal">
        <div class="faq-question" onclick="toggleFaq(this)">
          <div class="faq-icon">
            <i class="fa-solid fa-coins"></i>
          </div>
          <div class="faq-question-text">
            <h3>¿Puedo ofrecer una recompensa?</h3>
          </div>
          <div class="faq-toggle">
            <i class="fa-solid fa-chevron-down"></i>
          </div>
        </div>
        <div class="faq-content">
          <p>¡Por supuesto! Desde tu panel de control puedes:</p>
          <div class="faq-features">
            <div class="faq-feature">
              <i class="fa-solid fa-toggle-on"></i>
              <span>Activar/desactivar recompensa cuando quieras</span>
            </div>
            <div class="faq-feature">
              <i class="fa-solid fa-coins"></i>
              <span>Establecer el monto que consideres apropiado</span>
            </div>
            <div class="faq-feature">
              <i class="fa-solid fa-eye"></i>
              <span>La recompensa aparece destacada en el perfil público</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

{{-- ====== SOCIAL CTA ====== --}}
<section class="social-cta">
  <div class="container reveal">
    <h2 class="section-title mb-3">Únete a nuestra comunidad</h2>
    <p class="section-subtitle mb-4">Muy pronto compartiremos tips, rescates y novedades. ¡Síguenos!</p>
    <div class="social-buttons">
      <a class="social-btn wa" href="https://www.instagram.com/qrpettag?igsh=MWRzdG1kMWVsZ2F0cQ%3D%3D&utm_source=qr" target="_blank" rel="noopener">
        <i class="fa-brands fa-instagram"></i> Instagram
      </a>
      <a class="social-btn fb" href="https://www.facebook.com/share/17VnVJfcxr/?mibextid=wwXIfr" target="_blank" rel="noopener">
        <i class="fa-brands fa-facebook-f"></i> Facebook
      </a>
      <a class="social-btn tt" href="#" target="_blank" rel="noopener">
        <i class="fa-brands fa-tiktok"></i> TikTok
      </a>
    </div>
  </div>
</section>

{{-- ====== FINAL CTA ====== --}}
<section class="cta-final">
  <div class="container cta-final-content reveal">
    <h2>Protege a tu mascota hoy mismo</h2>
    <p>Regístrate y crea su QR-Pet Tag en minutos. Protección 24/7 para tu mejor amigo.</p>
    @guest
      <a href="{{ route('plans.index') }}" class="btn-cta-final will-change-transform">
        <i class="fa-solid fa-tags"></i> Ver Planes
      </a>
    @else
      <a href="{{ route('plans.index') }}" class="btn-cta-final will-change-transform">
        <i class="fa-solid fa-tags"></i> Ver Planes
      </a>
    @endguest
  </div>
</section>

{{-- ====== FOOTER ====== --}}
<section class="py-4 text-center" style="background: var(--bg-subtle); color: var(--muted); border-top: 1px solid var(--border);">
  <div class="container small">© {{ date('Y') }} QR-Pet Tag — Todos los derechos reservados</div>
</section>

{{-- ====== WHATSAPP BUTTON ====== --}}
@php
    $whatsappNumber = config('app.whatsapp_number');
    $message = "¡Hola! Me gustaría obtener más información sobre los tags";
    $encodedMessage = str_replace('+', '%20', urlencode($message));
@endphp

<a href="https://wa.me/{{ $whatsappNumber }}?text={{ $encodedMessage }}" 
   class="whatsapp-float will-change-transform" 
   target="_blank" 
   title="Chatea con nosotros 💬">
  <i class="fa-brands fa-whatsapp"></i>
  <span class="whatsapp-tooltip">¿Necesitas ayuda? 💬</span>
</a>

@endsection

@push('scripts')
<script>
// Esperar a que el DOM esté completamente cargado
document.addEventListener('DOMContentLoaded', () => {
  
  /* ========= TÍTULOS ALTERNANTES ========= */
  const heroTitleEl = document.getElementById('heroTitle');
  
  const phrases = [
    'Nunca más pierdas a tu mejor amigo 🐾',
    'Tu mascota siempre vuelve a casa 🐾',
    'Un QR que conecta en segundos 🐾',
    'Más seguridad, menos estrés 🐾',
    'Protección 24/7 para tu mascota 🐾'
  ];
  
  let currentPhraseIndex = 0;
  
  function changePhrase() {
    // Fade out
    heroTitleEl.style.opacity = '0';
    heroTitleEl.style.transform = 'translateY(20px)';
    
    setTimeout(() => {
      // Cambiar texto
      currentPhraseIndex = (currentPhraseIndex + 1) % phrases.length;
      heroTitleEl.textContent = phrases[currentPhraseIndex];
      
      // Fade in
      heroTitleEl.style.opacity = '1';
      heroTitleEl.style.transform = 'translateY(0)';
    }, 500);
  }
  
  // Cambiar frase cada 4 segundos
  setInterval(changePhrase, 4000);
  
  // Estilos de transición para el título
  heroTitleEl.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
  
  /* ========= REVEAL ON SCROLL ========= */
  const observerOptions = {
    threshold: 0.15,
    rootMargin: '0px 0px -50px 0px'
  };

  const observer = new IntersectionObserver((entries) => {
    entries.forEach((entry, index) => {
      if (entry.isIntersecting) {
        setTimeout(() => {
          entry.target.classList.add('show');
        }, index * 100); // Stagger animation
        observer.unobserve(entry.target);
      }
    });
  }, observerOptions);

  document.querySelectorAll('.reveal').forEach(el => observer.observe(el));

  /* ========= COUNTER ANIMATION ========= */
  const runCounter = (el) => {
    const target = parseInt(el.dataset.target);
    const duration = 2000; // 2 seconds
    const increment = target / (duration / 16); // 60 FPS
    let current = 0;

    const updateCounter = () => {
      current += increment;
      if (current < target) {
        el.textContent = Math.floor(current).toLocaleString();
        requestAnimationFrame(updateCounter);
      } else {
        el.textContent = target.toLocaleString();
      }
    };

    updateCounter();
  };

  const counterObserver = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        runCounter(entry.target);
        counterObserver.unobserve(entry.target);
      }
    });
  }, { threshold: 0.5 });

  document.querySelectorAll('.counter').forEach(counter => {
    counterObserver.observe(counter);
  });

  /* ========= SMOOTH SCROLL ========= */
  document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
      const href = this.getAttribute('href');
      if (href !== '#' && href !== '') {
        e.preventDefault();
        const target = document.querySelector(href);
        if (target) {
          target.scrollIntoView({
            behavior: 'smooth',
            block: 'start'
          });
        }
      }
    });
  });

  /* ========= PERFORMANCE: Lazy load images ========= */
  if ('IntersectionObserver' in window) {
    const imageObserver = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          const img = entry.target;
          if (img.dataset.src) {
            img.src = img.dataset.src;
            img.removeAttribute('data-src');
          }
          imageObserver.unobserve(img);
        }
      });
    });

    document.querySelectorAll('img[data-src]').forEach(img => {
      imageObserver.observe(img);
    });
  }
});

/* ========= FAQ TOGGLE ========= */
function toggleFaq(element) {
  const faqItem = element.closest('.faq-item');
  const isActive = faqItem.classList.contains('active');
  
  // Close all FAQs
  document.querySelectorAll('.faq-item').forEach(item => {
    item.classList.remove('active');
  });
  
  // Open clicked FAQ if it wasn't active
  if (!isActive) {
    faqItem.classList.add('active');
  }
}
</script>
@endpush
