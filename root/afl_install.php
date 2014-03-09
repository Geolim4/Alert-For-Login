<?php
/**
 *
 * @author Geolim4 (Georges.L) geolim4@gmail.com
 * @version $Id: acp_alert_for_login.php v1.3.1b 00:27 11/07/2013 Geolim4 Exp $
 * @copyright (c) 2012, 2013 Geolim4.com http://geolim4.com
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 *
 */

/**
 * @ignore
 */
define('UMIL_AUTO', true);
define('IN_PHPBB', true);
$phpbb_root_path = (defined('PHPBB_ROOT_PATH')) ? PHPBB_ROOT_PATH : './';
$phpEx = substr(strrchr(__FILE__, '.'), 1);

include($phpbb_root_path . 'common.' . $phpEx);
$user->session_begin();
$auth->acl($user->data);
$user->setup();

$cur_afl_version = (isset($config['afl_mod_version']) ? $config['afl_mod_version'] : '');

$php_v_required = phpbb_version_compare(PHP_VERSION, '5.3.0', '>=');


//$user->add_lang('mods/info_acp_alert_for_login');
if (!file_exists($phpbb_root_path . 'umil/umil_auto.' . $phpEx))
{
	trigger_error('Please download the latest UMIL (Unified MOD Install Library) from: <a href="http://www.phpbb.com/mods/umil/">phpBB.com/mods/umil</a>', E_USER_ERROR);
}
// The name of the mod to be displayed during installation.
$mod_name = 'ACP_AFL_CONFIG_UMIL';

/*
* The name of the config variable which will hold the currently installed version
* UMIL will handle checking, setting, and updating the version itself.
*/
$version_config_name = 'afl_mod_version';


// The language file which will be included when installing
$language_file = 'mods/info_acp_alert_for_login';


/*
* Optionally we may specify our own logo image to show in the upper corner instead of the default logo.
* $phpbb_root_path will get prepended to the path specified
* Image height should be 50px to prevent cut-off or stretching.
*/
$logo_img = 'images/afl.png';

// Options to display to the user
$options = array(
	'legend2'	=> 'WARNING',
	'welcome'	=> array('lang' => 'ACP_AFL_CONFIG_UMIL_PHP', 'type' => 'custom', 'function' => 'display_message', 'params' => array('ACP_AFL_CONFIG_UMIL_PHP530_' . ($php_v_required ? 'OK' : 'NO'), ($php_v_required ? 'success' : 'error')), 'explain' => false),
	'legend3'	=> 'ACP_SUBMIT_CHANGES',
);

