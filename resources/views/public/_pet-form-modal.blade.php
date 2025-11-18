
{{-- Modal para registrar mascota desde checkout - REDISEÑADO COMPLETAMENTE --}}
<style>
/* ===== VARIABLES Y RESET ===== */
:root {
    --primary: #2563eb;
    --primary-light: #3b82f6;
    --primary-dark: #1d4ed8;
    --success: #10b981;
    --success-light: #34d399;
    --danger: #ef4444;
    --warning: #f59e0b;
    --info: #06b6d4;
    --gray-50: #f9fafb;
    --gray-100: #f3f4f6;
    --gray-200: #e5e7eb;
    --gray-300: #d1d5db;
    --gray-400: #9ca3af;
    --gray-500: #6b7280;
    --gray-600: #4b5563;
    --gray-700: #374151;
    --gray-800: #1f2937;
    --gray-900: #111827;
}

/* ===== MODAL BASE ===== */
#registerPetModal .modal-dialog {
    max-width: 850px;
    margin: 1.5rem auto;
}

#registerPetModal .modal-content {
    border-radius: 20px;
    border: none;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.2);
    overflow: hidden;
}

/* ===== HEADER REDISEÑADO ===== */
#registerPetModal .modal-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    padding: 2rem 2.5rem;
    border: none;
    position: relative;
    overflow: hidden;
}

#registerPetModal .modal-header::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -10%;
    width: 300px;
    height: 300px;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 50%;
}

#registerPetModal .modal-title {
    font-size: 1.75rem;
    font-weight: 800;
    color: white;
    display: flex;
    align-items: center;
    gap: 1rem;
    position: relative;
    z-index: 1;
}

#registerPetModal .modal-title i {
    font-size: 2rem;
    animation: bounce 2s infinite;
}

@keyframes bounce {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-5px); }
}

#registerPetModal .modal-title .badge {
    background: rgba(255, 255, 255, 0.3);
    backdrop-filter: blur(10px);
    color: white;
    padding: 0.5rem 1rem;
    border-radius: 50px;
    font-size: 0.875rem;
    font-weight: 600;
    border: 2px solid rgba(255, 255, 255, 0.3);
}

#registerPetModal .btn-close {
    background: rgba(255, 255, 255, 0.2);
    border-radius: 50%;
    width: 40px;
    height: 40px;
    opacity: 1;
    transition: all 0.3s ease;
    position: relative;
    z-index: 1;
}

#registerPetModal .btn-close:hover {
    background: rgba(255, 255, 255, 0.3);
    transform: rotate(90deg);
}

/* ===== BODY ===== */
#registerPetModal .modal-body {
    padding: 2.5rem;
    background: white;
    max-height: calc(100vh - 300px);
    overflow-y: auto;
}

