<?php
$systemCheck = array();
include PROJECT_HOME_PATH . '/install/lib/assert.php';

$systemCheck['php_version'] = assert_php_version('5.1.6');
$systemCheck['php_ini_safe_mode'] = assert_ini('safe_mode', false);
//$systemCheck['php_ini_short_open_tag'] = assert_ini('short_open_tag', false);
//$systemCheck['php_ini_default_charset'] = assert_ini('default_charset', 'utf-8');
$systemCheck['php_ini_file_uploads'] = assert_ini('file_uploads');
$systemCheck['php_ini_memory_limit'] = assert_ini_size_gt('memory_limit', 64);

$systemCheck['php_conf_home_writable'] = assert_writable(PROJECT_HOME_PATH, true, array("repository", "themes"));
$systemCheck['php_conf_global_writable'] = assert_writable(PROJECT_HOME_PATH . '/change.xml');
$systemCheck['php_conf_project_writable'] = assert_writable(PROJECT_HOME_PATH . '/config/project.default.xml');

if ($systemCheck['php_conf_home_writable'])
{
	$systemCheck['system_symlink'] = assert_symlink(PROJECT_HOME_PATH);
}

//$systemCheck['php_ext_posix'] = assert_ext('posix') || assert_function('posix_getuid');
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

$systemCheck['php_ext_gd'] = assert_ext('gd') || assert_ext('imagick') || assert_function('getimagesize');

if ($systemCheck['php_ext_curl'])
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
if ($systemCheckOk || assert_class('DOMDocument'))
{
	$doc = new DOMDocument('1.0', 'UTF-8');
	$doc->load(PROJECT_HOME_PATH .'/change.xml');
	$productName = $doc->getElementsByTagName('description')->item(0)->textContent;
	$productType = $doc->getElementsByTagName('name')->item(0)->textContent;
	$productVersion = $doc->getElementsByTagName('version')->item(0)->textContent;
}
else
{
	$productName = "CMS Core";	
	$productVersion = "";
	$productType = "cmscore";
}

function generateErrorReporting($systemCheck)
{
	$result = array();
	foreach ($systemCheck as $key => $isOk) 
	{
		if ($isOk) {continue;}
		switch ($key) 
		{
			case 'php_version':
				$result[] = 'La version de php minimum est 5.1.6';
				break;
			case 'php_ini_safe_mode':
				$result[] = 'la valeur de la variable de configuration [safe_mode] doit être à off';
				break;
			case 'php_ini_short_open_tag':
				$result[] = 'la valeur de la variable de configuration [short_open_tag] doit être à off';
				break;
			case 'php_ini_default_charset':
				$result[] = 'la valeur de la variable de configuration [default_charset] doit être à utf-8';
				break;
			case 'php_ini_allow_url_fopen':
				$result[] = 'la valeur de la variable de configuration [allow_url_fopen] doit être à on';
				break;
			case 'php_ini_file_uploads':
				$result[] = 'la valeur de la variable de configuration [file_uploads] doit être à on';
				break;
			case 'php_ini_magic_quotes_gpc':
				$result[] = 'la valeur de la variable de configuration [magic_quotes_gpc] doit être à off';
				break;
			case 'php_ini_memory_limit':
				$result[] = 'la valeur de la variable de configuration [memory_limit] doit être à 64M minimum';
				break;	
			case 'php_conf_home_writable':
				$result[] = 'le dossier [' . PROJECT_HOME_PATH . '] n\'est pas accessible en écriture';
				break;
			case 'php_conf_global_writable':
				$result[] = 'le fichier [' . PROJECT_HOME_PATH . '/change.xml] n\'est pas accessible en écriture';
				break;
			case 'php_conf_project_writable':
				$result[] = 'le fichier [' . PROJECT_HOME_PATH . '/config/project.default.xml] n\'est pas accessible en écriture';
				break;
				
			case 'php_ext_posix':
				$result[] = 'l\'extension [posix] n\'est pas installée';
				break;
			case 'php_ext_spl':
				$result[] = 'l\'extension [SPL] n\'est pas installée';
				break;
			case 'php_ext_reflection':
				$result[] = 'l\'extension [Reflection] n\'est pas installée';
				break;
			case 'php_ext_curl':
				$result[] = 'l\'extension [curl] n\'est pas installée';
				break;
			case 'php_ext_pdo':
				$result[] = 'l\'extension [pdo] [pdo_mysql] n\'est pas installée';
				break;
			case 'php_ext_xml_dom':
				$result[] = 'l\'extension [dom] n\'est pas installée';
				break;
			case 'php_ext_xml_w':
				$result[] = 'l\'extension [xmlwriter] n\'est pas installée';
				break;				
			case 'php_ext_xml_r':
				$result[] = 'l\'extension [xmlreader] n\'est pas installée';
				break;				
			case 'php_ext_xml_sxml':
				$result[] = 'l\'extension [SimpleXML] n\'est pas installée';
				break;					
			case 'php_ext_xsl':
				$result[] = 'l\'extension [xsl] n\'est pas installée';
				break;
			case 'php_ext_string_mbstring':
				$result[] = 'l\'extension [mbstring] n\'est pas installée';
				break;					
			case 'php_ext_string_iconv':
				$result[] = 'l\'extension [iconv] n\'est pas installée';
				break;					
			case 'php_ext_gd':
				$result[] = 'l\'extension [gd] n\'est pas installée';
				break;			
			case 'system_symlink':
				$result[] = 'Votre système ne permet pas la création de liens symboliques';
				break;
			case 'system_selfview':
				$result[] = "Le serveur n'a pu faire de requête vers lui même : assurez-vous que ".$_SERVER["HTTP_HOST"]." soit accessible à votre serveur";
				break;
			case 'system_rewrite':
				$result[] = "Le module mod_rewrite n'a pas l'air activé. Veuillez activer mod_rewrite";
				break;
		}
		
	}
	return $result;
}
