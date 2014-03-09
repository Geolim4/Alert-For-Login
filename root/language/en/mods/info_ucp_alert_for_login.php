<?php
/**
*
* info_ucp_alert_on_login.php [English]
* @package ucp info_ucp_alert_for_login
* @version $Id: acp_alert_for_login.php v1.3.1 00:21 05/1/2013 Geolim4 Exp $
* @copyright (c) 2012, 2013 Geolim4.com http://geolim4.com
* @copyright (c) 2005 phpBB Group
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
// 'Page %s of %s' you can (and should) write 'Page %1$s of %2$s[EN]', this allows
// translators to re-order the output of data while ensuring it remains correct
//
// You do not need this where single placeholders are used, e.g. 'Message %d' is fine
// equally where a string contains only two placeholders which are used to wrap text
// in a url you again do not need to specify an order e.g., 'Click %sHERE%s' is fine
// Some characters you may want to copy&paste:
// ’ » “ ” …
// Use: <strong style="color:green">Texte</strong>[EN]',
//For add Color
//
//User Alert (UCP)
$lang = array_merge($lang, array(
//UCP prefs module language
	'AFL_FIELDSET_TITLE'		=> 'Alert for login',
	'AFL_HOOK_ERROR'			=> 'The <strong>__autoload</strong>’s function already exist, cannot continue.',
	'AFL_HOOK_SECURED'			=> 'Login secured using <em>%s %s %s</em>',
	'AFL_NOTIFY_LOGIN' 			=> 'Logins failed alert',
	'AFL_NOTIFY_LOGIN_EXPLAIN' 	=> 'Alert me by PM and/or Mail when failed logins happen to my account.',
	'AFL_SUCCESS'				=> 'Logins success alert',
	'AFL_SUCCESS_EXPLAIN'		=> 'Alert me by PM and/or Mail when success logins happen to my account.',
	'AFL_SUCCESS_EXPLAIN_ACP'	=> 'Alert me by PM and/or Mail when success logins happen to my account or my ACP.(Require this setting is also enabled in your ACP).<br /><em>Note: That didn\'t disable success logins to ACP from other Admin if the ACP setting "Alert founder(s) for success logins (ACP)" is enabled.</em>',
	'AFL_SETTING_FORCED'		=> '<span style="color:red">Option disabled, reason: Setting forced by the administration settings</span>',
	'AFL_ALERT_TYPE'			=> 'Alert type',
	'AFL_ALERT_TYPE_EXPLAIN'	=> 'Prefer use of email alert for avoids MP’s deleting if an unauthorized access is done to your account.',
	'AFL_ALERT_TYPE_ACP'		=> '<br />This feature concern all login type! Failed/succes login from an other account included.',
	'AFL_ALERT_TYPE_PM'			=> 'Private message',
	'AFL_ALERT_TYPE_MAIL'		=> 'Mail(recomended)',
//Email
	'AFL_EMAIL_PWD'				=> 'Attempted password: %1$s',
/**
*Okey now all these key is for automatized private message, so i need to use bbcode and not HTML...
*HTML is very dangerous and we need to use the phpbb's system with bbcode...
*We've used generate_text_for_display & generate_text_for_storage for use it correctly in our function afl_pm, remember it!!
*/
	'AFL_PM_SUCCESS_LOGIN_TITLE'	=> 'Alert on success login',
	'AFL_PM_SUCCESS_LOGIN_'			=> '[size=130]Hello %1$s,

This private message is to inform you that there was a successful login to your account here with the IP %2$s.[/size]

[b]######[/b][u]More information about this login[/u][b]######[/b]
[list][*]Time(local): [i]%3$s[/i]
[*]IP Detail: [url=%4$s]%4$s[/url]
[*]HTTP user agent: [i]%5$s[/i][/list]
######################################################',

	'AFLF_WARNING_ATTEMPT'					=> 'Alert on fail login',
	'AFLF_WARNING_LOGIN_ATTEMPT_PM'			=> 'Warning someone is trying to connect to your account!!',
	'AFLF_WARNING_LOGIN_ATTEMPT_PM_TEXT'	=> 'Hello [i]%1$s[/i],
Your account is maybe compromised because an user with the IP [b]%2$s[/b]
try to connect to your account! Be carefull!

We recommand you to change your password or check if your current password is enought strong!!
You can also alert an administrator with the follow email adress: [b] %3$s [/b]
It’ll take appropriate measure for protect your account...

######More information about these attempt:######
Current count of failed login on your account: [i]%4$s[/i]
Detail about that IP: [url=%5$s]%5$s[/url]
HTTP user agent used was: [i]%6$s[/i]
Time(local): [i]%7$s[/i]
###########################################################[EN]',

