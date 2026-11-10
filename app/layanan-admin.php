<?php

namespace App;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * =========================================================================
 * FDS KONTEN LAYANAN ADMIN PANEL (NON-DESTRUCTIVE OVERRIDE EDITOR)
 * =========================================================================
 * Mengelola seluruh konten editable untuk:
 * - Hub Layanan (/layanan/)
 * - 6 Halaman Sektor Industri (/layanan/{sektor}/)
 * - 3 Halaman Kategori Layanan (/layanan/{kategori}/)
 * - Teks UI Bersama, Form Penawaran, Mega Menu, Footer Layanan & /terima-kasih/
 *
 * Setiap tab menyimpan satu option terisolasi tanpa menyentuh halaman lain,
 * dan menyediakan tombol Reset khusus halaman aktif ke default bawaan tema.
 */

// 1. DAFTARKAN MENU DI WP ADMIN
add_action('admin_menu', function () {
    add_menu_page(
        'Konten Layanan',
        'Konten Layanan',
        'manage_options',
        'fds-layanan-content',
        __NAMESPACE__ . '\\render_layanan_content_admin_page',
        'dashicons-networking',
        26
    );
});

// 2. ENQUEUE WP MEDIA UPLOADER UNTUK GAMBAR SEKTOR
add_action('admin_enqueue_scripts', function ($hook) {
    if ($hook === 'toplevel_page_fds-layanan-content') {
        wp_enqueue_media();
    }
});

// 3. METABOX PINTASAN DI EDITOR HALAMAN WP PAGES
add_action('add_meta_boxes', function () {
    add_meta_box(
        'fds_layanan_editor_shortcut',
        'Pengelolaan Konten Layanan FDS',
        __NAMESPACE__ . '\\render_layanan_page_shortcut_metabox',
        'page',
        'side',
        'high'
    );
});

function fds_detect_layanan_tab_for_post($post): string {
    if (!$post || empty($post->post_name)) {
        return '';
    }
    $slug = (string) $post->post_name;
    if ($slug === 'layanan') {
        return 'hub';
    }
    if ($slug === 'terima-kasih') {
        return 'ui';
    }
    $sectors    = function_exists(__NAMESPACE__ . '\\fds_get_layanan_sectors') ? fds_get_layanan_sectors() : [];
    $categories = function_exists(__NAMESPACE__ . '\\fds_get_layanan_categories') ? fds_get_layanan_categories() : [];
    if (isset($sectors[$slug])) {
        return 'sektor-' . $slug;
    }
    if (isset($categories[$slug])) {
        return 'kat-' . $slug;
    }
    return '';
}

function render_layanan_page_shortcut_metabox($post) {
    $tab = fds_detect_layanan_tab_for_post($post);
    if ($tab === '') {
        echo '<p style="margin:0;color:#64748b;font-size:12px;">Halaman ini tidak terhubung dengan template Konten Layanan. Pengaturan Beranda ada di menu <strong>Konten Beranda</strong>.</p>';
        return;
    }
    $url = admin_url('admin.php?page=fds-layanan-content&tab=' . rawurlencode($tab));
    ?>
    <p style="margin:0 0 10px;font-size:12.5px;color:#334155;line-height:1.5;">
        Seluruh teks, tabel metode, deliverables, FAQ, dan pengaturan form halaman ini dikelola terpusat pada panel <strong>Konten Layanan</strong> agar sinkron dengan Mega Menu &amp; JSON-LD SEO.
    </p>
    <a href="<?php echo esc_url($url); ?>" class="button button-primary" style="background:#0f808a;border-color:#0f808a;width:100%;text-align:center;font-weight:600;">
        Buka Editor Konten Layanan &rarr;
    </a>
    <?php
}

// 4. PARSING HELPERS (SELALU UNSLASH + SANITIZE)
function fds_admin_unslash_text($val): string {
    return sanitize_text_field(wp_unslash((string) $val));
}

function fds_admin_unslash_textarea($val): string {
    return sanitize_textarea_field(wp_unslash((string) $val));
}

function fds_admin_parse_lines($raw): array {
    $text  = fds_admin_unslash_textarea($raw);
    $lines = preg_split('/\R/u', $text) ?: [];
    $out   = [];
    foreach ($lines as $line) {
        $clean = trim($line);
        if ($clean !== '') {
            $out[] = $clean;
        }
    }
    return $out;
}

function fds_admin_parse_method_comparison(array $post): array {
    $cols_raw = fds_admin_unslash_text($post['mc_columns'] ?? '');
    $cols     = [];
    if ($cols_raw !== '') {
        foreach (explode('|', $cols_raw) as $col) {
            $c = trim($col);
            if ($c !== '') {
                $cols[] = $c;
            }
        }
    }

    $rows      = [];
    $rows_raw  = fds_admin_unslash_textarea($post['mc_rows'] ?? '');
    $row_lines = preg_split('/\R/u', $rows_raw) ?: [];
    $col_count = count($cols);
    foreach ($row_lines as $rline) {
        $trimmed = trim($rline);
        if ($trimmed === '') {
            continue;
        }
        $cells = array_map('trim', explode('|', $trimmed));
        if ($col_count > 0) {
            if (count($cells) < $col_count) {
                $cells = array_pad($cells, $col_count, '');
            } elseif (count($cells) > $col_count) {
                $cells = array_slice($cells, 0, $col_count);
            }
        }
        $rows[] = $cells;
    }

    return [
        'eyebrow'  => fds_admin_unslash_text($post['mc_eyebrow'] ?? ''),
        'title'    => fds_admin_unslash_text($post['mc_title'] ?? ''),
        'subtitle' => fds_admin_unslash_textarea($post['mc_subtitle'] ?? ''),
        'columns'  => $cols,
        'rows'     => $rows,
    ];
}

function fds_admin_parse_repeater_pairs($titles, $descs, string $k1 = 'title', string $k2 = 'desc'): array {
    if (!is_array($titles)) {
        return [];
    }
    $out = [];
    foreach ($titles as $idx => $t_raw) {
        $v1 = fds_admin_unslash_text($t_raw);
        $v2 = is_array($descs) ? fds_admin_unslash_textarea($descs[$idx] ?? '') : '';
        if ($v1 === '' && $v2 === '') {
            continue;
        }
        $out[] = [
            $k1 => $v1,
            $k2 => $v2,
        ];
    }
    return $out;
}

function fds_admin_parse_workflow_steps($steps, $titles, $descs): array {
    if (!is_array($titles)) {
        return [];
    }
    $out = [];
    $num = 1;
    foreach ($titles as $idx => $t_raw) {
        $step_no = is_array($steps) ? fds_admin_unslash_text($steps[$idx] ?? '') : '';
        $title   = fds_admin_unslash_text($t_raw);
        $desc    = is_array($descs) ? fds_admin_unslash_textarea($descs[$idx] ?? '') : '';
        if ($title === '' && $desc === '') {
            continue;
        }
        if ($step_no === '') {
            $step_no = str_pad((string) $num, 2, '0', STR_PAD_LEFT);
        }
        $out[] = [
            'step'  => $step_no,
            'title' => $title,
            'desc'  => $desc,
        ];
        $num++;
    }
    return $out;
}

function fds_admin_parse_reading_links($labels, $urls, $slugs): array {
    if (!is_array($labels)) {
        return [];
    }
    $out = [];
    foreach ($labels as $idx => $lbl_raw) {
        $lbl  = fds_admin_unslash_text($lbl_raw);
        $url  = is_array($urls) ? fds_sanitize_service_url((string) ($urls[$idx] ?? '')) : '';
        $slug = is_array($slugs) ? sanitize_title(wp_unslash((string) ($slugs[$idx] ?? ''))) : '';
        if ($lbl === '' && $url === '' && $slug === '') {
            continue;
        }
        $entry = ['label' => $lbl];
        if ($url !== '') {
            $entry['url'] = $url;
        }
        if ($slug !== '') {
            $entry['slug'] = $slug;
        }
        $out[] = $entry;
    }
    return $out;
}

function fds_admin_parse_card_tags($raw): array {
    $lines = fds_admin_parse_lines($raw);
    $out   = [];
    foreach ($lines as $line) {
        $parts = array_map('trim', explode('|', $line, 2));
        $label = sanitize_text_field($parts[0] ?? '');
        if ($label === '') {
            continue;
        }
        $url   = isset($parts[1]) ? fds_sanitize_service_url($parts[1]) : '';
        $out[] = ['label' => $label, 'url' => $url];
    }
    return $out;
}

// 5. RESOLVE ACTIVE TAB MAP & OPTION KEY
function fds_get_layanan_admin_tabs(): array {
    $tabs = [
        'hub' => ['kind' => 'hub', 'slug' => 'layanan', 'label' => 'Hub Layanan', 'option' => 'fds_layanan_hub_overrides_v1'],
    ];
    foreach (fds_get_layanan_sectors() as $slug => $sec) {
        $tabs['sektor-' . $slug] = [
            'kind'   => 'sektor',
            'slug'   => $slug,
            'label'  => ($sec['num'] ?? '') . '. ' . ($sec['card_title'] ?? ucfirst($slug)),
            'option' => 'fds_layanan_sector_overrides_' . $slug,
        ];
    }
    foreach (fds_get_layanan_categories() as $slug => $cat) {
        $tabs['kat-' . $slug] = [
            'kind'   => 'kategori',
            'slug'   => $slug,
            'label'  => $cat['short_title'] ?? ucfirst($slug),
            'option' => 'fds_layanan_cat_overrides_' . $slug,
        ];
    }
    $tabs['ui'] = ['kind' => 'ui', 'slug' => 'ui', 'label' => 'UI Bersama & Terima Kasih', 'option' => 'fds_layanan_ui_overrides_v1'];
    return $tabs;
}

