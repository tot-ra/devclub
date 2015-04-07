<?php
/**
 * Configuration class. Stores all sensitive info, including general passwords, DB connections etc.
 */

define('sys_url', "http://devclub.gratheon.com/");
define('sys_url_rel', "/");


define('FB_APP_ID', "");
define('FB_APP_SECRET', "");


class SiteConfig extends Gratheon\Core\Config {

	public $routes = array(
		'front'  => '/Gratheon/Devclub'
//		'content'=> '/Gratheon/CMS'
	);

	public $db = array(
		0 => array(
			'engine'    => 'PDO',
			'server'    => 'localhost',
			'database'  => 'devclub',
			'login'     => '',
			'password'  => ''
		)
	);
}