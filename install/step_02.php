<?php
if (!defined('PROJECT_HOME_PATH'))
{
	header('Location: /install/index.php');
	die();
}

if (isset($_POST['install']) && is_array($_POST['install']))
{
	$configManager->setParameters($_POST['install']);
	$configManager->check();
}
else
{
	$configManager->initialise();
}
?>
<form class="cmxform" action="" method="post" id="install_form">
	<input type="hidden" name="NEXTSTEP" value="03" id="NEXTSTEP" />
	<div class="stepheader"><img src="./ressources/thread_02.png" usemap="#mapping_thread"/>
		<map name="mapping_thread" id="mapping_thread">
			<area shape="rect" href="javascript:gotoStep('01')" alt="<?php echo $localeManager->getLocales('webinstaller.step1.label'); ?>" title="<?php echo $localeManager->getLocales('webinstaller.step1.label'); ?>" coords="0,0,181,29"/>
			<area shape="rect" href="javascript:gotoStep('02')" alt="<?php echo $localeManager->getLocales('webinstaller.step2.label'); ?>" title="<?php echo $localeManager->getLocales('webinstaller.step2.label'); ?>" coords="182,0,364,29"/>
		</map>	
	</div>
	<div class="stepcontent">
		<?php if ($configManager->hasError()) { ?>
			<div class="error">
				<p><?php echo $localeManager->getLocales('webinstaller.step2.configError'); 
				if ($configManager->getError('others')) {
					echo "<br/>";
					foreach ($configManager->getError('others') as $other) {
						echo "- ".$other . "<br/>\n";
					}
				} ?></p>
			</div>
		<?php } elseif ($configManager->isChecked()) { ?>
			<div class="success"><p><?php echo $localeManager->getLocales('webinstaller.step2.configOk'); ?></p></div>
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
			<img src="./ressources/.png" alt="<?php echo $localeManager->getLocales('webinstaller.step2.languageAlt'); ?>" />
			<div class="sectionfield">
				<ol>
					<li><label for="install_default_lang"><?php echo $localeManager->getLocales('webinstaller.step2.language'); ?></label>
					<select name="install[DEFAULT_LANG]" id="install_default_lang">
						<option value="fr" <?php if ($configManager->getParameter('DEFAULT_LANG') == 'fr') echo 'selected="selected"'; ?>><?php echo $localeManager->getLocales('webinstaller.step2.french'); ?></option>
						<option value="en" <?php if ($configManager->getParameter('DEFAULT_LANG') == 'en') echo 'selected="selected"'; ?>><?php echo $localeManager->getLocales('webinstaller.step2.english'); ?></option>
					</select>
						<p class="help"><?php echo $localeManager->getLocales('webinstaller.step2.language-help'); ?></p>	
					</li>
					<li><label><?php echo $localeManager->getLocales('webinstaller.step2.otherLang'); ?></label>
					</li>
					<li>
						<label for="install_FRENCH"><?php echo $localeManager->getLocales('webinstaller.step2.french'); ?></label>
						<input name="install[OTHER_LANG1]" type="checkbox" <?php if ($configManager->getParameter('OTHER_LANG1')) echo 'checked="true"'; ?> id="install_FRENCH" value="fr" />
					</li>
					<li>	
						<label for="install_ENGLISH"><?php echo $localeManager->getLocales('webinstaller.step2.english'); ?></label>
						<input name="install[OTHER_LANG2]" type="checkbox" <?php if ($configManager->getParameter('OTHER_LANG2')) echo 'checked="true"'; ?> id="install_ENGLISH" value="en" />
					</li>
					<li>	
						<label for="install_GERMAN"><?php echo $localeManager->getLocales('webinstaller.step2.german'); ?></label>
						<input name="install[OTHER_LANG3]" type="checkbox" <?php if ($configManager->getParameter('OTHER_LANG3')) echo 'checked="true"'; ?> id="install_GERMAN" value="de" />
						<p class="help"><?php echo $localeManager->getLocales('webinstaller.step2.multilingual-help'); ?></p>	
					</li>
				</ol>
			</div>
		</div>
		<div class="section">
			<img src="./ressources/section_domain.png" alt="<?php echo $localeManager->getLocales('webinstaller.step2.fqdnAlt'); ?>" />
			<div class="sectionfield">
				<ol>
					<li><label for="install_FQDN"><?php echo $localeManager->getLocales('webinstaller.step2.fqdn'); ?></label>
						<input name="install[FQDN]" class="textfield" id="install_FQDN" size="40" spellcheck="false"
						value="<?php echo $configManager->getParameter('FQDN');?>" type="text">
						<p class="help"><?php echo $localeManager->getLocales('webinstaller.step2.fqdn-help'); ?></p>	
					</li>
					<li><label for="install_WWW_GROUP"><?php echo $localeManager->getLocales('webinstaller.step2.wwwGroup'); ?></label>
						<input name="install[WWW_GROUP]" class="textfield" id="install_WWW_GROUP" size="20" spellcheck="false"
						value="<?php echo $configManager->getParameter('WWW_GROUP');?>" type="text">
						<p class="help"><?php echo $localeManager->getLocales('webinstaller.step2.wwwGroup-help'); ?></p>
					</li>
					<li><label for="install_TMP_PATH"><?php echo $localeManager->getLocales('webinstaller.step2.tmpPath'); ?></label>
						<input name="install[TMP_PATH]" class="textfield" id="install_TMP_PATH" size="20" spellcheck="false"
						value="<?php echo $configManager->getParameter('TMP_PATH');?>" type="text">
						<p class="help"><?php echo $localeManager->getLocales('webinstaller.step2.tmpPath-help'); ?></p>
					</li>
				</ol>
			</div>
		</div>
		<?php if ($configManager->getError('DOMAIN')) {?>
			<div class="error"><p><?php echo $configManager->getError('DOMAIN'); ?></p></div>
		<?php }?>
		<?php if ($configManager->getError('TMP_PATH')) {?>
			<div class="error"><p><?php echo $configManager->getError('TMP_PATH'); ?></p></div>
		<?php }?>		
		<div class="section">
			<img src="./ressources/section_db.png" alt="<?php echo $localeManager->getLocales('webinstaller.step2.dbAlt'); ?>" />
			<div class="sectionfield">
				<ol>
					<li><label for="install_DB_HOST"><?php echo $localeManager->getLocales('webinstaller.step2.dbHost'); ?></label>
					<input name="install[DB_HOST]" class="textfield nocmx" size="25" spellcheck="false"
						id="install_DB_HOST" value="<?php echo $configManager->getParameter('DB_HOST');?>" type="text">				
					
					<label for="install_DB_PORT" class="nocmx"><?php echo $localeManager->getLocales('webinstaller.step2.dbPort'); ?></label>
					<input name="install[DB_PORT]" class="textfield nocmx" size="4"
						id="install_DB_PORT" value="<?php echo $configManager->getParameter('DB_PORT');?>" type="text">
						
					<p class="help"><?php echo $localeManager->getLocales('webinstaller.step2.dbHost-help'); ?></p>			
					</li>
				
					<li><label for="install_DB_USER"><?php echo $localeManager->getLocales('webinstaller.step2.dbUser'); ?></label>
					<input name="install[DB_USER]" class="textfield" id="install_DB_USER" size="20" spellcheck="false"  
						value="<?php echo $configManager->getParameter('DB_USER');?>" type="text"></li>
				
					<li><label for="install_DB_PASSWORD"><?php echo $localeManager->getLocales('webinstaller.step2.dbPassword'); ?></label>
					<input name="install[DB_PASSWORD]" class="textfield" id="install_DB_PASSWORD" size="20" spellcheck="false" 
						value="<?php echo $configManager->getParameter('DB_PASSWORD');?>" type="text"></li>
						
					<li><label for="install_DB_DATABASE"><?php echo $localeManager->getLocales('webinstaller.step2.dbName'); ?></label>
					<input name="install[DB_DATABASE]" class="textfield" size="30" spellcheck="false"
						id="install_DB_DATABASE" value="<?php echo $configManager->getParameter('DB_DATABASE');?>" type="text"></li>	
				</ol>
			</div>
		</div>
		<?php if ($configManager->getError('DB')) {?>
			<div class="error"><p><?php echo $configManager->getError('DB'); ?></p></div>
		<?php }?>		
		<div class="section">
			<img src="./ressources/section_mail.png" alt="<?php echo $localeManager->getLocales('webinstaller.step2.mailAlt'); ?>" />
			<div class="sectionfield">
				<ol>
					<li><label for="install_SERVER_MAIL"><?php echo $localeManager->getLocales('webinstaller.step2.mailServer'); ?></label>
					<select name="install[SERVER_MAIL]" id="install_SERVER_MAIL">
						<option value="NOMAIL" <?php if ($configManager->getParameter('SERVER_MAIL') == 'NOMAIL') echo 'selected="selected"'; ?>><?php echo $localeManager->getLocales('webinstaller.step2.mailNone'); ?></option>
						<option value="SENDMAIL" <?php if ($configManager->getParameter('SERVER_MAIL') == 'SENDMAIL') echo 'selected="selected"'; ?>><?php echo $localeManager->getLocales('webinstaller.step2.mailSendmail'); ?></option>
						<option value="SMTP" <?php if ($configManager->getParameter('SERVER_MAIL') == 'SMTP') echo 'selected="selected"'; ?>><?php echo $localeManager->getLocales('webinstaller.step2.mailSmtp'); ?></option>
					</select>
					</li>
					
					<li><label for="install_NO_REPLY"><?php echo $localeManager->getLocales('webinstaller.step2.mailSender'); ?></label>
					<input name="install[NO_REPLY]" class="textfield" size="42" spellcheck="false"
						id="install_NO_REPLY" value="<?php echo $configManager->getParameter('NO_REPLY');?>" type="text"></li>
				</ol>		
				<ol id="SENDMAIL">													
					<li><label for="install_SENDMAIL_PATH"><?php echo $localeManager->getLocales('webinstaller.step2.mailSendmailPath'); ?></label>
					<input name="install[SENDMAIL_PATH]" class="textfield" size="30" spellcheck="false"
						id="install_SENDMAIL_PATH" value="<?php echo $configManager->getParameter('SENDMAIL_PATH');?>" type="text">
					</li>
				
					<li><label for="install_SENDMAIL_ARGS"><?php echo $localeManager->getLocales('webinstaller.step2.mailSendmailArgs'); ?></label>
					<input name="install[SENDMAIL_ARGS]" class="textfield" size="30" spellcheck="false"
						id="install_SENDMAIL_ARGS" value="<?php echo $configManager->getParameter('SENDMAIL_ARGS');?>" type="text">
					</li>
				</ol>
				<ol id="SMTP">													
					<li><label for="install_SMTP_HOST"><?php echo $localeManager->getLocales('webinstaller.step2.mailSmtpHost'); ?></label>
					<input name="install[SMTP_HOST]" class="textfield" size="42" spellcheck="false"
						id="install_SMTP_HOST" value="<?php echo $configManager->getParameter('SMTP_HOST');?>" type="text">
					</li>
				
					<li><label for="install_SMTP_PORT"><?php echo $localeManager->getLocales('webinstaller.step2.mailSmtpPort'); ?></label>
					<input name="install[SMTP_PORT]" class="textfield" size="4"
						id="install_SMTP_PORT" value="<?php echo $configManager->getParameter('SMTP_PORT');?>" type="text">
					</li>
				</ol>
			</div>
		</div>
		<?php if ($configManager->getError('MAIL')) {?>
			<div class="error"><p><?php echo $configManager->getError('MAIL'); ?></p></div>
		<?php }?>			
		<div class="section">
			<img src="./ressources/section_search.png" alt="<?php echo $localeManager->getLocales('webinstaller.step2.searchAlt'); ?>" />
			<div class="sectionfield">
				<ol>
					<li><label for="install_SOLR_URL"><?php echo $localeManager->getLocales('webinstaller.step2.search'); ?></label>
					<input name="install[SOLR_URL]" class="textfield" size="42" spellcheck="false"
						id="install_SOLR_URL" value="<?php echo $configManager->getParameter('SOLR_URL');?>" type="text">
					<p class="help"><?php echo $localeManager->getLocales('webinstaller.step2.search-help'); ?></p>	
					</li>
				</ol>
			</div>
		</div>
		
		<div class="section">
			<img src="./ressources/section_sample.png" alt="<?php echo $localeManager->getLocales('webinstaller.step2.sampleAlt'); ?>" />
			<div class="sectionfield">
				<ol>
					<li><label for="install_SAMPLES"><?php echo $localeManager->getLocales('webinstaller.step2.sample'); ?></label>
						<input name="install[SAMPLES]" class="textfield donotmonitor" value="checked"
						id="install_SAMPLES" <?php if ($configManager->getParameter('SAMPLES') == 'checked') echo 'checked="checked"'; ?> type="checkbox">
						<p class="help"><?php echo $localeManager->getLocales('webinstaller.step2.sample-help'); ?></p>	
					</li>
				</ol>
			</div>
		</div>
		
	</div>	
	<div class="stepfooter"><img src="./ressources/content_footer_bg.png" /></div>	
	<p class="download">
		<a href="javascript:testStep02(); void 0;" title="<?php echo $localeManager->getLocales('webinstaller.step2.test'); ?>"><?php echo $localeManager->getLocales('webinstaller.step2.test'); ?></a>
		<?php if ($configManager->isChecked()) { ?>
			<a href="javascript:submitStep02(); void 0;" id="launch-install" title="<?php echo $localeManager->getLocales('webinstaller.step2.installTitle'); ?>"><?php echo $localeManager->getLocales('webinstaller.step2.install'); ?></a>
		<?php }?>
	</p>
</form>
<script type="text/javascript">
addOnload(function() {initselectServermail();});
</script>
