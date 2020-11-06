<?php

class Database {

	public static $mysql;

	public static function connect($host, $database, $user, $password) {

		try {

			self::$mysql = new PDO("mysql:host=".$host.";dbname=".$database, $user, $password);
			self::$mysql->exec("SET CHARACTER SET utf8");
			self::$mysql->exec("SET lc_time_names = 'nl_NL'");

		}
		catch(exception $e) {

			throw new SystemException($e->getMessage());

		}

	}

	public static function select($sql, $vars = array()) {

		$query = self::$mysql->prepare($sql);

		$query->execute($vars);

		$errors = $query->errorInfo();

		if($errors[0] != '00000') {

			throw new SystemException($errors[2]);

		}
		else {

			return $query->fetchAll(PDO::FETCH_ASSOC);

		}

	}

	public static function query($sql, $vars = array(), $rows_affected = 0) {
		
		$query = self::$mysql->prepare($sql);

		$query->execute($vars);

		$errors = $query->errorInfo();

		if($errors[0] != '00000') {

			throw new SystemException($errors[2]);

		}
		else {

			if ($rows_affected) {

				return $query->rowCount();

			} 
			else {

			return true;

			}

		}

	}
	
	public static function getLastId(){
		
		return self::$mysql->lastInsertId();
	}

	public static function beginTransaction(){
		
		self::$mysql->beginTransaction();
	}

	public static function commit(){
		
		self::$mysql->commit();
	}

	public static function rollBack(){
		
		self::$mysql->rollBack();
	}

}
