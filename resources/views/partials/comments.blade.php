@php
  $post_id = get_the_ID();
  $comments_count = (int) get_comments_number($post_id);
  $comments = get_comments([
    'post_id' => $post_id,
    'status'  => 'approve',
    'order'   => 'ASC',
  ]);
  $is_unapproved = isset($_GET['unapproved']);
  $current_user = wp_get_current_user();
@endphp

{{-- ========================================================== --}}
{{-- KOMENTAR & DISKUSI ARTIKEL                                 --}}
{{-- ========================================================== --}}
<section id="comments" class="mt-16 pt-12 border-t border-black/[0.08]">
  
  {{-- Header Komentar --}}
  <div class="flex items-center justify-between mb-8 flex-wrap gap-4">
    <div class="flex items-center gap-3">
      <h2 class="text-[24px] sm:text-[28px] font-semibold tracking-[-0.02em] text-[#1d1d1f]">
        Komentar &amp; Tanggapan
      </h2>
      <span class="bg-[#f5f5f7] border border-black/[0.06] text-[#515154] text-[13px] font-semibold px-3 py-1 rounded-full">
        {{ $comments_count }}
      </span>
    </div>

    @if(comments_open())
      <a href="#respond" class="text-[13.5px] font-medium text-[#0066cc] hover:underline inline-flex items-center gap-1">
        <span>Tulis Komentar</span>
        <span>&darr;</span>
      </a>
    @endif
  </div>

  {{-- Alert jika komentar baru menunggu moderasi --}}
  @if($is_unapproved)
    <div class="mb-8 p-4 rounded-2xl bg-[#f0fdf4] border border-[#22c55e]/20 text-[#166534] text-[14px] flex items-center gap-3">
      <svg class="w-5 h-5 flex-shrink-0 text-[#22c55e]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
      </svg>
      <span>Terima kasih! Komentar Anda telah berhasil terkirim dan sedang menunggu moderasi oleh tim kami sebelum ditampilkan.</span>
    </div>
  @endif

  {{-- List Komentar yang Sudah Disetujui --}}
  @if(!empty($comments))
    <div class="space-y-4 mb-12">
      @foreach($comments as $comment)
        @php
          $author_name = $comment->comment_author ?: 'Anonim';
          $initial = strtoupper(mb_substr($author_name, 0, 1));
          $comment_date = date_i18n('j F Y - H:i', strtotime($comment->comment_date));
        @endphp
        <div class="bg-[#f9f9fb] border border-black/[0.05] rounded-2xl p-5 sm:p-6 transition-all duration-150 hover:border-black/[0.09]">
          <div class="flex items-center gap-3.5 mb-3.5">
            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-[#0066cc] to-[#3b82f6] text-white flex items-center justify-center font-bold text-[15px] shadow-sm flex-shrink-0">
              {{ $initial }}
            </div>
            <div>
              <h4 class="text-[15px] font-semibold text-[#1d1d1f] leading-tight">
                {{ esc_html($author_name) }}
              </h4>
              <p class="text-[12px] text-[#86868b] mt-0.5">
                {{ $comment_date }} WIB
              </p>
            </div>
          </div>
          <div class="text-[14.5px] text-[#334155] leading-relaxed pl-0 sm:pl-[52px]">
            {!! nl2br(esc_html($comment->comment_content)) !!}
          </div>
        </div>
      @endforeach
    </div>
  @else
    {{-- Empty State Komentar --}}
    <div class="py-10 mb-10 text-center rounded-2xl bg-[#fafafa] border border-black/[0.04]">
      <svg class="w-10 h-10 text-[#94a3b8] mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
      </svg>
      <p class="text-[14px] text-[#64748b]">
        Belum ada komentar untuk artikel ini. Jadilah yang pertama membagikan tanggapan Anda!
      </p>
    </div>
  @endif

  {{-- Formulir Kirim Komentar --}}
  @if(comments_open())
    <div id="respond" class="bg-[#f9f9fb] border border-black/[0.06] rounded-[1.5rem] p-6 sm:p-8">
      <h3 class="text-[18px] sm:text-[20px] font-semibold text-[#1d1d1f] mb-2 tracking-tight">
        Tinggalkan Komentar
      </h3>
      <p class="text-[13px] text-[#86868b] mb-6">
        Alamat email Anda tidak akan dipublikasikan. Ruas yang wajib ditandai <span class="text-red-500">*</span>
      </p>

      <form action="{{ esc_url(site_url('/wp-comments-post.php')) }}" method="post" class="space-y-4">
        
        {{-- Anti-Bot Honeypot Trap (Hidden from humans, traps spambots) --}}
        <div style="display:none !important; position:absolute !important; left:-9999px !important;" aria-hidden="true">
          <label for="fds_comment_hp_field">Do not fill this field</label>
          <input type="text" id="fds_comment_hp_field" name="fds_comment_hp" value="" tabindex="-1" autocomplete="off">
        </div>

        {{-- Status login --}}
        @if(is_user_logged_in())
          <div class="text-[13px] text-[#64748b] pb-1">
            Masuk sebagai <strong class="text-[#1d1d1f]">{{ $current_user->display_name }}</strong>. 
            <a href="{{ wp_logout_url(get_permalink()) }}" class="text-[#0066cc] hover:underline ml-1">Keluar?</a>
          </div>
        @else
          {{-- Field Nama & Email untuk Tamu --}}
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <label class="block text-[12px] font-semibold text-[#334155] mb-1.5">
                Nama Lengkap <span class="text-red-500">*</span>
              </label>
              <input type="text" name="author" required maxlength="60" placeholder="Contoh: Budi Santoso"
                     class="w-full px-4 py-2.5 bg-white border border-black/[0.1] rounded-xl text-[14px] text-[#1d1d1f] placeholder-[#94a3b8] focus:border-[#0066cc] focus:ring-2 focus:ring-[#0066cc]/10 outline-none transition-all">
            </div>
            <div>
              <label class="block text-[12px] font-semibold text-[#334155] mb-1.5">
                Alamat Email <span class="text-red-500">*</span>
              </label>
              <input type="email" name="email" required maxlength="100" placeholder="nama@perusahaan.com"
                     class="w-full px-4 py-2.5 bg-white border border-black/[0.1] rounded-xl text-[14px] text-[#1d1d1f] placeholder-[#94a3b8] focus:border-[#0066cc] focus:ring-2 focus:ring-[#0066cc]/10 outline-none transition-all">
            </div>
          </div>
        @endif

        {{-- Textarea Komentar --}}
        <div>
          <label class="block text-[12px] font-semibold text-[#334155] mb-1.5">
            Komentar Anda <span class="text-red-500">*</span>
          </label>
          <textarea name="comment" rows="4" required maxlength="2000" placeholder="Tulis tanggapan, pertanyaan, atau pandangan Anda terkait artikel ini..."
                    class="w-full px-4 py-3 bg-white border border-black/[0.1] rounded-xl text-[14px] text-[#1d1d1f] placeholder-[#94a3b8] focus:border-[#0066cc] focus:ring-2 focus:ring-[#0066cc]/10 outline-none transition-all leading-relaxed"></textarea>
        </div>

        {{-- Hidden Fields WordPress Comments --}}
        <input type="hidden" name="comment_post_ID" value="{{ $post_id }}">
        <input type="hidden" name="comment_parent" id="comment_parent" value="0">

        {{-- Submit Button --}}
        <div class="pt-2">
          <button type="submit"
                  class="inline-flex items-center gap-2 bg-[#1d1d1f] hover:bg-[#0066cc] active:scale-95 text-white text-[13.5px] font-semibold px-6 py-3 rounded-full transition-all duration-200 shadow-sm cursor-pointer">
            <span>Kirim Komentar</span>
            <span>&rsaquo;</span>
          </button>
        </div>
      </form>
    </div>
  @else
    <div class="py-6 text-center text-[#86868b] text-[13.5px] italic">
      Komentar untuk artikel ini telah ditutup.
    </div>
  @endif

</section>