/*
* The array of versions and actions within each.
* You do not need to order it a specific way (it will be sorted automatically), however, you must enter every version, even if no actions are done for it.
*
* You must use correct version numbering.  Unless you know exactly what you can use, only use X.X.X (replacing X with an integer).
* The version numbering must otherwise be compatible with the version_compare function - http://php.net/manual/en/function.version-compare.php
*/
$now = time();
$versions = array(
	//
	// v1.3.2
	//
	'1.3.2' => array(
		'config_update' => array(
			array('afl_update_date', $now)
		),
	),
	'1.3.1c' => array(
		'config_update' => array(
			array('afl_update_date', $now)
		),
	),
	'1.3.1b' => array(
		'config_update' => array(
			array('afl_update_date', $now)
		),
	),
	'1.3.1' => array(
		'config_update' => array(
			array('afl_update_date', $now)
		),
	),
	'1.3.0' => array(
		'config_update' => array(
			array('afl_update_date', $now)
		),
		'config_add' => array(
			array('afl_cache_time', $now),
			array('afl_language_topic', AFL_TOPIC_LANGUAGE_BOARD),
			array('afl_groups_include', serialize(array($user->data['group_id']))),
		),
		'table_column_add' => array(
			array(USERS_TABLE, 'user_afl_founder', array('UINT', 0)),
			array(USERS_TABLE, 'user_afl_type', array('VCHAR:10', AFL_TYPE_MAIL)),
		),
		'table_row_insert' => array(
			array('phpbb_afl_exclude_ips', array(
				'exclude_ip'	=> '192.168.0.254',//exclude local gateway
				),
			),
		),
		'custom' => 'log_update_afl',
		'cache_purge' => array(''),
	),
	//
	// v1.2.2
	//
	'1.2.2' => array(
		'config_update' => array(
			array('afl_update_date', $now)
		),
		'cache_purge' => array(''),
	),
	//
	// v1.2.1
	//
	'1.2.1' => array(
		'config_add' => array(
			array('afl_hook_login', 1),
		),
		'config_update' => array(
			array('afl_update_date', $now)
		),
		'cache_purge' => array(''),
	),
	//
	// v1.2.0
	//
	'1.2.0' => array(
		'config_add' => array(
			array('afl_groups_exclude', 'a:1:{i:0;i:3;}'),//Exclude COPPA group for example.
			array('afl_poster_owner', 0),
			array('afl_poster_id', serialize(array(
					'user_id'		=> $user->data['user_id'],
					'username'		=> $user->data['username'],
					'user_colour'	=> $user->data['user_colour']
				))),
			array('afl_poster_icon_id', 0),
			array('afl_lock_topic', 0),
			array('afl_addon_qte_id', 0),
			array('afl_forum_target', 0),
			array('afl_stats_topics', 0),
			array('afl_fail_topics', 0),
			array('afl_success_topics', 0),
			array('afl_update_date', $now)
		),
		'cache_purge' => array(''),
	),
	//
	// v1.1.0
	//
	'1.1.0' => array(
		'config_add' => array(
			array('afl_alert_limit', 5),
		),
		'table_column_add' => array(
			array(USERS_TABLE, 'user_afl_limit', array('UINT', 0)),
		),
		'table_row_insert' => array(
			array('phpbb_afl_exclude_ips', array(
				'exclude_ip'	=> 'AAAA:0000:AAAA:0000:AAAA:0000:AAAA:0000',//IPV6 :D
				),
			),
		),
		'cache_purge' => array(''),
	),
	//
	// v1.0.0rc1
	//
	'1.0.0rc1' => array(
	//Some config we've need...
			'config_add' => array(
				array('afl_acp_fail', 1),
				array('afl_founders_exclude', serialize(array())),
				array('afl_acp_success', 1),
				array('afl_alert_founder_acp', 1),
				array('afl_alert_founder_ucp', 1),
				array('afl_whois_url', 'http://en.utrace.de/?query={USER_IP}'),
				array('afl_bot_id', $user->data['user_id']),
				array('afl_bot_username', $user->data['username']),//Default username...Can be changed later...
				array('afl_force_alert', 0),
				array('afl_force_alert_succ', 0),
				array('afl_force_all', 1),
				array('afl_mail_alert', 1),
				array('afl_mod_enabled', 1),
				array('afl_pm_alert', 1),
				array('afl_pm_icon_id', 0),
				array('afl_settings_founder', 1),
				array('afl_stats_enabled', 1),
				array('afl_stats_fail', 0),
				array('afl_stats_fail_acp', 0),
				array('afl_stats_mail', 0),
				array('afl_stats_pm', 0),
				array('afl_stats_success', 0),
				array('afl_stats_success_acp',0),
				array('afl_ucp_fail', 1),
				array('afl_ucp_success', 1),
				array('afl_install_date', $now),//Current date install
				array('afl_mail_pwd', 1),
				array('afl_login_count', 5),
				array('afl_maxlogin_bypass', 0),
			),
	//Exclude IP table....
		'table_add' => array(
			array('phpbb_afl_exclude_ips', array(
				'COLUMNS' => array(
				'exclude_id' => array('UINT', NULL, 'auto_increment'),
				'exclude_ip' => array('VCHAR:40', ''),
				),
				'PRIMARY_KEY'	=> 'exclude_id',
			)),
		),
	//Insert row just for example
		'table_row_insert' => array(
			array('phpbb_afl_exclude_ips', array(
				'exclude_ip'	=> '127.0.0.1',//IPV4 :/
				),
			),
		),
	//Collumn Users....
			'table_column_add' => array(
				array(USERS_TABLE, 'user_afl_success', array('BOOL', 1)),
				array(USERS_TABLE, 'user_afl_fail', array('BOOL', 1)),
			),
	//ACP Module
		'module_add' => array(
			array('acp', 'ACP_BOARD_CONFIGURATION', array(
				'module_basename' => 'alert_for_login',
				'module_langname' => 'ACP_AFL_CONFIG',
				'module_mode'	=> 'configuration',
				'module_auth' => '',
				'after' => 'ACP_VC_CAPTCHA_DISPLAY',
			)),
		),
		'cache_purge' => array(''),
	),
);

// Include the UMIL Auto file, it handles the rest
include($phpbb_root_path . 'umil/umil_auto.' . $phpEx);

/**
* Display a message with a specified css class
*
* @param string		$lang_string	The language string to display
* @param string		$class			The css class to apply
* @return string					Formated html code
**/
function display_message($lang_string, $class)
{
	global $user;

	$lang_string = isset($user->lang[$lang_string]) ? $user->lang[$lang_string] : $lang_string;
	return '<span class="' . $class . '">' . $lang_string . '</span>';
}

/**
* log_update_afl()
* Here is our custom function that will be called in UMIL install file.
* @param string $action The action (install|update|uninstall) will be sent through this.
* @param string $version The version this is being run for will be sent through this.
*/
function log_update_afl($action, $version)
{
	global $config, $user, $cur_afl_version;

	if ( $action == 'update' )
	{
		add_log('admin', 'ACP_AFL_LOG_UPDATE', $cur_afl_version, $version);
	}
	return $user->lang['ACP_AFL_LOG_ADDED'];
}