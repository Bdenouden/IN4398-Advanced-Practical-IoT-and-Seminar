<?php

class setup_controller extends Controller
{

    private $model;

    public function __construct($arg)
    {

        if (Page::isInitialSetupCompleted()){
            Auth::redirect('/');
        }

        $this->model = $this->loadModel('setup');

        if (!strpos($_SERVER['REQUEST_URI'], "setup")) {
            Auth::redirect('/setup');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register-account'])) {

            try {

                if ($this->validateRegisterForm()) {

                    $salt = Auth::createCode(13);
                    $password = Auth::hash_password($_POST['password'], $salt);

                    $this->model->requestAccountConfirmation(Auth::esc($_POST['username']), $password);

                }

            } catch (UserException $e) {
                $this->loadView('setup/main', [
                    'notification' => $e->getMessage()
                ]);

            } finally {
                $this->loadView('setup/main', [
                    'error' => 'Error Unknown'
                ]);
            }

        } else {

            $this->loadView('setup/main');

        }
    }

    private function validateRegisterForm() {

        $form = new Form();

        $fields = array(
            'username' => array(
                'default_error_message' => 'Invalid username',
                'validators' => array(
                    'alphaNumeric' => array(
                        'error_message' => 'Username contains invalid characters'
                    ),
                    'notEmpty' => array(
                        'error_message' => 'Username may not be empty'
                    )
                )
            ),
            'password' => array(
                'default_error_message' => 'Invalid password',
                'validators' => array(
                    'alphaNumericExtra' => array(
                        'error_message' => 'Invalid characters in password'
                    ),
                    'notEmpty' => array(
                        'error_message' => 'Password may not be empty'
                    )
                )
            ),
            'password_confirm' => array(
                'default_error_message' => 'Invalid confirm password',
                'validators' => array(
                    'alphaNumericExtra' => array(
                        'error_message' => 'Invalid characters in confirm password'
                    ),
                    'notEmpty' => array(
                        'error_message' => 'Confirm password may not be empty'
                    )
                )
            )
        );

        try {

            $form->isValidPost($fields);

        }
        catch(Exception $e) {

            throw new Exception($e->getMessage());

        }

        return true;

    }

}