/* ===== ALERT INFO ===== */
.pet-alert {
    background: linear-gradient(135deg, #e0f2fe 0%, #bae6fd 100%);
    border-left: 4px solid #0284c7;
    border-radius: 12px;
    padding: 1.25rem 1.5rem;
    display: flex;
    align-items: center;
    gap: 1.25rem;
    margin-bottom: 2rem;
}

.pet-alert-icon {
    width: 48px;
    height: 48px;
    background: white;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #0284c7;
    font-size: 1.5rem;
    flex-shrink: 0;
    box-shadow: 0 2px 8px rgba(2, 132, 199, 0.2);
}

.pet-alert-text {
    color: #075985;
    font-size: 0.9375rem;
    line-height: 1.6;
}

/* ===== SECTION CARD NUEVA ===== */
.pet-section {
    background: white;
    border: 1px solid var(--gray-200);
    border-radius: 16px;
    padding: 2rem;
    margin-bottom: 2rem;
    transition: all 0.3s ease;
}

.pet-section:hover {
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    border-color: var(--gray-300);
}

.pet-section-header {
    display: flex;
    align-items: center;
    gap: 1.25rem;
    margin-bottom: 2rem;
    padding-bottom: 1.25rem;
    border-bottom: 2px solid var(--gray-100);
}

.pet-section-icon {
    width: 56px;
    height: 56px;
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.75rem;
    flex-shrink: 0;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.pet-section-icon.blue {
    background: linear-gradient(135deg, #60a5fa 0%, #3b82f6 100%);
    color: white;
}

.pet-section-icon.green {
    background: linear-gradient(135deg, #34d399 0%, #10b981 100%);
    color: white;
}

.pet-section-icon.cyan {
    background: linear-gradient(135deg, #22d3ee 0%, #06b6d4 100%);
    color: white;
}

.pet-section-icon.purple {
    background: linear-gradient(135deg, #a78bfa 0%, #8b5cf6 100%);
    color: white;
}

.pet-section-icon.amber {
    background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);
    color: white;
}

.pet-section-title {
    flex: 1;
}

.pet-section-title h3 {
    font-size: 1.375rem;
    font-weight: 700;
    color: var(--gray-900);
    margin: 0 0 0.375rem 0;
}

.pet-section-title p {
    font-size: 0.9375rem;
    color: var(--gray-500);
    margin: 0;
}

/* ===== FORM INPUTS MODERNOS ===== */
.pet-form-group {
    margin-bottom: 1.5rem;
}

.pet-label {
    display: block;
    font-weight: 600;
    color: var(--gray-700);
    margin-bottom: 0.625rem;
    font-size: 0.9375rem;
}

.pet-label .required {
    color: var(--danger);
    margin-left: 0.25rem;
}

.pet-input,
.pet-select,
.pet-textarea {
    width: 100%;
    padding: 0.875rem 1rem;
    border: 2px solid var(--gray-200);
    border-radius: 10px;
    font-size: 1rem;
    color: var(--gray-900);
    background: white;
    transition: all 0.2s ease;
}

.pet-input:focus,
.pet-select:focus,
.pet-textarea:focus {
    outline: none;
    border-color: var(--primary);
    box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1);
}

.pet-input::placeholder,
.pet-textarea::placeholder {
    color: var(--gray-400);
}

.pet-textarea {
    resize: vertical;
    min-height: 120px;
    font-family: inherit;
}

/* Input con icono */
.pet-input-icon {
    position: relative;
}

.pet-input-icon i {
    position: absolute;
    left: 1rem;
    top: 50%;
    transform: translateY(-50%);
    color: var(--gray-400);
    font-size: 1.125rem;
}

.pet-input-icon .pet-input,
.pet-input-icon .pet-select {
    padding-left: 3rem;
}

/* ===== RADIO BUTTONS MODERNOS (para sexo) ===== */
.pet-radio-group {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1rem;
}

.pet-radio-card {
    position: relative;
}

.pet-radio-card input[type="radio"] {
    position: absolute;
    opacity: 0;
    width: 100%;
    height: 100%;
    cursor: pointer;
}

.pet-radio-label {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 1.25rem 1rem;
    border: 2px solid var(--gray-200);
    border-radius: 12px;
    background: white;
    cursor: pointer;
    transition: all 0.3s ease;
    min-height: 100px;
}

.pet-radio-label:hover {
    border-color: var(--primary-light);
    background: var(--gray-50);
    transform: translateY(-2px);
}

.pet-radio-card input[type="radio"]:checked + .pet-radio-label {
    border-color: var(--primary);
    background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
    box-shadow: 0 4px 12px rgba(37, 99, 235, 0.2);
}

.pet-radio-label i {
    font-size: 2rem;
    color: var(--gray-400);
    margin-bottom: 0.5rem;
    transition: all 0.3s ease;
}

.pet-radio-card input[type="radio"]:checked + .pet-radio-label i {
    color: var(--primary);
    transform: scale(1.1);
}

.pet-radio-label span {
    font-weight: 600;
    color: var(--gray-700);
    font-size: 0.9375rem;
}

.pet-radio-card input[type="radio"]:checked + .pet-radio-label span {
    color: var(--primary-dark);
}

/* ===== CHECKBOXES MODERNOS (reemplazo de toggles) ===== */
.pet-checkbox-card {
    background: white;
    border: 2px solid var(--gray-200);
    border-radius: 12px;
    padding: 1.25rem 1.5rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
    transition: all 0.3s ease;
    cursor: pointer;
    gap: 1rem;
    user-select: none;
}

.pet-checkbox-card:hover {
    border-color: var(--primary-light);
    background: var(--gray-50);
    transform: translateY(-1px);
}

.pet-checkbox-card:active {
    transform: scale(0.99);
}

.pet-checkbox-card.checked {
    border-color: var(--success);
    background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
}

.pet-checkbox-label {
    display: flex;
    align-items: center;
    gap: 1rem;
    cursor: pointer;
    flex: 1;
    margin: 0;
    pointer-events: none;
}

.pet-checkbox-label i {
    font-size: 1.5rem;
    color: var(--gray-400);
    transition: all 0.3s ease;
}

.pet-checkbox-card.checked .pet-checkbox-label i {
    color: var(--success);
}

.pet-checkbox-label span {
    font-weight: 600;
    color: var(--gray-700);
    font-size: 1rem;
}

.pet-checkbox-card.checked .pet-checkbox-label span {
    color: var(--success);
}

.pet-checkbox-input {
    position: relative;
    width: 56px;
    height: 32px;
    flex-shrink: 0;
    pointer-events: none;
}

.pet-checkbox-input input[type="checkbox"] {
    position: absolute;
    opacity: 0;
    width: 100%;
    height: 100%;
    cursor: pointer;
    pointer-events: none;
}

.pet-checkbox-box {
    width: 56px;
    height: 32px;
    border: 3px solid var(--gray-300);
    border-radius: 20px;
    background: white;
    position: relative;
    transition: all 0.3s ease;
}

.pet-checkbox-box::after {
    content: '';
    position: absolute;
    top: 50%;
    left: 4px;
    transform: translateY(-50%);
    width: 20px;
    height: 20px;
    border-radius: 50%;
    background: var(--gray-400);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
}

.pet-checkbox-input input[type="checkbox"]:checked + .pet-checkbox-box {
    background: var(--success);
    border-color: var(--success);
}

.pet-checkbox-input input[type="checkbox"]:checked + .pet-checkbox-box::after {
    left: calc(100% - 24px);
    background: white;
}

/* ===== PHOTO UPLOAD ZONES ===== */
.pet-photo-main {
    border: 3px dashed var(--gray-300);
    border-radius: 16px;
    background: var(--gray-50);
    min-height: 320px;
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
    transition: all 0.3s ease;
    margin-bottom: 1.25rem;
    overflow: hidden;
}

.pet-photo-main:hover {
    border-color: var(--primary);
    background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
}

.pet-photo-main.has-photo {
    border-style: solid;
    border-color: var(--success);
    background: white;
}

.pet-photo-main img {
    width: 100%;
    height: 100%;
    object-fit: contain;
    max-height: 450px;
}

.pet-photo-placeholder {
    text-align: center;
    padding: 3rem 2rem;
    color: var(--gray-500);
}

.pet-photo-placeholder-icon {
    width: 80px;
    height: 80px;
    margin: 0 auto 1.5rem;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 2.5rem;
    box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);
}

.pet-photo-placeholder h4 {
    font-size: 1.25rem;
    font-weight: 700;
    color: var(--gray-900);
    margin: 0 0 0.5rem 0;
}

.pet-photo-placeholder p {
    font-size: 1rem;
    color: var(--gray-500);
    margin: 0;
}

.pet-photo-actions {
    display: flex;
    gap: 1rem;
    justify-content: center;
    flex-wrap: wrap;
}

/* Photo grid adicionales */
.pet-photo-upload-zone {
    border: 3px dashed var(--gray-300);
    border-radius: 16px;
    background: white;
    padding: 2.5rem 2rem;
    text-align: center;
    cursor: pointer;
    transition: all 0.3s ease;
    min-height: 180px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
}

.pet-photo-upload-zone:hover {
    border-color: var(--primary);
    background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
    transform: translateY(-2px);
}

.pet-photo-upload-zone.has-photos {
    border-style: solid;
    border-color: var(--success);
    background: linear-gradient(135deg, #ecfdf5 0%, #d1fae5 100%);
}

.pet-photo-upload-icon {
    width: 72px;
    height: 72px;
    margin: 0 auto 1.25rem;
    background: linear-gradient(135deg, #60a5fa 0%, #3b82f6 100%);
    border-radius: 18px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 2.25rem;
    box-shadow: 0 6px 16px rgba(59, 130, 246, 0.3);
}

.pet-photo-upload-zone.has-photos .pet-photo-upload-icon {
    background: linear-gradient(135deg, #34d399 0%, #10b981 100%);
}

.pet-photo-upload-text {
    font-size: 1.125rem;
    font-weight: 700;
    color: var(--gray-900);
    margin-bottom: 0.5rem;
}

.pet-photo-upload-hint {
    font-size: 0.9375rem;
    color: var(--gray-500);
}

/* ===== Upload Area ===== */
.upload-area {
    border: 3px dashed var(--gray-300);
    border-radius: 20px;
    padding: 2rem 1.5rem;
    text-align: center;
    background: var(--gray-50);
    transition: all 0.3s ease;
    cursor: pointer;
    margin-bottom: 1.25rem;
}

.upload-area:hover {
    border-color: var(--primary);
    background: linear-gradient(135deg, rgba(102, 126, 234, 0.02), rgba(118, 75, 162, 0.02));
}

.upload-label {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 1rem;
    cursor: pointer;
    margin: 0;
}

.upload-icon {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--primary) 0%, #764ba2 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.75rem;
    box-shadow: 0 8px 16px rgba(102, 126, 234, 0.2);
}

.upload-text {
    display: flex;
    flex-direction: column;
    gap: 0.375rem;
}

.upload-title {
    font-weight: 600;
    color: var(--gray-800);
    font-size: 0.9375rem;
}

.upload-subtitle {
    color: var(--gray-500);
    font-size: 0.8125rem;
}

/* ===== Photos Grid (SIMPLE - como /portal/pets/create) ===== */
.photos-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
    gap: 1rem;
    margin-bottom: 1rem;
}

.photos-grid .ph {
    position: relative;
    aspect-ratio: 1;
    border-radius: 12px;
    overflow: hidden;
    background: var(--gray-100);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    transition: all 0.3s ease;
}

.photos-grid .ph:hover {
    transform: scale(1.05);
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.12);
}

.photos-grid .ph img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.photos-grid .ph .ph-remove {
    position: absolute;
    top: 0.5rem;
    right: 0.5rem;
    width: 32px;
    height: 32px;
    border-radius: 50%;
    border: none;
    background: rgba(0, 0, 0, 0.7);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.2s ease;
    backdrop-filter: blur(4px);
    font-size: 0.875rem;
}

.photos-grid .ph .ph-remove:hover {
    background: #ef4444;
    transform: scale(1.1);
}

.btn-clear-photos {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    padding: 0.75rem 1.25rem;
    border: 2px solid var(--gray-200);
    border-radius: 12px;
    background: white;
    color: #ef4444;
    font-weight: 600;
    font-size: 0.875rem;
    cursor: pointer;
    transition: all 0.3s ease;
    width: 100%;
    margin-top: 0.5rem;
}

.btn-clear-photos:hover {
    background: #fef2f2;
    border-color: #ef4444;
    transform: translateY(-2px);
}

/* ===== ZONE PREVIEW ===== */
.pet-zone-preview {
    background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
    border: 2px solid #bae6fd;
    border-radius: 12px;
    padding: 1rem 1.25rem;
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-top: 1rem;
}

.pet-zone-preview i {
    color: #0284c7;
    font-size: 1.25rem;
}

.pet-zone-preview-text {
    flex: 1;
}

.pet-zone-preview-label {
    font-size: 0.8125rem;
    color: #075985;
    font-weight: 600;
    margin-bottom: 0.25rem;
}

.pet-zone-preview-value {
    font-size: 1rem;
    color: #0c4a6e;
    font-weight: 700;
}

/* ===== BUTTONS ===== */
.pet-btn {
    padding: 1rem 2rem;
    border: none;
    border-radius: 12px;
    font-weight: 600;
    font-size: 1rem;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.625rem;
    cursor: pointer;
    transition: all 0.3s ease;
    min-width: 140px;
}

.pet-btn:active {
    transform: scale(0.97);
}

.pet-btn-primary {
    background: linear-gradient(135deg, #60a5fa 0%, #3b82f6 100%);
    color: white;
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
}

.pet-btn-primary:hover {
    box-shadow: 0 6px 20px rgba(59, 130, 246, 0.4);
    transform: translateY(-2px);
}

.pet-btn-success {
    background: linear-gradient(135deg, #34d399 0%, #10b981 100%);
    color: white;
    box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
}

.pet-btn-success:hover {
    box-shadow: 0 6px 20px rgba(16, 185, 129, 0.4);
    transform: translateY(-2px);
}

.pet-btn-danger {
    background: white;
    color: var(--danger);
    border: 2px solid var(--danger);
}

.pet-btn-danger:hover {
    background: var(--danger);
    color: white;
}

.pet-btn-secondary {
    background: var(--gray-100);
    color: var(--gray-700);
    border: 2px solid var(--gray-300);
}

.pet-btn-secondary:hover {
    background: var(--gray-200);
    border-color: var(--gray-400);
}

/* ===== FOOTER ===== */
#registerPetModal .modal-footer {
    padding: 1.75rem 2.5rem;
    background: var(--gray-50);
    border-top: 2px solid var(--gray-200);
    display: flex;
    gap: 1rem;
    justify-content: flex-end;
    position: sticky;
    bottom: 0;
    z-index: 100;
    box-shadow: 0 -4px 12px rgba(0, 0, 0, 0.05);
}

/* ===== HINTS ===== */
.pet-hint {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin-top: 1rem;
    padding: 0.75rem 1rem;
    background: var(--gray-50);
    border-radius: 8px;
    border-left: 3px solid var(--info);
}

.pet-hint i {
    color: var(--info);
    font-size: 1rem;
}

.pet-hint span {
    font-size: 0.875rem;
    color: var(--gray-600);
}

/* ===== SCROLLBAR ===== */
#registerPetModal .modal-body::-webkit-scrollbar {
    width: 10px;
}

#registerPetModal .modal-body::-webkit-scrollbar-track {
    background: var(--gray-100);
    border-radius: 10px;
}

#registerPetModal .modal-body::-webkit-scrollbar-thumb {
    background: var(--gray-300);
    border-radius: 10px;
}

#registerPetModal .modal-body::-webkit-scrollbar-thumb:hover {
    background: var(--gray-400);
}

