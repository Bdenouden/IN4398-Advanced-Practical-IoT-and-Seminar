<?php
Class home_controller extends Controller{

	public function __construct($arg){

        $this->model = $this->loadModel('admin');

        $this->loadView('data/content', array(
            'sensor_data' => $this->model->getAdminPageSensorData()
        ));

	}

}
