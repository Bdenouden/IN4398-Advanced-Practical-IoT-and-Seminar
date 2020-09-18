<?php
Class admin_controller extends Controller{

	private $model;

	public function __construct($arg){

		$this->model = $this->loadModel('admin');

		$this->loadView('admin/main', array(
//			'users' => $this->model->getAllUsers()
//			'user_levels' => $this->model->getUserLevels()
		));

	}

}
