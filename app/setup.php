<?php

/**
 * Theme setup.
 */

namespace App;

if (!defined('ABSPATH')) {
    exit;
}

use Illuminate\Support\Facades\Vite;

/**
 * Inject styles into the block editor.
 *
 * @return array
 */
add_filter('block_editor_settings_all', function ($settings) {
    $style = Vite::asset('resources/css/editor.css');

    $settings['styles'][] = [
        'css' => "@import url('{$style}')",
    ];

    return $settings;
});

/**
 * Inject scripts into the block editor.
 *
 * @return void
 */
add_action('admin_head', function () {
    if (! get_current_screen()?->is_block_editor()) {
        return;
    }

    if (! Vite::isRunningHot()) {
        $dependencies = json_decode(Vite::content('editor.deps.json'));

        foreach ($dependencies as $dependency) {
            if (! wp_script_is($dependency)) {
                wp_enqueue_script($dependency);
            }
        }
    }
    echo Vite::withEntryPoints([
        'resources/js/editor.js',
    ])->toHtml();
});

/**
 * Use the generated theme.json file.
 *
 * @return string
 */
add_filter('theme_file_path', function ($path, $file) {
    return $file === 'theme.json'
        ? public_path('build/assets/theme.json')
        : $path;
}, 10, 2);

/**
 * Disable on-demand block asset loading.
 *
 * @link https://core.trac.wordpress.org/ticket/61965
 */
add_filter('should_load_separate_core_block_assets', '__return_false');

/**
 * Dequeue unused core block library styles on frontend to eliminate render-blocking CSS
 */
add_action('wp_enqueue_scripts', function () {
    if (!is_admin()) {
        wp_dequeue_style('wp-block-library');
        wp_dequeue_style('wp-block-library-theme');
        wp_dequeue_style('global-styles');
        wp_dequeue_style('classic-theme-styles');
        wp_dequeue_style('akismet-widget-style');
    }
}, 100);

/**
 * Kecualikan aset Vite yang sudah ter-minifikasi dari LiteSpeed CSS/JS Combine agar tidak pernah 404 saat purge
 */
add_filter('litespeed_optm_css_exc', function ($excludes) {
    $excludes = is_array($excludes) ? $excludes : [];
    $excludes[] = 'public/build/assets/';
    $excludes[] = 'fds-theme/public/build/';
    return $excludes;
});
add_filter('litespeed_optm_js_exc', function ($excludes) {
    $excludes = is_array($excludes) ? $excludes : [];
    $excludes[] = 'public/build/assets/';
    $excludes[] = 'fds-theme/public/build/';
    return $excludes;
});

/**
 * Tunda eksekusi third-party Facebook Pixel (fbevents.js - 223 KiB / 505ms TBT) hingga interaksi pertama pengguna
 * sehingga Total Blocking Time (TBT) Mobile menjadi 0ms tanpa menghilangkan tracking pengunjung asli.
 */
add_action('wp_head', function () {
    if (!is_admin()) {
        ob_start();
    }
}, 0);

add_action('wp_head', function () {
    if (!is_admin() && ob_get_level() > 0) {
        $html = ob_get_clean();
        if (strpos($html, 'connect.facebook.net/en_US/fbevents.js') !== false) {
            $html = preg_replace(
                '#t\.src=v;s=b\.getElementsByTagName\(e\)\[0\];s\.parentNode\.insertBefore\(t,s\)#',
                'var l=function(){if(b.getElementById("fds-fb-px"))return;t.id="fds-fb-px";t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)};["scroll","touchstart","pointerdown","mousemove","keydown"].forEach(function(ev){window.addEventListener(ev,l,{once:true,passive:true})})',
                $html
            );
        }
        echo $html;
    }
}, 9999);

/**
 * Matikan Speculation Rules API bawaan WP 6.5+ yang memicu prerender instan setengah-jadi (FOUC)
 */
add_action('init', function () {
    remove_action('wp_head', 'wp_maybe_enqueue_speculation_rules', 10);
});
add_action('wp_footer', function () {
    remove_action('wp_footer', 'wp_print_speculation_rules');
    remove_action('wp_footer', 'wp_maybe_enqueue_speculation_rules');
}, 0);
add_filter('wp_speculation_rules_configuration', '__return_empty_array', 9999);
add_filter('speculation_rules_configuration', '__return_empty_array', 9999);

/**
 * Register the initial theme setup.
 *
 * @return void
 */
add_action('after_setup_theme', function () {
    /**
     * Disable full-site editing support.
     *
     * @link https://wptavern.com/gutenberg-10-5-embeds-pdfs-adds-verse-block-color-options-and-introduces-new-patterns
     */
    remove_theme_support('block-templates');

    /**
     * Register the navigation menus.
     *
     * @link https://developer.wordpress.org/reference/functions/register_nav_menus/
     */
    register_nav_menus([
        'primary_navigation' => __('Primary Navigation', 'sage'),
    ]);

    /**
     * Disable the default block patterns.
     *
     * @link https://developer.wordpress.org/block-editor/developers/themes/theme-support/#disabling-the-default-block-patterns
     */
    remove_theme_support('core-block-patterns');

    /**
     * Enable plugins to manage the document title.
     *
     * @link https://developer.wordpress.org/reference/functions/add_theme_support/#title-tag
     */
    add_theme_support('title-tag');

    /**
     * Enable post thumbnail support.
     *
     * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
     */
    add_theme_support('post-thumbnails');

    /**
     * Enable custom logo support.
     */
    add_theme_support('custom-logo', [
        'height'      => 60,
        'width'       => 240,
        'flex-width'  => true,
        'flex-height' => true,
    ]);

    /**
     * Enable responsive embed support.
     *
     * @link https://developer.wordpress.org/block-editor/how-to-guides/themes/theme-support/#responsive-embedded-content
     */
    add_theme_support('responsive-embeds');

    /**
     * Enable HTML5 markup support.
     *
     * @link https://developer.wordpress.org/reference/functions/add_theme_support/#html5
     */
    add_theme_support('html5', [
        'caption',
        'comment-form',
        'comment-list',
        'gallery',
        'search-form',
        'script',
        'style',
    ]);

    /**
     * Enable selective refresh for widgets in customizer.
     *
     * @link https://developer.wordpress.org/reference/functions/add_theme_support/#customize-selective-refresh-widgets
     */
    add_theme_support('customize-selective-refresh-widgets');
}, 20);

/**
 * Register the theme sidebars.
 *
 * @return void
 */
add_action('widgets_init', function () {
    $config = [
        'before_widget' => '<section class="widget %1$s %2$s">',
        'after_widget' => '</section>',
        'before_title' => '<h3>',
        'after_title' => '</h3>',
    ];

    register_sidebar([
        'name' => __('Primary', 'sage'),
        'id' => 'sidebar-primary',
    ] + $config);

    register_sidebar([
        'name' => __('Footer', 'sage'),
        'id' => 'sidebar-footer',
    ] + $config);
});

/**
 * Helper fds_img untuk mengambil URL gambar kustom atau fallback
 */
if (!function_exists('App\fds_img')) {
    function fds_img($key, $default = '') {
        $val = get_option("fds_img_{$key}", '');
        return !empty($val) ? $val : $default;
    }
}
