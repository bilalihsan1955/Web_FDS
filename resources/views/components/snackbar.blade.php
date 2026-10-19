{{-- Global Toast / Snackbar Notification Component --}}
<div id="fds-snackbar" class="fixed bottom-6 right-6 z-[99999] max-w-[420px] w-[calc(100%-3rem)] transition-all duration-300 transform translate-y-24 opacity-0 pointer-events-none" role="status" aria-live="polite">
  <div id="fds-snackbar-card" class="rounded-2xl p-4 shadow-2xl shadow-gray-900/30 flex items-start gap-3.5">
    <div id="fds-snackbar-icon" class="w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5"></div>
    <div class="flex-1 min-w-0">
      <p id="fds-snackbar-title" class="text-[13.5px] font-bold text-white leading-tight mb-0.5"></p>
      <p id="fds-snackbar-desc" class="text-[12.5px] text-white/90 leading-snug"></p>
    </div>
    <button type="button" onclick="fdsCloseSnackbar()" class="text-white/70 hover:text-white hover:bg-white/15 p-1.5 rounded-lg transition-colors flex-shrink-0 cursor-pointer min-w-[32px] min-h-[32px] flex items-center justify-center" aria-label="Tutup Notifikasi">
      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
    </button>
  </div>
</div>

<script>
var fdsSnackbarTimer = null;

function fdsShowSnackbar(isSuccess, title, message) {
  var bar = document.getElementById('fds-snackbar');
  var card = document.getElementById('fds-snackbar-card');
  var icon = document.getElementById('fds-snackbar-icon');
  var titleEl = document.getElementById('fds-snackbar-title');
  var descEl = document.getElementById('fds-snackbar-desc');
  if (!bar) return;

  if (isSuccess) {
    card.className = 'bg-[#0f808a] border border-[#0a555c] rounded-2xl p-4 shadow-2xl shadow-gray-900/30 flex items-start gap-3.5 text-white';
    icon.className = 'w-8 h-8 rounded-full bg-white/20 text-white flex items-center justify-center flex-shrink-0 mt-0.5';
    icon.innerHTML = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>';
  } else {
    card.className = 'bg-[#dc2626] border border-[#b91c1c] rounded-2xl p-4 shadow-2xl shadow-gray-900/30 flex items-start gap-3.5 text-white';
    icon.className = 'w-8 h-8 rounded-full bg-white/20 text-white flex items-center justify-center flex-shrink-0 mt-0.5';
    icon.innerHTML = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>';
  }

  titleEl.textContent = title;
  descEl.textContent = message;

  bar.classList.remove('translate-y-24', 'opacity-0', 'pointer-events-none');
  bar.classList.add('translate-y-0', 'opacity-100', 'pointer-events-auto');

  clearTimeout(fdsSnackbarTimer);
  fdsSnackbarTimer = setTimeout(function() {
    fdsCloseSnackbar();
  }, 5000);
}

function fdsCloseSnackbar() {
  var bar = document.getElementById('fds-snackbar');
  if (!bar) return;
  bar.classList.remove('translate-y-0', 'opacity-100', 'pointer-events-auto');
  bar.classList.add('translate-y-24', 'opacity-0', 'pointer-events-none');
}
</script>
