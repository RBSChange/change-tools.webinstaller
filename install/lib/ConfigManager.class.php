<?php
if (!defined('PROJECT_HOME_PATH'))
{
	header('Location: /install/index.php');
	die();
}

class ConfigManager
{
	/**
	 * @var ConfigManager
	 */
	private static $instance;

	/**
	 * @var array
	 */
	private $parameters = array();

	/**
	 * @var array
	 */
	private $errors = array();

	/**
	 * @var string
	 */
	public $productName = "CMS Core";

	/**
	 * @var string
	 */
	public $productType = "";

	/**
	 * @var string
	 */
	public $productVersion = "3.6.2";

	/**
	 * @var string
	 */
	public $frameworkRepo = "3.6.2";

	public function getProductTitle()
	{
		return $this->productName . ' ' . $this->productVersion;
	}
	/**
	 * @return ConfigManager
	 */
	public static function getInstance()
	{
		if (self::$instance === null)
		{
			self::$instance = new self(PROJECT_HOME_PATH);
		}

		return self::$instance;
	}


	protected function __construct($projectHomePath)
	{
		$this->parameters['WEBEDIT_HOME'] = $projectHomePath;
		$savedConfig = $this->getInstallParametersFilePath();
		if (file_exists($savedConfig))
		{
			$dataconfig = array();
			include $savedConfig;
			$this->parameters = $dataconfig;
			$this->parameters['WEBEDIT_HOME'] = $projectHomePath;
		}
		$this->loadProductVersion();
	}

	private function loadProductVersion()
	{
		$path = $this->getChangePath();
		if (is_readable($path) && class_exists('DOMDocument', false))
		{
			$doc = new DOMDocument('1.0', 'UTF-8');
			$doc->load($path);
			$productName = $doc->getElementsByTagName('description')->item(0)->textContent;
			$productType = $doc->getElementsByTagName('name')->item(0)->textContent;
			$frameworkNode = $doc->getElementsByTagName('dependencies')->item(0)->getElementsByTagName('framework')->item(0);
			$productVersion = $frameworkNode->textContent;
			$frameworkRepo = $frameworkNode->hasAttribute('hotfixes') ? $productVersion . '-' . $frameworkNode->getAttribute('hotfixes') : $productVersion;
		}
		else
		{
			$productName = "CMS Core";
			$productVersion = "3.6.2";
			$productType = "cmscore";
			$frameworkRepo = "3.6.2";
		}

		$this->productName = $productName;
		$this->productType = $productType;
		$this->productVersion = $productVersion;
		$this->frameworkRepo = $frameworkRepo;
	}

