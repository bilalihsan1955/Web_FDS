@extends('layouts.app')

@section('content')
@php
  $nr = function_exists('App\fds_get_newsroom_ui_content') ? \App\fds_get_newsroom_ui_content() : [];
@endphp
<div class="bg-white pt-[52px] min-h-[85vh] flex items-center justify-center">
  <div class="text-center px-6 py-20 fds-hero-entrance">
    <p class="font-display tabular-nums text-[84px] sm:text-[112px] font-bold tracking-[-0.04em] text-[#1d1d1f] opacity-[0.08] leading-none select-none mb-6">{{ $nr['err404_code'] ?? '404' }}</p>
    <h1 class="text-[34px] sm:text-[44px] font-semibold tracking-[-0.025em] leading-[1.1] text-[#1d1d1f] mb-4">{{ $nr['err404_title'] ?? 'Halaman tidak ditemukan.' }}</h1>
    <p class="text-[17px] text-[#424245] mb-10 max-w-[460px] mx-auto leading-[1.65]">
      {{ $nr['err404_desc'] ?? 'Halaman yang Anda cari mungkin telah dihapus atau dipindahkan.' }}
    </p>
    <a href="{!! esc_url(home_url('/')) !!}" class="inline-flex items-center justify-center gap-2 min-h-[44px] bg-[#0f808a] hover:bg-[#0a555c] active:scale-[0.97] text-white text-[15px] font-semibold px-6 py-3 rounded-full transition-all duration-150 shadow-sm leading-none group">
      <span>{{ $nr['err404_cta'] ?? 'Kembali ke Beranda' }}</span>
      <svg class="w-[17px] h-[17px] transition-transform duration-200 group-hover:translate-x-0.5 group-hover:-translate-y-0.5 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
        <path d="M6 6h12v12"/>
        <path d="M6 18 18 6"/>
      </svg>
    </a>
  </div>
</div>
@endsection
