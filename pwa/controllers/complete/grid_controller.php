<?php
class grid_controller extends Controller
{
    private $model;

    public function __construct()
    {
        $this->model = $this->loadModel('grid');
        $error = 'none';


        if (isset($_POST['id']) && isset($_POST['x']) && isset($_POST['y'])) {

            $this->model->setSnlLoc($_POST['id'], $_POST['x'], $_POST['y']);

            return json_encode(["success" => true, "message" => "Successfully stored all sensor data entries!"]);
        }

        if (isset($_GET['action'])) {
            switch ($_GET['action']) {
                case 'delete':
                    if (isset($_GET['id'])) {
                        // TODO delete snl_loc entry
                        $this->model->delete($_GET['id']);
                    }
                    break;

                case 'add':
                    if (isset($_GET['snl_id'])) {
                        // TODO add to snl_loc
                        $this->model->add($_GET['snl_id']);
                    }
                    break;

                case 'settings':
                    // $error = 'settings';
                    if (isset($_GET['radius']) && isset($_GET['snap']) && isset($_GET['mapName'])) {
                        $error = $this->model->setUserSettings($_GET['radius'], $_GET['snap'], $_GET['mapName']);
                    }
                default:
                    break;
            }
        }

        return $this->loadView('grid/index', array(
            'error' => $error,
            'loc_data' => $this->model->getSnlLocData(),
            'snl_list' => $this->model->getSnl(),
            'settings' => $this->model->getUserSettings()
        ));
    }

    public function actionTest()
    {
        echo 'test';
    }
}
