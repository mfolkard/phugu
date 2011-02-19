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
		"password VARCHAR(20) NOT NULL," .
		"status SMALLINT(2) NOT NULL default " . DISABLED .
		") ENGINE = MYISAM";
	echo ($verbose ? "Creating users table...<br />" : "");
	$dbo->query($sql);


	$sql = "INSERT INTO " . DB_TABLE_PREFIX . "users (username, password, status) VALUES ('root', MD5('root'), " . LIVE . ")";
	echo ($verbose ? "Inserting root user...<br />" : "");
	$dbo->query($sql);
	$root_id = mysql_insert_id();
	echo ($verbose ? "Root id:" . $root_id . "<br />" : "");



	$sql = "DROP TABLE " . DB_TABLE_PREFIX . "groups";
	echo ($verbose ? "Dropping groups table...<br />" : "");
	$dbo->query($sql);
	
	$sql = "CREATE TABLE " . DB_TABLE_PREFIX . "groups (" .
		"id INT(11) PRIMARY KEY AUTO_INCREMENT, " .
		"name VARCHAR(20) NOT NULL, " .
		"status SMALLINT(2) NOT NULL default " . DISABLED .
		") ENGINE = MYISAM";

	echo ($verbose ? "Creating groups table...<br />" : "");
	$dbo->query($sql);

	$sql = "INSERT INTO " . DB_TABLE_PREFIX . "groups (name, status) VALUES ('administrator', " . LIVE . ")";

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

	

	$sql = "DROP TABLE " . DB_TABLE_PREFIX . "keys";
	echo ($verbose ? "Dropping keys table...<br />" : "");
	$dbo->query($sql);

	$sql = "CREATE TABLE " . DB_TABLE_PREFIX . "keys (" .
		"id INT(11) PRIMARY KEY AUTO_INCREMENT, " .
		"objectid INT(11) NOT NULL, " .
		"userid INT(11), " .
		"groupid INT(11), " .
		"level SMALLINT(2) NOT NULL, " .
		"status SMALLINT(2) NOT NULL default " . DISABLED .
		") ENGINE = MYISAM";

	echo ($verbose ? "Creating keys table...<br />" : "");
	$dbo->query($sql);

	$sql = "INSERT INTO " . DB_TABLE_PREFIX . "keys (userid, groupid, level, status, objectid) " .
	"VALUES (" . $root_id . ", " . $admin_group . ", " . KEY_WRITE . ", " . LIVE . ", " . SYS_OBJ . ")";
	
	echo ($verbose ? "Inserting root user/admin group system write key...<br />" : "");
	$dbo->query($sql);



	$sql = "DROP TABLE " . DB_TABLE_PREFIX . "objects";
	echo ($verbose ? "Dropping objects table...<br />" : "");
	$dbo->query($sql);

	$sql = "CREATE TABLE " . DB_TABLE_PREFIX . "objects (" .
		"id INT(11) PRIMARY KEY AUTO_INCREMENT, " .
		"name varchar(255)" .
		") ENGINE = MYISAM";

	echo ($verbose ? "Creating objects table...<br />" : "");
	$dbo->query($sql);



	$sql = "DROP TABLE " . DB_TABLE_PREFIX . "object_fields";
	echo ($verbose ? "Dropping object fields table...<br />" : "");
	$dbo->query($sql);

	$sql = "CREATE TABLE " . DB_TABLE_PREFIX . "object_fields (" .
		"id INT(11) PRIMARY KEY AUTO_INCREMENT, " .
		"objectid int(11) NOT NULL, " .
		"name varchar(255) NOT NULL, " .
		"type varchar(100) NOT NULL" . 
		") ENGINE = MYISAM";

	echo ($verbose ? "Creating object fields table...<br />" : "");
	$dbo->query($sql);



	$objects = explode(",", "users,groups,keys");
	foreach($objects as $object){
		$sql = "INSERT INTO " . DB_TABLE_PREFIX . "objects (name) VALUES ('" . $object . "')";
		echo ($verbose ? "Inserting " . $object . " object...<br />" : "");
		$dbo->query($sql);
		$obj_id = mysql_insert_id();
		switch ($object) {
			case "users":
				$fields = array( 'id' => 'int(11)',	'username' => 'varchar(20)', 'password' => 'varchar(20)', 'status' => 'smallint(2)');
			break;
			case "groups":
				$fields = array( 'id' => 'int(11)',	'name' => 'varchar(20)', 'status' => 'smallint(2)');
			break;
			case "keys":
				$fields = array('id' => 'int(11)', 'objectid' => 'int(11)', 'userid' => 'int(11)', 'userid' => 'int(11)', 'level' => 'smallint(2)', 'status' => 'smallint(2)');
			break;
		}
		echo ($verbose ? "Inserting fields for " . $object . " object...<br />" : "");
		foreach($fields as $field => $type){
			$sql = "INSERT INTO " . DB_TABLE_PREFIX . "object_fields (objectid, name, type) VALUES ('" . $obj_id . "', '" . $field . "', '" . $type . "')";
			$dbo->query($sql);
		}
	}



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
