<?php
if (!defined('PROJECT_HOME_PATH'))
{
	header('Location: /install/index.php');
	die();
}

include PROJECT_HOME_PATH . '/install/i18n/'.$localeManager->getLang().'.php';

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
			<h3 id="stepTitle"><?php echo $localeManager->getLocales('webinstaller.step3.title'); ?></h3>
			<div class="cmdresult" id="cmdresult"></div>
		</div>
		<div class="error" id="erroroninstall" style="display: none;"><p><?php echo $localeManager->getLocales('webinstaller.step3.installError'); ?></p></div>
		<div id="readytouse" style="display: none;">	
			<div class="success" id="readytousesuccess" ><p><?php echo str_replace("{fqdn}", $configManager->getParameter('BASEURL'), $localeManager->getLocales('webinstaller.step3.installOk'));?></p></div>			
			<table>
				<tbody>
					<tr>
						<td>
							<a href="<?php echo $configManager->getParameter('BASEURL')?>/" target="_blank"><img src="./ressources/button_index.png" /><br /><?php echo $localeManager->getLocales('webinstaller.step3.homePage'); ?></a>
						</td>
						<td>
							<a href="<?php echo $configManager->getParameter('BASEURL')?>/admin" target="_blank"><img src="./ressources/button_admin.png" /><br /><?php echo $localeManager->getLocales('webinstaller.step3.adminPage'); ?></a>
						</td>
					</tr>	
				</tbody>
			</table>
		</div>
	</div>	
	<div class="stepfooter"><img src="./ressources/content_footer_bg.png" /></div>
</form>
<script type="text/javascript">
var projectURL = "<?php echo $configManager->getParameter('BASEURL')?>";
addOnload(function() {createProject("<?php echo $configManager->getParameter('SAMPLES') == 'checked' ? $configManager->productType : '';?>");});
</script>