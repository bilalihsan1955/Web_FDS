@extends('layouts.app')

@section('content')
<div class="bg-[#f5f5f7] pt-[52px] min-h-[85vh] flex items-center justify-center">
  <div class="text-center px-6 py-20">
    <p class="text-[80px] sm:text-[100px] font-semibold tracking-[-0.05em] text-[#1d1d1f] opacity-[0.08] leading-none select-none mb-6">404</p>
    <h1 class="text-[28px] sm:text-[36px] font-semibold tracking-[-0.02em] text-[#1d1d1f] mb-4">Halaman tidak ditemukan.</h1>
    <p class="text-[17px] text-[#515154] mb-10 max-w-[380px] mx-auto leading-relaxed">
      Halaman yang Anda cari mungkin telah dihapus atau dipindahkan.
    </p>
    <a href="{{ home_url('/') }}" class="inline-flex items-center bg-[#0066cc] hover:bg-[#0055b0] active:scale-[0.97] text-white text-[15px] font-semibold px-6 py-3 rounded-full transition-all duration-150">
      Kembali ke Beranda
    </a>
  </div>
</div>
@endsection
