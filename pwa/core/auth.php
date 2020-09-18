<?php

class Auth {

	public static function hash_password($password, $salt) {

		return hash('sha512', $password.$salt).$salt;

	}

	public static function getSalt($password_hash) {

		return substr($password_hash, -13);

	}

	public static function createCode($length) {

		$string = "abcdefghijkmnopqrstuvwxyz023456789";
		$chars = str_split($string);
		$count = count($chars) -1;
		$result = null;

		for($a = 0; $a < $length; $a++) {
			$result .= $chars[rand(0,$count)];
		} 

		return $result;
	}


	public static function redirect($header) {

		header('location: '.$header);
		exit;

	}
	
	public static function esc($input) {
		
		return htmlentities($input);
	}


}