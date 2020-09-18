<?php

class e404_controller extends Controller {

	public function __construct() {
		
		header($_SERVER['SERVER_PROTOCOL'].' 404 Not Found');
		
		$this->loadView('http_errors/404/content', array('message' => ''));

	}

}

?>
