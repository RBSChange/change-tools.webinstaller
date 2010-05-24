<?php
if (!defined('PROJECT_HOME_PATH') || $systemCheckOk)
{
	header('Location: /install/index.php');
	die();
}
$msgClass = "error";
$msgText = "Votre hébergement est incompatible";	
?>
<form class="cmxform" action="" method="post">
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
</form>