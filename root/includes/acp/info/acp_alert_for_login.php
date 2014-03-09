<?php
/**
*
* @package acp Alert For Logins
* @version $Id: acp_alert_for_login.php v1.3.1b 00:27 11/07/2013 Geolim4 Exp $
* @copyright (c) 2012 Geolim4.com  http://Geolim4.com
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

/**
* @package module_install
*/
if (!defined('IN_PHPBB'))
{
	exit;
}
/**
* @package module_install
*/
class acp_alert_for_login_info
{
	function module()
	{
		return array(
			'filename'	=> 'acp_alert_for_login',
			'title'		=> 'ACP_AFL_CONFIG',
			'version'	=> '1.3.1b',
			'modes'		=> array(
			'configuration'		=> array('title' => 'ACP_AFL_CONFIG', 'auth' => '', 'cat' => array('ACP_CAT_USERS')),
			),
		);
	}

	function install()
	{
	}

	function uninstall()
	{
	}
}

?>