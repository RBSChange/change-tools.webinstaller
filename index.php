<?php
$requestURI = ($_SERVER['REQUEST_URI'] == "/") ? "/index.php" : $_SERVER["REQUEST_URI"];
if ($requestURI != '/'.basename($_SERVER['SCRIPT_NAME']))
{
	echo "Dossier d'installation invalide : le script d'installation doit être placé à la racine";
}
else
{
	header('Location: /install/index.php');
}
die();