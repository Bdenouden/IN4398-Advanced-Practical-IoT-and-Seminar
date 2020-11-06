<?php
Class link_modules_controller extends Controller{

	private $model;
	private $api_model;

	public function __construct($arg){

		$this->model = $this->loadModel('admin');
		$this->api_model = $this->loadModel('api');

		$this->loadView('admin/link', array(
		    'sensor_data' => $this->model->getAdminPageSensorData(),
            'nodes' => Api::aggregateSensorsPerNode($this->api_model->getKnownDevices()),
            'sensor_types' => $this->model->getSensorTypes(),
            'triggers' => Api::aggregateTriggersPerNode($this->model->getTriggers()),
		));

	}

}