	/**
	 * @param array $parameters
	 */
	public function setParameters($parameters)
	{
		if (is_array( $parameters ))
		{
			$this->parameters = $parameters;
		} else
		{
			$this->parameters = array();
		}
		$this->parameters['WEBEDIT_HOME'] = PROJECT_HOME_PATH;
		$this->parameters['USEHTTPS'] = isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] != "off";
		$this->parameters['BASEURL'] = 'http'.(($this->parameters['USEHTTPS']) ? "s" : "").'://' . $this->parameters['FQDN'];

	}

	/**
	 * @return boolean
	 */
	public function isChecked()
	{
		return isset( $this->parameters['checked'] ) && $this->parameters['checked'];
	}

	public function getErrors()
	{
		return $this->errors;
	}

	/**
	 * @return boolean
	 */
	public function hasError()
	{
		return count($this->errors) > 0;
	}

	public function getError($name)
	{
		if (isset( $this->errors[$name] ))
		{
			return $this->errors[$name];
		}
		return false;
	}

	public function initialise()
	{
		$this->parameters['FQDN'] = $_SERVER['SERVER_NAME'];
		$this->parameters['USEHTTPS'] = isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] != "off";
		$this->parameters['BASEURL'] = 'http'.(($this->parameters['USEHTTPS']) ? "s" : "").'://' . $this->parameters['FQDN'];

		if (function_exists( 'posix_getgrgid' ))
		{
			$grpInfos = posix_getgrgid( posix_getegid() );
			$grpName = $grpInfos['name'];
			if (empty( $grpName ))
			{
				$grpName = 'www-data';
			}
		} else
		{
			$grpName = '';
		}

		$this->parameters['WWW_GROUP'] = $grpName;

		$this->parameters['DB_HOST'] = 'localhost';
		$this->parameters['DB_PORT'] = '3306';
		$this->parameters['DB_USER'] = 'root';
		$this->parameters['DB_PASSWORD'] = '';
		$this->parameters['DB_DATABASE'] = 'C4_' . str_replace( array('www.', '.'), array('', '_'), $this->parameters['FQDN'] ) . '_default';

		$this->parameters['SERVER_MAIL'] = 'NOMAIL';
		$this->parameters['NO_REPLY'] = 'noreply@' . str_replace( 'www.', '', $this->parameters['FQDN'] );
		$this->parameters['SMTP_HOST'] = 'localhost';
		$this->parameters['SMTP_PORT'] = '25';

		$this->parameters['SENDMAIL_PATH'] = '/usr/sbin/sendmail';
		$this->parameters['SENDMAIL_ARGS'] = '-t -i';

		$this->parameters['SOLR_URL'] = $this->parameters['BASEURL'] . '/mysqlindexer';

		$this->parameters['SAMPLES'] = 'checked';

		$this->parameters['checked'] = false;

		$this->parameters['TMP_PATH'] = $this->getTmpPath();

		$this->parameters['DEFAULT_LANG'] = "fr";

	}

	private function getTmpPath()
	{
		if (function_exists( 'sys_get_temp_dir' ))
		{
			return sys_get_temp_dir();
		}

		$tmpfile = @tempnam( null, 'loc_' );
		if ($tmpfile)
		{
			@unlink( $tmpfile );
			return dirname( $tmpfile );
		}
		if (isset( $_ENV['TMP'] ))
		{
			return $_ENV['TMP'];
		}
		if (isset( $_ENV['TEMP'] ))
		{
			return $_ENV['TEMP'];
		}
		if (is_writable( '/tmp' ))
		{
			return '/tmp';
		}
	}

	public function save()
	{
		$this->writeConfiguration();
	}

	/**
	 * @return boolean
	 */
	public function check()
	{

		$checked = $this->checkFQDN();
		$checked = $this->checkFrameworkPath();
		$checked = $this->checkDb() && $checked;
		$checked = $this->checkMail() && $checked;
		$checked = $this->checkTmpPath() && $checked;
		$this->parameters['checked'] = $checked;
		$this->writeConfiguration();
		return $this->isChecked();
	}

	/**
	 * @return string
	 */
	public function getProjectId()
	{
		return str_replace( array('www.', '.'), array('', '_'), $this->parameters['FQDN'] ) . '_default';
	}

	/**
	 * @param string $name
	 * @return mixed
	 */
	public function getParameter($name)
	{
		if (isset( $this->parameters[$name] ))
		{
			return $this->parameters[$name];
		}
		return null;
	}

	/**
	 * @return array
	 */
	public function getParameters()
	{
		return $this->parameters;
	}

	private function getWebeditHome()
	{
		return $this->parameters['WEBEDIT_HOME'];
	}

	private function getInstallParametersFilePath()
	{
		return implode( DIRECTORY_SEPARATOR, array($this->getWebeditHome(), 'install', 'configdatas.php') );
	}

	private function getChangePropertiesFilePath()
	{
		return implode( DIRECTORY_SEPARATOR, array($this->getWebeditHome(), 'change.properties') );
	}

	private function getChangePath()
	{
		return implode( DIRECTORY_SEPARATOR, array($this->getWebeditHome(), 'change.xml') );
	}

	private function getProjectConfigFilePath()
	{
		return implode( DIRECTORY_SEPARATOR, array($this->getWebeditHome(), 'config', 'project.default.xml') );
	}

	private function writeConfiguration()
	{
		$localeManager = LocaleManager::getInstance();
		$content = '<' . '?php' . "\n\t" . '$dataconfig = ' . var_export( $this->parameters, true ) . ';';
		if (file_put_contents( $this->getInstallParametersFilePath(), $content ) === false)
		{
			$this->errors['others'][] = str_replace("{filePath}", $this->getInstallParametersFilePath(), $localeManager->getLocales('webinstaller.configmanager.writeFileError'));
			return false;
		}
		return true;
	}

	public function applyConfiguration()
	{
		$localeManager = LocaleManager::getInstance();

		$changeProperties = file_get_contents( $this->getChangePropertiesFilePath() );
		$lines = array();
		$oldlines = explode( "\n", $changeProperties );
		foreach ( $oldlines as $line )
		{
			if (strpos( $line, 'LOCAL_REPOSITORY=' ) === 0)
			{
				if (defined( 'DEV_LOCAL_REPOSITORY' ))
				{
					$lines[] = 'LOCAL_REPOSITORY=' . DEV_LOCAL_REPOSITORY;
				} else
				{
					$lines[] = 'LOCAL_REPOSITORY=' . implode( DIRECTORY_SEPARATOR, array($this->getWebeditHome(), 'repository') );
				}
			} else if (strpos( $line, 'WWW_GROUP=' ) === 0)
			{
				$lines[] = 'WWW_GROUP=' . $this->parameters['WWW_GROUP'];
			} else if (strpos( $line, 'PEAR_INCLUDE_PATH=' ) !== false)
			{
				$lines[] = 'PEAR_INCLUDE_PATH=' . implode( DIRECTORY_SEPARATOR, array($this->getWebeditHome(), 'pear') );
			} else
			{
				$lines[] = $line;
			}
		}
		if (file_put_contents( $this->getChangePropertiesFilePath(), implode( "\n", $lines ) ) === false)
		{
			$this->errors['others'][] = str_replace("{filePath}", $this->getChangePropertiesFilePath(), $localeManager->getLocales('webinstaller.configmanager.writeFileError'));
			return false;
		}

		$doc = new DOMDocument( '1.0', 'UTF-8' );
		$doc->formatOutput = true;
		$doc->preserveWhiteSpace = false;

		if ($doc->load( $this->getProjectConfigFilePath() ) === false)
		{
			$this->errors['others'][] = str_replace("{filePath}", $this->getProjectConfigFilePath(), $localeManager->getLocales('webinstaller.configmanager.readXmlFileError'));
			return false;
		}

		$xpath = new DOMXPath( $doc );
		$nl = $xpath->query( '/project/defines/define[@name="PROJECT_ID"]' );
		$nl->item( 0 )->nodeValue = $this->getProjectId();

		$nl = $xpath->query( '/project/defines/define[@name="DEFAULT_UI_PROTOCOL"]' );
		$nl->item( 0 )->nodeValue = $this->parameters['USEHTTPS'] ? 'https' : 'http';

		$nl = $xpath->query( '/project/defines/define[@name="TMP_PATH"]' );
		$nl->item( 0 )->nodeValue = $this->parameters['TMP_PATH'];

		$langs = array($this->parameters['DEFAULT_LANG']);
		if (isset($this->parameters['OTHER_LANG1'])){
			$langs[] = $this->parameters['OTHER_LANG1'];
		}
		if (isset($this->parameters['OTHER_LANG2'])){
			$langs[] = $this->parameters['OTHER_LANG2'];
		}
		if (isset($this->parameters['OTHER_LANG3'])){
			$langs[] = $this->parameters['OTHER_LANG3'];
		}
		$langs = array_unique($langs);

		$nl = $xpath->query( '/project/defines/define[@name="SUPPORTED_LANGUAGES"]' );
		$nl->item( 0 )->nodeValue = implode(" ", array_intersect($langs, array("fr", "en", "de")));
		
		$nl = $xpath->query( '/project/defines/define[@name="UI_SUPPORTED_LANGUAGES"]' );
		$nl->item( 0 )->nodeValue = implode(" ", array_intersect($langs, array("fr", "en")));

		$nl = $xpath->query( '/project/config/general/entry[@name="server-fqdn"]' );
		$nl->item( 0 )->nodeValue = $this->parameters['FQDN'];

		$nl = $xpath->query( '/project/config/databases/webapp/entry[@name="user"]' );
		$nl->item( 0 )->nodeValue = $this->parameters['DB_USER'];

		$nl = $xpath->query( '/project/config/databases/webapp/entry[@name="password"]' );
		$nl->item( 0 )->nodeValue = $this->parameters['DB_PASSWORD'];

		$nl = $xpath->query( '/project/config/databases/webapp/entry[@name="database"]' );
		$nl->item( 0 )->nodeValue = $this->parameters['DB_DATABASE'];

		$nl = $xpath->query( '/project/config/databases/webapp/entry[@name="host"]' );
		$nl->item( 0 )->nodeValue = $this->parameters['DB_HOST'];

		$nl = $xpath->query( '/project/config/databases/webapp/entry[@name="port"]' );
		$nl->item( 0 )->nodeValue = $this->parameters['DB_PORT'];

		$nl = $xpath->query( '/project/config/injection/entry[@name="MailService"]' );
		switch ($this->parameters['SERVER_MAIL'])
		{
			case 'SENDMAIL' :
				if ($nl->length == 1)
				{
					$nl->item( 0 )->parentNode->removeChild( $nl->item( 0 ) );
				}
				$nl = $xpath->query( '/project/config/mail/entry[@name="type"]' );
				$nl->item( 0 )->nodeValue = "Sendmail";

				$nl = $xpath->query( '/project/config/mail/entry[@name="sendmail_path"]' );
				$nl->item( 0 )->nodeValue = $this->parameters['SENDMAIL_PATH'];

				$nl = $xpath->query( '/project/config/mail/entry[@name="sendmail_args"]' );
				$nl->item( 0 )->nodeValue = $this->parameters['SENDMAIL_ARGS'];
				break;
			case 'SMTP' :
				if ($nl->length == 1)
				{
					$nl->item( 0 )->parentNode->removeChild( $nl->item( 0 ) );
				}
				$nl = $xpath->query( '/project/config/mail/entry[@name="type"]' );
				$nl->item( 0 )->nodeValue = "Smtp";

				$nl = $xpath->query( '/project/config/mail/entry[@name="host"]' );
				$nl->item( 0 )->nodeValue = $this->parameters['SMTP_HOST'];

				if (empty( $this->parameters['SMTP_PORT'] ))
				{
					$this->parameters['SMTP_PORT'] = "25";
				}
				$nl = $xpath->query( '/project/config/mail/entry[@name="port"]' );
				$nl->item( 0 )->nodeValue = $this->parameters['SMTP_PORT'];
				break;
			default :
				if ($nl->length == 1)
				{
					$nl->item( 0 )->nodeValue = 'NullMailService';
				} else
				{
					$nl = $xpath->query( '/project/config/injection' );
					$nl->item( 0 )->appendChild( $doc->createElement( 'entry', 'NullMailService' ) )->setAttribute( 'name', 'MailService' );
				}
				break;
		}

		$nl = $xpath->query( '/project/defines/define[@name="SOLR_INDEXER_URL"]' );
		if (! empty( $this->parameters['SOLR_URL'] ))
		{
			if ($nl->length == 1)
			{
				$nl->item( 0 )->nodeValue = $this->parameters['SOLR_URL'];
			} else
			{
				$nl = $xpath->query( '/project/defines' );
				$nl->item( 0 )->appendChild( $doc->createElement( 'define', $this->parameters['SOLR_URL'] ) )->setAttribute( 'name', 'SOLR_INDEXER_URL' );
			}
		} else if ($nl->length == 1)
		{
			$nl->item( 0 )->parentNode->removeChild( $nl->item( 0 ) );
		}

		$nl = $xpath->query( '/project/defines/define[@name="SOLR_INDEXER_CLIENT"]' );
		if (! empty( $this->parameters['SOLR_URL'] ))
		{
			if ($nl->length == 1)
			{
				$nl->item( 0 )->nodeValue = $this->parameters['DB_DATABASE'];
			} else
			{
				$nl = $xpath->query( '/project/defines' );
				$nl->item( 0 )->appendChild( $doc->createElement( 'define', $this->parameters['DB_DATABASE'] ) )->setAttribute( 'name', 'SOLR_INDEXER_CLIENT' );
			}
		} else if ($nl->length == 1)
		{
			$nl->item( 0 )->parentNode->removeChild( $nl->item( 0 ) );
		}

		if (! empty( $this->parameters['NO_REPLY'] ))
		{
			$nl = $xpath->query( '/project/defines/define[@name="MOD_NOTIFICATION_SENDER"]' );
			if ($nl->length == 1)
			{
				$nl->item( 0 )->nodeValue = $this->parameters['NO_REPLY'];
			} else
			{
				$nl = $xpath->query( '/project/defines' );
				$nl->item( 0 )->appendChild( $doc->createElement( 'define', $this->parameters['NO_REPLY'] ) )->setAttribute( 'name', 'MOD_NOTIFICATION_SENDER' );
			}

			list(, $host) = explode( '@', $this->parameters['NO_REPLY'] );
			$nl = $xpath->query( '/project/defines/define[@name="MOD_NOTIFICATION_SENDER_HOST"]' );
			if ($nl->length == 1)
			{
				$nl->item( 0 )->nodeValue = $host;
			} else
			{
				$nl = $xpath->query( '/project/defines' );
				$nl->item( 0 )->appendChild( $doc->createElement( 'define', $host ) )->setAttribute( 'name', 'MOD_NOTIFICATION_SENDER_HOST' );
			}
		} else
		{
			$nl = $xpath->query( '/project/defines/define[@name="MOD_NOTIFICATION_SENDER"]' );
			if ($nl->length == 1)
			{
				$nl->item( 0 )->parentNode->removeChild( $nl->item( 0 ) );
			}
			$nl = $xpath->query( '/project/defines/define[@name="MOD_NOTIFICATION_SENDER_HOST"]' );
			if ($nl->length == 1)
			{
				$nl->item( 0 )->parentNode->removeChild( $nl->item( 0 ) );
			}
		}

		if ($doc->save( $this->getProjectConfigFilePath() ) === false)
		{
			$this->errors['others'][] = 'Le fichier ' . $this->getProjectConfigFilePath() . ' n\'a pu être écrit.';
			return false;
		}

		$this->generateOverrideFolder();

		if (file_exists( PROJECT_HOME_PATH . '/index.php' ))
		{
			unlink( PROJECT_HOME_PATH . '/index.php' );
		}
		return true;
	}

	private function generateOverrideFolder()
	{
		$from = PROJECT_HOME_PATH . '/install/override';
		$dest = PROJECT_HOME_PATH . '/override';
		@mkdir( $dest, 0777, true );
		$fromLength = strlen( $from );
		foreach ( new RecursiveIteratorIterator( new RecursiveDirectoryIterator( $from, RecursiveDirectoryIterator::KEY_AS_PATHNAME ), RecursiveIteratorIterator::SELF_FIRST ) as $file => $info )
		{
			$relFile = substr( $file, $fromLength + 1 );
			$destFile = $dest . "/" . $relFile;
			if (file_exists( $destFile ))
			{
				continue;
			}
			if ($info->isDir())
			{
				@mkdir( $destFile, 0777 );
			} else
			{
				@copy( $file, $destFile );
			}
		}
	}

	private function checkDb()
	{
		try
		{
			$dsn = 'mysql:host=' . $this->parameters['DB_HOST'] . ';port=' . $this->parameters['DB_PORT'] . ';dbname=' . $this->parameters['DB_DATABASE'];
			new PDO( $dsn, $this->parameters['DB_USER'], $this->parameters['DB_PASSWORD'] );
		} catch ( Exception $e )
		{
			$msg = $e->getMessage();
			if (strpos( $msg, 'SQLSTATE[42000] [1049]' ) === 0)
			{
				return $this->createDataBase();
			}
			$localeManager = LocaleManager::getInstance();
			$this->errors['DB'] = $localeManager->getLocales('webinstaller.configmanager.dbConnexionError');
			return false;
		}
		return true;
	}

	private function createDataBase()
	{
		$localeManager = LocaleManager::getInstance();
		try
		{
			$dsn = 'mysql:host=' . $this->parameters['DB_HOST'] . ';port=' . $this->parameters['DB_PORT'];
			$pdo = new PDO( $dsn, $this->parameters['DB_USER'], $this->parameters['DB_PASSWORD'] );
			if ($pdo->exec( "create database if not exists `" . $this->parameters['DB_DATABASE'] . "`" ) === false)
			{
				$this->errors['DB'] = $localeManager->getLocales('webinstaller.configmanager.dbCreationError');
				return false;
			}
			return true;
		} catch ( Exception $e )
		{
			$this->errors['DB'] = $localeManager->getLocales('webinstaller.configmanager.dbConnexionError');
			return false;
		}
	}

	private function checkFQDN()
	{
		$localeManager = LocaleManager::getInstance();
		$data = "checkFQDNOK".md5((isset($_SERVER["HTTPS"]) ? "on":"")."/".$_SERVER["HTTP_HOST"]."/".dirname(dirname(__FILE__)));
		$testFilePath = implode( DIRECTORY_SEPARATOR, array($this->getWebeditHome(), 'install', 'checkFQDN.php') );
		$url = $this->parameters['BASEURL'] . '/install/checkFQDN.php';
		$cr = curl_init();
		$options = array(CURLOPT_RETURNTRANSFER => true, CURLOPT_TIMEOUT => 5, CURLOPT_CONNECTTIMEOUT => 5,
				CURLOPT_FOLLOWLOCATION => false, CURLOPT_URL => $url, CURLOPT_POSTFIELDS => null,
				CURLOPT_POST => false, CURLOPT_SSL_VERIFYPEER =>false);
		curl_setopt_array( $cr, $options );

		$curldata = curl_exec( $cr );
		$errno = curl_errno( $cr );
		curl_close( $cr );

		if ($errno || $data != $curldata)
		{
			$this->errors['DOMAIN'] = str_replace("{fqdn}", $this->parameters['BASEURL'], $localeManager->getLocales('webinstaller.configmanager.fqdnError'));
			return false;
		}
		return true;
	}

	private function checkFrameworkPath()
	{
		$localeManager = LocaleManager::getInstance();
		$target = implode(DIRECTORY_SEPARATOR, array($this->getWebeditHome(), 'repository', 'framework', 'framework-' . $this->frameworkRepo));
		if (is_dir($target))
		{
			$link = implode(DIRECTORY_SEPARATOR, array($this->getWebeditHome(), 'framework'));
			@unlink($link);
			if (symlink($target, $link))
			{
				return true;
			}
			$this->errors['DOMAIN'] = str_replace("{target}", $target, str_replace("{link}", $link, $localeManager->getLocales('webinstaller.configmanager.symlinkError')));
			return false;
		}
		$this->errors['DOMAIN'] = str_replace("{frameworkVersion}", $this->frameworkRepo, $localeManager->getLocales('webinstaller.configmanager.versionError'));
		return false;
	}

	private function checkMail()
	{
		$localeManager = LocaleManager::getInstance();
		if (!preg_match('/^[a-z0-9][a-z0-9_.-]*@[a-z0-9][a-z0-9.-]*\.[a-z]{2,}$/i', $this->parameters['NO_REPLY']))
		{
			if ($this->parameters['SERVER_MAIL'] != 'NOMAIL')
			{
				$this->errors['MAIL'] = $localeManager->getLocales('webinstaller.configmanager.invalidMailError');
				return false;
			}
			else
			{
				// Address is used to populate notifications...
				$this->parameters['NO_REPLY'] = 'noreply@noreply.fr';
			}
		}

		switch ($this->parameters['SERVER_MAIL'])
		{
			case 'SENDMAIL' :
				if (! is_executable( $this->parameters['SENDMAIL_PATH'] ))
				{
					$this->errors['MAIL'] = $localeManager->getLocales('webinstaller.configmanager.sendmailError');
					return false;
				}
				break;
			case 'SMTP' :
				$port = $this->parameters['SMTP_PORT'];
				$host = $this->parameters['SMTP_HOST'];
				if (empty( $port ))
				{
					$port = 25;
					$this->parameters['SMTP_PORT'] = 25;
				}
				$errno = 0;
				$errstr = '';
				$smtp_conn = @fsockopen( $host, $port, $errno, $errstr, 5 );
				if (empty( $smtp_conn ))
				{
					$this->errors['MAIL'] = $localeManager->getLocales('webinstaller.configmanager.smtpError');
					return false;
				} else
				{
					fclose( $smtp_conn );
				}
				break;
		}
		return true;
	}

	private function checkTmpPath()
	{
		$localeManager = LocaleManager::getInstance();
		$tmpPath = $this->parameters['TMP_PATH'];
		if (trim($tmpPath) == "")
		{
			$this->errors['TMP_PATH'] = $localeManager->getLocales('webinstaller.configmanager.emptyTmpPathError');
			return false;
		}
		if (!is_dir($tmpPath) || realpath(dirname(tempnam($tmpPath, "change-webinstall"))) != realpath($tmpPath))
		{
			$this->errors['TMP_PATH'] = str_replace("{tmpPath}", $tmpPath, $localeManager->getLocales('webinstaller.configmanager.writeTmpPathError'));
			return false;
		}
		return true;
	}
}
