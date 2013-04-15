<?php
/**
 * @author Artjom Kurapov
 * @since 12.04.13 23:28
 */
namespace Gratheon\Devclub\Model;

class Vote extends \Gratheon\Core\Model {

	use ModelSingleton;


	final function __construct() {
		parent::__construct('devclub_vote');
	}


	public function getUserVoteCount($email) {
		return $this->prepare(array("email" => $email))->int("user=:email", "COUNT(*)");
	}


	public function getUniqueUsers() {
		return $this->int("1=1", "COUNT(DISTINCT(user))");
	}


	public function getUserVotedStoriesOrdered($email) {
		return $this->prepare(['user' => $email])->q(
			"SELECT * FROM devclub_vote t1
			INNER JOIN devclub_story t2 ON t2.ID=t1.storyID AND t2.status='icebox'
			WHERE `user`=:user
			ORDER BY `position` ASC", 'array'
		);
	}


	public function updatePositions($storyID, $position, $email) {
		//remove previous vote for this story and user
		$this->delete("storyID='$storyID' AND `user`='" . $email . "'");


		//recreate proper order for single user
		$userVotes = $this->getUserVotedStoriesOrdered($email);
		$i         = 0;

		if($userVotes) {
			foreach($userVotes as $vote) {
				//insert new vote and increment the following positions by 1
				if($position>=$i && $position<($i+1)) {
					$this->insert(array(
						'storyID'  => $storyID,
						'user'     => $email,
						'position' => (string)$position
					));
					$i++;
				}

				$this->update(
					['position' => (string)$i],
					"storyID='{$vote->storyID}' AND user='" . $email . "'"
				);

				$i++;
			}

			if((int)$position >= count($userVotes)) {
				$this->insert(array(
					'storyID'  => $storyID,
					'user'     => $email,
					'position' => $i//count($userVotes)
				));
			}
		}
		else {
			$this->insert(array(
				'storyID'  => $storyID,
				'user'     => $email,
				'position' => 0
			));
		}
	}
}
