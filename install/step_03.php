<?php
if (!defined('PROJECT_HOME_PATH'))
{
	header('Location: /install/index.php');
	die();
}

if ($configManager->isChecked())
{
	$configManager->applyConfiguration();
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
			<div class="success" id="readytousesuccess" ><p>Pour des raisons de securité, penser à supprimer le dossier 'install' de votre projet.<br/>Pensez également à accéder rapidement à <a href="http://<?php echo $configManager->getParameter('FQDN')?>/admin" target="_blank">l'interface d'administration</a> pour y définir votre mot de passe principal.</p></div>			
			<table>
				<tbody>
					<tr>
						<td>
							<a href="http://<?php echo $configManager->getParameter('FQDN')?>/" target="_blank"><img src="./ressources/button_index.png" /><br />Accueil du site</a>
						</td>
						<td>
							<a href="http://<?php echo $configManager->getParameter('FQDN')?>/admin" target="_blank"><img src="./ressources/button_admin.png" /><br />Interface d'administration</a>
						</td>
					</tr>	
				</tbody>
			</table>
		</div>
	</div>	
	<div class="stepfooter"><img src="./ressources/content_footer_bg.png" /></div>
</form>
<script type="text/javascript">
var projectURL = "http://<?php echo $configManager->getParameter('FQDN')?>";
addOnload(function() {createProject("<?php echo $configManager->getParameter('SAMPLES') == 'checked' ? $productType : '';?>");});
</script>