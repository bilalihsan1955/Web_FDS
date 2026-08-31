<?php

namespace App;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * =========================================================================
 * FDS SOLUSI INDUSTRI MANAGER FOR HOMEPAGE
 * =========================================================================
 * Mengelola judul, deskripsi, dan kartu solusi industri beranda secara dinamis.
 */

// Solusi Industri functions and helpers

// 2. ENQUEUE WP MEDIA UPLOADER
add_action('admin_enqueue_scripts', function ($hook) {
    if ($hook === 'toplevel_page_fds-solusi-settings') {
        wp_enqueue_media();
    }
});

// 3. HELPER DEFAULT CARDS
function fds_get_default_solusi_cards() {
    return [
        [
            'image'     => 'https://images.unsplash.com/photo-1527011046414-4781f1f94f8c?auto=format&fit=crop&w=800&q=80',
            'title'     => 'Penyemprotan & Analisis NDVI',
            'desc'      => 'Penyemprotan >50% lebih efisien bahan kimia dengan radar terrain-following kontur tanah untuk seri FERTO 5L–50L. Pemantauan kesehatan tanaman 10x lebih cepat (30–40 Ha/jam) dengan kamera multispektral NDVI.',
            'tag'       => 'FERTO 5L – 50L',
            'link_text' => 'Lihat Seri FERTO',
            'link_url'  => '#produk',
        ],
        [
            'image'     => 'https://images.unsplash.com/photo-1508614589041-895b88991e3e?auto=format&fit=crop&w=800&q=80',
            'title'     => 'Pemetaan Udara & Topografi 3D',
            'desc'      => 'Menghemat waktu survei 70–80% untuk area luas dengan Fixed-Wing Hybrid VTOL DELTAV (jangkauan 60 km). Menghasilkan ortomozaik sub-sentimeter, model 3D DSM/DTM, kalkulasi volume cut & fill (akurasi ±2.35%), dan data siap CAD/BIM.',
            'tag'       => 'DELTAV (60 km)',
            'link_text' => 'Konsultasi Pemetaan',
            'link_url'  => '#kontak',
        ],
        [
            'image'     => 'https://images.unsplash.com/photo-1581092160607-ee22621dd758?auto=format&fit=crop&w=800&q=80',
            'title'     => 'Inspeksi Industri & Infrastruktur',
            'desc'      => 'Inspeksi aset secara efisien dan aman tanpa shutdown operasional (zero downtime), serta bebas risiko bekerja di ketinggian. Didukung sensor optik high-zoom, kamera termal inframerah, dan analitik AI untuk deteksi dini anomali serta pemeliharaan preventif.',
            'tag'       => 'MULTIPURPOSE UAV',
            'link_text' => 'Konsultasi Solusi Inspeksi',
            'link_url'  => '#kontak',
        ],
        [
            'image'     => 'https://images.unsplash.com/photo-1508614589041-895b88991e3e?auto=format&fit=crop&w=800&q=80',
            'title'     => 'Distribusi Kargo & Sebar Biji (Seedball)',
            'desc'      => 'Distribusi kargo logistik cepat 3–10 kg ke area terisolir dengan DELFRO. Serta misi penaburan benih seedball otonom berkapasitas 20 kg dengan REBO untuk restorasi hutan dan reklamasi tambang 80% lebih cepat dibanding survei darat.',
            'tag'       => 'DELFRO & REBO',
            'link_text' => 'Pelajari Produk',
            'link_url'  => '#produk',
        ],
    ];
}

