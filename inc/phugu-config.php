<?php
	error_reporting(E_ALL);
	ini_set('display_errors', '1');

	define('VERSION', '0.0.1');

	define('DB_SERVER', '127.0.0.1');
	define('DB_NAME', 'phugu');
	define('DB_ADMIN_USER', 'phugu');
	define('DB_ADMIN_PASSWORD', 'phu9u');
	
	define('DB_READ_USER', 'phugu_read');
	define('DB_READ_PASSWORD', 'phu9u_r3ad');

	define('DB_TABLE_PREFIX', 'phu_');

	define('SESSION_STORE', 'cookie'); /* Possible values are cookie or db */

	define('JQUERY_DIR', '/js/jquery/');

	define('JQUERY_JS', JQUERY_DIR . 'jquery-1.5.min.js');

	/*define('BASE_DIR', __file__ . "/");
	define('INC_DIR', BASE_DIR . "inc/");*/
?>
