<?php
/*
Plugin Name: Visual Actions
Description: Visualize actions
Author: Max Kardanets
Version: 1.0.0
*/

add_action('init', function() {
    if(
        is_admin() ||
        !current_user_can( 'manage_options')
    ) {
        return;
    }

    define('VA_ROOT', rtrim(plugin_dir_path(__FILE__), '/'));
    define('VA_ROOT_URI', rtrim(plugin_dir_url(__FILE__), '/'));

    require_once VA_ROOT .'/inc/helpers.php';
    require_once VA_ROOT .'/inc/hooks.php';
});
