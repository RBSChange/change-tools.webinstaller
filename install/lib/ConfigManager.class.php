<?php
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
	
	private $errors = array();
	
	/**
	 * @return ConfigManager
	 */
	public static function getInstance()
	{
		if (self::$instance === null)
		{
			self::$instance = new self( );
		}
		self::$instance->parameters['WEBEDIT_HOME'] = PROJECT_HOME_PATH;
		$savedConfig = self::$instance->getInstallParametersFilePath();
		if (file_exists( $savedConfig ))
		{
			$dataconfig = array();
			include $savedConfig;
			self::$instance->parameters = $dataconfig;
		}
		return self::$instance;
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
		$this->parameters['DB_USER'] = 'admin-webedit';
		$this->parameters['DB_PASSWORD'] = 'admin-webedit';
		$this->parameters['DB_DATABASE'] = 'C4_' . str_replace( array('www.', '.'), array('', '_'), $this->parameters['FQDN'] ) . '_default';
		
		$this->parameters['SERVER_MAIL'] = 'NOMAIL';
		$this->parameters['NO_REPLY'] = 'noreply@' . str_replace( 'www.', '', $this->parameters['FQDN'] );
		$this->parameters['SMTP_HOST'] = 'localhost';
		$this->parameters['SMTP_PORT'] = '25';
		
		$this->parameters['SENDMAIL_PATH'] = '/usr/sbin/sendmail';
		$this->parameters['SENDMAIL_ARGS'] = '-t -i';
		
		$this->parameters['SOLR_URL'] = 'http://' . $this->parameters['FQDN'] . '/mysqlindexer';
		
		$this->parameters['SAMPLES'] = 'checked';
		
		$this->parameters['checked'] = false;
		
		$this->parameters['TMP_PATH'] = $this->getTmpPath();
	
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
	
	private function getProjectConfigFilePath()
	{
		return implode( DIRECTORY_SEPARATOR, array($this->getWebeditHome(), 'config', 'project.default.xml') );
	}
	
	private function writeConfiguration()
	{
		$content = '<' . '?php' . "\n\t" . '$dataconfig = ' . var_export( $this->parameters, true ) . ';';
		if (file_put_contents( $this->getInstallParametersFilePath(), $content ) === false)
		{
			$this->errors[] = 'Path ' . $this->getWebeditHome() . ' is not writable.';
			return false;
		}
		return true;
	}
	
	public function applyConfiguration()
	{
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
			$this->errors[] = 'Unable to write  ' . $this->getChangePropertiesFilePath() . ' file.';
			return false;
		}
		
		$doc = new DOMDocument( '1.0', 'UTF-8' );
		$doc->formatOutput = true;
		$doc->preserveWhiteSpace = false;
		
		if ($doc->load( $this->getProjectConfigFilePath() ) === false)
		{
			$this->errors[] = 'Unable to load  ' . $this->getProjectConfigFilePath() . ' file.';
			return false;
		}
		
		$xpath = new DOMXPath( $doc );
		$nl = $xpath->query( '/project/defines/define[@name="PROJECT_ID"]' );
		$nl->item( 0 )->nodeValue = $this->getProjectId();
		
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
			$this->errors[] = 'Unable to write  ' . $this->getProjectConfigFilePath() . ' file.';
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
			$this->errors['DB'] = 'Impossible de se connecter à la base de données';
			return false;
		}
		return true;
	}
	
	private function createDataBase()
	{
		try
		{
			$dsn = 'mysql:host=' . $this->parameters['DB_HOST'] . ';port=' . $this->parameters['DB_PORT'];
			$pdo = new PDO( $dsn, $this->parameters['DB_USER'], $this->parameters['DB_PASSWORD'] );
			if ($pdo->exec( "create database if not exists `" . $this->parameters['DB_DATABASE'] . "`" ) === false)
			{
				$this->errors['DB'] = 'Impossible de créer la base de données';
				return false;
			}
			return true;
		} catch ( Exception $e )
		{
			$this->errors['DB'] = 'Impossible de se connecter au serveur';
			return false;
		}
	}
	
	private function checkFQDN()
	{
		$data = strval( time() );
		$testFilePath = implode( DIRECTORY_SEPARATOR, array($this->getWebeditHome(), 'install', 'checkFQDN.txt') );
		$url = 'http://' . $this->parameters['FQDN'] . '/install/checkFQDN.txt';
		file_put_contents( $testFilePath, $data );
		$cr = curl_init();
		$options = array(CURLOPT_RETURNTRANSFER => true, CURLOPT_TIMEOUT => 5, CURLOPT_CONNECTTIMEOUT => 5, CURLOPT_FOLLOWLOCATION => false, CURLOPT_URL => $url, CURLOPT_POSTFIELDS => null, CURLOPT_POST => false);
		curl_setopt_array( $cr, $options );
		
		$curldata = curl_exec( $cr );
		$errno = curl_errno( $cr );
		curl_close( $cr );
		
		if ($errno || $data != $curldata)
		{
			$this->errors['DOMAIN'] = 'Le nom de domaine saisi n\'est pas un domaine valide pour ce projet. Assurez-vous que http://'.$this->parameters['FQDN'].' soit accessible à votre serveur';
			return false;
		}
		@unlink( $testFilePath );
		return true;
	}
	
	private function checkMail()
	{
		if ($this->parameters['SERVER_MAIL'] != 'NOMAIL')
		{
			if (count( explode( '@', $this->parameters['NO_REPLY'] ) ) != 2)
			{
				$this->errors['MAIL'] = 'L\'adresse  de l\'expéditeur du site n\'est pas valide';
				return false;
			}
		}
		
		switch ($this->parameters['SERVER_MAIL'])
		{
			case 'SENDMAIL' :
				if (! is_executable( $this->parameters['SENDMAIL_PATH'] ))
				{
					$this->errors['MAIL'] = 'Le chemin vers sendmail n\'est pas valide';
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
					$this->errors['MAIL'] = 'Impossible de se connecter au serveur SMTP';
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
		$tmpPath = $this->parameters['TMP_PATH'];
		if (trim($tmpPath) == "")
		{
			$this->errors['TMP_PATH'] = "Veuillez renseigner le dossier temporaire";
			return false;
		}
		if (!is_readable($tmpPath) && !@mkdir($tmpPath))
		{
			$this->errors['TMP_PATH'] = $tmpPath." n'est pas accessible en lecture";
			return false;
		}
		if (!is_writable($tmpPath))
		{
			$this->errors['TMP_PATH'] = $tmpPath." n'est pas accessible en écriture";
			return false;
		}
		return true;
	}
}
