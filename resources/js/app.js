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

  window.fdsSubmitInquiry = async (form, options = {}) => {
    if (!form) return;

    const statusBox = options.statusEl || form.querySelector('[data-fds-form-status]');
    const submitBtn = options.submitBtn || form.querySelector('button[type="submit"], input[type="submit"]');

    const setStatus = (msg, type = 'error') => {
      if (statusBox) {
        statusBox.textContent = msg || '';
        statusBox.classList.remove('hidden', 'text-[#0a555c]', 'bg-[#e8fbfd]', 'border-[#a3eef5]', 'text-red-700', 'bg-red-50', 'border-red-200');
        if (msg) {
          if (type === 'success') {
            statusBox.classList.add('text-[#0a555c]', 'bg-[#e8fbfd]', 'border-[#a3eef5]');
          } else {
            statusBox.classList.add('text-red-700', 'bg-red-50', 'border-red-200');
          }
        } else {
          statusBox.classList.add('hidden');
        }
      }
      if (msg && typeof window.fdsShowSnackbar === 'function' && (!statusBox || type === 'success')) {
        window.fdsShowSnackbar(type === 'success', type === 'success' ? 'Permintaan Terkirim' : 'Perhatian', msg);
      }
    };

    if (typeof form.checkValidity === 'function' && !form.checkValidity()) {
      form.reportValidity();
      return;
    }

    setStatus('');
    window.fdsPopulateAttribution(form);

    // Sync service_label from select element when present
    const serviceSelect = form.querySelector('select[name="service_slug"], select[name="service_label"], select[name="interest"]');
    if (serviceSelect && serviceSelect.selectedOptions && serviceSelect.selectedOptions[0]) {
      const chosenOpt = serviceSelect.selectedOptions[0];
      const labelVal = (chosenOpt.getAttribute('data-label') || chosenOpt.textContent || chosenOpt.value || '').trim();
      let hiddenLabel = form.querySelector('input[name="service_label"]');
      if (!hiddenLabel && serviceSelect.name !== 'service_label') {
        hiddenLabel = document.createElement('input');
        hiddenLabel.type = 'hidden';
        hiddenLabel.name = 'service_label';
        form.appendChild(hiddenLabel);
      }
      if (hiddenLabel && labelVal) {
        hiddenLabel.value = labelVal;
      }
    }

    const originalBtnHtml = submitBtn ? submitBtn.innerHTML : '';
    const loadingText = (submitBtn && submitBtn.getAttribute('data-loading-text')) || 'Mengirim Permintaan...';
    if (submitBtn) {
      submitBtn.disabled = true;
      submitBtn.classList.add('opacity-75', 'cursor-wait');
      if (submitBtn.tagName === 'BUTTON') {
        submitBtn.textContent = loadingText;
      }
    }

    const ajaxUrl = options.ajaxUrl
      || form.getAttribute('data-ajax-url')
      || form.getAttribute('action')
      || (window.fdsInquiryConfig && window.fdsInquiryConfig.ajaxUrl)
      || '/wp-admin/admin-ajax.php';

    try {
      const formData = new FormData(form);
      if (!formData.has('action')) {
        formData.append('action', 'fds_submit_inquiry');
      }

      const resp = await fetch(ajaxUrl, {
        method: 'POST',
        body: formData,
        credentials: 'same-origin',
      });

      let payload = null;
      try {
        payload = await resp.json();
      } catch (parseErr) {
        payload = null;
      }

      if (resp.ok && payload && payload.success) {
        const data = payload.data || {};
        window.fdsTrackEvent('generate_lead', {
          lead_ref: data.lead_ref || '',
          service_label: data.service_label || '',
          form_location: options.formLocation || form.getAttribute('data-form-location') || window.location.pathname,
        });

        try {
          window.sessionStorage.setItem('fds_last_lead', JSON.stringify({
            ref: data.lead_ref || '',
            service: data.service_label || '',
            at: new Date().toISOString(),
          }));
        } catch (storageErr) {}

        if (typeof options.onSuccess === 'function') {
          options.onSuccess(data, form);
        }

        if (data.redirect_url && options.redirect !== false) {
          window.location.href = data.redirect_url;
          return;
        }

        setStatus(data.message || 'Permintaan Anda berhasil dikirim.', 'success');
        form.reset();
      } else {
        const errMsg = (payload && payload.data && payload.data.message)
          ? payload.data.message
          : 'Terjadi kendala saat mengirim formulir. Silakan periksa kembali isian Anda dan coba lagi.';
        setStatus(errMsg, 'error');
      }
    } catch (netErr) {
      setStatus('Koneksi terputus sementara. Silakan periksa jaringan Anda atau hubungi kami melalui WhatsApp.', 'error');
    } finally {
      if (submitBtn) {
        submitBtn.disabled = false;
        submitBtn.classList.remove('opacity-75', 'cursor-wait');
        if (submitBtn.tagName === 'BUTTON' && originalBtnHtml) {
          submitBtn.innerHTML = originalBtnHtml;
        }
      }
    }
  };

  document.addEventListener('submit', (e) => {
    const form = e.target && e.target.closest ? e.target.closest('form[data-fds-inquiry-form]') : null;
    if (!form) return;
    e.preventDefault();
    window.fdsSubmitInquiry(form);
  });

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initZeroReflowAOS, { once: true });
  } else {
    initZeroReflowAOS();
  }
}
