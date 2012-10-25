<?php
/**
 * @author Artjom Kurapov
 * @since 20.06.12 15:44
 */

namespace Gratheon\Devclub\Controller\Front;

class Front extends \Gratheon\Core\Controller {

	private $admins = array(
		//'artkurapov@gmail.com',
		'soswow@gmail.com',
		'ant.arhipov@gmail.com',
		'jevgeni.holodkov@gmail.com',
		'yuri.mulenko@gmail.com',
		'draco.ater@gmail.com',
		'kirill.linnik@mail.ee',
		'andrei.solntsev@gmail.com'
	);

    public $load_config = false;


	private function getEmail() {
		return $_SESSION[__CLASS__]['auth_email'];
	}


	function main() {
		$this->add_css('/vendor/twitter/bootstrap/css/bootstrap.css');
		$this->add_css('/vendor/twitter/bootstrap/css/bootstrap-responsive.css');
		$this->add_css('main.css', false);
		$this->add_css('/vendor/jquery/jquery-ui/themes/base/jquery.ui.all.css', false);

		$this->add_js('https://browserid.org/include.js', false);

		$this->add_js('/vendor/jquery/jquery/jquery-1.7.2.js');
		$this->add_js('/vendor/twitter/bootstrap/js/bootstrap.min.js');
		$this->add_js('/vendor/backbonejs/underscorejs/underscore-min.js');
		$this->add_js('/vendor/backbonejs/backbonejs/backbone-min.js');
		$this->add_js('/vendor/jquery/jquery-ui/ui/jquery.ui.core.js');
		$this->add_js('/vendor/jquery/jquery-ui/ui/jquery.ui.widget.js');
		$this->add_js('/vendor/jquery/jquery-ui/ui/jquery.ui.mouse.js');
		$this->add_js('/vendor/jquery/jquery-ui/ui/jquery.ui.autocomplete.js');
		$this->add_js('/vendor/jquery/jquery-ui/ui/jquery.ui.draggable.js');
		$this->add_js('/vendor/jquery/jquery-ui/ui/jquery.ui.droppable.js');
		$this->add_js('/vendor/jquery/jquery-ui/ui/jquery.ui.sortable.js');

		$this->add_js('touch-punch.js');
		$this->add_js('main.js');

		$this->add_js_var('sys_url', sys_url_rel);

		$this->assign('email', $this->getEmail());

		$votes = $this->model('devclub_vote');
		$this->assign('voted', $votes->int("user='" . $this->getEmail() . "'", "COUNT(*)"));

		$this->assign('distinct_users', $votes->int("1=1", "COUNT(DISTINCT(user))"));

		return $this->view('main.tpl');
	}


	function story() {
		if (!$this->getEmail()) {
			return false;
		}

		$stories = $this->model('devclub_story');
		$votes   = $this->model('devclub_vote');

		$params = (array)json_decode(file_get_contents('php://input'));

		$position = $params['position'];
		$status   = $params['status'];
		unset($params['position']);


		$ID = (int)$this->input->URI[3];
		if ($ID) {
			$story = $stories->obj($ID);

			if (!$this->checkAdmin()) {
				unset($params['status']);
			}
			unset($params['creator_email']);


			if($params['duration']=='0'){
				$params['status'] = 'openspace';
			}

			if ($params['title']) {
				$params['title'] = htmlentities($params['title'], ENT_COMPAT, 'UTF-8');
			}
			if ($params['authors']) {
				$params['authors'] = htmlentities($params['authors'], ENT_COMPAT, 'UTF-8');
			}
			if ($params['description']) {
				$params['description'] = htmlentities($params['description'], ENT_COMPAT, 'UTF-8');
			}


			if ($params) {
				$stories->update($params, "ID='$ID'");
			}

			echo json_encode($story);
		}
		else {

			if($params['duration']=='0'){
				$status = 'openspace';
			}
			else{
				$status = 'icebox';
			}

			$ID = $stories->insert(
				array(
                    'title'         => htmlentities($params['title'], ENT_COMPAT, 'UTF-8'),
                    'authors'       => htmlentities($params['authors'], ENT_COMPAT, 'UTF-8'),
                    'description'   => htmlentities($params['description'], ENT_COMPAT, 'UTF-8'),
                    'duration'      => (int)$params['duration'],
                    'date_added'    => 'NOW()',
                    'status'        => $status,
                    'creator_email' => $this->getEmail()
               )
			);

			$story = $stories->obj($ID);
		}

		if ($status == 'icebox') {
			$votes->delete("storyID='$ID' AND `user`='" . $this->getEmail() . "'");


			//recreate proper order for single user
			$userVotes = $votes->arr(
				"`user`='" . $this->getEmail() . "' ORDER BY POSITION ASC", "*",
				"devclub_vote t1 INNER JOIN devclub_story t2 ON t2.ID=t1.storyID AND t2.status='icebox'"
			);

			$i = 0;

			if ($userVotes) {
				foreach ($userVotes as $vote) {
					if ($i == $position) {
						$votes->insert(array(
						                    'storyID'  => $ID,
						                    'user'     => $this->getEmail(),
						                    'position' => $position
						               ));
						$i++;
					}

					$votes->update(array('position'=> $i), "storyID='{$vote->storyID}' AND user='" . $this->getEmail() . "'");
					$i++;
				}
				if($position>=count($userVotes)){
					$votes->insert(
						array(
	                    'storyID'  => $ID,
	                    'user'     => $this->getEmail(),
	                    'position' => count($userVotes)
	               ));
				}
			}
			else {
				$votes->insert(
					array(
                    'storyID'  => $ID,
                    'user'     => $this->getEmail(),
                    'position' => 0
               ));
			}
		}

		echo json_encode($story);
	}


