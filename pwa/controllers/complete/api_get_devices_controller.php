<?php

class api_get_devices_controller extends Controller
{

    private $model;

    public function __construct($arg)
    {
        $this->model = $this->loadModel('api');

        try {
            $login = Auth::requireBasicLogin();

            if ($login){
                echo json_encode($this->model->getKnownDevices());
            }
            else {
                $this->loadController('e403');
            }
        } catch (UserException $e) {
            $this->loadController('e403');
        }

    }


}
