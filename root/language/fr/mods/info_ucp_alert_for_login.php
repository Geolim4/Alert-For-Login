<?php
/**
*
* info_ucp_alert_on_login.php [Français]
* @package ucp info_ucp_alert_for_login
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
//User Alert (UCP)
$lang = array_merge($lang, array(
//UCP prefs module language
	'AFL_FIELDSET_TITLE'		=> 'Alerte de connexions',
	'AFL_HOOK_ERROR'			=> 'La fonction <strong>__autoload</strong> existe déjà, impossible de continuer.',
	'AFL_HOOK_SECURED'			=> 'Connexion sécurisée par <em>%s %s %s</em>',
	'AFL_NOTIFY_LOGIN' 			=> 'Alertes de connexions échouées',
	'AFL_NOTIFY_LOGIN_EXPLAIN' 	=> 'M’alerter par mail et/ou message privé lors de connexions échouées à mon compte.',
	'AFL_SUCCESS'				=> 'Alertes de connexions réussies.',
	'AFL_SUCCESS_EXPLAIN'		=> 'Vous permet de recevoir également un mail lors de connexions réussies à votre compte.',
	'AFL_SUCCESS_EXPLAIN_ACP'	=> 'Vous permet de recevoir également un mail lors de connexions réussies à votre compte ou à votre Administration.(Requiert que ce paramètre sois également activé dans votre administration).<br /><em>Notez que celà ne désactive pas les alertes de connexions réussies à l’ACP d’autres Administrateurs si le paramètre ACP "Alerter fondateur sur les connexions réussies (Administration)" est activé.</em>',
	'AFL_SETTING_FORCED'		=> '<span style="color:red">Option désactivée, raison: Paramètre écrasé par la configuration Administrative</span>',
	'AFL_ALERT_TYPE'			=> 'Type d’alerte',
	'AFL_ALERT_TYPE_EXPLAIN'	=> 'Préférer l’envoi d’alerte par Email pour éviter la suppression du MP si un accès non autorisé est effectué à votre compte.',
	'AFL_ALERT_TYPE_ACP'		=> '<br />Cette fonctionalité concerne tout les types de connexions! Les connexions réussies/échouées des autres comptes sont également incluses.',
	'AFL_ALERT_TYPE_PM'			=> 'Message privé',
	'AFL_ALERT_TYPE_MAIL'		=> 'Email(recommandé)',
//Email
	'AFL_EMAIL_PWD'				=> 'Mot de passe tenté: %1$s',

/**
*Okey now all these key is for automatized private message, so i need to use bbcode and not HTML...
*HTML is very dangerous and we need to use the phpbb's system with bbcode...
*We've used generate_text_for_display & generate_text_for_storage for use it correctly in our function afl_pm, remember it!!
*/
	'AFL_PM_SUCCESS_LOGIN_TITLE'	=> 'Alerte de connexion réussie',
	'AFL_PM_SUCCESS_LOGIN_'			=> '[size=130]Bonjour %1$s,

Ce message privé est destiné à vous informer qu’une connexion reussie a été effectuée à votre compte à partir de l’adresse IP %2$s[/size]

[b]######[/b][u]Informations supplémentaires sur cette connexion[/u][b]######[/b]
[list][*]Heure(locale): [i]%3$s[/i]
[*]Détails sur cette IP: [url=%4$s]%4$s[/url]
[*]L’agent HTTP utilisé était: [i]%5$s[/i][/list]
######################################################',

	'AFLF_WARNING_ATTEMPT'					=> 'Alert on fail login',
	'AFLF_WARNING_LOGIN_ATTEMPT_PM'			=> 'Attention quelqu’un tente de se connecter à votre compte!!',
	'AFLF_WARNING_LOGIN_ATTEMPT_PM_TEXT'	=> 'Bonjour [i]%1$s[/i],
Votre compte est peut-être compromis car un utilisateur avec l’IP [b]%2$s[/b]
essaye de se connecter à votre compte! Soyez prudent!

Nous vous recommandons de changer votre mot de passe ou de vérifier que votre mot de passe actuel est suffisemment fort!!
Vous pouvez également alerter un administrateur à l’adresse mail suivante: [b] %3$s [/b]
Il prendras les mesures nécéssaires pour protéger votre compte...

