<?php

class login_controller extends Controller
{

    public function __construct()
    {

        $notification = '';

        try {

            if (isset($_GET['setup_success']) && $_GET['setup_success'] == "true") {

                $notification = "Successfully completed initial setup! Enjoy";
            }

            if ($_SERVER['REQUEST_METHOD'] == 'POST') {

                $this->validateForm();

                $this->model = $this->loadModel('login');

                if ($this->login()) {
                    Auth::redirect('/admin');
                } else {
                    Auth::redirect('/' . Auth::esc(str_replace('-', '/', $_GET['next'])));
                }

            }

            $this->loadView('login/content', array('notification' => $notification));

        } catch (exception $e) {

            $this->loadView('login/content', array('error' => $e->getMessage()));

        }


    }

    private function validateForm()
    {

        $_POST['save_session'] = (!isset($_POST['save_session'])) ? 0 : 1;

        $form = new Form();

        $fields = array(
            'username' => array(
                'default_error_message' => 'Invalid username',
                'validators' => array(
                    'notEmpty' => array(),
                    'minLength' => array(
                        'helper' => 4,
                        'error_message' => 'Your username is too short, it must be at least 4 characters'
                    ),
                    'maxLength' => array(
                        'helper' => 20,
                        'error_message' => 'Your username it too long, it may only be 20 characters'
                    ),
                )
            ),
            'password' => array(
                'default_error_message' => 'Invalid password',
                'validators' => array(
                    'notEmpty' => array(),
                    'minLength' => array(
                        'helper' => 6,
                        'error_message' => 'Your password is too short, it must be at least 6 characters'
                    )
                )

            ),
            'save_session' => array(
                'validators' => array()
            )
        );

        try {

            $form->isValidPost($fields);

        } catch (exception $e) {

            throw new exception($e->getMessage());

        }

        return true;

    }

    private function login()
    {

        $user_row = $this->model->searchUserByUsername($_POST['username']);

        if ($user_row === false) {

            throw new UserException('Your login information is incorrect.');

        }

        $salt = Auth::getSalt($user_row['user_password']);
        $password = Auth::hash_password($_POST['password'], $salt);

        if ($password == $user_row['user_password']) {

            $token = Auth::createCode(64);

            $time = ($_POST['save_session']) ? time() + 60 * 60 * 24 * 180 : 0;

            $this->createSession($token, $time);

            $this->model->saveSession($user_row['user_id'], $token, date("Y-m-d H:i:s", $time));

            if ($user_row['user_type'] == 'admin') {
                return true;
            } else {
                return false;
            }

        } else {

            throw new UserException('Your login information is incorrect.');

        }

    }

    public function createSession($token, $time)
    {

        setcookie(
            'practical_iot_login',
            $token,
            $time,
            '/'
        );

    }

}

?>