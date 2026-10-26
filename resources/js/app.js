import AOS from 'aos';
import 'aos/dist/aos.css';
import Lenis from 'lenis';

// Initialize on client side
if (typeof window !== 'undefined') {
  // 1. Lenis Smooth Momentum Scrolling (lightweight ~3kb, butter-smooth Apple/Linear feel)
  let lenis = null;
  const prefersReduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  if (!prefersReduced) {
    lenis = new Lenis({
      duration: 1.15,
      easing: (t) => Math.min(1, 1.001 - Math.pow(2, -10 * t)),
      smoothWheel: true,
      wheelMultiplier: 0.95,
      touchMultiplier: 1.5,
    });

    function raf(time) {
      lenis.raf(time);
      requestAnimationFrame(raf);
    }
    requestAnimationFrame(raf);
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

  // 2. AOS (Animate On Scroll) Library (industry standard, lightweight ~14kb)
  const initAOS = () => {
    processRevealGroups();
    AOS.init({
      duration: 700,
      easing: 'ease-out-cubic',
      once: true,
      offset: 40,
      delay: 0,
      disable: prefersReduced,
    });
  };

  // Expose global helper for dynamic/Alpine content
  window.fdsRefreshAOS = () => {
    processRevealGroups();
    AOS.refresh();
  };

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initAOS);
  } else {
    initAOS();
  }

  window.addEventListener('load', () => {
    if (typeof AOS.refreshHard === 'function') {
      AOS.refreshHard();
    } else {
      AOS.refresh();
    }
  });
}