######Informations supplémentaires sur ces tentatives######
Nombre actuel de tentative de connexions en echecs sur votre compte: [i]%4$s[/i]
Détails sur cette IP: [url=%5$s]%5$s[/url]
L’agent HTTP utilisé était: [i]%6$s[/i]
###########################################################',

//Founder Alert (ACP)
	'AFLF_FOUNDER_ALERT_PM'			=> 'Attention, activité douteuse sur un compte!!',
	'AFLF_FOUNDER_ALERT_PM_TEXT'	=> 'Bonjour [i]%1$s[/i],
Le compte de [b]%2$s[/b] est peut-être compromis car un utilisateur avec l’IP [b]%3$s[/b]
essaye de s’y connecter de façon douteuse! Soyez vigilant!

Nous vous recommandons de changer son mot de passe ou de vérifier que les dernières IP de l’utilisateur, correspondent bien à celle qui essaye de se connecter actuellement!!
Vous pouvez également contacter le membre par MP pour constater la situation avec lui.

######Informations supplémentaires sur ces tentatives######
Nombre actuel de tentative de connexions en echecs sur ce compte: [i]%4$s[/i]
Détails sur cette IP:[url=%5$s]%5$s[/url]
L’agent HTTP utilisé était: [i]%6$s[/i]
Heure(locale): [i]%7$s[/i]
###########################################################',

//Founder Alert FAIL (ACP)
	'AFLF_ACP_FOUNDER_ALERT_PM'			=> 'Connexions échouées à votre Administration',
	'AFLF_ACP_FOUNDER_PM_TEXT'			=> 'Bonjour [i]%1$s[/i],
Des connections échouées à votre administration à partir du compte de [b]%2$s[/b] ont été enregistrées avec l’IP [b]%3$s[/b] qui
essaye de s’y connecter de façon douteuse! Soyez vigilant!

Nous vous recommandons de vérifier l’intégrité de votre ACP ainsi que de vérifier que l’IP de cet Administrateur correspond bien à ses précédentes IP utilisées!!
Vous pouvez également contacter le membre par MP pour constater la situation avec lui.

######Informations supplémentaires sur ces tentatives######
Heure(locale): [i]%4$s[/i]
Détails sur cette IP: [url=%5$s]%5$s[/url]</a>
L’agent HTTP utilisé était: [i]%6$s[/i]
###########################################################',

//Founder Alert SUCCESS (ACP)
	'AFLF_ACP_FOUNDER_ALERT_SUCCESS'			=> 'Connexions réussie à votre Administration',
	'AFLF_ACP_FOUNDER_SUCCESS_TEXT'			=> 'Bonjour [i]%1$s[/i],
Une connexion réussie à votre administration a eu lieu à partir du compte de [b]%2$s[/b] avec l’IP [b]%3$s[/b].

######Informations supplémentaires sur cette connexion######
Heure(locale): [i]%4$s[/i]
Détails sur cette IP: [url=%5$s]%5$s[/url]</a>
L’agent HTTP utilisé était: [i]%6$s[/i]
###########################################################',

//User Alert SUCCESS (UCP)
	'AFL_UCP_USER_ALERT_SUCCESS'			=> 'Connexions réussie à votre compte',
	'AFL_UCP_USER_SUCCESS_TEXT'			=> 'Bonjour [i]%1$s[/i],
Une connexion réussie à votre compte a eu lieu avec l’IP [b]%2$s[/b].

######Informations supplémentaires sur cette connexion######
Heure(locale): [i]%3$s[/i]
Détails sur cette IP: [url=%4$s]%4$s[/url]</a>
L’agent HTTP utilisé était: [i]%5$s[/i]
###########################################################',

//Founder Alert SUCCESS (ACP)
	'AFLF_ACP_USER_ALERT_SUCCESS'			=> 'Connexions réussie à votre Administration',
	'AFLF_ACP_USER_SUCCESS_TEXT'			=> 'Bonjour [i]%1$s[/i],
Une connexion réussie à votre administration a eu lieu à partir de votre compte avec l’IP [b]%2$s[/b].

######Informations supplémentaires sur cette connexion######
Heure(locale): [i]%3$s[/i]
Détails sur cette IP: [url=%4$s]%4$s[/url]</a>
L’agent HTTP utilisé était: [i]%5$s[/i]
###########################################################',

//User Alert (ACP)
	'AFLF_ACP_USER_ALERT_PM'		=> 'Connections échouées à votre Administration',
	'AFLF_ACP_USER_PM_TEXT'			=> 'Bonjour [i]%1$s[/i],
