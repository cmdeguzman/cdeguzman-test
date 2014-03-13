<?php

require_once("image.php");
require_once("vote.php");

/** 
 * App - This class controls the flow of the application.
 *
 * @author Christopher Deguzman <taiki@cox.net>
 * @version 1.0
 *
 * @property Image $image Current image being seen by user.
 * @property string $routeTo Class method to execute when execute() is called.
 * @property PDO $_db Private DB instance.
 */

class App { 

	public $image;
	private $_routeTo;
	private $_db;
	/**
	 * Class constructor. Sets the current image metadata.
	 */ 
	public function __construct($imageId = null, $action = null) { 
		// Start session.
		session_start();
		
		$this->_db = DB::getInstance();

		// If no ImageId is given, 
		if($imageId === null) { 
			$imageId = 1;
		}
		try { 
			$this->image = new Image($imageId);
		} catch(Exception $e) { 
			// Obviously in production we'd do something more savory here, but we're kind of pressed for time now.
			die($e->getMessage());
		}
		switch($action) { 
			case 'vote': 
				$this->_routeTo = 'doVote';
				break;
			case 'changeTo':
				$this->_routeTo = 'getImage';
				break;
			default: 
				$this->_routeTo = 'render';
				break;
		}
	}
	
	/** 
	 * Execute the current action.
	 */
	public function execute() { 
		$this->{$this->_routeTo}();
	}

	/**
	 * Renders the main page.
	 */ 

	public function render() {
		require_once(getcwd()."/templates/main.php");
	}

	/** 
	 * Inserts a vote for a given imageId
	 */ 
	
	public function doVote() { 
		$imageId = $_POST['imageId'];
		$voterId = session_id();
		$vote = $_POST['vote'];
		
		$result = array();

		// Check for double votes.
		$query = "SELECT count(*) FROM `vote` WHERE `voterId` = :voterId AND `imageId` = :imageId"; 
		$statement = $this->_db->prepare($query);
		$statement->bindParam(":voterId", $voterId);
		$statement->bindParam(":imageId", $imageId);
		$statement->execute(); 
		$votes = $statement->fetchColumn();
		
		if($votes > 0) { 
			// If attempted double vote, error.
			$result['error'] = 'You have already voted!';	
		} else { 
			// Otherwise record vote and return result.
			$query = "INSERT INTO `vote` (`imageId`, `voterId`, `vote`) VALUES (:imageId, :voterId, :vote)";
			$statement = $this->_db->prepare($query);
			$statement->bindParam(":voterId", $voterId);
			$statement->bindParam(":imageId", $imageId);
			$statement->bindParam(":vote", $vote);
			$statement->execute();
			$this->image->getVotes();
			$result['yes'] = $this->image->getYesVotePercentage();
			$result['no'] = $this->image->getNoVotePercentage();
		}	
		echo json_encode($result);
	}
	
	/** 
	 * Updates the current image and returns the next/prev and vote totals. 
	 */ 
	
	public function getImage() { 
		
		$imageId = $_POST['imageId'];
		
		$result = array();
		// Get new image.
		try { 
			$image = new Image($imageId);
		} catch(Exception $e) { 
			// If no image, catch exception and return error
			$result['error'] = "Error: No image exists";
			echo json_encode($result);
		}
		
		// Populate return.

		$result['next'] = $image->getNextId();
		$result['prev'] = $image->getPrevId();
		$result['yes'] = $image->getYesVotePercentage();
		$result['no'] = $image->getNoVotePercentage(); 
		$result['src'] = $image->path;	
		echo json_encode($result);

	}
}
