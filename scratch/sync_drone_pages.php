<?php
require_once __DIR__ . '/../../wp-load.php';

$drones_to_create = [
    'ferto-5l'      => 'FERTO 5 (5 Liter)',
    'ferto-10l'     => 'FERTO 10 (10 Liter)',
    'ferto-15l'     => 'FERTO 15 (17 Liter)',
    'ferto-22l'     => 'FERTO 22 (22 Liter)',
    'ferto-30l'     => 'FERTO 30 (30 Liter)',
    'ferto-50l'     => 'FERTO 50 (50 Liter)',
    'deltav'        => 'DELTAV (UAV Pemetaan VTOL)',
    'multipurpose'  => 'MULTIPURPOSE (UAV Kustom/Inspeksi)',
    'delfro'        => 'DELFRO (UAV Kargo Logistik)',
    'rebo'          => 'REBO (UAV Reboisasi Seedball)',
];

foreach ($drones_to_create as $slug => $title) {
    $existing = get_page_by_path($slug);
    if (!$existing) {
        $id = wp_insert_post([
            'post_title'   => $title,
            'post_name'    => $slug,
            'post_status'  => 'publish',
            'post_type'    => 'page',
            'post_content' => 'Halaman detail spesifikasi resmi drone ' . $title,
        ]);
        echo "CREATED: page $slug (ID: $id)\n";
    } else {
        echo "EXISTS: page $slug (ID: {$existing->ID})\n";
    }
}
