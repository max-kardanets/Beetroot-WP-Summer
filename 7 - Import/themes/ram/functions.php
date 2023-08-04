<?php

define('RAM_ROOT', get_template_directory());
define('RAM_ROOT_URI', get_template_directory_uri());

require_once RAM_ROOT .'/inc/hooks/theme-support.php';
require_once RAM_ROOT .'/inc/hooks/enqueue.php';
require_once RAM_ROOT .'/inc/hooks/taxonomies.php';
require_once RAM_ROOT .'/inc/hooks/post-types.php';
