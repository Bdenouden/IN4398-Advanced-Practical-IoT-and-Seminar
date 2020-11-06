<?php

class data_controller extends Controller {

    private $model;

    public function __construct() {

        $this->model = $this->loadModel('admin');

        $this->loadView('data/content', array(
            'sensor_data' => $this->model->getAdminPageSensorData()
        ));

    }

}

?>