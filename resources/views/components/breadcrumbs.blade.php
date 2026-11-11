@props([
  'items' => [],
  'theme' => 'dark',
])

@if(!empty($items))
  @php
    $is_dark   = ($theme === 'dark');
    $nav_cls   = $is_dark ? 'text-white/80' : 'text-[#515154]';
    $link_cls  = $is_dark ? 'hover:text-white' : 'hover:text-[#0f808a]';
    $sep_cls   = $is_dark ? 'text-white/50' : 'text-[#86868b]';
    $curr_cls  = $is_dark ? 'text-white font-semibold' : 'text-[#1d1d1f] font-semibold';
  @endphp
  <nav aria-label="Breadcrumb" class="flex flex-wrap items-center gap-x-2 gap-y-1 text-[13px] {{ $nav_cls }}">
    @foreach($items as $idx => $item)
      @if($idx > 0)
        <span aria-hidden="true" class="{{ $sep_cls }}">/</span>
      @endif
      @if(!empty($item['url']) && !$loop->last)
        <a href="{!! esc_url($item['url']) !!}" class="inline-flex items-center min-h-[44px] sm:min-h-0 py-1 sm:py-0 {{ $link_cls }} hover:underline underline-offset-4 transition-colors">
          {{ function_exists('fds_clean_string') ? fds_clean_string((string) ($item['name'] ?? '')) : ($item['name'] ?? '') }}
        </a>
      @else
        <span aria-current="page" class="{{ $curr_cls }}">
          {{ function_exists('fds_clean_string') ? fds_clean_string((string) ($item['name'] ?? '')) : ($item['name'] ?? '') }}
        </span>
      @endif
    @endforeach
  </nav>
@endif

