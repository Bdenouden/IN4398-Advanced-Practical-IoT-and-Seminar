<?php

class e401_controller extends Controller {

	public function __construct() {

		$this->loadView('http_errors/401/content', array('message' => ''));

	}

}

?>