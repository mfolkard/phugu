<?php

	$connect = @mysql_connect($_POST['db_server'], $_POST['db_username'], base64_decode($_POST['db_password']), true);
	if (!$connect){
		echo "Connecting to databse failed!!\nThe error was:\n" . mysql_error();
		exit();
	} else {
		$select = @mysql_select_db($_POST['db_name'], $connect);
		if (!$select){
			echo "Selecting the database failed!!\nThe error was:\n" . mysql_error();
			mysql_close($connect);
			exit();
		}
	}
	echo "Connecting to database was successful!!";
	mysql_close($connect);
?>