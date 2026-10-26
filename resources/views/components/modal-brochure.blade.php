@props([
  'droneId'     => 0,
  'droneName'   => 'Drone FDS',
  'title'       => 'Unduh Brosur Teknis Resmi',
  'descInitial' => 'Dapatkan lembar spesifikasi komprehensif, panduan instalasi, dan kalkulasi ROI operasional langsung dalam format PDF.',
  'descReady'   => 'Email Anda telah terverifikasi. Silakan unduh dokumen brosur PDF resmi melalui tombol di bawah ini.',
  'placeholder' => 'Masukkan email bisnis Anda...',
  'btnText'     => 'Download Brosur PDF (Resmi)',
  'fileUrl'     => '',
])

<div class="bg-[#f5f5f7] rounded-3xl p-8 sm:p-10 border border-black/[0.08] shadow-sm">
  <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-8 lg:gap-12">
    
    {{-- Left: Big Heading & Subtitle --}}
    <div class="max-w-2xl">
      <h3 class="text-[26px] sm:text-[32px] lg:text-[36px] font-bold text-[#1d1d1f] tracking-tight leading-[1.18]">
        {!! esc_html($title) !!}
      </h3>
      <p class="fds-brochure-desc-text text-[15px] sm:text-[17px] text-[#6e6e73] mt-2.5 sm:mt-3 leading-relaxed transition-all duration-200"
         data-desc-initial="{!! esc_attr(wp_specialchars_decode($descInitial)) !!}"
         data-desc-ready="{!! esc_attr(wp_specialchars_decode($descReady)) !!}">
        {!! esc_html(wp_specialchars_decode($descInitial)) !!}
      </p>
    </div>

    {{-- Right: Gated Action Area --}}
    <div class="w-full lg:w-auto">
      
      {{-- STATE 1: FORM INPUT EMAIL --}}
      <form id="fds-brochure-form" class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3.5">
        <input type="hidden" name="action" value="fds_download_brochure">
        <input type="hidden" name="nonce" value="{{ wp_create_nonce('fds_brochure_download_nonce') }}">
        <input type="hidden" name="drone_id" value="{{ (int) $droneId }}">
        <input type="hidden" name="drone_name" value="{{ esc_attr(wp_specialchars_decode($droneName)) }}">

        <div class="relative w-full sm:w-[320px] lg:w-[340px]">
          <input type="email" 
                 id="brochure_email" 
                 name="email" 
                 required 
                 placeholder="{{ esc_attr($placeholder) }}" 
                 class="w-full h-[54px] sm:h-[58px] px-6 bg-white border border-black/[0.1] rounded-full text-[15px] sm:text-[16px] text-[#1d1d1f] placeholder:text-[#86868b] focus:outline-none focus:bg-white focus:border-[#0f808a] focus:ring-2 focus:ring-[#0f808a]/20 transition-all">
        </div>

        <button type="submit" 
                id="fds-brochure-submit-btn" 
                class="h-[54px] sm:h-[58px] px-8 sm:px-10 bg-[#1d1d1f] hover:bg-black active:scale-[0.98] text-white text-[15px] sm:text-[16px] font-semibold rounded-full flex items-center justify-center gap-2.5 transition-all duration-150 cursor-pointer whitespace-nowrap min-h-[44px] leading-none group">
          <span class="leading-none">Kirim Email</span>
          <svg class="w-6 h-6 transition-transform duration-200 group-hover:translate-x-0.5 group-hover:-translate-y-0.5 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M6 6h12v12"/>
            <path d="M6 18 18 6"/>
          </svg>
        </button>
      </form>

      {{-- STATE 2: TOMBOL DOWNLOAD AKTIF --}}
      <div id="fds-brochure-download-ready" class="hidden items-center">
        <a id="fds-brochure-download-link" 
           href="{{ esc_url($fileUrl ?: '#') }}" 
           target="_blank" 
           rel="noopener noreferrer"
           class="inline-flex items-center justify-center gap-3 bg-[#0f808a] hover:bg-[#0a555c] active:scale-[0.98] text-white h-[54px] sm:h-[58px] px-10 sm:px-12 rounded-full font-semibold text-[15px] sm:text-[16px] transition-all duration-150 whitespace-nowrap cursor-pointer shadow-md shadow-black/15 min-h-[44px]">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
          </svg>
          <span>{!! esc_html(wp_specialchars_decode($btnText)) !!}</span>
        </a>
      </div>

      {{-- Feedback message --}}
      <div id="fds-brochure-msg" class="hidden text-[13px] mt-2.5 px-2 text-center sm:text-left"></div>
    </div>

  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
  const form = document.getElementById('fds-brochure-form');
  const readyBox = document.getElementById('fds-brochure-download-ready');
  const downloadLink = document.getElementById('fds-brochure-download-link');
  const submitBtn = document.getElementById('fds-brochure-submit-btn');
  const msgBox = document.getElementById('fds-brochure-msg');
  const descTexts = document.querySelectorAll('.fds-brochure-desc-text');
  const initialPdf = '{{ esc_js($fileUrl) }}';

  if (!form || !readyBox) return;

  function updateDescToReady() {
    descTexts.forEach(el => {
      if (el.dataset.descReady) {
        el.textContent = el.dataset.descReady;
      }
    });
  }

  // 1. Cek State LocalStorage
  const isUnlocked = localStorage.getItem('fds_brochure_unlocked') === '1';
  if (isUnlocked) {
    form.classList.add('hidden');
    readyBox.classList.remove('hidden');
    readyBox.classList.add('flex');
    updateDescToReady();
    if (initialPdf && downloadLink) {
      downloadLink.href = initialPdf;
    }
  }

  // 2. Submit Handler
  form.addEventListener('submit', function(e) {
    e.preventDefault();
    
    const emailInput = document.getElementById('brochure_email');
    const emailVal = emailInput ? emailInput.value.trim() : '';

    if (!emailVal || (emailInput && !emailInput.validity.valid)) {
      if (msgBox) {
        msgBox.textContent = 'Silakan masukkan alamat email yang valid.';
        msgBox.className = 'text-[13px] mt-2.5 font-medium text-rose-600 block text-center sm:text-left';
        msgBox.classList.remove('hidden');
      }
      return;
    }

    if (submitBtn) {
      submitBtn.disabled = true;
      submitBtn.innerHTML = `
        <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
          <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
          <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        <span>Memproses...</span>
      `;
    }
    if (msgBox) msgBox.classList.add('hidden');

    const formData = new FormData(form);

    fetch('{{ admin_url('admin-ajax.php') }}', {
      method: 'POST',
      body: formData,
    })
    .then(res => res.json())
    .then(data => {
      localStorage.setItem('fds_brochure_email', emailVal);
      localStorage.setItem('fds_brochure_unlocked', '1');

      if (data && data.success && data.data && data.data.download_url) {
        if (downloadLink) downloadLink.href = data.data.download_url;
      } else if (initialPdf && downloadLink) {
        downloadLink.href = initialPdf;
      }

      form.classList.add('hidden');
      readyBox.classList.remove('hidden');
      readyBox.classList.add('flex');
      updateDescToReady();
    })
    .catch(() => {
      localStorage.setItem('fds_brochure_email', emailVal);
      localStorage.setItem('fds_brochure_unlocked', '1');
      form.classList.add('hidden');
      readyBox.classList.remove('hidden');
      readyBox.classList.add('flex');
      updateDescToReady();
    })
    .finally(() => {
      if (submitBtn) {
        submitBtn.disabled = false;
        submitBtn.innerHTML = `
          <span class="leading-none">Kirim Email</span>
          <svg class="w-6 h-6 transition-transform duration-200 group-hover:translate-x-0.5 group-hover:-translate-y-0.5 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M6 6h12v12"/>
            <path d="M6 18 18 6"/>
          </svg>
        `;
      }
    });
  });
});
</script>
