<?php

	include("inc/load.php");
	$dbo = $GLOBALS['phu_db'];
	if(isset($_GET['verbose']) && $_GET['verbose']){
		$verbose = true;
	} else {
		$verbose = false;
	}
	$sql = "SELECT COUNT(*) FROM " . DB_TABLE_PREFIX . "users";
	echo ($verbose ? "Testing for previous install...<br />" : "");
	$res = $dbo->query($sql);

	if($res){
		if((!isset($_GET['force'])) || (!$_GET['force'])){
			echo "Previous installation dectected. To overwrite the database <a href='install.php?force=true&verbose=" . $verbose . "'>click here</a>.<br />";
			exit();
		}
		else {
			echo "Previous installation dectected. Wiping and creating new install...<br />";
		}
	} else {
		echo "Creating new install...<br />";
	}


	$sql = "DROP TABLE " . DB_TABLE_PREFIX . "users";
	echo ($verbose ? "Dropping users table...<br />" : "");
	$dbo->query($sql);

	$sql = "CREATE TABLE " . DB_TABLE_PREFIX . "users (" .
		"id INT(11) PRIMARY KEY AUTO_INCREMENT, " .
		"username VARCHAR(20) NOT NULL, " .
		"password VARCHAR(20) NOT NULL" .
		") ENGINE = MYISAM";
	echo ($verbose ? "Creating users table...<br />" : "");
	$dbo->query($sql);

	$sql = "INSERT INTO " . DB_TABLE_PREFIX . "users (username, password) VALUES ('root', 'root')";
	echo ($verbose ? "Inserting root user...<br />" : "");
	$dbo->query($sql);
	$root_id = mysql_insert_id();
	echo ($verbose ? "Root id:" . $root_id . "<br />" : "");

	$sql = "DROP TABLE " . DB_TABLE_PREFIX . "groups";
	echo ($verbose ? "Dropping groups table...<br />" : "");
	$dbo->query($sql);
	
	$sql = "CREATE TABLE " . DB_TABLE_PREFIX . "groups (" .
		"id INT(11) PRIMARY KEY AUTO_INCREMENT, " .
		"name VARCHAR(20) NOT NULL" .
		") ENGINE = MYISAM";

	echo ($verbose ? "Creating groups table...<br />" : "");
	$dbo->query($sql);

	$sql = "INSERT INTO " . DB_TABLE_PREFIX . "groups (name) VALUES ('administrator')";

	echo ($verbose ? "Inserting admin group...<br />" : "");
	$dbo->query($sql);
	$admin_group = mysql_insert_id();
	echo ($verbose ? "Admin group:" . $admin_group . "<br />" : "");

	$sql = "DROP TABLE " . DB_TABLE_PREFIX . "users_groups";
	echo ($verbose ? "Dropping users/Groups table...<br />" : "");
	$dbo->query($sql);

	$sql = "CREATE TABLE " . DB_TABLE_PREFIX . "users_groups (" .
		"id INT(11) PRIMARY KEY AUTO_INCREMENT, " .
		"userid INT(11) NOT NULL, " .
		"groupid INT(11) NOT NULL" .
		") ENGINE = MYISAM";

	echo ($verbose ? "Creating Users/Groups table...<br />" : "");
	$dbo->query($sql);

	$sql = "INSERT INTO " . DB_TABLE_PREFIX . "users_groups (userid, groupid) VALUES (" . $root_id . ", " . $admin_group . ")";
	
	echo ($verbose ? "Inserting root user/admin association...<br />" : "");
	$dbo->query($sql);

	$sql = "DROP TABLE " . DB_TABLE_PREFIX . "sessions";
	echo ($verbose ? "Dropping sessions table...<br />" : "");
	$dbo->query($sql);

	$sql = "CREATE TABLE " . DB_TABLE_PREFIX . "sessions (" .
		"uuid VARCHAR(25) NOT NULL PRIMARY KEY, " .
		"date_updated DATETIME NOT NULL" .
		") ENGINE = INNODB"; /* innoDB for row level locking*/
	echo ($verbose ? "Creating sessions table...<br />" : "");
	$dbo->query($sql);

	$sql = "DROP TABLE " . DB_TABLE_PREFIX . "session_vars";
	echo ($verbose ? "Dropping session variables table...<br />" : "");
	$dbo->query($sql);

	$sql = "CREATE TABLE " . DB_TABLE_PREFIX . "session_vars (" .
		"uuid VARCHAR(25), " .
		"ses_key VARCHAR(25), " .
		"ses_value VARCHAR(500)," .
		"PRIMARY KEY (uuid, ses_key)" . 
		") ENGINE = INNODB"; /* innoDB for row level locking*/
	echo ($verbose ? "Creating session variables table...<br />" : "");
	$dbo->query($sql);

	echo "Finished installing. Have a nice day!!";

?>
