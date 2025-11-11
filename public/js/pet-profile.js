/**
 * PET PROFILE - PUBLIC VIEW
 * Optimized and extracted from pet.blade.php
 */

// ===== SCROLL REVEAL ANIMATIONS =====
function initScrollReveal() {
  const reveals = document.querySelectorAll('.pet-reveal');

  if (reveals.length === 0) return;

  const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.classList.add('active');
      }
    });
  }, { threshold: 0.1 });

  reveals.forEach(reveal => observer.observe(reveal));
}

// ===== GALLERY LIGHTBOX =====
function initGalleryLightbox() {
  const galleryItems = document.querySelectorAll('.pet-gallery-item');
  const lightbox = document.getElementById('petLightbox');
  const lightboxImg = document.getElementById('petLightboxImg');
  const lightboxClose = document.getElementById('petLightboxClose');

  if (!lightbox || galleryItems.length === 0) return;

  galleryItems.forEach(item => {
    item.addEventListener('click', () => {
      const src = item.dataset.src || item.querySelector('img').src;
      lightboxImg.src = src;
      lightbox.classList.add('active');
      document.body.style.overflow = 'hidden';
    });
  });

  if (lightboxClose) {
    lightboxClose.addEventListener('click', closeLightbox);
  }

  lightbox.addEventListener('click', (e) => {
    if (e.target === lightbox) {
      closeLightbox();
    }
  });

  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && lightbox.classList.contains('active')) {
      closeLightbox();
    }
  });

  function closeLightbox() {
    lightbox.classList.remove('active');
    document.body.style.overflow = '';
  }
}

// ===== SHARE FUNCTIONALITY =====
function initShareButtons() {
  const shareButtons = document.querySelectorAll('.pet-share-btn');

  if (shareButtons.length === 0) return;

  const shareUrl = window.location.href;
  const shareTitle = document.querySelector('.pet-hero-name')?.textContent || 'Perfil de Mascota';

  shareButtons.forEach(btn => {
    btn.addEventListener('click', async (e) => {
      e.preventDefault();

      // Try Web Share API first
      if (navigator.share) {
        try {
          await navigator.share({ title: shareTitle, url: shareUrl });
          return;
        } catch (err) {
          // User cancelled or error occurred
          if (err.name !== 'AbortError') {
            console.error('Share failed:', err);
          }
        }
      }

      // Fallback to clipboard
      try {
        await copyToClipboard(shareUrl);
        showShareSuccess(btn);
      } catch (err) {
        // Final fallback to alert
        alert('Copia este enlace:\n' + shareUrl);
      }
    });
  });
}

async function copyToClipboard(text) {
  if (navigator.clipboard && window.isSecureContext) {
    return navigator.clipboard.writeText(text);
  }

  // Fallback for older browsers
  const textarea = document.createElement('textarea');
  textarea.value = text;
  textarea.style.position = 'fixed';
  textarea.style.left = '-999999px';
  textarea.style.opacity = '0';
  document.body.appendChild(textarea);
  textarea.select();

  return new Promise((resolve, reject) => {
    const success = document.execCommand('copy');
    textarea.remove();
    success ? resolve() : reject();
  });
}

function showShareSuccess(btn) {
  const originalHTML = btn.innerHTML;
  btn.innerHTML = '<i class="fa-solid fa-check"></i> ¡Copiado!';
  btn.style.pointerEvents = 'none';

  setTimeout(() => {
    btn.innerHTML = originalHTML;
    btn.style.pointerEvents = '';
  }, 2500);
}

// ===== AUTO-PING WITH GEOLOCATION =====
function initAutoPing(pingUrl, csrfToken) {
  if (!pingUrl) return;

  function sendPing(body) {
    fetch(pingUrl, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': csrfToken,
        'Accept': 'application/json'
      },
      body: JSON.stringify(body)
    }).catch(err => {
      console.error('Ping failed:', err);
    });
  }

  const isSecureContext = window.isSecureContext ||
                         location.protocol === 'https:' ||
                         ['localhost', '127.0.0.1'].includes(location.hostname);

  if (navigator.geolocation && isSecureContext) {
    let done = false;
    const fallbackTimer = setTimeout(() => {
      if (!done) {
        done = true;
        sendPing({ method: 'ip' });
      }
    }, 6000);

    navigator.geolocation.getCurrentPosition(
      (position) => {
        if (done) return;
        done = true;
        clearTimeout(fallbackTimer);

        const coords = position.coords || {};
        sendPing({
          method: 'gps',
          lat: coords.latitude,
          lng: coords.longitude,
          accuracy: Math.round(coords.accuracy || 0)
        });
      },
      (error) => {
        if (done) return;
        done = true;
        clearTimeout(fallbackTimer);
        sendPing({ method: 'ip' });
      },
      {
        enableHighAccuracy: true,
        timeout: 12000,
        maximumAge: 0
      }
    );
  } else {
    sendPing({ method: 'ip' });
  }
}

// ===== LAZY LOAD IMAGES =====
function initLazyLoading() {
  if ('loading' in HTMLImageElement.prototype) {
    // Native lazy loading supported
    const images = document.querySelectorAll('img[loading="lazy"]');
    images.forEach(img => {
      if (img.dataset.src) {
        img.src = img.dataset.src;
      }
    });
  } else {
    // Fallback for older browsers
    const images = document.querySelectorAll('img[data-src]');
    const imageObserver = new IntersectionObserver((entries, observer) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          const img = entry.target;
          img.src = img.dataset.src;
          img.removeAttribute('data-src');
          imageObserver.unobserve(img);
        }
      });
    });

    images.forEach(img => imageObserver.observe(img));
  }
}

// ===== INITIALIZE ALL =====
function initPetProfile(config = {}) {
  // Wait for DOM to be ready
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => init(config));
  } else {
    init(config);
  }
}

function init(config) {
  initScrollReveal();
  initGalleryLightbox();
  initShareButtons();
  initLazyLoading();

  // Initialize auto-ping if config provided
  if (config.pingUrl && config.csrfToken) {
    initAutoPing(config.pingUrl, config.csrfToken);
  }
}

// Export for use in blade templates
window.PetProfile = {
  init: initPetProfile
};
