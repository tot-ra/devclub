<?php
namespace Gratheon\Devclub\Model;

class Story extends \Gratheon\Core\Model {

	use ModelSingleton;


	final function __construct() {
		parent::__construct('devclub_story');
	}


	public function filterStory($params) {
		if($params['duration'] == '0') {
			$params['status'] = 'openspace';
		}

		if(isset($params['duration'])) {
			$params['duration'] = (int)$params['duration'];
		}

		if($params['title']) {
			$params['title'] = htmlentities($params['title'], ENT_COMPAT, 'UTF-8');
		}
		if($params['authors']) {
			$params['authors'] = htmlentities($params['authors'], ENT_COMPAT, 'UTF-8');
		}
		if($params['description']) {
			$params['description'] = htmlentities($params['description'], ENT_COMPAT, 'UTF-8');
		}

		return $params;
	}


	public function getPublicRatingStoryListOrdered($sortAlgorithm, $userEmail, $voteCount) {
		if($sortAlgorithm == 'mine') {
			$sortingOrder = 'emptyMyVote ASC,';
		}
		else {
			$sortingOrder = '';
		}

		$sortingOrder .= 'emptyAllVotes ASC,';

		switch($sortAlgorithm) {
			case 'mine':
				$sortingOrder .= 't3.position ASC';
				$rateVal       = 'arithmeticAvg';
				$sortingSelect = 'AVG(t2.position) arithmeticAvg';
				break;

			case 'geometric':
				$sortingOrder .= 'geometricAvg ASC';
				$rateVal       = 'geometricAvg';
				$sortingSelect = 'EXP(AVG(LN(t2.position))) geometricAvg';
				break;

			case 'harmonic':
				$sortingOrder .= 'harmonicAvg ASC';
				$rateVal       = 'harmonicAvg';
				$sortingSelect = 'COUNT(t2.storyID)/SUM(1/(t2.position+1)) harmonicAvg';
				break;

			case 'date':
				$sortingOrder .= 'date_added ASC';
				$rateVal       = 'date_added2';
				$sortingSelect = 'DATE_FORMAT(date_added, "%Y.%m.%d") date_added2';
				break;

			case 'harmonic_weight':
//				$voteCount  = $vote->int("1=1", "COUNT(*)");
				$topicCount = $this->int("1=1", "COUNT(id)");

				$sortingOrder .= 'harmonicWeight DESC';
				$rateVal       = 'harmonicWeight';
				$sortingSelect = "
				(
					($voteCount - SQRT( ($voteCount * $voteCount) - POW(COUNT(t2.storyID),2) ))
					/
					( $topicCount - SQRT( ($topicCount * $topicCount) - POW(COUNT(t2.storyID)/SUM(1/(t2.position+1)),2))
				) ) harmonicWeight";

				break;

			case 'arithmetic':
				$sortingOrder .= 'arithmeticAvg ASC';
				$rateVal       = 'arithmeticAvg';
				$sortingSelect = 'AVG(t2.position) arithmeticAvg';
				break;

			case 'absolute':
			default:
				$sortingOrder .= 'totalCount DESC';
				$rateVal       = 'totalCount';
				$sortingSelect = 'COUNT(t2.storyID) totalCount';
				break;
		}


		$query = "SELECT t1.*, " . $sortingSelect . ",
						t3.position IS NULL AS emptyMyVote,
						AVG(t2.position) IS NULL AS emptyAllVotes,
						t3.position,
						t1.ID as id,
						GROUP_CONCAT(t2.position ORDER BY t2.position ASC SEPARATOR ' ') distribution,
						COUNT(t2.user) votes,
						COUNT(t3.user) voted


					FROM devclub_story t1
		            LEFT JOIN devclub_vote t2 ON t1.ID=t2.storyID
		            LEFT JOIN devclub_vote t3 ON t1.ID=t3.storyID AND t3.user='" . $userEmail . "'
		            WHERE status='icebox'
		            GROUP BY t1.ID
		            ORDER BY " . $sortingOrder;

		$list = $this->q($query);

		foreach($list as &$topic) {
			if($rateVal == 'totalCount' || $rateVal=='date_added2') {
				$topic->rate = $topic->{$rateVal};
			}
			else {
				$topic->rate = round(100 * $topic->{$rateVal}) / 100;
			}
		}

		return $list;
	}


	public function getStoryListByStatus($status) {
		return $this->prepare(['status' => $status])->arr(
			"status=:status",
			"*, '' votes, '' rate, '0' voted, '' position"
		);
	}


	public function postProcessStoryList($list, $userEmail, $isAdmin) {
		if($list) {
			foreach($list as &$topic) {
				$topic->owner    = ($topic->creator_email == $userEmail) || $isAdmin;
				$topic->gravatar = md5(strtolower(trim($topic->creator_email)));
				unset($topic->creator_email);
			}
		}

		return $list;
	}


	public function getAuthorsByNamePart($name) {
		$authors = $this->prepare(['name' => $name])->arrint("authors LIKE concat(:name, '%') ", "DISTINCT(authors)");
		return $authors;
	}
}
