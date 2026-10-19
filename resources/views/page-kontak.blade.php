{{--
  Template Name: Kontak Kami / Contact FDS
--}}

@extends('layouts.app')

@section('content')
@php
  // Ambil data kontak terpusat dari Theme Settings
  $global_c  = function_exists('\App\fds_get_global_contact') ? \App\fds_get_global_contact() : [];
  $c_phone   = $global_c['phone'] ?? '+62 8112 748 882';
  $c_email   = $global_c['email'] ?? 'marketing@fulldronesolutions.com';
  $c_address = $global_c['address'] ?? 'Jl. Griya Perwita Asri No.15, Ngropoh, Condongcatur, Kec. Depok, Kabupaten Sleman, Daerah Istimewa Yogyakarta 55281';
  $c_wa_link = $global_c['wa_link'] ?? 'https://wa.me/628112748882';
  $c_maps    = $global_c['maps_url'] ?? 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d4859.550770370755!2d110.35575187584948!3d-7.733164692285225!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e7a59ea1c47127b%3A0xd9a7f206f6f28d07!2sFull%20Drone%20Solutions!5e1!3m2!1sid!2sid!4v1787546079011!5m2!1sid!2sid';

  // Deep decode string helper
  $c_phone   = fds_clean_string($c_phone);
  $c_email   = fds_clean_string($c_email);
  $c_address = fds_clean_string($c_address);
  $c_company = fds_clean_string($global_c['company_name'] ?? 'PT Karya Solusi Angkasa');

  // Kustomisasi Narasi Halaman Kontak Dinamis
  $p_badge       = fds_clean_string($global_c['page_kontak_badge'] ?? 'Pusat Kontak');
  $p_title       = fds_clean_string($global_c['page_kontak_title'] ?? 'Hubungi Kami.');
  $p_desc        = fds_clean_string($global_c['page_kontak_desc'] ?? 'Diskusikan pengadaan armada drone, jadwal demo terbang, atau kebutuhan teknis operasional Anda bersama tim Full Drone Solutions.');
  $p_panel_label = fds_clean_string($global_c['page_kontak_panel_label'] ?? 'Kantor & Workshop Resmi');
  $p_panel_desc  = fds_clean_string($global_c['page_kontak_panel_desc'] ?? 'Produsen UAV nasional bersertifikasi TKDN 60,74%, SNI 9199:2023, dan ISO 9001:2015 berlokasi di Sleman, Yogyakarta.');
  $p_hours_wd    = fds_clean_string($global_c['page_kontak_hours_wd'] ?? 'Senin – Jumat: 08:30 – 17:00 WIB');
  $p_hours_sat   = fds_clean_string($global_c['page_kontak_hours_sat'] ?? 'Sabtu: 08:30 – 13:00 WIB');
  $p_wa_btn      = fds_clean_string($global_c['page_kontak_wa_btn'] ?? 'Chat WhatsApp Kami');
  $p_form_title  = fds_clean_string($global_c['page_kontak_form_title'] ?? 'Kirim Pesan atau Permintaan Penawaran');
  $p_form_desc   = fds_clean_string($global_c['page_kontak_form_desc'] ?? 'Lengkapi formulir di bawah ini, tim kami akan merespons dalam 1×24 jam kerja:');
  $p_form_btn    = fds_clean_string($global_c['page_kontak_form_btn'] ?? 'Kirim Pesan');
  $p_form_note   = fds_clean_string($global_c['page_kontak_form_note'] ?? 'Respons 1×24 jam kerja. Data terjamin aman.');
  $p_map_title   = fds_clean_string($global_c['page_kontak_map_title'] ?? 'Lokasi Workshop');
  $p_map_btn     = fds_clean_string($global_c['page_kontak_map_btn'] ?? 'Buka di Google Maps');
  $p_map_link    = $global_c['page_kontak_map_link'] ?? 'https://maps.google.com/?q=Full+Drone+Solutions+Condongcatur+Sleman';
@endphp

<div id="contact-page" class="w-full bg-[#f5f5f7] font-sans">
  
  {{-- 1. HERO SECTION --}}
  <x-page-hero
    :label="$p_badge"
    :title="$p_title"
    :description="$p_desc"
  />

  {{-- 2. MASTER CONTACT & INQUIRY HUB --}}
  <section id="hubungi-form" class="bg-[#f5f5f7] pb-12 sm:pb-16 overflow-hidden">
    <div class="max-w-[1400px] mx-auto px-6 lg:px-12 w-full">
      <x-inquiry-hub
        :panel-label="$p_panel_label"
        :company="$c_company"
        :panel-desc="$p_panel_desc"
        :phone="$c_phone"
        :email="$c_email"
        :address="$c_address"
        :wa-link="$c_wa_link"
        :wa-btn="$p_wa_btn"
        :hours-wd="$p_hours_wd"
        :hours-sat="$p_hours_sat"
        :form-title="$p_form_title"
        :form-desc="$p_form_desc"
        :form-btn="$p_form_btn"
        :form-note="$p_form_note"
      />
    </div>
  </section>

  {{-- 3. INTERACTIVE GOOGLE MAPS SECTION --}}
  <section id="lokasi-workshop" class="bg-[#f5f5f7] pb-20 sm:pb-28">
    <div class="max-w-[1400px] mx-auto px-6 lg:px-12 w-full">
      <x-google-map-card
        :title="$p_map_title"
        :address="$c_address"
        :map-url="$c_maps"
        :btn-text="$p_map_btn"
        :btn-url="$p_map_link"
      />
    </div>
  </section>

</div>

{{-- Global Floating Toast Notification --}}
<x-snackbar />

@endsection
