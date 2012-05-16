
function submitForm0()
{
	var form = document.forms[0];
	if (form.getAttribute('submited') != 'true')
	{
		form.setAttribute('submited', 'true');
		form.submit();
	}
}

function gotoStep(stepName)
{
	var nextStep = document.getElementById('NEXTSTEP');
	nextStep.setAttribute("value", stepName);
	submitForm0();	
}

function submitStep01() 
{
	var ok = document.getElementById('licenseOk');
	if (ok.checked)
	{
		submitForm0();
	}
	else
	{
		alert(i18nLocale.submitStep01_agreementRequired);
	}
}

function testStep02() 
{
	var nextStep = document.getElementById('NEXTSTEP');
	nextStep.setAttribute("value", "02");
	submitForm0();
}

function submitStep02() 
{
	submitForm0();
}

function initselectServermail()
{
	var mailSelect = document.getElementById('install_SERVER_MAIL');
	addOnchange(mailSelect, selectServermail);
	selectServermail(null);
}


function selectServermail(event)
{
	var mailSelect = document.getElementById('install_SERVER_MAIL');
	var fieldsSendmail = document.getElementById('SENDMAIL');
	var fieldsSmtp = document.getElementById('SMTP');
	switch (mailSelect.selectedIndex)
	{
		case 1:
			fieldsSendmail.style.display = 'block';
			fieldsSmtp.style.display = 'none';
			break;
		case 2:
			fieldsSendmail.style.display = 'none';
			fieldsSmtp.style.display = 'block';
			break;	
		default:
			fieldsSendmail.style.display = 'none';
			fieldsSmtp.style.display = 'none';
			break;
	}
}

var cmds = [    
		{cmd: "init-project --clear", label:i18nLocale.cmd_init_project},
		{cmd: "init-webapp", label:i18nLocale.cmd_init_webapp},
		{cmd: "compile-config", label:i18nLocale.cmd_compile_config},
		{cmd: "update-autoload", label:i18nLocale.cmd_update_autoload},
		{cmd: "compile-documents", label:i18nLocale.cmd_compile_documents},
		{cmd: "generate-database", label:i18nLocale.cmd_generate_database},
		{cmd: "compile-document-filters", label:i18nLocale.cmd_compile_document_filters},
		{cmd: "compile-roles", label:i18nLocale.cmd_compile_roles},
		{cmd: "compile-permissions", label:i18nLocale.cmd_compile_permissions},
		{cmd: "compile-locales", label:i18nLocale.cmd_compile_locales},
		{cmd: "compile-tags", label:i18nLocale.cmd_compile_tags},
		{cmd: "compile-listeners", label:i18nLocale.cmd_compile_listeners},
		{cmd: "compile-js-dependencies", label:i18nLocale.cmd_compile_js_dependencies},
		{cmd: "compile-phptal", label:i18nLocale.cmd_compile_phptal},
		{cmd: "compile-db-schema", label:i18nLocale.cmd_compile_db_schema},
		{cmd: "compile-aop", label:i18nLocale.cmd_compile_aop},
		
		{cmd: "compile-url-rewriting", label:i18nLocale.cmd_compile_url_rewriting},
		{cmd: "compile-blocks", label:i18nLocale.cmd_compile_blocks},
		{cmd: "compile-editors-config", label:i18nLocale.cmd_compile_editors_config},
		{cmd: "compile-htaccess", label:i18nLocale.cmd_compile_htaccess},
		{cmd: "website.compile-bbcodes", label:i18nLocale.cmd_website_compile_bbcodes},

		{cmd: "import-init-data", label:i18nLocale.cmd_import_init_data},
		{cmd: "compile-config", label:i18nLocale.cmd_compile_config},
		{cmd: "theme.install", label:i18nLocale.cmd_theme_install},
		{cmd: "init-patch-db", label:i18nLocale.cmd_init_patch_db}
]

