<?php
	/*
		Class for database access
	*/
	class phugu_db {

		/* Constructor: creates the connection and then selects the DB being used*/
		function __construct($dbuser, $dbpassword, $dbname, $dbserver, $dbreaduser, $dbreadpassword) {
			$this->conn = mysql_connect($dbserver, $dbuser, $dbpassword, true);
			$this->conn_read = mysql_connect($dbserver, $dbreaduser, $dbreadpassword, true);
			$this->selectDB($dbname, $this->conn);
			$this->selectDB($dbname, $this->conn_read);
		}

		/*Support for PHP 4 which doesn't look for the __construct method by default*/
		function phugu_db($dbuser, $dbpassword, $dbname, $dbserver, $dbreaduser, $dbreadpassword) {
			$this->__construct($dbuser, $dbpassword, $dbname, $dbserver, $dbreaduser, $dbreadpassword);
		}

		/* Wrapper to select the Phugu DB*/
		function selectDB($dbname, $conn){
			mysql_select_db($dbname, $conn);
		}

		/* Sends a query to the database using admin connection*/
		function query($sql){
			return $this->doQuery($sql, $this->conn);
		}

		/* Sends a query to the database using read only connection*/
		function queryReadOnly($sql){
			return $this->doQuery($sql, $this->conn_read);
		}

		/* Sends a query to the database and returns the result if successful*/
		function doQuery($sql, $connection){
			
			$db_res = mysql_query($sql, $connection);
			if (!$db_res){
				echo mysql_error($connection);
			}
			return $db_res;
		}

		/* Close the database connection*/
		function DBclose(){
			if($this->conn){
				mysql_close($this->conn);
			}
			if($this->conn_read){
				mysql_close($this->conn_read);
			}
		}

		/* Destructor: Tidy up the database connection*/
		function __destruct(){
			$this->DBclose();
		}
	}

	if (!isset($phu_db)) {
		$phu_db = new phugu_db(DB_ADMIN_USER, DB_ADMIN_PASSWORD, DB_NAME, DB_SERVER, DB_READ_USER, DB_READ_PASSWORD);
	}

?>
