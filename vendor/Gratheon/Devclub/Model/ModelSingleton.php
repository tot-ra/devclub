<?php
/**
 * @author Artjom Kurapov
 * @since 07.04.13 13:17
 */

namespace Gratheon\Devclub\Model;

trait ModelSingleton{
	private static $instance;


	public static function singleton() {
		if(!isset(self::$instance)) {
			$c              = __CLASS__;
			self::$instance = new $c;
		}
		return self::$instance;
	}

	final public function __clone() {
		trigger_error('Cloning not allowed on a singleton object', E_USER_ERROR);
	}
}