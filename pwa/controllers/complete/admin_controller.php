<?php
Class admin_controller extends Controller{

	private $model;

	public function __construct($arg){

		$this->model = $this->loadModel('admin');

		$this->loadView('admin/main', array(
		    'sensor_data' => $this->model->getAdminPageSensorData()
		));

	}

}
