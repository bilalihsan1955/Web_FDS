<?php

namespace App;

/**
 * =========================================================================
 * FDS HERO SLIDER MANAGER FOR HOMEPAGE
 * =========================================================================
 * Mengelola upload & pengaturan gambar slider hero di halaman depan (Home).
 */

// Hero slider functions and helpers

// 2. ENQUEUE WP MEDIA UPLOADER UNTUK ADMIN SLIDER
add_action('admin_enqueue_scripts', function ($hook) {
    if ($hook === 'toplevel_page_fds-hero-slider') {
        wp_enqueue_media();
    }
});

// 3. HELPER UNTUK MENGAMBIL SLIDES DI FRONTEND
function fds_get_hero_slides() {
    $slides = get_option('fds_hero_slides', []);
    
    // Filter hanya slide yang memiliki URL valid
    $valid_slides = [];
    if (is_array($slides)) {
        foreach ($slides as $s) {
            if (!empty($s['url'])) {
                $valid_slides[] = [
                    'url'   => esc_url($s['url']),
                    'title' => !empty($s['title']) ? sanitize_text_field($s['title']) : '',
                    'alt'   => !empty($s['alt']) ? sanitize_text_field($s['alt']) : 'Full Drone Solutions Hero Slide',
                ];
            }
        }
    }

    // Default fallback jika admin belum mengunggah gambar
    if (empty($valid_slides)) {
        $valid_slides = [
            [
                'url'   => 'https://picsum.photos/seed/fds-drone-industrial-hero/1920/900',
                'title' => 'Solusi Drone Industrial untuk Berbagai Sektor',
                'alt'   => 'Full Drone Solutions Industrial Drone',
            ],
            [
                'url'   => 'https://picsum.photos/seed/fds-drone-spraying-agriculture/1920/900',
                'title' => 'Teknologi Presisi Pertanian & Perkebunan',
                'alt'   => 'Drone Pertanian FERTO FDS',
            ],
            [
                'url'   => 'https://picsum.photos/seed/fds-drone-mapping-gis/1920/900',
                'title' => 'Pemetaan Topografi & Akuisisi Data Geospasial',
                'alt'   => 'Drone Pemetaan DELTAV VTOL',
            ],
        ];
    }

    return $valid_slides;
}

