<?php
	define('SYS_OBJ', 0);

	/* Record/Object status */
	define('DISABLED', 0);
	define('LIVE', 1);

	/* Key levels */
	define('KEY_DISABLED', 0);
	define('KEY_READ', 1);
	define('KEY_WRITE', 2);

	/* Field Types */
	$fields = array( 'id' => 'Numeric(Normal)',	'username' => 'Text(20)', 'password' => 'Password(20)', 'status' => 'Numeric(Small)');
	define('NUM_NORMAL', 1);
	define('NUM_SHORT', 2);
	define('NUM_LONG', 3);
	define('TEXT', 4);
	
?>