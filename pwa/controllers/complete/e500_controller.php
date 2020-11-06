<?php

class e500_controller extends Controller {

	public function __construct(?array $data) {

	    if (!isset($data)){
            $this->loadView('http_errors/500/content', array('message' => ''));
        }
	    else {
            $this->loadView('http_errors/500/content', array(
                'message' => $data
            ));
        }

	}

}

?>