<?php

require_once("db.php");

/** 
 * Vote class, represents a given vote for a given image.
 * 
 * @author Christopher Deguzan <taiki@cox.net>
 * @version 1.0
 *
 * @property int $voteId Primary key of the vote object.
 * @property string $voterId PHP Session hash of the current user.
 * @property int $imageId Primary key of the image object this vote is attached to
 * @property int $vote Value of the vote, 0 down, 1 up.
 */ 

class Vote { 

	// Vote ID is the primary key
	// Voter ID is the session ID of a given voter. 
	// Vote rigging can occur if a user flushes cookies - But this is supposed to be simple so...
	// Image ID is the primary key for the image.
	// Vote - value of the vote, 0 - Down, 1 - up
	
	public $voteId;
	public $voterId;
	public $imageId;
	public $vote;
	private $_db;

	public function __construct($voteId = null) { 
		$this->_db = DB::getInstance();
		if($voteId !== null ) { 
			$query = "SELECT * FROM `vote` WHERE `voteId` = :voteId";
			$this->_db->prepare($query);
			$this->_db->bindParam(":voteId", $voteId);
			$this->_db->execute();
			$voteData = $this->_db->fetch();
			if($voteData) { 
				foreach($voteData as $col => $value) { 
					$this->$col = $value;
				}
			} else { 
				throw new Exception("No vote found");
			}
		}
	}

	/** 
	 * Save function - Saves a given vote to the DB.
	 *
	 * @return bool Result of the PDO statement save.
	 */

	public function save() { 
		// Set up query
		$query = "INSERT INTO `vote` (`voterId`, `imageId`, `vote`) VALUES (:voterId, :imageId, :vote)";
		
		// Prepare and bind parameters.
		$statement = $this->_db->prepare($query);
		$statement->bindParam(":voterId", $this->voterId);
		$statement->bindParam(":imageId", $this->imageId);
		$statement->bindParam(":vote", $this->vote);

		// Return the value of the save.
		return $statement->execute();
	}
}
?>
