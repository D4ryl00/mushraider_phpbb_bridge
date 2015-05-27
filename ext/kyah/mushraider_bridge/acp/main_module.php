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

/**
* @ignore
*/
if (!defined('IN_PHPBB'))
{
    exit;
}

class main_module {

   var $u_action;
   var $new_config = array();

    function main($id, $mode)
    {
        global $config, $db, $user, $auth, $template, $cache;
        global $phpbb_root_path, $phpbb_admin_path, $phpEx;

        $submit = (isset($_POST['submit'])) ? true : false ;
        
        $form_key = 'acp_mushraider_bridge';
        add_form_key($form_key);
        
        switch ($mode) {
            case 'config':
                $display_vars = array(
                    'title'    => 'ACP_MUSHRAIDER_BRIDGE_CONFIG_TITLE',
                    'vars'    => array(
                        'mrb_enable' => array(
                            'lang'      => 'MUSHRAIDER_BRIDGE_ENABLE',
                            'validate'  => 'bool',
                            'type'      => 'radio:yes_no',
                            'explain'   => true),
                        'mrb_hashkey' => array(
                            'lang'      => 'MUSHRAIDER_BRIDGE_KEY',
                            'validate'  => 'string',
                            'type'      => 'text:16:45',
                            'explain'   => true),
                    ),
                );
                
                $this->new_config = $config;
                $cfg_array = (isset($_REQUEST['config'])) ? request_var('config', array('' => '')) : $this->new_config;
                $error = array();

                // We validate the complete config if whished
                validate_config_vars($display_vars['vars'], $cfg_array, $error);
                
                if ($submit && !check_form_key($form_key))
                {
                    $error[] = $this->user->lang['FORM_INVALID'];
                }

                // Do not write values if there is an error
                if (sizeof($error))
                {
                    $submit = false;
                }
                
				// We go through the display_vars to make sure no one is trying to set variables he/she is not allowed to...
				foreach ($display_vars['vars'] as $config_name => $null)
				{
					if ($submit && ((isset($null['type']) && $null['type'] == 'custom') || (isset($null['submit_type']) && $null['submit_type'] == 'custom')))
					{
						$func = array($this, $null['submit']);

						if(method_exists($this, $null['submit']))
						{
							$args = array($cfg_array[$config_name], $config_name);
							call_user_func_array($func, $args);
						}
						else
						{
							$args = array($cfg_array[$config_name], $config_name);
							call_user_func_array($null['submit'], $args);
						}
					}

					if (!isset($cfg_array[$config_name]) || strpos($config_name, 'legend') !== false)
					{
						continue;
					}

					if(isset($null['type']) && $null['type'] == 'custom')
					{
						continue;
					}

					$this->new_config[$config_name] = $config_value = $cfg_array[$config_name];

					if ($submit)
					{	
						set_config($config_name, $config_value);
					}
				}
                
                // Assign value to template
                foreach ($display_vars['vars'] as $config_key => $vars)
                {
                    if (!is_array($vars) && strpos($config_key, 'legend') === false)
                    {
                        continue;
                    }

                    if (strpos($config_key, 'legend') !== false)
                    {
                        $template->assign_block_vars('options', array(
                            'S_LEGEND'   => true,
                            'LEGEND'     => (isset($user->lang[$vars])) ? $user->lang[$vars] : $vars)
                        );

                        continue;
                    }

                    $type = explode(':', $vars['type']);

                    $l_explain = '';
                    if ($vars['explain'] && isset($vars['lang_explain']))
                    {
                        $l_explain = (isset($user->lang[$vars['lang_explain']])) ? $user->lang[$vars['lang_explain']] : $vars['lang_explain'];
                    }
                    else if ($vars['explain'])
                    {
                        $l_explain = (isset($user->lang[$vars['lang'] . '_EXPLAIN'])) ? $user->lang[$vars['lang'] . '_EXPLAIN'] : '';
                    }

                    $content = build_cfg_template($type, $config_key, $this->new_config, $config_key, $vars);

                    if (empty($content))
                    {
                        continue;
                    }

                    $template->assign_block_vars('options', array(
                        'KEY'            => $config_key,
                        'TITLE'          => (isset($user->lang[$vars['lang']])) ? $user->lang[$vars['lang']] : $vars['lang'],
                        'S_EXPLAIN'      => $vars['explain'],
                        'TITLE_EXPLAIN'  => $l_explain,
                        'CONTENT'        => $content,
                    ));
                    
                    unset($display_vars['vars'][$config_key]);
                }
                
                $this->page_title = 'ACP_MUSHRAIDER_BRIDGE';
                $this->tpl_name = 'mushraider_bridge';
                break;
            case 'permissions':
                $display_vars = array(
                    'title'    => 'ACP_MUSHRAIDER_BRIDGE_PERMS_TITLE',
                    'vars'    => array(
                        'mrb_admin' => array(
                            'lang'      => 'MUSHRAIDER_BRIDGE_ADMINS',
                            'validate'  => 'string',
                            'type'      => 'custom',
                            'method'    => 'groups_select',
                            'submit'    => 'groups_select_submit',
                            'explain'   => true),
                        'mrb_officers' => array(
                            'lang'      => 'MUSHRAIDER_BRIDGE_OFFICERS',
                            'validate'  => 'string',
                            'type'      => 'custom',
                            'method'    => 'groups_select',
                            'submit'    => 'groups_select_submit',
                            'explain'   => true),
                        'mrb_members' => array(
                            'lang'      => 'MUSHRAIDER_BRIDGE_MEMBERS',
                            'validate'  => 'string',
                            'type'      => 'custom',
                            'method'    => 'groups_select',
                            'submit'    => 'groups_select_submit',
                            'explain'   => true),
                    ),
                );
                
                $this->new_config = $config;
                $cfg_array = (isset($_REQUEST['config'])) ? request_var('config', array('' => '')) : $this->new_config;
                $error = array();

                // We validate the complete config if whished
                validate_config_vars($display_vars['vars'], $cfg_array, $error);
                
                if ($submit && !check_form_key($form_key))
                {
                    $error[] = $this->user->lang['FORM_INVALID'];
                }

                // Do not write values if there is an error
                if (sizeof($error))
                {
                    $submit = false;
                }
                
				// We go through the display_vars to make sure no one is trying to set variables he/she is not allowed to...
				foreach ($display_vars['vars'] as $config_name => $null)
				{
					if ($submit && ((isset($null['type']) && $null['type'] == 'custom') || (isset($null['submit_type']) && $null['submit_type'] == 'custom')))
					{
						$func = array($this, $null['submit']);

						if(method_exists($this, $null['submit']))
						{
							$args = array($config_name);
							call_user_func_array($func, $args);
						}
						else
						{
							$args = array($config_name);
							call_user_func_array($null['submit'], $args);
						}
					}

					if (!isset($cfg_array[$config_name]) || strpos($config_name, 'legend') !== false)
					{
						continue;
					}

					if(isset($null['type']) && $null['type'] == 'custom')
					{
						continue;
					}

					$this->new_config[$config_name] = $config_value = $cfg_array[$config_name];

					if ($submit)
					{	
						set_config($config_name, $config_value);
					}
				}
                
                // Assign value to template
                foreach ($display_vars['vars'] as $config_key => $vars)
                {
                    if (!is_array($vars) && strpos($config_key, 'legend') === false)
                    {
                        continue;
                    }

                    if (strpos($config_key, 'legend') !== false)
                    {
                        $template->assign_block_vars('options', array(
                            'S_LEGEND'   => true,
                            'LEGEND'     => (isset($user->lang[$vars])) ? $user->lang[$vars] : $vars)
                        );

                        continue;
                    }

                    $type = explode(':', $vars['type']);

                    $l_explain = '';
                    if ($vars['explain'] && isset($vars['lang_explain']))
                    {
                        $l_explain = (isset($user->lang[$vars['lang_explain']])) ? $user->lang[$vars['lang_explain']] : $vars['lang_explain'];
                    }
                    else if ($vars['explain'])
                    {
                        $l_explain = (isset($user->lang[$vars['lang'] . '_EXPLAIN'])) ? $user->lang[$vars['lang'] . '_EXPLAIN'] : '';
                    }

                    if($vars['type'] != 'custom')
					{
						$content = build_cfg_template($type, $config_key, $this->new_config, $config_key, $vars);
					}
					else
					{
						$args = array($this->new_config[$config_key], $config_key);
						if (!is_array($vars['method']))
						{
							$func = array($this, $vars['method']);
						}
						else
						{
							$func = $vars['method'];
						}
						$content = call_user_func_array($func, $args);
					}

                    if (empty($content))
                    {
                        continue;
                    }

                    $template->assign_block_vars('options', array(
                        'KEY'            => $config_key,
                        'TITLE'          => (isset($user->lang[$vars['lang']])) ? $user->lang[$vars['lang']] : $vars['lang'],
                        'S_EXPLAIN'      => $vars['explain'],
                        'TITLE_EXPLAIN'  => $l_explain,
                        'CONTENT'        => $content,
                    ));
                    unset($display_vars['vars'][$config_key]);
                }
                
                $this->page_title = 'ACP_MUSHRAIDER_BRIDGE';
                $this->tpl_name = 'mushraider_bridge';
                break;
            default:
                trigger_error('NO_MODE', E_USER_ERROR);
                break;
        }
    }
    