	function list_openspace_stories() {
		$stories = $this->model('devclub_story');
		$list = $stories->arr("status='openspace'", "*, '' votes, '' rate, '0' voted, '' position");

		echo json_encode($list);
	}


	function list_backlog_stories() {
		$stories = $this->model('devclub_story');
		$list = $stories->arr("status='backlog'", "*, '' votes, '' rate, '0' voted, '' position");

		echo json_encode($list);
	}


	function list_completed_stories() {
		$stories = $this->model('devclub_story');
		$list = $stories->q(
			"SELECT t1.*, COUNT(t2.user) votes, '' rate, COUNT(t3.user) voted, '' position
			FROM devclub_story t1
			LEFT JOIN devclub_yearly_vote t2 ON t1.ID=t2.storyID
			LEFT JOIN devclub_yearly_vote t3 ON t1.ID=t3.storyID AND t3.user='".$this->getEmail()."'
			WHERE t1.status='completed'
			GROUP BY t1.ID
			ORDER BY votes DESC");

		foreach ($list as &$topic) {

			$topic->owner = ($topic->creator_email==$this->getEmail()) || $this->checkAdmin();

			$topic->gravatar = md5(strtolower(trim($topic->creator_email)));
			unset($topic->creator_email);

			//round(100 * (float)$vote->int("storyID='" . $topic->ID . "'", "1 + AVG(position)")) / 100;
		}

		echo json_encode($list);
	}


	function list_public_stories() {
		$stories = $this->model('devclub_story');
		$vote    = $this->model('devclub_vote');

		if($_GET['sort']=='mine'){
			$sortingOrder='emptyMyVote ASC,';
		}
		else{
			$sortingOrder = '';
		}

		$sortingOrder.='emptyAllVotes ASC,';

		switch($_GET['sort']){
			case 'mine':
				$sortingOrder .= 't3.position ASC';
				$rateVal = 'arithmeticAvg';
				$sortingSelect = 'AVG(t2.position) arithmeticAvg';
				break;

			case 'geometric':
				$sortingOrder .= 'geometricAvg ASC';
				$rateVal = 'geometricAvg';
				$sortingSelect = 'EXP(AVG(LN(t2.position))) geometricAvg';
				break;

			case 'harmonic':
				$sortingOrder .= 'harmonicAvg ASC';
				$rateVal = 'harmonicAvg';
				$sortingSelect = 'COUNT(t2.storyID)/SUM(1/(t2.position+1)) harmonicAvg';
				break;

			case 'harmonic_weight':
				$voteCount = $vote->int("1=1","COUNT(*)");
				$topicCount = $stories->int("1=1","COUNT(id)");

				$sortingOrder .= 'harmonicWeight DESC';
				$rateVal = 'harmonicWeight';
				$sortingSelect = "
				(
					($voteCount - SQRT( ($voteCount * $voteCount) - POW(COUNT(t2.storyID),2) ))
					/
					( $topicCount - SQRT( ($topicCount * $topicCount) - POW(COUNT(t2.storyID)/SUM(1/(t2.position+1)),2))
				) ) harmonicWeight";

				break;

			case 'arithmetic':
				$sortingOrder .= 'arithmeticAvg ASC';
				$rateVal = 'arithmeticAvg';
				$sortingSelect = 'AVG(t2.position) arithmeticAvg';
				break;

			case 'absolute':
			default:
				$sortingOrder .= 'totalCount DESC';
				$rateVal = 'totalCount';
				$sortingSelect = 'COUNT(t2.storyID) totalCount';
				break;
		}



		$query = "SELECT t1.*, ".$sortingSelect.",
						t3.position IS NULL AS emptyMyVote,
						AVG(t2.position) IS NULL AS emptyAllVotes,
						t3.position,
						t1.ID as id,
						GROUP_CONCAT(t2.position ORDER BY t2.position ASC SEPARATOR ' ') distribution,
						COUNT(t2.user) votes,
						COUNT(t3.user) voted


					FROM devclub_story t1
		            LEFT JOIN devclub_vote t2 ON t1.ID=t2.storyID
		            LEFT JOIN devclub_vote t3 ON t1.ID=t3.storyID AND t3.user='" . $this->getEmail() . "'
		            WHERE status='icebox'
		            GROUP BY t1.ID
		            ORDER BY " . $sortingOrder;

		$list = $stories->q($query);

		foreach ($list as &$topic) {
/*
			$topic->voted = $vote->int("storyID='" . $topic->ID . "' AND user='" . $this->getEmail() . "'", "COUNT(*)");
			$topic->votes = $vote->int("storyID='" . $topic->ID . "'", "COUNT(user)");
*/
			if($rateVal=='totalCount'){
				$topic->rate  = $topic->{$rateVal};
			}
			else{
				$topic->rate  = round(100 * $topic->{$rateVal})/100;
			}

			$topic->owner = ($topic->creator_email==$this->getEmail()) || $this->checkAdmin();

			$topic->gravatar = md5(strtolower(trim($topic->creator_email)));
			unset($topic->creator_email);

			//round(100 * (float)$vote->int("storyID='" . $topic->ID . "'", "1 + AVG(position)")) / 100;
		}

		echo json_encode($list);
	}


	function delete_story() {
		$ID      = (int)$this->input->URI[3];
		$stories = $this->model('devclub_story');
		$story   = $stories->obj($ID);
		if ($story && ($this->checkAdmin() || $story->creator_email == $this->getEmail())) {
			$stories->delete($ID);
		}
	}


	function logout() {
		unset($_SESSION[__CLASS__]['auth_email']);
	}


	function login() {
		$url  = 'https://browserid.org/verify';
		$data = http_build_query(array(
		                              'assertion' => $_POST['assertion'],
		                              'audience'  => urlencode('gratheon.com')
		                         ));

		$params = array(
			'http' => array(
				'method'  => 'POST',
				'content' => $data,
				'header'  => "Content-type: application/x-www-form-urlencoded\r\n"
						. "Content-Length: " . strlen($data) . "\r\n"
			)
		);

		$ctx = stream_context_create($params);
		$fp  = fopen($url, 'rb', false, $ctx);

		if ($fp) {
			$result = stream_get_contents($fp);
		}
		else {
			$result = FALSE;
		}

		$json = json_decode($result);

		if ($json->status == 'okay') {
			$_SESSION[__CLASS__]['auth_email'] = $json->email;
		}

		$this->user();
	}


	function user() {
		echo json_encode(array(
		                      'email'  => $this->getEmail(),
		                      'isAdmin'=> $this->checkAdmin()
		                 ));
	}


	function checkAdmin() {
		return in_array($this->getEmail(), $this->admins);
	}


	function author_list() {
		if (strlen($_GET['term']) < 2) {
			return false;
		}

		$name          = mysql_real_escape_string($_GET['term']);
		$devclub_story = $this->model('devclub_story');
		echo json_encode($devclub_story->arrint("authors LIKE '%$name%'", "DISTINCT(authors)"));
	}


	function yearly_vote(){
		$yvotes = $this->model('devclub_yearly_vote');
		if($_POST['ID'] && $this->getEmail()){
			$yvotes->insert(array(
				'storyID' => (int)$_POST['ID'],
				'user' => $this->getEmail()
			));
		}
		exit();
	}


	function yearly_unvote(){
		$yvotes = $this->model('devclub_yearly_vote');
		if($_POST['ID'] && $this->getEmail()){
			$yvotes->delete("storyID = '".(int)$_POST['ID']."' AND user = '".$this->getEmail()."'");
		}
		exit();
	}
}