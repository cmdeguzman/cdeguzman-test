<?php

require_once("config.php");

/** 
 * DB class. Creates a singleton that creates a single PDO object.
 *
 * @author Christopher Deguzman <taiki@cox.net>
 * 
 * @property PDO $_db PDO instance belonging to the current instance.
 * @property DB $_instance Static DB instance
 */ 

class DB { 
	private $_db;
	static $_instance;

	// Build new PDO object.

	private function __construct() { 
		$this->_db = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_USER, DB_PASS);
	}

	public static function getInstance() { 
		if(!(self::$_instance instanceof self)) { 
			self::$_instance = new self();
		}
		// We're not concerned with abstracting PDO away, we just want a 
		// single point of origin of config for the PDO object.

		return self::$_instance->getDb();
	}

	// Return DB instance.

	public function getDb() { 
		return $this->_db;
	}

}
