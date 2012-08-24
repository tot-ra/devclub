<?php
/**
 * Spark initialization file
 *
 * @require php5, mysql 5 + innodb
 * @author Arjtom Kurapov <artkurapov@gmail.com>
 * @link http://kurapov.name
 */

mb_internal_encoding("UTF-8");
define('sys_root', realpath(dirname(__FILE__)) . DIRECTORY_SEPARATOR);


require 'vendor/autoload.php';
require_once('SiteConfig.php');

$config = new SiteConfig();


/**
 * Functions
 */


$error  = new Gratheon\Core\Error();
$system = new Gratheon\Core\System();

function pre($input,$options=''){
	Gratheon\Core\Error::debug($input,$options);
}

set_error_handler( array( $error, 'warning' ) );
set_exception_handler( array( $error, 'captureException' ) );
register_shutdown_function( array( $error, 'captureShutdown' ) );


$system->run();
exit();