Des connections échouées à votre administration depuis votre compte, ont été enregistrées avec l’IP [b]%2$s[/b] qui
essaye de s’y connecter de façon douteuse! Soyez vigilant!

Nous vous recommandons de vérifier l’intégrité de votre ACP ainsi que de vérifier que cette IP correspond bien à vos précédentes IP utilisées!!

######Informations supplémentaires sur ces tentatives######
Heure(locale): [i]%3$s[/i]
Détails sur cette IP: [url=%4$s]%4$s[/url]
L’agent HTTP utilisé était: [i]%5$s[/i]
###########################################################',

//Automatic Forum Posting
	//////////////////////////////
	//UCP
	//////////////////////////////

	'AFL_POST_LOGIN_FAIL_TITLE'		=> 'Connexion de compte échouée [%1$s tentative(s)]',
	'AFL_POST_LOGIN_FAIL'			=> '[size=130][b]Rapport de connexion:[/b][/size]

[size=110][color=red]Connexion échouée au compte de [b]%1$s[/b] depuis l’IP [b]%2$s[/b].[/color][/size]

Nous vous recommandons de changer son mot de passe si l’IP de cet utilisateur ne correspond pas à ses précédentes IP utilisées!
Vous pouvez également le [url=%3$s]contacter[/url] par MP pour constater la situation avec lui.

[b]######[/b][u]Informations supplémentaires sur ces tentatives[/u][b]######[/b]
[list][*]Heure(locale): [i]%4$s[/i]
[*]Nombre actuel de tentative de connexions en echecs sur ce compte: [i]%5$s[/i]
[*]Détails sur cette IP: [url=%6$s]%6$s[/url]
[*]L’agent HTTP utilisé était: [i]%7$s[/i][/list]
######################################################',

	'AFL_POST_LOGIN_SUCCESS_TITLE'	=> 'Connexion de compte réussie',
	'AFL_POST_LOGIN_SUCCESS'		=> '[size=130][b]Rapport de connexion:[/b][/size]

[size=110][color=green]Connexion réussie au compte de [b]%1$s[/b] depuis l’IP [b]%2$s[/b].[/color][/size]


[b]######[/b][u]Informations supplémentaires sur cette connexion[/u][b]######[/b]
[list][*]Heure(locale): [i]%3$s[/i]
[*]Détails sur cette IP: [url=%4$s]%4$s[/url]
[*]L’agent HTTP utilisé était: [i]%5$s[/i][/list]
######################################################',
	//////////////////////////////
	//ACP
	//////////////////////////////

	'AFL_POST_LOGIN_FAIL_ACP_TITLE'		=> 'Connexion échouée à l’Administration',
	'AFL_POST_LOGIN_FAIL_ACP'			=> '[size=130][b]Rapport de connexion:[/b][/size]

[size=110][color=red]Connexion échouée à l’administration depuis le compte de [b]%1$s[/b] avec l’IP [b]%2$s[/b].[/color][/size]

Il est recommandé de vérifier l’intégrité de l’Administration si l’IP de cet Administrateur ne correspond pas à ses précédentes IP utilisées!
Vous pouvez également le [url=%3$s]contacter[/url] par MP pour constater la situation avec lui.

[b]######[/b][u]Informations supplémentaires sur ces tentatives[/u][b]######[/b]
[list][*]Heure(locale): [i]%4$s[/i]
[*]Détails sur cette IP: [url=%5$s]%5$s[/url]</a>
[*]L’agent HTTP utilisé était: [i]%6$s[/i][/list]
######################################################',

	'AFL_POST_LOGIN_SUCCESS_ACP_TITLE'	=> 'Connexion réussie à l’Administration',
	'AFL_POST_LOGIN_SUCCESS_ACP'		=> '[size=130][b]Rapport de connexion:[/b][/size]

[size=110][color=green]Connexion réussie à l’administration depuis le compte de [b]%1$s[/b] avec l’IP [b]%2$s[/b].[/color][/size]


[b]######[/b][u]Informations supplémentaires sur cette connexion[/u][b]######[/b]
[list][*]Heure(locale): [i]%3$s[/i]
[*]Détails sur cette IP: [url=%4$s]%4$s[/url]
[*]L’agent HTTP utilisé était: [i]%5$s[/i][/list]
######################################################',
));

?>