//Founder Alert (ACP)
	'AFLF_FOUNDER_ALERT_PM'			=> 'Account maybe compromised !!!!',
	'AFLF_FOUNDER_ALERT_PM_TEXT'	=> 'Hello [i]%1$s[/i],
The [b]%2$s[/b]’s account may be compromised because an user with the [b]%3$s[/b]’s IP
try to connect dubiously! Be carrefull!

We recommand you to change his password or check if his last IP used match with the current ip whith try to connect actually!!
You can also contact the user by PM for see the situation with him.

######More information about these attempt:######
Current count of failed login on this account: [i]%4$s[/i]
Detail about that IP: [url=%5$s]%5$s[/url]
HTTP user agent used was: [i]%6$s[/i]
Time(local): [i]%7$s[/i]
###########################################################',

//Founder Alert (ACP)
	'AFLF_ACP_FOUNDER_ALERT_PM'		=> 'Failed logins to your administration',
	'AFLF_ACP_FOUNDER_PM_TEXT'		=> 'Hello [i]%1$s[/i],
We’ve recorded some failled login to your Administration from the [b]%2$s[/b]’s account with the ip [b]%3$s[/b] whith
try to connect dubiously! Be carrefull!

We recommand you to check your ACP integrity and check also if this administrator’s IP match with them previous used IP!!
You can also contact the user by PM for see the situation with him.

######More information about these attempt:######
Time(local): [i]%4$s[/i]
Detail about that IP: [url=%5$s]%5$s[/url]</a>
HTTP user agent used was: [i]%6$s[/i]
###########################################################',

//Founder Alert SUCCESS (ACP)
	'AFLF_ACP_FOUNDER_ALERT_SUCCESS'			=> 'Success logins to your administration',
	'AFLF_ACP_FOUNDER_SUCCESS_TEXT'			=> 'Hello [i]%1$s[/i],
A successful login has been done to your Administration Panel from the [b]%2$s[/b]’s account with the IP [b]%3$s[/b].

######More information about this login######
Time(local): [i]%4$s[/i]
Detail about that IP: [url=%5$s]%5$s[/url]</a>
HTTP user agent used was: [i]%6$s[/i]
###########################################################',

//Founder Alert SUCCESS (ACP)
	'AFLF_ACP_USER_ALERT_SUCCESS'			=> 'Success logins to your administration',
	'AFLF_ACP_USER_SUCCESS_TEXT'			=> 'Hello [i]%1$s[/i],
A successful login has been done to your Administration Panel from your account with the IP [b]%2$s[/b].

######More information about this login######
Time(local): [i]%3$s[/i]
Detail about that IP: [url=%4$s]%4$s[/url]</a>
HTTP user agent used was: [i]%5$s[/i]
###########################################################',

//User Alert SUCCESS (UCP)
	'AFL_UCP_USER_ALERT_SUCCESS'			=> 'Success logins to your account',
	'AFL_UCP_USER_SUCCESS_TEXT'			=> 'Hello [i]%1$s[/i],
A successful login has been done to your account with the IP [b]%2$s[/b].

######More information about this login######
Time(local): [i]%3$s[/i]
Detail about that IP: [url=%4$s]%4$s[/url]</a>
HTTP user agent used was: [i]%5$s[/i]
###########################################################',

//User Alert (ACP)
	'AFLF_ACP_USER_ALERT_PM'	=> 'Failed logins to your Administration',
	'AFLF_ACP_USER_PM_TEXT'		=> 'Hello [i]%1$s[/i],
We’ve recorded some failled login to your Administration from your account with the ip [b]%2$s[/b] which
try to connect dubiously! Be carrefull!

We recommand you to check your ACP integrity and check also if this IP match with your previous used IP!!

######More information about this login:######
Time(local): [i]%3$s[/i]
Detail about that IP: [url=%4$s]%4$s[/url]</a>
HTTP user agent used was: [i]%5$s[/i]
###########################################################',

//Automatic Forum Posting
	//////////////////////////////
	//UCP
	//////////////////////////////

	'AFL_POST_LOGIN_FAIL_TITLE'		=> 'Login attempt failed [%1$s attempt(s)]',
	'AFL_POST_LOGIN_FAIL'			=> '[size=130][b]Login report:[/b][/size]

[size=110][color=red]Login failed to the [b]%1$s[/b]’s account from the IP [b]%2$s[/b].[/color][/size]

We recommend you to check your board’s integrity and check if this Administator’s IP match with his previous IP!!
You can also [url=%3$s]contact[/url] by PM to clarify the situation.

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