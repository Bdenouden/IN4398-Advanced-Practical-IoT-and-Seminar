<?php

class e403_controller extends Controller {

	public function __construct($args = array()) {

		if(isset($args[0])){
			switch ($args[0]){
				
				case 1: 
					$message = "Your account level has no access rights for this page" ;
				break;
				
				default:
					$message = "";
				break;
			}
		}
		else{
			$message = "";
		}
		$this->loadView('http_errors/403/content', array('message' => $message));

	}

}

?>