// 4. TAMPILAN HALAMAN WP ADMIN HERO SLIDER
function render_hero_slider_admin_page() {
    if (!current_user_can('manage_options')) {
        return;
    }

    // PROSES SIMPAN FORM
    $message = '';
    if (isset($_POST['fds_hero_slider_save']) && check_admin_referer('fds_hero_slider_nonce_action', 'fds_hero_slider_nonce')) {
        $posted_slides = $_POST['slides'] ?? [];
        $sanitized_slides = [];

        if (is_array($posted_slides)) {
            foreach ($posted_slides as $item) {
                $url   = esc_url_raw($item['url'] ?? '');
                $title = sanitize_text_field($item['title'] ?? '');
                $alt   = sanitize_text_field($item['alt'] ?? '');

                if (!empty($url)) {
                    $sanitized_slides[] = [
                        'url'   => $url,
                        'title' => $title,
                        'alt'   => $alt,
                    ];
                }
            }
        }

        update_option('fds_hero_slides', $sanitized_slides);
        $message = 'Pengaturan Hero Slider berhasil disimpan!';
    }

    $current_slides = get_option('fds_hero_slides', []);
    ?>
    <div class="wrap" style="max-width: 1000px; margin-top: 20px;">
        <h1 style="display: flex; align-items: center; gap: 10px; font-weight: 700; color: #1d1d1f; margin-bottom: 8px;">
            <span class="dashicons dashicons-images-alt2" style="font-size: 32px; width: 32px; height: 32px; color: #0066cc;"></span>
            Pengaturan Hero Slider Homepage
        </h1>
        <p style="color: #64748b; font-size: 14px; margin-bottom: 24px;">
            Unggah dan atur urutan gambar slide untuk bagian Hero Section di halaman utama (Home). Gambar akan otomatis berganti (*auto-slide*).
        </p>

        <?php if (!empty($message)): ?>
            <div class="notice notice-success is-dismissible" style="padding: 12px 16px; margin-bottom: 20px; border-left-color: #0066cc;">
                <p style="font-size: 14px; font-weight: 600; color: #0f172a; margin: 0;"><?php echo esc_html($message); ?></p>
            </div>
        <?php endif; ?>

        <form method="post" action="">
            <?php wp_nonce_field('fds_hero_slider_nonce_action', 'fds_hero_slider_nonce'); ?>

            <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 24px; box-shadow: 0 4px 12px rgba(0,0,0,0.03); margin-bottom: 24px;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; padding-bottom: 12px; border-bottom: 2px solid #f1f5f9;">
                    <h2 style="font-size: 16px; font-weight: 700; margin: 0; color: #1e293b;">Daftar Gambar Slide</h2>
                    <button type="button" id="fds-add-slide-btn" class="button button-primary" style="background: #0066cc; border-color: #0066cc; font-weight: 600; padding: 4px 16px; height: auto;">
                        + Tambah Gambar Slide
                    </button>
                </div>

                <div id="fds-slides-container" style="display: flex; flex-direction: column; gap: 16px;">
                    <?php if (empty($current_slides)): ?>
                        <div id="fds-empty-notice" style="padding: 30px; text-align: center; background: #f8fafc; border: 2px dashed #cbd5e1; border-radius: 8px; color: #64748b;">
                            <p style="margin: 0 0 10px 0; font-size: 14px;">Belum ada gambar kustom yang diunggah. Slider saat ini menggunakan gambar default.</p>
                            <button type="button" class="button fds-trigger-add" style="font-weight: 600;">Unggah Gambar Pertama</button>
                        </div>
                    <?php else: ?>
                        <?php foreach ($current_slides as $index => $slide): ?>
                            <div class="fds-slide-item" style="display: grid; grid-template-columns: 160px 1fr auto; gap: 20px; align-items: center; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 16px;">
                                <div style="width: 160px; height: 95px; border-radius: 6px; overflow: hidden; background: #000; display: flex; align-items: center; justify-content: center; position: relative;">
                                    <img src="<?php echo esc_url($slide['url']); ?>" class="fds-slide-preview" style="width: 100%; height: 100%; object-fit: cover;">
                                </div>
                                <div style="display: grid; gap: 8px;">
                                    <div>
                                        <label style="font-size: 11px; font-weight: 700; color: #475569; display: block; margin-bottom: 3px;">URL Gambar</label>
                                        <input type="text" name="slides[<?php echo $index; ?>][url]" value="<?php echo esc_attr($slide['url']); ?>" class="fds-slide-url-input regular-text" style="width: 100%; font-size: 13px;" required>
                                    </div>
                                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
                                        <div>
                                            <label style="font-size: 11px; font-weight: 600; color: #64748b; display: block; margin-bottom: 2px;">Judul / Keterangan (Opsional)</label>
                                            <input type="text" name="slides[<?php echo $index; ?>][title]" value="<?php echo esc_attr($slide['title'] ?? ''); ?>" style="width: 100%; font-size: 12px;" placeholder="Contoh: Solusi Drone Pertanian">
                                        </div>
                                        <div>
                                            <label style="font-size: 11px; font-weight: 600; color: #64748b; display: block; margin-bottom: 2px;">Alt Text (SEO)</label>
                                            <input type="text" name="slides[<?php echo $index; ?>][alt]" value="<?php echo esc_attr($slide['alt'] ?? ''); ?>" style="width: 100%; font-size: 12px;" placeholder="Deskripsi gambar untuk SEO">
                                        </div>
                                    </div>
                                </div>
                                <div style="display: flex; flex-direction: column; gap: 6px;">
                                    <button type="button" class="button fds-change-img-btn" style="font-size: 12px;">Ganti</button>
                                    <button type="button" class="button fds-remove-slide-btn" style="font-size: 12px; color: #dc2626; border-color: #fecaca;">Hapus</button>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>

            <div style="display: flex; gap: 12px; align-items: center;">
                <input type="submit" name="fds_hero_slider_save" class="button button-primary button-large" value="Simpan Perubahan Slider" style="background: #0066cc; border-color: #0066cc; font-weight: 600; padding: 0 24px;">
                <span style="font-size: 13px; color: #64748b;">Perubahan akan langsung tampil di halaman depan.</span>
            </div>
        </form>
    </div>

    <!-- JAVASCRIPT WP MEDIA UPLOADER -->
    <script>
    jQuery(document).ready(function($){
        let slideIndex = <?php echo count($current_slides); ?>;

        function attachMediaUploader(button, targetInput, targetImg) {
            let mediaUploader = wp.media({
                title: 'Pilih Gambar Hero Slide',
                button: { text: 'Gunakan Gambar Ini' },
                multiple: false
            });

            mediaUploader.on('select', function() {
                let attachment = mediaUploader.state().get('selection').first().toJSON();
                targetInput.val(attachment.url);
                if (targetImg) {
                    targetImg.attr('src', attachment.url);
                }
            });

            mediaUploader.open();
        }

        // Tambah slide baru
        $('#fds-add-slide-btn, .fds-trigger-add').on('click', function(e) {
            e.preventDefault();

            let mediaUploader = wp.media({
                title: 'Pilih Gambar Hero Slide',
                button: { text: 'Tambahkan ke Slider' },
                multiple: true
            });

            mediaUploader.on('select', function() {
                let selection = mediaUploader.state().get('selection');
                $('#fds-empty-notice').remove();

                selection.each(function(attachment) {
                    attachment = attachment.toJSON();
                    let itemHtml = `
                    <div class="fds-slide-item" style="display: grid; grid-template-columns: 160px 1fr auto; gap: 20px; align-items: center; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 16px;">
                        <div style="width: 160px; height: 95px; border-radius: 6px; overflow: hidden; background: #000; display: flex; align-items: center; justify-content: center;">
                            <img src="${attachment.url}" class="fds-slide-preview" style="width: 100%; height: 100%; object-fit: cover;">
                        </div>
                        <div style="display: grid; gap: 8px;">
                            <div>
                                <label style="font-size: 11px; font-weight: 700; color: #475569; display: block; margin-bottom: 3px;">URL Gambar</label>
                                <input type="text" name="slides[${slideIndex}][url]" value="${attachment.url}" class="fds-slide-url-input regular-text" style="width: 100%; font-size: 13px;" required>
                            </div>
                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
                                <div>
                                    <label style="font-size: 11px; font-weight: 600; color: #64748b; display: block; margin-bottom: 2px;">Judul / Keterangan (Opsional)</label>
                                    <input type="text" name="slides[${slideIndex}][title]" value="${attachment.title || ''}" style="width: 100%; font-size: 12px;" placeholder="Contoh: Solusi Drone Pertanian">
                                </div>
                                <div>
                                    <label style="font-size: 11px; font-weight: 600; color: #64748b; display: block; margin-bottom: 2px;">Alt Text (SEO)</label>
                                    <input type="text" name="slides[${slideIndex}][alt]" value="${attachment.alt || ''}" style="width: 100%; font-size: 12px;" placeholder="Deskripsi gambar untuk SEO">
                                </div>
                            </div>
                        </div>
                        <div style="display: flex; flex-direction: column; gap: 6px;">
                            <button type="button" class="button fds-change-img-btn" style="font-size: 12px;">Ganti</button>
                            <button type="button" class="button fds-remove-slide-btn" style="font-size: 12px; color: #dc2626; border-color: #fecaca;">Hapus</button>
                        </div>
                    </div>`;

                    $('#fds-slides-container').append(itemHtml);
                    slideIndex++;
                });
            });

            mediaUploader.open();
        });

        // Ganti gambar
        $(document).on('click', '.fds-change-img-btn', function() {
            let row = $(this).closest('.fds-slide-item');
            let urlInput = row.find('.fds-slide-url-input');
            let previewImg = row.find('.fds-slide-preview');
            attachMediaUploader($(this), urlInput, previewImg);
        });

        // Hapus slide
        $(document).on('click', '.fds-remove-slide-btn', function() {
            if (confirm('Hapus slide ini?')) {
                $(this).closest('.fds-slide-item').remove();
            }
        });
    });
    </script>
    <?php
}
