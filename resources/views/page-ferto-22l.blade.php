@extends('layouts.app')

@section('content')
@php
  $slug    = get_post_field('post_name', get_the_ID());
  $drones  = [
    'ferto-22l' => [
      'name'    => 'FERTO 22L',
      'tag'     => 'Drone Agrikultur &mdash; Kapasitas Enterprise',
      'color'   => '#0066cc',
      'specs'   => [
        ['Kapasitas tangki', '22 Liter'],
        ['Cakupan per jam', '10&ndash;12 Ha/jam'],
        ['Lebar semprot', '5&ndash;7 meter'],
        ['Daya tahan baterai', '±12 menit (full load)'],
        ['Ketahanan', 'IP67'],
        ['TKDN', '44,85%'],
        ['Nozzle', '6 titik bertekanan'],
        ['Software', 'Ground Control App'],
      ],
      'desc'    => 'FERTO 22L adalah unggulan lini produk FDS &mdash; drone agrikultur dengan tangki 22 liter yang dirancang untuk operasional lahan perkebunan berskala enterprise. Mampu mengcover hingga 10&ndash;12 hektare per jam dengan sistem nozzle 6-titik.',
      'for'     => ['Perkebunan kelapa sawit & tebu skala besar', 'Kontraktor jasa semprot profesional', 'Program modernisasi pertanian pemerintah', 'BUMN dan korporasi agrikultur'],
    ],
    'ferto-15l' => [
      'name'    => 'FERTO 15L',
      'tag'     => 'Drone Agrikultur &mdash; Kapasitas Menengah Atas',
      'color'   => '#0066cc',
      'specs'   => [
        ['Kapasitas tangki', '15 Liter'],
        ['Cakupan per jam', '8&ndash;10 Ha/jam'],
        ['Lebar semprot', '4&ndash;6 meter'],
        ['Daya tahan baterai', '±14 menit (full load)'],
        ['Ketahanan', 'IP67'],
        ['TKDN', '44,85%'],
        ['Nozzle', '4 titik bertekanan'],
        ['Software', 'Ground Control App'],
      ],
      'desc'    => 'FERTO 15L menghadirkan kapasitas 15 liter dengan bobot lebih kompak. Pilihan populer untuk operator profesional yang membutuhkan fleksibilitas transportasi tanpa mengorbankan produktivitas misi.',
      'for'     => ['Lahan padi dan hortikultura', 'Perkebunan medium', 'Operator multi-lokasi dalam satu hari', 'Koperasi pertanian'],
    ],
    'ferto-10l' => [
      'name'    => 'FERTO 10L',
      'tag'     => 'Drone Agrikultur &mdash; Serbaguna & Populer',
      'color'   => '#0066cc',
      'specs'   => [
        ['Kapasitas tangki', '10 Liter'],
        ['Cakupan per jam', '6&ndash;8 Ha/jam'],
        ['Lebar semprot', '4&ndash;5 meter'],
        ['Daya tahan baterai', '±16 menit (full load)'],
        ['Ketahanan', 'IP67'],
        ['TKDN', '44,85%'],
        ['Nozzle', '4 titik presisi'],
        ['Software', 'Ground Control App'],
      ],
      'desc'    => 'FERTO 10L adalah varian terlaris di lini produk FDS &mdash; titik manis antara kapasitas, portabilitas, dan harga. Menjadi pilihan utama kelompok tani, koperasi, dan operator drone di seluruh Indonesia.',
      'for'     => ['Kelompok tani dan koperasi', 'Penyuluh pertanian lapangan', 'Startup agritech', 'Program desa digital'],
    ],
    'ferto-5l'  => [
      'name'    => 'FERTO 5L',
      'tag'     => 'Drone Agrikultur &mdash; Kompak & Lincah',
      'color'   => '#0066cc',
      'specs'   => [
        ['Kapasitas tangki', '5 Liter'],
        ['Cakupan per jam', '4&ndash;6 Ha/jam'],
        ['Lebar semprot', '3&ndash;4 meter'],
        ['Daya tahan baterai', '±20 menit (full load)'],
        ['Ketahanan', 'IP67'],
        ['TKDN', '44,85%'],
        ['Nozzle', '2 titik presisi'],
        ['Software', 'Ground Control App'],
      ],
      'desc'    => 'FERTO 5L adalah varian paling kompak &mdash; dirancang untuk lahan berkontur, perkebunan berbukit, dan area sulit yang tidak bisa dijangkau drone besar. Ringan, lincah, dan mudah dioperasikan.',
      'for'     => ['Perkebunan kopi, kakao, cengkeh', 'Lahan berbukit dan terasering', 'Program pelatihan pilot', 'Petani mandiri'],
    ],
  ];
  $drone = $drones[$slug] ?? null;
