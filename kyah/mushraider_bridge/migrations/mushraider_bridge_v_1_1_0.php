<?php
/**
* @author rems14 http://www.octetsetquartdepouces.net
*
* @package mushraider_bridge
* @version $Id:
* @copyright (c) 2015 rems14
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

namespace kyah\mushraider_bridge\migrations;

class mushraider_bridge_v_1_1_0 extends \phpbb\db\migration\migration
{
	public function effectively_installed()
	{
		return isset($this->config['mushraider_bridge_version']) && version_compare($this->config['mushraider_bridge_version'], '1.1.0', '>=');
	}

	static public function depends_on()
	{
		return array('\phpbb\db\migration\data\v310\dev');
	}

	public function update_data()
	{
		return array(
			// Current version
			array('config.add', array('mushraider_bridge_version', '1.1.0')),
		    // Add a permission settings
			array('permission.add', array('a_mushraider_bridge')),
			// Global Group permissions
			array('permission.permission_set', array('ADMINISTRATORS', 'a_mushraider_bridge', 'group')),
            // Add config var
			array('config.add', array('mrb_enable', 1)),
			array('config.add', array('mrb_hashkey', 'SET ME')),
			array('config.add', array('mrb_members', '2')),
			array('config.add', array('mrb_officers', '4')),
			array('config.add', array('mrb_admin', '5')),
		    // Add the module
            array('module.add', array('acp', 'ACP_CAT_DOT_MODS', 'ACP_MUSHRAIDER_BRIDGE')),
            array('module.add', array('acp', 'ACP_MUSHRAIDER_BRIDGE', array(
					'module_basename'	=> '\kyah\mushraider_bridge\acp\main_module',
					'module_langname'	=> 'ACP_MUSHRAIDER_BRIDGE_CONFIG',
					'module_mode' 		=> 'config',
					'module_auth'		=> 'acl_a_mushraider_bridge',
				    ))),
            array('module.add', array('acp', 'ACP_MUSHRAIDER_BRIDGE', array(
					'module_basename'	=> '\kyah\mushraider_bridge\acp\main_module',
					'module_langname'	=> 'ACP_MUSHRAIDER_BRIDGE_PERMS',
					'module_mode' 		=> 'permissions',
					'module_auth'		=> 'acl_a_mushraider_bridge',
				    ))),
		);
	}

	public function revert_data()
	{
		// Remove
		return array(
			// Remove current version
			array('config.remove', array('mushraider_bridge_version')),
		    // Remove a permission settings
			array('permission.remove', array('a_mushraider_bridge')),
            // Remove config var
			array('config.remove', array('mrb_enable')),
			array('config.remove', array('mrb_hashkey')),
			array('config.remove', array('mrb_members')),
			array('config.remove', array('mrb_officers')),
			array('config.remove', array('mrb_admin')),
		    // Remove the module
           array('module.remove', array('acp', 'ACP_MUSHRAIDER_BRIDGE', array(
					'module_basename'	=> 'mushraider_bridge',
					'module_langname'	=> 'ACP_MUSHRAIDER_BRIDGE_CONFIG',
					'module_mode' 		=> 'config',
					'module_auth'		=> 'acl_a_mushraider_bridge',
				    ))),
           array('module.remove', array('acp', 'ACP_MUSHRAIDER_BRIDGE', array(
					'module_basename'	=> 'mushraider_bridge',
					'module_langname'	=> 'ACP_MUSHRAIDER_BRIDGE_PERMS',
					'module_mode' 		=> 'permissions',
					'module_auth'		=> 'acl_a_mushraider_bridge',
				    ))),
           array('module.remove', array('acp', 'ACP_CAT_DOT_MODS', 'ACP_MUSHRAIDER_BRIDGE')),
		);
	}
}
