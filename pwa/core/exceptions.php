<?php

class UserException extends exception {

	public function __construct($exceptionText){
		
		if(config::_c('show_user_exceptions')){

			parent::__construct($exceptionText);

		}

		else{

			parent::__construct('An error has occurred!');
		}
		
		error_log('UserException: '.$this->getMessage().' In: '. $this->getTraceAsString());
		
	}
}

class SystemException extends exception {

	public function __construct($exceptionText){
		
		if(config::_c('show_system_exceptions')){

			parent::__construct($exceptionText);
		}

		else{

			parent::__construct('An error has occurred!');
		}
		
		error_log('SystemException: '.$this->getMessage().' In: '. $this->getTraceAsString());
		
	}
}

