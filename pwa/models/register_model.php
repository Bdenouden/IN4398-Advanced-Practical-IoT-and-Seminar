<?php

class register_model extends Model {


	public function requestAccountConfirmation($username, $email, $password) {

		$result = Database::select("SELECT * FROM users WHERE user_name = :username", array(':username' => $username));

		if(isset($result[0])){

			throw new UserException("An account is already registered with this username!");

		}
		else{

			$result = Database::select("SELECT * FROM users WHERE user_email = :email", array(':email' => $email));

			if(isset($result[0])){

				throw new UserException("An account is already registered with this email address!");
				
			}
			else {

				Database::query("INSERT INTO users (user_name, user_email, user_password, user_last_login) VALUES (:username, :email, :password, :ip)",
					array(
						':username' => $username,
						':email' => $email,
						':password' => $password,
						':ip' => $_SERVER['REMOTE_ADDR']
					)
				);

				$reset_token = Auth::createCode(64);

				Database::query(
					"INSERT INTO password_resets (reset_email, reset_token, reset_ip) VALUES (:email, :code, :ip)",
					[':email' => $email, ':code' => $reset_token, ':ip' => $_SERVER['REMOTE_ADDR']]
				);

				$user_email = Database::select(
					"SELECT user_email, user_name FROM users WHERE user_email=:email AND user_type IN ('admin', 'user')",
					[':email' => $email]
				);

				if (isset($user_email[0]['user_email'])) {
					$mail = new Mailer;
					$mail->setSender();
					$mail->setReceiver($user_email[0]['user_email']);
					$mail->setTitle(WEBSITE_NAME . ': Confirm Account');
					$mail->setMessage('
						Hi '.$user_email[0]['user_name'].'!<br /><br />
						Looks like you want to sign up for the Blockade Simulator, awesome!<br/>
						With this link you can confirm your account and log in!:<br/>
						<a href="http://'.DOMAIN.'/register?token=' . $reset_token.'">http://'.DOMAIN.'/register?token=' . $reset_token.'</a><br />
						<br />
						Regards,<br />
						Team '.WEBSITE_NAME.'<br />
						');
					$mail->send_mail();
				}

				throw new UserException("A mail has been sent with an account confirmation link.<br>Don't forget to check your spam folder!");

			}
		}
	}

	public function confirmAccount($token){

		$auth_token = $_GET['token'];

		$reset_row = Database::select(
			"SELECT * FROM password_resets WHERE reset_token = :token AND reset_status = 0",
			[':token' => $auth_token]
		);

		if(!$reset_row) {

			throw new UserException('Not a valid token');

		}

		$user_row = $this->searchUserByEmail($reset_row[0]['reset_email']);

		if(!$user_row) {

			throw new UserException('No account exists with this emailaddress ' . $reset_row[0]['reset_email']);

		}

		Database::query(
			"UPDATE password_resets SET reset_status = 1 WHERE reset_token = :token",
			[':token' => $reset_row[0]['reset_token']]
		);

		Database::query(
			"UPDATE users SET user_activated = 1 WHERE user_email = :email",
			[':email' => $reset_row[0]['reset_email']]
		);

		Auth::redirect('/login?success=1');

	}

	public function searchUserByEmail($email) {

		return Database::select(
			"SELECT * FROM users WHERE user_email=:email AND user_type IN ('admin', 'user') LIMIT 1",
			array(':email' => $email)
		);

	}

}

?>