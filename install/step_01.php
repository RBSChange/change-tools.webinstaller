<?php
if (!defined('PROJECT_HOME_PATH'))
{
	header('Location: /install/index.php');
	die();
}
$systemCheckOk = true;
include PROJECT_HOME_PATH . '/install/lib/check_install.php';
if ($systemCheckOk)
{
	$msgClass = "success";
	$msgText = "Votre hébergement est compatible";
	$LICENSE = file_get_contents('./LICENSE.txt');
?>
<form class="cmxform" action="" method="post">
	<input type="hidden" name="NEXTSTEP" value="02" id="NEXTSTEP" />
	<div class="stepheader"><img src="./ressources/thread_01.png" usemap="#mapping_thread"/>
		<map name="mapping_thread" id="mapping_thread">
			<area shape="rect" href="javascript:gotoStep('01')" alt="Etape 1" title="Etape 1" coords="0,0,181,29"/>
		</map>	
	</div>
	<div class="stepcontent">
		<div class="<?php echo $msgClass; ?>"><p><?php echo $msgText; ?></p></div>
		<div style="margin-left:30px">
			<p>Vous êtes sur le point de démarrer l'installation du pack RBS Change <?php echo $configManager->getProductTitle() ?>. 
			Pour passer à l'étape suivante, merci de lire et d'accepter les termes de la licence.</p>
			<pre class="license"><?php echo $LICENSE; ?></pre>
			<br />
			<label for="licenseOk" style="width:33%">Acceptation de la licence: </label>
			<input type="checkbox" id="licenseOk" />
		</div>		
	</div>	
	
	<div class="stepfooter"><img src="./ressources/content_footer_bg.png" /></div>	
	<p class="download">
		<a href="javascript:submitStep01(); void 0;" title="Configurer votre projet">Configuration du projet</a>
	</p>
</form>
<?php
}
else
{
	$msgClass = "error";
	$msgText = "Votre hébergement est incompatible";
?>
<form class="cmxform" action="" method="post">
	<input type="hidden" name="NEXTSTEP" value="01" id="NEXTSTEP" />
	<div class="stepheader"><img src="./ressources/thread_01.png" usemap="#mapping_thread"/>
		<map name="mapping_thread" id="mapping_thread">
			<area shape="rect" href="javascript:gotoStep('01')" alt="Etape 1" title="Etape 1" coords="0,0,181,29"/>
		</map>
	</div>	
	<div class="stepcontent">
		<div class="<?php echo $msgClass; ?>"><?php echo $msgText; ?></div>
		<ul style="margin-left:30px">
		<?php foreach (generateErrorReporting($systemCheck) as $error) 
		{
			echo '<li>' . $error . '</li>';
		}
		?>
		</ul>		
	</div>	
	<div class="stepfooter"><img src="./ressources/content_footer_bg.png" /></div>
	<p class="download">
		<a href="javascript:gotoStep('01'); void 0;" title="Rafraichire">Rafraichire</a>
	</p>
</form>
<?php
}