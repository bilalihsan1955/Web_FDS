@props([
  'type' => 'fluid',
  'uid'  => 'fds-w',
])

@php
  $safe_id = preg_replace('/[^a-zA-Z0-9_-]/', '', (string) $uid) ?: 'fds-w';
@endphp

@if($type === 'sektor')
<svg class="fds-wave-bg w-full h-full" viewBox="0 0 1440 500" preserveAspectRatio="none" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
  <defs>
    <linearGradient id="{{ $safe_id }}-w1" x1="0%" y1="0%" x2="0%" y2="100%">
      <stop offset="0%" stop-color="#ffffff" stop-opacity="0.12"/>
      <stop offset="100%" stop-color="#052b2e" stop-opacity="0.55"/>
    </linearGradient>
    <linearGradient id="{{ $safe_id }}-w2" x1="0%" y1="0%" x2="0%" y2="100%">
      <stop offset="0%" stop-color="#75e6f0" stop-opacity="0.18"/>
      <stop offset="100%" stop-color="#031e20" stop-opacity="0.75"/>
    </linearGradient>
  </defs>
  <path d="M0,180 C340,220 720,340 1080,380 C1260,400 1380,390 1440,365 L1440,500 L0,500 Z" fill="url(#{{ $safe_id }}-w1)"/>
  <path d="M0,310 C320,330 680,410 1040,440 C1240,455 1360,450 1440,435 L1440,500 L0,500 Z" fill="url(#{{ $safe_id }}-w2)"/>
</svg>
@elseif($type === 'eko')
<svg class="fds-wave-bg w-full h-full" viewBox="0 0 398 96" preserveAspectRatio="xMidYMid slice" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
  <defs>
    <linearGradient id="{{ $safe_id }}-p0" x1="199" y1="0" x2="199" y2="96" gradientUnits="userSpaceOnUse">
      <stop stop-color="#0f808a" stop-opacity="0.35"/>
      <stop offset="0.915" stop-color="#052b2e" stop-opacity="0.9"/>
    </linearGradient>
    <linearGradient id="{{ $safe_id }}-p1" x1="192.438" y1="-8.14648" x2="192.438" y2="160.338" gradientUnits="userSpaceOnUse">
      <stop stop-color="#18d6e7" stop-opacity="0.34"/>
      <stop offset="0.915" stop-color="#0f808a" stop-opacity="0.14"/>
    </linearGradient>
    <linearGradient id="{{ $safe_id }}-p2" x1="183" y1="-0.710449" x2="183" y2="171" gradientUnits="userSpaceOnUse">
      <stop stop-color="#46deec" stop-opacity="0.30"/>
      <stop offset="0.49" stop-color="#0f808a" stop-opacity="0.20"/>
      <stop offset="0.9999" stop-color="#0a555c"/>
    </linearGradient>
    <linearGradient id="{{ $safe_id }}-p3" x1="65.3711" y1="53.9736" x2="65.3711" y2="166.765" gradientUnits="userSpaceOnUse">
      <stop stop-color="#75e6f0" stop-opacity="0.32"/>
      <stop offset="0.49" stop-color="#18d6e7" stop-opacity="0.20"/>
      <stop offset="0.9999" stop-color="#052b2e"/>
    </linearGradient>
  </defs>
  <rect width="398" height="96" fill="url(#{{ $safe_id }}-p0)"/>
  <path d="M-18.2893 -6.32697C-136.964 10.9814 -24.6036 35.4983 -46.9639 64.4209C-70.1751 94.444 4.76287 155.377 4.76287 155.377L406.77 160.338L455.686 19.5816C455.686 19.5816 486.61 10.9501 346.048 50.0886C205.485 89.2271 100.385 -23.6353 -18.2893 -6.32697Z" fill="url(#{{ $safe_id }}-p1)"/>
  <path d="M-87.4837 43.4736C-65.1233 14.551 -71.703 -9.63438 27.815 2.49305C127.333 14.6205 180.184 94.5513 338.738 73.6039C402.333 65.2021 433.365 58.4269 447.725 53.3251L453.437 41.8199C453.437 41.8199 469.167 45.7069 447.725 53.3251L389.303 171L-53.1488 161.803C-53.1488 161.803 -110.695 73.4967 -87.4837 43.4736Z" fill="url(#{{ $safe_id }}-p2)"/>
  <path d="M52.5537 76.3607C17.9455 66.8521 -56.751 53.9736 -56.751 53.9736L-46.402 162.355L187.493 166.765C187.493 166.765 114.012 93.2462 52.5537 76.3607Z" fill="url(#{{ $safe_id }}-p3)"/>