/* ===== RESPONSIVE ===== */
@media (max-width: 768px) {
    #registerPetModal .modal-dialog {
        margin: 0.5rem;
        max-width: calc(100% - 1rem);
    }

    #registerPetModal .modal-header {
        padding: 1rem 1.25rem;
    }

    #registerPetModal .modal-title {
        font-size: 1.125rem;
        flex-wrap: wrap;
    }

    #registerPetModal .modal-title i {
        font-size: 1.375rem;
    }

    #registerPetModal .modal-title .badge {
        font-size: 0.75rem;
        padding: 0.375rem 0.75rem;
    }

    #registerPetModal .modal-body {
        padding: 1.25rem;
        max-height: calc(100vh - 240px);
    }

    #registerPetModal .modal-footer {
        padding: 1rem 1.25rem;
        flex-direction: row;
        gap: 0.75rem;
        position: sticky;
        bottom: 0;
    }

    .pet-alert {
        padding: 1rem;
        gap: 0.875rem;
        margin-bottom: 1.25rem;
    }

    .pet-alert-icon {
        width: 40px;
        height: 40px;
        font-size: 1.25rem;
    }

    .pet-alert-text {
        font-size: 0.875rem;
    }

    .pet-section {
        padding: 1.25rem;
        margin-bottom: 1.25rem;
    }

    .pet-section-header {
        margin-bottom: 1.25rem;
        padding-bottom: 1rem;
    }

    .pet-section-icon {
        width: 44px;
        height: 44px;
        font-size: 1.375rem;
    }

    .pet-section-title h3 {
        font-size: 1rem;
    }

    .pet-section-title p {
        font-size: 0.875rem;
    }

    .pet-form-group {
        margin-bottom: 1.25rem;
    }

    .pet-label {
        font-size: 0.875rem;
        margin-bottom: 0.5rem;
    }

    .pet-input,
    .pet-select,
    .pet-textarea {
        padding: 0.75rem 0.875rem;
        font-size: 0.9375rem;
    }

    .pet-textarea {
        min-height: 100px;
    }

    .pet-radio-group {
        grid-template-columns: 1fr;
        gap: 0.625rem;
    }

    .pet-radio-label {
        padding: 1rem 0.875rem;
        min-height: 85px;
    }

    .pet-radio-label i {
        font-size: 1.625rem;
    }

    .pet-radio-label span {
        font-size: 0.875rem;
    }

    .pet-checkbox-card {
        padding: 0.875rem 1rem;
    }

    .pet-checkbox-label i {
        font-size: 1.25rem;
    }

    .pet-checkbox-label span {
        font-size: 0.875rem;
    }

    .pet-checkbox-input {
        width: 50px;
        height: 28px;
    }

    .pet-checkbox-box {
        width: 50px;
        height: 28px;
    }

    .pet-checkbox-box::after {
        width: 18px;
        height: 18px;
    }

    .pet-photo-main {
        min-height: 220px;
    }

    .pet-photo-placeholder {
        padding: 1.75rem 1.25rem;
    }

    .pet-photo-placeholder-icon {
        width: 56px;
        height: 56px;
        font-size: 1.75rem;
        margin-bottom: 1rem;
    }

    .pet-photo-placeholder h4 {
        font-size: 1.125rem;
    }

    .pet-photo-placeholder p {
        font-size: 0.9375rem;
    }

    .pet-photo-actions {
        flex-direction: column;
        gap: 0.75rem;
    }

    .pet-photo-actions .pet-btn {
        width: 100%;
    }

    .pet-photo-upload-zone {
        padding: 1.75rem 1.5rem;
        min-height: 160px;
    }

    .pet-photo-upload-icon {
        width: 60px;
        height: 60px;
        font-size: 1.875rem;
        margin-bottom: 1rem;
    }

    .pet-photo-upload-text {
        font-size: 1rem;
    }

    .pet-photo-upload-hint {
        font-size: 0.875rem;
    }

    .photos-grid {
        grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
        gap: 0.875rem;
    }

    .upload-area {
        padding: 1.5rem 1rem;
    }

    .upload-icon {
        width: 52px;
        height: 52px;
        font-size: 1.5rem;
    }

    .pet-btn {
        padding: 0.75rem 1.25rem;
        font-size: 0.875rem;
        min-width: auto;
    }

    .pet-hint {
        padding: 0.625rem 0.875rem;
        margin-top: 0.875rem;
    }

    .pet-hint span {
        font-size: 0.8125rem;
    }

    .pet-zone-preview {
        padding: 0.875rem 1rem;
    }

    .pet-zone-preview-label {
        font-size: 0.75rem;
    }

    .pet-zone-preview-value {
        font-size: 0.9375rem;
    }
}

