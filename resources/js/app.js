import 'aos/dist/aos.css';

// Initialize on client side with zero main-thread scroll blocking (0ms TBT)
if (typeof window !== 'undefined') {
  const prefersReduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  // Helper: auto-orchestrate reveal groups with staggered animation delays
  const processRevealGroups = () => {
    document.querySelectorAll('[data-reveal-group]').forEach((group) => {
      const type = group.getAttribute('data-reveal-type') || 'fade-up';
      const step = parseInt(group.getAttribute('data-reveal-step') || '160', 10);
      const base = parseInt(group.getAttribute('data-reveal-base') || '0', 10);
      const modulo = parseInt(group.getAttribute('data-reveal-modulo') || '0', 10);
      const selector = group.getAttribute('data-reveal-selector');
      const isCarousel = group.getAttribute('data-reveal-carousel') === 'true';

      let items;
      if (selector) {
        items = Array.from(group.querySelectorAll(selector));
      } else {
        items = Array.from(group.children).filter(
          (el) => el.nodeType === 1 && !['SCRIPT', 'STYLE', 'TEMPLATE'].includes(el.tagName)
        );
      }

      items.forEach((item, index) => {
        if (isCarousel) {
          item.setAttribute('data-reveal-carousel-child', 'true');
        }
        if (!item.hasAttribute('data-aos')) {
          item.setAttribute('data-aos', type);
        }
        if (!item.hasAttribute('data-aos-delay')) {
          let delay;
          if (isCarousel) {
            delay = Math.min(index, 3) * step + base;
          } else {
            delay = modulo > 0 ? (index % modulo) * step + base : index * step + base;
          }
          if (delay > 0) {
            item.setAttribute('data-aos-delay', delay.toString());
          }
        }
      });
    });
  };

  // Mark interactive card reveal transitions settled so subsequent hover lift is instant (0ms delay)
  const markCardSettled = (el) => {
    if (!el || !el.classList || !el.classList.contains('fds-service-card') || el.classList.contains('fds-aos-settled')) {
      return;
    }
    const delayMs = parseInt(el.getAttribute('data-aos-delay') || '0', 10);
    window.setTimeout(() => {
      el.classList.add('fds-aos-settled');
    }, Math.max(0, delayMs) + 460);
  };

  // 2. Zero-Reflow Native IntersectionObserver for [data-aos] (Replaces layout-thrashing AOS.js offsetTop loop)
  let aosObserver = null;
  let carouselObserver = null;

  const initZeroReflowAOS = () => {
    if (!document.body) return;
    document.body.setAttribute('data-aos-easing', 'ease-out-cubic');
    document.body.setAttribute('data-aos-duration', '500');
    document.body.setAttribute('data-aos-delay', '0');

    processRevealGroups();
    const elements = document.querySelectorAll('[data-aos]');
    if (!elements.length) return;

    if (prefersReduced || typeof IntersectionObserver === 'undefined') {
      elements.forEach((el) => {
        el.classList.add('aos-init', 'aos-animate');
        if (el.classList.contains('fds-service-card')) {
          el.classList.add('fds-aos-settled');
        }
      });
      return;
    }

    if (!aosObserver) {
      aosObserver = new IntersectionObserver(
        (entries, obs) => {
          for (let i = 0; i < entries.length; i++) {
            const entry = entries[i];
            if (!entry.isIntersecting) continue;
            entry.target.classList.add('aos-animate');
            markCardSettled(entry.target);
            obs.unobserve(entry.target);
          }
        },
        { rootMargin: '40px 0px -16px 0px', threshold: 0.01 }
      );
    }

    if (!carouselObserver) {
      carouselObserver = new IntersectionObserver(
        (entries, obs) => {
          for (let i = 0; i < entries.length; i++) {
            const entry = entries[i];
            if (!entry.isIntersecting) continue;
            const children = entry.target.querySelectorAll('[data-reveal-carousel-child="true"]');
            children.forEach((child) => {
              child.classList.add('aos-animate');
              markCardSettled(child);
            });
            entry.target.setAttribute('data-reveal-carousel-done', 'true');
            obs.unobserve(entry.target);
          }
        },
        { rootMargin: '120px 0px 80px 0px', threshold: 0.01 }
      );
    }

    elements.forEach((el) => {
      if (el.hasAttribute('data-reveal-carousel-child')) {
        if (!el.classList.contains('aos-animate')) {
          el.classList.add('aos-init');
        }
        return;
      }
      if (!el.classList.contains('aos-animate')) {
        el.classList.add('aos-init');
        aosObserver.observe(el);
      }
    });

    document.querySelectorAll('[data-reveal-carousel="true"]').forEach((track) => {
      if (track.getAttribute('data-reveal-carousel-done') === 'true') {
        track.querySelectorAll('[data-reveal-carousel-child="true"]').forEach((child) => {
          child.classList.add('aos-init', 'aos-animate');
          markCardSettled(child);
        });
        return;
      }
      if (!track.hasAttribute('data-reveal-carousel-observed')) {
        track.setAttribute('data-reveal-carousel-observed', 'true');
        carouselObserver.observe(track);
      }
    });
  };

  // Expose global helper for dynamic content
  window.fdsRefreshAOS = () => {
    initZeroReflowAOS();
  };

  // 3. First-Touch + Latest UTM & Click ID Attribution + Global Conversion Telemetry
  const FDS_ATTR_KEY = 'fds_attribution';
  const FDS_UTM_KEYS = ['utm_source', 'utm_medium', 'utm_campaign', 'utm_term', 'utm_content', 'gclid', 'fbclid'];

  const syncAttributionState = () => {
    let stored = {};
    try {
      const raw = window.sessionStorage.getItem(FDS_ATTR_KEY);
      if (raw) stored = JSON.parse(raw) || {};
    } catch (e) {}

    if (!stored.landing_page) stored.landing_page = window.location.href;
    if (!stored.referrer && document.referrer) stored.referrer = document.referrer;

    const params = new URLSearchParams(window.location.search || '');
    FDS_UTM_KEYS.forEach((k) => {
      const val = params.get(k);
      if (val !== null && val.trim() !== '') {
        stored[k] = val.trim().slice(0, 140);
      }
    });

    try {
      window.sessionStorage.setItem(FDS_ATTR_KEY, JSON.stringify(stored));
    } catch (e) {}

    return stored;
  };

  const initialAttribution = syncAttributionState();

  window.fdsPopulateAttribution = (form) => {
    if (!form) return;
    const state = syncAttributionState() || initialAttribution || {};
    const fields = {
      source_page: window.location.href,
      source_title: document.title || '',
      referrer: state.referrer || document.referrer || '',
      utm_source: state.utm_source || '',
      utm_medium: state.utm_medium || '',
      utm_campaign: state.utm_campaign || '',
      utm_term: state.utm_term || '',
      utm_content: state.utm_content || '',
      gclid: state.gclid || '',
      fbclid: state.fbclid || '',
    };

    Object.keys(fields).forEach((name) => {
      let input = form.querySelector(`input[name="${name}"]`);
      if (!input) {
        input = document.createElement('input');
        input.type = 'hidden';
        input.name = name;
        form.appendChild(input);
      }
      input.value = fields[name];
    });
  };

  window.fdsTrackEvent = (eventName, params = {}) => {
    if (!eventName) return;
    const payload = Object.assign({ page_path: window.location.pathname }, params || {});

    window.dataLayer = window.dataLayer || [];
    window.dataLayer.push(Object.assign({ event: eventName }, payload));

    if (typeof window.gtag === 'function') {
      window.gtag('event', eventName, payload);
      const cfg = window.fdsTrackingConfig || {};
      if (eventName === 'generate_lead' && cfg.gads_id && cfg.gads_label) {
        window.gtag('event', 'conversion', {
          send_to: `${cfg.gads_id}/${cfg.gads_label}`,
        });
      }
    }

    if (typeof window.fbq === 'function') {
      if (eventName === 'generate_lead') {
        window.fbq('track', 'Lead', payload);
      } else if (['click_whatsapp', 'click_phone', 'click_email'].includes(eventName)) {
        window.fbq('track', 'Contact', payload);
      } else if (eventName === 'download_brochure') {
        window.fbq('trackCustom', 'DownloadBrochure', payload);
      } else {
        window.fbq('trackCustom', eventName, payload);
      }
    }
  };

  document.addEventListener('click', (e) => {
    const anchor = e.target && e.target.closest ? e.target.closest('a[href]') : null;
    if (!anchor) return;
    const href = anchor.getAttribute('href') || '';
    if (!href) return;

    if (/wa\.me\/|whatsapp\.com/i.test(href)) {
      window.fdsTrackEvent('click_whatsapp', {
        link_url: href,
        link_text: (anchor.textContent || '').trim().slice(0, 80),
      });
    } else if (/^tel:/i.test(href)) {
      window.fdsTrackEvent('click_phone', { phone_href: href });
    } else if (/^mailto:/i.test(href)) {
      window.fdsTrackEvent('click_email', { email_href: href });
    } else if (href.includes('#hubungi-form') || href.includes('#form-penawaran')) {
      window.fdsTrackEvent('click_quotation_cta', {
        link_url: href,
        link_text: (anchor.textContent || '').trim().slice(0, 80),
      });
    }
  });

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initZeroReflowAOS, { once: true });
  } else {
    initZeroReflowAOS();
  }
}
