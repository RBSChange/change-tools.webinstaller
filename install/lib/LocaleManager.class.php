<?php
if (!defined('PROJECT_HOME_PATH'))
{
	header('Location: /install/index.php');
	die();
}

class LocaleManager
{

	/**
	 * @var LocaleManager
	 */
	private static $instance;

	/**
	 * @var string
	 */
	private $lang = "en";

	/**
	 * @var array
	 */
	private $locales = array();

	/**
	 * @return LocaleManager
	 */
	public static function getInstance()
	{
		if (self::$instance === null)
		{
			self::$instance = new self();
		}
		return self::$instance;
	}

	// Init LocaleManager with the lang of the browser
	protected function __construct()
	{
		// Set the lang
		if (isset($_SERVER["HTTP_ACCEPT_LANGUAGE"])){
			$offsetFr = strpos($_SERVER["HTTP_ACCEPT_LANGUAGE"], "fr");
			if ($offsetFr !== false){
				$offsetEn = strpos($_SERVER["HTTP_ACCEPT_LANGUAGE"], "en");
				if ($offsetEn === false || $offsetFr < $offsetEn){
					$this->lang = "fr";
				}
			}
		}
		
		include PROJECT_HOME_PATH . '/install/i18n/'.$this->lang.'.php';
		
		// Load the locales
		$this->locales = $i18nLocale;
	}

	/**
	 * Get the default lang
	 * return string $lang
	 */
	public function getLang(){
		return $this->lang;
	}

	/**
	 * Get the locale for a key
	 * return string $locale
	 */
	public function getLocales($key){
		return $this->locales[$key];
	}

}