<?php
Class set_triggers_controller extends Controller{

    private $model;
    private $api_model;

    public function __construct($arg){

        $this->model = $this->loadModel('admin');
        $this->api_model = $this->loadModel('api');

        $this->loadView('admin/triggers', array(
            'nodes' => Api::aggregateSensorsPerNode($this->api_model->getKnownDevices()),
            'triggers' => Api::aggregateTriggersPerNode($this->model->getTriggers()),
        ));

    }

}
