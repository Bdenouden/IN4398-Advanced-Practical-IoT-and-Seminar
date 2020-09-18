<?php

class password_controller extends Controller {

    private $model;

    public function __construct() {

        $this->model = $this->loadModel('password');

        if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['request-reset'])) {

            try {

                if($this->validateForgotForm()) {

                    $this->model->requestPasswordReset($_POST['email']);

                }

            }
            catch(UserException $e) {

                $this->loadView('forgot-password/request-reset', [
                    'error' => $e->getMessage()
                ]);

            }
            finally {

                $this->loadView('forgot-password/request-reset', [
                    'error' => 'Error Unknown'
                ]);

            }

        }
        else if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reset_password'])) {

            try {

                if($this->validateResetForm()) {

                    if($_POST['password'] != $_POST['password_confirm']) {

                        throw new UserException('password_match');

                    }

                    $this->model->resetPassword();

                }

            }
            catch(Exception $e) {

                $this->loadView('forgot-password/reset-password', [
                    'error' => $e->getMessage()
                ]);

            }
            finally {

                $this->loadView('forgot-password/reset-password', [
                    'error' => 'Error Unknown'
                ]);

            }

        }
        else {

            $this->getView();

        }

    }

    private function validateForgotForm() {

        $form = new Form();

        $fields = array(
            'email' => array(
                'default_error_message' =>'email_invalid',
                'validators' => array(
                    'email' => array(
                        'error_message' => 'email_invalid'
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

    private function validateResetForm() {

        $form = new Form();

        $fields = array(
            'password' => array(
                'default_error_message' => 'password_invalid',
                'validators' => array(
                    'minLength' => array(
                        'helper' => 6,
                        'error_message' => 'password_short'
                    )
                )
            ),
            'password_confirm' => array(
                'default_error_message' => 'password_invalid',
                'validators' => array(
                    'minLength' => array(
                        'helper' => 6,
                        'error_message' => 'password_short'
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

    private function getView() {

        if(isset($_GET['token'])) {

            $this->loadView('forgot-password/reset-password');

        }
        else {

            $this->loadView('forgot-password/request-reset');

        }

    }

}