// 4. HELPER DATA FRONTEND
function fds_get_solusi_data() {
    $badge = get_option('fds_solusi_badge', 'Solusi Industri FDS');
    $title = get_option('fds_solusi_title', 'Satu platform. Berbagai industri strategis.');
    $desc  = get_option('fds_solusi_desc', 'Solusi rekayasa UAV terintegrasi hardware, software FDS STATION, sensor AI, dan layanan operasional bersertifikasi untuk efisiensi maksimal di lapangan.');
    
    $saved_cards = get_option('fds_solusi_cards', null);
    if ($saved_cards === null || !is_array($saved_cards) || empty($saved_cards)) {
        $cards = fds_get_default_solusi_cards();
    } else {
        $cards = $saved_cards;
    }

    $normalized_cards = [];
    if (is_array($cards)) {
        foreach ($cards as $c) {
            $tag = $c['tag'] ?? '';
            if (strpos($tag, 'Warning</b>') !== false) {
                $tag = '';
            }
            $normalized_cards[] = [
                'image'     => $c['image'] ?? '',
                'title'     => $c['title'] ?? '',
                'desc'      => $c['desc'] ?? '',
                'tag'       => $tag,
                'link_text' => $c['link_text'] ?? 'Pelajari Selengkapnya',
                'link_url'  => $c['link_url'] ?? '#kontak',
            ];
        }
    }

    // Decode HTML entities berulang (&amp;amp; → &amp; → &, dll)
    $fds_deep_decode = function($str) {
        if (!is_string($str)) return $str;
        $prev = '';
        while ($prev !== $str) {
            $prev = $str;
            $str = wp_specialchars_decode($str, ENT_QUOTES);
        }
        return $str;
    };

    foreach ($normalized_cards as &$nc) {
        foreach ($nc as $key => &$val) {
            $val = $fds_deep_decode($val);
        }
        unset($val);
    }
    unset($nc);

    return [
        'badge' => $fds_deep_decode($badge),
        'title' => $fds_deep_decode($title),
        'desc'  => $fds_deep_decode($desc),
        'cards' => $normalized_cards,
    ];
}

