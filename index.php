<?php
	include("inc/load.php");
	include("inc/page_header.php");
	if($_REQUEST['session']->getSessionVar("bob") == ""){
		$_REQUEST['session']->setSessionVar("bob", "Hi my name is bob");
	}
	$var = $_REQUEST['session']->getSessionVar("bob");

	$_REQUEST['session']->setSessionVar("mike", "spikey mikey");

	echo "here is the session value:" . $var . " - the end<br />";
?>

go to the <a href="/index2.php?uuid=<?= @$_REQUEST['uuid'] ?>">second index
<?php
	include("inc/page_footer.php");
	include("inc/footer.php");
?>
