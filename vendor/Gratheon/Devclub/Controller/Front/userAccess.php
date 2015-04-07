<?php
namespace Gratheon\Devclub\Controller\Front;

use Facebook\FacebookRequest;
use Facebook\FacebookSession;
use Facebook\GraphUser;

trait userAccess {

	private $admins = array(
		//'artkurapov@gmail.com',
		'soswow@gmail.com',
		'ant.arhipov@gmail.com',
		'jevgeni.holodkov@gmail.com',
		'yuri.mulenko@gmail.com',
		'draco.ater@gmail.com',
		'kirill.linnik@mail.ee',
		'andrei.solntsev@gmail.com',
		'webervin@gmail.com'
	);


	private function getSessionValue($key) {
		if(!isset($_SESSION[__CLASS__])) {
			return null;
		}

		return $_SESSION[__CLASS__][$key];
	}


	private function getEmail() {
		return $this->getSessionValue('auth_email');
	}


	public function logout() {
		unset($_SESSION[__CLASS__]['auth_email']);
	}


	public function login() {
		$url  = 'https://browserid.org/verify';
		$data = http_build_query(array(
			'assertion' => $this->in->post['assertion'],
			'audience'  => urlencode('devclub.gratheon.com')
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

		if($fp) {
			$result = stream_get_contents($fp);
		}
		else {
			$result = FALSE;
		}

		$json = json_decode($result);

		if($json->status == 'okay') {
			$_SESSION[__CLASS__]['auth_email'] = $json->email;
		}

		$this->user();
	}

	public function loginFB(){
		$accessToken = $this->in->get('token');
		FacebookSession::setDefaultApplication(FB_APP_ID, FB_APP_SECRET);
		$session = new FacebookSession($accessToken);
		$request = new FacebookRequest($session, 'GET', '/me');
		$response = $request->execute();
		$graphObject = $response->getGraphObject(GraphUser::className());
		$email = $graphObject->getEmail();
		if($email){
			$_SESSION[__CLASS__]['auth_email'] =	$email;
		}
		$this->user();
	}

	public function user() {
		echo json_encode(array(
			'email'   => $this->getEmail(),
			'isAdmin' => $this->checkAdmin()
		));
	}


	public function checkAdmin() {
		return in_array($this->getEmail(), $this->admins);
	}
}