// 5. HALAMAN PENGATURAN WP ADMIN
function render_solusi_settings_admin_page() {
    if (!current_user_can('manage_options')) {
        return;
    }

    $message = '';
    if (isset($_POST['fds_solusi_save']) && check_admin_referer('fds_solusi_nonce_action', 'fds_solusi_nonce')) {
        $badge = sanitize_text_field($_POST['fds_solusi_badge'] ?? '');
        $title = sanitize_text_field($_POST['fds_solusi_title'] ?? '');
        $desc  = sanitize_textarea_field($_POST['fds_solusi_desc'] ?? '');

        $posted_cards = $_POST['cards'] ?? [];
        $sanitized_cards = [];

        if (is_array($posted_cards)) {
            foreach ($posted_cards as $c) {
                $c_title = sanitize_text_field($c['title'] ?? '');
                if (!empty($c_title)) {
                    $sanitized_cards[] = [
                        'image'     => esc_url_raw($c['image'] ?? ''),
                        'title'     => $c_title,
                        'desc'      => sanitize_textarea_field($c['desc'] ?? ''),
                        'tag'       => sanitize_text_field($c['tag'] ?? ''),
                        'link_text' => sanitize_text_field($c['link_text'] ?? 'Pelajari Selengkapnya'),
                        'link_url'  => sanitize_text_field($c['link_url'] ?? '#kontak'),
                    ];
                }
            }
        }

        update_option('fds_solusi_badge', $badge);
        update_option('fds_solusi_title', $title);
        update_option('fds_solusi_desc', $desc);
        update_option('fds_solusi_cards', $sanitized_cards);

        $message = 'Pengaturan Solusi Industri Beranda berhasil disimpan!';
    }

    $solusi_data = fds_get_solusi_data();
    ?>
    <div class="wrap" style="max-width: 100%; margin-top: 20px; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; box-sizing: border-box;">
        <div style="background: #fff; padding: 24px 32px; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.06); border: 1px solid #e2e8f0; margin-bottom: 24px;">
            <div style="display: flex; align-items: center; gap: 14px; margin-bottom: 8px;">
                <div style="width: 44px; height: 44px; border-radius: 10px; background: #0066cc; display: flex; align-items: center; justify-content: center; color: #fff;">
                    <span class="dashicons dashicons-grid-view" style="font-size: 24px; width: 24px; height: 24px;"></span>
                </div>
                <div>
                    <h1 style="margin: 0; font-size: 22px; font-weight: 700; color: #1e293b;">Pengaturan Section Solusi Industri Beranda</h1>
                    <p style="margin: 4px 0 0; color: #64748b; font-size: 13px;">Kelola judul section, pengantar, foto, tag model drone, dan uraian kartu solusi industri yang tampil di halaman beranda.</p>
                </div>
            </div>

            <?php if (!empty($message)): ?>
                <div style="background: #f0fdf4; border-left: 4px solid #22c55e; color: #166534; padding: 14px 18px; border-radius: 6px; margin: 20px 0 10px; font-size: 14px; font-weight: 500;">
                    ✓ <?php echo esc_html($message); ?>
                </div>
            <?php endif; ?>
        </div>

        <form method="post" action="">
            <?php wp_nonce_field('fds_solusi_nonce_action', 'fds_solusi_nonce'); ?>

            <!-- 1. HEADER SECTION SETTINGS -->
            <div style="background: #fff; padding: 28px 32px; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.06); border: 1px solid #e2e8f0; margin-bottom: 24px;">
                <h2 style="font-size: 16px; font-weight: 700; color: #0f172a; margin-top: 0; margin-bottom: 20px; border-bottom: 1px solid #f1f5f9; padding-bottom: 12px;">
                    1. Header &amp; Judul Section
                </h2>

                <div style="display: grid; gap: 16px;">
                    <div>
                        <label style="display: block; font-size: 13px; font-weight: 600; color: #334155; margin-bottom: 4px;">Kategori Kecil / Sub-Heading</label>
                        <input type="text" name="fds_solusi_badge" value="<?php echo esc_attr($solusi_data['badge']); ?>" style="width: 100%; font-size: 14px; padding: 8px 12px; border-radius: 6px; border: 1px solid #cbd5e1;" placeholder="Contoh: Solusi Industri FDS">
                    </div>

                    <div>
                        <label style="display: block; font-size: 13px; font-weight: 600; color: #334155; margin-bottom: 4px;">Judul Utama Section</label>
                        <input type="text" name="fds_solusi_title" value="<?php echo esc_attr($solusi_data['title']); ?>" style="width: 100%; font-size: 15px; font-weight: 600; padding: 8px 12px; border-radius: 6px; border: 1px solid #cbd5e1;" placeholder="Contoh: Satu platform. Berbagai industri strategis.">
                    </div>

                    <div>
                        <label style="display: block; font-size: 13px; font-weight: 600; color: #334155; margin-bottom: 4px;">Deskripsi / Teks Pengantar</label>
                        <textarea name="fds_solusi_desc" rows="3" style="width: 100%; font-size: 13px; padding: 8px 12px; border-radius: 6px; border: 1px solid #cbd5e1;"><?php echo esc_textarea($solusi_data['desc']); ?></textarea>
                    </div>
                </div>
            </div>

            <!-- 2. KARTU SOLUSI INDUSTRI -->
            <div style="background: #fff; padding: 28px 32px; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.06); border: 1px solid #e2e8f0; margin-bottom: 24px;">
                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px; border-bottom: 1px solid #f1f5f9; padding-bottom: 12px;">
                    <div>
                        <h2 style="font-size: 16px; font-weight: 700; color: #0f172a; margin: 0;">2. Kartu Solusi Industri (Maksimal 4 Kolom)</h2>
                        <p style="margin: 4px 0 0; color: #64748b; font-size: 13px;">Kelola foto, judul, uraian, tag model UAV, dan tautan tombol pada masing-masing kartu.</p>
                    </div>
                    <button type="button" id="fds-add-solusi-card-btn" class="button button-secondary" style="font-weight: 600;">+ Tambah Kartu Solusi</button>
                </div>

                <div id="fds-solusi-cards-container" style="display: flex; flex-direction: column; gap: 20px;">
                    <?php foreach ($solusi_data['cards'] as $index => $c): ?>
                    <div class="fds-solusi-card-item" style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; padding: 20px; position: relative;">
                        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 14px; border-bottom: 1px solid #e2e8f0; padding-bottom: 8px;">
                            <span style="font-size: 13px; font-weight: 700; color: #0066cc;">Kartu #<span class="card-num"><?php echo $index + 1; ?></span> &mdash; <?php echo esc_html($c['title'] ?: 'Solusi'); ?></span>
                            <button type="button" class="button fds-remove-solusi-btn" style="font-size: 11px; color: #dc2626; border-color: #fecaca;">Hapus Kartu</button>
                        </div>

                        <div style="display: grid; grid-template-columns: 200px 1fr; gap: 20px;">
                            <!-- Kolom Foto Preview -->
                            <div>
                                <label style="display: block; font-size: 11px; font-weight: 700; color: #475569; margin-bottom: 6px;">Foto Solusi</label>
                                <div style="width: 100%; height: 130px; border-radius: 8px; overflow: hidden; background: #1e293b; margin-bottom: 8px;">
                                    <img src="<?php echo esc_url($c['image']); ?>" class="card-preview-img" style="width: 100%; height: 100%; object-fit: cover;">
                                </div>
                                <input type="hidden" name="cards[<?php echo $index; ?>][image]" value="<?php echo esc_attr($c['image']); ?>" class="card-image-input">
                                <button type="button" class="button button-small fds-upload-card-img-btn" style="width: 100%; font-size: 12px;">Ganti Foto</button>
                            </div>

                            <!-- Kolom Input Data -->
                            <div style="display: grid; gap: 12px;">
                                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                                    <div>
                                        <label style="display: block; font-size: 11px; font-weight: 600; color: #475569; margin-bottom: 2px;">Judul Solusi</label>
                                        <input type="text" name="cards[<?php echo $index; ?>][title]" value="<?php echo esc_attr($c['title']); ?>" style="width: 100%; font-size: 14px; font-weight: 600;" placeholder="Contoh: Penyemprotan & Analisis NDVI" required>
                                    </div>
                                    <div>
                                        <label style="display: block; font-size: 11px; font-weight: 600; color: #475569; margin-bottom: 2px;">Tag / Model UAV (Pojok kiri bawah)</label>
                                        <input type="text" name="cards[<?php echo $index; ?>][tag]" value="<?php echo esc_attr($c['tag'] ?? ''); ?>" style="width: 100%; font-size: 13px;" placeholder="Contoh: FERTO 5L – 50L">
                                    </div>
                                </div>

                                <div>
                                    <label style="display: block; font-size: 11px; font-weight: 600; color: #475569; margin-bottom: 2px;">Deskripsi Solusi</label>
                                    <textarea name="cards[<?php echo $index; ?>][desc]" rows="3" style="width: 100%; font-size: 12px;" placeholder="Deskripsi teknis dan manfaat solusi..."><?php echo esc_textarea($c['desc']); ?></textarea>
                                </div>

                                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                                    <div>
                                        <label style="display: block; font-size: 11px; font-weight: 600; color: #475569; margin-bottom: 2px;">Teks Tombol / Tautan</label>
                                        <input type="text" name="cards[<?php echo $index; ?>][link_text]" value="<?php echo esc_attr($c['link_text']); ?>" style="width: 100%; font-size: 12px;" placeholder="Contoh: Lihat Seri FERTO">
                                    </div>
                                    <div>
                                        <label style="display: block; font-size: 11px; font-weight: 600; color: #475569; margin-bottom: 2px;">URL Tujuan Link</label>
                                        <input type="text" name="cards[<?php echo $index; ?>][link_url]" value="<?php echo esc_attr($c['link_url']); ?>" style="width: 100%; font-size: 12px;" placeholder="Contoh: #produk atau #kontak">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- TOMBOL SIMPAN -->
            <div style="padding: 10px 0;">
                <button type="submit" name="fds_solusi_save" class="button button-primary button-large" style="background: #0066cc; border-color: #0066cc; font-size: 15px; font-weight: 600; padding: 10px 32px; border-radius: 8px; height: auto;">
                    Simpan Perubahan Solusi Industri
                </button>
            </div>
        </form>
    </div>

    <script>
    jQuery(document).ready(function($) {
        let cardIndex = <?php echo count($solusi_data['cards']); ?>;

        // Upload media per card
        $(document).on('click', '.fds-upload-card-img-btn', function(e) {
            e.preventDefault();
            let cardItem = $(this).closest('.fds-solusi-card-item');
            let imgInput = cardItem.find('.card-image-input');
            let imgPreview = cardItem.find('.card-preview-img');

            let mediaUploader = wp.media({
                title: 'Pilih Foto Solusi Industri',
                button: { text: 'Gunakan Foto Ini' },
                multiple: false
            });

            mediaUploader.on('select', function() {
                let attachment = mediaUploader.state().get('selection').first().toJSON();
                imgInput.val(attachment.url);
                imgPreview.attr('src', attachment.url);
            });

            mediaUploader.open();
        });

        // Tambah card baru
        $('#fds-add-solusi-card-btn').on('click', function() {
            let nextNum = $('.fds-solusi-card-item').length + 1;
            let html = `
            <div class="fds-solusi-card-item" style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; padding: 20px; position: relative;">
                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 14px; border-bottom: 1px solid #e2e8f0; padding-bottom: 8px;">
                    <span style="font-size: 13px; font-weight: 700; color: #0066cc;">Kartu #<span class="card-num">${nextNum}</span> &mdash; Solusi Baru</span>
                    <button type="button" class="button fds-remove-solusi-btn" style="font-size: 11px; color: #dc2626; border-color: #fecaca;">Hapus Kartu</button>
                </div>

                <div style="display: grid; grid-template-columns: 200px 1fr; gap: 20px;">
                    <div>
                        <label style="display: block; font-size: 11px; font-weight: 700; color: #475569; margin-bottom: 6px;">Foto Solusi</label>
                        <div style="width: 100%; height: 130px; border-radius: 8px; overflow: hidden; background: #1e293b; margin-bottom: 8px;">
                            <img src="https://picsum.photos/seed/fds-new-${cardIndex}/800/500" class="card-preview-img" style="width: 100%; height: 100%; object-fit: cover;">
                        </div>
                        <input type="hidden" name="cards[${cardIndex}][image]" value="https://picsum.photos/seed/fds-new-${cardIndex}/800/500" class="card-image-input">
                        <button type="button" class="button button-small fds-upload-card-img-btn" style="width: 100%; font-size: 12px;">Ganti Foto</button>
                    </div>

                    <div style="display: grid; gap: 12px;">
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                            <div>
                                <label style="display: block; font-size: 11px; font-weight: 600; color: #475569; margin-bottom: 2px;">Judul Solusi</label>
                                <input type="text" name="cards[${cardIndex}][title]" value="Judul Solusi Industri" style="width: 100%; font-size: 14px; font-weight: 600;" required>
                            </div>
                            <div>
                                <label style="display: block; font-size: 11px; font-weight: 600; color: #475569; margin-bottom: 2px;">Tag / Model UAV</label>
                                <input type="text" name="cards[${cardIndex}][tag]" value="FDS UAV" style="width: 100%; font-size: 13px;">
                            </div>
                        </div>

                        <div>
                            <label style="display: block; font-size: 11px; font-weight: 600; color: #475569; margin-bottom: 2px;">Deskripsi Solusi</label>
                            <textarea name="cards[${cardIndex}][desc]" rows="3" style="width: 100%; font-size: 12px;" placeholder="Deskripsi teknis dan manfaat solusi..."></textarea>
                        </div>

                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                            <div>
                                <label style="display: block; font-size: 11px; font-weight: 600; color: #475569; margin-bottom: 2px;">Teks Tombol / Tautan</label>
                                <input type="text" name="cards[${cardIndex}][link_text]" value="Pelajari Produk" style="width: 100%; font-size: 12px;">
                            </div>
                            <div>
                                <label style="display: block; font-size: 11px; font-weight: 600; color: #475569; margin-bottom: 2px;">URL Tujuan Link</label>
                                <input type="text" name="cards[${cardIndex}][link_url]" value="#kontak" style="width: 100%; font-size: 12px;">
                            </div>
                        </div>
                    </div>
                </div>
            </div>`;

            $('#fds-solusi-cards-container').append(html);
            cardIndex++;
        });

        // Hapus card
        $(document).on('click', '.fds-remove-solusi-btn', function() {
            if ($('.fds-solusi-card-item').length <= 1) {
                alert('Minimal harus ada 1 kartu solusi.');
                return;
            }
            if (confirm('Hapus kartu solusi ini?')) {
                $(this).closest('.fds-solusi-card-item').remove();
                $('.fds-solusi-card-item').each(function(i) {
                    $(this).find('.card-num').text(i + 1);
                });
            }
        });
    });
    </script>
    <?php
}
