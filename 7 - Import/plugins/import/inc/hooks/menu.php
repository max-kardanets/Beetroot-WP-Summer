<?php
function import_admin_menu() {
    require_once IMPORT_ROOT .'/inc/pages/import.php';

    add_menu_page(
        'Import',
        'Import',
        'manage_options',
        'import',
        'import_page',
        'dashicons-database-import'
    );
}
add_action('admin_menu', 'import_admin_menu');