@media (max-width: 576px) {
    #registerPetModal .modal-dialog {
        margin: 0.25rem;
        max-width: calc(100% - 0.5rem);
    }

    #registerPetModal .modal-header {
        padding: 0.875rem 1rem;
    }

    #registerPetModal .modal-title {
        font-size: 1rem;
        gap: 0.5rem;
    }

    #registerPetModal .modal-title i {
        font-size: 1.25rem;
    }

    #registerPetModal .modal-title .badge {
        font-size: 0.6875rem;
        padding: 0.3rem 0.625rem;
    }

    #registerPetModal .modal-body {
        padding: 1rem;
        max-height: calc(100vh - 200px);
    }

    #registerPetModal .modal-footer {
        flex-direction: column;
        padding: 0.875rem 1rem;
        gap: 0.625rem;
    }

    #registerPetModal .modal-footer .pet-btn {
        width: 100%;
        padding: 0.75rem 1rem;
        font-size: 0.875rem;
    }

    .pet-alert {
        padding: 0.875rem;
        margin-bottom: 1rem;
    }

    .pet-alert-icon {
        width: 36px;
        height: 36px;
        font-size: 1.125rem;
    }

    .pet-alert-text {
        font-size: 0.8125rem;
        line-height: 1.5;
    }

    .pet-section {
        padding: 1rem;
        margin-bottom: 1rem;
    }

    .pet-section-header {
        margin-bottom: 1rem;
        padding-bottom: 0.875rem;
        gap: 0.875rem;
    }

    .pet-section-icon {
        width: 40px;
        height: 40px;
        font-size: 1.25rem;
    }

    .pet-section-title h3 {
        font-size: 0.9375rem;
    }

    .pet-section-title p {
        font-size: 0.8125rem;
    }

    .pet-form-group {
        margin-bottom: 1rem;
    }

    .pet-label {
        font-size: 0.8125rem;
    }

    .pet-input,
    .pet-select,
    .pet-textarea {
        padding: 0.6875rem 0.75rem;
        font-size: 0.875rem;
    }

    .pet-textarea {
        min-height: 90px;
    }

    .pet-radio-label {
        padding: 0.875rem;
        min-height: 75px;
    }

    .pet-radio-label i {
        font-size: 1.5rem;
    }

    .pet-radio-label span {
        font-size: 0.8125rem;
    }

    .pet-checkbox-card {
        padding: 0.75rem 0.875rem;
    }

    .pet-checkbox-label span {
        font-size: 0.8125rem;
    }

    .pet-photo-main {
        min-height: 200px;
    }

    .pet-photo-placeholder {
        padding: 1.5rem 1rem;
    }

    .pet-photo-placeholder-icon {
        width: 52px;
        height: 52px;
        font-size: 1.625rem;
        margin-bottom: 0.875rem;
    }

    .pet-photo-placeholder h4 {
        font-size: 1rem;
    }

    .pet-photo-placeholder p {
        font-size: 0.875rem;
    }

    .pet-photo-actions {
        flex-direction: column;
        width: 100%;
    }

    .pet-photo-actions .pet-btn {
        width: 100%;
    }

    .pet-photo-upload-zone {
        padding: 1.5rem 1.25rem;
        min-height: 140px;
    }

    .pet-photo-upload-icon {
        width: 54px;
        height: 54px;
        font-size: 1.75rem;
    }

    .pet-photo-upload-text {
        font-size: 0.9375rem;
    }

    .pet-photo-upload-hint {
        font-size: 0.8125rem;
    }

    .photos-grid {
        grid-template-columns: repeat(auto-fill, minmax(90px, 1fr));
        gap: 0.75rem;
    }

    .upload-area {
        padding: 1.25rem 0.875rem;
    }

    .upload-icon {
        width: 48px;
        height: 48px;
        font-size: 1.25rem;
    }

    .upload-title {
        font-size: 0.875rem;
    }

    .upload-subtitle {
        font-size: 0.75rem;
    }

    .pet-btn {
        padding: 0.6875rem 1rem;
        font-size: 0.8125rem;
    }

    .pet-hint {
        padding: 0.5rem 0.75rem;
    }

    .pet-hint i {
        font-size: 0.875rem;
    }

    .pet-hint span {
        font-size: 0.75rem;
    }

    .pet-zone-preview {
        padding: 0.75rem 0.875rem;
    }

    .pet-zone-preview i {
        font-size: 1.125rem;
    }

    .pet-zone-preview-label {
        font-size: 0.6875rem;
    }

    .pet-zone-preview-value {
        font-size: 0.875rem;
    }
}
</style>

