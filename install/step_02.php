<?php
if (!defined('PROJECT_HOME_PATH') || !$systemCheckOk)
{
	header('Location: /install/index.php');
	die();
}
include PROJECT_HOME_PATH . '/install/lib/ConfigManager.class.php';
$config = ConfigManager::getInstance();
if (isset($_POST['install']) && is_array($_POST['install']))
{
	$config->setParameters($_POST['install']);
	$config->check();
}
else
{
	$config->initialise();
}
?>
<form class="cmxform" action="" method="post" id="install_form">
	<input type="hidden" name="NEXTSTEP" value="03" id="NEXTSTEP" />
	<div class="stepheader"><img src="./ressources/thread_02.png" usemap="#mapping_thread"/>
		<map name="mapping_thread" id="mapping_thread">
			<area shape="rect" href="javascript:gotoStep('01')" alt="Etape 1" title="Etape 1" coords="0,0,181,29"/>
			<area shape="rect" href="javascript:gotoStep('02')" alt="Etape 2" title="Etape 2" coords="182,0,364,29"/>
		</map>	
	</div>
	<div class="stepcontent">
		<?php if ($config->hasError()) { ?>
			<div class="error"><p>Votre configuration comporte des erreurs. Veuillez les corriger.</p></div>
		<?php } elseif ($config->isChecked()) { ?>
			<div class="success"><p>Votre configuration a été validée, vous pouvez désormais installer le projet.</p></div>
			<script type="text/javascript">
			function install_getinputvalue(formElem)
			{
				if (formElem.tagName.toLowerCase() == "select")
				{
					return formElem.options[formElem.selectedIndex].value;
				}
				if (formElem.tagName.toLowerCase() == "input" && formElem.getAttribute("type") == "checkbox")
				{
					return (formElem.checked) ? "checked" : "notchecked";
				}
				return formElem.value;
			}
			
			function install_checkmodifications()
			{
				var form = document.getElementById('install_form');
				for (var i = 0; i < form.elements.length; i++)
				{
					var formElem = form.elements[i];
					if (formElem.className.indexOf('donotmonitor') == -1 && formElem.tagName != "input" && formElem.getAttribute("type") != "hidden")
					{
						formElem.setAttribute("initialValue", install_getinputvalue(formElem));
						addOnchange(formElem, function(e) { 
							if (!e) var e = window.event;
							var elem = (e.target) ? e.target : e.srcElement;
							
							var actualValue = install_getinputvalue(elem);
							if (actualValue != elem.getAttribute("initialValue"))
							{
								elem.setAttribute("valueChanged", "true");
							}
							else
							{
								elem.removeAttribute("valueChanged");
							}
							var visibility = 'visible';
							var elems = elem.form.elements;
							for (var i = 0; i < elems.length; i++)
							{
								if (elems[i].getAttribute("valueChanged") == "true")
								{
									visibility = 'hidden';
									break;
								}
							}
							document.getElementById('launch-install').style.visibility = visibility;
						});
					}
				}
			}

			addOnload(install_checkmodifications);
			</script>
		<?php } ?>
		<div class="section">
			<img src="./ressources/section_domain.png" alt="Nom de domaine" />
			<div class="sectionfield">
				<ol>
					<li><label for="install_FQDN">Domaine :</label>
						<input name="install[FQDN]" class="textfield" id="install_FQDN" size="40" spellcheck="false"
						value="<?php echo $config->getParameter('FQDN');?>" type="text">
						<p class="help">C'est l'adresse internet principale de votre site. Si vous souhaitez gérer plusieurs sites, vous pourrez les configurer ultérieurement dans l'interface d'adminstration.</p>	
					</li>
					<li><label for="install_WWW_GROUP">WWW Groupe :</label>
						<input name="install[WWW_GROUP]" class="textfield" id="install_WWW_GROUP" size="20" spellcheck="false"
						value="<?php echo $config->getParameter('WWW_GROUP');?>" type="text">
						<p class="help">Nom du groupe ayant les accès en lecture / écriture sur les fichiers du projet.</p>
					</li>
					<li><label for="install_TMP_PATH">Dossier temporaire :</label>
						<input name="install[TMP_PATH]" class="textfield" id="install_TMP_PATH" size="20" spellcheck="false"
						value="<?php echo $config->getParameter('TMP_PATH');?>" type="text">
						<p class="help">Dossier temporaire de travail. Ce dossier doit être accessible en lecture et écriture au serveur web.</p>
					</li>
				</ol>
			</div>
		</div>
		<?php if ($config->getError('DOMAIN')) {?>
			<div class="error"><p><?php echo $config->getError('DOMAIN'); ?></p></div>
		<?php }?>
		<?php if ($config->getError('TMP_PATH')) {?>
			<div class="error"><p><?php echo $config->getError('TMP_PATH'); ?></p></div>
		<?php }?>		
		<div class="section">
			<img src="./ressources/section_db.png" alt="Base de données" />
			<div class="sectionfield">
				<ol>
					<li><label for="install_DB_HOST">Adresse serveur :</label>
					<input name="install[DB_HOST]" class="textfield nocmx" size="25" spellcheck="false"
						id="install_DB_HOST" value="<?php echo $config->getParameter('DB_HOST');?>" type="text">				
					
					<label for="install_DB_PORT" class="nocmx">&nbsp;&nbsp;Port :</label>
					<input name="install[DB_PORT]" class="textfield nocmx" size="4"
						id="install_DB_PORT" value="<?php echo $config->getParameter('DB_PORT');?>" type="text">
						
					<p class="help">Adresse DNS ou IP de votre serveur MySQL.</p>			
					</li>
				
					<li><label for="install_DB_USER">Utilisateur :</label>
					<input name="install[DB_USER]" class="textfield" id="install_DB_USER" size="20" spellcheck="false"  
						value="<?php echo $config->getParameter('DB_USER');?>" type="text"></li>
				
					<li><label for="install_DB_PASSWORD">Mot de passe :</label>
					<input name="install[DB_PASSWORD]" class="textfield" id="install_DB_PASSWORD" size="20" spellcheck="false" 
						value="<?php echo $config->getParameter('DB_PASSWORD');?>" type="text"></li>
						
					<li><label for="install_DB_DATABASE">Nom de la base :</label>
					<input name="install[DB_DATABASE]" class="textfield" size="30" spellcheck="false"
						id="install_DB_DATABASE" value="<?php echo $config->getParameter('DB_DATABASE');?>" type="text"></li>	
				</ol>
			</div>
		</div>
		<?php if ($config->getError('DB')) {?>
			<div class="error"><p><?php echo $config->getError('DB'); ?></p></div>
		<?php }?>		
		<div class="section">
			<img src="./ressources/section_mail.png" alt="Courriel" />
			<div class="sectionfield">
				<ol>
					<li><label for="install_SERVER_MAIL">Serveur Mail :</label>
					<select name="install[SERVER_MAIL]" id="install_SERVER_MAIL">
						<option value="NOMAIL" <?php if ($config->getParameter('SERVER_MAIL') == 'NOMAIL') echo 'selected="selected"'; ?>>Aucun envoi</option>
						<option value="SENDMAIL" <?php if ($config->getParameter('SERVER_MAIL') == 'SENDMAIL') echo 'selected="selected"'; ?>>Sendmail</option>
						<option value="SMTP" <?php if ($config->getParameter('SERVER_MAIL') == 'SMTP') echo 'selected="selected"'; ?>>Smtp</option>
					</select>
					</li>
					
					<li><label for="install_NO_REPLY">Expéditeur du site :</label>
					<input name="install[NO_REPLY]" class="textfield" size="50" spellcheck="false"
						id="install_NO_REPLY" value="<?php echo $config->getParameter('NO_REPLY');?>" type="text"></li>
				</ol>		
				<ol id="SENDMAIL">													
					<li><label for="install_SENDMAIL_PATH">Sendmail path :</label>
					<input name="install[SENDMAIL_PATH]" class="textfield" size="30" spellcheck="false"
						id="install_SENDMAIL_PATH" value="<?php echo $config->getParameter('SENDMAIL_PATH');?>" type="text">
					</li>
				
					<li><label for="install_SENDMAIL_ARGS">Arguments :</label>
					<input name="install[SENDMAIL_ARGS]" class="textfield" size="30" spellcheck="false"
						id="install_SENDMAIL_ARGS" value="<?php echo $config->getParameter('SENDMAIL_ARGS');?>" type="text">
					</li>
				</ol>
				<ol id="SMTP">													
					<li><label for="install_SMTP_HOST">Adresse serveur :</label>
					<input name="install[SMTP_HOST]" class="textfield" size="50" spellcheck="false"
						id="install_SMTP_HOST" value="<?php echo $config->getParameter('SMTP_HOST');?>" type="text">
					</li>
				
					<li><label for="install_SMTP_PORT">Port :</label>
					<input name="install[SMTP_PORT]" class="textfield" size="4"
						id="install_SMTP_PORT" value="<?php echo $config->getParameter('SMTP_PORT');?>" type="text">
					</li>
				</ol>
			</div>
		</div>
		<?php if ($config->getError('MAIL')) {?>
			<div class="error"><p><?php echo $config->getError('MAIL'); ?></p></div>
		<?php }?>			
		<div class="section">
			<img src="./ressources/section_search.png" alt="Recherche" />
			<div class="sectionfield">
				<ol>
					<li><label for="install_SOLR_URL">Recherche :</label>
					<input name="install[SOLR_URL]" class="textfield" size="50" spellcheck="false"
						id="install_SOLR_URL" value="<?php echo $config->getParameter('SOLR_URL');?>" type="text">
					<p class="help">Conserver la valeur par défaut en cas de doutes.</p>	
					</li>
				</ol>
			</div>
		</div>
		
		<div class="section">
			<img src="./ressources/section_sample.png" alt="Exemples" />
			<div class="sectionfield">
				<ol>
					<li><label for="install_SAMPLES">Données d'exemple :</label>
						<input name="install[SAMPLES]" class="textfield donotmonitor" value="checked"
						id="install_SAMPLES" <?php if ($config->getParameter('SAMPLES') == 'checked') echo 'checked="checked"'; ?> type="checkbox">
						<p class="help">Cocher cette case pour créer un site d'exemple.</p>	
					</li>
				</ol>
			</div>
		</div>
	</div>	
	<div class="stepfooter"><img src="./ressources/content_footer_bg.png" /></div>	
	<p class="download">
		<a href="javascript:testStep02(); void 0;" title="Tester la configuration">Tester la configuration</a>
		<?php if ($config->isChecked()) { ?>
			<a href="javascript:submitStep02(); void 0;" id="launch-install" title="Installer votre projet">Installation du projet</a>
		<?php }?>
	</p>
</form>
<script type="text/javascript">
addOnload(function() {initselectServermail();});
</script>
