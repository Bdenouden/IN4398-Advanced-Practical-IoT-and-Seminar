<?php

class api_update_controller extends Controller
{

    private $model;

    public function __construct($arg)
    {

        $this->model = $this->loadModel('api');

        try {
            $data = json_decode(file_get_contents('php://input'), true);
        }
        catch (Exception $e){
            $this->loadController('e403');
        }

        $api_key = $data['API_KEY'];

        $failures = [];

        if (isset($api_key) && $this->isValidApiKey($api_key)) {
            foreach ($data as $node_chipid => $entry) {
                if (is_array($entry)) {
                    foreach ($entry as $sensor_uid => $sensor_data){
                        if(!$this->model->storeSensorEntry($node_chipid, $sensor_uid, $sensor_data['name'], $sensor_data['value'], $sensor_data['unit'])){
                            $failures[] = [$node_chipid, $sensor_uid];
                        }
                    }
                }
            }

            if (sizeof($failures) > 0){
                echo json_encode("Ran into one or more failures!");
                echo json_encode($failures);
            }
            else {
                echo json_encode(["succes" => true, "message" => "Successfully stored all sensor data entries!"]);
            }

        } else {
            $this->loadController('e403');
        }

    }

    public static function isValidApiKey($api_key)
    {
        return $api_key == 123;
    }

}
