<?php

class User {

	private $user_data;

	public function __construct() {

		$_SESSION['user']['data'] = array();
		$_SESSION['user']['session_exists'] = false;
		$_SESSION['user']['permissions'] = array();

		if(isset($_COOKIE['practical_iot_login'])) {

			$this->getUserFromCookie($_COOKIE['practical_iot_login']);

		}

	}

	public static function session_exists() {

		return $_SESSION['user']['session_exists'];

	}

	public static function g($key) {

		if(User::session_exists()) {

			if(array_key_exists($key, $_SESSION['user']['data'])) {

				return $_SESSION['user']['data'][$key];

			}
			else {

				return false;

			}

		}
		else {

			return false;

		}

	}

	public static function hasPermission($permission_key) {

		return in_array($permission_key, $_SESSION['user']['permissions']);

	}

	public static function updateUserInfo(){

		$user_data = Database::select("SELECT * FROM users WHERE user_id = :id", array(':id' => self::g('user_id')));
		$_SESSION['user']['data'] = $user_data[0];

	}


	private function getUserFromCookie($token) {

		$user_data = Database::select("SELECT * FROM users LEFT JOIN sessions ON users.user_id = sessions.user_id WHERE session_token = :token", array(':token' => $token));

		if(count($user_data) > 0){

			$_SESSION['user']['data'] = $user_data[0];
			$_SESSION['user']['session_exists'] = true;
		}

	}

	public static function userMinimalAccessLevel($level){

		if($level == '') {
			return true;
		}
		elseif($level == 'admin' && in_array(User::g('user_type'), array('admin'))){
			return true;
		}
		elseif($level == 'user' && in_array(User::g('user_type'), array('admin', 'user')) ){
			return true;
		}
		else{
			return false;
		}
	}
}
