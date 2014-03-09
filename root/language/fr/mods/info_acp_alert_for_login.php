<?php
/**
*
* info_acp_alert_on_login.php [Français]
* @package acp info_acp_alert_for_login
* @version $Id: acp_alert_for_login.php v1.3.1 00:21 05/1/2013 Geolim4 Exp $
* @copyright (c) 2012, 2013 Geolim4.com http://geolim4.com
* @package language
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

/**
* DO NOT CHANGE
*/
if (!defined('IN_PHPBB'))
{
	exit;
}

if (empty($lang) || !is_array($lang))
{
	$lang = array();
}

// DEVELOPERS PLEASE NOTE
//
// All language files should use UTF-8 as their encoding and the files must not contain a BOM.
//
// Placeholders can now contain order information, e.g. instead of
// 'Page %s of %s' you can (and should) write 'Page %1$s of %2$s', this allows
// translators to re-order the output of data while ensuring it remains correct
//
// You do not need this where single placeholders are used, e.g. 'Message %d' is fine
// equally where a string contains only two placeholders which are used to wrap text
// in a url you again do not need to specify an order e.g., 'Click %sHERE%s' is fine
// Some characters you may want to copy&paste:
// ’ » “ ” …
// Use: <strong style="color:green">Texte</strong>',
//For add Color
//
$lang = array_merge($lang, array(
//UMIL
	'ACP_AFL_CONFIG_UMIL'			=> 'Alertes de connexions',
	'ACP_AFL_CONFIG_UMIL_PHP'		=> 'Information de version PHP',
	'ACP_AFL_CONFIG_UMIL_PHP530_OK'	=> '<strong style="color:green;">Super!! Vous possédez PHP <strong>5.3.0</strong> ou supérieur, vous pouvez continuer l’installation en toute sécurité.</strong>',
	'ACP_AFL_CONFIG_UMIL_PHP530_NO'	=> '<strong style="color:red;">Dommage!! Vous ne possédez pas PHP <strong>5.3.0</strong> ou supérieur, vous ne devriez pas continuer l’installation sans risquer des erreurs critiques</strong>',
//Logs
	'ACP_AFL_LOG_ADDED'				=> 'Ajout d’une entrée dans les journaux.',
	'ACP_AFL_LOG_ALTERED'			=> '<strong>Modification des paramètres des alertes de connexions</strong>',
	'ACP_AFL_LOG_UPDATE'			=> 'Mise à jour du MOD Alert For Login depuis la version <strong style="color: red;">%s</strong> vers <strong style="color: green;">%s</strong>',
	'ACP_AFL_LOG_OFF'				=> '<strong>Alertes de connexions désactivées pour cause d’installation incomplète.</strong><br />»Pour plus d’informations consultez les Alertes de Connexions afin de voir les erreurs retournées.',

//Mod Name
	'ACP_AFL_CONFIG'				=> 'Alertes de connexions',

//Mod error
	'AFL_INSTALL_NO_FILE'			=> 'Le fichier <strong>“ %s ”</strong> est absent.',
	'AFL_INSTALL_NO_COLLUMN'		=> 'La colonne SQL <strong>“ %1$s ”</strong> de la table <strong>“%2$s”</strong> est absente.',
	'AFL_INSTALL_NO_TABLE'			=> 'La table SQL <strong>“ %1$s ”</strong> est absente.',
	'AFL_ERROR_INC_EXC'				=> 'Vous ne pouvez pas sélectionner les <strong>même</strong> groupes à exclure et à considérer comme fondateur!',
	'AFL_NO_JAVASCRIPT'				=> 'L’Administration de ce mod requiert Javascript pour de meilleures performances, merci de l’activer',
	'AFL_FULL_OPTION'				=> 'Vous avez activé les MPs d’alerte, les E-mails d’alerte, et les sujets d’alerte, il n’est pas recommandé d’utiliser toute ces fonctions en même temps, car celà va résulter d’une grande quantité de requêtes SQL à chaque connexions d’utilisateur.',
	'ACP_AFL_ERR_INSTALL'			=> 'Le Mod a donc été désactivé par sécurité tant que l’installation ne seras pas complétée.',
	'ACP_AFL_ERR_NOCONST'			=> 'Constante <em>AFL_EXCLUDE_IPS_TABLE</em> absente... Vérifiez les modifications de “/includes/constants.php”',

//Mod statistic
	'AFL_STAT_TITLE'				=> 'Statistiques des alertes de connexions',
	'AFL_STAT_INFO'					=> 'Merci de noter que les "statistiques de connections" sont basées seulement sur les connections utilisant les "Alertes de connections"... Toutes les connections n’utilisant pas les "Alertes de connections" seront ignorées...',
	'AFL_STAT_MANY_USER'			=> 'Votre forum semble avoir beaucoup d’utilisateurs, si vous constatez des lenteurs sur les pages de connexions, désactivez les statistiques!.',
	'AFL_STAT_FAIL'					=> 'Nombre de connexions en échecs depuis l’installation des alertes de connexions',
	'AFL_STAT_SUCCESS'				=> 'Nombre de connexions réussies depuis l’installation des alertes de connexions',
	'AFL_STAT_FAIL_ACP'				=> 'Nombre de connexions en echecs à l’Administration depuis l’installation des alertes de connexions',
	'AFL_STAT_SUCCESS_ACP'			=> 'Nombre de connexions réussies à l’Administration depuis l’installation des alertes de connexions',
	'AFL_STAT_PM'					=> 'Nombres de messages privés envoyés par les alertes de connexions',
	'AFL_STAT_MAIL'					=> 'Nombres de mail envoyés par les alertes de connexions',
	'AFL_STAT_TOPIC'				=> 'Nombres de sujets crées par les alertes de connexions',
	'AFL_INSTALL_DATE'				=> 'Date d’installation des alertes de connexions',
	'AFL_UPDATE_DATE'				=> 'Date de mise à jour des alertes de connexions',
	'AFL_RATIO_LOGIN'				=> 'Ratio de connexions réussies/échouées',
	'AFL_STAT_USERS_SUCCESS'		=> 'Nombre d’utilisateurs qui utilisent l’alerte de connexions réussies.<br />  (Basé sur une liste de %s utilisateurs)',
	'AFL_STAT_USERS_FAIL'			=> 'Nombre d’utilisateurs qui utilisent l’alerte de connexions échouées.<br />  (Basé sur une liste de %s utilisateurs)',

//Mod Settings
	'AFL_ALERT_LIMIT'				=> 'Limites d’alertes (Connections échouées seulement)',
	'AFL_ALERT_LIMIT_EXPLAIN'		=> 'Nombre limite d’alertes qui seront envoyées après de multiples échecs de connections(0 pour désactiver).
										<br />Une fois cette limite franchie, plus personne ne seras alerté, y compris les fondateurs.',
	'AFL_FOUNDER_EXCLUDE'			=> 'Fondateur(s) exclu(s) des alertes',
	'AFL_FOUNDER_EXCLUDE_EXPLAIN'	=> 'Vous pouvez sélectionner un ou des fondateurs qui seront exclus des alertes de connexions.
										<br />Notez que ce paramètre influe seulement sur les alertes reçues à partir des comptes autres que fondateurs.
										<br /><em>Utilisez ctrl+clic pour sélectionner plus d’un fondateur.</em>',
	'AFL_GROUPS_EXCLUDE'			=> 'Groupe(s) exclu(s) des alertes',
	'AFL_GROUPS_EXCLUDE_EXPLAIN'	=> 'Vous pouvez sélectionner un ou des groupes qui seront exclus des alertes de connexions.
										<br />Notez que ce paramètre est basé seulement sur le groupe par <strong>défaut</strong> de l’utilisateur, et que ce paramètre écrase le paramètre <em>"Groupe(s) considérés(s) comme fondateurs"</em> ci-dessous.
										<br /><em>Utilisez ctrl+clic pour sélectionner plus d’un groupe.</em>',
	'AFL_GROUPS_INCLUDE'			=> 'Groupe(s) considérés(s) comme fondateurs',
	'AFL_GROUPS_INCLUDE_EXPLAIN'	=> 'Vous pouvez sélectionner un ou des groupes qui seront considérés comme des membres fondateurs.
										<br />Notez que ce paramètre est basé sur tout les groupe dont l’utilisateur est membre.
										<br /><em>Utilisez ctrl+clic pour sélectionner plus d’un groupe.</em>',
	'AFL_USERS_INDIVIDUAL'			=> 'Gestion des utilisateurs individuels',
	'AFL_USERS_INCLUDE'				=> 'Utilisateurs(s) individuel(s) à considérer comme fondateurs',
	'AFL_USERS_INCLUDE_EXPLAIN'		=> 'Vous pouvez ajouter un ou des utilisateurs qui seront considérés comme des membres fondateurs.
										<br />Notez que les fondateurs réels actuels seront ignorés de cette liste.
										<br /><em>Pour spécifier plusieurs utilisateurs, entrez chacun d’eux sur une nouvelle ligne.</em>',
	'AFL_USERS_INCLUDE_IGNORED'		=> 'L’utilisateur %s à été ignoré car il n’existe pas ou est déjà fondateur.',
	'AFL_USERS_UNEXCLUDE'			=> 'Supprimer des utilisateurs considérés comme fondateurs',
	'AFL_USERS_UNEXCLUDE_EXPLAIN'	=> 'Vous pouvez sélectionner un ou des utilisateurs qui ne seront plus considérés comme des membres fondateurs.
										<br /><em>Utilisez ctrl+clic pour sélectionner plus d’un membre.</em>',
	'AFL_NO_EXCLUDE_USERS'			=> 'Aucun utilisateur à ne plus considérer comme fondateur.',
	'AFL_GROUPS_INCLUDED'			=> 'Celà inclus aussi les membres de(s) groupe(s) considéré(s) comme fondateurs.',
	'AFL_MAXLOGIN_BYPASS'			=> 'Nombre de connexions personalisées',
	'AFL_MAXLOGIN_BYPASS_EXPLAIN'	=> 'Activer ce paramètre vous permettras de saisir votre propre limite minimum de connexions échoués totalement indépendante du paramètre <em>“Nombre maximal de tentatives de connexion par nom d’utilisateur”</em>
										<br />Une fois ce paramètre activé choisissez ci dessous le nombre de connexions en échecs requis avant le déclenchement des alertes.',
	'AFL_LOGIN_COUNT'				=> 'Nombre de connexions échouées minimum',
	'AFL_LOGIN_COUNT_TRY'			=> ' essais',
	'AFL_LOGIN_COUNT_EXPLAIN'		=> 'Choissisez le nombre de connexions échouées minimum requis avant le déclenchement des alertes.
										<br />Requiert que le paramètre ci-dessus sois activé',
	'AFL_ACP_LDAP'					=> 'Fonctionalité non gérée par l’authentification via LDAP',
	'AFL_ACP_LEGEND'				=> 'Légende:',
	'AFL_ACP_LEGEND1'				=> 'Mail seulement',
	'AFL_ACP_LEGEND2'				=> 'Message privé et mail',
	'AFL_HOOK_LOGIN'				=> 'Ajouter un tag de connexion sécurisée',
	'AFL_HOOK_LOGIN_EXPLAIN'		=> 'Cela va ajouter un tag sécurisé, qui devrait décourager les pirates de compromettre les comptes de vos utilisateurs
										<br />%sVoir un exemple%s?',
	'AFL_MAIL_PWD'					=> 'Afficher le mot de passe tenté',
	'AFL_MAIL_PWD_EXPLAIN'			=> 'Celà donneras la possibilité à l’utilisateur "victime" des échecs <strong>(et lui seul)</strong> de voir le mot de passe qui à été tenté.<br /><em>Pour des raisons de sécurité le mot de passe tenté ne seras affiché que lors des envois par mail !!</em>',
	'AFL_MOD_ENABLED'				=> 'Activer les alertes de connexions',
	'AFL_MOD_ENABLED_EXPLAIN'		=> 'Activer les alertes de connexions? Notez que si il est désactivé les messages privés déjà envoyés ne seront pas supprimés!',
	'AFL_UCP_FAIL'					=> 'Alerte de connexions échouées',
	'AFL_UCP_FAIL_F'				=> 'Alerter fondateur sur les connexions échouées',
	'AFL_UCP_SETTINGS'				=> 'Forcer l’envois des alertes par mail/MP',
	'AFL_UCP_SETTINGS_EXPLAIN'		=> 'Si ce paramètre est activé, les alertes seront envoyées, même si l’utilisateur à désactivé cette option dans son panneau d’utilisateur. De plus celà bloquera le paramètre "Alertes de connexions échouées" dans le panneau de l’utilisateur',
	'AFL_UCP_SETTINGS_SUCC'			=> 'Forcer l’envois des alertes',
	'AFL_UCP_SETTINGS_SUCC_EXPLAIN'	=> 'Si ce paramètre est activé, les alertes seront envoyées, même si l’utilisateur à désactivé cette option dans son panneau d’utilisateur. De plus celà bloquera le paramètre "Alertes de connexions réussies" dans le panneau de l’utilisateur',
	'AFL_UCP_FORCE_ALL'				=> '<em>Mettre également à jour les paramètres pour tout les utilisateurs déjà enregistrés.</em><br /><strong>Note:</strong>Cela modifieras les paramètres personnels de tout les utilisateurs en fonction du paramètre selectionné ci-dessus.',
	'AFL_UCP_FAIL_F_EXPLAIN'		=> 'Envoyer également une alerte à tout les fondateurs lors de connexions échouées aux comptes?',
	'AFL_UCP_FAIL_EXPLAIN'			=> 'Activer l’alerte de connexion lors de multiples connexions en echecs répétées au comptes des utilisateurs?',
	'AFL_UCP_SUCCESS'				=> 'Alerte de connexions réussie',
	'AFL_UCP_SUCCESS_CHECKB'		=> '<em>Mettre également à jour les paramètres pour tout les utilisateurs déjà enregistrés.</em><br /><strong>Note:</strong>Cela modifieras les paramètres personnels de tout les utilisateurs en fonction du paramètre selectionné ci-dessus. ',
	'AFL_UCP_SUCCESS_EXPLAIN'		=> 'Activer l’information de connexion reussie lors d’une connexion reussie au compte.<br /><strong>Note:</strong><em> Ne devrait être utilisé seulement dans les forums ou la sécurité des comptes des utilisateurs est vraiment cruciale...Option non nécéssaire en temps normal.</em>',
	'AFL_ACP_FAIL'					=> 'Alerte de connexions échouées (Administration)',
	'AFL_ACP_FAIL_EXPLAIN'			=> 'Activer l’alerte de connexion lors de multiples connexions en echecs répétées à l’Administration?',
	'AFL_ACP_FAIL_F'				=> 'Alerter fondateur sur les connexions échouées (Administration)',
	'AFL_ACP_FAIL_F_EXPLAIN'		=> 'Envoyer également une alerte à tout les fondateurs lors de connexions échouées à l’Administration? <br />',
	'AFL_ACP_SUCCESS_F'				=> 'Alerter fondateur sur les connexions réussies (Administration)',
	'AFL_ACP_SUCCESS_F_EXPLAIN'		=> 'Envoyer également une alerte à tout les fondateurs lors de connexions réussies à l’Administration?',
	'AFL_MAIL_ALERT'				=> 'Activer les alertes par email',
	'AFL_MAIL_ALERT_EXPLAIN'		=> 'Activer l’envois des alerte par mail. <strong>Requiert que la fonction mail du forum sois activée!</strong> Vérifiez les <em>"Paramètres des e-mails"</em> pour celà.<br />Si ce paramètre est désactivé le MOD n’enverras <strong>AUCUN</strong> email !!!',
	'AFL_PM_ALERT'					=> 'Activer les alertes par MP ',
	'AFL_TOPIC_ALERT'				=> 'Enregistrer les traces de connexions avec un sujet',
	'AFL_TOPIC_ALERT_EXPLAIN'		=> 'Activer la création automatique d’un sujet qui contient les données basiques de la connexion? (UCP et ACP)',
	'AFL_TOPIC_FORUM'				=> 'Forum cible des traces de connexions',
	'AFL_TOPIC_ERROR'				=> 'Le forum de destination spécifié n’existe pas!',
	'AFL_TOPIC_FORUM_EXPLAIN'		=> 'Le forum ou le robot ci-dessous va poster un nouveau sujet si vous souhaitez garder une trace de la connexion.
										<br /><strong style="color: darkred;">Ce forum devrait être privé dans le sens ou il contient des informations sensibles sur la connexion !</strong>',
 	'AFL_TOPIC_LANGUAGE'			=> 'Langue du sujet',
 	'AFL_TOPIC_LANGUAGE_USER'		=> 'Langage de l’utilisateur cible',
 	'AFL_TOPIC_LANGUAGE_BOARD'		=> 'Langage par défaut du forum (<strong>%s</strong>)',
	'AFL_TOPIC_LOCK_EXPLAIN'		=> 'Une fois le sujet crée, il seras verrouillé immédiatemment.',
	'AFL_PM_ALERT_EXPLAIN'			=> 'Activer l’envois des alerte par MP. <strong>Requiert que la messagerie privée du forum sois activée!</strong> Vérifiez les paramètres de <em>" Messagerie privée"</em> pour celà.<br />Si ce paramètre est désactivé le MOD n’enverras <strong>AUCUN</strong> message privé !!!',
	'AFL_PM_SETTINGS'				=> 'Paramètres des messages privés',
	'AFL_TOPIC_SETTINGS'			=> 'Paramètres de sujet',
	'AFL_WHOIS_URL'					=> 'Url du WHOIS des IP',
	//Add your own whois link here following the comment example (don't forgot the variable {USER_IP} )
	//Don't forgot to use &amp; instead & in your url !
	'AFL_WHOIS_URLS'				=> array(
												'Utrace.de'			=> 'http://en.utrace.de/?query={USER_IP}',
												'GeoIP(Flagfox)'	=> 'http://geoip.flagfox.net/?ip={USER_IP}',
												'IP-Adress.com'		=> 'http://www.ip-adress.com/ip_tracer/{USER_IP}',
												'Network-tools'		=> 'http://network-tools.com/default.asp?prog=express&amp;host={USER_IP}'
											//	'your site'			=> 'http://yoursite.com{USER_IP}'
										),
	'AFL_TRIGGER_URL'				=> 'L’url du WHOIS des IP ne contenant pas la variable “<em>{USER_IP}</em>” celle-ci à donc été ré-intialisée par défault.',
	'AFL_WHOIS_URL_EXPLAIN'			=> 'Adresse Url du WHOIS qui seras utilisé pour les détails sur l’IP dans les MP et les EMAIL.<br />Url par defaut: <em>http://en.utrace.de/?query={USER_IP}</em><br />Utilisez la variable <strong>{USER_IP}</strong> pour l’IP qui seras utilisée dans les MP et les mails.',
	'AFL_BOT_ID'					=> 'Expéditeur du message privé',
	'AFL_BOT_ID_EXPLAIN'			=> 'Nom d’utilisateur qui seras utilisé pour l’envois des message privé',
	'AFL_POSTER_ID'					=> 'Auteur du sujet',
	'AFL_POSTER_ID_EXPLAIN'			=> 'Nom d’utilisateur qui seras utilisé pour la création du sujet',
	'AFL_POSTER_ID_OWNER'			=> 'Utiliser le nom d’utilisateur de la connexion à la place',
	'AFL_POSTER_ID_OWNER_WARN'		=> 'Il est recommandé de désactiver l’incrémentation du compteur de message dans le forum cible, sinon l’utilisateur va s’auto-incrémenter à chaque connexion réussies ou échouée!',
	'AFL_CONFIG_ERROR'				=> 'Attention les <em>"Paramètres des e-mails"</em> et de <em>"Messagerie privée"</em> sont désactivés, vous devriez configurer la création automatique de sujet sinon le MOD seras désactivé et aucune alerte ne seras faîte!!',
	'AFL_EMAIL_ERROR'				=> 'Option désactivée car les <em>"Paramètres des e-mails"</em> et/ou <em>"les alertes par email"</em> sont désactivés',
	'AFL_CONFIG_ERROR_TRIGGER'		=> 'Aucune méthode d’alerte n’est valide: MPs, E-mail, et sujets automatiques sont inactifs, le mod à été désactivé jusqu’à ce qu’une méthode d’alerte sois disponible!',
	'AFL_PM_ERROR'					=> 'Option désactivée car la <em>"Messagerie privée"</em> et/ou <em>"les alertes par MP"</em> sont désactivés',
	'AFL_PM_EMAIL_ERROR'			=> 'Option désactivée car la <em>"Messagerie privée"</em> et les <em>"Paramètres des e-mails"</em> sont désactivés',
	'AFL_FOUNDER_ERROR'				=> 'Attention, seuls les fondateurs peuvent modifier les réglages des alertes de connexions!',
	'AFL_SETTINGS_CUSTOMIZABLE'		=> 'Notez que l’utilisateur peux choisir d’être alerté par email ou par message privé.',
	'AFL_SETTINGS_FOUNDER'			=> 'Paramètres fondateur seulement',
	'AFL_SETTINGS_FOUNDER_EXPLAIN'	=> 'Reserver la configuration de ce mod aux fondateurs seulement.<br /> Si ce paramètre est réglé sur "non" tout personne ayant accès à l’administration pourras changer les paramètres des alertes de connexions.<br /><em>A l’exception de celui-ci qui seras réservé aux fondateurs.</em>',
	'AFL_FAIL_SETTINGS'				=> 'Paramètres de connexions échouées',
	'AFL_SUCCESS_SETTINGS'			=> 'Paramètres de connexions réussies',
	'AFL_ICON_PM'					=> 'Icône du message privé',
	'AFL_ICON_TOPIC'				=> 'Icône du sujet',
	'AFL_ICON_PM_NO'				=> 'Aucune icône',
	'AFL_ICON_PM_EXPLAIN'			=> 'Choisissez l’icone qui seras utilisé pour le message privé',
	'AFL_ICON_TOPIC_EXPLAIN'		=> 'Choisissez l’icone qui seras utilisé pour le sujet',
	'AFL_STATS_ENABLE'				=> 'Statistiques des alertes de connexions',
	'AFL_STATS_RESET'				=> 'Réinitialiser toutes les statistiques à zéro.',
	'AFL_STATS_ENABLE_EXPLAIN'		=> 'Activer les statistiques des alertes de connexions comme le nombres de connexions échouées, réussies, le nombre de mails ou de messages privés envoyés etc... <br /><em>Option non conseillée sur les forums ayant un trafic très important.</em>',
//ACP Javascript alert popup key
//We use \n for back line because only G.Chrome understand <br /> in Javascript popup functions (like "alert", "confirm" and other)
	'AFL_STATS_RESET_POPUP'			=> 'Les statistiques vont être remis à zéro, action irréverssible! \n Êtes-vous vraiment sûr de vouloir continuer?',
	'AFL_UCP_SUCCESS_POPUP'			=> 'Attention cette option peux très vite agacer les utilisateurs si la connexion automatique est désactivée.\n Êtes-vous sur de vouloir continuer?.',
	'AFL_UCP_FAIL_POPUP'			=> 'Attention cette option est fortement déconseillée pour les forums comportant de nombreux utilisateurs! \n Vous risquez potentiellement de voir les boîtes mail personelles des fondateurs submergées de mails d’alerte!! \n Êtes-vous vraiment sûr de vouloir activer cette option?',
	'AFL_UCP_FORCE_ALL_POPUP'		=> 'Vous êtes sur le point d’affecter le paramètre utilisateur “Alertes de connexions échouées” de tout les utilisateurs du forum.\n Êtes-vous sur de vouloir continuer?',
	'AFL_UCP_FORCE_ALL_SUCCESS_POPUP'=> 'Vous êtes sur le point d’affecter le paramètre utilisateur “Alertes de connexions réussies” de tout les utilisateurs du forum.\n Êtes-vous sur de vouloir continuer?',

//Version Check based on the Sylver35's Stuff (http://breizh-portal.com)
	'AFL_ACP_UPDATE_CHECK'			=> '<a href="http://breizh-portal.com" title="ACP Update Check by Sylver35">ACP Update Check</a>',//Deserved backlink for Sylver35
	'AFL_ERRORS_CONFIG_ALT'			=> 'Configuration des alertes de connexions',
	'AFL_ERRORS_CONFIG_EXPLAIN'		=> 'Sur cette page, vous pouvez vérifier si votre version de ce Mod est bien à jour ou dans le cas contraire, les actions à effectuer pour le mettre à jour.<br />Vous pouvez également régler des points simple de configuration qui s’y rapportent.',
	'ACP_ERRORS'					=> 'Erreurs',
	'ACP_UNINSTALL_FILE'				=> '» %sFichier de désinstallation UMIL%s',
	'AFL_UNABLE_CONNECT'    			=> 'Impossible de récupérer la version du mod depuis le serveur, message d’erreur: %s',
	'AFL_CURRENT_VERSION'				=> 'Version actuelle',
	'AFL_LATEST_VERSION'				=> 'Dernière version',
	'AFL_NEW_VERSION'					=> 'Votre version des alertes de connexions n’est pas à jour. Votre version est la %1$s, la version la plus récente est la %2$s. Lisez la suite pour plus d’informations',
	'AFL_ERRORS_NO_VERSION'				=> '<span style="color: red">La version du serveur n’a pas pu être contactée...</span>',
	'AFL_ERRORS_VERSION_COPY'			=> '<a href="%1$s" title="Mod Alert For Login">Mod Alert For Login v%2$s</a> &copy; 2012 <a href="http://geolim4.com" title="geolim4.com"><em>Geolim4.com</em></a>',
	'AFL_ERRORS_VERSION_CHECK'			=> 'Vérificateur de Version du Mod Alert For Login',
	'AFL_ERRORS_VERSION_CHECK_EXPLAIN'	=> 'Vérifie si la version des alertes de connexions que vous utilisez en ce moment est à jour.',
	'AFL_ERRORS_VERSION_NOT_UP_TO_DATE'	=> 'Votre version des alertes de connexions n’est pas à jour :`(<br />Ci-dessous vous trouverez un lien vers l’annonce de sortie de la version la plus récente ainsi que des instructions sur la façon d’effectuer la mise à jour.',
	'AFL_ERRORS_VERSION_UP_TO_DATE'		=> 'Votre installation est à jour :)',
	'AFL_ERRORS_INSTRUCTIONS'			=> '<br /><h1>Utilisation des alertes de connexions v%1$s</h1><br />
										<p>L’équipe de Geolim4.com vous remercie de votre confiance et espère que vous apprécierez les fonctionalités de ce Mod.<br />
										Rendez-vous <strong><a href="%2$s" title="Mod Alert For Login">sur cette page</a></strong></p>
										<p>Pour toute demande de support, rendez vous dans le <strong><a href="%3$s" title="forum de support">forum de support</a></strong></p>
										<p>Visitez également le Traqueur <strong><a href="%4$s" title="Traqueur du Mod Alert For Login">sur cette page</a></strong>. Tenez vous informés des éventuels bugs, ajouts ou demandes de fonctionnalités, la sécurité...</p>',
	'AFL_ERRORS_UPDATE_INSTRUCTIONS'	=> '
		<h1>Annonce de sortie</h1>
		<p>Veuillez lire <a href="%1$s" title="%1$s"><strong>le sujet de la version la plus récente</strong></a> pour acceder au processus de mise à jour, il peut contenir des informations utiles. Il inclueras également le lien de téléchargement ainsi que le journal des modifications.</p>
		<br />
		<h1>Comment mettre à jour votre installation des alertes de connexions</h1>
		<p>► Téléchargez la dernière version.</p>
		<p>► Décompressez l’archive et ouvrez le fichier install.xml, il contient toutes les informations de mise à jour.</p>
		<p>► Annonce officielle de la dernière version: (%2$s)</p>',
//Stuff based on the log connection by Elglobo....
	'AFL_MANAGE_IP'					=> 'Gestion des adresses IPs',
	'AFL_NO_EXCLUDE_IP'				=> 'Aucune adresse IP exclue',
	'AFL_INCLUDE_IP'				=> 'Inclure mon IP actuelle',
	'AFL_INCLUDE_ME'				=> 'M’ajouter',
	'AFL_EXCLUSION_IP'				=> 'Exclure des adresses IPs (tout type de connexions)',
	'AFL_ALREADY_IP'				=> 'L’IP <strong>%1$s</strong> n’a pu être ajouté car elle est déjà exclue.',
	'AFL_EXCLUSION_IP_EXPLAIN'		=> 'Pour spécifier plusieurs adresses IPs, entrez chacune d’elles sur une nouvelle ligne.',
	'AFL_UNEXCLUSION_IP'				=> 'Ré-inclure des adresses IPs',
	'AFL_UNEXCLUSION_IP_EXPLAIN'		=> 'Vous pouvez ré-inclure plusieurs adresses IPs d’un coup en utilisant la combinaison de touches appropriée avec votre clavier et votre souris.',
	'AFL_EXCLUDE_NO_IP'					=> 'L’IP <strong>%1$s</strong> n’a pas été correctement saisie',
	//QTE addon
	'AFL_ADDON_QTE'					=> 'L’attribut %s à été selectionné',
	'AFL_ADDON_QTE_NO'				=> 'Aucun attribut sélectionné',
));

?>