// 6. HANDLE POST SAVE & RESET PER TAB AKTIF
function fds_handle_layanan_admin_post(string $active_tab, array $tabs): array {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        return ['message' => '', 'error' => ''];
    }
    if (!current_user_can('manage_options')) {
        return ['message' => '', 'error' => 'Akses ditolak.'];
    }
    if (!isset($_POST['fds_layanan_nonce']) || !check_admin_referer('fds_layanan_admin_nonce', 'fds_layanan_nonce')) {
        return ['message' => '', 'error' => 'Token keamanan tidak valid. Muat ulang halaman lalu coba lagi.'];
    }

    $post_tab = sanitize_key((string) wp_unslash($_POST['fds_layanan_tab'] ?? $active_tab));
    if (!isset($tabs[$post_tab])) {
        return ['message' => '', 'error' => 'Tab tujuan tidak dikenal.'];
    }
    $meta = $tabs[$post_tab];

    if (isset($_POST['fds_layanan_reset'])) {
        delete_option($meta['option']);
        fds_flush_layanan_cache();
        return ['message' => 'Halaman "' . $meta['label'] . '" dikembalikan ke konten default bawaan tema.', 'error' => ''];
    }

    if (!isset($_POST['fds_layanan_save'])) {
        return ['message' => '', 'error' => ''];
    }

    if ($meta['kind'] === 'hub') {
        $override = [
            'eyebrow'            => fds_admin_unslash_text($_POST['eyebrow'] ?? ''),
            'h1'                 => fds_admin_unslash_text($_POST['h1'] ?? ''),
            'subtitle'           => fds_admin_unslash_textarea($_POST['subtitle'] ?? ''),
            'hero_summary'       => fds_admin_unslash_textarea($_POST['hero_summary'] ?? ''),
            'cta_primary_text'   => fds_admin_unslash_text($_POST['cta_primary_text'] ?? ''),
            'cta_primary_url'    => fds_sanitize_service_url((string) ($_POST['cta_primary_url'] ?? '')),
            'cta_secondary_text' => fds_admin_unslash_text($_POST['cta_secondary_text'] ?? ''),
            'cta_secondary_url'  => fds_sanitize_service_url((string) ($_POST['cta_secondary_url'] ?? '')),
            'intro_paragraphs'   => fds_admin_parse_lines($_POST['intro_paragraphs'] ?? ''),
            'challenge_section'  => [
                'eyebrow'     => fds_admin_unslash_text($_POST['ch_eyebrow'] ?? ''),
                'title'       => fds_admin_unslash_text($_POST['ch_title'] ?? ''),
                'paragraphs'  => fds_admin_parse_lines($_POST['ch_paragraphs'] ?? ''),
                'pain_points' => fds_admin_parse_repeater_pairs($_POST['pp_title'] ?? [], $_POST['pp_desc'] ?? [], 'title', 'desc'),
            ],
            'why_fds' => [
                'title' => fds_admin_unslash_text($_POST['why_title'] ?? ''),
                'desc'  => fds_admin_unslash_textarea($_POST['why_desc'] ?? ''),
                'items' => fds_admin_parse_repeater_pairs($_POST['why_item_title'] ?? [], $_POST['why_item_desc'] ?? [], 'title', 'desc'),
            ],
            'method_comparison' => fds_admin_parse_method_comparison($_POST),
            'workflow' => [
                'title' => fds_admin_unslash_text($_POST['wf_title'] ?? ''),
                'desc'  => fds_admin_unslash_textarea($_POST['wf_desc'] ?? ''),
                'steps' => fds_admin_parse_workflow_steps($_POST['wf_step'] ?? [], $_POST['wf_step_title'] ?? [], $_POST['wf_step_desc'] ?? []),
            ],
            'cost_framework' => [
                'eyebrow'       => fds_admin_unslash_text($_POST['cf_eyebrow'] ?? ''),
                'title'         => fds_admin_unslash_text($_POST['cf_title'] ?? ''),
                'intro'         => fds_admin_unslash_textarea($_POST['cf_intro'] ?? ''),
                'factors'       => fds_admin_parse_repeater_pairs($_POST['cf_factor_name'] ?? [], $_POST['cf_factor_desc'] ?? [], 'name', 'desc'),
                'kak_checklist' => fds_admin_parse_lines($_POST['cf_kak_checklist'] ?? ''),
            ],
            'eeat_proof' => [
                'eyebrow'        => fds_admin_unslash_text($_POST['ep_eyebrow'] ?? ''),
                'title'          => fds_admin_unslash_text($_POST['ep_title'] ?? ''),
                'intro'          => fds_admin_unslash_textarea($_POST['ep_intro'] ?? ''),
                'bullets'        => fds_admin_parse_lines($_POST['ep_bullets'] ?? ''),
                'portfolio_note' => fds_admin_unslash_textarea($_POST['ep_portfolio_note'] ?? ''),
            ],
            'reading_links' => fds_admin_parse_reading_links($_POST['rl_label'] ?? [], $_POST['rl_url'] ?? [], $_POST['rl_slug'] ?? []),
            'faqs'          => fds_admin_parse_repeater_pairs($_POST['faq_q'] ?? [], $_POST['faq_a'] ?? [], 'q', 'a'),
            'quote_defaults' => [
                'eyebrow'     => fds_admin_unslash_text($_POST['qd_eyebrow'] ?? ''),
                'title'       => fds_admin_unslash_text($_POST['qd_title'] ?? ''),
                'subtitle'    => fds_admin_unslash_textarea($_POST['qd_subtitle'] ?? ''),
                'wa_prefill'  => fds_admin_unslash_textarea($_POST['qd_wa_prefill'] ?? ''),
                'placeholder' => fds_admin_unslash_textarea($_POST['qd_placeholder'] ?? ''),
            ],
            'seo' => [
                'meta_title' => fds_admin_unslash_text($_POST['seo_meta_title'] ?? ''),
                'meta_desc'  => fds_admin_unslash_textarea($_POST['seo_meta_desc'] ?? ''),
            ],
        ];
        update_option($meta['option'], $override, 'no');
    } elseif ($meta['kind'] === 'sektor') {
        $solutions = [];
        $sol_names = $_POST['sol_name'] ?? [];
        if (is_array($sol_names)) {
            foreach ($sol_names as $idx => $sn_raw) {
                $sn = fds_admin_unslash_text($sn_raw);
                $sd = fds_admin_unslash_textarea($_POST['sol_desc'][$idx] ?? '');
                if ($sn === '' && $sd === '') {
                    continue;
                }
                $solutions[] = [
                    'name'       => $sn,
                    'desc'       => $sd,
                    'link_url'   => fds_sanitize_service_url((string) ($_POST['sol_url'][$idx] ?? '')),
                    'link_label' => fds_admin_unslash_text($_POST['sol_label'][$idx] ?? ''),
                ];
            }
        }

        $products_tech = [];
        $pt_names      = $_POST['pt_name'] ?? [];
        if (is_array($pt_names)) {
            foreach ($pt_names as $idx => $ptn_raw) {
                $ptn = fds_admin_unslash_text($ptn_raw);
                if ($ptn === '') {
                    continue;
                }
                $pt_item = [
                    'name' => $ptn,
                    'url'  => fds_sanitize_service_url((string) ($_POST['pt_url'][$idx] ?? '')),
                ];
                $note = fds_admin_unslash_textarea($_POST['pt_note'][$idx] ?? '');
                if ($note !== '') {
                    $pt_item['note'] = $note;
                }
                $sub_lines = fds_admin_parse_lines($_POST['pt_sublinks'][$idx] ?? '');
                $sublinks  = [];
                foreach ($sub_lines as $sline) {
                    $sp    = array_map('trim', explode('|', $sline, 2));
                    $s_lbl = sanitize_text_field($sp[0] ?? '');
                    $s_url = isset($sp[1]) ? fds_sanitize_service_url($sp[1]) : '';
                    if ($s_lbl !== '' && $s_url !== '') {
                        $sublinks[] = ['label' => $s_lbl, 'url' => $s_url];
                    }
                }
                if (!empty($sublinks)) {
                    $pt_item['sublinks'] = $sublinks;
                }
                $products_tech[] = $pt_item;
            }
        }

        $deliverable_groups = [];
        $dg_titles          = $_POST['dg_title'] ?? [];
        if (is_array($dg_titles)) {
            foreach ($dg_titles as $idx => $dgt_raw) {
                $dgt   = fds_admin_unslash_text($dgt_raw);
                $items = fds_admin_parse_lines($_POST['dg_items'][$idx] ?? '');
                if ($dgt === '' && empty($items)) {
                    continue;
                }
                $deliverable_groups[] = [
                    'title' => $dgt,
                    'items' => $items,
                ];
            }
        }

        $rel_caps  = [];
        $rc_titles = $_POST['rc_title'] ?? [];
        if (is_array($rc_titles)) {
            foreach ($rc_titles as $idx => $rct_raw) {
                $rct = fds_admin_unslash_text($rct_raw);
                $rcd = fds_admin_unslash_textarea($_POST['rc_desc'][$idx] ?? '');
                $rcu = fds_sanitize_service_url((string) ($_POST['rc_url'][$idx] ?? ''));
                if ($rct === '' && $rcd === '') {
                    continue;
                }
                $rel_caps[] = ['title' => $rct, 'desc' => $rcd, 'url' => $rcu];
            }
        }

        $override = [
            'num'               => fds_admin_unslash_text($_POST['num'] ?? ''),
            'card_title'        => fds_admin_unslash_text($_POST['card_title'] ?? ''),
            'card_desc'         => fds_admin_unslash_textarea($_POST['card_desc'] ?? ''),
            'card_tags'         => fds_admin_parse_card_tags($_POST['card_tags'] ?? ''),
            'home_tag'          => fds_admin_unslash_text($_POST['home_tag'] ?? ''),
            'image'             => esc_url_raw(wp_unslash((string) ($_POST['image'] ?? ''))),
            'image_alt'         => fds_admin_unslash_text($_POST['image_alt'] ?? ''),
            'h1'                => fds_admin_unslash_text($_POST['h1'] ?? ''),
            'subtitle'          => fds_admin_unslash_text($_POST['subtitle'] ?? ''),
            'hero_summary'      => fds_admin_unslash_textarea($_POST['hero_summary'] ?? ''),
            'intro_paragraphs'  => fds_admin_parse_lines($_POST['intro_paragraphs'] ?? ''),
            'challenge_section' => [
                'eyebrow'     => fds_admin_unslash_text($_POST['ch_eyebrow'] ?? ''),
                'title'       => fds_admin_unslash_text($_POST['ch_title'] ?? ''),
                'paragraphs'  => fds_admin_parse_lines($_POST['ch_paragraphs'] ?? ''),
                'pain_points' => fds_admin_parse_repeater_pairs($_POST['pp_title'] ?? [], $_POST['pp_desc'] ?? [], 'title', 'desc'),
            ],
            'solutions'          => $solutions,
            'products_tech'      => $products_tech,
            'work_outputs'       => fds_admin_unslash_textarea($_POST['work_outputs'] ?? ''),
            'closing_paragraphs' => fds_admin_parse_lines($_POST['closing_paragraphs'] ?? ''),
            'method_comparison'  => fds_admin_parse_method_comparison($_POST),
            'deliverable_groups' => $deliverable_groups,
            'workflow_steps'     => fds_admin_parse_workflow_steps($_POST['wf_step'] ?? [], $_POST['wf_step_title'] ?? [], $_POST['wf_step_desc'] ?? []),
            'software_integrations' => [
                'eyebrow'  => fds_admin_unslash_text($_POST['si_eyebrow'] ?? ''),
                'title'    => fds_admin_unslash_text($_POST['si_title'] ?? ''),
                'subtitle' => fds_admin_unslash_textarea($_POST['si_subtitle'] ?? ''),
                'formats'  => fds_admin_parse_lines($_POST['si_formats'] ?? ''),
                'tools'    => fds_admin_parse_lines($_POST['si_tools'] ?? ''),
            ],
            'cost_framework' => [
                'eyebrow'       => fds_admin_unslash_text($_POST['cf_eyebrow'] ?? ''),
                'title'         => fds_admin_unslash_text($_POST['cf_title'] ?? ''),
                'intro'         => fds_admin_unslash_textarea($_POST['cf_intro'] ?? ''),
                'factors'       => fds_admin_parse_repeater_pairs($_POST['cf_factor_name'] ?? [], $_POST['cf_factor_desc'] ?? [], 'name', 'desc'),
                'kak_checklist' => fds_admin_parse_lines($_POST['cf_kak_checklist'] ?? ''),
            ],
            'eeat_proof' => [
                'eyebrow'        => fds_admin_unslash_text($_POST['ep_eyebrow'] ?? ''),
                'title'          => fds_admin_unslash_text($_POST['ep_title'] ?? ''),
                'intro'          => fds_admin_unslash_textarea($_POST['ep_intro'] ?? ''),
                'bullets'        => fds_admin_parse_lines($_POST['ep_bullets'] ?? ''),
                'portfolio_note' => fds_admin_unslash_textarea($_POST['ep_portfolio_note'] ?? ''),
            ],
            'related_capabilities' => $rel_caps,
            'reading_links'        => fds_admin_parse_reading_links($_POST['rl_label'] ?? [], $_POST['rl_url'] ?? [], $_POST['rl_slug'] ?? []),
            'faqs'                 => fds_admin_parse_repeater_pairs($_POST['faq_q'] ?? [], $_POST['faq_a'] ?? [], 'q', 'a'),
            'quote_defaults'       => [
                'eyebrow'     => fds_admin_unslash_text($_POST['qd_eyebrow'] ?? ''),
                'title'       => fds_admin_unslash_text($_POST['qd_title'] ?? ''),
                'subtitle'    => fds_admin_unslash_textarea($_POST['qd_subtitle'] ?? ''),
                'wa_prefill'  => fds_admin_unslash_textarea($_POST['qd_wa_prefill'] ?? ''),
                'placeholder' => fds_admin_unslash_textarea($_POST['qd_placeholder'] ?? ''),
            ],
            'seo' => [
                'meta_title'   => fds_admin_unslash_text($_POST['seo_meta_title'] ?? ''),
                'meta_desc'    => fds_admin_unslash_textarea($_POST['seo_meta_desc'] ?? ''),
                'service_type' => fds_admin_unslash_text($_POST['seo_service_type'] ?? ''),
            ],
        ];
        update_option($meta['option'], $override, 'no');
    } elseif ($meta['kind'] === 'kategori') {
        $sector_links_raw = $_POST['sector_links'] ?? [];
        $valid_sectors    = array_keys(fds_get_layanan_sectors());
        $sector_links     = [];
        if (is_array($sector_links_raw)) {
            foreach ($sector_links_raw as $sl) {
                $clean_sl = sanitize_key((string) wp_unslash($sl));
                if (in_array($clean_sl, $valid_sectors, true)) {
                    $sector_links[] = $clean_sl;
                }
            }
        }

        $override = [
            'short_title'      => fds_admin_unslash_text($_POST['short_title'] ?? ''),
            'h1'               => fds_admin_unslash_text($_POST['h1'] ?? ''),
            'subtitle'         => fds_admin_unslash_text($_POST['subtitle'] ?? ''),
            'hero_summary'     => fds_admin_unslash_textarea($_POST['hero_summary'] ?? ''),
            'intro_paragraphs' => fds_admin_parse_lines($_POST['intro_paragraphs'] ?? ''),
            'challenge_section'=> [
                'eyebrow'     => fds_admin_unslash_text($_POST['ch_eyebrow'] ?? ''),
                'title'       => fds_admin_unslash_text($_POST['ch_title'] ?? ''),
                'paragraphs'  => fds_admin_parse_lines($_POST['ch_paragraphs'] ?? ''),
                'pain_points' => fds_admin_parse_repeater_pairs($_POST['pp_title'] ?? [], $_POST['pp_desc'] ?? [], 'title', 'desc'),
            ],
            'capabilities'     => fds_admin_parse_repeater_pairs($_POST['cap_name'] ?? [], $_POST['cap_desc'] ?? [], 'name', 'desc'),
            'method_comparison'=> fds_admin_parse_method_comparison($_POST),
            'workflow_steps'   => fds_admin_parse_workflow_steps($_POST['wf_step'] ?? [], $_POST['wf_step_title'] ?? [], $_POST['wf_step_desc'] ?? []),
            'software_integrations' => [
                'eyebrow'  => fds_admin_unslash_text($_POST['si_eyebrow'] ?? ''),
                'title'    => fds_admin_unslash_text($_POST['si_title'] ?? ''),
                'subtitle' => fds_admin_unslash_textarea($_POST['si_subtitle'] ?? ''),
                'formats'  => fds_admin_parse_lines($_POST['si_formats'] ?? ''),
                'tools'    => fds_admin_parse_lines($_POST['si_tools'] ?? ''),
            ],
            'outputs'          => fds_admin_parse_lines($_POST['outputs'] ?? ''),
            'sector_links'     => $sector_links,
            'cost_framework'   => [
                'eyebrow'       => fds_admin_unslash_text($_POST['cf_eyebrow'] ?? ''),
                'title'         => fds_admin_unslash_text($_POST['cf_title'] ?? ''),
                'intro'         => fds_admin_unslash_textarea($_POST['cf_intro'] ?? ''),
                'factors'       => fds_admin_parse_repeater_pairs($_POST['cf_factor_name'] ?? [], $_POST['cf_factor_desc'] ?? [], 'name', 'desc'),
                'kak_checklist' => fds_admin_parse_lines($_POST['cf_kak_checklist'] ?? ''),
            ],
            'eeat_proof' => [
                'eyebrow'        => fds_admin_unslash_text($_POST['ep_eyebrow'] ?? ''),
                'title'          => fds_admin_unslash_text($_POST['ep_title'] ?? ''),
                'intro'          => fds_admin_unslash_textarea($_POST['ep_intro'] ?? ''),
                'bullets'        => fds_admin_parse_lines($_POST['ep_bullets'] ?? ''),
                'portfolio_note' => fds_admin_unslash_textarea($_POST['ep_portfolio_note'] ?? ''),
            ],
            'reading_links'  => fds_admin_parse_reading_links($_POST['rl_label'] ?? [], $_POST['rl_url'] ?? [], $_POST['rl_slug'] ?? []),
            'faqs'           => fds_admin_parse_repeater_pairs($_POST['faq_q'] ?? [], $_POST['faq_a'] ?? [], 'q', 'a'),
            'quote_defaults' => [
                'eyebrow'     => fds_admin_unslash_text($_POST['qd_eyebrow'] ?? ''),
                'title'       => fds_admin_unslash_text($_POST['qd_title'] ?? ''),
                'subtitle'    => fds_admin_unslash_textarea($_POST['qd_subtitle'] ?? ''),
                'wa_prefill'  => fds_admin_unslash_textarea($_POST['qd_wa_prefill'] ?? ''),
                'placeholder' => fds_admin_unslash_textarea($_POST['qd_placeholder'] ?? ''),
            ],
            'seo' => [
                'meta_title'   => fds_admin_unslash_text($_POST['seo_meta_title'] ?? ''),
                'meta_desc'    => fds_admin_unslash_textarea($_POST['seo_meta_desc'] ?? ''),
                'service_type' => fds_admin_unslash_text($_POST['seo_service_type'] ?? ''),
            ],
        ];
        update_option($meta['option'], $override, 'no');
    } elseif ($meta['kind'] === 'ui') {
        $defaults  = fds_get_default_layanan_ui_texts();
        $ui_save   = [];
        $submitted = $_POST['ui'] ?? [];
        if (is_array($submitted)) {
            foreach ($defaults as $k => $def_val) {
                $raw_v       = (string) ($submitted[$k] ?? $def_val);
                $ui_save[$k] = str_contains((string) $k, 'subtitle') || str_contains((string) $k, '_desc')
                    ? fds_admin_unslash_textarea($raw_v)
                    : fds_admin_unslash_text($raw_v);
            }
        }
        update_option($meta['option'], $ui_save, 'no');
    }

    fds_flush_layanan_cache();
    return ['message' => 'Perubahan untuk "' . $meta['label'] . '" berhasil disimpan dan disinkronkan!', 'error' => ''];
}

