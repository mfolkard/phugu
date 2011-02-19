<?php
	include("phugu-config.php");
	include("db.php");
	$GLOBALS['phu_db'] = $phu_db;

	if(isset($_GET["uuid"]) && (trim($_GET["uuid"]) != "")){
		$_REQUEST["uuid"] = $_GET["uuid"];
	}

	if(SESSION_STORE == 'cookie'){
		session_start();
		include("cookie_session.php");
		if(!isset($_REQUEST['session'])){
			$_REQUEST['session'] = new cookie_session();
		}
	} else {
		include("db_session.php");
		$_REQUEST['session'] = new db_session();
		if(isset($_REQUEST["uuid"]) && (trim($_REQUEST["uuid"]) != "")){
			$_REQUEST['session']->setUUID($_REQUEST["uuid"]);
		}
		$_REQUEST['session']->createSession();
		/*print_r($_REQUEST[$_REQUEST["uuid"]]);
		echo "<br /><br /><br /><br />";*/
	}

?>
