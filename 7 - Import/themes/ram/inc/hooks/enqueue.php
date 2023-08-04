<?php
add_action('wp_enqueue_scripts', function() {
    wp_enqueue_style('main', RAM_ROOT_URI .'/assets/css/main.css');
});