    public function groups_select($value, $key) //Based on group_select_options from $phpbb_root_path/includes/functions_admin.php
    {
        global $db, $config, $user;
        
        $selected = array();
		if(isset($config[$key]) && strlen($config[$key]) > 0)
		{
			$selected = explode(',', $config[$key]);
		}
        
        $s_groups = '<select id="'.$key.'" name="'.$key.'[]" size="10" multiple="multiple">';
        
        $sql = 'SELECT group_id, group_name, group_type
		FROM ' . GROUPS_TABLE . "
		ORDER BY group_type DESC, group_name ASC";
        $result = $db->sql_query($sql);
        
        while ($row = $db->sql_fetchrow($result))
        {
            $isSelected = in_array($row['group_id'],$selected) ? ' selected="selected"' : '';
            $s_groups .= '<option' . (($row['group_type'] == GROUP_SPECIAL) ? ' class="sep"' : '') . ' value="' . $row['group_id'] . '"' . $isSelected . '>' . (($row['group_type'] == GROUP_SPECIAL) ? $user->lang['G_' . $row['group_name']] : $row['group_name']) . '</option>';
        }
        $db->sql_freeresult($result);
        
        $s_groups .= '</select>';
        return $s_groups;
    }
    
    public function groups_select_submit($key)
    {
        // Set selected groups
        $values = request_var($key, array(0 => ''));
        $groups = implode(',', $values);
        set_config($key, $groups);
    }
    
}

?>
