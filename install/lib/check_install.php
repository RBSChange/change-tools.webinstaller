<?php
$systemCheck = array();
include PROJECT_HOME_PATH . '/install/lib/assert.php';

$systemCheck['php_version'] = assert_php_version('5.2.4');
$systemCheck['php_ini_safe_mode'] = assert_ini('safe_mode', false);
$systemCheck['php_ini_file_uploads'] = assert_ini('file_uploads');
$systemCheck['php_ini_memory_limit'] = assert_ini_size_gt('memory_limit', 64);

$systemCheck['php_conf_home_writable'] = assert_writable(PROJECT_HOME_PATH);
$systemCheck['php_conf_global_writable'] = assert_writable(PROJECT_HOME_PATH . '/change.xml');
$systemCheck['php_conf_change_properties_writable'] = assert_writable(PROJECT_HOME_PATH . '/change.properties');
$systemCheck['php_conf_project_writable'] = assert_writable(PROJECT_HOME_PATH . '/config/project.default.xml');

foreach (scandir(PROJECT_HOME_PATH) as $dir)
{
	if ($dir == "." || $dir == ".." || !is_dir(PROJECT_HOME_PATH .'/'.$dir))
	{
		continue;
	}
	if (!assert_writable(PROJECT_HOME_PATH .'/'.$dir))
	{
		$systemCheck['php_conf_subdir_home_writable'] = false;
		break;
	}
}

if ($systemCheck['php_conf_home_writable'])
{
	$systemCheck['system_symlink'] = assert_symlink(PROJECT_HOME_PATH);
}

$systemCheck['php_ext_spl'] = assert_ext('SPL') || assert_class('ArrayObject');
$systemCheck['php_ext_reflection'] = assert_ext('Reflection');
$systemCheck['php_ext_curl'] = assert_ext('curl') || assert_function('curl_init');
$systemCheck['php_ext_pdo'] = assert_class('PDO') && assert_ext('pdo_mysql');

$systemCheck['php_ext_xml_dom'] = assert_class('DOMDocument');
$systemCheck['php_ext_xml_w'] = assert_class('xmlwriter');
$systemCheck['php_ext_xml_r'] = assert_class('xmlreader');
$systemCheck['php_ext_xml_sxml'] = assert_ext('SimpleXML') || assert_function('simplexml_load_file');
$systemCheck['php_ext_xsl'] = assert_ext('xsl');

$systemCheck['php_ext_string_mbstring'] = assert_ext('mbstring') || assert_function('mb_strtolower');
$systemCheck['php_ext_string_iconv'] = assert_ext('iconv') || assert_function('iconv_strlen');

$systemCheck['php_ext_json'] = assert_ext('json');

$systemCheck['php_ext_gd'] = assert_ext('gd') || assert_ext('imagick') || assert_function('getimagesize');

if ($systemCheck['php_ext_curl'] && $systemCheck['php_conf_home_writable'])
{
	$systemCheck['system_selfview'] = assert_selfview();
	if ($systemCheck['system_selfview'])
	{
		$systemCheck['system_rewrite'] = assert_rewrite();
	}
}

//$systemCheck['error'] = false;

$systemCheckOk = true;
foreach ($systemCheck as $key => $isOk) 
{
	$systemCheckOk = $systemCheckOk && $isOk;
	if (!$systemCheckOk)
	{
		break;
	}
}

