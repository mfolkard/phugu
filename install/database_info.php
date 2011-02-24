<?php
	include("../inc/phugu-config.php");

	function getDBConfigForm(){
		echo "<form>\n";
		echo "Database Server IP or Hostname:\n";
		echo "<input type='text' name='db_server' id='db_server' value='127.0.0.1'><br />\n";
		echo "Database Name:\n";
		echo "<input type='text' name='db_name' id='db_name' value='phugu'><br />\n";
		echo "Database Username:\n";
		echo "<input type='text' name='db_username' id='db_username' value='phugu'><br />\n";
		echo "Database Password:\n";
		echo "<input type='password' name='db_password' id='db_password' value='phu9u'><br />\n";
		echo "<input type='button' onclick='sendDBCheck()' value='Test Connection'><br />\n";
		echo "</form>\n";
	}

	include("../inc/page_header.php");
	getDBConfigForm();
	include("../inc/page_footer.php");
?>

<script>
	function sendDBCheck(){
		$.post("/install/test_connection.php", {
				db_server: $("#db_server").val(),
				db_name: $("#db_name").val(),
				db_username: $("#db_username").val(),
				db_password: $.base64.encode($("#db_password").val())
			},
			function(data){
				alert(data);
			});
	}
</script>