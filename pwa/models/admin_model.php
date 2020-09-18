<?php

class admin_model extends Model {

	public function getAllUsers() {
		
		return Database::select("SELECT * FROM users");
	}

	public function getUserLevels() {

		return Database::select("SELECT * FROM user_levels");
	}

}

?>


