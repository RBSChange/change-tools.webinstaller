<?php
//die('<span class="row_35">Simulation :' . $_GET['cmd'] .'</span><br />');
umask(0002);
define("WEBEDIT_HOME", dirname(dirname(dirname(realpath(__FILE__)))));
chdir(WEBEDIT_HOME);
set_time_limit(0);
ignore_user_abort(true);
error_reporting(E_ALL);

define("C_DEBUG", false);

$C_BOOT_STRAP_AS_LIB = true;
ob_start();
require_once 'httpbootstrap.php';
ob_get_clean();

$bootStrap = new c_ChangeBootStrap(WEBEDIT_HOME);
$bootStrap->setAutoloadPath(WEBEDIT_HOME."/.change/autoload");
$bootStrap->setLooseVersions(false);
$bootStrap->addPropertiesLocation(WEBEDIT_HOME."/.change");
$bootStrap->dispatch('func:executeChangeCmd');

function executeChangeCmd($argv, $computedDeps)
{
	global $bootStrap;
	if (isset($_GET['cmd']))
	{		
		$frameworkInfo = $computedDeps["change-lib"]["framework"];
		$bootStrap->appendToAutoload($frameworkInfo["path"]);
		$argv = explode(' ', $_GET['cmd']);
		$script = new c_Changescripthttp('change.php', $frameworkInfo['path']);
		$script->setBootStrap($bootStrap);
		$script->setEnvVar("computedDeps", $computedDeps);
		registerCommands($script, $computedDeps);
		
		ob_start();
		$script->execute($argv);
		ob_flush();
	}
}

/**
 * @param c_Changescript $script
 * @param array $computedDeps
 */
function registerCommands($script, $computedDeps)
{
	global $bootStrap;
	$frameworkInfo = $computedDeps["change-lib"]["framework"];
	$script->addCommandDir($frameworkInfo["path"].'/change-commands');
	$script->addGhostCommandDir($frameworkInfo["path"].'/changedev-commands');
		
	$path = WEBEDIT_HOME . "/modules/";
	if (!is_dir($path))
	{
		return;
	}
	
	foreach (new DirectoryIterator($path) as $fileInfo)
	{
		if (!$fileInfo->isDot() && $fileInfo->isDir())
		{
			$modulePath = realpath($fileInfo->getPathname());
			$moduleName = basename($fileInfo->getPathname());
			$moduleInAutoload = false;
			if (is_dir($modulePath."/change-commands"))
			{
				$bootStrap->appendToAutoload($modulePath);
				$moduleInAutoload = true;
				$script->addCommandDir($modulePath."/change-commands", "$moduleName|Module $moduleName commands");
			}
	
			$ghostPath = $modulePath.'/changedev-commands';
			if (is_dir($ghostPath))
			{
				$script->addGhostCommandDir($ghostPath, "$moduleName|Module $moduleName commands");
				if (!$moduleInAutoload)
				{
					$bootStrap->appendToAutoload($modulePath);
				}
			}
		}
	}	
}