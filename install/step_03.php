<?php
if (!defined('PROJECT_HOME_PATH') || !$systemCheckOk)
{
	header('Location: /install/index.php');
	die();
}
include PROJECT_HOME_PATH . '/install/lib/ConfigManager.class.php';
$config = ConfigManager::getInstance();
if ($config->isChecked())
{
	$config->applyConfiguration();
}
else
{
	header('Location: /install/index.php');
}
?>
<form class="cmxform" action="" method="post">
	<div class="stepheader"><img src="./ressources/thread_03.png"/></div>
	<div class="stepcontent">
		<div style="margin-left:30px">
			<h3 id="stepTitle">Création du projet</h3>
			<div class="cmdresult" id="cmdresult"></div>
		</div>
		<div class="error" id="erroroninstall" style="display: none;"><p>Une erreur est survenue lors de l'installation.</p></div>
		<div id="readytouse" style="display: none;">	
			<div class="success" id="readytousesuccess" ><p>Pour des raisons de securité, penser à supprimer le dossier 'install' de votre projet.</p></div>			
			<table>
				<tbody>
					<tr>
						<td>
							<a href="/" target="_blank"><img src="./ressources/button_index.png" /><br />Accueil du site</a>
						</td>
						<td>
							<a href="/admin" target="_blank"><img src="./ressources/button_admin.png" /><br />Interface d'administration</a>
						</td>
					</tr>	
				</tbody>
			</table>
		</div>
	</div>	
	<div class="stepfooter"><img src="./ressources/content_footer_bg.png" /></div>
</form>
<script type="text/javascript">
var projectURL = "http://<?php echo $config->getParameter('FQDN')?>";
window.onload = function() {createProject("<?php echo $config->getParameter('SAMPLES') == 'checked' ? $productType : '';?>");}
</script>
