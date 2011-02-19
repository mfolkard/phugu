<?php
	
	include("inc/load.php");
	$var = $_REQUEST['session']->getSessionVar("bob");
	echo "session value:" . $var . " - the end<br />";
	$_REQUEST['session']->setSessionVar("bob", "mamakakakakamaka");

	include("inc/footer.php");
?>
