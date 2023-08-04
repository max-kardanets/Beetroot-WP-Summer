<?php
function import_admin_enqueue($hook) {
    $assets_dir = IMPORT_ROOT_URI .'/assets';

    // CSS
    $css_dir = $assets_dir .'/css';
    wp_register_style('import', $css_dir .'/main.css', false, IMPORT_VER);

    // JS
    $js_dir = $assets_dir .'/js';
    wp_register_script('import', $js_dir .'/main.js', ['jquery'], IMPORT_VER, true);
}
add_action('admin_enqueue_scripts', 'import_admin_enqueue');
