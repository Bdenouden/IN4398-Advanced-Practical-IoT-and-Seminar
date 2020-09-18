<?php

class login_model extends Model {


	public function searchUserByUsername($username) {

		$result = Database::select("SELECT * FROM users WHERE user_name = :username", array(':username' => $username));
		return (count($result) == 0) ? false : $result[0];

	}

	public function saveSession($user_id, $token, $time) {

		return Database::query("INSERT INTO sessions(user_id, session_token, session_expiration) VALUES (:user_id, :token, :time)", 
			array(':user_id' => $user_id, ':token' => $token, ':time' => $time));
		
	}

}

?>


