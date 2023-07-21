<?php
define('CHAT_ROOT', plugin_dir_path(__FILE__));
define('CHAT_ROOT_URI', plugin_dir_url(__FILE__));

define('CHAT_VER', '1.0.0.0');

require_once CHAT_ROOT .'vendor/autoload.php';
require_once CHAT_ROOT .'inc/helpers/init.php';
require_once CHAT_ROOT .'inc/hooks/init.php';
require_once CHAT_ROOT .'inc/classes/Chat.class.php';
require_once CHAT_ROOT .'inc/classes/ChatHelper.class.php';
require_once CHAT_ROOT .'inc/shortcodes/init.php';

global $chat_helper;
$chat_helper = new ChatHelper();
