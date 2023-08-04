<?php
/*
Plugin Name: Import
Description: Plugin for importing data
Version: 1.0.0
 */

$plugin_data = get_file_data(__FILE__, array('Version' => 'Version'), false);
define('IMPORT_VER', $plugin_data['Version']);

define('IMPORT_ROOT', rtrim(plugin_dir_path(__FILE__), '/'));
define('IMPORT_ROOT_URI', rtrim(plugin_dir_url(__FILE__), '/'));

require_once IMPORT_ROOT .'/inc/classes/Import.class.php';
require_once IMPORT_ROOT .'/inc/classes/RaMImport.class.php';
$import_obj = new RaMImport();

require_once IMPORT_ROOT .'/inc/helpers/api.php';
require_once IMPORT_ROOT .'/inc/helpers/file.php';
require_once IMPORT_ROOT .'/inc/hooks/enqueue.php';
require_once IMPORT_ROOT .'/inc/hooks/menu.php';
require_once IMPORT_ROOT .'/inc/ajax/import.php';
