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
                            foreach ($entry as $sensor_link_id => $sensor_data) {
                                try {
                                    if ($sensor_data['value'] !== null) {
                                        $query_result = $this->model->storeSensorEntry(
                                            $node_chipid,
                                            $sensor_link_id,
                                            $sensor_data['value'],
                                            $measure_time
                                        );
                                        if ($query_result !== true) {
                                            $failures[] = [$node_chipid, $query_result];
                                        } else {
                                            $success = true;
                                        }
                                    }
                                    else {
                                        $success = true;
                                    }
                                }
                                catch (Exception $e) {
                                    $failures[] = [$node_chipid, $e->getMessage()];
                                }
                                catch (TypeError $e) {
                                    $failures[] = [$node_chipid, $e->getMessage()];
                                }
                            }
                        }
                    }

                    if (sizeof($failures) > 0 || !$success) {
                        $this->loadController('e500', ["success" => false, "failures" => $failures]);
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
