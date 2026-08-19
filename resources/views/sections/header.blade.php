<header class="sticky top-0 z-50 bg-slate-950/95 border-b border-slate-800 text-slate-100 transition-all duration-200">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex items-center justify-between h-16 sm:h-20">
      
      <!-- Brand Logo -->
      <a href="{{ home_url('/') }}" class="flex items-center gap-3 group">
        <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-md bg-blue-600/10 border border-blue-500/30 flex items-center justify-center text-blue-400 group-hover:border-blue-400 group-hover:bg-blue-600/20 transition-all">
          <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
          </svg>
        </div>
        <div class="flex flex-col">
          <span class="font-bold text-base sm:text-lg tracking-wider text-slate-100 uppercase group-hover:text-blue-400 transition-colors">
            FULL DRONE <span class="text-blue-500">SOLUTIONS</span>
          </span>
          <span class="text-[10px] tracking-widest text-slate-400 uppercase font-mono -mt-1">
            TKDN 44.85% Certified
          </span>
        </div>
      </a>

      <!-- Desktop Navigation Links -->
      <nav class="hidden md:flex items-center gap-8 text-xs sm:text-sm font-medium tracking-wide uppercase text-slate-300">
        <a href="#solusi" class="hover:text-blue-400 transition-colors relative py-1 after:absolute after:bottom-0 after:left-0 after:w-0 after:h-0.5 after:bg-blue-500 hover:after:w-full after:transition-all">
          Solusi
        </a>
        <a href="#produk" class="hover:text-blue-400 transition-colors relative py-1 after:absolute after:bottom-0 after:left-0 after:w-0 after:h-0.5 after:bg-blue-500 hover:after:w-full after:transition-all">
          Produk
        </a>
        <a href="#tentang" class="hover:text-blue-400 transition-colors relative py-1 after:absolute after:bottom-0 after:left-0 after:w-0 after:h-0.5 after:bg-blue-500 hover:after:w-full after:transition-all">
          Tentang Kami
        </a>
        <a href="#kontak" class="hover:text-blue-400 transition-colors relative py-1 after:absolute after:bottom-0 after:left-0 after:w-0 after:h-0.5 after:bg-blue-500 hover:after:w-full after:transition-all">
          Kontak
        </a>
      </nav>

      <!-- CTA Button & Mobile Menu Button -->
      <div class="flex items-center gap-3 sm:gap-4">
        <a href="#kontak" class="bg-blue-600 hover:bg-blue-500 text-white font-medium text-xs sm:text-sm px-4 sm:px-5 py-2 sm:py-2.5 rounded-md shadow-sm hover:shadow-blue-600/25 transition-all flex items-center gap-2">
          <span>Jadwalkan Demo</span>
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
          </svg>
        </a>

        <!-- Mobile Menu Trigger -->
        <button id="mobile-menu-toggle" type="button" class="md:hidden p-2 rounded-md text-slate-400 hover:text-slate-100 hover:bg-slate-800 transition-colors" aria-label="Toggle navigation">
          <svg id="menu-icon-open" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
          </svg>
          <svg id="menu-icon-close" class="w-6 h-6 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
          </svg>
        </button>
      </div>

    </div>
  </div>

  <!-- Mobile Menu Dropdown -->
  <div id="mobile-menu" class="hidden md:hidden border-t border-slate-800 bg-slate-950 px-4 py-6 space-y-4">
    <nav class="flex flex-col space-y-3 text-sm uppercase tracking-wider font-medium text-slate-300">
      <a href="#solusi" class="mobile-nav-link hover:text-blue-400 py-2 border-b border-slate-800/50">Solusi Lintas Industri</a>
      <a href="#produk" class="mobile-nav-link hover:text-blue-400 py-2 border-b border-slate-800/50">Spesifikasi Produk</a>
      <a href="#tentang" class="mobile-nav-link hover:text-blue-400 py-2 border-b border-slate-800/50">Tentang FDS & TKDN</a>
      <a href="#kontak" class="mobile-nav-link hover:text-blue-400 py-2">Hubungi Sales & Demo</a>
    </nav>
  </div>
</header>

<script>
  document.addEventListener('DOMContentLoaded', () => {
    const toggleBtn = document.getElementById('mobile-menu-toggle');
    const mobileMenu = document.getElementById('mobile-menu');
    const iconOpen = document.getElementById('menu-icon-open');
    const iconClose = document.getElementById('menu-icon-close');
    const mobileLinks = document.querySelectorAll('.mobile-nav-link');

    if (toggleBtn && mobileMenu) {
      toggleBtn.addEventListener('click', () => {
        const isHidden = mobileMenu.classList.toggle('hidden');
        if (iconOpen && iconClose) {
          iconOpen.classList.toggle('hidden', !isHidden);
          iconClose.classList.toggle('hidden', isHidden);
        }
      });

      mobileLinks.forEach(link => {
        link.addEventListener('click', () => {
          mobileMenu.classList.add('hidden');
          if (iconOpen && iconClose) {
            iconOpen.classList.remove('hidden');
            iconClose.classList.add('hidden');
          }
        });
      });
    }
  });
</script>

