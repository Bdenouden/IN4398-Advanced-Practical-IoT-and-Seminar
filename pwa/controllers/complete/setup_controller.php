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

        if (isset($_GET['setup_success']) && $_GET['setup_success'] == "false") {

            $error = "Something went wrong in the setup process, please try again";

            $this->loadView('setup/main', array('error' => $error));

        }

        else if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register-account'])) {

            try {

                if ($this->validateRegisterForm()) {

                    $salt = Auth::createCode(13);
                    $password = Auth::hash_password($_POST['password'], $salt);

                    $api_salt = Auth::createCode(13);
                    $api_password = Auth::hash_password($_POST['api_password'], $api_salt);

                    $this->model->initialAccountSetup(Auth::esc($_POST['username']), $password, Auth::esc($_POST['api_username']), $api_password);

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
            ),
            'api_username' => array(
                'default_error_message' => 'Invalid API username',
                'validators' => array(
                    'alphaNumeric' => array(
                        'error_message' => 'API username contains invalid characters'
                    ),
                    'notEmpty' => array(
                        'error_message' => 'API username may not be empty'
                    )
                )
            ),
            'api_password' => array(
                'default_error_message' => 'Invalid API password',
                'validators' => array(
                    'alphaNumericExtra' => array(
                        'error_message' => 'Invalid characters in API password'
                    ),
                    'notEmpty' => array(
                        'error_message' => 'API password may not be empty'
                    )
                )
            ),
            'api_password_confirm' => array(
                'default_error_message' => 'Invalid confirm API password',
                'validators' => array(
                    'alphaNumericExtra' => array(
                        'error_message' => 'Invalid characters in confirm API password'
                    ),
                    'notEmpty' => array(
                        'error_message' => 'Confirm API password may not be empty'
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
