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

	public static function requireBasicLogin(){
        $model = (new Controller)->loadModel('login');

        $user_row = false;

        if (isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW'])){
            $user_row = $model->searchUserByUsername($_SERVER['PHP_AUTH_USER']);
        }

        if (!$user_row) {

            header('WWW-Authenticate: Basic realm="'.WEBSITE_NAME.' API Login"');
            header('HTTP/1.0 401 Unauthorized');
            throw new UserException('Your login information is incorrect.');

        }
        else {

            $salt = Auth::getSalt($user_row['user_password']);
            $password = Auth::hash_password($_SERVER['PHP_AUTH_PW'], $salt);

            if ($password == $user_row['user_password']) {

                if ($user_row['user_type'] == 'api') {
                    return true;
                } else {
                    return false;
                }

            } else {

                throw new UserException('Your login information is incorrect.');

            }
        }

    }

    public static function destroySession(){
	    session_destroy();
	    unset($_SERVER);
    }


}