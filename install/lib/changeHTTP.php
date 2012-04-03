<?php
header('Last-Modified' .': '. gmdate('D, d M Y H:i:s') . ' GMT');
header('Expires' .': '. 'Mon, 26 Jul 1997 05:00:00 GMT');
header('Cache-Control' .': '. 'no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
header('Pragma' .': '. 'no-cache');
ignore_user_abort(true);
set_time_limit(0);
umask(0002);
session_start();
clearstatcache();

define("WEBEDIT_HOME", dirname(dirname(dirname(realpath(__FILE__)))));
chdir(WEBEDIT_HOME);

$profile = @file_get_contents(WEBEDIT_HOME . DIRECTORY_SEPARATOR . 'profile');
if ($profile === false || empty($profile))
{
	header("HTTP/1.1 500 Internal Server Error");
	echo 'Profile not defined. Please define a profile in file ./profile.';
	exit(-1);
}

define('PROFILE', trim($profile));
define('FRAMEWORK_HOME', WEBEDIT_HOME . DIRECTORY_SEPARATOR . 'framework');
define('AG_CACHE_DIR', WEBEDIT_HOME . DIRECTORY_SEPARATOR . 'cache' . DIRECTORY_SEPARATOR . PROFILE);
	
require_once FRAMEWORK_HOME . '/bin/bootstrap.php';

$bootStrap = new c_ChangeBootStrap(WEBEDIT_HOME);
$bootStrap->setAutoloadPath(WEBEDIT_HOME."/cache/autoload");

$argv = explode(' ', $_GET['cmd']);
$script = new c_Changescripthttp(__FILE__, FRAMEWORK_HOME, 'change');
require_once FRAMEWORK_HOME . '/bin/change_script.inc';