// 7. SHARED SECTION RENDER HELPERS FOR ADMIN PAGE
function fds_render_shared_seo_quote_fields(array $d, bool $include_service_type): void {
    $qd  = $d['quote_defaults'] ?? [];
    $seo = $d['seo'] ?? [];
    ?>
    <div class="fds-card">
        <h3>SEO Meta Tags &amp; Schema</h3>
        <div class="fds-grid-2">
            <div class="fds-field">
                <label>Meta Title (&lt;title&gt; Fallback / Rank Math)</label>
                <input type="text" name="seo_meta_title" value="<?php echo esc_attr($seo['meta_title'] ?? ''); ?>">
            </div>
            <?php if ($include_service_type): ?>
                <div class="fds-field">
                    <label>Schema.org <code>serviceType</code></label>
                    <input type="text" name="seo_service_type" value="<?php echo esc_attr($seo['service_type'] ?? ''); ?>">
                </div>
            <?php endif; ?>
        </div>
        <div class="fds-field">
            <label>Meta Description</label>
            <textarea name="seo_meta_desc" rows="2"><?php echo esc_textarea($seo['meta_desc'] ?? ''); ?></textarea>
        </div>
    </div>

    <div class="fds-card">
        <h3>Form Penawaran &amp; Prefill WhatsApp Halaman Ini</h3>
        <div class="fds-grid-2">
            <div class="fds-field">
                <label>Eyebrow Form Penawaran</label>
                <input type="text" name="qd_eyebrow" value="<?php echo esc_attr($qd['eyebrow'] ?? ''); ?>">
            </div>
            <div class="fds-field">
                <label>Judul Form Penawaran</label>
                <input type="text" name="qd_title" value="<?php echo esc_attr($qd['title'] ?? ''); ?>">
            </div>
        </div>
        <div class="fds-field">
            <label>Subjudul Form Penawaran</label>
            <textarea name="qd_subtitle" rows="2"><?php echo esc_textarea($qd['subtitle'] ?? ''); ?></textarea>
        </div>
        <div class="fds-grid-2">
            <div class="fds-field">
                <label>Pesan Otomatis WhatsApp (<code>wa_prefill</code>)</label>
                <textarea name="qd_wa_prefill" rows="2"><?php echo esc_textarea($qd['wa_prefill'] ?? ''); ?></textarea>
            </div>
            <div class="fds-field">
                <label>Placeholder Textarea Form</label>
                <textarea name="qd_placeholder" rows="2"><?php echo esc_textarea($qd['placeholder'] ?? ''); ?></textarea>
            </div>
        </div>
    </div>
    <?php
}

