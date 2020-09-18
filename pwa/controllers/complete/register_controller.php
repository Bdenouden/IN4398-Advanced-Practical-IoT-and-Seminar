<?php
Class register_controller extends Controller{

	private $model;

	public function __construct($arg){

		$this->model = $this->loadModel('register');

		if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register-account'])) {

			try {

				if($this->validateRegisterForm()) {

					$salt = Auth::createCode(13);
					$password = Auth::hash_password($_POST['password'], $salt);

					$this->model->requestAccountConfirmation(Auth::esc($_POST['username']), Auth::esc($_POST['email']), $password);

				}

			}
			catch(UserException $e) {

				$this->loadView('register/content', [
					'notification' => $e->getMessage()
				]);

			}
			finally {

				$this->loadView('register/content', [
					'error' => 'Error Unknown'
				]);

			}

		}
		else{

			$this->getView();

		}

	}

	private function validateRegisterForm() {

		$form = new Form();

		$fields = array(
			'email' => array(
				'default_error_message' => 'Invalid email address',
				'validators' => array(
					'email' => array(
						'error_message' => 'Invalid email address'
					)
				)
			),
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

	private function getView() {

		if(isset($_GET['token'])) {

			try{

				$this->model->confirmAccount($_GET['token']);

			}
			catch(UserException $e) {

				$this->loadView('register/confirm', [
					'error' => $e->getMessage()
				]);

			}
			finally {

				$this->loadView('register/confirm', [
					'error' => 'Error Unknown'
				]);

			}

		}
		else {

			$this->loadView('register/content');

		}

	}

}
