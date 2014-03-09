<?php
/**
*
* @package Class Structure function_alert_for_login.php
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

define('IN_AFL', true);//To developper: Can be used if you want know if this file is already included or not in your code...
define('AFL_SECURED', 'http://geolim4.com');//Be honest bro, if you wont use this feature, it can be disabled from ACP, but if you want keep it, i'll realy appreciate ;)
define('AFL_EXTERNAL', ' onclick="window.open(this.href); return false;" ');

define('AFL_TYPE_PM', 'pm');
define('AFL_TYPE_MAIL', 'mail');

define('AFL_AUTH_DB', 'db');
define('AFL_AUTH_LDAP', 'ldap');
define('AFL_AUTH_APACHE', 'apache');

define('AFL_USER_FOUNDER', 1);
define('AFL_USER_NORMAL', 0);

define('AFL_TOPIC_LANGUAGE_USER', 'user');
define('AFL_TOPIC_LANGUAGE_BOARD', 'board');

define('AFL_FAIL_UCP', 'fail_ucp');
define('AFL_FAIL_ACP', 'fail_acp');
define('AFL_SUCCESS_UCP', 'success_ucp');
define('AFL_SUCCESS_ACP', 'success_acp');
/**
* afl class structure
*/
class afl
{
	private $afl_uid = 0;
	private $afl_uname = '';
	private $afl_colour = '';
	private $config = array();
	private $config_poster = array();
	private $uid_loop = array();

	/**
	* Mods Method(19):
	* @ __construct()					=> Construct some datas														@ Access public
	* @ config()						=> Return our own config method												@ Access private
	* @ __toString()					=> Return an array of the current config if $afl is treated as a string
															with mod like Prime Login via email and others...		@ Access public
	* @ trigger_index()					=> Hook the index to show an example to the admin from ACP					@ Access public
	* @ get_founders()					=> Here we get founder if neeeded											@ Access private
	* @ sql_alter_query()				=> Here we alter the query to grab required datas for our MOD... 			@ Access public
	* @ afl_fail_pass()					=> New function for reduce code added in auth_db.php (fail pass) 			@ Access public
	* @ afl_captcha_pass				=> New function for reduce code added in auth_db.php (captcha pass)			@ Access public
	* @ afl_success_pass				=> New function for reduce code added in auth_db.php (success pass)			@ Access public
	* @ afl_hook_posting				=> Function for hook posting and avoid anonymous posting...					@ Access public
	* @ afl_get_excludes_ip()			=> create a list of excluded IP (for all)									@ Access public
	* @ afl_pm() 						=> our own function for send pm and avoid to repeat code each time			@ Access private
	* @ afl_mail()						=> our own function for send mail and avoid to repeat code each time 		@ Access private
	* @ afl_post()						=> our own function for create new topic and avoid to repeat code each time @ Access private
	* @ alert_fail_login() 				=> fail connection alert (to ucp)											@ Access public
	* @ alert_success_login() 			=> success connection alert (to ucp)										@ Access public
	* @ alert_fail_login_acp() 			=> fail connection alert (to acp)											@ Access public
	* @ alert_success_login_acp()		=> success connection alert (to acp)										@ Access public
	* @ afl_hook_secured()				=> Function destined to inform user than the login is secured by AFL !		@ Access private
	* @ afl_post_compatibilty()			=> make the post compatible with the most part of DB mods of.Com which 		@ Access private
	* 														add additional required collumn in post/topic table.
	*/

	/**
	* AFL Constructor
	* Construct some datas
	* @noparam
	*/
	public function __construct()
	{
		global $config;
		$this->config($config);
	}

	/**
	* AFL config
	* Return our own config method
	* @param array $config passed throught constructor
	*/
	private function config($config)
	{
		foreach($config as $config_key => $config_value)
		{
			$this->config[$config_key] = $config_value;
		}
	}

	/**
	* AFL __toString Magic method
	* Return an array of the current config if $afl is treated as a string with mod like Prime Login via email and others...
	* @noparam
	*/
	public function __toString()
	{
		global $config;
		return (sizeof($this->config) ? $this->config : $this->config($config));
	}
	/**
	* AFL trigger_index
	* Hook the index to show an example to the admin from ACP
	* @noparam
	*/
	public function trigger_index()
	{
		global $user, $phpbb_root_path, $phpEx, $auth;
		$expe = request_var('afl_hook_example', 0);
		if ($expe && $auth->acl_get('a_') )
		{
			$user->add_lang(array('mods/info_ucp_alert_for_login', 'ucp'));
			$tag_path = $phpbb_root_path . 'images/afl/' . $user->data['user_lang'] . '/afl.png';
			$image = '<img src="' . $tag_path . '" alt="' . $user->lang['AFL_FIELDSET_TITLE'] . '" style="float: right;" />';
			meta_refresh(8, "{$phpbb_root_path}index.$phpEx");
			trigger_error($user->lang['LOGIN_REDIRECT'] . '<br /><br />'. $user->lang('RETURN_INDEX', '<a href="' . "{$phpbb_root_path}index.$phpEx" . '">', '</a>') . '<br />' . '<a' . AFL_EXTERNAL . ' href="' . AFL_SECURED . '">' . $image . '</a>' . '<br />' );
		}
	}
	/**
	* AFL get_founders
	* Here we get founder if neeeded
	* @noparam
	*/
	private function get_founders()
	{
		global $db;

		//Get ALL founder (Included virtual founder and groups considered as founders)
		$where_stmt = $db->sql_in_set('u.user_id', unserialize($this->config['afl_founders_exclude']), true, true) . ' AND (u.user_type = ' . USER_FOUNDER . ' OR u.user_afl_founder = ' . AFL_USER_FOUNDER . ')';
		if (sizeof(unserialize($this->config['afl_groups_include'])) )
		{
			$where_stmt = $db->sql_in_set('u.user_id', unserialize($this->config['afl_founders_exclude']), true, true) . ' AND ( (u.user_type = ' . USER_FOUNDER . ' OR u.user_afl_founder = ' . AFL_USER_FOUNDER . ') OR u.user_id = g.user_id ) AND ' . $db->sql_in_set('g.group_id', unserialize($this->config['afl_groups_include']), false, true);
		}
		$sql_array = array(
				
			'SELECT'	=> 'u.user_id, u.group_id, u.username, u.user_email, u.user_lang, u.user_dateformat, u.user_afl_success, u.user_afl_fail, u.user_afl_type',
			
			'FROM'		=>	array(USERS_TABLE => 'u'),

			'WHERE'		=> $where_stmt,//Don't include pending users...
		);
		if (unserialize($this->config['afl_groups_include']) )
		{
			$sql_array['LEFT_JOIN'] = array(
				array(
					'FROM'	=> array(USER_GROUP_TABLE => 'g'),
					'ON'	=> ' g.user_pending = 0 ',//Do not include user pending joining this group!!
				),
			);
			$sql_array['SELECT'] .= ', g.user_id AS g_user_id, g.group_id AS g_group_id';
		}
		$sql = $db->sql_build_query('SELECT', $sql_array);
		return $sql;
	}

	/**
	* AFL sql_alter_query
	* Here we alter the query to grab required datas for our MOD...
	* @param string $sql query to alter
	* @pass ref string
	*/
	public function sql_alter_query( &$sql )
	{
		//construct a switch to be compatible in the future !!
		switch ( $this->config['auth_method'] )
		{
			case AFL_AUTH_DB:
				$sql = str_replace('user_login_attempts', 'group_id, user_colour, user_dateformat, user_login_attempts, user_lang, user_afl_success, user_afl_fail, user_afl_type, user_afl_limit, user_afl_founder', $sql);
			break;
			
			case AFL_AUTH_LDAP:
				$sql = str_replace('user_type', 'user_type, group_id, user_colour, user_dateformat, user_login_attempts, user_lang, user_afl_success, user_afl_fail, user_afl_type, user_afl_limit, user_afl_founder', $sql);
			break;
			//not for now :)
/* 			case AFL_AUTH_APACHE;
			
			break; */
		}
	}

