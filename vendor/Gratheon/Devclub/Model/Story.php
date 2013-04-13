<?php
namespace Gratheon\Devclub\Model;

class Story extends \Gratheon\Core\Model {

	use ModelSingleton;


	final function __construct() {
		parent::__construct('devclub_story');
	}

	public function filterStory($params){
		if($params['duration']=='0'){
			$params['status'] = 'openspace';
		}

		if(isset($params['duration'])){
			$params['duration'] = (int)$params['duration'];
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

		return $params;
	}

}
