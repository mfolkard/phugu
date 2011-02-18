<?php
	
	include("inc/load.php");
	$dbo = $GLOBALS['phu_db'];

	$s = "select * from PHUUSERS";

	$res = $dbo->query($s);

	while($row = mysql_fetch_array($res)){
		echo $row['USERNAME'] . " - " . $row['PASSWORD'] . "<br />";
	}
?>
