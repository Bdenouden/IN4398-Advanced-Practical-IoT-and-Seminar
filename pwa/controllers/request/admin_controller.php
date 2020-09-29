<?php

class admin_controller extends Controller
{

    private $model;

    public function __construct()
    {
        $this->model = $this->loadModel('admin');

    }

    public function getSensorDataForId()
    {
        if (isset($_POST['sensorId']) && is_numeric($_POST['sensorId']) && !empty($_POST['csrf-token']) && array_key_exists($_POST['csrf-token'], $_SESSION['csrf-tokens'])) {
            echo json_encode($this->model->getSensorDataForId($_POST['sensorId']));
        }
    }

    public function removeSensorFromNode()
    {
        if (isset($_POST['sensorId']) && is_numeric($_POST['sensorId']) && !empty($_POST['csrf-token']) && array_key_exists($_POST['csrf-token'], $_SESSION['csrf-tokens'])) {
            $this->model->removeSensorFromNode($_POST['sensorId']);
        }
    }

}
