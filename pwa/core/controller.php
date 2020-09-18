<?php

class Controller {

	public function loadView($view, $data = array()) {

		try {

			foreach($data as $key => $value) {

				$$key = $value;

			}

			require_once 'views/'.$view.'.php';

		}
		catch(exception $e) {

			throw new Exception($e->getMessage());

		}

	}

	public function loadModel($model) {

		$model .= '_model';

		try {

			require_once 'models/'.$model.'.php';

			return new $model();

		}
		catch(exception $e) {

			throw new Exception($e->getMessage());

		}

	}

	public function loadController($controller, $data = array(), $type = 'complete') {

		$controller .= '_controller';

		try {

			require_once 'controllers/'.$type.'/'.$controller.'.php';
			return new $controller($data);


		}
		catch(exception $e) {

			throw new Exception($e->getMessage());

		}

	}	

}