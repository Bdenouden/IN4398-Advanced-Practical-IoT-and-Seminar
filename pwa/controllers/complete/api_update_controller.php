<?php

class api_update_controller extends Controller
{

    private $model;

    public function __construct($arg)
    {

        $this->model = $this->loadModel('api');

        try {
            $login = Auth::requireBasicLogin();

            if ($login) {
                try {
                    $data = json_decode(file_get_contents('php://input'), true);
                } catch (Exception $e) {
                    $this->loadController('e403');
                }

                if (!isset($data)) {
                    $this->loadController('e403');
                } else {

                    $failures = [];
                    $success = false;

                    foreach ($data as $node_chipid => $entry) {
                        if (is_array($entry)) {
                            if (isset($entry['measure_time'])){
                                $measure_time = $entry['measure_time'];
                                unset($entry['measure_time']);
                            }
                            else {
                                $measure_time = null;
                            }
                            foreach ($entry as $sensor_uid => $sensor_data) {
                                $query_result = $this->model->storeSensorEntry($node_chipid, $sensor_uid, $sensor_data['name'], $sensor_data['value'], $sensor_data['unit'], $measure_time);
                                if ($query_result !== true) {
                                    $failures[] = [$node_chipid, $query_result];
                                }
                                else {
                                    $success = true;
                                }
                            }
                        }
                    }

                    if (sizeof($failures) > 0 || !$success) {
                        echo json_encode("Ran into one or more failures!");
                        echo json_encode($failures);
                    } else {
                        echo json_encode(["success" => true, "message" => "Successfully stored all sensor data entries!"]);
                    }
                }
            } else {
                $this->loadController('e403');
            }
        } catch
        (UserException $e) {
            $this->loadController('e403');
        }

    }

}
