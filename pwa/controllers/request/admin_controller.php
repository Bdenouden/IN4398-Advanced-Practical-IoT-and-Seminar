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
            echo json_encode($this->model->removeSensorFromNode($_POST['sensorId']));
        }
    }

    public function addSensorToNode()
    {
        if (isset($_POST['nodeId']) && isset($_POST['sensorId']) && is_numeric($_POST['sensorId']) && !empty($_POST['csrf-token']) && array_key_exists($_POST['csrf-token'], $_SESSION['csrf-tokens'])) {
            echo json_encode($this->model->addSensorToNode($_POST['sensorId'], $_POST['nodeId']));
        }
    }

    public function addTriggerToSensor()
    {
        if (isset($_POST['linkId']) && isset($_POST['ltGt']) && isset($_POST['triggerVal']) && isset($_POST['notificationChoice'])
            && is_numeric($_POST['linkId']) && is_numeric($_POST['ltGt']) && is_numeric($_POST['triggerVal']) && is_numeric($_POST['notificationChoice'])
            && !empty($_POST['csrf-token']) && array_key_exists($_POST['csrf-token'], $_SESSION['csrf-tokens'])) {

            echo json_encode($this->model->addTriggertoSensor($_POST['linkId'], $_POST['ltGt'], $_POST['triggerVal'], $_POST['notificationChoice']));

        }
    }

}

