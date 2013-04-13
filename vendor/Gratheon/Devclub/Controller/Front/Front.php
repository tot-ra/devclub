<?php
/**
 * @author Artjom Kurapov
 * @since 20.06.12 15:44
 */

namespace Gratheon\Devclub\Controller\Front;

class Front extends \Gratheon\Core\Controller {
	public $load_config = false;

	use userAccess;

	public function main() {
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

		/** @var $vote \Gratheon\Devclub\Model\Vote */
		$vote = $this->model('Vote');
		$this->assign('voted', $vote->getUserVoteCount($this->getEmail()));
		$this->assign('distinct_users', $vote->getUniqueUsers());

		return $this->view('main.tpl');
	}


	public function story() {
		if(!$this->getEmail()) {
			return false;
		}

		/** @var $storyModel \Gratheon\Devclub\Model\Story */
		$storyModel = $this->model('Story');
		$params     = (array)json_decode(file_get_contents('php://input'));

		$position = $params['position'];
		$status   = $params['status'];

		unset($params['position']);

		$ID = (int)$this->in->URI[3];

		if($ID) {
			if(!$this->checkAdmin()) {
				unset($params['status']);
			}
			unset($params['creator_email']);
			unset($params['id']);
			$params = $storyModel->filterStory($params);

			if($params) {
				$params['ID'] = $ID;
				$storyModel->update($params);
			}

			$story = $storyModel->obj($ID);
			echo json_encode($story);
		}
		else {
			if($params['duration'] > 0) {
				$params['status'] = 'icebox';
			}

			$params['date_added']    = 'NOW()';
			$params['creator_email'] = $this->getEmail();
			$params                  = $storyModel->filterStory($params);

			$ID    = $storyModel->insert($params);
			$story = $storyModel->obj($ID);
		}

		if($status == 'icebox') {
			/** @var $votesModel \Gratheon\Devclub\Model\Vote */
			$votesModel = $this->model('Vote');
			$votesModel->updatePositions($ID, $position, $this->getEmail());
		}

		echo json_encode($story);
	}


	public function list_openspace_stories() {
		/** @var $storyModel \Gratheon\Devclub\Model\Story */
		$storyModel = $this->model('Story');
		$list       = $storyModel->getStoryListByStatus('openspace');

		echo json_encode($list);
	}


	public function list_backlog_stories() {
		/** @var $storyModel \Gratheon\Devclub\Model\Story */
		$storyModel = $this->model('Story');
		$list       = $storyModel->getStoryListByStatus('backlog');
		$list       = $storyModel->postProcessStoryList($list, $this->getEmail(), $this->checkAdmin());

		echo json_encode($list);
	}


	public function list_completed_stories() {
		/** @var $storyModel \Gratheon\Devclub\Model\Story */
		$storyModel = $this->model('Story');
		$list       = $storyModel->q(
			"SELECT t1.*, " . ($this->checkAdmin() ? 'COUNT(t2.user)' : "''") . " votes, '' rate, COUNT(t3.user) voted, '' position
			FROM devclub_story t1
			LEFT JOIN devclub_yearly_vote t2 ON t1.ID=t2.storyID
			LEFT JOIN devclub_yearly_vote t3 ON t1.ID=t3.storyID AND t3.user='" . $this->getEmail() . "'
			WHERE t1.status='completed'
			GROUP BY t1.ID
			ORDER BY " . ($this->checkAdmin() ? 'votes' : 'date_added') . " DESC");

		$list = $storyModel->postProcessStoryList($list, $this->getEmail(), $this->checkAdmin());

		echo json_encode($list);
	}


	public function list_public_stories() {
		/** @var $stories \Gratheon\Devclub\Model\Story */
		$stories = $this->model('Story');
		$vote    = $this->model('devclub_vote');


		$voteCount = $vote->int("1=1", "COUNT(*)");
		$list      = $stories->getPublicRatingStoryListOrdered($this->in->get['sort'], $this->getEmail(), $voteCount);

		foreach($list as &$topic) {
			$topic->owner = ($topic->creator_email == $this->getEmail()) || $this->checkAdmin();

			$topic->gravatar = md5(strtolower(trim($topic->creator_email)));
			unset($topic->creator_email);
		}

		echo json_encode($list);
	}


	public function delete_story() {
		$ID      = (int)$this->in->URI[3];
		$stories = $this->model('devclub_story');
		$story   = $stories->obj($ID);
		if($story && ($this->checkAdmin() || $story->creator_email == $this->getEmail())) {
			$stories->delete($ID);
		}
	}


	public function author_list() {
		if(strlen($this->in->get['term']) < 2) {
			return false;
		}

		$name          = mysql_real_escape_string($this->in->get['term']);
		$devclub_story = $this->model('devclub_story');
		echo json_encode($devclub_story->arrint("authors LIKE '%$name%'", "DISTINCT(authors)"));
	}

	public function yearly_report() {
		if(!$this->checkAdmin()) {
			echo "Login as admin first";
			die();
		}
		$yvotes  = $this->model('devclub_yearly_vote');
		$results = $yvotes->arr("1=1", "devclub_yearly_vote.*, devclub_story.title", "devclub_yearly_vote INNER JOIN devclub_story ON devclub_story.id=devclub_yearly_vote.storyID");

		echo json_encode($results);
	}


	public function yearly_vote() {
		$yvotes = $this->model('devclub_yearly_vote');
		if($this->in->post['ID'] && $this->getEmail()) {
			$yvotes->insert(array(
				'storyID' => (int)$this->in->post['ID'],
				'user'    => $this->getEmail()
			));
		}
		exit();
	}


	public function yearly_unvote() {
		$yvotes = $this->model('devclub_yearly_vote');
		if($this->in->post['ID'] && $this->getEmail()) {
			$yvotes->delete("storyID = '" . (int)$this->in->post['ID'] . "' AND user = '" . $this->getEmail() . "'");
		}
		exit();
	}
}