function fds_render_shared_challenge_cost_eeat_faq(array $d, array $wf_steps, bool $include_wf_header): void {
    $ch = $d['challenge_section'] ?? [];
    $mc = $d['method_comparison'] ?? [];
    $cf = $d['cost_framework'] ?? [];
    $ep = $d['eeat_proof'] ?? [];
    $rl = $d['reading_links'] ?? [];
    $fq = $d['faqs'] ?? [];
    ?>
    <!-- Tantangan & Pain Points -->
    <div class="fds-card">
        <h3>Tantangan Lapangan &amp; Konteks Teknis</h3>
        <div class="fds-grid-2">
            <div class="fds-field">
                <label>Eyebrow Section</label>
                <input type="text" name="ch_eyebrow" value="<?php echo esc_attr($ch['eyebrow'] ?? ''); ?>">
            </div>
            <div class="fds-field">
                <label>Judul Section</label>
                <input type="text" name="ch_title" value="<?php echo esc_attr($ch['title'] ?? ''); ?>">
            </div>
        </div>
        <div class="fds-field">
            <label>Paragraf Penjelasan (1 paragraf per baris)</label>
            <textarea name="ch_paragraphs" rows="4"><?php echo esc_textarea(implode("\n", (array) ($ch['paragraphs'] ?? []))); ?></textarea>
        </div>
        <div class="fds-rep-header">
            <strong>Poin Masalah Utama (Pain Points)</strong>
            <button type="button" class="button button-small" onclick="fdsAddPairRow('pp-list','pp_title[]','pp_desc[]','Judul Masalah','Penjelasan singkat')">+ Tambah Poin</button>
        </div>
        <div id="pp-list" class="fds-rep-list">
            <?php foreach ((array) ($ch['pain_points'] ?? []) as $pp): ?>
                <div class="fds-rep-row">
                    <div class="fds-rep-controls">
                        <button type="button" class="button button-small" onclick="fdsMoveRow(this,-1)">&uarr;</button>
                        <button type="button" class="button button-small" onclick="fdsMoveRow(this,1)">&darr;</button>
                        <button type="button" class="button button-small button-link-delete" onclick="fdsRemoveRow(this)">Hapus</button>
                    </div>
                    <input type="text" name="pp_title[]" value="<?php echo esc_attr($pp['title'] ?? ''); ?>" placeholder="Judul Masalah">
                    <textarea name="pp_desc[]" rows="2" placeholder="Penjelasan singkat"><?php echo esc_textarea($pp['desc'] ?? ''); ?></textarea>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Tabel Perbandingan Metode -->
    <div class="fds-card">
        <h3>Tabel Matriks Perbandingan Metode</h3>
        <div class="fds-grid-2">
            <div class="fds-field">
                <label>Eyebrow Tabel</label>
                <input type="text" name="mc_eyebrow" value="<?php echo esc_attr($mc['eyebrow'] ?? ''); ?>">
            </div>
            <div class="fds-field">
                <label>Judul Tabel</label>
                <input type="text" name="mc_title" value="<?php echo esc_attr($mc['title'] ?? ''); ?>">
            </div>
        </div>
        <div class="fds-field">
            <label>Subjudul Tabel</label>
            <textarea name="mc_subtitle" rows="2"><?php echo esc_textarea($mc['subtitle'] ?? ''); ?></textarea>
        </div>
        <div class="fds-field">
            <label>Header Kolom (pisahkan dengan tanda <code>|</code>)</label>
            <input type="text" name="mc_columns" value="<?php echo esc_attr(implode(' | ', (array) ($mc['columns'] ?? []))); ?>">
        </div>
        <div class="fds-field">
            <label>Baris Data Tabel (1 baris = 1 baris tabel, antar sel dipisahkan <code>|</code>)</label>
            <?php
            $row_strings = [];
            foreach ((array) ($mc['rows'] ?? []) as $row) {
                $row_strings[] = implode(' | ', array_map('strval', (array) $row));
            }
            ?>
            <textarea name="mc_rows" rows="6"><?php echo esc_textarea(implode("\n", $row_strings)); ?></textarea>
        </div>
    </div>

    <!-- Alur Kerja -->
    <div class="fds-card">
        <h3>Alur Kerja / Tahapan Pelaksanaan</h3>
        <?php if ($include_wf_header): ?>
            <?php $wf = $d['workflow'] ?? []; ?>
            <div class="fds-grid-2">
                <div class="fds-field">
                    <label>Judul Alur Kerja</label>
                    <input type="text" name="wf_title" value="<?php echo esc_attr($wf['title'] ?? ''); ?>">
                </div>
                <div class="fds-field">
                    <label>Deskripsi Alur Kerja</label>
                    <textarea name="wf_desc" rows="2"><?php echo esc_textarea($wf['desc'] ?? ''); ?></textarea>
                </div>
            </div>
        <?php endif; ?>
        <div class="fds-rep-header">
            <strong>Daftar Tahap Kerja</strong>
            <button type="button" class="button button-small" onclick="fdsAddWorkflowRow()">+ Tambah Tahap</button>
        </div>
        <div id="wf-list" class="fds-rep-list">
            <?php foreach ($wf_steps as $ws): ?>
                <div class="fds-rep-row">
                    <div class="fds-rep-controls">
                        <button type="button" class="button button-small" onclick="fdsMoveRow(this,-1)">&uarr;</button>
                        <button type="button" class="button button-small" onclick="fdsMoveRow(this,1)">&darr;</button>
                        <button type="button" class="button button-small button-link-delete" onclick="fdsRemoveRow(this)">Hapus</button>
                    </div>
                    <div class="fds-grid-step">
                        <input type="text" name="wf_step[]" value="<?php echo esc_attr($ws['step'] ?? ''); ?>" placeholder="01">
                        <input type="text" name="wf_step_title[]" value="<?php echo esc_attr($ws['title'] ?? ''); ?>" placeholder="Judul Tahap">
                    </div>
                    <textarea name="wf_step_desc[]" rows="2" placeholder="Penjelasan tahap"><?php echo esc_textarea($ws['desc'] ?? ''); ?></textarea>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Panduan Biaya & E-E-A-T Proof -->
    <div class="fds-card">
        <h3>Panduan Biaya Proyek &amp; Checklist KAK</h3>
        <div class="fds-grid-2">
            <div class="fds-field">
                <label>Eyebrow Biaya</label>
                <input type="text" name="cf_eyebrow" value="<?php echo esc_attr($cf['eyebrow'] ?? ''); ?>">
            </div>
            <div class="fds-field">
                <label>Judul Section Biaya</label>
                <input type="text" name="cf_title" value="<?php echo esc_attr($cf['title'] ?? ''); ?>">
            </div>
        </div>
        <div class="fds-field">
            <label>Pengantar Biaya</label>
            <textarea name="cf_intro" rows="2"><?php echo esc_textarea($cf['intro'] ?? ''); ?></textarea>
        </div>
        <div class="fds-rep-header">
            <strong>Faktor Penentu Biaya</strong>
            <button type="button" class="button button-small" onclick="fdsAddPairRow('cf-list','cf_factor_name[]','cf_factor_desc[]','Nama Variabel Biaya','Penjelasan')">+ Tambah Faktor</button>
        </div>
        <div id="cf-list" class="fds-rep-list">
            <?php foreach ((array) ($cf['factors'] ?? []) as $fac): ?>
                <div class="fds-rep-row">
                    <div class="fds-rep-controls">
                        <button type="button" class="button button-small" onclick="fdsMoveRow(this,-1)">&uarr;</button>
                        <button type="button" class="button button-small" onclick="fdsMoveRow(this,1)">&darr;</button>
                        <button type="button" class="button button-small button-link-delete" onclick="fdsRemoveRow(this)">Hapus</button>
                    </div>
                    <input type="text" name="cf_factor_name[]" value="<?php echo esc_attr($fac['name'] ?? ''); ?>" placeholder="Nama Faktor">
                    <textarea name="cf_factor_desc[]" rows="2" placeholder="Penjelasan"><?php echo esc_textarea($fac['desc'] ?? ''); ?></textarea>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="fds-field" style="margin-top:14px;">
            <label>Checklist Informasi Evaluasi Awal (KAK) — 1 butir per baris</label>
            <textarea name="cf_kak_checklist" rows="4"><?php echo esc_textarea(implode("\n", (array) ($cf['kak_checklist'] ?? []))); ?></textarea>
        </div>
    </div>

    <div class="fds-card">
        <h3>Otoritas Manufaktur FDS (E-E-A-T Proof)</h3>
        <div class="fds-grid-2">
            <div class="fds-field">
                <label>Eyebrow</label>
                <input type="text" name="ep_eyebrow" value="<?php echo esc_attr($ep['eyebrow'] ?? ''); ?>">
            </div>
            <div class="fds-field">
                <label>Judul</label>
                <input type="text" name="ep_title" value="<?php echo esc_attr($ep['title'] ?? ''); ?>">
            </div>
        </div>
        <div class="fds-field">
            <label>Paragraf Pembuka</label>
            <textarea name="ep_intro" rows="3"><?php echo esc_textarea($ep['intro'] ?? ''); ?></textarea>
        </div>
        <div class="fds-field">
            <label>Daftar Poin Keunggulan (1 butir per baris)</label>
            <textarea name="ep_bullets" rows="4"><?php echo esc_textarea(implode("\n", (array) ($ep['bullets'] ?? []))); ?></textarea>
        </div>
        <div class="fds-field">
            <label>Catatan Dokumentasi Portofolio (di bawah daftar)</label>
            <textarea name="ep_portfolio_note" rows="2"><?php echo esc_textarea($ep['portfolio_note'] ?? ''); ?></textarea>
        </div>
    </div>

    <!-- Tautan Referensi -->
    <div class="fds-card">
        <h3>Tautan Referensi Armada &amp; Artikel Terkait</h3>
        <p class="fds-help">Isi kolom <em>URL Penuh</em> atau <em>Slug Post Blog Opsional</em> (hanya tampil jika artikel berstatus <code>publish</code>).</p>
        <div class="fds-rep-header">
            <strong>Daftar Tautan</strong>
            <button type="button" class="button button-small" onclick="fdsAddReadingLinkRow()">+ Tambah Tautan</button>
        </div>
        <div id="rl-list" class="fds-rep-list">
            <?php foreach ((array) $rl as $link): ?>
                <div class="fds-rep-row">
                    <div class="fds-rep-controls">
                        <button type="button" class="button button-small" onclick="fdsMoveRow(this,-1)">&uarr;</button>
                        <button type="button" class="button button-small" onclick="fdsMoveRow(this,1)">&darr;</button>
                        <button type="button" class="button button-small button-link-delete" onclick="fdsRemoveRow(this)">Hapus</button>
                    </div>
                    <div class="fds-grid-3">
                        <input type="text" name="rl_label[]" value="<?php echo esc_attr($link['label'] ?? ''); ?>" placeholder="Teks Tautan">
                        <input type="text" name="rl_url[]" value="<?php echo esc_attr($link['url'] ?? ''); ?>" placeholder="URL Penuh (opsional)">
                        <input type="text" name="rl_slug[]" value="<?php echo esc_attr($link['slug'] ?? ''); ?>" placeholder="Slug Post Blog (opsional)">
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- FAQ -->
    <div class="fds-card">
        <h3>FAQ Halaman Ini (Otomatis Sinkron ke Schema FAQPage)</h3>
        <div class="fds-rep-header">
            <strong>Daftar Pertanyaan &amp; Jawaban</strong>
            <button type="button" class="button button-small" onclick="fdsAddPairRow('faq-list','faq_q[]','faq_a[]','Pertanyaan','Jawaban lengkap')">+ Tambah FAQ</button>
        </div>
        <div id="faq-list" class="fds-rep-list">
            <?php foreach ((array) $fq as $item): ?>
                <div class="fds-rep-row">
                    <div class="fds-rep-controls">
                        <button type="button" class="button button-small" onclick="fdsMoveRow(this,-1)">&uarr;</button>
                        <button type="button" class="button button-small" onclick="fdsMoveRow(this,1)">&darr;</button>
                        <button type="button" class="button button-small button-link-delete" onclick="fdsRemoveRow(this)">Hapus</button>
                    </div>
                    <input type="text" name="faq_q[]" value="<?php echo esc_attr($item['q'] ?? ''); ?>" placeholder="Pertanyaan">
                    <textarea name="faq_a[]" rows="2" placeholder="Jawaban"><?php echo esc_textarea($item['a'] ?? ''); ?></textarea>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php
}