function addSamples(sampleType)
{
	cmds.push({cmd: "import-data website sample.xml", label:i18nLocale.cmd_import_data_website_sample_xml});
	cmds.push({cmd: "import-data form sample.xml", label:i18nLocale.cmd_import_data_form_sample_xml});	
	cmds.push({cmd: "import-data users sample.xml", label:i18nLocale.cmd_import_data_users_sample_xml});
	
	switch (sampleType) 
	{
	 	case "cmsecomos":
	 		cmds.push({cmd: "import-data sharethis sample.xml", label:i18nLocale.cmd_import_data_sharethis_sample_xml});
	 	case "ecommercecore":
	 		cmds.push({cmd: "import-data catalog default.xml", label:i18nLocale.cmd_import_data_catalog_default_xml});
	 		cmds.push({cmd: "import-data customer default.xml", label:i18nLocale.cmd_import_data_customer_default_xml});
	 		cmds.push({cmd: "import-data order default.xml", label:i18nLocale.cmd_import_data_order_default_xml});
			break;
	}
}

function createProject(sampleType)
{
	if (sampleType.length > 0)
	{
		addSamples(sampleType);
	}
	executeCmd(0);
}

function executeCmd(cmdindex)
{
	var cmdInfo = cmds[cmdindex];
	var cmd = cmdInfo.cmd;
	
	var stepTitel = document.getElementById('stepTitle');
	stepTitel.innerHTML = i18nLocale.cmdAction + ' ' + (cmdindex + 1) + ' / ' + cmds.length + ': ' + cmdInfo.label;
	
	var cmdElem = document.getElementById('cmdresult');
	cmdElem.innerHTML += "<span class=\"row_std\">change:" + cmd + "</span>";
	cmdElem.scrollTop = cmdElem.scrollHeight;
	
	var req = new XMLHttpRequest();
	req.open('GET', projectURL + '/install/lib/changeHTTP.php?cmd=' + escape(cmd), true);
	req.onreadystatechange = function (aEvt) 
	{
		if (req.readyState == 4) 
		{
			
			cmdElem.innerHTML += "<div id=\"cmd_" + cmdindex + "\">" + req.responseText + "</div>";
			cmdElem.scrollTop = cmdElem.scrollHeight;
			if(req.status == 200)
			{
				var next = cmdElem.nextSibling;
				if (cmdindex + 1 < cmds.length)
				{
					executeCmd(cmdindex + 1);
				}
				else
				{
					readyToUse();
				}
			}
			else
			{
				cmdElem.style.cursor = 'default';
				var errorOnInstall = document.getElementById('erroroninstall');
				errorOnInstall.style.display = 'block';
			}
		}
	};
	req.send(null);
}

function readyToUse()
{
	var stepTitel = document.getElementById('stepTitle');
	stepTitel.innerHTML = i18nLocale.installFinishTesting;
	
	var cmdElem = document.getElementById('cmdresult');
	cmdElem.innerHTML += "<span class=\"row_std\">change:test_availability.php</span><br />";
	cmdElem.scrollTop = cmdElem.scrollHeight;
	
	var req = new XMLHttpRequest();
	req.open('GET', projectURL + '/changecron.php', true);
	req.onreadystatechange = function (aEvt) 
	{
		if (req.readyState == 4) 
		{
			if (req.status == 200)
			{
				stepTitel.innerHTML = i18nLocale.installFinish;			
				var readytouse = document.getElementById("readytouse");
				readytouse.style.display = 'block';
			}
			else
			{	
				stepTitel.innerHTML = i18nLocale.installFailed;
				cmdElem.innerHTML += "<span class=\"row_31\">http status :" + req.status +"</span><br />" +
				"<span class=\"row_31\">" + req.responseText +"</span><br />";			
			}
			cmdElem.style.cursor = 'default';
			cmdElem.scrollTop = cmdElem.scrollHeight;
		}
	};
	req.send(null);
}

function addOnload(func) 
{
	if ('attachEvent' in window)
	{
		window.attachEvent('onload', func);
	}
	else
	{
		window.addEventListener('load', func, false);
	}
}

function addOnchange(item, func) 
{ 
	if ('attachEvent' in item)
	{
		item.attachEvent('onchange', func);
	}
	else
	{
		item.addEventListener('change', func, false);
	}
}