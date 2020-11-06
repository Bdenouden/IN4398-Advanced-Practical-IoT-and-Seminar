<?php
setlocale(LC_TIME, 'dutch');
date_default_timezone_set('Europe/Amsterdam');

define("WEBSITE_NAME", "ModFarm");

define('WEBSITE_DOMAIN', 'https://' . $_SERVER['HTTP_HOST']);
define('WEBSITE_MAIL_ADDRESS', '');
define('WEBSITE_MAIL_PASSWORD', '');
define('WEBSITE_MAIL_NAME', WEBSITE_NAME . " - Team");
define('WEBSITE_MAIL_HOST', '');

class Config {

	public static $settings;

	public function __construct() {

		try {

			$this->setSettings();

			$this->connectDatabase();

			$this->createCSRFtoken();

		}
		catch(exception $e) {

			throw new Exception($e->getMessage());

		}

	}

	private function createCSRFtoken() {

		$new_token = md5(rand(0,10000).$_SERVER['REMOTE_ADDR']);

		$_SESSION['csrf-token'] = $new_token;
		$_SESSION['csrf-tokens'][$new_token] = time();

		foreach($_SESSION['csrf-tokens'] as $token => $time) {

			if($time < time() - 1800) {
				unset($_SESSION['csrf-tokens'][$token]);
			}

		}

	}

	private function setSettings() {

		try {

			self::$settings = get_object_vars(include 'config_local.php');

		}
		catch(exception $e) {

			throw new Exception('Settings could not be loaded');

		}


	}

	public static function _c($key) {

		return (isset(self::$settings[$key])) ? self::$settings[$key] : null;

	}


	private function connectDatabase() {

		try {

			Database::connect($this->_c('database_host'), $this->_c('database_name'), $this->_c('database_username'), $this->_c('database_password'));

		}
		catch(exception $e) {

			throw new Exception($e->getMessage());

		}



	}

}