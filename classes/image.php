<?php

require_once("db.php");

/** 
 * Image class, represents a given image.
 * 
 * @author Christopher Deguzan <taiki@cox.net>
 * @version 1.0
 *
 * @property int $imageId Primary key for the image
 * @property string $path Path of the image on the disk.
 * @property int $yesVotes Number of yes votes for this image.
 * @property int $noVotes Number of no votes for this image.
 * @property PDO $_db PDO instance of the currently instantiated DB
 */ 

class Image { 

	// Image ID is the primary key for the image.
	// Path is the path to mthe image.

	public $imageId;
	public $path;
	
	public $yesVotes; 
	public $noVotes;

	private $_db;

	/**
	 * Class constructor, populates the image data for the ID given.
	 */ 
	public function __construct($imageId = null) { 
		$this->_db = DB::getInstance();
		if($imageId !== null ) { 
			$query = "SELECT * FROM `image` WHERE `imageId` = :imageId";
			$statement = $this->_db->prepare($query);
			$statement->bindParam(":imageId", $imageId);
			$statement->execute();
			$imageData = $statement->fetch(PDO::FETCH_ASSOC);
			if($imageData) { 
				foreach($imageData as $col => $value) { 
					$this->$col = $value;
				}

				// Pre-grab the votes for this image.
				$this->getVotes();
			} else { 
				throw new Exception("No image found");
			}
		}
	}


	/** 
	 * Grabs the votes and calculates the totals
	 */ 
	public function getVotes() { 

			// Start with the nos. 
			$query = "SELECT count(voteId) FROM `vote` WHERE `imageId` = :imageId AND `vote` = 0";
			$statement = $this->_db->prepare($query);
			$statement->bindParam(":imageId", $this->imageId);
			$statement->execute();
			$voteCount = $statement->fetchColumn();
			if(!$voteCount) { 
				$this->noVotes = 0;
			} else { 
				$this->noVotes = $voteCount;
			}
			// Then do the yeses.
			$query = "SELECT count(voteId) FROM `vote` WHERE `imageId` = :imageId AND `vote` = 1";
			$statement = $this->_db->prepare($query);
			$statement->bindParam(":imageId", $this->imageId);
			$statement->execute();
			$voteCount = $statement->fetchColumn();
			if(!$voteCount) { 
				$this->yesVotes = 0;
			} else { 
				$this->yesVotes = $voteCount;
			}

	}

	/** 
	 * Returns the next image's primary key from the DB by the current primary key.
	 *
	 * @return mixed Returns next image's primary key if there is one, false otherwise.
	 */ 
	public function getNextId() {
		$query = "SELECT `imageId` FROM `image` WHERE `imageId` > :imageId ORDER BY `imageId` ASC LIMIT 1";
		$statement = $this->_db->prepare($query);
		$statement->bindParam(":imageId", $this->imageId);
		if($statement->execute()) { 
			$imageId = $statement->fetchColumn();
			return $imageId;
		} else { 
			return false;
		}
	}

	/**
	 * Returns the previous image's primary key from the DB by the current primary key
	 *
	 * @return mixed Return previous image primary key if there is one, false otherwise.
	 */ 
	public function getPrevId() {
		$query = "SELECT `imageId` FROM `image` WHERE `imageId` < :imageId ORDER BY `imageId` DESC LIMIT 1";
		$statement = $this->_db->prepare($query);
		$statement->bindParam(":imageId", $this->imageId);
		if($statement->execute()) { 
			$imageId = $statement->fetchColumn();
			return $imageId;
		} else { 
			return false;
		}
	}
	
	/** 
	 * Returns the number of yes votes in terms of percentage.
	 *
	 * @return int Returns the integer value of number of yes votes as a percentage of the whole
	 */ 
	public function getYesVotePercentage() { 
		$total = $this->yesVotes + $this->noVotes;
		if($this->yesVotes == 0) { 
			return 0;
		} else {
			return floor(($this->yesVotes) / ($total) * 100);
		}
	}
		
	/** 
	 * Returns the number of no votes in terms of percentage.
	 *
	 * @return int Returns the integer value of number of no votes as a percentage of the whole
	 */ 

	public function getNoVotePercentage() { 
		$total = $this->yesVotes + $this->noVotes;
		if($this->noVotes == 0 ) { 
			return 0;
		} else { 
			return floor(($this->noVotes / $total) * 100);
		}
	}

}
