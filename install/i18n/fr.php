<?php
$i18nLocale = array();
$i18nLocale['webinstaller.index.title'] = 'Installation {productTitle}';
$i18nLocale['webinstaller.index.toRbschangeSite'] = "Vers le site rbschange.fr";
$i18nLocale['webinstaller.index.toRbsSite'] = "Vers le site rbs.fr";

$i18nLocale['webinstaller.step1.compitibilityOk'] = "Votre hébergement est compatible";
$i18nLocale['webinstaller.step1.licenseIntro'] = "Vous êtes sur le point de démarrer l'installation de RBS Change {productTitle}.
Pour passer à l'étape suivante, merci de lire et d'accepter les termes de la licence.";
$i18nLocale['webinstaller.step1.licenseOk'] = "Acceptation de la licence :";
$i18nLocale['webinstaller.step1.configure'] = "Configuration du projet";
$i18nLocale['webinstaller.step1.configureTitle'] = "Configurer votre projet";
$i18nLocale['webinstaller.step1.compitibilityNOk'] = "Votre hébergement est incompatible";
$i18nLocale['webinstaller.step1.label'] = "Etape 1";
$i18nLocale['webinstaller.step1.title'] = "Compatibilité";
$i18nLocale['webinstaller.step1.refresh'] = "Rafraîchir";

$i18nLocale['webinstaller.step2.label'] = "Etape 2";
$i18nLocale['webinstaller.step2.title'] = "Configuration";
$i18nLocale['webinstaller.step2.configError'] = "Votre configuration comporte des erreurs. Veuillez les corriger.";
$i18nLocale['webinstaller.step2.configOk'] = "Votre configuration a été validée, vous pouvez désormais installer le projet.";
$i18nLocale['webinstaller.step2.fqdnAlt'] = "Nom de domaine";
$i18nLocale['webinstaller.step2.fqdn'] = "Domaine :";
$i18nLocale['webinstaller.step2.fqdn-help'] = "C'est l'adresse internet principale de votre site. Si vous souhaitez gérer plusieurs sites, vous pourrez les configurer ultérieurement dans l'interface d'adminstration.";
$i18nLocale['webinstaller.step2.wwwGroup'] = "WWW Groupe :";
$i18nLocale['webinstaller.step2.wwwGroup-help'] = "Nom du groupe ayant les accès en lecture / écriture sur les fichiers du projet. Dans le doute, laissez le champ vide.";
$i18nLocale['webinstaller.step2.tmpPath'] = "Dossier temporaire :";
$i18nLocale['webinstaller.step2.tmpPath-help'] = "Dossier temporaire de travail. Ce dossier doit être accessible en lecture et écriture au serveur web.";
$i18nLocale['webinstaller.step2.dbAlt'] = "Base de données";
$i18nLocale['webinstaller.step2.dbHost'] = "Adresse serveur :";
$i18nLocale['webinstaller.step2.dbHost-help'] = "Adresse DNS ou IP de votre serveur MySQL";
$i18nLocale['webinstaller.step2.dbPort'] = "&nbsp;&nbsp;Port :";
$i18nLocale['webinstaller.step2.dbUser'] = "Utilisateur :";
$i18nLocale['webinstaller.step2.dbPassword'] = "Mot de passe :";
$i18nLocale['webinstaller.step2.dbName'] = "Nom de la base :";
$i18nLocale['webinstaller.step2.mailAlt'] = "Courriel";
$i18nLocale['webinstaller.step2.mailSmtpHost'] = "Adresse serveur :";
$i18nLocale['webinstaller.step2.mailServer'] = "Serveur Mail :";
$i18nLocale['webinstaller.step2.mailNone'] = "Aucun envoi";
$i18nLocale['webinstaller.step2.mailSendmail'] = "Sendmail";
$i18nLocale['webinstaller.step2.mailSmtp'] = "Smtp";
$i18nLocale['webinstaller.step2.mailSender'] = "Expéditeur du site :";
$i18nLocale['webinstaller.step2.mailSendmailPath'] = "Binaire sendmail :";
$i18nLocale['webinstaller.step2.mailSendmailArgs'] = "Arguments :";
$i18nLocale['webinstaller.step2.mailSmtpPort'] = "Port :";
$i18nLocale['webinstaller.step2.searchAlt'] = "Recherche";
$i18nLocale['webinstaller.step2.search'] = "URL de recherche :";
$i18nLocale['webinstaller.step2.search-help'] = "Conserver la valeur par défaut en cas de doutes.";
$i18nLocale['webinstaller.step2.sampleAlt'] = "Exemples";
$i18nLocale['webinstaller.step2.sample'] = "Données d'exemple :";
$i18nLocale['webinstaller.step2.sample-help'] = "Cocher cette case pour créer un site d'exemple.";
$i18nLocale['webinstaller.step2.test'] = "Tester la configuration";
$i18nLocale['webinstaller.step2.installTitle'] = "Installer votre projet";
$i18nLocale['webinstaller.step2.install'] = "Installation du projet";
$i18nLocale['webinstaller.step2.languageAlt'] = "Langue du site";
$i18nLocale['webinstaller.step2.language'] = "Langue principale :";
$i18nLocale['webinstaller.step2.language-help'] = "Sélectionner la langue principale de votre site et de votre backoffice ainsi que les langues supplémentaires si votre site est multilingue.";
$i18nLocale['webinstaller.step2.otherLang'] = "Autres langues :";
$i18nLocale['webinstaller.step2.french'] = "Français";
$i18nLocale['webinstaller.step2.english'] = "Anglais";
$i18nLocale['webinstaller.step2.german'] = "Allemand";

