<?php
/**
 * Configuration class. Stores all sensitive info, including general passwords, DB connections etc.
 */

define('app_path', '/Gratheon/Devclub');
define('sys_url', "http://localhost/devclub/");

ini_set('display_errors', 1);
error_reporting(-1);

class SiteConfig extends Gratheon\Core\Config {
	public $db = array(
		0 => array(
			'engine'    => 'Mysqli',
			'server'    => '',
			'database'  => '',
			'login'     => '',
			'password'  => ''

		)
	);
}