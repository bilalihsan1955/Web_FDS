<?php

/**
 * Global Theme Helper Functions (Non-namespaced)
 */

if (!defined('ABSPATH')) {
    exit;
}

if (!function_exists('fds_img')) {
    function fds_img($key, $default = '') {
        if (function_exists('App\fds_img')) {
            return \App\fds_img($key, $default);
        }
        $val = get_option("fds_img_{$key}", '');
        return !empty($val) ? $val : $default;
    }
}
