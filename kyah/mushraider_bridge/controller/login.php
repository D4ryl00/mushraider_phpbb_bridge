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

namespace kyah\mushraider_bridge\controller;

class login {
	public function __construct(\phpbb\config\config $config, \phpbb\user $user, \phpbb\auth\auth $auth, \phpbb\request\request $request, $root_path, $php_ext, $db)
	{
		$this->config = $config;
		$this->user = $user;
		$this->auth = $auth;
		$this->request = $request;
		$this->root_path = $root_path;
		$this->php_ext = $php_ext;
		$this->db = $db;
	}

    public function base() {
        $this->user->session_begin();
        $this->auth->acl($this->user->data);

        if ($_POST && $this->config['mrb_enable']) {
            if (!isset($_POST['login']) || !isset($_POST['pwd'])) {
                return new \Symfony\Component\HttpFoundation\JsonResponse(array('authenticated' => false));
            } else {
                $salt = $this->config['mrb_hashkey'];
                // Decrypt MushRaider password
                $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_ECB);
                $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
                $pwd = $this->request->get_super_global(\phpbb\request\request_interface::POST)['pwd'];
                $password = utf8_decode(trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $salt, stripslashes($pwd), MCRYPT_MODE_ECB, $iv)));
                $username = request_var('login', '');
                // Test if phpBB connects user
                $result = $this->auth->login($username, htmlspecialchars($password));
                $error = $result['error_msg'];
                if ($result['status'] == LOGIN_SUCCESS)
                 {
                    // User was successfully logged into phpBB
                    // Here we need to find if user is admin, officer or member!
					$group_admin = explode(',', $this->config['mrb_admin']);
					$group_officer = explode(',', $this->config['mrb_officers']);
					$group_member = explode(',', $this->config['mrb_members']);
					$phpbbUserRow = $result['user_row'];
					$user_id = (int) $phpbbUserRow['user_id'];
					$groups_ids = $this->getUserGroups((int)$user_id);

					if (!empty(array_intersect($group_admin, $groups_ids))) {
						$userRole = 'admin';
					} elseif (!empty(array_intersect($group_officer, $groups_ids))) {
						$userRole = 'officer';
					} elseif (!empty(array_intersect($group_member, $groups_ids))) {
						$userRole = 'member';
					} else {
                        $userRole = null;
                    }

					$userInfos = array("authenticated" => $userRole !== null,
									   "email" => $phpbbUserRow['user_email'],
									   "role" => $userRole,
									  );
					// Everything is OK!
					// Return json to mushraider
					return new \Symfony\Component\HttpFoundation\JsonResponse($userInfos);
				 }
				else {
					return new \Symfony\Component\HttpFoundation\JsonResponse(array('authenticated' => false));
				}
			}
		} else {
			// If we don't come from mushraider, send them back to home...
			redirect(append_sid("{$this->root_path}index.$this->php_ext"));
		}
	}

	/* get ALL groups for a user, not only one */
	private function getUserGroups($user_id, $onlyId = true)
	{

		$sql = 'SELECT ug.*, g.*
					FROM ' . GROUPS_TABLE . ' g, ' . USER_GROUP_TABLE . " ug
					WHERE ug.user_id = $user_id
						AND g.group_id = ug.group_id
					ORDER BY g.group_type DESC, ug.user_pending ASC, g.group_name";
		$result = $this->db->sql_query($sql);

		$groups = array();

		while ($row = $this->db->sql_fetchrow($result))
		{
			if ($onlyId)
			{
				$groups[] = (int) $row['group_id'];
			}
			else
			{
				$groups[] = $row;
			}
		}
		return $groups;
	}
}
