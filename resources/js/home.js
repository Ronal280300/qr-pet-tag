/**
 * HOME PAGE - PUBLIC VIEW
 * Optimized and extracted from home.blade.php
 */

// Esperar a que el DOM esté completamente cargado
document.addEventListener('DOMContentLoaded', () => {

  /* TÍTULOS ALTERNANTES */
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

  /* REVEAL ON SCROLL */
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

  /* COUNTER ANIMATION */
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

  /* SMOOTH SCROLL */
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

  /* PERFORMANCE: Lazy load images */
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

/* FAQ TOGGLE */
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
