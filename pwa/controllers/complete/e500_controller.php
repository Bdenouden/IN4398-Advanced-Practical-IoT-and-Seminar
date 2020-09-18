<?php

class e500_controller extends Controller {

	public function __construct() {

		$this->loadView('http_errors/500/content', array('message' => ''));

	}

}

?>