// 8. MAIN RENDERER
function render_layanan_content_admin_page() {
    if (!current_user_can('manage_options')) {
        return;
    }

    $tabs       = fds_get_layanan_admin_tabs();
    $active_tab = sanitize_key((string) ($_GET['tab'] ?? 'hub'));
    if (!isset($tabs[$active_tab])) {
        $active_tab = 'hub';
    }

    $status = fds_handle_layanan_admin_post($active_tab, $tabs);

    // Ambil data terkini setelah kemungkinan save/reset
    $meta     = $tabs[$active_tab];
    $sectors  = fds_get_layanan_sectors(true);
    $cats     = fds_get_layanan_categories(true);
    $hub      = fds_get_layanan_hub_data(true);
    $ui_texts = fds_get_layanan_ui_texts(true);

    $current_data = [];
    if ($meta['kind'] === 'hub') {
        $current_data = $hub;
    } elseif ($meta['kind'] === 'sektor') {
        $current_data = $sectors[$meta['slug']] ?? [];
    } elseif ($meta['kind'] === 'kategori') {
        $current_data = $cats[$meta['slug']] ?? [];
    } elseif ($meta['kind'] === 'ui') {
        $current_data = $ui_texts;
    }

    $has_override = ($meta['option'] !== '' && get_option($meta['option'], null) !== null);
    ?>
    <style>
        .fds-ly-wrap { max-width: 100%; margin: 20px 20px 40px 0; font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif; }
        .fds-ly-header { background: #fff; padding: 22px 28px; border-radius: 12px; border: 1px solid #e2e8f0; box-shadow: 0 4px 16px rgba(0,0,0,0.04); margin-bottom: 18px; display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 16px; }
        .fds-ly-tabs { display: flex; flex-wrap: wrap; gap: 7px; margin-bottom: 18px; }
        .fds-ly-tab { display: inline-block; padding: 8px 14px; border-radius: 8px; border: 1px solid #e2e8f0; background: #fff; color: #334155; font-size: 12.5px; font-weight: 600; text-decoration: none; }
        .fds-ly-tab:hover { border-color: #0f808a; color: #0f808a; }
        .fds-ly-tab.is-active { background: #0f808a; border-color: #0f808a; color: #fff; }
        .fds-card { background: #fff; padding: 22px 26px; border-radius: 12px; border: 1px solid #e2e8f0; box-shadow: 0 2px 10px rgba(0,0,0,0.03); margin-bottom: 18px; }
        .fds-card h3 { margin: 0 0 14px; font-size: 15px; font-weight: 700; color: #0f172a; border-bottom: 1px solid #f1f5f9; padding-bottom: 10px; }
        .fds-field { margin-bottom: 12px; }
        .fds-field label { display: block; font-size: 11.5px; font-weight: 700; color: #334155; margin-bottom: 4px; }
        .fds-card input[type="text"], .fds-card input[type="url"], .fds-card textarea { width: 100%; border: 1px solid #cbd5e1; border-radius: 6px; padding: 7px 10px; font-size: 13px; box-sizing: border-box; }
        .fds-grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }
        .fds-grid-3 { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 10px; }
        .fds-grid-step { display: grid; grid-template-columns: 80px 1fr; gap: 10px; margin-bottom: 8px; }
        .fds-help { margin: 0 0 10px; font-size: 12px; color: #64748b; }
        .fds-rep-header { display: flex; align-items: center; justify-content: space-between; margin: 12px 0 8px; }
        .fds-rep-list { display: flex; flex-direction: column; gap: 10px; }
        .fds-rep-row { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 12px; display: flex; flex-direction: column; gap: 8px; }
        .fds-rep-controls { display: flex; gap: 6px; justify-content: flex-end; }
        .fds-sticky-bar { position: sticky; bottom: 16px; background: #fff; border: 1px solid #e2e8f0; border-radius: 10px; padding: 14px 22px; box-shadow: 0 -4px 18px rgba(0,0,0,0.08); display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 12px; z-index: 90; }
        .fds-placeholder-note { background: #fffbeb; border: 1px solid #fde68a; border-left: 4px solid #f59e0b; border-radius: 8px; padding: 14px 18px; margin-bottom: 18px; }
        @media (max-width: 900px) { .fds-grid-2, .fds-grid-3 { grid-template-columns: 1fr; } }
    </style>

    <div class="wrap fds-ly-wrap">
        <div class="fds-ly-header">
            <div style="display:flex;align-items:center;gap:14px;">
                <div style="width:42px;height:42px;border-radius:10px;background:#0f808a;display:flex;align-items:center;justify-content:center;color:#fff;">
                    <span class="dashicons dashicons-networking" style="font-size:22px;width:22px;height:22px;"></span>
                </div>
                <div>
                    <h1 style="margin:0;font-size:21px;font-weight:700;color:#0f172a;">Pusat Konten Layanan &amp; Sektor Industri</h1>
                    <p style="margin:4px 0 0;font-size:12.5px;color:#64748b;">Edit Hub <code>/layanan/</code>, 6 Sektor Industri, 3 Kategori Layanan, Mega Menu, Footer, Form RFP &amp; <code>/terima-kasih/</code> secara non-destruktif.</p>
                </div>
            </div>
            <?php if (!empty($current_data['url'])): ?>
                <a href="<?php echo esc_url($current_data['url']); ?>" target="_blank" rel="noopener" class="button">
                    Lihat Halaman Live &rarr;
                </a>
            <?php elseif ($active_tab === 'ui'): ?>
                <a href="<?php echo esc_url(home_url('/terima-kasih/')); ?>" target="_blank" rel="noopener" class="button">
                    Lihat /terima-kasih/ &rarr;
                </a>
            <?php endif; ?>
        </div>

        <?php if ($status['message'] !== ''): ?>
            <div style="background:#f0fdf4;border-left:4px solid #22c55e;color:#166534;padding:12px 16px;border-radius:6px;margin-bottom:16px;font-size:13.5px;font-weight:500;">
                &check; <?php echo esc_html($status['message']); ?>
            </div>
        <?php endif; ?>
        <?php if ($status['error'] !== ''): ?>
            <div style="background:#fef2f2;border-left:4px solid #ef4444;color:#991b1b;padding:12px 16px;border-radius:6px;margin-bottom:16px;font-size:13.5px;font-weight:500;">
                <?php echo esc_html($status['error']); ?>
            </div>
        <?php endif; ?>

        <!-- TAB NAVIGATION -->
        <div class="fds-ly-tabs">
            <?php foreach ($tabs as $t_key => $t_meta): ?>
                <a href="<?php echo esc_url(admin_url('admin.php?page=fds-layanan-content&tab=' . rawurlencode($t_key))); ?>"
                   class="fds-ly-tab <?php echo $active_tab === $t_key ? 'is-active' : ''; ?>">
                    <?php echo esc_html($t_meta['label']); ?>
                </a>
            <?php endforeach; ?>
        </div>

        <?php if (!empty($current_data['placeholders']) && is_array($current_data['placeholders'])): ?>
            <div class="fds-placeholder-note">
                <strong style="display:block;font-size:12.5px;color:#92400e;margin-bottom:6px;">Catatan Internal Tim (Tidak Ditampilkan di Halaman Publik):</strong>
                <ul style="margin:0;padding-left:18px;font-size:12px;color:#78350f;line-height:1.5;">
                    <?php foreach ($current_data['placeholders'] as $ph): ?>
                        <li><?php echo esc_html($ph); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form method="post" action="<?php echo esc_url(admin_url('admin.php?page=fds-layanan-content&tab=' . rawurlencode($active_tab))); ?>">
            <?php wp_nonce_field('fds_layanan_admin_nonce', 'fds_layanan_nonce'); ?>
            <input type="hidden" name="fds_layanan_tab" value="<?php echo esc_attr($active_tab); ?>">

            <?php if ($meta['kind'] === 'hub'): ?>
                <div class="fds-card">
                    <h3>Hero &amp; Pengantar Hub (/layanan/)</h3>
                    <div class="fds-grid-2">
                        <div class="fds-field">
                            <label>Eyebrow Hero</label>
                            <input type="text" name="eyebrow" value="<?php echo esc_attr($current_data['eyebrow'] ?? ''); ?>">
                        </div>
                        <div class="fds-field">
                            <label>Heading Utama (H1)</label>
                            <input type="text" name="h1" value="<?php echo esc_attr($current_data['h1'] ?? ''); ?>">
                        </div>
                    </div>
                    <div class="fds-field">
                        <label>Subjudul Hero</label>
                        <textarea name="subtitle" rows="2"><?php echo esc_textarea($current_data['subtitle'] ?? ''); ?></textarea>
                    </div>
                    <div class="fds-field">
                        <label>Ringkasan Hero</label>
                        <textarea name="hero_summary" rows="2"><?php echo esc_textarea($current_data['hero_summary'] ?? ''); ?></textarea>
                    </div>
                    <div class="fds-grid-2">
                        <div class="fds-field">
                            <label>Tombol CTA Utama (Teks)</label>
                            <input type="text" name="cta_primary_text" value="<?php echo esc_attr($current_data['cta_primary_text'] ?? ''); ?>">
                        </div>
                        <div class="fds-field">
                            <label>Tombol CTA Utama (URL)</label>
                            <input type="text" name="cta_primary_url" value="<?php echo esc_attr($current_data['cta_primary_url'] ?? ''); ?>">
                        </div>
                        <div class="fds-field">
                            <label>Tombol CTA Sekunder (Teks)</label>
                            <input type="text" name="cta_secondary_text" value="<?php echo esc_attr($current_data['cta_secondary_text'] ?? ''); ?>">
                        </div>
                        <div class="fds-field">
                            <label>Tombol CTA Sekunder (URL)</label>
                            <input type="text" name="cta_secondary_url" value="<?php echo esc_attr($current_data['cta_secondary_url'] ?? ''); ?>">
                        </div>
                    </div>
                    <div class="fds-field">
                        <label>Paragraf Pengantar Hub (1 paragraf per baris)</label>
                        <textarea name="intro_paragraphs" rows="5"><?php echo esc_textarea(implode("\n", (array) ($current_data['intro_paragraphs'] ?? []))); ?></textarea>
                    </div>
                </div>

                <div class="fds-card">
                    <h3>Keunggulan Layanan Terpadu (Mengapa Memilih FDS)</h3>
                    <?php $wfds = $current_data['why_fds'] ?? []; ?>
                    <div class="fds-grid-2">
                        <div class="fds-field">
                            <label>Judul Section</label>
                            <input type="text" name="why_title" value="<?php echo esc_attr($wfds['title'] ?? ''); ?>">
                        </div>
                        <div class="fds-field">
                            <label>Deskripsi Section</label>
                            <textarea name="why_desc" rows="2"><?php echo esc_textarea($wfds['desc'] ?? ''); ?></textarea>
                        </div>
                    </div>
                    <div class="fds-rep-header">
                        <strong>Poin Keunggulan</strong>
                        <button type="button" class="button button-small" onclick="fdsAddPairRow('why-list','why_item_title[]','why_item_desc[]','Judul Poin','Penjelasan singkat')">+ Tambah Poin</button>
                    </div>
                    <div id="why-list" class="fds-rep-list">
                        <?php foreach ((array) ($wfds['items'] ?? []) as $wi): ?>
                            <div class="fds-rep-row">
                                <div class="fds-rep-controls">
                                    <button type="button" class="button button-small" onclick="fdsMoveRow(this,-1)">&uarr;</button>
                                    <button type="button" class="button button-small" onclick="fdsMoveRow(this,1)">&darr;</button>
                                    <button type="button" class="button button-small button-link-delete" onclick="fdsRemoveRow(this)">Hapus</button>
                                </div>
                                <input type="text" name="why_item_title[]" value="<?php echo esc_attr($wi['title'] ?? ''); ?>" placeholder="Judul Poin">
                                <textarea name="why_item_desc[]" rows="2" placeholder="Penjelasan"><?php echo esc_textarea($wi['desc'] ?? ''); ?></textarea>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <?php
                fds_render_shared_challenge_cost_eeat_faq($current_data, (array) ($current_data['workflow']['steps'] ?? []), true);
                fds_render_shared_seo_quote_fields($current_data, false);
                ?>

            <?php elseif ($meta['kind'] === 'sektor'): ?>
                <div class="fds-card">
                    <h3>Identitas Kartu Sektor, Gambar &amp; Hero</h3>
                    <div class="fds-grid-3">
                        <div class="fds-field">
                            <label>Nomor Sektor (01–06)</label>
                            <input type="text" name="num" value="<?php echo esc_attr($current_data['num'] ?? ''); ?>">
                        </div>
                        <div class="fds-field">
                            <label>Judul Kartu &amp; Menu</label>
                            <input type="text" name="card_title" value="<?php echo esc_attr($current_data['card_title'] ?? ''); ?>">
                        </div>
                        <div class="fds-field">
                            <label>Tag Pendek Slide Beranda</label>
                            <input type="text" name="home_tag" value="<?php echo esc_attr($current_data['home_tag'] ?? ''); ?>">
                        </div>
                    </div>
                    <div class="fds-grid-2">
                        <div class="fds-field">
                            <label>Deskripsi Kartu Ringkas</label>
                            <textarea name="card_desc" rows="3"><?php echo esc_textarea($current_data['card_desc'] ?? ''); ?></textarea>
                        </div>
                        <div class="fds-field">
                            <label>Tag Keyword Kartu Hub (1 per baris: <code>Label</code> atau <code>Label | URL</code>)</label>
                            <?php
                            $tag_lines = [];
                            foreach ((array) ($current_data['card_tags'] ?? []) as $tg) {
                                if (!empty($tg['label'])) {
                                    $tag_lines[] = !empty($tg['url']) ? ($tg['label'] . ' | ' . $tg['url']) : $tg['label'];
                                }
                            }
                            ?>
                            <textarea name="card_tags" rows="4"><?php echo esc_textarea(implode("\n", $tag_lines)); ?></textarea>
                        </div>
                    </div>
                    <div style="display:flex;gap:14px;align-items:center;margin:10px 0 14px;background:#f8fafc;border:1px solid #e2e8f0;border-radius:8px;padding:12px;">
                        <img id="sec-img-preview" src="<?php echo esc_url($current_data['image'] ?? ''); ?>" style="width:120px;height:72px;object-fit:cover;border-radius:6px;border:1px solid #cbd5e1;background:#e2e8f0;">
                        <div style="flex:1;display:grid;gap:8px;">
                            <div class="fds-field" style="margin:0;">
                                <label>URL Gambar Sektor</label>
                                <div style="display:flex;gap:8px;">
                                    <input type="text" id="sec-img-input" name="image" value="<?php echo esc_attr($current_data['image'] ?? ''); ?>" style="flex:1;">
                                    <button type="button" id="sec-img-upload" class="button">Pilih / Unggah</button>
                                </div>
                            </div>
                            <div class="fds-field" style="margin:0;">
                                <label>Alt Text Gambar (SEO)</label>
                                <input type="text" name="image_alt" value="<?php echo esc_attr($current_data['image_alt'] ?? ''); ?>">
                            </div>
                        </div>
                    </div>
                    <div class="fds-grid-2">
                        <div class="fds-field">
                            <label>Heading Utama Halaman (H1)</label>
                            <input type="text" name="h1" value="<?php echo esc_attr($current_data['h1'] ?? ''); ?>">
                        </div>
                        <div class="fds-field">
                            <label>Subjudul Sektor</label>
                            <input type="text" name="subtitle" value="<?php echo esc_attr($current_data['subtitle'] ?? ''); ?>">
                        </div>
                    </div>
                    <div class="fds-field">
                        <label>Ringkasan Hero</label>
                        <textarea name="hero_summary" rows="2"><?php echo esc_textarea($current_data['hero_summary'] ?? ''); ?></textarea>
                    </div>
                    <div class="fds-field">
                        <label>Paragraf Pengantar Sektor (1 paragraf per baris)</label>
                        <textarea name="intro_paragraphs" rows="4"><?php echo esc_textarea(implode("\n", (array) ($current_data['intro_paragraphs'] ?? []))); ?></textarea>
                    </div>
                </div>

                <!-- Solusi & Layanan Sektor -->
                <div class="fds-card">
                    <h3>Daftar Solusi dan Layanan Sektor</h3>
                    <div class="fds-rep-header">
                        <strong>Kartu Solusi</strong>
                        <button type="button" class="button button-small" onclick="fdsAddSolutionRow()">+ Tambah Solusi</button>
                    </div>
                    <div id="sol-list" class="fds-rep-list">
                        <?php foreach ((array) ($current_data['solutions'] ?? []) as $sol): ?>
                            <div class="fds-rep-row">
                                <div class="fds-rep-controls">
                                    <button type="button" class="button button-small" onclick="fdsMoveRow(this,-1)">&uarr;</button>
                                    <button type="button" class="button button-small" onclick="fdsMoveRow(this,1)">&darr;</button>
                                    <button type="button" class="button button-small button-link-delete" onclick="fdsRemoveRow(this)">Hapus</button>
                                </div>
                                <input type="text" name="sol_name[]" value="<?php echo esc_attr($sol['name'] ?? ''); ?>" placeholder="Nama Solusi">
                                <textarea name="sol_desc[]" rows="2" placeholder="Deskripsi solusi"><?php echo esc_textarea($sol['desc'] ?? ''); ?></textarea>
                                <div class="fds-grid-2">
                                    <input type="text" name="sol_label[]" value="<?php echo esc_attr($sol['link_label'] ?? ''); ?>" placeholder="Teks Tautan (opsional)">
                                    <input type="text" name="sol_url[]" value="<?php echo esc_attr($sol['link_url'] ?? ''); ?>" placeholder="URL Tautan (opsional)">
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Produk/Teknologi, Output & Penutup -->
                <div class="fds-card">
                    <h3>Produk &amp; Teknologi Pendukung, Output Pekerjaan, dan Paragraf Penutup</h3>
                    <div class="fds-rep-header">
                        <strong>Daftar Produk &amp; Teknologi (opsional)</strong>
                        <button type="button" class="button button-small" onclick="fdsAddProductTechRow()">+ Tambah Produk/Teknologi</button>
                    </div>
                    <div id="pt-list" class="fds-rep-list">
                        <?php foreach ((array) ($current_data['products_tech'] ?? []) as $pt): ?>
                            <?php
                            $sub_lines = [];
                            foreach ((array) ($pt['sublinks'] ?? []) as $sub) {
                                if (!empty($sub['label']) && !empty($sub['url'])) {
                                    $sub_lines[] = $sub['label'] . ' | ' . $sub['url'];
                                }
                            }
                            ?>
                            <div class="fds-rep-row">
                                <div class="fds-rep-controls">
                                    <button type="button" class="button button-small" onclick="fdsMoveRow(this,-1)">&uarr;</button>
                                    <button type="button" class="button button-small" onclick="fdsMoveRow(this,1)">&darr;</button>
                                    <button type="button" class="button button-small button-link-delete" onclick="fdsRemoveRow(this)">Hapus</button>
                                </div>
                                <div class="fds-grid-2">
                                    <input type="text" name="pt_name[]" value="<?php echo esc_attr($pt['name'] ?? ''); ?>" placeholder="Nama Produk / Sistem">
                                    <input type="text" name="pt_url[]" value="<?php echo esc_attr($pt['url'] ?? ''); ?>" placeholder="URL Tautan Utama (opsional)">
                                </div>
                                <textarea name="pt_note[]" rows="2" placeholder="Catatan tambahan (opsional)"><?php echo esc_textarea($pt['note'] ?? ''); ?></textarea>
                                <textarea name="pt_sublinks[]" rows="2" placeholder="Sub-tautan spesifik, 1 per baris: Label | https://..."><?php echo esc_textarea(implode("\n", $sub_lines)); ?></textarea>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="fds-field" style="margin-top:14px;">
                        <label>Ringkasan Output Pekerjaan (opsional)</label>
                        <textarea name="work_outputs" rows="2"><?php echo esc_textarea($current_data['work_outputs'] ?? ''); ?></textarea>
                    </div>
                    <div class="fds-field">
                        <label>Paragraf Penutup Lintas Tim Operasional (1 paragraf per baris)</label>
                        <textarea name="closing_paragraphs" rows="3"><?php echo esc_textarea(implode("\n", (array) ($current_data['closing_paragraphs'] ?? []))); ?></textarea>
                    </div>
                </div>

                <!-- Deliverable Groups -->
                <div class="fds-card">
                    <h3>Kelompok Teknis, Konsultasi &amp; Deliverables</h3>
                    <div class="fds-rep-header">
                        <strong>Kelompok Deliverables</strong>
                        <button type="button" class="button button-small" onclick="fdsAddDeliverableGroupRow()">+ Tambah Kelompok</button>
                    </div>
                    <div id="dg-list" class="fds-rep-list">
                        <?php foreach ((array) ($current_data['deliverable_groups'] ?? []) as $dg): ?>
                            <div class="fds-rep-row">
                                <div class="fds-rep-controls">
                                    <button type="button" class="button button-small" onclick="fdsMoveRow(this,-1)">&uarr;</button>
                                    <button type="button" class="button button-small" onclick="fdsMoveRow(this,1)">&darr;</button>
                                    <button type="button" class="button button-small button-link-delete" onclick="fdsRemoveRow(this)">Hapus</button>
                                </div>
                                <input type="text" name="dg_title[]" value="<?php echo esc_attr($dg['title'] ?? ''); ?>" placeholder="Judul Kelompok">
                                <textarea name="dg_items[]" rows="5" placeholder="1 butir deliverable per baris"><?php echo esc_textarea(implode("\n", (array) ($dg['items'] ?? []))); ?></textarea>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Software Integrations & Related Capabilities -->
                <div class="fds-card">
                    <h3>Format Output, Kompatibilitas Software &amp; Layanan Terkait</h3>
                    <?php $si = $current_data['software_integrations'] ?? []; ?>
                    <div class="fds-grid-2">
                        <div class="fds-field">
                            <label>Eyebrow Kompatibilitas</label>
                            <input type="text" name="si_eyebrow" value="<?php echo esc_attr($si['eyebrow'] ?? ''); ?>">
                        </div>
                        <div class="fds-field">
                            <label>Judul Kompatibilitas</label>
                            <input type="text" name="si_title" value="<?php echo esc_attr($si['title'] ?? ''); ?>">
                        </div>
                    </div>
                    <div class="fds-field">
                        <label>Subjudul Kompatibilitas</label>
                        <textarea name="si_subtitle" rows="2"><?php echo esc_textarea($si['subtitle'] ?? ''); ?></textarea>
                    </div>
                    <div class="fds-grid-2">
                        <div class="fds-field">
                            <label>Format File Penyerahan (1 per baris)</label>
                            <textarea name="si_formats" rows="4"><?php echo esc_textarea(implode("\n", (array) ($si['formats'] ?? []))); ?></textarea>
                        </div>
                        <div class="fds-field">
                            <label>Perangkat Lunak Penerima (1 per baris)</label>
                            <textarea name="si_tools" rows="4"><?php echo esc_textarea(implode("\n", (array) ($si['tools'] ?? []))); ?></textarea>
                        </div>
                    </div>
                    <div class="fds-rep-header">
                        <strong>Kartu Layanan &amp; Sektor Terkait (maks. 3 rekomendasi)</strong>
                        <button type="button" class="button button-small" onclick="fdsAddRelCapRow()">+ Tambah Kartu Terkait</button>
                    </div>
                    <div id="rc-list" class="fds-rep-list">
                        <?php foreach ((array) ($current_data['related_capabilities'] ?? []) as $rc): ?>
                            <div class="fds-rep-row">
                                <div class="fds-rep-controls">
                                    <button type="button" class="button button-small" onclick="fdsMoveRow(this,-1)">&uarr;</button>
                                    <button type="button" class="button button-small" onclick="fdsMoveRow(this,1)">&darr;</button>
                                    <button type="button" class="button button-small button-link-delete" onclick="fdsRemoveRow(this)">Hapus</button>
                                </div>
                                <div class="fds-grid-2">
                                    <input type="text" name="rc_title[]" value="<?php echo esc_attr($rc['title'] ?? ''); ?>" placeholder="Judul Layanan Terkait">
                                    <input type="text" name="rc_url[]" value="<?php echo esc_attr($rc['url'] ?? ''); ?>" placeholder="URL Tujuan">
                                </div>
                                <textarea name="rc_desc[]" rows="2" placeholder="Deskripsi singkat"><?php echo esc_textarea($rc['desc'] ?? ''); ?></textarea>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <?php
                fds_render_shared_challenge_cost_eeat_faq($current_data, (array) ($current_data['workflow_steps'] ?? []), false);
                fds_render_shared_seo_quote_fields($current_data, true);
                ?>

            <?php elseif ($meta['kind'] === 'kategori'): ?>
                <div class="fds-card">
                    <h3>Hero &amp; Pengantar Kategori Layanan</h3>
                    <div class="fds-grid-2">
                        <div class="fds-field">
                            <label>Nama Singkat Kategori (Menu &amp; Footer)</label>
                            <input type="text" name="short_title" value="<?php echo esc_attr($current_data['short_title'] ?? ''); ?>">
                        </div>
                        <div class="fds-field">
                            <label>Heading Utama (H1)</label>
                            <input type="text" name="h1" value="<?php echo esc_attr($current_data['h1'] ?? ''); ?>">
                        </div>
                    </div>
                    <div class="fds-field">
                        <label>Subjudul Hero</label>
                        <input type="text" name="subtitle" value="<?php echo esc_attr($current_data['subtitle'] ?? ''); ?>">
                    </div>
                    <div class="fds-field">
                        <label>Ringkasan Hero</label>
                        <textarea name="hero_summary" rows="2"><?php echo esc_textarea($current_data['hero_summary'] ?? ''); ?></textarea>
                    </div>
                    <div class="fds-field">
                        <label>Paragraf Pengantar (1 paragraf per baris)</label>
                        <textarea name="intro_paragraphs" rows="4"><?php echo esc_textarea(implode("\n", (array) ($current_data['intro_paragraphs'] ?? []))); ?></textarea>
                    </div>
                </div>

                <div class="fds-card">
                    <h3>Kapabilitas Utama, Output &amp; Penerapan Sektor</h3>
                    <div class="fds-rep-header">
                        <strong>Kapabilitas Teknis</strong>
                        <button type="button" class="button button-small" onclick="fdsAddPairRow('cap-list','cap_name[]','cap_desc[]','Nama Kapabilitas','Penjelasan lengkap')">+ Tambah Kapabilitas</button>
                    </div>
                    <div id="cap-list" class="fds-rep-list">
                        <?php foreach ((array) ($current_data['capabilities'] ?? []) as $cap): ?>
                            <div class="fds-rep-row">
                                <div class="fds-rep-controls">
                                    <button type="button" class="button button-small" onclick="fdsMoveRow(this,-1)">&uarr;</button>
                                    <button type="button" class="button button-small" onclick="fdsMoveRow(this,1)">&darr;</button>
                                    <button type="button" class="button button-small button-link-delete" onclick="fdsRemoveRow(this)">Hapus</button>
                                </div>
                                <input type="text" name="cap_name[]" value="<?php echo esc_attr($cap['name'] ?? ''); ?>" placeholder="Nama Kapabilitas">
                                <textarea name="cap_desc[]" rows="2" placeholder="Penjelasan"><?php echo esc_textarea($cap['desc'] ?? ''); ?></textarea>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <div class="fds-field" style="margin-top:14px;">
                        <label>Output &amp; Deliverables Utama (1 butir per baris)</label>
                        <textarea name="outputs" rows="5"><?php echo esc_textarea(implode("\n", (array) ($current_data['outputs'] ?? []))); ?></textarea>
                    </div>

                    <div class="fds-field">
                        <label>Penerapan di Sektor Industri (centang sektor yang relevan)</label>
                        <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:8px;padding:10px;background:#f8fafc;border:1px solid #e2e8f0;border-radius:8px;">
                            <?php
                            $selected_secs = (array) ($current_data['sector_links'] ?? []);
                            foreach ($sectors as $sec_slug => $sec_obj):
                            ?>
                                <label style="display:flex;align-items:center;gap:6px;margin:0;font-weight:500;">
                                    <input type="checkbox" name="sector_links[]" value="<?php echo esc_attr($sec_slug); ?>" <?php checked(in_array($sec_slug, $selected_secs, true)); ?>>
                                    <span><?php echo esc_html($sec_obj['card_title']); ?></span>
                                </label>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

                <div class="fds-card">
                    <h3>Format Output &amp; Kompatibilitas Software/Ekosistem</h3>
                    <?php $si = $current_data['software_integrations'] ?? []; ?>
                    <div class="fds-grid-2">
                        <div class="fds-field">
                            <label>Eyebrow</label>
                            <input type="text" name="si_eyebrow" value="<?php echo esc_attr($si['eyebrow'] ?? ''); ?>">
                        </div>
                        <div class="fds-field">
                            <label>Judul</label>
                            <input type="text" name="si_title" value="<?php echo esc_attr($si['title'] ?? ''); ?>">
                        </div>
                    </div>
                    <div class="fds-field">
                        <label>Subjudul</label>
                        <textarea name="si_subtitle" rows="2"><?php echo esc_textarea($si['subtitle'] ?? ''); ?></textarea>
                    </div>
                    <div class="fds-grid-2">
                        <div class="fds-field">
                            <label>Format Output &amp; Modul (1 per baris)</label>
                            <textarea name="si_formats" rows="4"><?php echo esc_textarea(implode("\n", (array) ($si['formats'] ?? []))); ?></textarea>
                        </div>
                        <div class="fds-field">
                            <label>Perangkat Lunak &amp; Platform Pendukung (1 per baris)</label>
                            <textarea name="si_tools" rows="4"><?php echo esc_textarea(implode("\n", (array) ($si['tools'] ?? []))); ?></textarea>
                        </div>
                    </div>
                </div>

                <?php
                fds_render_shared_challenge_cost_eeat_faq($current_data, (array) ($current_data['workflow_steps'] ?? []), false);
                fds_render_shared_seo_quote_fields($current_data, true);
                ?>

            <?php elseif ($meta['kind'] === 'ui'): ?>
                <?php
                $ui_groups = [
                    'Label Bersama, Mega Menu & Footer Kolom Layanan' => [
                        'cta_wa_text'             => 'Teks Tombol WhatsApp Hero',
                        'trust_bar'               => 'Baris Trust Signal Hero',
                        'sticky_cta_label'        => 'Label Tombol Sticky Bar Mobile',
                        'menu_industries_heading' => 'Mega Menu: Judul Kolom Sektor Industri',
                        'menu_hub_link_text'      => 'Mega Menu: Teks Tautan Hub Layanan',
                        'menu_categories_heading' => 'Mega Menu: Judul Kolom Spesialis & Pelatihan',
                        'menu_all_services_text'  => 'Mega Menu: Teks "Lihat Semua Layanan"',
                        'footer_layanan_heading'  => 'Footer: Judul Kolom Layanan',
                        'footer_hub_link_text'    => 'Footer: Teks Tautan Hub (/layanan/)',
                    ],
                    'Template Hub (/layanan/)' => [
                        'hub_intro_eyebrow'       => 'Eyebrow Intro',
                        'hub_intro_heading'       => 'Judul Intro',
                        'hub_sectors_eyebrow'     => 'Eyebrow 6 Sektor',
                        'hub_sectors_heading'     => 'Judul 6 Sektor',
                        'hub_sectors_desc'        => 'Deskripsi 6 Sektor',
                        'hub_sector_card_cta'     => 'Label CTA Kartu Sektor',
                        'hub_categories_eyebrow'  => 'Eyebrow Kategori',
                        'hub_categories_heading'  => 'Judul Kategori',
                        'hub_categories_desc'     => 'Deskripsi Kategori',
                        'hub_category_card_cta'   => 'Label CTA Kartu Kategori',
                        'hub_why_eyebrow'         => 'Eyebrow Mengapa FDS',
                        'hub_workflow_eyebrow'    => 'Eyebrow Alur Kerja',
                        'hub_cost_cta_text'       => 'Tautan CTA Section Biaya',
                        'hub_reading_heading'     => 'Judul Tautan Teknis Terkait',
                        'hub_faq_title'           => 'Judul FAQ Hub',
                        'hub_faq_subtitle'        => 'Subjudul FAQ Hub',
                    ],
                    'Template Sektor (/layanan/{sektor}/)' => [
                        'sektor_cta_primary_prefix'       => 'Awalan Tombol CTA Primer (diikuti nama sektor)',
                        'sektor_cta_secondary_text'       => 'Tombol CTA Sekunder Hero',
                        'sektor_solutions_eyebrow_prefix' => 'Awalan Eyebrow Solusi (diikuti nama sektor)',
                        'sektor_solutions_heading'        => 'Judul Solusi dan Layanan',
                        'sektor_products_heading'         => 'Judul Produk & Teknologi Pendukung',
                        'sektor_outputs_heading'          => 'Judul Box Output Pekerjaan',
                        'sektor_closing_heading'          => 'Judul Dukungan Lintas Tim',
                        'sektor_deliverables_eyebrow'     => 'Eyebrow Deliverables',
                        'sektor_deliverables_heading'     => 'Judul Deliverables',
                        'sektor_deliverables_sub_prefix'  => 'Awalan Subjudul Deliverables (diikuti nama sektor)',
                        'sektor_workflow_eyebrow'         => 'Eyebrow Alur Kerja',
                        'sektor_workflow_heading_prefix'  => 'Awalan Judul Alur Kerja (diikuti nama sektor)',
                        'sektor_formats_heading'          => 'Judul Format File Penyerahan',
                        'sektor_tools_heading'            => 'Judul Perangkat Lunak Penerima',
                        'sektor_cost_cta_text'            => 'Tautan CTA Section Biaya',
                        'sektor_reading_heading'          => 'Judul Referensi Terkait',
                        'sektor_related_heading'          => 'Judul Layanan & Sektor Terkait',
                        'sektor_related_subtitle'         => 'Subjudul Layanan & Sektor Terkait',
                        'sektor_related_all_link'         => 'Tautan Semua Layanan FDS',
                        'sektor_related_card_cta'         => 'Label CTA Kartu Terkait',
                        'sektor_faq_prefix'               => 'Awalan Judul FAQ (diikuti nama sektor)',
                        'sektor_faq_subtitle'             => 'Subjudul FAQ Sektor',
                    ],
                    'Template Kategori (/layanan/{kategori}/)' => [
                        'kat_cta_primary_prefix'    => 'Awalan CTA Primer (diikuti nama kategori)',
                        'kat_cta_secondary_text'    => 'Tombol CTA Sekunder Hero',
                        'kat_intro_heading_prefix'  => 'Awalan Judul Intro (diikuti nama kategori)',
                        'kat_cap_eyebrow'           => 'Eyebrow Kapabilitas',
                        'kat_cap_heading'           => 'Judul Kapabilitas & Ruang Lingkup',
                        'kat_workflow_eyebrow'      => 'Eyebrow Alur Kerja',
                        'kat_workflow_heading_prefix'=> 'Awalan Judul Alur Kerja (diikuti nama kategori)',
                        'kat_formats_heading'       => 'Judul Format Output & Modul',
                        'kat_tools_heading'         => 'Judul Perangkat Lunak Pendukung',
                        'kat_outputs_heading'       => 'Judul Output & Deliverables Utama',
                        'kat_sectors_heading'       => 'Judul Penerapan di Sektor Industri',
                        'kat_sector_card_cta'       => 'Label CTA Kartu Sektor',
                        'kat_cost_cta_text'         => 'Tautan CTA Section Biaya',
                        'kat_reading_heading'       => 'Judul Referensi Teknis',
                        'kat_faq_prefix'            => 'Awalan Judul FAQ (diikuti nama kategori)',
                        'kat_faq_subtitle'          => 'Subjudul FAQ Kategori',
                    ],
                    'Form Penawaran Bersama (Quotation Section)' => [
                        'quote_kak_heading'       => 'Judul Daftar Checklist Evaluasi',
                        'quote_wa_btn'            => 'Teks Tombol WhatsApp Panel Kiri',
                        'quote_phone_label'       => 'Label Telepon',
                        'quote_email_label'       => 'Label Email',
                        'quote_trust_footer'      => 'Teks Footer Trust Panel Kiri',
                        'quote_form_heading'      => 'Judul Formulir Panel Kanan',
                        'quote_form_subtitle'     => 'Subjudul Formulir Panel Kanan',
                        'quote_form_submit_text'  => 'Teks Tombol Submit',
                        'quote_form_privacy_note' => 'Catatan Privasi di Bawah Tombol',
                    ],
                    'Halaman Konfirmasi Lead (/terima-kasih/)' => [
                        'ty_badge'         => 'Badge Status Konfirmasi',
                        'ty_h1'            => 'Judul Utama (H1)',
                        'ty_subtitle'      => 'Paragraf Pengantar Konfirmasi',
                        'ty_ref_label'     => 'Label Nomor Referensi',
                        'ty_steps_heading' => 'Judul 3 Tahap Peninjauan',
                        'ty_step1_title'   => 'Tahap 01: Judul',
                        'ty_step1_desc'    => 'Tahap 01: Deskripsi',
                        'ty_step2_title'   => 'Tahap 02: Judul',
                        'ty_step2_desc'    => 'Tahap 02: Deskripsi',
                        'ty_step3_title'   => 'Tahap 03: Judul',
                        'ty_step3_desc'    => 'Tahap 03: Deskripsi',
                        'ty_extra_heading' => 'Judul Box Dokumen Tambahan',
                        'ty_btn_wa'        => 'Teks Tombol WhatsApp',
                        'ty_btn_back'      => 'Teks Tombol Kembali ke Layanan',
                        'ty_btn_compare'   => 'Teks Tombol Bandingkan Armada',
                    ],
                ];
                foreach ($ui_groups as $group_title => $fields):
                ?>
                    <div class="fds-card">
                        <h3><?php echo esc_html($group_title); ?></h3>
                        <div class="fds-grid-2">
                            <?php foreach ($fields as $key => $lbl): ?>
                                <div class="fds-field">
                                    <label><?php echo esc_html($lbl); ?></label>
                                    <?php if (str_contains($key, 'subtitle') || str_contains($key, '_desc')): ?>
                                        <textarea name="ui[<?php echo esc_attr($key); ?>]" rows="2"><?php echo esc_textarea($current_data[$key] ?? ''); ?></textarea>
                                    <?php else: ?>
                                        <input type="text" name="ui[<?php echo esc_attr($key); ?>]" value="<?php echo esc_attr($current_data[$key] ?? ''); ?>">
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>

            <div class="fds-sticky-bar">
                <div style="display:flex;align-items:center;gap:12px;">
                    <?php if ($has_override): ?>
                        <button type="submit" name="fds_layanan_reset" class="button"
                                onclick="return confirm('Kembalikan konten tab ini ke default bawaan tema? Perubahan yang pernah Anda simpan pada tab ini akan dihapus.');"
                                style="color:#dc2626;border-color:#fca5a5;">
                            Reset Halaman Ini ke Default
                        </button>
                    <?php endif; ?>
                    <span style="font-size:12.5px;color:#64748b;">Tab aktif: <strong><?php echo esc_html($meta['label']); ?></strong></span>
                </div>
                <button type="submit" name="fds_layanan_save" class="button button-primary button-large" style="background:#0f808a;border-color:#0f808a;font-weight:600;padding:4px 24px;">
                    Simpan Perubahan Tab Ini
                </button>
            </div>
        </form>
    </div>

    <script>
    jQuery(document).ready(function($) {
        $('#sec-img-upload').on('click', function(e) {
            e.preventDefault();
            var uploader = wp.media({
                title: 'Pilih Gambar Sektor',
                button: { text: 'Gunakan Gambar Ini' },
                multiple: false
            }).on('select', function() {
                var att = uploader.state().get('selection').first().toJSON();
                $('#sec-img-input').val(att.url);
                $('#sec-img-preview').attr('src', att.url);
            }).open();
        });
    });

    function fdsRemoveRow(btn) {
        var row = btn.closest('.fds-rep-row');
        if (row) row.remove();
    }

    function fdsMoveRow(btn, dir) {
        var row = btn.closest('.fds-rep-row');
        if (!row) return;
        if (dir === -1 && row.previousElementSibling) {
            row.parentNode.insertBefore(row, row.previousElementSibling);
        } else if (dir === 1 && row.nextElementSibling) {
            row.parentNode.insertBefore(row.nextElementSibling, row);
        }
    }

    function fdsRowControlsHtml() {
        return '<div class="fds-rep-controls">' +
            '<button type="button" class="button button-small" onclick="fdsMoveRow(this,-1)">&uarr;</button>' +
            '<button type="button" class="button button-small" onclick="fdsMoveRow(this,1)">&darr;</button>' +
            '<button type="button" class="button button-small button-link-delete" onclick="fdsRemoveRow(this)">Hapus</button>' +
            '</div>';
    }

    function fdsAddPairRow(containerId, name1, name2, ph1, ph2) {
        var el = document.getElementById(containerId);
        if (!el) return;
        var div = document.createElement('div');
        div.className = 'fds-rep-row';
        div.innerHTML = fdsRowControlsHtml() +
            '<input type="text" name="' + name1 + '" value="" placeholder="' + ph1 + '">' +
            '<textarea name="' + name2 + '" rows="2" placeholder="' + ph2 + '"></textarea>';
        el.appendChild(div);
    }

    function fdsAddWorkflowRow() {
        var el = document.getElementById('wf-list');
        if (!el) return;
        var nextNo = String(el.querySelectorAll('.fds-rep-row').length + 1).padStart(2, '0');
        var div = document.createElement('div');
        div.className = 'fds-rep-row';
        div.innerHTML = fdsRowControlsHtml() +
            '<div class="fds-grid-step">' +
                '<input type="text" name="wf_step[]" value="' + nextNo + '" placeholder="01">' +
                '<input type="text" name="wf_step_title[]" value="" placeholder="Judul Tahap">' +
            '</div>' +
            '<textarea name="wf_step_desc[]" rows="2" placeholder="Penjelasan tahap"></textarea>';
        el.appendChild(div);
    }

    function fdsAddReadingLinkRow() {
        var el = document.getElementById('rl-list');
        if (!el) return;
        var div = document.createElement('div');
        div.className = 'fds-rep-row';
        div.innerHTML = fdsRowControlsHtml() +
            '<div class="fds-grid-3">' +
                '<input type="text" name="rl_label[]" value="" placeholder="Teks Tautan">' +
                '<input type="text" name="rl_url[]" value="" placeholder="URL Penuh (opsional)">' +
                '<input type="text" name="rl_slug[]" value="" placeholder="Slug Post Blog (opsional)">' +
            '</div>';
        el.appendChild(div);
    }

    function fdsAddSolutionRow() {
        var el = document.getElementById('sol-list');
        if (!el) return;
        var div = document.createElement('div');
        div.className = 'fds-rep-row';
        div.innerHTML = fdsRowControlsHtml() +
            '<input type="text" name="sol_name[]" value="" placeholder="Nama Solusi">' +
            '<textarea name="sol_desc[]" rows="2" placeholder="Deskripsi solusi"></textarea>' +
            '<div class="fds-grid-2">' +
                '<input type="text" name="sol_label[]" value="" placeholder="Teks Tautan (opsional)">' +
                '<input type="text" name="sol_url[]" value="" placeholder="URL Tautan (opsional)">' +
            '</div>';
        el.appendChild(div);
    }

    function fdsAddProductTechRow() {
        var el = document.getElementById('pt-list');
        if (!el) return;
        var div = document.createElement('div');
        div.className = 'fds-rep-row';
        div.innerHTML = fdsRowControlsHtml() +
            '<div class="fds-grid-2">' +
                '<input type="text" name="pt_name[]" value="" placeholder="Nama Produk / Sistem">' +
                '<input type="text" name="pt_url[]" value="" placeholder="URL Tautan Utama (opsional)">' +
            '</div>' +
            '<textarea name="pt_note[]" rows="2" placeholder="Catatan tambahan (opsional)"></textarea>' +
            '<textarea name="pt_sublinks[]" rows="2" placeholder="Sub-tautan spesifik, 1 per baris: Label | https://..."></textarea>';
        el.appendChild(div);
    }

    function fdsAddDeliverableGroupRow() {
        var el = document.getElementById('dg-list');
        if (!el) return;
        var div = document.createElement('div');
        div.className = 'fds-rep-row';
        div.innerHTML = fdsRowControlsHtml() +
            '<input type="text" name="dg_title[]" value="" placeholder="Judul Kelompok">' +
            '<textarea name="dg_items[]" rows="5" placeholder="1 butir deliverable per baris"></textarea>';
        el.appendChild(div);
    }

    function fdsAddRelCapRow() {
        var el = document.getElementById('rc-list');
        if (!el) return;
        var div = document.createElement('div');
        div.className = 'fds-rep-row';
        div.innerHTML = fdsRowControlsHtml() +
            '<div class="fds-grid-2">' +
                '<input type="text" name="rc_title[]" value="" placeholder="Judul Layanan Terkait">' +
                '<input type="text" name="rc_url[]" value="" placeholder="URL Tujuan">' +
            '</div>' +
            '<textarea name="rc_desc[]" rows="2" placeholder="Deskripsi singkat"></textarea>';
        el.appendChild(div);
    }
    </script>
    <?php
}
