<?php
/**
*
* info_acp_alert_on_login.php [English]
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
		'ACP_AFL_CONFIG_UMIL'					=> 'Logins alerts',
		'ACP_AFL_CONFIG_UMIL_PHP'				=> 'PHP version Information',
		'ACP_AFL_CONFIG_UMIL_PHP530_OK'			=> 'Great!! You have PHP <strong>5.3.0</strong> or higher, you can proceed to the mod install',
		'ACP_AFL_CONFIG_UMIL_PHP530_NO'			=> 'Sorry!! You don’t have PHP <strong>5.3.0</strong> or higher, you should not proceed to the mod install',
//Logs
		'ACP_AFL_LOG_ADDED'						=> 'Added log entry',
		'ACP_AFL_LOG_ALTERED'					=> '<strong>Altered logins alert’s settings</strong>',
		'ACP_AFL_LOG_UPDATE'					=> 'Updated Alert For Login’s MOD from <strong style="color: red;">%s</strong> to	 <strong style="color: green;">%s</strong>',
		'ACP_AFL_LOG_OFF'						=> '<strong>Login alert has been disabled because the installation is not completed.</strong><br />»For further information, jump on the login’s alert to see returned errors.',

//Mod Name
		'ACP_AFL_CONFIG'						=> 'Logins alerts',

//Mod error
		'AFL_INSTALL_NO_FILE'					=> 'The file<strong>“ %s ”</strong> is missing.',
		'AFL_INSTALL_NO_COLLUMN'				=> 'The SQL column <strong>“ %1$s ”</strong> from the <strong>“ %2$s ”</strong> table is missing.',
		'AFL_INSTALL_NO_TABLE'					=> 'The SQL table <strong>“ %1$s ”</strong> is missing.',
		'AFL_ERROR_INC_EXC'						=> 'You cannot select the <strong>same</strong> groups to exclude and considerate as founder!',
		'AFL_NO_JAVASCRIPT'						=> 'Administration of this MOD require Javascript for better performances, please enable it!',
		'AFL_FULL_OPTION'						=> 'You have enabled PM Alert, Mail Alert and Topic Alert, it is not recommanded to use all these feature in the same time, because that will result a lot of SQL query at each user’s logins.',
		'ACP_AFL_ERR_INSTALL'					=> 'The MOD is now disabled for security reasons until its installation is complete.',
		'ACP_AFL_ERR_NOCONST'					=> '<em>AFL_EXCLUDE_IPS_TABLE</em> Constant is missing... Check modifications in “/includes/constants.php” file',

//Mod statistic
		'AFL_STAT_TITLE'						=> '"Alerts For Logins" Statistics',
		'AFL_STAT_INFO'							=> 'Please note those "login" statistics are based only on "login" which using "Alert For Login" MOD ... Any login without "Alert For Login" will be ignored...',
		'AFL_STAT_MANY_USER'					=> 'Your board seem to have a lot of users, if you notice any slow downs on login pages, disable the statistic!',
		'AFL_STAT_FAIL'							=> 'Number of failed logins since the "Alert For Logins" install.',
		'AFL_STAT_SUCCESS'						=> 'Number of successful logins since the "Alert For Logins" install.',
		'AFL_STAT_FAIL_ACP'						=> 'Number of failed logins to the ACP since the "Alert For Logins" install.',
		'AFL_STAT_SUCCESS_ACP'					=> 'Number of successful logins to the ACP since the "Alert For Logins" install.',
		'AFL_STAT_PM'							=> 'Number of private message sent by "Alert For Logins"',
		'AFL_STAT_MAIL'							=> 'Number of emails sent by  "Alert For Logins"',
		'AFL_STAT_TOPIC'						=> 'Number of topics created by "Alert For Logins"',
		'AFL_INSTALL_DATE'						=> 'Install date of "Alert For Logins"',
		'AFL_UPDATE_DATE'						=> 'Update date of "Alert For Logins"',
		'AFL_RATIO_LOGIN'						=> 'Ratio of successful/failed logins',
		'AFL_STAT_USERS_SUCCESS'				=> 'Number of users which use "Alert On Succes Login".<br /> (Based on a list of %d users)',
		'AFL_STAT_USERS_FAIL'					=> 'Number of users which use "Alert On Failed Login".<br /> (Based on a list of %d users)',

//Mod Settings
		'AFL_ALERT_LIMIT'						=> 'Alert Limit (Failed logins only)',
		'AFL_ALERT_LIMIT_EXPLAIN'				=> 'Limit number of alerts which will be sent after multiple failed logins (0 for Disable)
													<br />Once this limit reached, no one will be longer alerted, founders included.',
		'AFL_FOUNDER_EXCLUDE'					=> 'Founders(s) excluded from alerts',
		'AFL_FOUNDER_EXCLUDE_EXPLAIN'			=> 'You can select one or more founder(s) which will be excluded from login alerts.
														<br />Note this setting only affects alerts received from accounts other than founders.
														<br /><em>Use ctrl+click for select more than one founder.</em>',
		'AFL_GROUPS_EXCLUDE'					=> 'Group(s) excluded from alerts',
		'AFL_GROUPS_EXCLUDE_EXPLAIN'			=> 'You can select one or more group(s) which will be excluded from login alerts.
														<br />Note this setting is based only on the <strong>default</strong> group of the user, and it also overwrite the <em>"Group(s) considerated as founders"</em> setting below.
														<br /><em>Use ctrl+click for select more than one groupe.</em>',
		'AFL_GROUPS_INCLUDE'					=> 'Group(s) considerated as founders',
		'AFL_GROUPS_INCLUDE_EXPLAIN'			=> 'You can select one or more group(s) which will be considerated as founder.
														<br />Note that this parameter is based on every group that the user is a member.
														<br /><em>Use ctrl+click for select more than one groupe.</em>',
		'AFL_USERS_INCLUDE_IGNORED'				=> 'The user %s has been ignored because he does not exist or is already a founder.',
		'AFL_USERS_INDIVIDUAL'					=> 'Individual(s) user(s) management',
		'AFL_USERS_INCLUDE'						=> 'Individual(s) user(s) to consider as founders',
		'AFL_USERS_INCLUDE_EXPLAIN'				=> 'You can select one or more user who will be considered as founder.
													<br />Note that real current founders will be ignored from this list.
													<br /><em>To specify several different users, enter each one on a new line.</em>',
		'AFL_USERS_UNEXCLUDE'					=> 'Remove individual(s) user(s) considered as founders',
		'AFL_USERS_UNEXCLUDE_EXPLAIN'			=> 'You can select one or more user who will be not longer considered as founder.
													<br /><em>Use ctrl+click for select more than one groupe.</em>',
		'AFL_NO_EXCLUDE_USERS'					=> 'Any user which will be not longer considered as founder',
		'AFL_GROUPS_INCLUDED'					=> 'This include also members of the group(s) considerated as founders.',
		'AFL_MAXLOGIN_BYPASS'					=> 'Customized login number',
		'AFL_MAXLOGIN_BYPASS_EXPLAIN'			=> 'Enable this setting will allow you to set your own maximum login attempt limit, fully independent of the setting <em>“Maximum number of login attempts per username”</em>
																				<br />Once this setting enabled, choice below the number of failed logins before the minimum required triggering alerts',
		'AFL_LOGIN_COUNT'						=> 'Minimum number of failed logins',
		'AFL_LOGIN_COUNT_TRY'					=> ' attempt',// to translators: keep this space !!!
		'AFL_LOGIN_COUNT_EXPLAIN'				=> 'Choose the number of failed logins before the minimum required triggering alerts.
														<br />Setting above is required!!',
		'AFL_ACP_LDAP'							=> 'Feature not supported by the LDAP authentication',
		'AFL_ACP_LEGEND'						=> 'Legend:',
		'AFL_ACP_LEGEND1'						=> 'Mail only',
		'AFL_ACP_LEGEND2'						=> 'Private messages and emails',
		'AFL_HOOK_LOGIN'						=> 'Add a trusted tag on the login',
		'AFL_HOOK_LOGIN_EXPLAIN'				=> 'This will add a trusted tag on the login, which should discourage hackers to attempt to your user’s accounts
													<br />%sSee an example%s?',
		'AFL_MAIL_PWD'							=> 'Show the failed password',
		'AFL_MAIL_PWD_EXPLAIN'					=> 'This will add the feature to the "victim" user of failed logins <strong>(and him only)</strong> to see the password which failed during a login.<br /><em>For security reasons, the failed password will be displayed only for email shipments !</em>',
		'AFL_MOD_ENABLED'						=> 'Enable "Alert For Logins" MOD',
		'AFL_MOD_ENABLED_EXPLAIN'				=> 'Enable "Alert For Logins" MOD ? Please note if it is disabled, the previously sent private messages will be not deleted!',
		'AFL_UCP_FAIL'							=> 'Alert of failed logins',
		'AFL_UCP_FAIL_F'						=> 'Alert founders on failed logins',
		'AFL_UCP_SETTINGS'						=> 'Force email/PM shipments',
		'AFL_UCP_SETTINGS_EXPLAIN'				=> 'If enabled, alerts will be send, even if the user has deactivated this setting in his user control panel. Also that will block the setting "Alert of failed logins" on the user control panel.',
		'AFL_UCP_SETTINGS_SUCC'					=> 'Force email shipments',
		'AFL_UCP_SETTINGS_SUCC_EXPLAIN'			=> 'If enabled, alerts will be sent, even if the user has deactivated this setting in his user control panel. Also that will block the setting "Alert of successful logins" on the user control panel.',
		'AFL_UCP_FORCE_ALL'						=> '<em>Also update the settings for all users already registered</em><br /><strong>Note:</strong>This will alter personal settings of all users according to the settings above.',
		'AFL_UCP_FAIL_F_EXPLAIN'				=> 'Also send an alert to all founders when failed to login to an account',
		'AFL_UCP_FAIL_EXPLAIN'					=> 'Enable alerts of login when multiple failed logins happened to user’s accounts?',
		'AFL_UCP_SUCCESS'						=> 'Alert of successful logins',
		'AFL_UCP_SUCCESS_CHECKB'				=> '<em>Also update the settings for all users already registered.</em><br /><strong>Note:</strong>This will alter personal settings of all users according the settings above.',
		'AFL_UCP_SUCCESS_EXPLAIN'				=> 'Enable alert on successful logins.<br /><strong>Note:</strong><em> Should only be used on boards where the security of user\'s account is very crucial. Otherwise this setting is useless.</em>',
		'AFL_ACP_FAIL'							=> 'Alert of failed login attempts(ACP)',
		'AFL_ACP_FAIL_EXPLAIN'					=> 'Enable alerte of failed login to the ACP?',
		'AFL_ACP_FAIL_F'						=> 'Alert founders on failed logins (ACP)',
		'AFL_ACP_FAIL_F_EXPLAIN'				=> 'Also send an alert to all founders when multiple failed logins to the ACP?<br />',
		'AFL_ACP_SUCCESS_F'						=> 'Alert founder(s) for successful logins (ACP)',
		'AFL_ACP_SUCCESS_F_EXPLAIN'				=> 'Also send an alert to all founders when a successful login occurs to the ACP?',
		'AFL_MAIL_ALERT'						=> 'Enable alert by email',
		'AFL_MAIL_ALERT_EXPLAIN'				=> 'Enable shipments by email. <strong>The email function of the board should be enabled in order to work!</strong> Check if the setting <em>"Enable board-wide e-mails"</em> is enabled.<br />If disabled, the MOD will send <strong>NO</strong> email !',
		'AFL_PM_ALERT'							=> 'Enable alert by PM',
		'AFL_TOPIC_ALERT'						=> 'Keep a trace of login under a topic',
		'AFL_TOPIC_ALERT_EXPLAIN'				=> 'Enable an automatic new topic posting which contains the basic data of this login? (UCP and ACP)',
		'AFL_TOPIC_FORUM'						=> 'Forum target for login’s trace',
		'AFL_TOPIC_ERROR'						=> 'The specified destination forum does not exist !',
		'AFL_TOPIC_FORUM_EXPLAIN'				=> 'The forum where the bot under this setting will post a new topic if you choice to keep a trace of login
												<br /><strong style="color: darkred;">This forum should be private because it’ll contains sensible informations of the login !</strong>',
		'AFL_TOPIC_LANGUAGE'					=> 'Topic’s language',
		'AFL_TOPIC_LANGUAGE_USER'				=> 'Target user language',
		'AFL_TOPIC_LANGUAGE_BOARD'				=> 'Default language of the board (<strong>%s</strong>)',
		'AFL_TOPIC_LOCK_EXPLAIN'				=> 'Once the topic is created it’ll be locked immédiatly.',
		'AFL_PM_ALERT_EXPLAIN'					=> 'Enable shipments by private message. <strong>Private messaging required on the board!</strong> Check the settings <em>"Private messaging"</em> for that.<br />If disabled, the MOD will send <strong>NO</strong> private message !!',
		'AFL_PM_SETTINGS'						=> 'Private messages settings',
		'AFL_TOPIC_SETTINGS'					=> 'Topic settings',
		'AFL_WHOIS_URL'							=> 'Url of the IP WHOIS ',
		//Add your own whois link here following the comment example (don't forgot the variable {USER_IP} )
		//Don't forgot to use &amp; instead & in your url !
		'AFL_WHOIS_URLS'						=> array(
														'Utrace.de'			=> 'http://en.utrace.de/?query={USER_IP}',
														'GeoIP(Flagfox)'	=> 'http://geoip.flagfox.net/?ip={USER_IP}',
														'IP-Adress.com'		=> 'http://www.ip-adress.com/ip_tracer/{USER_IP}',
														'Network-tools'		=> 'http://network-tools.com/default.asp?prog=express&amp;host={USER_IP}'
													//	'your site'			=> 'http://yoursite.com{USER_IP}'
												),
		'AFL_TRIGGER_URL'						=> 'Url of the IP WHOIS didn’t contain the var“<em>{USER_IP}</em>”, so this one was reset',
		'AFL_WHOIS_URL_EXPLAIN'					=> 'Url of the WHOIS which will be used for the IP details in the PM and EMAIL.<br />Default URL: <em>http://en.utrace.de/?query={USER_IP}</em><br />Use the variable <strong>{USER_IP}</strong> for the IP which will be used in the PM and EMAIL.',
		'AFL_BOT_ID'							=> 'Private message sender',
		'AFL_BOT_ID_EXPLAIN'					=> 'Username will be used for PM shipments',
		'AFL_POSTER_ID'							=> 'Topic author',
		'AFL_POSTER_ID_EXPLAIN'					=> 'Username will be used for creating topic',
		'AFL_POSTER_ID_OWNER'					=> 'Use the login username instead',
		'AFL_POSTER_ID_OWNER_WARN'				=> 'It is recommended to disable post incrementation in the target forum, otherwise the user will increment himself at each logins fail or success!',
		'AFL_CONFIG_ERROR'						=> 'Warning, the <em>"Enable board-wide e-mails"</em> and <em>"Private messaging"</em> settings are disabled, so you should configure the automatic forum posting or the MOD will be disabled too and no alert will be sent!',
		'AFL_CONFIG_ERROR_TRIGGER'				=> 'Any alert method are valid: PMs, Email, and automatic topics are inactive, the mod has been disabled untill a method of alert are available!',
		'AFL_EMAIL_ERROR'						=> 'Option deactivated because the <em>"Email settings"</em> and/or <em>"Email alert"</em> are deactivated',
		'AFL_PM_ERROR'							=> 'Option deactivated because the <em>"Private Messages settings"</em> and/or <em>"PMs alert"</em> are deactivated',
		'AFL_PM_EMAIL_ERROR'					=> 'Option deactivated because the <em>"Private Messages settings"</em> and the <em>"Email settings"</em> are deactivated',
		'AFL_FOUNDER_ERROR'						=> 'Warning, only founders can alter settings of "Alert For Logins"!',
		'AFL_SETTINGS_CUSTOMIZABLE'				=> 'Note that the user can choice to be alerted by email or by PM.',
		'AFL_SETTINGS_FOUNDER'					=> 'Founders setting only',
		'AFL_SETTINGS_FOUNDER_EXPLAIN'			=> 'Block the setting of this MOD to the founders only.<br />If this setting is set to "no", all people who have access to the Administration Control Panel will be able to alter the settings of "Alert For Logins"<br /><em>Except those to founders.</em>',
		'AFL_FAIL_SETTINGS'						=> 'Settings of failed logins',
		'AFL_SUCCESS_SETTINGS'					=> 'Settings of successful logins',
		'AFL_ICON_PM'							=> 'Icon of the private message',
		'AFL_ICON_TOPIC'						=> 'Icon of the topic',
		'AFL_ICON_PM_NO'						=> 'Any icon',
		'AFL_ICON_PM_EXPLAIN'					=> 'Choose the icon which will be used for private message',
		'AFL_ICON_TOPIC_EXPLAIN'				=> 'Choose the icon which will be used for the topic',
		'AFL_STATS_ENABLE'						=> 'Statistics for "Alert For Logins"',
		'AFL_STATS_RESET'						=> 'Reset all statistics of "Alert For Logins"',
		'AFL_STATS_ENABLE_EXPLAIN'				=> 'Enable statistics of "Alert For Logins" like the number of failed or successful logins, the number of sent email and pm... <br /><em>Those settings are not recommended on the boards with heavy traffic</em>',

//ACP Javascript alert popup key
//We use \n for back line because only G.Chrome understand <br /> in Javascript popup functions (like "alert", "confirm" and other)
		'AFL_STATS_RESET_POPUP'							=> 'All statistics will be reset! \n Are you sure you want to continue?',
		'AFL_UCP_SUCCESS_POPUP'							=> 'Attention this option will irritate users if autologin is disabled. \n Although users have the ability to disable alerts of successful connections in their user account settings, \n it’s important to disable this option by default in their account settings. \n For that, check the box below.',
		'AFL_UCP_FAIL_POPUP'							=> 'Please note this option is not recommended for forums with several users! \n You could potentially see the personal mailboxes of the founders to be submerged by email alerts!\n Are you really sure you want to enable this setting?',
		'AFL_UCP_FORCE_ALL_POPUP'						=> 'You are going to affect the user setting “Alert for failed logins” of all users on the forum\n Are you sure you want to continue?',
		'AFL_UCP_FORCE_ALL_SUCCESS_POPUP'				=> 'You are going to affect the user setting "Alert for successful logins" of all forum users.\n Are you sure you want to continue?',

//Version Check based on the Sylver35's Stuff (http://breizh-portal.com)
		'AFL_ERRORS_CONFIG_ALT'							=> 'Configuration of the MOD Alert For Logins',
		'AFL_ERRORS_CONFIG_EXPLAIN'						=> 'On this page you can check if your version of this mod is up to date or otherwise, actions to take for the update.<br />You can also set points simple configuration related.',
		'ACP_ERRORS'									=> 'Errors',
		'ACP_UNINSTALL_FILE'							=> '» %sUMIL Uninstall file%s',
		'AFL_UNABLE_CONNECT'							=> 'Can not connect to server version checking, error message: %s',
		'AFL_CURRENT_VERSION'							=> 'Current version',
		'AFL_LATEST_VERSION'							=> 'Latest version',
		'AFL_NEW_VERSION'								=> 'Your version of Alert For Logins is not up to date. Your version is the %1$s, the latest version is the %2$s. Read the next line for more information',
		'AFL_ERRORS_NO_VERSION'							=> '<span style="color: red">The version of the server could not be found...</span>',
		'AFL_ERRORS_VERSION_COPY'						=> '<a href="%1$s" title="Mod Alert For Logins">Mod Alert For Logins v%2$s</a> &copy; 2012 <a href="http://geolim4.com" title="geolim4.com"><em>Geolim4.com</em></a>',
		'AFL_ERRORS_VERSION_CHECK'						=> 'Version checker of Alert For Logins',
		'AFL_ERRORS_VERSION_CHECK_EXPLAIN'				=> 'Checks if the version of Alert For Logins that you are currently using is the latest.',
		'AFL_ERRORS_VERSION_NOT_UP_TO_DATE'				=> 'Your version of Alert For Logins is outdated :`(<br />Below you will find a link to the release announcement of the latest version and instructions on how to perform the update.',
		'AFL_ERRORS_VERSION_UP_TO_DATE'					=> 'Your installation is up to date :)',
		'AFL_ERRORS_INSTRUCTIONS'						=> '<br /><h1>To use Alert For Logins v%1$s</h1><br />
																				<p>Geolim4.com hopes you will like the features of this Mod.<br />
																				Feel free to donate to make durable development and support... Go <strong><a href="%2$s" title="Alert For Login">on this page</a></strong></p>
																				<p>For any support request, go to the <strong><a href="%3$s" title="Support Forum">Support Forum</a></strong></p>
																				<p>Also visit the Tracker <strong><a href="%4$s" title="Tracker of Mod Alert For Logins">on this page</a></strong>. Keep you informed of any bugs, feature requests or additions, security...</p>',
		'AFL_ERRORS_UPDATE_INSTRUCTIONS'				=> '
				<h1>Release announcement</h1>
				<p>Please read <a href="%1$s" title="%1$s"><strong>the release announcement of the latest version</strong></a> before beginning the process of updating, it may contain useful information. It also contains download links and a complete change log.</p>
				<br />
				<h1>How to update your installation of Alert For Logins</h1>
				<p>► Download the latest version.</p>
				<p>► Unzip the archive and open the install.xml file, it contains all the update information.</p>
				<p>► Official announcement of the latest version: (%2$s)</p>',
		//Stuff based on the log connection by Elglobo....
		'AFL_MANAGE_IP'					=> 'Manage IP addresses',
		'AFL_INCLUDE_IP'				=> 'Include my current IP',
		'AFL_INCLUDE_ME'				=> 'Include myself',
		'AFL_NO_EXCLUDE_IP'				=> 'No excluded IP addresses',
		'AFL_EXCLUSION_IP'				=> 'Exclude IPs (for all login type)',
		'AFL_ALREADY_IP'				=> 'The <strong>%1$s</strong> IP has not been added because it is already excluded.',
		'AFL_EXCLUSION_IP_EXPLAIN'		=> 'To specify several different IPs, enter each one on a new line.',
		'AFL_UNEXCLUSION_IP'			=> 'Un-exclude IPs',
		'AFL_UNEXCLUSION_IP_EXPLAIN'	=> 'You can un-exclude multiple IP addresses in one per one.
												<br /><em>Use ctrl+click for select more than one IP.</em>.',
		'AFL_EXCLUDE_NO_IP'				=> '<strong>%1$s</strong> IP is not correctly defined',
		//QTE addon
		'AFL_ADDON_QTE'					=> 'The attribute %s has been selected',
		'AFL_ADDON_QTE_NO'				=> 'No attribute selected',
));

?>