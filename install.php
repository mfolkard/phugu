<?php

	include("inc/load.php");
	$dbo = $GLOBALS['phu_db'];
	if(isset($_GET['verbose']) && $_GET['verbose']){
		$verbose = true;
	} else {
		$verbose = false;
	}
	$sql = "SELECT COUNT(*) FROM " . DB_TABLE_PREFIX . "USERS";
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


	$sql = "DROP TABLE " . DB_TABLE_PREFIX . "USERS";
	echo ($verbose ? "Dropping users table...<br />" : "");
	$dbo->query($sql);

	$sql = "CREATE TABLE " . DB_TABLE_PREFIX . "USERS (" .
		"ID INT(11) PRIMARY KEY AUTO_INCREMENT, " .
		"USERNAME VARCHAR(20) NOT NULL, " .
		"PASSWORD VARCHAR(20) NOT NULL" .
		") ENGINE = MYISAM";
	echo ($verbose ? "Creating users table...<br />" : "");
	$dbo->query($sql);

	$sql = "INSERT INTO " . DB_TABLE_PREFIX . "USERS (USERNAME, PASSWORD) VALUES ('root', 'root')";
	echo ($verbose ? "Inserting root user...<br />" : "");
	$dbo->query($sql);
	$root_id = mysql_insert_id();
	echo ($verbose ? "Root id:" . $root_id . "<br />" : "");

	$sql = "DROP TABLE " . DB_TABLE_PREFIX . "GROUPS";
	echo ($verbose ? "Dropping groups table...<br />" : "");
	$dbo->query($sql);
	
	$sql = "CREATE TABLE " . DB_TABLE_PREFIX . "GROUPS (" .
		"ID INT(11) PRIMARY KEY AUTO_INCREMENT, " .
		"NAME VARCHAR(20) NOT NULL" .
		") ENGINE = MYISAM";

	echo ($verbose ? "Creating groups table...<br />" : "");
	$dbo->query($sql);

	$sql = "INSERT INTO " . DB_TABLE_PREFIX . "GROUPS (NAME) VALUES ('administrator')";

	echo ($verbose ? "Inserting admin group...<br />" : "");
	$dbo->query($sql);
	$admin_group = mysql_insert_id();
	echo ($verbose ? "Admin group:" . $admin_group . "<br />" : "");

	$sql = "DROP TABLE " . DB_TABLE_PREFIX . "USERS_GROUPS";
	echo ($verbose ? "Dropping users/Groups table...<br />" : "");
	$dbo->query($sql);

	$sql = "CREATE TABLE " . DB_TABLE_PREFIX . "USERS_GROUPS (" .
		"ID INT(11) PRIMARY KEY AUTO_INCREMENT, " .
		"USERID INT(11) NOT NULL, " .
		"GROUPID INT(11) NOT NULL" .
		") ENGINE = MYISAM";

	echo ($verbose ? "Creating Users/Groups table...<br />" : "");
	$dbo->query($sql);

	$sql = "INSERT INTO " . DB_TABLE_PREFIX . "USERS_GROUPS (USERID, GROUPID) VALUES (" . $root_id . ", " . $admin_group . ")";
	
	echo ($verbose ? "Inserting root user/admin association...<br />" : "");
	$dbo->query($sql);

	echo "Finished installing. Have a nice day!!";

?>
