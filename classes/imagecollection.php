<?php

require_once("db.php");
require_once("image.php");

/** 
 * Image Collection class, represents a collection of images.
 *
 * @author Christopher Deguzman <taiki@cox.net>
 *
 * @version 1.0
 */ 

class ImageCollection { 

	// Current is the current page we are pulling from 
	// Per Page is the number of images per page.
	// Images is an array of image objects.

	public $current;
	public $perPage;
	public $images;

	private $_db;
	
	/**  
	 * Constructor - Pulls first 
	 *
	 */ 
	public function __construct($perPage = null) { 
		$this->_db = DB::getInstance();
		
		// Default to 5 per page for now.
		if($perPage !== null ) { 
			$this->per:qPage = 5;
		} else { 
			$this->perPage = $perPage;
		}
		$query = "SELECT * FROM image ORDER BY imageId ASC LIMIT :perPage";
		$statement = $this->_db->prepare($query);
		$statement->bindParam(":perPage", $this->perPage);
	}


}

?>
