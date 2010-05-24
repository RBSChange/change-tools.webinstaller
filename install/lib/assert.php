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

function assert_writable($path)
{
       return file_exists($path) && is_writeable($path);
}