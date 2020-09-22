<?php

class api_new_device_controller extends Controller
{

    private $model;

    public function __construct($arg)
    {

        $this->model = $this->loadModel('api');

        if (isset($_POST['API_KEY']) && $this->isValidApiKey($_POST['API_KEY'])) {
            if (isset($_POST['node_id']) && is_string($_POST['node_id'])) {
                if ($this->model->addNewDevice($_POST['node_id'])) {
                    echo json_encode(["success" => true, "message" => "Successfully added a new device!"]);
                }
                else {
                    echo json_encode(["success" => false, "message" => "Something went wrong with adding a new device"]);

                }
            }
            else {
                echo json_encode(["success" => false, "message" => "Missing 'node_id' field!"]);
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
