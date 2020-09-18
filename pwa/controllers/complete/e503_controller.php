<?php

class e503_controller extends Controller {

	public function __construct() {

		$this->loadView('http_errors/503/content', array('message' => 'The server is currently down for maintenance'));

	}

}

?>