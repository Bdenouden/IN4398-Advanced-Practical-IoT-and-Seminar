<?php
class grid_controller extends Controller
{
    private $model;
    
    public function __construct()
    {
        $this->model = $this->loadModel('grid');

        $this->loadView('grid/test',array(
            'loc_data' => $this->model->getSnlLocData()
        ));
    }
}
