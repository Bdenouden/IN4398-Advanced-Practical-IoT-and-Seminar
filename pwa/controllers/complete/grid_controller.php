<?php
class grid_controller extends Controller
{
    private $model;
    
    public function __construct()
    {
        $this->model = $this->loadModel('grid');

        if (isset($_POST['id']) && isset($_POST['x']) && isset($_POST['y'])) {

            $this->model->setSnlLoc($_POST['id'], $_POST['x'], $_POST['y']);

            return json_encode(["success" => true, "message" => "Successfully stored all sensor data entries!"]);
        }

        return $this->loadView('grid/index',array(
            'loc_data' => $this->model->getSnlLocData()
        ));
    }
}
