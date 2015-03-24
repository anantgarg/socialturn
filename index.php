<?php

/* Set to UTC */

date_default_timezone_set('UTC');

/* Debug Mode */

error_reporting(E_ALL);
ini_set('display_errors','Off');
ini_set('error_log', dirname(__FILE__).'/error.log');
ini_set('log_errors', 'On');
    
/* Define */

define('ROOT',DIRNAME(__FILE__));
define('DS',DIRECTORY_SEPARATOR);

function stripSlashesDeep($value) {
	$value = is_array($value) ? array_map('stripSlashesDeep', $value) : stripslashes($value);
	return $value;
}

if (get_magic_quotes_gpc() || (defined('FORCE_MAGIC_QUOTES'))) {
	$_GET = stripSlashesDeep($_GET);
	$_POST = stripSlashesDeep($_POST);
	$_REQUEST = stripSlashesDeep($_REQUEST);
	$_COOKIE = stripSlashesDeep($_COOKIE);
}

/* Start Session */

session_start();

/* Get Basic Details */

if (empty($_SERVER['PATH_INFO'])) {
	$_SERVER['PATH_INFO'] = '';
}

$path = explode("/", (substr($_SERVER['PATH_INFO'],1)));

$controller = 'home';
$action = 'index';

if (!empty($path[0])) { $controller = str_replace("-","",$path[0]); }
if (!empty($path[1])) { $action = str_replace("-","",$path[1]); }

/* Include Libraries */

include_once ROOT.DS.'config.php';
include_once ROOT.DS.'libraries'.DS.'template.class.php';
include_once ROOT.DS.'libraries'.DS.'helper.class.php';
include_once ROOT.DS.'libraries'.DS.'pagination.class.php';
include_once ROOT.DS.'libraries'.DS.'postmark.class.php';
include_once ROOT.DS.'libraries'.DS.'uaparser.class.php';
include_once ROOT.DS.'libraries'.DS.'recaptcha.php';

include_once ROOT.DS.'libraries'.DS.'facebook'.DS.'facebook.php';
include_once ROOT.DS.'libraries'.DS.'twitter'.DS.'twitter.php';
include_once ROOT.DS.'libraries'.DS.'twitter'.DS.'twitter2.php';

$template = new Template($controller,$action);

$helper = new Helper();

include_once ROOT.DS.'controllers'.DS.'helpers.php';
include_once ROOT.DS.'libraries'.DS.'shared.php';

if (!defined('MINIMAL')) {
	/* Basic Bootstrapping */

	if (is_file(ROOT.DS.'controllers'.DS.$controller.'.php')) {

		if (function_exists($action)) {
			exit;
		}

		include ROOT.DS.'controllers'.DS.$controller.'.php';

		if (function_exists('pre')) {
			pre();
		}
		
		if (function_exists($action)) {
			call_user_func($action);
		} else {
			if (function_exists('index')) {
				call_user_func('index');
			} else {
				/* 404 error here */
				error404();
			}
		}
		
		$template->render();

		} else {
		/* 404 error here */
		error404();
	}
}