$i18nLocale['webinstaller.step3.label'] = "Etape 3";
$i18nLocale['webinstaller.step3.title'] = "Création du projet";
$i18nLocale['webinstaller.step3.installError'] = "Une erreur est survenue lors de l'installation.";
$i18nLocale['webinstaller.step3.installOk'] = "Pour des raisons de securité, penser à supprimer le dossier 'install' de votre projet.<br/>Pensez également à accéder rapidement à <a href=\"{fqdn}/admin\" target=\"_blank\">l'interface d'administration</a> pour y définir votre mot de passe principal.";
$i18nLocale['webinstaller.step3.homePage'] = "Accueil du site";
$i18nLocale['webinstaller.step3.adminPage'] = "Interface d'administration";

$i18nLocale['webinstaller.configmanager.writeFileError'] = "Le fichier {filePath} n'a pu être écrit.";
$i18nLocale['webinstaller.configmanager.readXmlFileError'] = "Le fichier {filePath} n'a pu être lu comme XML valide.";
$i18nLocale['webinstaller.configmanager.dbConnexionError'] = "Impossible de se connecter à la base de données.";
$i18nLocale['webinstaller.configmanager.dbCreationError'] = "Impossible de créer la base de données.";
$i18nLocale['webinstaller.configmanager.dbServerCxonnexionError'] = "Impossible de se connecter au serveur.";
$i18nLocale['webinstaller.configmanager.fqdnError'] = "Le nom de domaine saisi n'est pas un domaine valide pour ce projet. Assurez-vous que http://{fqdn} soit accessible à votre serveur.";
$i18nLocale['webinstaller.configmanager.symlinkError'] = "Imposible de créer le lien symbolique {target} -&gt; {link} ";
$i18nLocale['webinstaller.configmanager.versionError'] = "la version {frameworkVersion} du framework n'est pas disponible.";
$i18nLocale['webinstaller.configmanager.invalidMailError'] = "L'adresse de l'expéditeur du site n'est pas valide.";
$i18nLocale['webinstaller.configmanager.sendmailError'] = "Le chemin vers sendmail n'est pas valide.";
$i18nLocale['webinstaller.configmanager.smtpError'] = "Impossible de se connecter au serveur SMTP.";
$i18nLocale['webinstaller.configmanager.emptyTmpPathError'] = "Veuillez renseigner le dossier temporaire.";
$i18nLocale['webinstaller.configmanager.writeTmpPathError'] = "{tmpPath} n'est pas accessible en écriture.";

