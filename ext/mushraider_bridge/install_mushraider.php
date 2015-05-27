<?php
/**
* @author Julien Goret (Kyah) http://www.octetsetquartdepouces.net
*
* @package mushraider_bridge
* @version $Id:
* @copyright (c) 2014 Julien Goret
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

define('UMIL_AUTO', true);
define('IN_PHPBB', true);
$phpbb_root_path = (defined('PHPBB_ROOT_PATH')) ? PHPBB_ROOT_PATH : './';
$phpEx = substr(strrchr(__FILE__, '.'), 1);
include($phpbb_root_path . 'common.' . $phpEx);
$user->session_begin();
$auth->acl($user->data);
$user->setup();

if (!file_exists($phpbb_root_path . 'umil/umil_auto.' . $phpEx))
{
	trigger_error('Please download the latest UMIL (Unified MOD Install Library) from: <a href="http://www.phpbb.com/mods/umil/">phpBB.com/mods/umil</a>', E_USER_ERROR);
}

/*
* The language file which will be included when installing
* Language entries that should exist in the language file for UMIL (replace $mod_name with the mod's name you set to $mod_name above)
* $mod_name
* 'INSTALL_' . $mod_name
* 'INSTALL_' . $mod_name . '_CONFIRM'
* 'UPDATE_' . $mod_name
* 'UPDATE_' . $mod_name . '_CONFIRM'
* 'UNINSTALL_' . $mod_name
* 'UNINSTALL_' . $mod_name . '_CONFIRM'
*/
$language_file = 'mods/info_acp_mushraider_bridge';

// The name of the mod to be displayed during installation.
$mod_name = 'MUSHRAIDER_BRIDGE';

/*
* The name of the config variable which will hold the currently installed version
* You do not need to set this yourself, UMIL will handle setting and updating the version itself.
*/
$version_config_name = 'mushraider_bridge_version';

/*
* The array of versions and actions within each.
* You do not need to order it a specific way (it will be sorted automatically), however, you must enter every version, even if no actions are done for it.
*
* You must use correct version numbering.  Unless you know exactly what you can use, only use X.X.X (replacing X with an integer).
* The version numbering must otherwise be compatible with the version_compare function - http://php.net/manual/en/function.version-compare.php
*/
$versions = array(
	// Version 1.0.0
	'1.0.0'	=> array(
		// Add a permission settings
		'permission_add' => array(
			array('a_mushraider_bridge', 1),
		),
		'permission_set' => array(
			// Global Group permissions
			array('ADMINISTRATORS', 'a_mushraider_bridge', 'group'),
		),
        // Add config var
        'config_add' => array(
            array('mrb_enable', 1, 0),
            array('mrb_hashkey', 'SET ME', 0),
            array('mrb_members', '2', 0),
            array('mrb_officers', '4', 0),
            array('mrb_admin', '5', 0),
        ),
        
		// Add the module
		'module_add' => array(
			array('acp', 'ACP_CAT_DOT_MODS', 'ACP_MUSHRAIDER_BRIDGE'),
			array('acp', 'ACP_MUSHRAIDER_BRIDGE', array(
					'module_basename'	=> 'mushraider_bridge',
					'module_langname'	=> 'ACP_MUSHRAIDER_BRIDGE_CONFIG',
					'module_mode' 		=> 'config',
					'module_auth'		=> 'acl_a_mushraider_bridge',
				),
			),
            array('acp', 'ACP_MUSHRAIDER_BRIDGE', array(
					'module_basename'	=> 'mushraider_bridge',
					'module_langname'	=> 'ACP_MUSHRAIDER_BRIDGE_PERMS',
					'module_mode' 		=> 'permissions',
					'module_auth'		=> 'acl_a_mushraider_bridge',
				),
			),
		),
	),
);

// Include the UMIF Auto file and everything else will be handled automatically.
include($phpbb_root_path . 'umil/umil_auto.' . $phpEx);

?>