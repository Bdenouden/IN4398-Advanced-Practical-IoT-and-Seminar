<?php

class logout_controller {

	public function __construct() {

		Database::query(
			"UPDATE sessions SET session_active=0 WHERE session_token=:token",
			array(':token' => $_COOKIE['practical_iot_login'])
		);

		setcookie('practical_iot_login', "", time()-3600);

		session_destroy();

		Auth::redirect('/');

	}
	

}

?>