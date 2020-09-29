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
                $devices = $this->model->getKnownDevices();

                $output = [];
                foreach($devices as $device){
                    $sensor_data = [
                        "id" => $device["id"],
                        "name" => $device["name"],
                        "type" => $device["type"],
                        "rawMinVal" => $device["rawMinVal"],
                        "rawMaxVal" => $device["rawMaxVal"],
                        "minVal" => $device["minVal"],
                        "maxVal" => $device["maxVal"]
                    ];

                    if (array_key_exists($device["node_id"], $output)){
                        $output[$device["node_id"]]["sensors"][] = $sensor_data;
                    }
                    else {
                        $output[$device["node_id"]] = [
                            "id" => $device["id"],
                            "added" => $device["added"],
                            "is_active" => $device["is_active"],
                            "sensors" => [$sensor_data]
                        ];
                    }
                }

                echo json_encode($output);
            }
            else {
                $this->loadController('e403');
            }
        } catch (UserException $e) {
            $this->loadController('e403');
        }

    }


}