	/**
	* AFL fail pass
	* New function for reduce code added in auth_db.php (fail pass)
	* @param string $password password to check in phpbb_check_hash()
	* @param array $row user data to check
	* @param array $this->config, $this->config to pass, because here we can't call $this->config as global if them are modified behind in auth.php
	* @return bool
	*/
	public function afl_fail_pass($password, $row, $config)
	{
		//$config are passed an arg because we don't know if the excluded IP match in auth_db.php
		if ((isset($config['afl_mod_enabled']) && $config['afl_mod_enabled'])
					&& !phpbb_check_hash($password, $row['user_password'])
					&& ( $config['allow_privmsg'] || $config['email_enable'] || $config['afl_fail_topics'] )
					&& $config['afl_maxlogin_bypass']
					&& (($row['user_afl_limit'] <= $config['afl_alert_limit']) || empty($config['afl_alert_limit']))
					&& $row['user_type'] != USER_IGNORE)
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	/**
	* AFL captcha pass
	* New function for reduce code added in auth_db.php (captcha pass)
	* @param string $password password to check in phpbb_check_hash()
	* @param array $row user data to check
	* @param array $this->config, $this->config to pass, because here we can't call $this->config as global if them are modified behind in auth.php
	* @return bool
	*/
	public function afl_captcha_pass($password, $row, $config)
	{
		//$config are passed an arg because we don't know if the excluded IP match in auth_db.php
		if ((isset($config['afl_mod_enabled']) && $config['afl_mod_enabled'])
				&& !phpbb_check_hash($password, $row['user_password'])
				&& ( $config['allow_privmsg'] || $config['email_enable'] || $config['afl_fail_topics'] )
				&& empty($config['afl_maxlogin_bypass'])
				&& (($row['user_afl_limit'] <= $config['afl_alert_limit']) || empty($config['afl_alert_limit']))
				&& $row['user_type'] != USER_IGNORE )
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	/**
	* AFL success pass
	* New function for reduce code added in auth_db.php (success pass)
	* @param array $row user data to check
	* @param array $this->config, $this->config to pass, because here we can't call $this->config as global if them are modified behind in auth.php
	* @return bool
	*/
	public function afl_success_pass($row, $config)
	{
		//$config are passed an arg because we don't know if the excluded IP match in auth_db.php
		if ( ( $config['email_enable']|| $config['afl_success_topics'] )
			&& ( isset($config['afl_mod_enabled'] )
			&& $config['afl_mod_enabled'])
			&& ((isset($config['afl_ucp_success']) && $config['afl_ucp_success']) || (isset($config['afl_acp_success']) && $config['afl_acp_success']))
			&& !in_array($row['group_id'], unserialize($config['afl_groups_exclude']))
			&& $row['user_type'] != USER_IGNORE )
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	/**
	* AFL hook posting
	* Function for hook posting and avoid anonymous posting...
	* @param array $sql_data original array from function_posting altered with our data
	* @param const $type type of data we'll alter
	* return altered data
	*/
	public function afl_hook_posting($sql_data, $type)
	{	
		global $db;
		if ($type == TOPICS_TABLE )
		{
			$sql_data[POSTS_TABLE]['sql']['poster_id']					= (int) $this->afl_uid;
			$sql_data[TOPICS_TABLE]['sql']['topic_poster']				= $sql_data[TOPICS_TABLE]['sql']['topic_last_poster_id']		= $this->afl_uid;
			$sql_data[TOPICS_TABLE]['sql']['topic_first_poster_colour']	= $sql_data[TOPICS_TABLE]['sql']['topic_last_poster_colour']	= $db->sql_escape($this->afl_colour);
			$sql_data[TOPICS_TABLE]['sql']['topic_last_poster_name']	= $sql_data[TOPICS_TABLE]['sql']['topic_first_poster_name']		= $db->sql_escape($this->afl_uname);
			if ($this->config['afl_lock_topic'] )
			{
				$sql_data[TOPICS_TABLE]['sql']['topic_status'] = ITEM_LOCKED;
			}
			if ($this->config['afl_poster_icon_id'] )
			{
				$sql_data[TOPICS_TABLE]['sql']['icon_id'] = (int) $this->config['afl_poster_icon_id'];
			}
		}
		else if ($type == FORUMS_TABLE )
		{
			$sql_data[TOPICS_TABLE]['stat'][] = 'topic_last_poster_id = ' . (int) $this->afl_uid;
			$sql_data[TOPICS_TABLE]['stat'][] = "topic_last_poster_name = '" . $db->sql_escape($this->afl_uname) . "'";
			$sql_data[TOPICS_TABLE]['stat'][] = "topic_last_poster_colour = '" . $db->sql_escape($this->afl_colour) . "'";
			$sql_data[FORUMS_TABLE]['stat'][] = 'forum_last_poster_id = ' . (int) $this->afl_uid;
			$sql_data[FORUMS_TABLE]['stat'][] = "forum_last_poster_name = '" . $db->sql_escape($this->afl_uname) . "'";
			$sql_data[FORUMS_TABLE]['stat'][] = "forum_last_poster_colour = '" . $db->sql_escape($this->afl_colour) . "'";
			if ($this->config['afl_lock_topic'] )
			{
				$sql_data[TOPICS_TABLE]['stat'][] = 'topic_status = ' . ITEM_LOCKED;
			}
			if ($this->config['afl_poster_icon_id'] )
			{
				$sql_data[TOPICS_TABLE]['sql'][] = 'icon_id = ' . (int) $this->config['afl_poster_icon_id'];
			}
		}
		return $sql_data;
	}

	/**
	* AFL get excludes ip
	* Function destined to create a list of exluded if of Alert(success/fail && ucp/acp)
	* @param strinf $ip used to compare excludes IP from table to current IP
	* return false if IP match with one IP in excluded table, else return true
	*/
	public function afl_get_excludes_ip($ip)
	{
		global $cache, $db;

		if (($excludes_ip = $cache->get('_excludes_ip')) === false)
		{
			$excludes_ip = array();
			$sql = 'SELECT exclude_ip
				FROM ' . AFL_EXCLUDE_IPS_TABLE;
			$result = $db->sql_query($sql);
			while ($row = $db->sql_fetchrow($result))
			{
				$excludes_ip = array_merge($excludes_ip, array($row['exclude_ip']));
			}
			$db->sql_freeresult($result);
			$excludes_ip = serialize($excludes_ip);
			$cache->put('_excludes_ip', $excludes_ip, 1296000);//Keep it frozen for one month :mrgreen: We destroy it in ACP if updated...
		}
		$array_ip = unserialize($excludes_ip);
		if (in_array($ip, $array_ip))
		{
			unset($array_ip, $excludes_ip);//Safety reasons...
			return false;//Ip excluded, don't sent any alert....
		}
		else
		{
			unset($array_ip, $excludes_ip);//Safety reasons...
			return true;//No ip excluded...
		}
	}
	/**
	* AFL PM
	* Function destined to reduce the code in the function file
	* @param array $afl_pm_data assign data required for this function
	*/
	private function afl_pm($afl_pm_data)
	{
		global $user, $phpbb_root_path, $phpEx;
		//function submit_pm already exist or not? Check it now !!!
		if (!function_exists('submit_pm'))
		{
			include($phpbb_root_path . 'includes/functions_privmsgs.' . $phpEx);
		}
		//Thanks DoYouSpeakWak for that :)
		if (!sizeof($afl_pm_data['pm_adress_list']) )
		{
			return;
		}
		$uid = $bitfield = $options = '';
		$allow_bbcode = $allow_smilies = $allow_urls = true;
		$subject = $afl_pm_data['pm_sub_lang'];

		//$afl_pm_data['pm_mess_lang'] has been already passed trought sprintf()
		$message = generate_text_for_display($afl_pm_data['pm_mess_lang'], $uid, $bitfield, $options);
		generate_text_for_storage($message, $uid, $bitfield, $options, $allow_bbcode, $allow_urls, $allow_smilies);
		//Ok get data now...
		$pm_data = array(
			'from_user_id'		=> isset($this->config['afl_bot_id']) ? (int) $this->config['afl_bot_id'] : $user->data['user_id'],
			'from_user_ip'		=> $afl_pm_data['pm_mess_attempt_ip'],
			'from_username'		=> isset($this->config['afl_bot_username']) ? (string) $this->config['afl_bot_username'] : $user->data['username'],//Will not be show
			'enable_sig'		=> true,
			'enable_bbcode'		=> $allow_bbcode,
			'enable_smilies'	=> $allow_smilies,
			'enable_urls'		=> $allow_urls,
			'icon_id'			=> $this->config['afl_pm_icon_id'],
			'bbcode_bitfield'	=> $bitfield,
			'bbcode_uid'		=> $uid,
			'message'			=> $message,
			'address_list'		=> array('u' => array($afl_pm_data['pm_adress_list'] => 'to')),
		);
		submit_pm('post', $subject, $pm_data, false, false); //We set false for evoid to keep a full sentbox from the BOT expeditor

		//increment stats....
		if ($this->config['afl_stats_enabled'])
		{
			set_config_count('afl_stats_pm', 1, false);
		}
	 //END PM Step
	}
	/**
	* AFL MAIL
	* Function destined to reduce the code in the function file
	* @param array $afl_mail_data assign data required for this function
	*/
	private function afl_mail($afl_mail_data)
	{
		global $user, $phpbb_root_path, $phpEx;
		//Mail function, Mail enabled for the Mod and template file exist?
		if (file_exists($phpbb_root_path . 'language/' .$afl_mail_data['user_lang']. '/email/' . $afl_mail_data['email_template'] . '.txt'))
		{
			$email_template = $afl_mail_data['email_template'];
			if (!class_exists('messenger'))
			{
				include($phpbb_root_path . 'includes/functions_messenger.' . $phpEx);
			}
			$messenger = new messenger(false);

			$messenger->template($email_template, $afl_mail_data['user_lang']);

			$messenger->to($afl_mail_data['user_email'], $afl_mail_data['username']);

			$messenger->anti_abuse_headers($this->config, $user);
			$messenger->assign_vars(array(
				'BOARD_CONTACT'			=> htmlspecialchars_decode($this->config['board_email']),
				'USERNAME'				=> htmlspecialchars_decode($afl_mail_data['username']),
				'SITENAME'				=> htmlspecialchars_decode($this->config['sitename']),
				'EMAIL_SIG'				=> htmlspecialchars_decode($this->config['board_email_sig']),
				'ATTEMPT_IP'			=> $afl_mail_data['attempt_ip'],
				'ATTEMPT_BROWSER'		=> $afl_mail_data['attempt_browser'],
				'IP_LOCATION'			=> str_replace('{USER_IP}', $afl_mail_data['attempt_ip'], isset($this->config['afl_whois_url']) ? (string) $this->config['afl_whois_url'] : ''),
				'USER_LOGIN_ATTEMPTS'	=> $afl_mail_data['count_attempt'],
				'LOGGED_IN_TIME'		=> $afl_mail_data['logged_in_time'],
				'LOG_SUCCESS_IP'		=> $afl_mail_data['log_success_ip'],
				'ATTEMPT_USERNAME'		=> $afl_mail_data['attempt_username'],
				'USERNAME_LOGGED'		=> $afl_mail_data['username_logged'],
				'AFL_PWD_ATTEMPTED'		=> $afl_mail_data['attempt_password'],
				'DEACTIVATE_SETTING'	=> generate_board_url() . "/ucp.$phpEx?i=prefs&mode=personal" //For email i need to use & and not &amp because this a brut text....
			));
			// Mail ready, go go go, alert user fast, his account may be compromised!!!
			$messenger->send(NOTIFY_EMAIL);

			//increment stats....
			if ($this->config['afl_stats_enabled'])
			{
				set_config_count('afl_stats_mail', 1, false);
			}
		} //END MAIL Step
	}
	/**
	* AFL POST
	* Function destined to reduce the code in the function file
	* @param array $afl_post_data assign data required for this function
	* @param string $login_type type of login we'll post
	*/
	private function afl_post($afl_post_data, $login_type)
	{
		define('IN_AFL_POSTING', true);
		
		global $user, $db, $phpbb_root_path, $phpEx;
		$user->add_lang('mods/info_ucp_alert_for_login');
		if (!file_exists($phpbb_root_path . 'language/' . ( ($this->config['afl_language_topic'] == AFL_TOPIC_LANGUAGE_USER) ? strtolower($user->data['user_lang']) : strtolower($this->config['default_lang']) ) . '/mods/info_ucp_alert_for_login.'  . $phpEx) )
		{
			return;
		}
		else
		{
			include($phpbb_root_path . 'language/' . ( ($this->config['afl_language_topic'] == AFL_TOPIC_LANGUAGE_USER) ? strtolower($user->data['user_lang']) : strtolower($this->config['default_lang']) ) . '/mods/info_ucp_alert_for_login.'  . $phpEx);
		}
		$data = array();
		$subject = $message = '';
		$update_subject = $update_message = false;

		$this->config_poster = unserialize($this->config['afl_poster_id']);

		if (!function_exists('submit_post') )
		{
			include($phpbb_root_path . 'includes/functions_posting.' . $phpEx);
		}
		if (!class_exists('parse_message') )
		{
			include($phpbb_root_path . 'includes/message_parser.' . $phpEx);
		}

		switch ( $login_type )
		{
			//UCP
			case AFL_FAIL_UCP:

				$subject = sprintf($lang['AFL_POST_LOGIN_FAIL_TITLE'], $afl_post_data['count_attempts']);
				$message = sprintf($lang['AFL_POST_LOGIN_FAIL'], $afl_post_data['username'], $afl_post_data['attempt_ip'], generate_board_url() . "/ucp.$phpEx?i=pm&amp;mode=compose&u=" . $afl_post_data['user_id'], $user->format_date(time(), $afl_post_data['user_dateformat']), $afl_post_data['count_attempts'], str_replace('{USER_IP}', $afl_post_data['attempt_ip'], isset($this->config['afl_whois_url']) ? (string) $this->config['afl_whois_url'] : ''), $afl_post_data['attempt_browser']);

			break;

			case AFL_SUCCESS_UCP:

				$subject = $lang['AFL_POST_LOGIN_SUCCESS_TITLE'];
				$message = sprintf($lang['AFL_POST_LOGIN_SUCCESS'], $afl_post_data['username'], $afl_post_data['attempt_ip'], $user->format_date(time(), $afl_post_data['user_dateformat']), str_replace('{USER_IP}', $afl_post_data['attempt_ip'], isset($this->config['afl_whois_url']) ? (string) $this->config['afl_whois_url'] : ''), $afl_post_data['attempt_browser']);

			break;
			//ACP
			case AFL_FAIL_ACP:

				$subject = $lang['AFL_POST_LOGIN_FAIL_ACP_TITLE'];
				$message = sprintf($lang['AFL_POST_LOGIN_FAIL_ACP'], $afl_post_data['username'], $afl_post_data['attempt_ip'], generate_board_url() . "/ucp.$phpEx?i=pm&amp;mode=compose&u=" . $afl_post_data['user_id'], $user->format_date(time(), $afl_post_data['user_dateformat']), str_replace('{USER_IP}', $afl_post_data['attempt_ip'], isset($this->config['afl_whois_url']) ? (string) $this->config['afl_whois_url'] : ''), $afl_post_data['attempt_browser']);

			break;

			case AFL_SUCCESS_ACP:

				$subject = $lang['AFL_POST_LOGIN_SUCCESS_ACP_TITLE'];
				$message = sprintf($lang['AFL_POST_LOGIN_SUCCESS_ACP'], $afl_post_data['username'], $afl_post_data['attempt_ip'], $user->format_date(time(), $afl_post_data['user_dateformat']), str_replace('{USER_IP}', $afl_post_data['attempt_ip'], isset($this->config['afl_whois_url']) ? (string) $this->config['afl_whois_url'] : ''), $afl_post_data['attempt_browser']);

			break;
		}

		$message_parser = new parse_message();
		$message_parser->message = $message;
		$message_parser->parse(true, true, true, true, false, true, true);

		$message_md5 = md5($message_parser->message);

		$sql = 'SELECT forum_name
			FROM ' . FORUMS_TABLE . '
			WHERE forum_id = ' . (int) $this->config['afl_forum_target'];
		$result = $db->sql_query_limit($sql, 1);
		$f_name = $db->sql_fetchfield('forum_name');
		$db->sql_freeresult($result);

		$data = array(
			'post_id'			=> 0,//Compatibility with some mods which can return a php notice...
			'post_subject'		=> $subject,
			'forum_id'			=> (int) $this->config['afl_forum_target'],
			'icon_id'			=> $this->config['afl_poster_icon_id'],

			'enable_sig'		=> true,
			'enable_bbcode'		=> true,
			'enable_smilies'	=> true,
			'enable_urls'		=> true,

			'message_md5'		=> (string) $message_md5,
			'bbcode_bitfield'	=> $message_parser->bbcode_bitfield,
			'bbcode_uid'		=> $message_parser->bbcode_uid,
			'message'			=> $message_parser->message,
			'attachment_data'	=> $message_parser->attachment_data,
			'filename_data'		=> $message_parser->filename_data,
			'poster_ip'			=> $user->ip,

			'post_edit_locked'	=> 0,
			'topic_title'		=> $subject,
			'notify_set'		=> false,
			'notify'			=> false,
			'post_time'			=> time(),
			'forum_name'		=> $f_name,
			'enable_indexing'	=> true,
			'topic_status'		=> 0,
			'force_approved_state'	=> true,

		);
		$this->afl_post_compatibilty($data);
		$pool = array();
		$user_mention_ary = array();
		$user_mention_ary[] = '';
		$user_mention_ary = array_unique($user_mention_ary);
		//			 Mode	Subject					Username				  Topic Type	Pool    Data  Updt msg		Update message/subject bool
		submit_post('post', $data['post_subject'], ($this->config['afl_poster_owner'] ? $afl_post_data['username'] : $this->config_poster['username']), POST_NORMAL, $pool, $data, '', ($update_message || $update_subject) ? true : false, $user_mention_ary);
		//increment stats....
		if ($this->config['afl_stats_enabled'] )
		{
			set_config_count('afl_stats_topics', 1, false);
		}
	}
	/**
	* ALERT_FAIL_LOGIN
	* Function destined to alert user and/or founder when multiple logins to an account has failed
	* @param array $attempt_data contain major information about the failed attempt
	* @param array $tow contain all required informations for the attempted user
	* @param string $password contain UNENCRYPTED password destinated only to the user attempted for allow him to see the failed pwd attempted.
	*/

	public function alert_fail_login($attempt_data, $row, $password)
	{
		if (empty($row['user_lang']) )
		{
			return;
		}
		global $db, $user, $phpbb_root_path, $phpEx;

		$this->config_poster = unserialize($this->config['afl_poster_id']);

		$this->afl_uid		= ($this->config['afl_poster_owner'] ? $attempt_data['user_id']	: $this->config_poster['user_id']);
		$this->afl_uname	= ($this->config['afl_poster_owner'] ? $row['username']			: $this->config_poster['username']);
		$this->afl_colour	= ($this->config['afl_poster_owner'] ? $row['user_colour']		: $this->config_poster['user_colour']);

		include($phpbb_root_path . 'language/' . $row['user_lang'] . '/mods/info_ucp_alert_for_login.' . $phpEx);//(1)readme please
		if ($this->config['afl_maxlogin_bypass'] )
		{
			//Get logins attempt on this user
			$sql = 'SELECT COUNT(user_id) AS count_attempts
				FROM ' . LOGIN_ATTEMPT_TABLE . "
				WHERE user_id =" . (int) $attempt_data['user_id'];
			$result = $db->sql_query($sql);
			$count_attempts = $db->sql_fetchrow($result);
			$db->sql_freeresult($result);
			//If we've selected our own count of failed login so count them !!
			if (($count_attempts['count_attempts'] < $this->config['afl_login_count']) )
			{
				return;
			}
		}
		//Many query coming soon, keep alive !! (Closed before function end)
		$db->sql_transaction('begin');

	/***********************************************
	***								<<<<<<<<<<<<////
	**	Alert USER Step				<<<<<<<<<<<<////
	***								<<<<<<<<<<<<////
	************************************************/
	//==>MAIL Step
		if ($this->config['email_enable'] && $this->config['afl_ucp_fail'] && $this->config['afl_mail_alert'] &&  ($row['user_afl_fail'] || $this->config['afl_force_alert'])  && $row['user_afl_type'] == AFL_TYPE_MAIL && !in_array($row['group_id'], unserialize($this->config['afl_groups_exclude']))) //Mail function and Mail enabled for user in this Mod ?
		{
			$afl_mail_data = array(
				'email_template'	=> 'alert_for_fail_login',
				'user_lang'			=> $row['user_lang'],
				'username'			=> $row['username'],
				'user_email'		=> $row['user_email'],
				'attempt_ip'		=> $attempt_data['attempt_ip'],
				'attempt_browser'	=> $attempt_data['attempt_browser'],
				'count_attempt'		=> isset($count_attempts['count_attempts']) ? $count_attempts['count_attempts'] : 0, //Not used for alert user but founder....
				'attempt_username'	=> $attempt_data['username'], //Not used for alert user but founder....
				'logged_in_time'	=> $user->format_date(time(), $row['user_dateformat']),//GET individual date format....
				'log_success_ip'	=> $attempt_data['attempt_ip'],//Used for success login
				'username_logged'	=> '',//Not user for alert user but founder....
				'attempt_password'	=> $this->config['afl_mail_pwd'] ? sprintf($lang['AFL_EMAIL_PWD'], $password) : '' //Used only for user attempted
			);

			//Our customized function for sent new mail
			$this->afl_mail($afl_mail_data);
		} //END MAIL Step

		//==>PM Step
		if ($this->config['afl_pm_alert'] && $this->config['allow_privmsg'] && ($row['user_afl_fail'] || $this->config['afl_force_alert']) && $row['user_afl_type'] == AFL_TYPE_PM ) //If PM config turned on and PM enabled for the MOD
		{
			include($phpbb_root_path . 'language/' . $row['user_lang'] . '/mods/info_ucp_alert_for_login.' . $phpEx);//(1)readme please
			$afl_pm_data = array(
				'pm_sub_lang'			=> $lang['AFLF_WARNING_LOGIN_ATTEMPT_PM'],
				'pm_mess_lang'			=> sprintf($lang['AFLF_WARNING_LOGIN_ATTEMPT_PM_TEXT'], $row['username'], $attempt_data['attempt_ip'], htmlspecialchars_decode($this->config['board_email']), $count_attempts['count_attempts'], str_replace('{USER_IP}', $attempt_data['attempt_ip'], isset($this->config['afl_whois_url']) ? (string) $this->config['afl_whois_url'] : ''), $attempt_data['attempt_browser'], $user->format_date(time(), $row['user_dateformat'])),
				'pm_mess_attempt_ip'	=> $attempt_data['attempt_ip'],
				'pm_adress_list'		=> $attempt_data['user_id']
			);
			$this->afl_pm($afl_pm_data);
		} //END PM Step
	/***********************************************
	***								<<<<<<<<<<<<////
	**	Alert FOUNDER Step			<<<<<<<<<<<<////
	***								<<<<<<<<<<<<////
	************************************************/
		if ($this->config['afl_alert_founder_ucp']) //Check if founder should be alerted for fail login on user's account
		{

			$result = $db->sql_query($this->get_founders());

			if (!function_exists('submit_pm'))
			{
				include($phpbb_root_path . 'includes/functions_privmsgs.' . $phpEx);
			}
			//function submit_pm already declared or not? Check it now !!!
			while ( $f_row = $db->sql_fetchrow($result) )
			{
				if (in_array($f_row['user_id'], $this->uid_loop) )
				{
					//extra security measure: here we avoid any duplicate email/pm
					continue;
				}
				if (in_array($f_row['group_id'], unserialize($this->config['afl_groups_exclude'])) )
				{
					//default group of this user is excluded from alert, continue...
					continue;
				}
	//==>MAIL founder Step
				if ($this->config['email_enable'] && $this->config['afl_mail_alert'] && ($attempt_data['user_id'] != $f_row['user_id']) && $f_row['user_afl_type'] == AFL_TYPE_MAIL ) //We do not need to send again a MAIL if the user Attempted is founder :)
				{
					$afl_mail_data = array(
						'email_template'	=> 'alert_for_fail_login_founder',
						'user_lang'			=> $f_row['user_lang'],
						'username'			=> $f_row['username'],
						'user_email'		=> $f_row['user_email'],
						'attempt_ip'		=> $attempt_data['attempt_ip'],
						'attempt_browser'	=> $attempt_data['attempt_browser'],
						'count_attempt'		=> isset($count_attempts['count_attempts']) ? $count_attempts['count_attempts'] : 0, //Not used for alert user but founder....
						'attempt_username'	=> $row['username'], //Not used for alert user but founder....
						'logged_in_time'	=> $user->format_date(time(), $f_row['user_dateformat']),//GET individual date format....
						'log_success_ip'	=> $attempt_data['attempt_ip'],//Used for success login
						'username_logged'	=> '',//Not user for alert user but founder....
						'attempt_password'	=> '' //Used only for user attempted
					);
					//Our customized function for sent new mail
					$this->afl_mail($afl_mail_data);
				} //END MAIL founder Step
				//==>PM founder Step
				if ($attempt_data['user_id'] != $f_row['user_id'] && $f_row['user_afl_type'] == AFL_TYPE_PM ) //If PM turned on for this Mod and one more time we do not need to send again a MAIL to himself if the user Attempted is founder :)
				{
					include($phpbb_root_path . 'language/' . $f_row['user_lang'] . '/mods/info_ucp_alert_for_login.' . $phpEx);//(1)readme please
					$afl_pm_data = array(
						'pm_sub_lang'			=> $lang['AFLF_FOUNDER_ALERT_PM'],
						'pm_mess_lang'			=> sprintf($lang['AFLF_FOUNDER_ALERT_PM_TEXT'], $f_row['username'], $row['username'], $attempt_data['attempt_ip'], $count_attempts['count_attempts'], str_replace('{USER_IP}', $attempt_data['attempt_ip'], isset($this->config['afl_whois_url']) ? (string) $this->config['afl_whois_url'] : ''), $attempt_data['attempt_browser'], $user->format_date(time(), $f_row['user_dateformat'])),
						'pm_mess_attempt_ip'	=> $attempt_data['attempt_ip'],
						'pm_adress_list'		=> $f_row['user_id']
					);

					if ($this->config['afl_pm_alert'] && $this->config['allow_privmsg'])
					{
						$this->afl_pm($afl_pm_data);
					}
				} //END PM founder Step
				$this->uid_loop[] = $f_row['user_id'];
			}//End while round...
			$db->sql_freeresult($result);
		}
		if (!empty($this->config['afl_alert_limit']) && $this->config['auth_method'] == AFL_AUTH_DB )
		{
			// Password incorrect - increase user_afl_limit
			$sql = 'UPDATE ' . USERS_TABLE . '
				SET user_afl_limit = user_afl_limit + 1
				WHERE user_id = ' . (int) $row['user_id'];
			$db->sql_query($sql);
		}

		if ($this->config['afl_stats_enabled'])
		{
			set_config_count('afl_stats_fail', 1, false);
		}
		if ($this->config['afl_fail_topics'] )
		{
			$afl_post_data = array(
				'user_dateformat'	=> $row['user_dateformat'],
				'username'			=> $row['username'],
				'user_id'			=> $row['user_id'],
				'count_attempts'	=> $count_attempts['count_attempts'],
				'attempt_ip'		=> $attempt_data['attempt_ip'],
				'attempt_browser'	=> $attempt_data['attempt_browser'],
			);
			$this->afl_post($afl_post_data, AFL_FAIL_UCP);
		}
		//close it !
		$db->sql_transaction('commit');
		if ($this->config['afl_hook_login'] )
		{
			$this->afl_hook_secured('afl_f');
		}
	} //Function END

	/**
	* ALERT_FAIL_LOGIN_ACP
	* Function destined to alert user and/or founder when multiple logins to an account has failed
	* @param array $attempt_data contain major information about the failed attempt
	* @param array $tow contain all required informations for the attempted user
	* @param string $password contain UNENCRYPTED password destinated only to the user attempted for allow him to see the failed pwd attepted.
	*/

	public function alert_fail_login_acp($attempt_data, $row, $password)
	{
		global $user, $phpbb_root_path, $db, $phpEx;

		$this->config_poster = unserialize($this->config['afl_poster_id']);
		$this->afl_uid		= ($this->config['afl_poster_owner'] ? $attempt_data['user_id']	: $this->config_poster['user_id']);
		$this->afl_uname	= ($this->config['afl_poster_owner'] ? $row['username']			: $this->config_poster['username']);
		$this->afl_colour	= ($this->config['afl_poster_owner'] ? $row['user_colour']		: $this->config_poster['user_colour']);

		if (( ( $this->config['email_enable'] && $this->config['afl_mail_alert'] ) || ( $this->config['allow_privmsg'] && $this->config['afl_pm_alert'] ) ) && $this->config['afl_acp_fail'])
		{
			if ($this->config['afl_maxlogin_bypass'] )
			{
				//Get logins attempt on this user
				$sql = 'SELECT COUNT(user_id) AS count_attempts
					FROM ' . LOGIN_ATTEMPT_TABLE . "
					WHERE user_id =" . (int) $attempt_data['user_id'];
				$result = $db->sql_query($sql);
				$count_attempts = $db->sql_fetchrow($result);
				$db->sql_freeresult($result);
				//If we've selected our own count of failed login so count them !!
				if (($count_attempts['count_attempts'] < $this->config['afl_login_count']) )
				{
					return;
				}
			}
			//Many query coming soon, keep alive !! (Closed before function end)
			$db->sql_transaction('begin');

//==>MAIL Step
			$result = $db->sql_query($this->get_founders());
			while ($f_row = $db->sql_fetchrow($result))
			{
				if (in_array($f_row['user_id'], $this->uid_loop) )
				{
					//extra security measure: here we avoid any duplicate email/pm
					continue;
				}
				if (in_array($f_row['group_id'], unserialize($this->config['afl_groups_exclude'])) )
				{
					//default group of this user is excluded from alert, continue...
					continue;
				}
	//==>Mail Step (founders)
				if ($f_row['user_id'] != $attempt_data['user_id']  && $f_row['user_afl_type'] == AFL_TYPE_MAIL )
				{
					$afl_mail_data = array(
						'email_template'	=> 'alert_for_fail_login_acp_founder',
						'user_lang'			=> $f_row['user_lang'],
						'username'			=> $f_row['username'],
						'user_email'		=> $f_row['user_email'],
						'attempt_ip'		=> $attempt_data['attempt_ip'],
						'attempt_browser'	=> $attempt_data['attempt_browser'], // Like db_auth.php trim & substr $browser thx ;)
						'count_attempt'		=> '', //Fail attempt in acp are not counted...
						'attempt_username'	=> $row['username'],
						'logged_in_time'	=> $user->format_date(time(), $f_row['user_dateformat']),//GET individual date format....
						'log_success_ip'	=> $attempt_data['attempt_ip'],//Used for success login
						'username_logged'	=> '',
						'attempt_password'	=> '' //Used only for user attempted
					);
					//Our customized function for sent new mail
					$this->afl_mail($afl_mail_data);
				}
	//==>Mail Step (Admin attempted)
				//We do not need to send again a MAIL if the user Attempted is founder :)
				else if (($f_row['user_id'] == $attempt_data['user_id']) && $row['user_afl_fail'] && $f_row['user_afl_type'] == AFL_TYPE_MAIL )
				{
					include($phpbb_root_path . 'language/' . $row['user_lang'] . '/mods/info_ucp_alert_for_login.' . $phpEx);//(1)readme please
					$afl_mail_data = array(
						'email_template'	=> 'alert_for_fail_login_acp',
						'user_lang'			=> $f_row['user_lang'],
						'username'			=> $f_row['username'],
						'user_email'		=> $f_row['user_email'],
						'attempt_ip'		=> $attempt_data['attempt_ip'],
						'attempt_browser'	=> $attempt_data['attempt_browser'],
						'count_attempt'		=> '', //Fail attempt in acp are not counted...
						'attempt_username'	=> $row['username'],
						'logged_in_time'	=> $user->format_date(time(), $f_row['user_dateformat']),//GET individual date format....
						'log_success_ip'	=> $attempt_data['attempt_ip'],//Used for success login
						'username_logged'	=> '',
						'attempt_password'	=> $this->config['afl_mail_pwd'] ? sprintf($lang['AFL_EMAIL_PWD'], $password) : '' //Used only for user attempted
					);
					//Our customized function for sent new mail
					$this->afl_mail($afl_mail_data);
				}
//==>PM Step (Founders)
				//If PM turned on for this Mod and one more time we do not need to send again a PM if the user Attempted is founder :)
				if ($attempt_data['user_id'] != $f_row['user_id'] && $f_row['user_afl_type'] == AFL_TYPE_PM )
				{
			//Do you speack $row['user_lang'] ? :D
			//Here i'll don't use $user->lang why? Because i need to get the specific language of ALL users (attempted user, and founders to alert)
					include($phpbb_root_path . 'language/' . $f_row['user_lang'] . '/mods/info_ucp_alert_for_login.' . $phpEx);//(1)readme please
					$afl_pm_data = array(
						'pm_sub_lang'			=> $lang['AFLF_ACP_FOUNDER_ALERT_PM'],
						'pm_mess_lang'			=> sprintf($lang['AFLF_ACP_FOUNDER_PM_TEXT'], $f_row['username'], $row['username'], $attempt_data['attempt_ip'], $user->format_date(time(), $f_row['user_dateformat']), str_replace('{USER_IP}', $attempt_data['attempt_ip'], isset($this->config['afl_whois_url']) ? (string) $this->config['afl_whois_url'] : ''), $attempt_data['attempt_browser']),
						'pm_mess_attempt_ip'	=> $attempt_data['attempt_ip'],
						'pm_adress_list'		=> $f_row['user_id']
					);
					$this->config['afl_pm_alert'] ? ($this->config['allow_privmsg'] ? $this->afl_pm($afl_pm_data) : '' ) : '' ;
				}//END PM Step
				$this->uid_loop[] = $f_row['user_id'];
			}//End while round
			$db->sql_freeresult($result);
			
		//==>PM Step (Admin attempted)
			if ($this->config['afl_pm_alert'] && $this->config['allow_privmsg'] && ($row['user_afl_fail'] || $this->config['afl_force_alert']) && $row['user_afl_type'] == AFL_TYPE_PM ) //If PM turned on for this Mod and one more time we do not need to send again a PM if the user Attempted is founder :)
			{
				include($phpbb_root_path . 'language/' . $row['user_lang'] . '/mods/info_ucp_alert_for_login.' . $phpEx);//(1)readme please
				$afl_pm_data = array(
					'pm_sub_lang'			=> $lang['AFLF_ACP_USER_ALERT_PM'],
					'pm_mess_lang'			=> sprintf($lang['AFLF_ACP_USER_PM_TEXT'], $row['username'], $attempt_data['attempt_ip'], $user->format_date(time(), false, true), str_replace('{USER_IP}', $attempt_data['attempt_ip'], isset($this->config['afl_whois_url']) ? (string) $this->config['afl_whois_url'] : ''), $attempt_data['attempt_browser']),
					'pm_mess_attempt_ip'	=> $attempt_data['attempt_ip'],
					'pm_adress_list'		=> $attempt_data['user_id']
				);
				$this->config['afl_pm_alert'] ? ($this->config['allow_privmsg'] ? $this->afl_pm($afl_pm_data) : '' ) : '' ;
			}//END PM Step
		}
		if (!empty($this->config['afl_alert_limit']) )
		{
			$afl_increment = 1;
			switch ($db->sql_layer)
			{
				case 'firebird':
					// Precision must be from 1 to 18
					$sql_update = 'CAST(CAST(user_afl_limit as DECIMAL(18, 0)) + ' . (int) $afl_increment . ' as MEDIUMINT(8))';
				break;

				case 'postgres':
					// Need to cast to text first for PostgreSQL 7.x
					$sql_update = 'CAST(CAST(user_afl_limit::text as DECIMAL(8, 0)) + ' . (int) $afl_increment . ' as MEDIUMINT(8))';
				break;

				// MySQL, SQlite, mssql, mssql_odbc, oracle
				default:
					$sql_update = 'user_afl_limit + ' . (int) $afl_increment;
				break;
			}

			$db->sql_query('UPDATE ' . USERS_TABLE . ' SET user_afl_limit = ' . $sql_update . ' WHERE user_id = ' . (int) $row['user_id']);
		}
		//increment stats....
		if ($this->config['afl_stats_enabled'])
		{
			set_config_count('afl_stats_fail_acp', 1, false);
		}
		if ($this->config['afl_fail_topics'] )
		{
			$afl_post_data = array(
				'user_dateformat'	=> $row['user_dateformat'],
				'username'			=> $row['username'],
				'user_id'			=> $attempt_data['user_id'],
				'count_attempt'		=> '',
				'attempt_ip'		=> $attempt_data['attempt_ip'],
				'attempt_browser'	=> $attempt_data['attempt_browser'],
			);
			$this->afl_post($afl_post_data, AFL_FAIL_ACP);
		}
		//close it !
		$db->sql_transaction('commit');
		if ($this->config['afl_hook_login'] )
		{
			$this->afl_hook_secured('afl_f_acp');
		}
	}//Function End

	/**
	* ALERT_SUCCESS_LOGIN
	* Function destined to alert user and/or founder when a succes login is make to an account
	* @param array $tow contain all required informations for the current logged user
	* @param string $browser for get the Browser User Agent
	* @param array $attempt_data contain major information about the current login of the user
	*/

	public function alert_success_login($row, $browser, $attempt_data)
	{
		global $user, $phpbb_root_path, $phpEx;

		$this->config_poster = unserialize($this->config['afl_poster_id']);

		$this->afl_uid		= ($this->config['afl_poster_owner'] ? $attempt_data['user_id']	: $this->config_poster['user_id']);
		$this->afl_uname	= ($this->config['afl_poster_owner'] ? $row['username']			: $this->config_poster['username']);
		$this->afl_colour	= ($this->config['afl_poster_owner'] ? $row['user_colour']		: $this->config_poster['user_colour']);
		if (( $row['user_afl_success'] || $this->config['afl_force_alert_succ'] ) && $row['user_afl_type'] == AFL_TYPE_MAIL)
		{
			$afl_mail_data = array(
				'email_template'	=> 'alert_for_success_login',
				'user_lang'			=> $row['user_lang'],
				'username'			=> $row['username'],
				'user_email'		=> $row['user_email'],
				'attempt_ip'		=> $attempt_data['attempt_ip'],
				'attempt_browser'	=> trim(substr($browser, 0, 149)), // Like db_auth.php trim & substr $browser thx ;)
				'count_attempt'		=> '', //Not used for alert user but founder....
				'attempt_username'	=> '', //Not used for alert user but founder....
				'logged_in_time'	=> $user->format_date(time(), false, true),
				'log_success_ip'	=> $attempt_data['attempt_ip'],//Used for success login
				'username_logged'	=> '',//Not user for alert user but founder....
				'attempt_password'	=> false //Used only for user attempted
			);
			//Our customized function for sent new mail
			$this->afl_mail($afl_mail_data);
			//increment stats....
			if ($this->config['afl_stats_enabled'])
			{
				set_config_count('afl_stats_success', 1, false);
			}
		}
		if (($row['user_afl_success'] || $this->config['afl_force_alert_succ']) && $row['user_afl_type'] == AFL_TYPE_PM) //If PM turned on for this Mod and one more time we do not need to send again a PM if the user Attempted is founder :)
		{
			include($phpbb_root_path . 'language/' . $row['user_lang'] . '/mods/info_ucp_alert_for_login.' . $phpEx);//(1)readme please
			$afl_pm_data = array(
				'pm_sub_lang'			=> $lang['AFL_UCP_USER_ALERT_SUCCESS'],
				'pm_mess_lang'			=> sprintf($lang['AFL_UCP_USER_SUCCESS_TEXT'], $row['username'], $attempt_data['attempt_ip'], $user->format_date(time(), false, true), str_replace('{USER_IP}', $attempt_data['attempt_ip'], isset($this->config['afl_whois_url']) ? (string) $this->config['afl_whois_url'] : ''), $attempt_data['attempt_browser']),
				'pm_mess_attempt_ip'	=> $attempt_data['attempt_ip'],
				'pm_adress_list'		=> $row['user_id']
			);
			$this->config['afl_pm_alert'] ? ($this->config['allow_privmsg'] ? $this->afl_pm($afl_pm_data) : '' ) : '' ;
		}//END PM Step
		if ($this->config['afl_success_topics'] )
		{
			$afl_post_data = array(
				'user_dateformat'	=> $row['user_dateformat'],
				'username'			=> $row['username'],
				'user_id'			=> $row['user_id'],
				'count_attempt'		=> '',
				'attempt_ip'		=> $attempt_data['attempt_ip'],
				'attempt_browser'	=> trim(substr($browser, 0, 149)),
			);
			$this->afl_post($afl_post_data, AFL_SUCCESS_UCP);
		}
		if ($this->config['afl_hook_login'] )
		{
			$this->afl_hook_secured('afl_s');
		}
	}//Function End

	/**
	* ALERT_SUCCESS_LOGIN_ACP
	* Function destined to alert user and/or founder when a succes login is make to the ACP
	* @param array $tow contain all required informations for the current logged user
	* @param string $browser for get the Browser User Agent
	* @param array $attempt_data contain major information about the current login of the user
	*/
	public function alert_success_login_acp($row, $browser, $attempt_data)
	{
		global $user, $phpbb_root_path, $db, $phpEx;

		$this->config_poster = unserialize($this->config['afl_poster_id']);

		$this->afl_uid		= ($this->config['afl_poster_owner'] ? $attempt_data['user_id']	: $this->config_poster['user_id']);
		$this->afl_uname	= ($this->config['afl_poster_owner'] ? $row['username']			: $this->config_poster['username']);
		$this->afl_colour	= ($this->config['afl_poster_owner'] ? $row['user_colour']		: $this->config_poster['user_colour']);

		include($phpbb_root_path . 'includes/functions_messenger.' . $phpEx);
		$messenger = new messenger(false);

		//Many query coming soon, keep alive !! (Closed before function end)
		$db->sql_transaction('begin');

		$result = $db->sql_query($this->get_founders());
		while ($f_row = $db->sql_fetchrow($result))
		{
			if (in_array($f_row['user_id'], $this->uid_loop) )
			{
				//extra security measure: here we avoid any duplicate email/pm
				continue;
			}
			if (in_array($f_row['group_id'], unserialize($this->config['afl_groups_exclude'])) )
			{
				//default group of this user is excluded from alert, continue...
				continue;
			}
	//Founder who will be alerted of this success login
			if ($attempt_data['user_id'] != $f_row['user_id'] )
			{
				if ($f_row['user_afl_type'] == AFL_TYPE_MAIL )
				{
					$afl_mail_data = array(
						'email_template'		=> 'alert_for_success_login_acp_founder',
						'user_lang'				=> $f_row['user_lang'],
						'username'				=> $f_row['username'],
						'user_email'			=> $f_row['user_email'],
						'attempt_ip'			=> $attempt_data['attempt_ip'],
						'attempt_browser'		=> trim(substr($browser, 0, 149)), // Like db_auth.php trim & substr $browser thx ;)
						'count_attempt'			=> '', //Not used for alert user but founder....
						'attempt_username'		=> '', //Not used for alert user but founder....
						'logged_in_time'		=> $user->format_date(time(), $f_row['user_dateformat']),//GET individual date format....
						'log_success_ip'		=> $attempt_data['attempt_ip'],//Used for success login
						'username_logged'		=> $row['username'],
						'attempt_password'		=> false //Used only for user attempted
					);
					//Our customized function for sent new mail
					$this->afl_mail($afl_mail_data);
				}
				if (($row['user_afl_success'] || $this->config['afl_force_alert_succ']) && $f_row['user_afl_type'] == AFL_TYPE_PM) //If PM turned on for this Mod and one more time we do not need to send again a PM if the user Attempted is founder :)
				{
					include($phpbb_root_path . 'language/' . $f_row['user_lang'] . '/mods/info_ucp_alert_for_login.' . $phpEx);//(1)readme please
					$afl_pm_data = array(
						'pm_sub_lang'			=> $lang['AFLF_ACP_FOUNDER_ALERT_SUCCESS'],
						'pm_mess_lang'			=> sprintf($lang['AFLF_ACP_FOUNDER_SUCCESS_TEXT'], $f_row['username'], $row['username'], $attempt_data['attempt_ip'], $user->format_date(time(), false, true), str_replace('{USER_IP}', $attempt_data['attempt_ip'], isset($this->config['afl_whois_url']) ? (string) $this->config['afl_whois_url'] : ''), $attempt_data['attempt_browser']),
						'pm_mess_attempt_ip'	=> $attempt_data['attempt_ip'],
						'pm_adress_list'		=> $f_row['user_id']
					);
					$this->config['afl_pm_alert'] ? ($this->config['allow_privmsg'] ? $this->afl_pm($afl_pm_data) : '' ) : '' ;
				}//END PM Step
			}
			$this->uid_loop[] = $f_row['user_id'];
		}
		$db->sql_freeresult($result);
	//User Logged into ACP, so set a different template...
		if (($attempt_data['user_id'] == $row['user_id']) && $row['user_afl_success'] )
		{
			if ($row['user_afl_type'] == AFL_TYPE_MAIL )
			{
				$afl_mail_data = array(
					'email_template'	=> 'alert_for_success_login_acp',
					'user_lang'			=> $row['user_lang'],
					'username'			=> $row['username'],
					'user_email'		=> $row['user_email'],
					'attempt_ip'		=> $attempt_data['attempt_ip'],
					'attempt_browser'	=> trim(substr($browser, 0, 149)), // Like db_auth.php trim & substr $browser thx ;)
					'count_attempt'		=> '',//Not used for alert user but founder....
					'attempt_username'	=> '',//Not used for alert user but founder....
					'logged_in_time'	=> $user->format_date(time(), false, true),
					'log_success_ip'	=> $attempt_data['attempt_ip'],//Used for success login
					'username_logged'	=> '',//Not used for alert user but founder....
					'attempt_password'	=> false //Used only for user attempted
				);
				//Our customized function for sent new mail
				$this->afl_mail($afl_mail_data);
			}
			if (($row['user_afl_success'] || $this->config['afl_force_alert_succ']) && $row['user_afl_type'] == AFL_TYPE_PM) //If PM turned on for this Mod and one more time we do not need to send again a PM if the user Attempted is founder :)
			{
				include($phpbb_root_path . 'language/' . $row['user_lang'] . '/mods/info_ucp_alert_for_login.' . $phpEx);//(1)readme please
				$afl_pm_data = array(
					'pm_sub_lang'			=> $lang['AFLF_ACP_USER_ALERT_SUCCESS'],
					'pm_mess_lang'			=> sprintf($lang['AFLF_ACP_USER_SUCCESS_TEXT'], $f_row['username'], $attempt_data['attempt_ip'], $user->format_date(time(), false, true), str_replace('{USER_IP}', $attempt_data['attempt_ip'], isset($this->config['afl_whois_url']) ? (string) $this->config['afl_whois_url'] : ''), $attempt_data['attempt_browser']),
					'pm_mess_attempt_ip'	=> $attempt_data['attempt_ip'],
					'pm_adress_list'		=> $row['user_id']
				);
				$this->config['afl_pm_alert'] ? ($this->config['allow_privmsg'] ? $this->afl_pm($afl_pm_data) : '' ) : '' ;
			}//END PM Step
		}
		//increment stats....
		if ($this->config['afl_stats_enabled'] )
		{
			set_config_count('afl_stats_success_acp', 1, false);
		}
		if ($this->config['afl_success_topics'] )
		{
			$afl_post_data = array(
				'user_dateformat'	=> $row['user_dateformat'],
				'username'			=> $row['username'],
				'user_id'			=> $attempt_data['user_id'],
				'count_attempt'		=> '',
				'attempt_ip'		=> $attempt_data['attempt_ip'],
				'attempt_browser'	=> trim(substr($browser, 0, 149)),
			);
			$this->afl_post($afl_post_data, AFL_SUCCESS_ACP);
		}

		//close it !
		$db->sql_transaction('commit');
		if ($this->config['afl_hook_login'] )
		{
			$this->afl_hook_secured('afl_s_acp');
		}
	}
	/**
	* AFL_HOOK_SECURED
	* Function destined to inform user than the login is secured by AFL !
	* @param string $mode mode we're using...
	*/
	private function afl_hook_secured($mode)
	{
		global $user, $template, $phpbb_root_path;
		$user->add_lang('mods/info_ucp_alert_for_login');
		$tag_path = $phpbb_root_path . 'images/afl/' . $user->data['user_lang'] . '/afl.png';
		$image = '<img src="' . $tag_path . '" alt="' . $user->lang['AFL_FIELDSET_TITLE'] . '" style="float: right;" />';
		$tag_end = '<br /><a' . AFL_EXTERNAL . ' href="' . AFL_SECURED . '">' . $image . '</a>' . '<br />';

		switch ( $mode )
		{
			case'afl_s':
				$user->lang['RETURN_INDEX'] .= $tag_end;
				$user->lang['RETURN_PAGE'] .= $tag_end;
			break;

			case'afl_s_acp':
				$user->lang['PROCEED_TO_ACP'] .= $tag_end;
			break;

			case'afl_f':
			case'afl_f_acp':
				$template->assign_var('S_AFL_SECURED', $tag_end );
			break;

			default:
				return false;
		}
	}

	/**
	* AFL_POST_COMPATIBILTY
	* Function destined to make the post more compatible with some MODs
	* @param REF array $data contain all required informations for future topic
	* Uncomment $data parameters if you have one of these mod below !
	*/
	private function afl_post_compatibilty( &$data )
	{
		global $user, $auth, $qte;
	/*	MOD: Quick Title Edition	*/
		//because this mod is a more bit complex than other, we need to pre-integrate it, checking with empty and isset before!
		if (!empty($this->config['afl_addon_qte_id']) && isset($qte) )
		{
			$data['attr_id']	= $this->config['afl_addon_qte_id'];
		}
	/*	MOD: Post Background color	*/
		//Off course you can change the default colour
		//$data['post_bgcolor'] = 'FFFFFF';

	/*	MOD: Os, Browser & Screen	*/
		//Choice a default screen size...
		//$data['screen'] = '1920x1080';

	/*	MOD: Breizh Shoutbox	*/
		//shut up robot? okey turn true ;)
		//$data['hide_robot'] = true;

	/*	MOD: Topic description		*/
		//Here you can use a manual description or an lang key, it's your choice
		//$data['topic_desc'] = $user->lang['YOUR_LANG_KEY_DESCRIPTION'];

	/*	MOD: Ultimate point			*/
		//How many pts the member will win? (recommanded to keep commented... otherwise user will get pts at each login)
		// $data['user_points'] = 0;

	/*	MOD: www.phpBB-SEO.com SEO TOOLKIT END	*/
		//seo compatibility, choice a custom url....
		// global $phpbb_seo;
		// if (!empty($phpbb_seo->seo_opt['sql_rewrite']))
		// {
			// $data['topic_url'] = 'custom default url';
		// }
		return $data;
	}//Function End
}//Class End
/**(1)
*---------/!\---------/!\---------
* To validation Team and PHPbb Moders:
*Ok, now let me be clear:
*Why i use $(f_)row['user_lang']??
*HAHAHA, because i want the language of all founder which will be alerted, so i can't use $user->lang & $user->add_lang() !!!!
*I need to get manually the language key, using $lang['my_array'], unless if you have another solution for that, currently is the best i've find....
*/
?>