$i18nLocale['webinstaller.checkinstall.generateErrorReporting.php_version'] = "La version de php minimum est 5.1.6";
$i18nLocale['webinstaller.checkinstall.generateErrorReporting.php_ini_safe_mode'] = "la valeur de la variable de configuration [safe_mode] doit être à off";
$i18nLocale['webinstaller.checkinstall.generateErrorReporting.php_ini_short_open_tag'] = "la valeur de la variable de configuration [short_open_tag] doit être à off";
$i18nLocale['webinstaller.checkinstall.generateErrorReporting.php_ini_default_charset'] = "la valeur de la variable de configuration [default_charset] doit être à utf-8";
$i18nLocale['webinstaller.checkinstall.generateErrorReporting.php_ini_allow_url_fopen'] = "la valeur de la variable de configuration [allow_url_fopen] doit être à on";
$i18nLocale['webinstaller.checkinstall.generateErrorReporting.php_ini_file_uploads'] = "la valeur de la variable de configuration [file_uploads] doit être à on";
$i18nLocale['webinstaller.checkinstall.generateErrorReporting.php_ini_magic_quotes_gpc'] = "la valeur de la variable de configuration [magic_quotes_gpc] doit être à off";
$i18nLocale['webinstaller.checkinstall.generateErrorReporting.php_ini_memory_limit'] = "la valeur de la variable de configuration [memory_limit] doit être à 64M minimum";
$i18nLocale['webinstaller.checkinstall.generateErrorReporting.php_conf_home_writable'] = "le dossier [{PROJECT_HOME_PATH}] n'est pas accessible en écriture";
$i18nLocale['webinstaller.checkinstall.generateErrorReporting.php_conf_subdir_home_writable'] = "l'ensemble des sous-dossiers de [{PROJECT_HOME_PATH}] ne sont pas accessibles en écriture";
$i18nLocale['webinstaller.checkinstall.generateErrorReporting.php_conf_global_writable'] = "le fichier [{PROJECT_HOME_PATH}/change.xml] n'est pas accessible en écriture";
$i18nLocale['webinstaller.checkinstall.generateErrorReporting.php_conf_change_properties_writable'] = "le fichier [{PROJECT_HOME_PATH}/change.properties] n'est pas accessible en écriture";
$i18nLocale['webinstaller.checkinstall.generateErrorReporting.php_conf_project_writable'] = "le fichier [{PROJECT_HOME_PATH}/config/project.default.xml] n'est pas accessible en écriture";
$i18nLocale['webinstaller.checkinstall.generateErrorReporting.php_ext_posix'] = "l'extension [posix] n'est pas installée";
$i18nLocale['webinstaller.checkinstall.generateErrorReporting.php_ext_spl'] = "l'extension [SPL] n'est pas installée";
$i18nLocale['webinstaller.checkinstall.generateErrorReporting.php_ext_reflection'] = "l'extension [Reflection] n'est pas installée";
$i18nLocale['webinstaller.checkinstall.generateErrorReporting.php_ext_curl'] = "l'extension [curl] n'est pas installée";
$i18nLocale['webinstaller.checkinstall.generateErrorReporting.php_ext_pdo'] = "l'extension [pdo] [pdo_mysql] n'est pas installée";
$i18nLocale['webinstaller.checkinstall.generateErrorReporting.php_ext_xml_dom'] = "l'extension [dom] n'est pas installée";
$i18nLocale['webinstaller.checkinstall.generateErrorReporting.php_ext_xml_w'] = "l'extension [xmlwriter] n'est pas installée";
$i18nLocale['webinstaller.checkinstall.generateErrorReporting.php_ext_xml_r'] = "l'extension [xmlreader] n'est pas installée";
$i18nLocale['webinstaller.checkinstall.generateErrorReporting.php_ext_xml_sxml'] = "l'extension [SimpleXML] n'est pas installée";
$i18nLocale['webinstaller.checkinstall.generateErrorReporting.php_ext_xsl'] = "l'extension [xsl] n'est pas installée";
$i18nLocale['webinstaller.checkinstall.generateErrorReporting.php_ext_string_mbstring'] = "l'extension [mbstring] n'est pas installée";
$i18nLocale['webinstaller.checkinstall.generateErrorReporting.php_ext_string_iconv'] = "l'extension [iconv] n'est pas installée";
$i18nLocale['webinstaller.checkinstall.generateErrorReporting.php_ext_gd'] = "l'extension [gd] n'est pas installée";
$i18nLocale['webinstaller.checkinstall.generateErrorReporting.php_ext_json'] = "l'extension [json] n'est pas installée";
$i18nLocale['webinstaller.checkinstall.generateErrorReporting.system_symlink'] = "Votre système ne permet pas la création de liens symboliques";
$i18nLocale['webinstaller.checkinstall.generateErrorReporting.system_selfview'] = "Le serveur n'a pu faire de requête vers lui même : assurez-vous que {SERVER_HTTP_HOST} soit accessible à votre serveur";
$i18nLocale['webinstaller.checkinstall.generateErrorReporting.system_rewrite'] = "Le module mod_rewrite n'a pas l'air activé. Veuillez activer mod_rewrite ou vous assurer que ces directives peuvent être placées dans un fichier .htaccess";