@endphp

@if(!$drone)
  {{-- Fallback: render default page content --}}
  <div class="pt-[52px] bg-[#f5f5f7] min-h-[70vh]">
    @while(have_posts()) @php(the_post())
      <div class="max-w-[1400px] mx-auto px-6 lg:px-12 py-20">
        <h1 class="text-[40px] font-semibold text-[#1d1d1f] mb-8">{!! get_the_title() !!}</h1>
        <div class="prose text-[18px] text-[#515154] leading-[1.7]">@php(the_content())</div>
      </div>
    @endwhile
  </div>

@else

  <div class="bg-white pt-[52px]">

    {{-- ── HERO ──────────────────────────────────────────────────── --}}
    <section class="bg-[#f5f5f7] pt-20 pb-0 overflow-hidden">
      <div class="max-w-[1400px] mx-auto px-6 lg:px-12 text-center">
        <p class="text-[13px] font-semibold text-[#0066cc] tracking-wide mb-4">{{ $drone['tag'] }}</p>
        <h1 class="text-[64px] sm:text-[80px] lg:text-[96px] font-semibold tracking-[-0.04em] text-[#1d1d1f] leading-[1]">
          {{ $drone['name'] }}
        </h1>
        <p class="mt-6 text-[18px] sm:text-[20px] text-[#515154] max-w-[580px] mx-auto leading-[1.55]">
          {{ $drone['desc'] }}
        </p>
        <div class="mt-8 flex flex-wrap gap-4 justify-center">
          <a href="{{ home_url('/#kontak') }}"
             class="inline-flex items-center bg-[#0066cc] hover:bg-[#0055b0] active:scale-[0.97] text-white text-[15px] font-semibold px-7 py-3.5 rounded-full transition-all duration-150">
            Minta Penawaran
          </a>
          <a href="{{ home_url('/#kontak') }}"
             class="inline-flex items-center text-[#0066cc] text-[15px] font-medium hover:underline gap-1">
            Jadwalkan Demo &rsaquo;
          </a>
        </div>

        {{-- Drone hero image placeholder --}}
        <div class="mt-14 rounded-t-[2rem] overflow-hidden" style="box-shadow: 0 -8px 48px rgba(0,0,0,0.08);">
          <img src="https://picsum.photos/seed/{{ $slug }}-hero/1600/700"
               alt="{{ $drone['name'] }} &mdash; FDS"
               class="w-full h-[300px] sm:h-[420px] lg:h-[520px] object-cover">
        </div>
      </div>
    </section>

    {{-- ── SPECS ──────────────────────────────────────────────────── --}}
    <section class="bg-white py-24 sm:py-32 border-t border-black/[0.06]">
      <div class="max-w-[1400px] mx-auto px-6 lg:px-12">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 lg:gap-24 items-start">

          {{-- Left --}}
          <div class="lg:sticky lg:top-28">
            <p class="text-[13px] font-semibold text-[#0066cc] tracking-wide mb-4">Spesifikasi</p>
            <h2 class="text-[36px] sm:text-[46px] font-semibold tracking-[-0.03em] text-[#1d1d1f] leading-[1.1] mb-6">
              Direkayasa untuk<br>performa nyata.
            </h2>
            <p class="text-[17px] text-[#515154] leading-relaxed max-w-[400px]">
              Setiap spesifikasi {{ $drone['name'] }} divalidasi melalui ratusan jam uji lapangan di berbagai kondisi cuaca dan jenis lahan di Indonesia.
            </p>
          </div>

          {{-- Right: spec table --}}
          <div class="divide-y divide-black/[0.06]">
            @foreach($drone['specs'] as [$label, $value])
            <div class="py-5 grid grid-cols-2 gap-6">
              <p class="text-[14px] font-medium text-[#86868b]">{{ $label }}</p>
              <p class="text-[16px] font-semibold text-[#1d1d1f]">{{ $value }}</p>
            </div>
            @endforeach
          </div>

        </div>
      </div>
    </section>

    {{-- ── FOR WHOM ────────────────────────────────────────────────── --}}
    <section class="bg-[#f5f5f7] py-24 sm:py-32 border-t border-black/[0.06]">
      <div class="max-w-[1400px] mx-auto px-6 lg:px-12">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
          <div>
            <p class="text-[13px] font-semibold text-[#0066cc] tracking-wide mb-4">Untuk Siapa</p>
            <h2 class="text-[36px] sm:text-[46px] font-semibold tracking-[-0.03em] text-[#1d1d1f] leading-[1.1]">
              {{ $drone['name'] }} cocok untuk Anda.
            </h2>
          </div>
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            @foreach($drone['for'] as $usecase)
            <div class="bg-white rounded-2xl p-5 flex items-start gap-3" style="box-shadow: 0 2px 16px rgba(0,0,0,0.05);">
              <div class="w-5 h-5 bg-[#e8f0fe] rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                <svg class="w-3 h-3 text-[#0066cc]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
              </div>
              <p class="text-[14px] font-medium text-[#1d1d1f] leading-snug">{{ $usecase }}</p>
            </div>
            @endforeach
          </div>
        </div>
      </div>
    </section>

    {{-- ── TRUST BAR ───────────────────────────────────────────────── --}}
    <section class="bg-white py-16 border-t border-black/[0.06]">
      <div class="max-w-[1400px] mx-auto px-6 lg:px-12">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-10 text-center">
          <div>
            <p class="text-[40px] font-semibold tracking-[-0.04em] text-[#1d1d1f]">IP67</p>
            <p class="text-[12px] font-semibold text-[#86868b] tracking-wide mt-1">Proteksi Debu & Air</p>
          </div>
          <div>
            <p class="text-[40px] font-semibold tracking-[-0.04em] text-[#1d1d1f]">44,85%</p>
            <p class="text-[12px] font-semibold text-[#86868b] tracking-wide mt-1">Nilai TKDN</p>
          </div>
          <div>
            <p class="text-[40px] font-semibold tracking-[-0.04em] text-[#1d1d1f]">100%</p>
            <p class="text-[12px] font-semibold text-[#86868b] tracking-wide mt-1">Software Bahasa Indonesia</p>
          </div>
          <div>
            <p class="text-[40px] font-semibold tracking-[-0.04em] text-[#1d1d1f]">Garansi</p>
            <p class="text-[12px] font-semibold text-[#86868b] tracking-wide mt-1">Purna Jual Resmi</p>
          </div>
        </div>
      </div>
    </section>

    {{-- ── CTA ──────────────────────────────────────────────────────── --}}
    <section class="bg-[#1d1d1f] py-24">
      <div class="max-w-[1400px] mx-auto px-6 lg:px-12 text-center">
        <h2 class="text-[36px] sm:text-[52px] font-semibold tracking-[-0.03em] text-white leading-[1.1] mb-5">
          Siap mengoperasikan {{ $drone['name'] }}?
        </h2>
        <p class="text-[18px] text-white/60 max-w-[480px] mx-auto mb-8 leading-relaxed">
          Konsultasikan kebutuhan lahan Anda dengan tim teknis FDS. Demo unit tersedia di Yogyakarta.
        </p>
        <div class="flex flex-wrap gap-4 justify-center">
          <a href="{{ home_url('/#kontak') }}"
             class="inline-flex items-center bg-white hover:bg-[#f5f5f7] active:scale-[0.97] text-[#1d1d1f] text-[15px] font-semibold px-7 py-3.5 rounded-full transition-all duration-150">
            Hubungi Tim Sales
          </a>
          <a href="{{ home_url('/blog') }}"
             class="inline-flex items-center text-white/70 text-[15px] font-medium hover:text-white transition-colors gap-1">
            Baca studi kasus &rsaquo;
          </a>
        </div>
      </div>
    </section>

  </div>

@endif
@endsection
