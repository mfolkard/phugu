<?php
	include("session.php");

	/*
		Class that acts as a wrapper for normal session access.
		Used so that session storage can be easily swapped with db_session.
	*/
	class cookie_session extends session {

		/* Gets a session value or empty string if it doesn't exist */
		function getSessionVar($key){
			if(isset($_SESSION[$key])){
				return $_SESSION[$key];
			} else {
				return "";
			}
		}

		/* Sets a session value */
		function setSessionVar($key, $val){
			return $_SESSION[$key] = $val;
		}
	}
?>