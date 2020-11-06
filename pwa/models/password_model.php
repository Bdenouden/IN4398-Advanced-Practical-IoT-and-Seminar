<?php

class password_model extends Model {

    public function __construct() {

    }

    public function requestPasswordReset($email) {

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
            $mail->setTitle(WEBSITE_NAME . ': Reset Password');
            $mail->setMessage('
                Hi '.$user_email[0]['user_name'].'!<br /><br />
                Someone told me you forgot your password...<br/>
                With this link you can configure a new one:<br/>
                <a href="http://'.DOMAIN.'/forgotpassword?token=' . $reset_token.'">http://'.DOMAIN.'/forgotpassword?token=' . $reset_token.'</a><br />
                <br />
                Regards,<br />
                Team '. WEBSITE_NAME .'<br />
                ');
            $mail->send_mail();
        }

        throw new UserException("A mail has been sent with a password reset link.<br>Don't forget to check your spam folder!");

    }

    public function resetPassword() {

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

        $salt = Auth::createCode(13);
        $password = Auth::hash_password($_POST['password'], $salt);

        Database::query(
            "UPDATE users SET user_password = :password WHERE user_id = :id",
            [':id' => $user_row[0]['user_id'], ':password' => $password]
        );

        Database::query(
            "UPDATE password_resets SET reset_status = 1 WHERE reset_token = :token",
            [':token' => $reset_row[0]['reset_token']]
        );

        Auth::redirect('/login');

    }

    public function searchUserByEmail($email) {

        return Database::select(
            "SELECT * FROM users WHERE user_email=:email AND user_type IN ('admin', 'user') LIMIT 1",
            array(':email' => $email)
        );

    }

}
