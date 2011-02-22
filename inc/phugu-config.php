<?php
	error_reporting(E_ALL);
	ini_set('display_errors', '1');

	define('VERSION', '0.0.1');

	define('DB_SERVER', 'localhost');
	define('DB_NAME', 'phugu');
	define('DB_ADMIN_USER', 'phugu');
	define('DB_ADMIN_PASSWORD', 'phu9u');
	
	define('DB_READ_USER', 'phugu_read');
	define('DB_READ_PASSWORD', 'phu9u_read');

	define('DB_TABLE_PREFIX', 'phu_');

	define('SESSION_STORE', 'db'); /* Possible values are cookie or db */

	define('ROOTDIR', dirname(__FILE__) . "/");
	define('INCDIR', ROOTDIR . "inc/");
?>
