<?php

class api_get_devices_controller extends Controller
{

    private $model;

    public function __construct($arg)
    {

        $this->model = $this->loadModel('api');

        if (isset($_POST['API_KEY']) && $this->isValidApiKey($_POST['API_KEY'])) {
            echo json_encode($this->model->getKnownDevices());
        } else {
            $this->loadController('e403');
        }

    }

    public static function isValidApiKey($api_key)
    {
        return $api_key == 123;
    }

}
