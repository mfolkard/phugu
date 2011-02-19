<?php
	/*
		Class for database access
	*/
	class phugu_db {

		/* Constructor: creates the connection and then selects the DB being used*/
		function __construct($dbuser, $dbpassword, $dbname, $dbserver) {
			$this->conn = mysql_connect($dbserver, $dbuser, $dbpassword, true);
			$this->selectDB($dbname, $this->conn);
		}

		/* Wrapper to select the Phugu DB*/
		function selectDB($dbname, $conn){
			mysql_select_db($dbname, $conn);
		}

		/* Sends a query to the database and returns the result if successful*/
		function query($sql){
			$db_res = mysql_query($sql, $this->conn);
			if (!$db_res){
				echo mysql_error($this->conn);
			}
			return $db_res;
		}

		/* Close the database connection*/
		function DBclose(){
			if($this->conn){
				mysql_close($this->conn);
			}
		}

		/* Destructor: Tidy up the database connection*/
		function __destruct(){
			$this->DBclose();
		}
	}

	if (!isset($phu_db)) {
		$phu_db = new phugu_db(DB_USER, DB_PASSWORD, DB_NAME, DB_SERVER);
	}

?>
