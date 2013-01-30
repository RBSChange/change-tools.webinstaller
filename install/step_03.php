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
	<div class="stepheader">
		<div class="previousStep"><?php echo $localeManager->getLocales('webinstaller.step1.label'); ?></div>
		<div class="previousArrow">&nbsp;</div>
		<div class="previousLabel divStep1"><?php echo $localeManager->getLocales('webinstaller.step1.title'); ?></div>
		<div class="previousStep"><?php echo $localeManager->getLocales('webinstaller.step2.label'); ?></div>
		<div class="previousArrow">&nbsp;</div>
		<div class="previousLabel divStep2"><?php echo $localeManager->getLocales('webinstaller.step2.title'); ?></div>
		<div class="activeStep"><?php echo $localeManager->getLocales('webinstaller.step3.label'); ?></div>
		<div class="activeArrow">&nbsp;</div>
		<div class="activeLabel divStep3"><?php echo $localeManager->getLocales('webinstaller.step3.title'); ?></div>
	</div>
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
							<a href="<?php echo $configManager->getParameter('BASEURL')?>/" target="_blank"><img src="./resources/button_index.png" /><br /><?php echo $localeManager->getLocales('webinstaller.step3.homePage'); ?></a>
						</td>
						<td>
							<a href="<?php echo $configManager->getParameter('BASEURL')?>/admin" target="_blank"><img src="./resources/button_admin.png" /><br /><?php echo $localeManager->getLocales('webinstaller.step3.adminPage'); ?></a>
						</td>
					</tr>	
				</tbody>
			</table>
		</div>
	</div>	
	<div class="stepfooter"><img src="./resources/content_footer_bg.png" /></div>
</form>
<script type="text/javascript">
var projectURL = "<?php echo $configManager->getParameter('BASEURL')?>";
addOnload(function() {createProject("<?php echo $configManager->getParameter('SAMPLES') == 'checked' ? $configManager->productType : '';?>");});
</script>