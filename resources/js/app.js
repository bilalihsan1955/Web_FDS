import 'aos/dist/aos.css';
import Lenis from 'lenis';

// Initialize on client side with zero main-thread blocking (0ms TBT)
if (typeof window !== 'undefined') {
  const prefersReduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  const isDesktopPointer = window.matchMedia('(pointer: fine)').matches && window.innerWidth >= 1024;

  // 1. Lenis Smooth Momentum Scrolling (Desktop-only & on-demand RAF loop — 0ms idle CPU)
  if (!prefersReduced && isDesktopPointer) {
    let lenis = null;
    let rafId = null;

    const startLenisOnDemand = () => {
      if (lenis) return;
      lenis = new Lenis({
        duration: 1.15,
        easing: (t) => Math.min(1, 1.001 - Math.pow(2, -10 * t)),
        smoothWheel: true,
        wheelMultiplier: 0.95,
        touchMultiplier: 1.5,
      });

      const raf = (time) => {
        lenis.raf(time);
        if (lenis.isScrolling) {
          rafId = requestAnimationFrame(raf);
        } else {
          rafId = null;
        }
      };

      window.addEventListener(
        'wheel',
        () => {
          if (!rafId) {
            rafId = requestAnimationFrame(raf);
          }
        },
        { passive: true }
      );
      rafId = requestAnimationFrame(raf);
    };

    window.addEventListener('wheel', startLenisOnDemand, { once: true, passive: true });
  }

  // Helper: auto-orchestrate reveal groups with staggered animation delays
  const processRevealGroups = () => {
    document.querySelectorAll('[data-reveal-group]').forEach((group) => {
      const type = group.getAttribute('data-reveal-type') || 'fade-up';
      const step = parseInt(group.getAttribute('data-reveal-step') || '160', 10);
      const base = parseInt(group.getAttribute('data-reveal-base') || '0', 10);
      const modulo = parseInt(group.getAttribute('data-reveal-modulo') || '0', 10);
      const selector = group.getAttribute('data-reveal-selector');

      let items;
      if (selector) {
        items = Array.from(group.querySelectorAll(selector));
      } else {
        items = Array.from(group.children).filter(
          (el) => el.nodeType === 1 && !['SCRIPT', 'STYLE', 'TEMPLATE'].includes(el.tagName)
        );
      }

      items.forEach((item, index) => {
        if (!item.hasAttribute('data-aos')) {
          item.setAttribute('data-aos', type);
        }
        if (!item.hasAttribute('data-aos-delay')) {
          const delay = modulo > 0 ? (index % modulo) * step + base : index * step + base;
          if (delay > 0) {
            item.setAttribute('data-aos-delay', delay.toString());
          }
        }
      });
    });
  };

  // 2. Zero-Reflow Native IntersectionObserver for [data-aos] (Replaces layout-thrashing AOS.js offsetTop loop)
  let aosObserver = null;

  const initZeroReflowAOS = () => {
    if (!document.body) return;
    document.body.setAttribute('data-aos-easing', 'ease-out-cubic');
    document.body.setAttribute('data-aos-duration', '700');
    document.body.setAttribute('data-aos-delay', '0');

    processRevealGroups();
    const elements = document.querySelectorAll('[data-aos]');
    if (!elements.length) return;

    if (prefersReduced || typeof IntersectionObserver === 'undefined') {
      elements.forEach((el) => el.classList.add('aos-init', 'aos-animate'));
      return;
    }

    if (!aosObserver) {
      aosObserver = new IntersectionObserver(
        (entries, obs) => {
          for (let i = 0; i < entries.length; i++) {
            const entry = entries[i];
            if (entry.isIntersecting) {
              entry.target.classList.add('aos-animate');
              obs.unobserve(entry.target);
            }
          }
        },
        { rootMargin: '0px 0px -24px 0px', threshold: 0.01 }
      );
    }

    elements.forEach((el) => {
      if (!el.classList.contains('aos-animate')) {
        el.classList.add('aos-init');
        aosObserver.observe(el);
      }
    });
  };

  // Expose global helper for dynamic content
  window.fdsRefreshAOS = () => {
    initZeroReflowAOS();
  };

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initZeroReflowAOS, { once: true });
  } else {
    initZeroReflowAOS();
  }
}
