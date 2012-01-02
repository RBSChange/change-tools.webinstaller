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
	$msgText = $localeManager->getLocales('webinstaller.step1.compitibilityOk');
	$LICENSE = file_get_contents('./LICENSE.txt');
?>
<form class="cmxform" action="" method="post">
	<input type="hidden" name="NEXTSTEP" value="02" id="NEXTSTEP" />
	<div class="stepheader">
		<div class="activeStep"><?php echo $localeManager->getLocales('webinstaller.step1.label'); ?></div>
		<div class="activeArrow">&nbsp;</div>
		<div class="activeLabel divStep1"><?php echo $localeManager->getLocales('webinstaller.step1.title'); ?></div>
		<div class="nextStep"><?php echo $localeManager->getLocales('webinstaller.step2.label'); ?></div>
		<div class="nextArrow">&nbsp;</div>
		<div class="nextLabel divStep2"><?php echo $localeManager->getLocales('webinstaller.step2.title'); ?></div>
		<div class="nextStep"><?php echo $localeManager->getLocales('webinstaller.step3.label'); ?></div>
		<div class="nextArrow">&nbsp;</div>
		<div class="nextLabel divStep3"><?php echo $localeManager->getLocales('webinstaller.step3.title'); ?></div>
	</div>
	<div class="stepcontent">
		<div class="<?php echo $msgClass; ?>"><p><?php echo $msgText; ?></p></div>
		<div style="margin-left:30px">
			<p><?php echo str_replace("{productTitle}", $configManager->getProductTitle(), $localeManager->getLocales('webinstaller.step1.licenseIntro')); ?></p>
			<pre class="license"><?php echo $LICENSE; ?></pre>
			<br />
			<label for="licenseOk" style="width:33%"><?php echo $localeManager->getLocales('webinstaller.step1.licenseOk'); ?></label>
			<input type="checkbox" id="licenseOk" />
		</div>		
	</div>	
	
	<div class="stepfooter"><img src="./ressources/content_footer_bg.png" /></div>	
	<p class="download">
		<a href="javascript:submitStep01(); void 0;" title="<?php echo $localeManager->getLocales('webinstaller.step1.configureTitle'); ?>"><?php echo $localeManager->getLocales('webinstaller.step1.configure'); ?></a>
	</p>
</form>
<?php
}
else
{
	$msgClass = "error";
	$msgText = $localeManager->getLocales('webinstaller.step1.compitibilityNOk');
?>
<form class="cmxform" action="" method="post">
	<input type="hidden" name="NEXTSTEP" value="01" id="NEXTSTEP" />
	<div class="stepheader"><img src="./ressources/thread_01.png" usemap="#mapping_thread"/>
		<map name="mapping_thread" id="mapping_thread">
			<area shape="rect" href="javascript:gotoStep('01')" alt="<?php echo $localeManager->getLocales('webinstaller.step1.label'); ?>" title="<?php echo $localeManager->getLocales('webinstaller.step1.label'); ?>" coords="0,0,181,29"/>
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
		<a href="javascript:gotoStep('01'); void 0;" title="<?php echo $localeManager->getLocales('webinstaller.step1.refresh'); ?>"><?php echo $localeManager->getLocales('webinstaller.step1.refresh'); ?></a>
	</p>
</form>
<?php
}