<div class="modal fade" id="registerPetModal" tabindex="-1" aria-labelledby="registerPetModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <!-- HEADER -->
            <div class="modal-header">
                <div style="width: 100%;">
                    <h5 class="modal-title" id="registerPetModalLabel" style="margin-bottom: 0;">
                        <i class="fa-solid fa-paw"></i>
                        <span>Registrar Mascota</span>
                        @php
                            $registeredCount = $order->pets ? $order->pets->count() : 0;
                            $currentPetNumber = $registeredCount + 1;
                            $totalPets = $order->pets_quantity;
                            $progressPercent = $totalPets > 0 ? ($registeredCount / $totalPets * 100) : 0;
                        @endphp
                        @if($totalPets > 1)
                            <span class="badge">{{ $currentPetNumber }} de {{ $totalPets }}</span>
                        @endif
                    </h5>

                    @if($totalPets > 1)
                    <!-- Progress bar visual -->
                    <div style="margin-top: 16px; background: rgba(255, 255, 255, 0.2); border-radius: 50px; height: 8px; overflow: hidden;">
                        <div style="background: rgba(255, 255, 255, 0.9); height: 100%; width: {{ $progressPercent }}%; transition: width 0.5s ease; border-radius: 50px; box-shadow: 0 2px 8px rgba(255, 255, 255, 0.3);"></div>
                    </div>
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 8px;">
                        <span style="color: rgba(255, 255, 255, 0.9); font-size: 0.875rem; font-weight: 600;">
                            @if($registeredCount > 0)
                                <i class="fa-solid fa-check-circle"></i> {{ $registeredCount }} completada{{ $registeredCount !== 1 ? 's' : '' }}
                            @else
                                Comienza registrando tu primera mascota
                            @endif
                        </span>
                        <span style="color: rgba(255, 255, 255, 0.75); font-size: 0.875rem;">
                            {{ $totalPets - $registeredCount }} pendiente{{ ($totalPets - $registeredCount) !== 1 ? 's' : '' }}
                        </span>
                    </div>
                    @endif
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- FORM -->
            <form action="{{ route('checkout.store-pet', $order) }}" method="POST" enctype="multipart/form-data" id="checkout-pet-form">
                @csrf
                
                <div class="modal-body">
                    <!-- ALERT INFO -->
                    <div class="pet-alert">
                        <div class="pet-alert-icon">
                            <i class="fa-solid fa-lightbulb"></i>
                        </div>
                        <div class="pet-alert-text">
                            @if($totalPets > 1)
                                <strong>Estás registrando tu mascota {{ $currentPetNumber }} de {{ $totalPets }}.</strong><br>
                                Completa este formulario y podrás continuar con {{ $totalPets > $currentPetNumber ? 'las siguientes' : 'la siguiente' }}.
                                Los datos se guardarán y enlazarán a tu cuenta tras verificar tu pago.
                            @else
                                <strong>¡Excelente!</strong> Completa la información de tu mascota.<br>
                                Los datos se guardarán y enlazarán a tu cuenta una vez que verifiquemos tu pago.
                            @endif
                        </div>
                    </div>

                    <!-- DATOS BÁSICOS -->
                    <div class="pet-section">
                        <div class="pet-section-header">
                            <div class="pet-section-icon blue">
                                <i class="fa-solid fa-paw"></i>
                            </div>
                            <div class="pet-section-title">
                                <h3>Datos básicos</h3>
                                <p>Información principal de tu mascota</p>
                            </div>
                        </div>

                        <div class="row g-3">
                            <div class="col-12 col-md-6">
                                <div class="pet-form-group">
                                    <label class="pet-label">
                                        Nombre<span class="required">*</span>
                                    </label>
                                    <input type="text" name="name" class="pet-input" required placeholder="Ej: Max, Luna, Rocky">
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="pet-form-group">
                                    <label class="pet-label">Raza</label>
                                    <input type="text" name="breed" class="pet-input" placeholder="Ej: Labrador, Poodle, Mestizo">
                                </div>
                            </div>

                            <div class="col-12">
                                <label class="pet-label">Sexo</label>
                                <div class="pet-radio-group">
                                    <div class="pet-radio-card">
                                        <input type="radio" id="sex_m" name="sex" value="male" checked>
                                        <label for="sex_m" class="pet-radio-label">
                                            <i class="fa-solid fa-mars"></i>
                                            <span>Macho</span>
                                        </label>
                                    </div>
                                    <div class="pet-radio-card">
                                        <input type="radio" id="sex_f" name="sex" value="female">
                                        <label for="sex_f" class="pet-radio-label">
                                            <i class="fa-solid fa-venus"></i>
                                            <span>Hembra</span>
                                        </label>
                                    </div>
                                    <div class="pet-radio-card">
                                        <input type="radio" id="sex_u" name="sex" value="unknown">
                                        <label for="sex_u" class="pet-radio-label">
                                            <i class="fa-solid fa-circle-question"></i>
                                            <span>Desconocido</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- SALUD -->
                    <div class="pet-section">
                        <div class="pet-section-header">
                            <div class="pet-section-icon green">
                                <i class="fa-solid fa-heartbeat"></i>
                            </div>
                            <div class="pet-section-title">
                                <h3>Información de salud</h3>
                                <p>Vacunas, esterilización y edad</p>
                            </div>
                        </div>

                        <div class="row g-3">
                            <div class="col-12 col-md-6">
                                <input type="hidden" name="is_neutered" value="0">
                                <div class="pet-checkbox-card" id="neutered-card">
                                    <label class="pet-checkbox-label" for="is_neutered">
                                        <i class="fa-solid fa-scissors"></i>
                                        <span>Esterilizado/a</span>
                                    </label>
                                    <div class="pet-checkbox-input">
                                        <input id="is_neutered" type="checkbox" name="is_neutered" value="1">
                                        <div class="pet-checkbox-box"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 col-md-6">
                                <input type="hidden" name="rabies_vaccine" value="0">
                                <div class="pet-checkbox-card" id="rabies-card">
                                    <label class="pet-checkbox-label" for="rabies_vaccine">
                                        <i class="fa-solid fa-syringe"></i>
                                        <span>Vacuna antirrábica</span>
                                    </label>
                                    <div class="pet-checkbox-input">
                                        <input id="rabies_vaccine" type="checkbox" name="rabies_vaccine" value="1">
                                        <div class="pet-checkbox-box"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="pet-form-group">
                                    <label class="pet-label">Edad (años)</label>
                                    <div class="pet-input-icon">
                                        <i class="fa-solid fa-cake-candles"></i>
                                        <input type="number" name="age" min="0" max="50" class="pet-input" placeholder="Ej: 3">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- UBICACIÓN -->
                    <div class="pet-section">
                        <div class="pet-section-header">
                            <div class="pet-section-icon cyan">
                                <i class="fa-solid fa-map-location-dot"></i>
                            </div>
                            <div class="pet-section-title">
                                <h3>Ubicación</h3>
                                <p>Provincia, cantón y distrito</p>
                            </div>
                        </div>

                        <div class="row g-3" id="cr-geo">
                            <div class="col-12">
                                <div class="pet-form-group">
                                    <label class="pet-label">Provincia</label>
                                    <div class="pet-input-icon">
                                        <i class="fa-solid fa-map"></i>
                                        <select id="cr-province" class="pet-select" disabled>
                                            <option value="">Seleccione una provincia</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="pet-form-group">
                                    <label class="pet-label">Cantón</label>
                                    <div class="pet-input-icon">
                                        <i class="fa-solid fa-map-pin"></i>
                                        <select id="cr-canton" class="pet-select" disabled>
                                            <option value="">Seleccione un cantón</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="pet-form-group">
                                    <label class="pet-label">Distrito</label>
                                    <div class="pet-input-icon">
                                        <i class="fa-solid fa-location-dot"></i>
                                        <select id="cr-district" class="pet-select" disabled>
                                            <option value="">Seleccione un distrito</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <input type="hidden" name="zone" id="zone" value="">
                            
                            <div class="col-12">
                                <div class="pet-zone-preview">
                                    <i class="fa-solid fa-location-dot"></i>
                                    <div class="pet-zone-preview-text">
                                        <div class="pet-zone-preview-label">Ubicación seleccionada:</div>
                                        <div class="pet-zone-preview-value" id="zone-preview">No seleccionada</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- OBSERVACIONES -->
                    <div class="pet-section">
                        <div class="pet-section-header">
                            <div class="pet-section-icon purple">
                                <i class="fa-solid fa-file-medical"></i>
                            </div>
                            <div class="pet-section-title">
                                <h3>Observaciones médicas</h3>
                                <p>Alergias, medicación, comportamiento</p>
                            </div>
                        </div>

                        <div class="pet-checkbox-card" id="no-medical-card" style="margin-bottom: 1.5rem;">
                            <label class="pet-checkbox-label" for="no-medical">
                                <i class="fa-solid fa-check-circle"></i>
                                <span>Sin observaciones médicas</span>
                            </label>
                            <div class="pet-checkbox-input">
                                <input id="no-medical" type="checkbox">
                                <div class="pet-checkbox-box"></div>
                            </div>
                        </div>

                        <textarea name="medical_conditions" id="medical_conditions" class="pet-textarea"
                            placeholder="Ej: Alérgica a pollo. Toma medicamento para artritis 2 veces al día. Es muy tranquila con niños."></textarea>
                    </div>

                    <!-- FOTO PRINCIPAL -->
                    <div class="pet-section">
                        <div class="pet-section-header">
                            <div class="pet-section-icon amber">
                                <i class="fa-solid fa-camera"></i>
                            </div>
                            <div class="pet-section-title">
                                <h3>Foto principal<span class="required">*</span></h3>
                                <p>La mejor foto de tu mascota</p>
                            </div>
                        </div>

                        <div class="pet-photo-main" id="photoDrop">
                            <img id="photoPreview" src="" alt="Vista previa" class="d-none">
                            <div class="pet-photo-placeholder" id="photoPlaceholder">
                                <div class="pet-photo-placeholder-icon">
                                    <i class="fa-solid fa-image"></i>
                                </div>
                                <h4>Arrastra tu foto aquí</h4>
                                <p>o haz clic en el botón de abajo</p>
                            </div>
                        </div>

                        <div class="pet-photo-actions">
                            <label for="photo" class="pet-btn pet-btn-primary">
                                <i class="fa-solid fa-upload"></i>
                                Seleccionar foto
                            </label>
                            <input id="photo" name="photo" type="file" accept="image/*" class="d-none" required>
                            <button type="button" id="btnClearPhoto" class="pet-btn pet-btn-danger">
                                <i class="fa-solid fa-trash"></i>
                                Quitar foto
                            </button>
                        </div>

                        <div class="pet-hint">
                            <i class="fa-solid fa-info-circle"></i>
                            <span>Formatos: JPG, PNG, HEIC • Máximo 100MB</span>
                        </div>
                    </div>

                    <!-- FOTOS ADICIONALES -->
                    <div class="pet-section">
                        <div class="pet-section-header">
                            <div class="pet-section-icon amber">
                                <i class="fa-solid fa-images"></i>
                            </div>
                            <div class="pet-section-title">
                                <h3>Fotos adicionales</h3>
                                <p>Hasta 3 fotos más (opcional)</p>
                            </div>
                        </div>

                        <div class="upload-area">
                            <input type="file" id="photos" name="photos[]" class="d-none" multiple accept="image/*">
                            <label for="photos" class="upload-label">
                                <div class="upload-icon">
                                    <i class="fa-solid fa-cloud-arrow-up"></i>
                                </div>
                                <div class="upload-text">
                                    <span class="upload-title">Haz clic para seleccionar fotos</span>
                                    <span class="upload-subtitle">JPG, PNG, HEIC · Máx. 100 MB por imagen · Hasta 3 fotos</span>
                                </div>
                            </label>
                        </div>

                        <div id="photosPreviewGrid" class="photos-grid d-none"></div>

                        <button type="button" id="btnClearPhotos" class="btn-clear-photos d-none">
                            <i class="fa-solid fa-trash"></i>
                            Eliminar todas las fotos
                        </button>

                        <div class="pet-hint">
                            <i class="fa-solid fa-info-circle"></i>
                            <span>Máximo 3 fotos adicionales • JPG, PNG, HEIC • Máximo 100MB cada una</span>
                        </div>
                    </div>
                </div>

                <!-- FOOTER -->
                <div class="modal-footer">
                    <button type="button" class="pet-btn pet-btn-secondary" data-bs-dismiss="modal">
                        <i class="fa-solid fa-times"></i>
                        Cancelar
                    </button>
                    <button type="submit" class="pet-btn pet-btn-success" id="submitPetForm">
                        <i class="fa-solid fa-check"></i>
                        @if($totalPets > 1)
                            @if($registeredCount >= $totalPets - 1)
                                Guardar última mascota
                            @else
                                Guardar y continuar ({{ $currentPetNumber }}/{{ $totalPets }})
                            @endif
                        @else
                            Guardar mascota
                        @endif
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// ===== Checkbox cards interactivity
(() => {
    // Esterilizado
    const neuteredCheckbox = document.getElementById('is_neutered');
    const neuteredCard = document.getElementById('neutered-card');
    if (neuteredCheckbox && neuteredCard) {
        neuteredCard.addEventListener('click', (e) => {
            e.preventDefault();
            neuteredCheckbox.checked = !neuteredCheckbox.checked;
            neuteredCard.classList.toggle('checked', neuteredCheckbox.checked);
        });
    }

    // Vacuna
    const rabiesCheckbox = document.getElementById('rabies_vaccine');
    const rabiesCard = document.getElementById('rabies-card');
    if (rabiesCheckbox && rabiesCard) {
        rabiesCard.addEventListener('click', (e) => {
            e.preventDefault();
            rabiesCheckbox.checked = !rabiesCheckbox.checked;
            rabiesCard.classList.toggle('checked', rabiesCheckbox.checked);
        });
    }

    // No medical
    const noMedicalCheckbox = document.getElementById('no-medical');
    const noMedicalCard = document.getElementById('no-medical-card');
    const medicalTextarea = document.getElementById('medical_conditions');
    
    if (noMedicalCheckbox && noMedicalCard && medicalTextarea) {
        noMedicalCard.addEventListener('click', (e) => {
            e.preventDefault();
            noMedicalCheckbox.checked = !noMedicalCheckbox.checked;
            noMedicalCard.classList.toggle('checked', noMedicalCheckbox.checked);
            
            if (noMedicalCheckbox.checked) {
                medicalTextarea.value = '';
                medicalTextarea.disabled = true;
                medicalTextarea.style.opacity = '0.5';
            } else {
                medicalTextarea.disabled = false;
                medicalTextarea.style.opacity = '1';
            }
        });
    }
})();

