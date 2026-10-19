<?php
require_once dirname(__DIR__, 2) . '/wp-load.php';
$p = get_page_by_path('tentang-kami');
if ($p) {
    echo "ID: " . $p->ID . "\n";
    echo "Slug: " . $p->post_name . "\n";
    echo "Template Meta: " . get_post_meta($p->ID, '_wp_page_template', true) . "\n";
} else {
    echo "Page not found by slug tentang-kami\n";
    $pages = get_pages();
    foreach ($pages as $pg) {
        echo "ID: {$pg->ID} | Slug: {$pg->post_name} | Title: {$pg->post_title} | Tpl: " . get_post_meta($pg->ID, '_wp_page_template', true) . "\n";
    }
}
