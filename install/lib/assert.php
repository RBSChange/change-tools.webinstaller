<?php
function assert_ieq($value, $asserted)
{
   return strtolower($value) == strtolower($asserted);
}

function assert_ini($iniName, $iniValue = true)
{
        $value = trim(ini_get($iniName));
        return $value == $iniValue;
}

function assert_ini_size_gt($iniName, $iniValue)
{
        $value = intval(trim(ini_get($iniName)));
        return ($value >= $iniValue);
}

function assert_ext($extName)
{
        return (extension_loaded($extName));
}

function assert_php_version($version)
{
       return (version_compare(PHP_VERSION, $version, '>='));
}

function assert_function($functionName)
{
       return function_exists($functionName);
}

function assert_class($className)
{
       return class_exists($className, false);
}

function assert_writable($path, $recursive = false, $ignore = null)
{
       if (!file_exists($path) || !is_writeable($path))
       {
                return false;
       }
       if (is_dir($path) && $recursive)
       {
                $ite = new DirectoryIterator($path);
                foreach($ite as $fileInfo)
                {
                        if ($fileInfo->isDot())
                        {
                                continue;
                        }
                        if ($fileInfo->isDir())
                        {
                                if (!$fileInfo->isWritable())
                                {
                                        return false;
                                }
                                if ($ignore === null || !in_array($fileInfo->getFilename(), $ignore))
                                {
                                		$ret = assert_writable($fileInfo->getPath().DIRECTORY_SEPARATOR.$fileInfo->getFilename(), true, null);
                                        if ($ret === false)
                                        {
                                                return false;
                                        }
                                }
                        }
                }
       }
       return true;
}

function assert_symlink($path)
{
	$source = tempnam($path, "symlinktest");
	touch($source);
	$link = tempnam($path, "symlinktest");
	unlink($link);
	if (symlink($source, $link))
	{
		unlink($source);
		unlink($link);
		return true;
	}
	return false;
}

function assert_frameworksymlink($frameworksRepoVersion = '3.5.0')
{
	$source = PROJECT_HOME_PATH . DIRECTORY_SEPARATOR . 'repository' .DIRECTORY_SEPARATOR. 'framework' .DIRECTORY_SEPARATOR. 'framework-' . $frameworksRepoVersion;
	$link = PROJECT_HOME_PATH . DIRECTORY_SEPARATOR . 'framework';
	@unlink($link);
	if (symlink($source, $link))
	{
		return true;
	}
	return false;
}

function wget($url, &$curldata, &$errno)
{
	$cr = curl_init();
	$options = array(CURLOPT_RETURNTRANSFER => true, CURLOPT_TIMEOUT => 5, CURLOPT_CONNECTTIMEOUT => 5,
	 CURLOPT_FOLLOWLOCATION => false, CURLOPT_URL => $url, CURLOPT_POSTFIELDS => null, 
	 CURLOPT_POST => false, CURLOPT_SSL_VERIFYPEER =>false);
	curl_setopt_array( $cr, $options );
	$curldata = curl_exec( $cr );
	$errno = curl_errno( $cr );
	curl_close( $cr );
}

function assert_url($url, $data)
{
	$curldata = null;
	$errno = null;
	wget($url, $curldata, $errno);
	if ($errno || $data != $curldata)
	{
		return false;
	}
	return true;
}

function assert_selfview()
{
	$data = "selfviewOK".md5((isset($_SERVER["HTTPS"]) ? "on":"")."/".$_SERVER["HTTP_HOST"]."/".dirname(dirname(__FILE__)));
	// 'off' can be a value. Cf. http://php.net/manual/en/reserved.variables.server.php
	$https = isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] != "off";
	$srcUrl = "http".(($https) ? "s" : "")."://".$_SERVER["HTTP_HOST"]."/install/selfview_ok.php";
	return assert_url($srcUrl, $data);
}

function assert_rewrite()
{
	$data = "rewriteOK".md5((isset($_SERVER["HTTPS"]) ? "on":"")."/".$_SERVER["HTTP_HOST"]."/".dirname(dirname(__FILE__)));
	// 'off' can be a value. Cf. http://php.net/manual/en/reserved.variables.server.php
	$https = isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] != "off";
	$rewriteUrl = "http".(($https) ? "s" : "")."://".$_SERVER["HTTP_HOST"]."/install/rewrite_ok";
	return assert_url($rewriteUrl, $data);
}