// ===== Cascada CR provincias/cantones/distritos
(() => {
    const API = 'https://ubicaciones.paginasweb.cr';
    const $prov = document.getElementById('cr-province');
    const $cant = document.getElementById('cr-canton');
    const $dist = document.getElementById('cr-district');
    const $zone = document.getElementById('zone');
    const $zonePreview = document.getElementById('zone-preview');

    if (!$prov || !$cant || !$dist || !$zone || !$zonePreview) return;

    async function getJSON(path) {
        const r = await fetch(`${API}${path}`);
        if (!r.ok) throw new Error('Error loading data');
        return await r.json();
    }

    function fillSelect($sel, map, placeholder) {
        $sel.innerHTML = `<option value="">${placeholder}</option>`;
        for (const [id, name] of Object.entries(map)) {
            const opt = document.createElement('option');
            opt.value = id;
            opt.textContent = name;
            $sel.appendChild(opt);
        }
    }

    function setZone() {
        const pName = $prov.options[$prov.selectedIndex]?.text || '';
        const cName = $cant.options[$cant.selectedIndex]?.text || '';
        const dName = $dist.options[$dist.selectedIndex]?.text || '';
        if (pName && cName && dName) {
            const z = `${dName}, ${cName}, ${pName}`;
            $zone.value = z;
            $zonePreview.textContent = z;
        } else {
            $zone.value = '';
            $zonePreview.textContent = 'No seleccionada';
        }
    }

    (async () => {
        try {
            const provincias = await getJSON('/provincias.json');
            fillSelect($prov, provincias, 'Seleccione una provincia');
            $prov.disabled = false;
        } catch (e) {
            console.error('Error loading provinces:', e);
        }
    })();

    $prov.addEventListener('change', async () => {
        $cant.disabled = true;
        $dist.disabled = true;
        $dist.innerHTML = `<option value="">Seleccione un distrito</option>`;
        setZone();
        if (!$prov.value) {
            $cant.innerHTML = `<option value="">Seleccione un cantón</option>`;
            return;
        }
        try {
            const cantones = await getJSON(`/provincia/${$prov.value}/cantones.json`);
            fillSelect($cant, cantones, 'Seleccione un cantón');
            $cant.disabled = false;
        } catch (e) {
            console.error('Error loading cantones:', e);
        }
    });

    $cant.addEventListener('change', async () => {
        $dist.disabled = true;
        $dist.innerHTML = `<option value="">Seleccione un distrito</option>`;
        setZone();
        if (!$prov.value || !$cant.value) return;
        try {
            const distritos = await getJSON(`/provincia/${$prov.value}/canton/${$cant.value}/distritos.json`);
            fillSelect($dist, distritos, 'Seleccione un distrito');
            $dist.disabled = false;
        } catch (e) {
            console.error('Error loading distritos:', e);
        }
    });

    $dist.addEventListener('change', setZone);
})();

