{{-- Modal para registrar mascota desde checkout - MISMO FORMULARIO que admin/pets/create --}}
<div class="modal fade" id="registerPetModal" tabindex="-1" aria-labelledby="registerPetModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="registerPetModalLabel">
                    <i class="fa-solid fa-paw me-2"></i>Registrar Mascota
                    @php
                        $currentPetNumber = ($order->pets ? $order->pets->count() : 0) + 1;
                    @endphp
                    @if($order->pets_quantity > 1)
                        <span class="badge bg-light text-primary ms-2">{{ $currentPetNumber }} de {{ $order->pets_quantity }}</span>
                    @endif
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('checkout.store-pet', $order) }}" method="POST" enctype="multipart/form-data" id="checkout-pet-form">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="fa-solid fa-info-circle me-2"></i>
                        Los datos de tu mascota serán guardados y se enlazarán a tu cuenta una vez que verifiquemos tu pago.
                    </div>

                    {{-- ======================= DATOS BÁSICOS ======================= --}}
                    <div class="section-card">
                        <div class="section-header">
                            <div class="section-icon bg-primary-subtle text-primary"><i class="fa-solid fa-paw"></i></div>
                            <div>
                                <h2 class="section-title">Datos básicos</h2>
                                <div class="section-sub">Nombre, raza y sexo de tu mascota.</div>
                            </div>
                        </div>

                        <div class="row g-3">
                            <div class="col-12 col-lg-6">
                                <label class="form-label">Nombre *</label>
                                <input type="text" name="name" class="form-control" required>
                            </div>
                            <div class="col-12 col-lg-6">
                                <label class="form-label">Raza</label>
                                <input type="text" name="breed" class="form-control" placeholder="Labrador, Poodle, etc.">
                            </div>

                            {{-- Sexo (segmented) --}}
                            <div class="col-12">
                                <label class="form-label d-block mb-2">Sexo</label>
                                <div class="segmented">
                                    <input type="radio" id="sex_m" name="sex" value="male" class="seg" checked>
                                    <label for="sex_m"><i class="fa-solid fa-mars me-1"></i> Macho</label>

                                    <input type="radio" id="sex_f" name="sex" value="female" class="seg">
                                    <label for="sex_f"><i class="fa-solid fa-venus me-1"></i> Hembra</label>

                                    <input type="radio" id="sex_u" name="sex" value="unknown" class="seg">
                                    <label for="sex_u"><i class="fa-solid fa-circle-question me-1"></i> Desconocido</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- ======================= SALUD ======================= --}}
                    <div class="section-card">
                        <div class="section-header">
                            <div class="section-icon bg-success-subtle text-success"><i class="fa-solid fa-stethoscope"></i></div>
                            <div>
                                <h2 class="section-title">Salud</h2>
                                <div class="section-sub">Esterilización, vacunas y edad.</div>
                            </div>
                        </div>

                        <div class="row g-3">
                            {{-- Esterilizado --}}
                            <div class="col-12 col-sm-6">
                                <div class="form-row">
                                    <label class="mb-0" for="is_neutered">Esterilizado</label>
                                    <input type="hidden" name="is_neutered" value="0">
                                    <label class="ft-switch" aria-label="Esterilizado">
                                        <input id="is_neutered" type="checkbox" name="is_neutered" value="1">
                                        <span class="track"><span class="thumb"></span></span>
                                    </label>
                                </div>
                            </div>

                            {{-- Vacuna antirrábica --}}
                            <div class="col-12 col-sm-6">
                                <div class="form-row">
                                    <label class="mb-0" for="rabies_vaccine">Vacuna antirrábica</label>
                                    <input type="hidden" name="rabies_vaccine" value="0">
                                    <label class="ft-switch" aria-label="Vacuna antirrábica">
                                        <input id="rabies_vaccine" type="checkbox" name="rabies_vaccine" value="1">
                                        <span class="track"><span class="thumb"></span></span>
                                    </label>
                                </div>
                            </div>

                            {{-- Edad --}}
                            <div class="col-12 col-sm-6">
                                <label class="form-label">Edad</label>
                                <div class="input-icon">
                                    <i class="fa-solid fa-cake-candles"></i>
                                    <input type="number" name="age" min="0" max="50" class="form-control" placeholder="0">
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- ======================= UBICACIÓN ======================= --}}
                    <div class="section-card">
                        <div class="section-header">
                            <div class="section-icon bg-info-subtle text-info"><i class="fa-solid fa-location-dot"></i></div>
                            <div>
                                <h2 class="section-title">Ubicación</h2>
                                <div class="section-sub">Se utiliza para mostrar la zona en el perfil público.</div>
                            </div>
                        </div>

                        <div class="row g-3" id="cr-geo" data-current-province="" data-current-canton="" data-current-district="">
                            <div class="col-12">
                                <label class="form-label">Provincia</label>
                                <div class="input-icon">
                                    <i class="fa-solid fa-map"></i>
                                    <select id="cr-province" class="form-select" aria-label="Provincia" disabled>
                                        <option value="">Provincia</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Cantón</label>
                                <div class="input-icon">
                                    <i class="fa-solid fa-map"></i>
                                    <select id="cr-canton" class="form-select" aria-label="Cantón" disabled>
                                        <option value="">Cantón</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Distrito</label>
                                <div class="input-icon">
                                    <i class="fa-solid fa-map"></i>
                                    <select id="cr-district" class="form-select" aria-label="Distrito" disabled>
                                        <option value="">Distrito</option>
                                    </select>
                                </div>
                            </div>

                            <input type="hidden" name="zone" id="zone" value="">
                            <div class="col-12">
                                <div class="d-flex align-items-center justify-content-between gap-3">
                                    <small class="text-muted">Se guardará como:</small>
                                    <code id="zone-preview">—</code>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- ======================= OBSERVACIONES ======================= --}}
                    <div class="section-card">
                        <div class="section-header">
                            <div class="section-icon bg-secondary-subtle text-secondary"><i class="fa-solid fa-notes-medical"></i></div>
                            <div>
                                <h2 class="section-title">Observaciones</h2>
                                <div class="section-sub">Alergias, medicación, comportamiento, etc.</div>
                            </div>
                        </div>

                        <div class="form-row mb-2">
                            <label class="mb-0" for="no-medical">Sin observaciones</label>
                            <label class="ft-switch" aria-label="Sin observaciones">
                                <input id="no-medical" type="checkbox">
                                <span class="track"><span class="thumb"></span></span>
                            </label>
                        </div>

                        <textarea name="medical_conditions" id="medical_conditions" rows="4" class="form-control"
                            placeholder="Ej: Alérgica a pollo. Toma medicamento 2 veces al día."></textarea>
                    </div>

                    {{-- ======================= FOTOS (múltiples) ======================= --}}
                    <div class="section-card">
                        <div class="section-header">
                            <div class="section-icon bg-warning-subtle text-warning"><i class="fa-solid fa-images"></i></div>
                            <div>
                                <h2 class="section-title">Fotos</h2>
                                <div class="section-sub">Puedes seleccionar varias (máx. 3 adicionales).</div>
                            </div>
                        </div>

                        <input type="file" id="photos" name="photos[]" class="form-control" multiple accept="image/*">
                        <div class="form-text">Formatos: JPG/PNG. Tamaño máx. 6 MB por imagen.</div>

                        <div id="photosPreviewGrid" class="mt-3 photos-grid d-none"></div>
                        <button type="button" id="btnClearPhotos" class="btn btn-outline-danger btn-sm mt-2 d-none">
                            <i class="fa-solid fa-xmark me-1"></i> Quitar todas
                        </button>
                    </div>

                    {{-- ======================= FOTO PRINCIPAL (legacy) ======================= --}}
                    <div class="section-card">
                        <div class="section-header">
                            <div class="section-icon bg-light text-body-tertiary"><i class="fa-regular fa-image"></i></div>
                            <div>
                                <h2 class="section-title">Foto principal (sistema antiguo)</h2>
                                <div class="section-sub">Opcional. El recorte es solo de vista previa.</div>
                            </div>
                        </div>

                        <div class="photo-uploader">
                            <div class="photo-uploader__preview" id="photoDrop">
                                <img id="photoPreview" src="" alt="Vista previa" class="d-none">
                                <div class="photo-uploader__overlay">Arrastra una imagen o haz clic en "Seleccionar imagen".</div>
                            </div>
                            <div class="photo-uploader__actions">
                                <label for="photo" class="btn btn-outline-primary">
                                    <i class="fa-solid fa-image me-1"></i> Seleccionar imagen
                                </label>
                                <input id="photo" name="photo" type="file" accept="image/*" class="d-none" required>
                                <button type="button" id="btnClearPhoto" class="btn btn-outline-danger">
                                    <i class="fa-solid fa-xmark me-1"></i> Quitar
                                </button>
                            </div>
                        </div>
                        <small class="text-muted d-block mt-2">
                            Formatos: JPG/PNG. Tamaño máx. 4 MB.
                        </small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fa-solid fa-times me-2"></i>Cancelar
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fa-solid fa-save me-2"></i>Guardar Mascota
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
