@props([
  'panelLabel' => 'Enterprise Sales',
  'company'    => 'Hubungi tim Enterprise FDS.',
  'panelDesc'  => 'Konsultasi teknis armada UAV, integrasi sensor AI khusus, hingga program sertifikasi operasional pilot.',
  'phone'      => '+62 8112 748 882',
  'email'      => 'marketing@fulldronesolutions.com',
  'address'    => 'Jl. Griya Perwita Asri No.15, Ngropoh, Condongcatur, Kec. Depok, Kabupaten Sleman, Daerah Istimewa Yogyakarta 55281',
  'waLink'     => 'https://wa.me/628112748882',
  'waBtn'      => 'Chat via WhatsApp',
  'formTitle'  => 'Kirim pesan inquiry',
  'formDesc'   => 'Silakan lengkapi detail kontak dan kebutuhan operasional drone Anda:',
  'formBtn'    => 'Kirim Permintaan Konsultasi',
  'formNote'   => 'Kami merespons dalam 1×24 jam kerja. Data terjamin aman.',
])

{{-- Unified Master Card: Seamless Dual-Surface (Apple/Linear Enterprise Hub) --}}
<div class="bg-white rounded-[2rem] border border-black/[0.08] shadow-2xl shadow-black/[0.06] overflow-hidden grid grid-cols-1 lg:grid-cols-12 items-stretch">

  {{-- Left Side: Vibrant Teal Architectural Panel (5 cols) with Ambient Dual-Orb Glow --}}
  <div class="lg:col-span-5 bg-gradient-to-br from-[#13abb9] via-[#0f808a] to-[#0a555c] py-8 sm:py-9 px-8 sm:px-10 lg:px-12 text-white flex flex-col justify-between relative overflow-hidden border-b lg:border-b-0 lg:border-r border-white/[0.08] group">

    {{-- Vector Background: Ambient Dual-Orb Gradient Glow (Ref: Frame 3716.svg) --}}
    <div class="absolute inset-0 pointer-events-none overflow-hidden z-0">
      <svg class="absolute inset-0 w-full h-full transition-transform duration-700 group-hover:scale-105" viewBox="0 0 335 160" preserveAspectRatio="xMaxYMid slice" fill="none" xmlns="http://www.w3.org/2000/svg">
        <defs>
          <linearGradient id="orb1-kontak" x1="323.781" y1="-61" x2="286" y2="80" gradientUnits="userSpaceOnUse">
            <stop offset="0.15" stop-color="#0f808a" stop-opacity="0.1"/>
            <stop offset="1" stop-color="#46deec" stop-opacity="1"/>
          </linearGradient>
          <linearGradient id="orb2-kontak" x1="239.637" y1="64.8558" x2="201.856" y2="205.856" gradientUnits="userSpaceOnUse">
            <stop offset="0.15" stop-color="#0f808a" stop-opacity="0.1"/>
            <stop offset="1" stop-color="#46deec" stop-opacity="1"/>
          </linearGradient>
        </defs>
        <g opacity="0.65">
          <circle cx="286" cy="5" r="75" fill="url(#orb1-kontak)"/>
          <circle cx="201.856" cy="130.856" r="75" transform="rotate(-165 201.856 130.856)" fill="url(#orb2-kontak)"/>
        </g>
      </svg>
    </div>

    {{-- Top Header Section --}}
    <div class="relative z-10">
      <div class="mb-2">
        <span class="text-[12px] font-semibold text-white/75 tracking-wide">
          {!! esc_html($panelLabel) !!}
        </span>
      </div>

      <h2 class="text-[24px] sm:text-[28px] lg:text-[30px] font-semibold tracking-tight text-white leading-tight mb-2">
        {!! nl2br(esc_html($company)) !!}
      </h2>
      <p class="text-[13px] sm:text-[13.5px] text-white/80 leading-relaxed max-w-[420px]">
        {!! nl2br(esc_html($panelDesc)) !!}
      </p>
    </div>

    {{-- Direct Contact Information (Pinned to Bottom with Adaptive Space) --}}
    <div class="mt-8 pt-2 space-y-4 relative z-10">
      {{-- WhatsApp Channel --}}
      <div>
        <p class="text-[11px] font-medium text-white/70 mb-1">Telepon / WhatsApp</p>
        <a href="{{ esc_url($waLink) }}" target="_blank" rel="noopener noreferrer"
           class="text-[15.5px] sm:text-[16.5px] font-bold text-white hover:text-white/80 transition-colors">
          {!! esc_html($phone) !!}
        </a>
      </div>

      {{-- Email Channel --}}
      <div>
        <p class="text-[11px] font-medium text-white/70 mb-1">Email Bisnis</p>
        <a href="mailto:{{ esc_attr($email) }}"
           class="text-[14px] sm:text-[15px] font-bold text-white hover:text-white/80 transition-colors break-all">
          {!! esc_html($email) !!}
        </a>
      </div>

      {{-- Workshop Location --}}
      <div>
        <p class="text-[11px] font-medium text-white/70 mb-1">Lokasi Workshop &amp; Testing Center</p>
        <p class="text-[12.5px] sm:text-[13px] text-white/85 leading-relaxed max-w-[400px]">
          {!! esc_html($address) !!}
        </p>
      </div>

      {{-- WhatsApp Action Button --}}
      <div class="pt-2">
        <a href="{{ esc_url($waLink) }}" target="_blank" rel="noopener noreferrer"
           class="inline-flex items-center gap-2 bg-[#25D366] hover:bg-[#20bd5a] active:scale-[0.97] text-white font-bold text-[13.5px] px-5 py-2.5 rounded-full transition-all duration-150 shadow-md min-h-[44px]">
          <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
          <span>{!! esc_html($waBtn) !!}</span>
        </a>
      </div>
    </div>
  </div>

  {{-- Right Side: Streamlined Inquiry Form (7 cols) --}}
  <div class="lg:col-span-7 py-8 sm:py-9 px-8 sm:px-10 lg:px-12 bg-white flex flex-col justify-between">
    {{-- Form Header --}}
    <div class="mb-4 sm:mb-5">
      <h3 class="text-[19px] sm:text-[21px] font-semibold text-[#1d1d1f] tracking-tight mb-1">
        {!! esc_html($formTitle) !!}
      </h3>
      <p class="text-[12.5px] text-[#64748b]">{!! esc_html($formDesc) !!}</p>
    </div>

    {{-- Form Body --}}
    <form id="fds-inquiry-form" class="flex-1 flex flex-col justify-between space-y-3.5" onsubmit="fdsSubmitInquiry(event)">
      @php wp_nonce_field('fds_inquiry_nonce', 'fds_inquiry_nonce_val'); @endphp

      {{-- Anti-Bot Honeypot Trap --}}
      <div style="display:none !important; position:absolute !important; left:-9999px !important;" aria-hidden="true">
        <label for="fds_website_url_hp">Do not fill this field</label>
        <input type="text" id="fds_website_url_hp" name="website_url_hp" value="" tabindex="-1" autocomplete="off">
      </div>

      <div class="space-y-3.5">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
          <div>
            <label class="block text-[11px] font-medium text-[#475569] mb-1">Nama Depan <span class="text-red-500">*</span></label>
            <input type="text" name="first_name" required maxlength="60" placeholder="Ahmad"
              class="w-full bg-[#f8fafc] border border-[#e2e8f0] focus:border-[#0f808a] focus:bg-white rounded-xl px-3.5 py-2.5 text-[13.5px] text-[#1d1d1f] placeholder-[#94a3b8] outline-none transition-all duration-150">
          </div>
          <div>
            <label class="block text-[11px] font-medium text-[#475569] mb-1">Nama Belakang <span class="text-red-500">*</span></label>
            <input type="text" name="last_name" required maxlength="60" placeholder="Fauzi"
              class="w-full bg-[#f8fafc] border border-[#e2e8f0] focus:border-[#0f808a] focus:bg-white rounded-xl px-3.5 py-2.5 text-[13.5px] text-[#1d1d1f] placeholder-[#94a3b8] outline-none transition-all duration-150">
          </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
          <div>
            <label class="block text-[11px] font-medium text-[#475569] mb-1">Perusahaan / Instansi <span class="text-red-500">*</span></label>
            <input type="text" name="company" required maxlength="100" placeholder="PT. Contoh Indonesia"
              class="w-full bg-[#f8fafc] border border-[#e2e8f0] focus:border-[#0f808a] focus:bg-white rounded-xl px-3.5 py-2.5 text-[13.5px] text-[#1d1d1f] placeholder-[#94a3b8] outline-none transition-all duration-150">
          </div>
          <div>
            <label class="block text-[11px] font-medium text-[#475569] mb-1">Email Bisnis <span class="text-red-500">*</span></label>
            <input type="email" name="email" required maxlength="100" placeholder="nama@perusahaan.co.id"
              class="w-full bg-[#f8fafc] border border-[#e2e8f0] focus:border-[#0f808a] focus:bg-white rounded-xl px-3.5 py-2.5 text-[13.5px] text-[#1d1d1f] placeholder-[#94a3b8] outline-none transition-all duration-150">
          </div>
        </div>

        <div>
          <label class="block text-[11px] font-medium text-[#475569] mb-1">Nomor Telepon / WhatsApp <span class="text-red-500">*</span></label>
          <input type="tel" name="phone" required maxlength="30" placeholder="+62 812-XXXX-XXXX"
            class="w-full bg-[#f8fafc] border border-[#e2e8f0] focus:border-[#0f808a] focus:bg-white rounded-xl px-3.5 py-2.5 text-[13.5px] text-[#1d1d1f] placeholder-[#94a3b8] outline-none transition-all duration-150">
        </div>

        <div>
          <label class="block text-[11px] font-medium text-[#475569] mb-1">Kebutuhan Anda <span class="text-red-500">*</span></label>
          <textarea name="message" rows="3" required maxlength="3000" placeholder="Jelaskan kebutuhan drone, layanan, atau pertanyaan teknis Anda..."
            class="w-full bg-[#f8fafc] border border-[#e2e8f0] focus:border-[#0f808a] focus:bg-white rounded-xl px-3.5 py-2.5 text-[13.5px] text-[#1d1d1f] placeholder-[#94a3b8] outline-none transition-all duration-150 resize-none"></textarea>
        </div>
      </div>

      {{-- Submit Row (Docked to bottom, without divider line) --}}
      <div class="pt-2 mt-auto flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
        <button type="submit" id="fds-inquiry-btn"
          class="bg-[#0f808a] hover:bg-[#0a555c] active:scale-[0.97] text-white font-semibold text-[13.5px] px-7 py-2.5 rounded-full transition-all duration-200 whitespace-nowrap shadow-md shadow-black/15 inline-flex items-center gap-1.5 cursor-pointer min-h-[44px]">
          <svg id="fds-inquiry-spinner" class="hidden animate-spin -ml-0.5 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
          </svg>
          <span id="fds-inquiry-btn-text">{!! esc_html($formBtn) !!}</span>
          <span id="fds-inquiry-btn-arrow" class="text-[15px]">&rsaquo;</span>
        </button>
        <div class="flex items-center gap-1.5 text-[11.5px] text-[#86868b] whitespace-nowrap">
          <svg class="w-3.5 h-3.5 text-[#86868b] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
          <span>{!! esc_html($formNote) !!}</span>
        </div>
      </div>

    </form>
  </div>

</div>