// ===== Uploader principal (foto principal)
(function() {
    const input = document.getElementById('photo');
    const preview = document.getElementById('photoPreview');
    const drop = document.getElementById('photoDrop');
    const clear = document.getElementById('btnClearPhoto');
    const placeholder = document.getElementById('photoPlaceholder');
    const form = document.getElementById('checkout-pet-form');
    const submit = document.getElementById('submitPetForm');

    if (!input || !preview || !drop || !clear || !placeholder) return;

    function hasMain() {
        return !!preview.src && !preview.classList.contains('d-none');
    }

    function syncSubmit() {
        if (submit) {
            submit.disabled = !hasMain();
        }
    }

    function show(file) {
        if (!file) return;
        const url = URL.createObjectURL(file);
        preview.src = url;
        preview.classList.remove('d-none');
        placeholder.style.display = 'none';
        drop.classList.add('has-photo');
        syncSubmit();
    }

    input.addEventListener('change', e => {
        if (e.target.files && e.target.files[0]) {
            show(e.target.files[0]);
        }
    });

    ['dragenter', 'dragover'].forEach(ev => drop.addEventListener(ev, e => {
        e.preventDefault();
        e.stopPropagation();
    }));

    ['dragleave', 'drop'].forEach(ev => drop.addEventListener(ev, e => {
        e.preventDefault();
        e.stopPropagation();
    }));

    drop.addEventListener('drop', e => {
        const files = e.dataTransfer.files;
        if (files && files[0]) {
            input.files = files;
            show(files[0]);
        }
    });

    clear.addEventListener('click', () => {
        preview.src = '';
        preview.classList.add('d-none');
        placeholder.style.display = 'block';
        input.value = '';
        drop.classList.remove('has-photo');
        syncSubmit();
    });

    let isSubmitting = false;

    if (form) {
        form.addEventListener('submit', (e) => {
            // Prevenir doble envío
            if (isSubmitting) {
                e.preventDefault();
                return;
            }

            // Validar foto principal
            if (!hasMain()) {
                e.preventDefault();
                Swal.fire({
                    icon: 'warning',
                    title: 'Foto principal requerida',
                    text: 'Por favor selecciona una foto principal para tu mascota antes de continuar.',
                    confirmButtonText: 'Entendido',
                    confirmButtonColor: '#667eea'
                });
                return;
            }

            // Marcar como enviando y deshabilitar botón
            isSubmitting = true;
            if (submit) {
                submit.disabled = true;
                submit.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Guardando...';
            }
        });
    }

    syncSubmit();
})();

