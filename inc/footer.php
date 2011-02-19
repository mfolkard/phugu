<?php

	if(SESSION_STORE == 'db'){
		$_REQUEST['session']->writeSession();
	}
?>