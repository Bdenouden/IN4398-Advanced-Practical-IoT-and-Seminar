<?php
Class setup_controller extends Controller{

    private $model;

    public function __construct($arg){

        $this->model = $this->loadModel('setup');

        if (!strpos($_SERVER['REQUEST_URI'], "setup")) {
            Auth::redirect('/setup');
        }

        $this->loadView('setup/main', array(
//			'users' => $this->model->getAllUsers()
//			'user_levels' => $this->model->getUserLevels()
        ));

    }

}
