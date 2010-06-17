
function submitForm0()
{
	var form = document.forms[0];
	if (!form.hasAttribute('submited'))
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
		alert("Vous devez accepter la licence");
	}
}

function submitStep01Error() 
{
	var nextStep = document.getElementById('NEXTSTEP');
	nextStep.setAttribute("value", "01ERR");
	submitForm0();
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
	if ('attachEvent' in mailSelect)
	{
		mailSelect.attachEvent('onchange', selectServermail);
	}
	else
	{
		mailSelect.addEventListener('change', selectServermail, false);
	}
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
		{cmd: "init-project", label:"Initialisation du projet (peut prendre quelques minutes)"},
		{cmd: "init-generic-modules", label:"Initialisation des modules"},
		{cmd: "init-webapp", label:"Initialisation des fichiers du site web"},
		{cmd: "compile-config", label:"Compilation de la configuration"},
		{cmd: "compile-documents", label:"Génération des classes de persistance des documents"},
		{cmd: "generate-database", label:"Génération de la structure de la base de données"},
		{cmd: "compile-document-filters", label:"Compilation des filtres de documents"},
		{cmd: "compile-roles", label:"Compilation des rôles"},
		{cmd: "compile-permissions", label:"Compilation des permissions"},
		{cmd: "compile-locales", label:"Compilation des traductions"},
		{cmd: "compile-tags", label:"Compilation des définitions de tags"},
		{cmd: "compile-listeners", label:"Compilation des événements php"},
		{cmd: "compile-js-dependencies", label:"Compilations des dépendances javascript"},
		{cmd: "compile-phptal", label:"Compilation des balises PHPTAL spécifiques"},
		{cmd: "compile-db-schema", label:"Initialisation des relations de base de données"},
		{cmd: "compile-aop", label:"Compilation AOP"},
		
		{cmd: "compile-url-rewriting", label:"Compilation des règles de réécriture"},
		{cmd: "compile-blocks", label:"Compilation des blocs d'affichage"},
		{cmd: "compile-editors-config", label:"Compilation des éditeurs de documents"},
		{cmd: "compile-htaccess", label:"Compilation des règles d'accès du serveur apache"},

		{cmd: "import-init-data", label:"Import des données systèmes des modules"},
		{cmd: "init-patch-db", label:"Initialisation de la base des patchs"},
		{cmd: "reset-root-user", label:"Initialisation de l'utilisateur wwwadmin"}
]

function addSamples(sampleType)
{
	cmds.push({cmd: "theme.install webfactory", label:"Import du thème webfactory"});
	cmds.push({cmd: "import-data website sample.xml", label:"Création du site d'exemple"});
	cmds.push({cmd: "import-data form sample.xml", label:"Création de formulaires d'exemples"});	
	cmds.push({cmd: "import-data users sample.xml", label:"Création d'utilisateurs d'exemples"});
	
	switch (sampleType) 
	{
	 	case "cmsecomos":
	 		cmds.push({cmd: "import-data sharethis sample.xml", label:"Création d'un formulaire de partage de lien"});
	 	case "ecommercecore":
	 		cmds.push({cmd: "import-data catalog default.xml", label:"Création d'un catalogue d'exemple"});
	 		cmds.push({cmd: "import-data customer default.xml", label:"Création de clients d'exemple"});
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
	stepTitel.innerHTML = 'Action ' + (cmdindex + 1) + ' / ' + cmds.length + ': ' + cmdInfo.label;
	
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
	stepTitel.innerHTML = 'Installation terminée : Test de disponibilité ...';
	
	var cmdElem = document.getElementById('cmdresult');
	cmdElem.innerHTML += "<span class=\"row_std\">change:test_availability.php</span><br />";
	cmdElem.scrollTop = cmdElem.scrollHeight;
	
	var req = new XMLHttpRequest();
	req.open('GET', projectURL + '/test_availability.php', true);
	req.onreadystatechange = function (aEvt) 
	{
		if (req.readyState == 4) 
		{
			if (req.status == 200 && req.responseText == 'OK')
			{
				stepTitel.innerHTML = 'Installation terminé';			
				var readytouse = document.getElementById("readytouse");
				readytouse.style.display = 'block';
			}
			else
			{	
				stepTitel.innerHTML = 'Installation échouée';
				cmdElem.innerHTML += "<span class=\"row_31\">http status :" + req.status +"</span><br />" +
				"<span class=\"row_31\">" + req.responseText +"</span><br />";			
			}
			cmdElem.style.cursor = 'default';
			cmdElem.scrollTop = cmdElem.scrollHeight;
		}
	};
	req.send(null);
}
