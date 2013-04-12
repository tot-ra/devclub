<?php
/**
 * Configuration class. Stores all sensitive info, including general passwords, DB connections etc.
 */

define('sys_url', "http://devclub.gratheon.com/");
define('sys_url_rel', "/");


class SiteConfig extends Gratheon\Core\Config {

	public $routes = array(
		'front'  => '/Gratheon/Devclub',
		'content'=> '/Gratheon/CMS'
	);

	public $db = array(
		0 => array(
			'engine'    => 'PDO',
			'server'    => '',
			'database'  => 'devclub',
			'login'     => 'devclub',
			'password'  => ''
		)
	);
}