</svg>
@elseif($type === 'kontak')
<svg class="fds-wave-bg w-full h-full" viewBox="0 0 1440 800" preserveAspectRatio="xMidYMid slice" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
  <defs>
    <linearGradient id="{{ $safe_id }}-g1" x1="0%" y1="0%" x2="100%" y2="100%">
      <stop offset="0%" stop-color="#0a555c" stop-opacity="0.22"/><stop offset="50%" stop-color="#0a555c" stop-opacity="0.10"/><stop offset="100%" stop-color="#0a555c" stop-opacity="0"/>
    </linearGradient>
    <linearGradient id="{{ $safe_id }}-g2" x1="0%" y1="0%" x2="100%" y2="100%">
      <stop offset="0%" stop-color="#0f808a" stop-opacity="0.28"/><stop offset="50%" stop-color="#0a555c" stop-opacity="0.14"/><stop offset="100%" stop-color="#0a555c" stop-opacity="0"/>
    </linearGradient>
    <linearGradient id="{{ $safe_id }}-g3" x1="0%" y1="0%" x2="100%" y2="100%">
      <stop offset="0%" stop-color="#46deec" stop-opacity="0.28"/><stop offset="50%" stop-color="#0a555c" stop-opacity="0.16"/><stop offset="100%" stop-color="#0a555c" stop-opacity="0"/>
    </linearGradient>
    <linearGradient id="{{ $safe_id }}-g4" x1="0%" y1="0%" x2="100%" y2="100%">
      <stop offset="0%" stop-color="#13abb9" stop-opacity="0.34"/><stop offset="50%" stop-color="#0f808a" stop-opacity="0.18"/><stop offset="100%" stop-color="#0a555c" stop-opacity="0"/>
    </linearGradient>
    <linearGradient id="{{ $safe_id }}-g5" x1="0%" y1="0%" x2="100%" y2="100%">
      <stop offset="0%" stop-color="#18d6e7" stop-opacity="0.38"/><stop offset="50%" stop-color="#46deec" stop-opacity="0.20"/><stop offset="100%" stop-color="#0a555c" stop-opacity="0"/>
    </linearGradient>
  </defs>
  <path d="M-50,-50 C250,60 500,480 720,480 C940,480 1190,60 1490,-50 L1490,900 L-50,900 Z" fill="url(#{{ $safe_id }}-g1)"/>
  <path d="M-50,110 C250,210 500,550 720,550 C940,550 1190,210 1490,110 L1490,900 L-50,900 Z" fill="url(#{{ $safe_id }}-g2)"/>
  <path d="M-50,290 C250,370 500,610 720,610 C940,610 1190,370 1490,290 L1490,900 L-50,900 Z" fill="url(#{{ $safe_id }}-g3)"/>
  <path d="M-50,470 C250,530 500,680 720,680 C940,680 1190,530 1490,470 L1490,900 L-50,900 Z" fill="url(#{{ $safe_id }}-g4)"/>
  <path d="M-50,650 C250,680 500,760 720,760 C940,760 1190,680 1490,650 L1490,900 L-50,900 Z" fill="url(#{{ $safe_id }}-g5)"/>
</svg>
@else
<svg class="fds-wave-bg w-full h-full" viewBox="0 0 1440 700" preserveAspectRatio="none" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
  <defs>
    <radialGradient id="{{ $safe_id }}-amb" cx="10%" cy="90%" r="75%" fx="0%" fy="100%">
      <stop offset="0%" stop-color="#ffffff" stop-opacity="0.18"/>
      <stop offset="45%" stop-color="#46deec" stop-opacity="0.10"/>
      <stop offset="100%" stop-color="#0f808a" stop-opacity="0"/>
    </radialGradient>
    <linearGradient id="{{ $safe_id }}-bdk" x1="0%" y1="0%" x2="0%" y2="100%">
      <stop offset="0%" stop-color="#0f808a" stop-opacity="0"/>
      <stop offset="50%" stop-color="#0a555c" stop-opacity="0.25"/>
      <stop offset="100%" stop-color="#052b2e" stop-opacity="0.65"/>
    </linearGradient>
    <linearGradient id="{{ $safe_id }}-f1" x1="0%" y1="0%" x2="0%" y2="100%">
      <stop offset="0%" stop-color="#ffffff" stop-opacity="0.12"/>
      <stop offset="45%" stop-color="#75e6f0" stop-opacity="0.15"/>
      <stop offset="100%" stop-color="#0a555c" stop-opacity="0.60"/>
    </linearGradient>
    <linearGradient id="{{ $safe_id }}-f2" x1="0%" y1="0%" x2="0%" y2="100%">
      <stop offset="0%" stop-color="#a3eef5" stop-opacity="0.20"/>
      <stop offset="45%" stop-color="#46deec" stop-opacity="0.18"/>
      <stop offset="100%" stop-color="#0a555c" stop-opacity="0.70"/>
    </linearGradient>
    <linearGradient id="{{ $safe_id }}-f3" x1="0%" y1="0%" x2="0%" y2="100%">
      <stop offset="0%" stop-color="#ffffff" stop-opacity="0.22"/>
      <stop offset="45%" stop-color="#46deec" stop-opacity="0.20"/>
      <stop offset="100%" stop-color="#052b2e" stop-opacity="0.80"/>
    </linearGradient>
    <linearGradient id="{{ $safe_id }}-f4" x1="0%" y1="0%" x2="0%" y2="100%">
      <stop offset="0%" stop-color="#d1f7fa" stop-opacity="0.25"/>
      <stop offset="45%" stop-color="#18d6e7" stop-opacity="0.15"/>
      <stop offset="100%" stop-color="#031e20" stop-opacity="0.85"/>
    </linearGradient>
  </defs>
  <rect width="1440" height="700" fill="url(#{{ $safe_id }}-amb)"/>
  <path d="M0,110 C280,130 580,270 860,420 C1120,550 1300,570 1440,520 L1440,700 L0,700 Z" fill="url(#{{ $safe_id }}-f1)"/>
  <path d="M0,250 C280,290 560,410 820,530 C1060,610 1260,620 1440,580 L1440,700 L0,700 Z" fill="url(#{{ $safe_id }}-f2)"/>
  <path d="M0,390 C250,420 500,500 740,580 C980,640 1200,645 1440,620 L1440,700 L0,700 Z" fill="url(#{{ $safe_id }}-f3)"/>
  <path d="M0,510 C180,520 380,580 600,630 C840,670 1100,675 1440,655 L1440,700 L0,700 Z" fill="url(#{{ $safe_id }}-f4)"/>
  <rect y="420" width="1440" height="280" fill="url(#{{ $safe_id }}-bdk)"/>
</svg>
@endif
