<?php
header('Last-Modified' .': '. gmdate('D, d M Y H:i:s') . ' GMT');
header('Expires' .': '. 'Mon, 26 Jul 1997 05:00:00 GMT');
header('Cache-Control' .': '. 'no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
header('Pragma' .': '. 'no-cache');
define('PROJECT_HOME_PATH', dirname(dirname(__FILE__)));

include PROJECT_HOME_PATH . '/install/lib/ConfigManager.class.php';
$configManager = ConfigManager::getInstance();

$step = isset($_POST['NEXTSTEP']) ? $_POST['NEXTSTEP'] : '01';
if (!in_array($step, array('01', '02', '03'))) {$step = '01';}
define('STEP_SCRIPT_PATH', PROJECT_HOME_PATH . '/install/step_'. $step .'.php');
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
<head>
	<title>Installation <?php echo $configManager->getProductTitle(); ?></title>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<link rel="stylesheet" href="/install/ressources/install.css" type="text/css" media="screen" />
	<script src="/install/ressources/library.js" type="text/javascript"></script>
</head>
<body>
	<div class="header">
	<h1>Installation <?php echo $configManager->getProductTitle(); ?></h1>
	</div>
	<div class="content"><a href="http://www.rbschange.fr" target="_blank" title="Vers le site rbschange.fr" >
		<img class="logo" src="/install/ressources/login-logo.png" /></a> 
		<div class="panel">
			<?php include STEP_SCRIPT_PATH;?>
		</div>
		<p class="copyright">RBS Change™ © 2011 <a href="http://www.rbs.fr" target="_blank" title="Vers le site rbs.fr" hreflang="fr">
			Ready Business System</a></p>
	</div>
</body>
</html>