// ===== Previews de fotos múltiples + LÍMITE 3 (LÓGICA SIMPLE - como /portal/pets/create)
(function() {
    const MAX = 3;
    const input = document.getElementById('photos');
    const grid = document.getElementById('photosPreviewGrid');
    const btnClear = document.getElementById('btnClearPhotos');
    let filesBuffer = [];

    if (!input || !grid || !btnClear) return;

    function refreshGrid() {
        grid.innerHTML = '';
        if (filesBuffer.length === 0) {
            grid.classList.add('d-none');
            btnClear.classList.add('d-none');
            return;
        }
        grid.classList.remove('d-none');
        btnClear.classList.remove('d-none');

        filesBuffer.forEach((file, idx) => {
            const url = URL.createObjectURL(file);
            const cell = document.createElement('div');
            cell.className = 'ph';
            const img = document.createElement('img');
            img.src = url;
            img.alt = `Foto ${idx+1}`;
            const rm = document.createElement('button');
            rm.type = 'button';
            rm.className = 'ph-remove';
            rm.innerHTML = '<i class="fa-solid fa-xmark"></i>';
            rm.addEventListener('click', () => removeAt(idx));
            cell.appendChild(img);
            cell.appendChild(rm);
            grid.appendChild(cell);
        });
    }

    function applyBufferToInput() {
        const dt = new DataTransfer();
        filesBuffer.forEach(f => dt.items.add(f));
        input.files = dt.files;
    }

    function removeAt(i) {
        filesBuffer.splice(i, 1);
        applyBufferToInput();
        refreshGrid();
    }

    input.addEventListener('change', (e) => {
        const incoming = Array.from(e.target.files || []);
        const totalIfAdded = filesBuffer.length + incoming.length;
        if (totalIfAdded > MAX) {
            const allowed = Math.max(0, MAX - filesBuffer.length);
            Swal.fire({
                icon: 'warning',
                title: 'Máximo 3 fotos adicionales',
                text: `Puedes añadir ${allowed} foto(s) más.`,
                confirmButtonText: 'Entendido',
                confirmButtonColor: '#667eea'
            });
            if (allowed > 0) filesBuffer = filesBuffer.concat(incoming.slice(0, allowed));
        } else {
            filesBuffer = filesBuffer.concat(incoming);
        }
        applyBufferToInput();
        refreshGrid();
        input.value = '';
    });

    btnClear.addEventListener('click', () => {
        Swal.fire({
            title: '¿Eliminar todas las fotos?',
            text: "Esta acción no se puede deshacer",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                filesBuffer = [];
                input.value = '';
                refreshGrid();
            }
        });
    });
})();

// ===== Resetear formulario cuando se cierra el modal
(() => {
    const modal = document.getElementById('registerPetModal');
    if (!modal) return;

    modal.addEventListener('hidden.bs.modal', () => {
        const form = document.getElementById('checkout-pet-form');
        if (!form) return;
        
        form.reset();

        // Limpiar checkbox cards
        document.querySelectorAll('.pet-checkbox-card').forEach(card => {
            card.classList.remove('checked');
        });

        // Limpiar foto principal
        const photoPreview = document.getElementById('photoPreview');
        const photoPlaceholder = document.getElementById('photoPlaceholder');
        const photoDrop = document.getElementById('photoDrop');
        
        if (photoPreview) {
            photoPreview.src = '';
            photoPreview.classList.add('d-none');
        }
        if (photoPlaceholder) {
            photoPlaceholder.style.display = 'block';
        }
        if (photoDrop) {
            photoDrop.classList.remove('has-photo');
        }

        // Limpiar fotos múltiples
        const grid = document.getElementById('photosPreviewGrid');
        const btnClearPhotos = document.getElementById('btnClearPhotos');

        if (grid) {
            grid.innerHTML = '';
            grid.classList.add('d-none');
        }
        if (btnClearPhotos) {
            btnClearPhotos.classList.add('d-none');
        }

        // Resetear textarea
        const medicalTextarea = document.getElementById('medical_conditions');
        if (medicalTextarea) {
            medicalTextarea.disabled = false;
            medicalTextarea.style.opacity = '1';
        }

        // Resetear zone
        const zonePreview = document.getElementById('zone-preview');
        if (zonePreview) {
            zonePreview.textContent = 'No seleccionada';
        }

        const zone = document.getElementById('zone');
        if (zone) {
            zone.value = '';
        }

        // Botón submit
        const submitBtn = document.getElementById('submitPetForm');
        if (submitBtn) {
            submitBtn.disabled = true;
        }
    });
})();
</script>

@push('scripts')
<script>
(() => {
  const MAX = 100 * 1024 * 1024; // 100MB
  function tooBig(f){return f && f.size > MAX;}
  function checkFileInput(input){
    if(!input || !input.files) return true;
    for(const f of input.files){ if(tooBig(f)) return false; }
    return true;
  }
  const form = document.getElementById('checkout-pet-form');
  if(form){
    form.addEventListener('submit',(e)=>{
      const main = form.querySelector('input[name="photo"]');
      const extras = form.querySelector('input[name="photos[]"]');
      if(main && !checkFileInput(main)){
        e.preventDefault();
        alert('La foto principal supera el límite de 100 MB.');
      }
      if(extras && !checkFileInput(extras)){
        e.preventDefault();
        alert('Una o más fotos adicionales superan el límite de 100 MB.');
      }
    });
  }
})();
</script>
@endpush