function generateErrorReporting($systemCheck)
{
	$localeManager = LocaleManager::getInstance();
	
	$result = array();
	foreach ($systemCheck as $key => $isOk) 
	{
		if ($isOk) {continue;}
		switch ($key) 
		{
			case 'php_version':
				$result[] = $localeManager->getLocales('webinstaller.checkinstall.generateErrorReporting.php_version');
				break;
			case 'php_ini_safe_mode':
				$result[] = $localeManager->getLocales('webinstaller.checkinstall.generateErrorReporting.php_ini_safe_mode');
				break;
			case 'php_ini_short_open_tag':
				$result[] = $localeManager->getLocales('webinstaller.checkinstall.generateErrorReporting.php_ini_short_open_tag');
				break;
			case 'php_ini_default_charset':
				$result[] = $localeManager->getLocales('webinstaller.checkinstall.generateErrorReporting.php_ini_default_charset');
				break;
			case 'php_ini_allow_url_fopen':
				$result[] = $localeManager->getLocales('webinstaller.checkinstall.generateErrorReporting.php_ini_allow_url_fopen');
				break;
			case 'php_ini_file_uploads':
				$result[] = $localeManager->getLocales('webinstaller.checkinstall.generateErrorReporting.php_ini_file_uploads');
				break;
			case 'php_ini_magic_quotes_gpc':
				$result[] = $localeManager->getLocales('webinstaller.checkinstall.generateErrorReporting.php_ini_magic_quotes_gpc');
				break;
			case 'php_ini_memory_limit':
				$result[] = $localeManager->getLocales('webinstaller.checkinstall.generateErrorReporting.php_ini_memory_limit');
				break;	
			case 'php_conf_home_writable':
				echo "#1";
				$result[] = str_replace("{PROJECT_HOME_PATH}", PROJECT_HOME_PATH, $localeManager->getLocales('webinstaller.checkinstall.generateErrorReporting.php_conf_home_writable'));
				break;
			case 'php_conf_subdir_home_writable':
				$result[] = str_replace("{PROJECT_HOME_PATH}", PROJECT_HOME_PATH, $localeManager->getLocales('webinstaller.checkinstall.generateErrorReporting.php_conf_subdir_home_writable'));
				break;
			case 'php_conf_global_writable':
				$result[] = str_replace("{PROJECT_HOME_PATH}", PROJECT_HOME_PATH, $localeManager->getLocales('webinstaller.checkinstall.generateErrorReporting.php_conf_global_writable'));
				break;
			case 'php_conf_change_properties_writable':
				$result[] = str_replace("{PROJECT_HOME_PATH}", PROJECT_HOME_PATH, $localeManager->getLocales('webinstaller.checkinstall.generateErrorReporting.php_conf_change_properties_writable'));
				break;
			case 'php_conf_project_writable':
				$result[] = str_replace("{PROJECT_HOME_PATH}", PROJECT_HOME_PATH, $localeManager->getLocales('webinstaller.checkinstall.generateErrorReporting.php_conf_project_writable'));
				break;
			case 'php_ext_posix':
				$result[] = $localeManager->getLocales('webinstaller.checkinstall.generateErrorReporting.php_ext_posix');
				break;
			case 'php_ext_spl':
				$result[] = $localeManager->getLocales('webinstaller.checkinstall.generateErrorReporting.php_ext_spl');
				break;
			case 'php_ext_reflection':
				$result[] = $localeManager->getLocales('webinstaller.checkinstall.generateErrorReporting.php_ext_reflection');
				break;
			case 'php_ext_curl':
				$result[] = $localeManager->getLocales('webinstaller.checkinstall.generateErrorReporting.php_ext_curl');
				break;
			case 'php_ext_pdo':
				$result[] = $localeManager->getLocales('webinstaller.checkinstall.generateErrorReporting.php_ext_pdo');
				break;
			case 'php_ext_xml_dom':
				$result[] = $localeManager->getLocales('webinstaller.checkinstall.generateErrorReporting.php_ext_xml_dom');
				break;
			case 'php_ext_xml_w':
				$result[] = $localeManager->getLocales('webinstaller.checkinstall.generateErrorReporting.php_ext_xml_w');
				break;				
			case 'php_ext_xml_r':
				$result[] = $localeManager->getLocales('webinstaller.checkinstall.generateErrorReporting.php_ext_xml_r');
				break;				
			case 'php_ext_xml_sxml':
				$result[] = $localeManager->getLocales('webinstaller.checkinstall.generateErrorReporting.php_ext_xml_sxml');
				break;					
			case 'php_ext_xsl':
				$result[] = $localeManager->getLocales('webinstaller.checkinstall.generateErrorReporting.php_ext_xsl');
				break;
			case 'php_ext_string_mbstring':
				$result[] = $localeManager->getLocales('webinstaller.checkinstall.generateErrorReporting.php_ext_string_mbstring');
				break;					
			case 'php_ext_string_iconv':
				$result[] = $localeManager->getLocales('webinstaller.checkinstall.generateErrorReporting.php_ext_string_iconv');
				break;					
			case 'php_ext_gd':
				$result[] = $localeManager->getLocales('webinstaller.checkinstall.generateErrorReporting.php_ext_gd');
				break;
			case 'php_ext_json':
				$result[] = $localeManager->getLocales('webinstaller.checkinstall.generateErrorReporting.php_ext_json');
				break;
			case 'system_symlink':
				$result[] = $localeManager->getLocales('webinstaller.checkinstall.generateErrorReporting.system_symlink');
				break;
			case 'system_selfview':
				$result[] = str_replace("{SERVER_HTTP_HOST}", $_SERVER["HTTP_HOST"], $localeManager->getLocales('webinstaller.checkinstall.generateErrorReporting.system_selfview'));
				break;
			case 'system_rewrite':
				$result[] = $localeManager->getLocales('webinstaller.checkinstall.generateErrorReporting.system_rewrite');
				break;
		}
		
	}
	return $result;
}
