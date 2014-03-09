<?php
/**
*
* @package acp Alert For Logins
* @version $Id: acp_alert_for_login.php v1.3.1 00:21 05/1/2013 Geolim4 Exp $
* @copyright (c) 2012, 2013 Geolim4.com http://geolim4.com
* @copyright (c) 2005 phpBB Group
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

/**
* @ignore
*/
if (!defined('IN_PHPBB'))
{
	exit;
}

/**
* @package acp
*/
define('IN_AFL_ACP', true);//To developper: Can be used if you want know if this file is already included or not in your code...
if ( !defined('GUESTS') )
{
	define('GUESTS', 1);
}
class acp_alert_for_login
{
	var $u_action;
	function main($id, $mode)
	{
		global $config;//Only Call $config Starting now if $error as returned as TRUE :/
		//Check install before all !!
		$error = $this->afl_check_install();

		if ( $error )
		{
			//Disable Mod: install not complete !!!
			if ( $config['afl_mod_enabled'] )
			{
				add_log('admin', 'ACP_AFL_LOG_OFF');
			}
			set_config('afl_mod_enabled', 0, false);
			trigger_error($error . adm_back_link($this->u_action), E_USER_WARNING);
		}

		//No $error? Okey boss load the settings please :)
		global $db, $user, $template, $phpbb_root_path, $phpEx, $cache;
		global $qte;
		include($phpbb_root_path . 'includes/functions_display.' . $phpEx);
		$mode = request_var('mode', '');
		$submit = (isset($_POST['submit'])) ? true : false;
		$config_submit = request_var('config_submit', '');
		$sql_ary = array();

		$this->tpl_name = 'acp_alert_for_login';
		$this->page_title = 'ACP_AFL_CONFIG';
		$user->add_lang('ucp');
		
		$form_key = 'acp_alert_for_login';
		add_form_key($form_key);
		$latest_version = $announcement_url = $trigger_info = $afl_exclusion_options = $whois_selector = '';//Empty string...
		$config_poster = unserialize($config['afl_poster_id']);

		switch ($mode)
		{
			case 'configuration':
				//$config['email_enable'] = $config['allow_privmsg'] = 0;
				if ( !$config['email_enable'] && !$config['allow_privmsg'] )
				{
					$template->assign_vars(array(
						'NO_PM_MAIL'	=> true,
					));
				}
				if ( $config['afl_settings_founder'] && $user->data['user_type'] != USER_FOUNDER )
				{
					trigger_error($user->lang['AFL_FOUNDER_ERROR'] . adm_back_link($this->u_action), E_USER_WARNING);//Not allowed.. Get out please...
				}
				$update = isset($_POST['update']) ? true : false;
				$update_addons = isset($_POST['update_addons']) ? true : false;
				if ( $update )
				{
					//before all check form integrity pls !!
					if ( !check_form_key($form_key) )
					{
						$error[] = $user->lang['FORM_INVALID'];
					}
					$settings = array (
						'afl_mod_enabled'		=> request_var('afl_mod_enabled', 1),
						'afl_fail_topics'		=> request_var('afl_fail_topics', 0),
						'afl_success_topics'	=> request_var('afl_success_topics', 0),
						'afl_forum_target'		=> request_var('afl_forum_target', 1),
						'afl_acp_fail'			=> request_var('afl_acp_fail', 1),
						'afl_ucp_fail'			=> request_var('afl_ucp_fail', 1),
						'afl_alert_founder_acp'	=> request_var('afl_alert_founder_acp', 1),
						'afl_alert_founder_ucp'	=> request_var('afl_alert_founder_ucp', 1),
						'afl_whois_url'			=> request_var('afl_whois_url', 'http://en.utrace.de/?query={USER_IP}'),
						'afl_force_alert'		=> request_var('afl_force_alert', 1),
						'afl_poster_icon_id'	=> request_var('afl_poster_icon_id', 0),
						'afl_lock_topic'		=> request_var('afl_lock_topic', 1),
						'afl_language_topic'	=> request_var('afl_language_topic', 'board'),
						'afl_stats_enabled'		=> request_var('afl_stats_enabled', 0),
						'afl_login_count'		=> request_var('afl_login_count', 8),
						'afl_maxlogin_bypass'	=> request_var('afl_maxlogin_bypass', 0),
						'afl_hook_login'		=> request_var('afl_hook_login', 1),
						'afl_alert_limit'		=> request_var('afl_alert_limit', 3),//Default limit: 3
						'afl_founders_exclude'	=> serialize(request_var('afl_founders_exclude', array(0))),
						'afl_users_include'		=> request_var('afl_users_include', ''),
						'afl_users_unexclude'	=> request_var('afl_users_unexclude', array(0)),
						'afl_groups_exclude'	=> serialize(request_var('afl_groups_exclude', array(0))),
						'afl_groups_include'	=> serialize(request_var('afl_groups_include', array(0))),
						'afl_poster_username'	=> request_var('afl_poster_username', (empty($config_poster['username']) ? $user->data['username'] : $config_poster['username']), true),//Will be unset before set_config
						//ip excluded
						'afl_exclude_ip'		=> request_var('afl_exclude_ip', ''), //Will be unset before set_config but used for SQL query
						'afl_unexclude_ip'		=> request_var('afl_unexclude_ip', array(0)), //Will be unset before set_config but used for SQL query
						//Grab $_POST data from checkbox
						'afl_poster_owner'		=> (isset($_POST['afl_poster_owner'])) ? true : false,
						'afl_force_all'			=> (isset($_POST['afl_force_all'])) ? true : false,//Will be unset before set_config
						'afl_force_all_success'	=> (isset($_POST['afl_force_all_success'])) ? true : false,//Will be unset before set_config
						'afl_stats_reset'		=> (isset($_POST['afl_stats_reset'])) ? true : false//Will be unset before set_config
					);
					if ( $config['allow_privmsg'] )
					{
						$settings = array_merge($settings, array(
							'afl_pm_alert'			=> request_var('afl_pm_alert', 1),
							'afl_bot_username'		=> request_var('afl_bot_username', (empty($config['afl_bot_username']) ? $user->data['username'] : $config['afl_bot_username']), true),
							'afl_pm_icon_id'		=> request_var('afl_pm_icon_id', 0),

						));
					}
					else
					{
						$settings = array_merge($settings, array(
							'afl_pm_alert'			=> false,
							'afl_bot_username'		=> request_var('afl_bot_username', (empty($config['afl_bot_username']) ? $user->data['username'] : $config['afl_bot_username']), true),
							'afl_pm_icon_id'		=> 0,

						));
					}
					if ( $config['email_enable'] )
					{
						$settings = array_merge($settings, array(
							'afl_mail_alert'		=> request_var('afl_mail_alert', 1),
							'afl_mail_pwd'			=> request_var('afl_mail_pwd', 0),
							'afl_ucp_success'		=> request_var('afl_ucp_success', 1),
							'afl_acp_success'		=> request_var('afl_acp_success', 1),
							'afl_force_alert_succ'	=> request_var('afl_force_alert_succ', 1),
						));
					}
					else
					{
						$settings = array_merge($settings, array(
							'afl_mail_alert'		=> false,
							'afl_mail_pwd'			=> false,
							'afl_ucp_success'		=> false,
							'afl_acp_success'		=> false,
							'afl_force_alert_succ'	=> false,
						));
					}
					if ( !in_array($settings['afl_language_topic'], array(AFL_TOPIC_LANGUAGE_USER, AFL_TOPIC_LANGUAGE_BOARD)) )
					{
						$settings['afl_language_topic'] = AFL_TOPIC_LANGUAGE_BOARD;
					}
					//Protect settings if the user is not founder but can modify the settings...
					if ( $user->data['user_type'] == USER_FOUNDER )
					{
						$settings = array_merge($settings, array('afl_settings_founder'	=> request_var('afl_settings_founder', 1)));
					}
					//look if one or more group are the same between "excluded group" and "as founder group"
					if ( sizeof(array_intersect(unserialize($settings['afl_groups_exclude']), unserialize($settings['afl_groups_include']) ) ) )
					{
						trigger_error($user->lang['AFL_ERROR_INC_EXC'] . adm_back_link($this->u_action), E_USER_WARNING);
					}
					//Check if WHOIS is correctly formed...If not, restore default value for avoid any error
					if ( !preg_match('#\{USER_IP\}#', $settings['afl_whois_url']) )
					{
						$trigger_info .= '<br />' . $user->lang['AFL_TRIGGER_URL'];//Add info to trigger_error
						$settings['afl_whois_url'] = 'http://en.utrace.de/?query={USER_IP}';
					}
					$settings['afl_whois_url'] = str_replace('amp;', '', str_replace('&', '&amp;', $settings['afl_whois_url']));
					if ( sizeof($settings['afl_users_unexclude']) )
					{
						$users_ary = array ('user_afl_founder'		=> AFL_USER_NORMAL);
						$sql = 'UPDATE ' . USERS_TABLE . '
							SET ' . $db->sql_build_array('UPDATE', $users_ary) . '
							WHERE ' . $db->sql_in_set('user_id', $settings['afl_users_unexclude'], false, true);
						$db->sql_query($sql);
					}
					if ( $settings['afl_users_include'] )
					{
						$settings['afl_users_include']  = explode("\n", $settings['afl_users_include']);
						
						$users_ary = array ('user_afl_founder'		=> AFL_USER_FOUNDER);
						$sql = 'UPDATE ' . USERS_TABLE . '
							SET ' . $db->sql_build_array('UPDATE', $users_ary) . '
							WHERE ' . $db->sql_in_set('username', $settings['afl_users_include'], false, true) . '
								AND user_type <> ' . USER_FOUNDER;
						$db->sql_query($sql);
						
						$settings['afl_users_include'] = array_flip($settings['afl_users_include']);
						$sql = 'SELECT username
							FROM ' . USERS_TABLE . '
							WHERE ' . $db->sql_in_set('username', $settings['afl_users_include'], false, true) . '
							AND user_type <> ' . USER_FOUNDER;
						$result = $db->sql_query($sql);
						while ( $row = $db->sql_fetchrow($result) )
						{
							unset($settings['afl_users_include'][$row['username']]);
						}
						$db->sql_freeresult($result);
						if ( sizeof($settings['afl_users_include']) )
						{
							//flip again 8)
							foreach(array_flip($settings['afl_users_include']) as $username_ )
							{
								$trigger_info .= '<br />' . $user->lang('AFL_USERS_INCLUDE_IGNORED', $username_);
							}
						}
					}
					//Why this Query SQL here?
					//Because if i get the user ID from the username of the bot here, i don't need to add this query on the function file !!!
					if ( $config['afl_bot_username'] != $settings['afl_bot_username'] && $config['allow_privmsg'] )
					{
						$sql = $db->sql_build_query('SELECT', array(
								'SELECT'	=> 'user_id',
							'FROM'		=>	array(USERS_TABLE => ''),
							'WHERE'		=> 'username = \'' . $db->sql_escape($settings['afl_bot_username']). '\'',
						));
						$result = $db->sql_query_limit($sql, 1);
						$uid_bot = (int) $db->sql_fetchfield('user_id');
						$db->sql_freeresult($result);
						$settings = array_merge($settings, array(
							'afl_bot_id'			=> $uid_bot,
						));
					}
					else
					{
						$uid_bot = $config['afl_bot_id'];
					}
					if ( ( $config_poster['username'] != $settings['afl_poster_username'] ) && ( !empty($config['afl_fail_topics']) || !empty($config['afl_success_topics'] ) ) && empty($settings['afl_poster_owner']) )
					{
						$sql = $db->sql_build_query('SELECT', array(
								'SELECT'	=> 'user_id, user_colour, username',
							'FROM'		=>	array(USERS_TABLE => ''),
							'WHERE'		=> "username = '" .(string) $db->sql_escape($settings['afl_poster_username']). "'",
						));
						$result = $db->sql_query_limit($sql, 1);
						$poster_bot = $db->sql_fetchrow($result);
						$db->sql_freeresult($result);
						$settings = array_merge($settings, array(
							'afl_poster_id'			=> serialize($poster_bot)
						));
					}
					else
					{
						$poster_bot = unserialize($config['afl_poster_id']);
					}
					//shamefully copied from acp_users :@
					if ( empty($uid_bot) || empty($poster_bot['username']) )
					{
						trigger_error($user->lang['NO_USER'] . adm_back_link($this->u_action), E_USER_WARNING);
					}
					if ( $settings['afl_stats_reset'] )
					{
						$statlist =	array('afl_stats_fail', 'afl_stats_fail_acp', 'afl_stats_success', 'afl_stats_success_acp', 'afl_stats_pm', 'afl_stats_mail', 'afl_stats_topics');
						foreach ( $statlist as $stat )
						{
							set_config($stat, 0, false);
						}
						//Ok stats cleared...
					}
					if ( $settings['afl_force_all'] )
					{
						$sql_ary += array ('user_afl_fail'		=> $settings['afl_force_alert']);
						$sql = 'UPDATE ' . USERS_TABLE . '
							SET ' . $db->sql_build_array('UPDATE', $sql_ary) . '
							WHERE user_id > 0 ';
						$db->sql_query($sql);
					}
					if ( $settings['afl_force_all_success'] )
					{
						$sql_ary += array ('user_afl_success'		=> $settings['afl_force_alert_succ']);
						$sql = 'UPDATE ' . USERS_TABLE . '
							SET ' . $db->sql_build_array('UPDATE', $sql_ary) . '
							WHERE user_id > 0 ';
						$db->sql_query($sql);
					}
					if ( !empty($settings['afl_fail_topics']) || !empty($settings['afl_success_topics'] ) )
					{
						// Check if destination forum exists
						$sql = 'SELECT forum_name
							FROM ' . FORUMS_TABLE . '
							WHERE forum_id = ' . (int) $settings['afl_forum_target'];
						$result = $db->sql_query($sql);
						$dest_forum_name = $db->sql_fetchfield('forum_name');
						$db->sql_freeresult($result);
						// Source forum doesn't exist
						if ( empty($dest_forum_name) )
						{
							trigger_error($user->lang['AFL_TOPIC_ERROR'] . adm_back_link($this->u_action), E_USER_WARNING);
						}
					}
					else
					{
						$settings['afl_forum_target'] = $config['afl_forum_target'];
					}
					if ( !empty($settings['afl_exclude_ip']) )//Get ip to exclude of alert
					{
						// Check for excluded IPs
						$iplist = $sql_ex_list = array();
						$sql_exip = 'SELECT exclude_ip, exclude_id FROM ' . AFL_EXCLUDE_IPS_TABLE;
						$ex_ip = $db->sql_query($sql_exip);
						while ( $iprow = $db->sql_fetchrow($ex_ip) )
						{
							$iplist[$iprow['exclude_id']] = $iprow['exclude_ip'];
						}
						$db->sql_freeresult($ex_ip);
						//-
						$exclusion_list = (!is_array($settings['afl_exclude_ip'])) ? array_unique(explode("\n", $settings['afl_exclude_ip'])) : $settings['afl_exclude_ip'];
						$exclusion_list_log = implode(', ', $exclusion_list);

						foreach ( $exclusion_list as $exclusion_item )
						{
							if ( !in_array($exclusion_item, $iplist) )
							{
								if ( preg_match(get_preg_expression('ipv4'), trim($exclusion_item)) || preg_match(get_preg_expression('ipv6'), trim($exclusion_item)) )
								{
									$sql_ex_list[] = array('exclude_ip' => $exclusion_item);
								}
								else
								{
									$trigger_info .= '<br />' . $user->lang('AFL_EXCLUDE_NO_IP', $exclusion_item);
								}
							}
							else
							{
								$trigger_info .= '<br />' . $user->lang('AFL_ALREADY_IP', $exclusion_item);
							}
						}
						if ( $sql_ex_list )
						{
							$db->sql_multi_insert(AFL_EXCLUDE_IPS_TABLE, $sql_ex_list);
							$cache->destroy('_excludes_ip');
						}
					}
					if ( !empty($settings['afl_unexclude_ip']) )//Get ip to UNexclude of alert
					{
						$unexclude_sql = array_map('intval', $settings['afl_unexclude_ip']);
						$sql = 'SELECT exclude_ip AS unexclude_info
							FROM ' . AFL_EXCLUDE_IPS_TABLE . '
							WHERE ' . $db->sql_in_set('exclude_id', $unexclude_sql);
						$result = $db->sql_query($sql);

						$l_unexclude_list = '';

						while ( $row = $db->sql_fetchrow($result) )
						{
							$l_unexclude_list .= (($l_unexclude_list != '') ? ', ' : '') . $row['unexclude_info'];
						}
						$db->sql_freeresult($result);
						$sql = 'DELETE FROM ' . AFL_EXCLUDE_IPS_TABLE . '
							WHERE ' . $db->sql_in_set('exclude_id', $unexclude_sql);
						$db->sql_query($sql);
						$cache->destroy('_excludes_ip');
					}

					unset($settings['afl_unexclude_ip'], $settings['afl_exclude_ip'], $settings['afl_stats_reset'], $settings['afl_force_all_success'], $settings['afl_force_all'], $settings['afl_poster_username'], $settings['afl_users_unexclude'], $settings['afl_users_include']);//Don't keep these VAR... no need here...
					//Many query coming soon, keep alive !!
					$db->sql_transaction('begin');

					foreach ( $settings as $config_name => $config_value )
					{
						if	($config_name != $config[$config_name])
						{
							set_config($config_name, $config_value, false);
						}
					}
					//close it !
					$db->sql_transaction('commit');

					if ( !$config['email_enable'] && !$config['allow_privmsg'] && empty($settings['afl_fail_topics']) && empty($settings['afl_success_topics']) )
					{
						set_config('afl_mod_enabled', 0, false);
						trigger_error($user->lang['AFL_CONFIG_ERROR_TRIGGER'] . $trigger_info . adm_back_link($this->u_action), E_USER_WARNING);
					}
					add_log('admin', 'ACP_AFL_LOG_ALTERED');
					if ( !isset($qte) )
					{
						trigger_error($user->lang['CONFIG_UPDATED'] . $trigger_info . adm_back_link($this->u_action));
					}
					else
					{
						$qte->attr_select($settings['afl_forum_target'], $user->data['user_id'], $config['afl_addon_qte_id'],  array());
						$template->assign_vars(array(
							//Basics vars
							'QTE_ADDON'			=> true,
						));
					}
				}
				else if ($update_addons)
				{
					if ( isset($qte) )
					{
						$qte_addon_value	= request_var('afl_addon_qte_id', 0);
					}
					set_config('afl_addon_qte_id', $qte_addon_value, false);
					trigger_error($user->lang['CONFIG_UPDATED'] . $trigger_info . adm_back_link($this->u_action));
				}
				else
				{
					if ( !function_exists('posting_gen_topic_icons') )
					{
						include($phpbb_root_path . 'includes/functions_posting.' . $phpEx);
					}
					// Get current and latest version
					$errstr = '';
					$errno = 0;
					$info = get_remote_file('geolim4.com', '/buildversion', 'alert_for_login.txt', $errstr, $errno);

					if ( $info === false )
					{
						$template->assign_vars(array(
							'S_ERROR'   => true,
							'ERROR_MSG' => sprintf($user->lang['AFL_UNABLE_CONNECT'], $errstr),
						));
					}
					else
					{
						$info 				= explode("\n", $info);
						$latest_version 	= trim($info[0]);
						$announcement_url 	= trim($info[1]);
						$up_to_date			= phpbb_version_compare($config['afl_mod_version'], $latest_version, '<') ? false : true;

						if ( !$up_to_date )
						{
							$template->assign_vars(array(
								'S_ERROR'   			=> true,
								'S_UP_TO_DATE'			=> false,
								'ERROR_MSG' 			=> sprintf($user->lang['AFL_NEW_VERSION'], $config['afl_mod_version'], $latest_version),
								'UPDATE_INSTRUCTIONS'	=> sprintf($user->lang['AFL_ERRORS_UPDATE_INSTRUCTIONS'], $announcement_url, $latest_version),
							));
						}
						else
						{
							$template->assign_vars(array(
								'S_ERROR'   			=> false,
								'S_UP_TO_DATE'			=> true,
								'UP_TO_DATE_MSG'		=> sprintf($user->lang['AFL_ERRORS_VERSION_UP_TO_DATE'], $config['afl_mod_version']),
								'UPDATE_INSTRUCTIONS'	=> sprintf($user->lang['AFL_ERRORS_INSTRUCTIONS'], $config['afl_mod_version'], $announcement_url, trim($info[2]), trim($info[3])),
							));
						}
					}
					$founders_options = '';
					$users_options = '';
					$f_count = $u_count = 0;
					$sql = 'SELECT DISTINCT username, user_id, user_colour, user_type, user_afl_founder
						FROM ' . USERS_TABLE . "
						WHERE user_type = " . USER_FOUNDER . "
							OR user_afl_founder = " . AFL_USER_FOUNDER . "
						ORDER BY username_clean ASC";
					$result = $db->sql_query($sql);
					while ( $row = $db->sql_fetchrow($result) )
					{
						if ( $row['user_afl_founder'] == AFL_USER_NORMAL && $row['user_type'] == USER_FOUNDER )
						{
							$f_count++;//increment for html size :D
							$selected = (in_array($row['user_id'], unserialize($config['afl_founders_exclude']))) ? ' selected="selected"' : '';
							$founders_options .= '<option' . ' value="' . $row['user_id'] . '" style="color:#' . $row['user_colour'] . '; font-weight:bold;"' . $selected. '>' . $row['username'] . "</option>\n";
						}
						else if ( $row['user_afl_founder'] == AFL_USER_FOUNDER && $row['user_type'] != USER_FOUNDER )
						{
							$u_count++;//increment for html size :D
							$users_options .= '<option' . ' value="' . $row['user_id'] . '" ' . ( $row['user_colour'] ? ' style="color:#' . $row['user_colour'] . '; font-weight:bold;"' : '' ) . '>' . $row['username'] . "</option>\n";
						}
					}
					$db->sql_freeresult($result);

					$exc_groups_options = $inc_groups_options = '';
					(int) $g_count = 0;
					$sql = 'SELECT DISTINCT group_id, group_name, group_type, group_colour
						FROM ' . GROUPS_TABLE . "
						WHERE group_id <>" . GUESTS . "
						AND group_name <> 'BOTS'
						ORDER BY group_name ASC";
					$result = $db->sql_query($sql);
					while ( $row = $db->sql_fetchrow($result) )
					{
						(int) $g_count++;//increment for html size :D
						$exc_selected = (in_array($row['group_id'], unserialize($config['afl_groups_exclude']))) ? ' selected="selected"' : '';
						$inc_selected = (in_array($row['group_id'], unserialize($config['afl_groups_include']))) ? ' selected="selected"' : '';

						$exc_groups_options .= '<option' . ' value="' . $row['group_id'] . '"' . ( (empty($row['group_colour'])) ? '' : ' style="color:#' . $row['group_colour'] . '; font-weight:bold;"' ) . $exc_selected . '>' . ( ($row['group_type'] == GROUP_SPECIAL && isset($user->lang['G_' . $row['group_name']]) ) ? $user->lang['G_' . $row['group_name']] : $row['group_name'] ) . "</option>\n";
						$inc_groups_options .= '<option' . ' value="' . $row['group_id'] . '"' . ( (empty($row['group_colour'])) ? '' : ' style="color:#' . $row['group_colour'] . '; font-weight:bold;"' ) . $inc_selected . '>' . ( ($row['group_type'] == GROUP_SPECIAL && isset($user->lang['G_' . $row['group_name']]) ) ? $user->lang['G_' . $row['group_name']] : $row['group_name'] ) . "</option>\n";
					}
					$db->sql_freeresult($result);

					$sql = 'SELECT exclude_id, exclude_ip
						FROM ' . AFL_EXCLUDE_IPS_TABLE . '
						ORDER BY exclude_ip ASC';
					$result = $db->sql_query($sql);

					(int) $ip_count = 0;
					while ( $ex_ip= $db->sql_fetchrow($result) )
					{
						(int) $ip_count++;
						$afl_exclusion_options .= '<option value="' . $ex_ip['exclude_id'] . '">' . $ex_ip['exclude_ip'] . '</option>';
					}
					$db->sql_freeresult($result);

					$s_topic_icons = posting_gen_topic_icons($mode, $config['afl_pm_icon_id']);
					$s_topic_icons2 = $this->posting_gen_topic_icons2($mode, $config['afl_poster_icon_id']);
					$afl_stats_succ = (int) $config['afl_stats_success'] + (int) $config['afl_stats_success_acp'];
					$afl_stats_fail = (int) $config['afl_stats_fail'] + (int) $config['afl_stats_fail_acp'];
					$afl_stats_succ && $afl_stats_fail ? ((float) $afl_ratio_login = ($afl_stats_succ / $afl_stats_fail)) : (($afl_stats_succ && $afl_stats_fail == 0) ? (float) $afl_ratio_login = $afl_stats_succ : $afl_ratio_login = 0);

					//GET BOT's AVATARS --------------------------------
					$sql = 'SELECT user_avatar, user_avatar_type
						FROM ' . USERS_TABLE . '
						WHERE username ="' . $config['afl_bot_username'] . '"';
					$result = $db->sql_query_limit($sql,1);
					$bot_avatar = $db->sql_fetchrow($result);
					$db->sql_freeresult($result);
					//---------------------------------------
					$sql = 'SELECT user_avatar, user_avatar_type
						FROM ' . USERS_TABLE . '
						WHERE username ="' . $config_poster['username'] . '"';
					$result = $db->sql_query_limit($sql,1);
					$bot_avatar2 = $db->sql_fetchrow($result);
					$db->sql_freeresult($result);
					//END BOT's AVATARS --------------------------------

					//GET USERS's STATS --------------------------------
					$sql = 'SELECT COUNT(user_id) AS users_fails
						FROM ' . USERS_TABLE . '
						WHERE user_afl_fail = 1 AND user_type <>' . USER_IGNORE;
					$result = $db->sql_query($sql);
					$users_fails = (int) $db->sql_fetchfield('users_fails');
					$db->sql_freeresult($result);
					//---------------------------------------

					$sql = 'SELECT COUNT(user_id) AS users_success
						FROM ' . USERS_TABLE . '
						WHERE  	user_afl_success = 1 AND user_type <>' . USER_IGNORE;
					$result = $db->sql_query($sql);
					$users_success = (int) $db->sql_fetchfield('users_success');
					$db->sql_freeresult($result);
					//END USERS's STATS --------------------------------

					foreach ( $user->lang['AFL_WHOIS_URLS'] as $key => $url )
					{
						$whois_selector .= '<option value="' . $url . '">' . $key . '</option>';
					}
					$select_forums = '<select id="afl_forum_target" name="afl_forum_target">' .make_forum_select((int)$config['afl_forum_target'], false, true, true). '</select>';
					//Vars work...
					if ( isset($qte) )
					{
						// load language
						$user->add_lang('mods/info_acp_attributes');
					}
					if ( file_exists($phpbb_root_path . 'afl_install.' . $phpEx) )
					{
						$uninstall_file = $user->lang('ACP_UNINSTALL_FILE', '<a href="' . append_sid("{$phpbb_root_path}afl_install.$phpEx") . '">', '</a>');
					}
					$template->assign_vars(array(
						//Basics vars
						'S_CONFIG'					=> isset($qte_addon_value) ? false : true,
						'U_ACTION'					=> $this->u_action,
						'TITLE'						=> $user->lang['AFL_ERRORS_CONFIG_ALT'],
						'TITLE_EXPLAIN'				=> $user->lang['AFL_ERRORS_CONFIG_EXPLAIN'],
						'TITLE_IMG'					=> $phpbb_root_path . 'images/afl.png',
						//Mods vars
						'QTE_ADDON'					=> isset($qte) ? true : false,
														//QTE no selected attribute value: -1 this why we do not use empty() here!
						'AFL_ADDON_QTE'				=> ($config['afl_addon_qte_id'] > 0 ) ? $user->lang('AFL_ADDON_QTE', $qte->attr_display($config['afl_addon_qte_id'], $user->data['username'], time())) : $user->lang['AFL_ADDON_QTE_NO'],
						'ERRORS_VERSION'			=> sprintf($user->lang['AFL_ERRORS_VERSION_COPY'], $announcement_url, $config['afl_mod_version']),
						'S_NO_VERSION'				=> $latest_version ? false : true,
						'LATEST_VERSION'			=> $latest_version ? $latest_version : $user->lang['ERRORS_NO_VERSION'],
						'CURRENT_VERSION'			=> $config['afl_mod_version'],
						'FOUNDERS_OPTIONS'			=> $founders_options,
						'USERS_OPTIONS'				=> $users_options,
						'EXC_GROUPS_OPTIONS'		=> $exc_groups_options,
						'INC_GROUPS_OPTIONS'		=> $inc_groups_options,
						'NUM_USERS'					=> ($config['num_users'] > 50000) ? true : false,//50000 start to be a big board...
						'AFL_USER_IP'				=> $user->ip,
						'AFL_USERNAME'				=> $user->data['username'],
						'AFL_UNINSTALL_FILE'		=> $uninstall_file,
						'WHOIS_SELECTOR'			=> $whois_selector,
						'U_FIND_USERNAME'			=> append_sid("{$phpbb_root_path}memberlist.$phpEx", 'mode=searchuser&amp;form=afl_settings&amp;field=afl_bot_username&amp;select_single=true'),
						'U_FIND_USERNAME2'			=> append_sid("{$phpbb_root_path}memberlist.$phpEx", 'mode=searchuser&amp;form=afl_settings&amp;field=afl_poster_username&amp;select_single=true'),
						'U_FIND_USERNAME3'			=> append_sid("{$phpbb_root_path}memberlist.$phpEx", 'mode=searchuser&amp;form=afl_settings&amp;field=afl_users_include'),
						'AFL_FORUM_TARGET'			=> $select_forums,
						'AFL_BOT_AVATAR'			=> str_replace('alt="'. $config['afl_bot_username'] .'"', 'style="margin-bottom: -8px;" alt="'. $config['afl_bot_username'] .'" title="' . $config['afl_bot_username'] . '"', get_user_avatar($bot_avatar['user_avatar'], $bot_avatar['user_avatar_type'], 30, 30, $config['afl_bot_username'])),//Get this TROOLFACE :D
						'AFL_BOT_AVATAR2'			=> str_replace('alt="'. $config_poster['username'] .'"', 'style="margin-bottom: -8px;" alt="'. $config_poster['username'] .'" title="' . $config_poster['username'] . '"', get_user_avatar($bot_avatar2['user_avatar'], $bot_avatar2['user_avatar_type'], 30, 30, $config_poster['username'])),//Get this TROOLFACE :D
						'AFL_USER_FOUNDER'			=> $user->data['user_type'] == USER_FOUNDER ? true : false,
						'L_AFL_HOOK_LOGIN_EXPLAIN'	=> $user->lang('AFL_HOOK_LOGIN_EXPLAIN', '<a onclick="find_username(this.href); return false;" href="' . "{$phpbb_root_path}index.$phpEx?afl_hook_example=1" . '">'  ,'</a>'),
						'L_AFL_TOPIC_LANGUAGE_BOARD'=> $user->lang('AFL_TOPIC_LANGUAGE_BOARD', strtoupper($config['default_lang'])),
						'AFL_SHOW_CONFIG_ICONS'		=> $s_topic_icons,
						'AFL_SHOW_CONFIG_ICONS2'	=> $s_topic_icons2,
						'S_EXCLUSION_OPTIONS'		=> ($afl_exclusion_options) ? true : false,
						'AFL_EXCLUSION_OPTIONS'		=> $afl_exclusion_options,
						'AFL_MOD_ENABLED'			=> isset($config['afl_mod_enabled'])		? (((bool)	$config['afl_mod_enabled'] == 1)		? true	: false) : '',
						'AFL_FAIL_TOPICS'			=> isset($config['afl_fail_topics'])		? (((bool)	$config['afl_fail_topics'] == 1)		? true	: false) : '',
						'AFL_SUCCESS_TOPICS'		=> isset($config['afl_success_topics'])		? (((bool)	$config['afl_success_topics'] == 1)		? true	: false) : '',
						'AFL_FORCE_ALERT'			=> isset($config['afl_force_alert'])		? (((bool)	$config['afl_force_alert'] == 1)		? true	: false) : '',
						'AFL_FORCE_ALERT_SUCC'		=> isset($config['afl_force_alert_succ'])	? (((bool)	$config['afl_force_alert_succ'] == 1)	? true	: false) : '',
						'AFL_UCP_FAIL'				=> isset($config['afl_ucp_fail']) 			? (((bool)	$config['afl_ucp_fail'] == 1)			? true	: false) : '',
						'AFL_ACP_FAIL'				=> isset($config['afl_acp_fail'])			? (((bool)	$config['afl_acp_fail'] == 1)			? true	: false) : '',
						'AFL_UCP_SUCCESS'			=> isset($config['afl_ucp_success'])		? (((bool)	$config['afl_ucp_success'] == 1)		? true	: false) : '',
						'AFL_ACP_SUCCESS'			=> isset($config['afl_acp_success'])		? (((bool)	$config['afl_acp_success'] == 1)		? true	: false) : '',
						'AFL_ALERT_FOUNDER_UCP'		=> isset($config['afl_alert_founder_ucp'])	? (((bool)	$config['afl_alert_founder_ucp'] == 1)	? true	: false) : '',
						'AFL_ALERT_FOUNDER_ACP'		=> isset($config['afl_alert_founder_acp'])	? (((bool)	$config['afl_alert_founder_acp'] == 1)	? true	: false) : '',
						'AFL_MAIL_ALERT'			=> isset($config['afl_mail_alert'])			? (((bool)	$config['afl_mail_alert'] == 1)			? true	: false) : '',
						'AFL_PM_ALERT'				=> isset($config['afl_pm_alert'])			? (((bool)	$config['afl_pm_alert'] == 1)			? true	: false) : '',
						'AFL_SETTINGS_FOUNDER'		=> isset($config['afl_settings_founder'])	? (((bool)	$config['afl_settings_founder'] == 1)	? true	: false) : '',
						'AFL_MAXLOGIN_BYPASS'		=> isset($config['afl_maxlogin_bypass'])	? (((bool)	$config['afl_maxlogin_bypass'] == 1)	? true	: false) : '',
						'AFL_HOOK_LOGIN'			=> isset($config['afl_hook_login'])			? (((bool)	$config['afl_hook_login'] == 1)			? true	: false) : '',
						'AFL_MAIL_PWD'				=> isset($config['afl_mail_pwd'])			? (((bool)	$config['afl_mail_pwd'] == 1)			? true	: false) : '',
						'AFL_LOCK_TOPIC'			=> isset($config['afl_lock_topic'])			? (((bool)	$config['afl_lock_topic'] == 1)			? true	: false) : '',
						'AFL_POSTER_OWNER'			=> isset($config['afl_poster_owner'])		? (((bool)	$config['afl_poster_owner'] == 1)		? true	: false) : '',
						'AFL_STATS_ENABLED'			=> isset($config['afl_stats_enabled'])		? (((bool)	$config['afl_stats_enabled'] == 1)		? true	: false) : '',
						'AFL_MAIL_PHPBB'			=> isset($config['email_enable'])			? (((bool)	$config['email_enable'] == 0)			? true	: false) : '',//them need to be reversed
						'AFL_PM_PHPBB'				=> isset($config['allow_privmsg'])			? (((bool)	$config['allow_privmsg'] == 0)			? true	: false) : '',//them need to be reversed
						'AFL_PM_ICON_ID'			=> isset($config['afl_pm_icon_id']) 		? (string)	$config['afl_pm_icon_id']						: '',
						'AFL_LANGUAGE_TOPIC'		=> isset($config['afl_language_topic']) 	? (string)	$config['afl_language_topic']					: '',
						'AFL_POSTER_ICON_ID'		=> isset($config['afl_poster_icon_id']) 	? (string)	$config['afl_poster_icon_id']					: '',
						'AFL_BOT_USERNAME'			=> isset($config['afl_bot_username'])		? (string)	$config['afl_bot_username']						: '',
						'AFL_POSTER_USERNAME'		=> isset($config_poster['username'])		? (string)	$config_poster['username']						: '',
						'AFL_WHOIS_URL'				=> isset($config['afl_whois_url'])			? (string)	$config['afl_whois_url']						: '',
						'AFL_INSTALL_DATE'			=> isset($config['afl_install_date'])		? (string)	$user->format_date($config['afl_install_date'])	: '',
						'AFL_UPDATE_DATE'			=> isset($config['afl_update_date'])		? (string)	$user->format_date($config['afl_update_date'])	: '',
						'FOUNDERS_COUNT'			=> isset($f_count)							? (int)		$f_count										: '',
						'GROUPS_COUNT'				=> isset($g_count)							? (int)		$g_count										: '',
						'IP_COUNT'					=> isset($ip_count)							? (int)		$ip_count										: '',
						'USERS_COUNT'				=> isset($u_count)							? (int)		$u_count										: '',
						'AFL_ALERT_LIMIT'			=> isset($config['afl_alert_limit'])		? (int)		$config['afl_alert_limit']						: '',
						'AFL_LOGIN_COUNT'			=> isset($config['afl_login_count'])		? (int)		$config['afl_login_count']						: '',
						'AFL_STATS_FAIL'			=> isset($config['afl_stats_fail'])			? (int)		$config['afl_stats_fail']						: '',
						'AFL_STATS_SUCCESS'			=> isset($config['afl_stats_success'])		? (int)		$config['afl_stats_success']					: '',
						'AFL_STATS_FAIL_ACP'		=> isset($config['afl_stats_fail_acp'])		? (int)		$config['afl_stats_fail_acp']					: '',
						'AFL_STATS_SUCCESS_ACP'		=> isset($config['afl_stats_success_acp'])	? (int)		$config['afl_stats_success_acp']				: '',
						'AFL_STATS_MAIL'			=> isset($config['afl_stats_mail'])			? (int)		$config['afl_stats_mail']						: '',
						'AFL_STATS_TOPICS'			=> isset($config['afl_stats_topics'])		? (int)		$config['afl_stats_topics']						: '',
						'AFL_STATS_PM'				=> isset($config['afl_stats_pm'])			? (int)		$config['afl_stats_pm']							: '',
						'AFL_RATIO_LOGIN'			=> number_format((float) $afl_ratio_login, 2),//Limit to 2 number after comma
						'AFL_STATS_USERS_F'			=> sprintf($user->lang['AFL_STAT_USERS_FAIL'], $config['num_users']),
						'AFL_STATS_USERS_S'			=> sprintf($user->lang['AFL_STAT_USERS_SUCCESS'], $config['num_users']),
						'S_AFL_STATS_USERS_FAIL'	=> $users_fails,
						'S_AFL_STATS_USERS_SUCCESS'	=> $users_success
					));
				}
			break;

			default:
				trigger_error('NO_MODE', E_USER_ERROR);
			break;
		}
	}
/**
* Generate Topic Icons for display (2th)
* We need to create a new one because "" $template->assign_block_vars('topic_icon', array( "" make a conflict and we need both icon type...
*/
function posting_gen_topic_icons2($mode, $icon_id)
{
	global $phpbb_root_path, $config, $template, $cache;

	// Grab icons
	$icons = $cache->obtain_icons();

	if (!$icon_id)
	{
		$template->assign_var('S_NO_ICON_CHECKED', ' checked="checked"');
	}

	if (sizeof($icons))
	{
		foreach ($icons as $id => $data)
		{
			if ($data['display'])
			{
				$template->assign_block_vars('topic_icon2', array(
					'ICON_ID'		=> $id,
					'ICON_IMG'		=> $phpbb_root_path . $config['icons_path'] . '/' . $data['img'],
					'ICON_WIDTH'	=> $data['width'],
					'ICON_HEIGHT'	=> $data['height'],

					'S_CHECKED'			=> ($id == $icon_id) ? true : false,
					'S_ICON_CHECKED'	=> ($id == $icon_id) ? ' checked="checked"' : '')
				);
			}
		}

		return true;
	}

	return false;
}
//Fast Install checking
/**
* Check all the steps of the install of the mod
*
* @return empty string if install is ok...
* @return string $error if one step of checking mod install has failed...
*/
	function afl_check_install()
	{
		global $config, $user, $phpbb_root_path, $phpbb_admin_path, $phpEx, $db;
		$now = time();
		if ( $config['afl_cache_time'] < $now ) 
		{
			if ( !class_exists('phpbb_db_tools') || !class_exists('dbal') )
			{
				include($phpbb_root_path . 'includes/db/db_tools.' . $phpEx);
			}
			$afl_db	= new phpbb_db_tools($db);
			$afl_dbal =	new dbal($db);
			$error = '';
			if ( !defined('AFL_EXCLUDE_IPS_TABLE') )
			{
				//Check constant here for avoid SQL error when collumn/table checking...
				$error .= $user->lang['ACP_AFL_ERR_NOCONST'] . '<br />';
				return $error;
			}
			$sql = 'SELECT style_name
				FROM ' . STYLES_TABLE . "
				WHERE style_id= '" . $config['default_style'] . " ' ";
			$result = $db->sql_query($sql);
			$row_style = $db->sql_fetchrow($result);
			$db->sql_freeresult($result);
			$filelist = array (
				1		=> 'style/acp_alert_for_login.html',
				2		=> 'style/acp_users_prefs_afl.html',
				3		=> 'includes/class_alert_for_login.php',
				4		=> 'includes/acp/info/acp_alert_for_login.php',
				//Admin Lang
				5		=> 'language/' . $user->data['user_lang'] . '/mods/info_ucp_alert_for_login.php',
				6		=> 'language/' . $user->data['user_lang'] . '/email/alert_for_fail_login.txt',
				7		=> 'language/' . $user->data['user_lang'] . '/email/alert_for_fail_login_acp.txt',
				8		=> 'language/' . $user->data['user_lang'] . '/email/alert_for_fail_login_acp_founder.txt',
				9		=> 'language/' . $user->data['user_lang'] . '/email/alert_for_fail_login_founder.txt',
				10		=> 'language/' . $user->data['user_lang'] . '/email/alert_for_success_login.txt',
				11		=> 'language/' . $user->data['user_lang'] . '/email/alert_for_success_login_acp.txt',
				12		=> 'language/' . $user->data['user_lang'] . '/email/alert_for_success_login_acp_founder.txt',
				13		=> 'styles/' . $user->theme['template_path'] . '/template/ucp_prefs_afl.html',
				//Config lang
				14		=> 'language/' . $config['default_lang'] . '/mods/info_ucp_alert_for_login.php',
				15		=> 'language/' . $config['default_lang'] . '/email/alert_for_fail_login.txt',
				16		=> 'language/' . $config['default_lang'] . '/email/alert_for_fail_login_acp.txt',
				17		=> 'language/' . $config['default_lang'] . '/email/alert_for_fail_login_acp_founder.txt',
				18		=> 'language/' . $config['default_lang'] . '/email/alert_for_fail_login_founder.txt',
				19		=> 'language/' . $config['default_lang'] . '/email/alert_for_success_login.txt',
				20		=> 'language/' . $config['default_lang'] . '/email/alert_for_success_login_acp.txt',
				21		=> 'language/' . $config['default_lang'] . '/email/alert_for_success_login_acp_founder.txt',
				22		=> ($user->theme['template_path'] != $row_style['style_name']) ? 'styles/'	 . $row_style['style_name'] . '/template/ucp_prefs_afl.html' : '',
			);
			$filelist = array_map('strtolower', $filelist);//Fix a potential bug with some style whith are lowerercase in folder but upercase in DB
			if ( !$afl_db->sql_table_exists(AFL_EXCLUDE_IPS_TABLE) )
			{
				$error .= sprintf($user->lang['AFL_INSTALL_NO_TABLE'] . '<br />', AFL_EXCLUDE_IPS_TABLE);
				return $error;//Return here because if the table is missing, sql_column_exists will return a general error...
			}
			$collumnlista = array (
				1		=> 'user_afl_success',
				2		=> 'user_afl_fail',
				3		=> 'user_afl_limit',
				4		=> 'user_afl_founder',
				5		=> 'user_afl_type'
			);
			$collumnlistb = array (
				1		=> 'exclude_id',
				2		=> 'exclude_ip'
			);
			foreach ( $filelist as $key => $file )
			{
				if (!file_exists($phpbb_root_path . $file) && $key >= 3)
				{
					$error .= sprintf($user->lang['AFL_INSTALL_NO_FILE'], $file);
				}
				else if (!file_exists($phpbb_admin_path . $file) && $key < 3)
				{
					$error .= sprintf($user->lang['AFL_INSTALL_NO_FILE'], $file);
				}
			}
			foreach ( $collumnlista as $key => $collumna )
			{
				if (!$afl_db->sql_column_exists(USERS_TABLE, $collumna))
				{
					$error .= sprintf($user->lang['AFL_INSTALL_NO_COLLUMN'] . '<br />', $collumna, USERS_TABLE);
				}
			}
			foreach ( $collumnlistb as $key => $collumnb )
			{
				if (!$afl_db->sql_column_exists(AFL_EXCLUDE_IPS_TABLE, $collumnb))
				{
					$error .= sprintf($user->lang['AFL_INSTALL_NO_COLLUMN'] . '<br />', $collumnb, AFL_EXCLUDE_IPS_TABLE);
				}
			}
			if ( $error )
			{
				$error .= $user->lang['ACP_AFL_ERR_INSTALL'];
			}
			else
			{
				set_config('afl_cache_time', $now + 3600);//cache one hour...
			}
			return $error;
		}
	}
}
?>