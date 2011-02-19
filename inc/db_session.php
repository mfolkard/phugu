<?php
	include("session.php");

	/*
		Class that allows session management via the database rather than cookies.
		The session is stored in a structure in the following format $_REQUEST[<uuid>][<key>].
		The session is written to the database at the end of each request via the writeSession function.
	*/
	class db_session extends session {

		private $uuid = '';

		/* Stores the UUID for this object */
		function setUUID($uuid){
			$this->uuid = $uuid;
		}

		/* Returns the session if it exists and hasn't timed out, otherwise it creates a new one */
		function createSession(){
			if($this->uuid == ""){
				$this->createNewSession();
				return;
			}
			
			$sql = "select date_updated from " . DB_TABLE_PREFIX . "sessions where uuid = '" . $this->uuid . "'";
			$result = $GLOBALS['phu_db']->query($sql);
			if($result && mysql_num_rows($result)){
				$row = mysql_fetch_array($result);
				$last_updated = date_create("@" . strtotime($row["date_updated"]));
				$diff = date_diff(date_create(), $last_updated);
				if($diff->i < 20){ /* 20 minute session time out, should be moved to an admin var once implemented */
					$this->populateSession();
					return;
				} 
			}
			$this->createNewSession();
		}

		/* Creates a session from values in the database */	
		function populateSession(){
			$sql = "select ses_key, ses_value from " . DB_TABLE_PREFIX . "session_vars where uuid = '" . $this->uuid . "'";
			$result = $GLOBALS['phu_db']->query($sql);
			if($result){
				while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
					$this->setSessionVar($row["ses_key"], $row["ses_value"]);
				}
			}
		}

		/* A new session */
		function createNewSession(){
			$this->uuid = uniqid('', true);
			$_REQUEST[$this->uuid] = '';
			$_REQUEST['uuid'] = $this->uuid;
		}

		/* Stores the session in the database (called from the footer page at the end of each request) */
		function writeSession(){
			$sql = "replace into " . DB_TABLE_PREFIX . "sessions (uuid, date_updated) values ('" . $this->uuid . "', now())";
			$result = $GLOBALS['phu_db']->query($sql);
			if(!$result){
				echo("Failed to create DB session");
			}
			$sql = "";
			foreach ($_REQUEST[$this->uuid] as $key => $value){
				$GLOBALS['phu_db']->query("replace into " . DB_TABLE_PREFIX . "session_vars (uuid, ses_key, ses_value ) values ('" . $this->uuid . "', '" . $key . "', '" . $value . "'); ");
			}
		}

		/* Removes the session from the database. Called when a user logs out */
		function destroySession(){
			$sql = "delete from " . DB_TABLE_PREFIX . "sessions where uuid = " . $this->uuid;
			$GLOBALS['phu_db']->query($sql);
			$sql = "delete from " . DB_TABLE_PREFIX . "session_vars where uuid = " . $this->uuid;
			$GLOBALS['phu_db']->query($sql);
		}

		/* Gets a session value or empty string if it doesn't exist */
		function getSessionVar($key){
			if(isset($_REQUEST[$this->uuid][$key])){
				return $_REQUEST[$this->uuid][$key];
			} else {
				return "";
			}
		}

		/* Sets a session value */
		function setSessionVar($key, $val){
			return $_REQUEST[$this->uuid][$key] = $val;
		}
	}
?>