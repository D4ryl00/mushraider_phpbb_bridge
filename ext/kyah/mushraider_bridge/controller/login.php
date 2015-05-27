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
    public function __construct(\phpbb\config\config $config, \phpbb\user $user, \phpbb\auth $auth, $root_path, $php_ext)
    {
        $this->config = $config;
        $this->user = $user;
        $this->auth = $auth;
        $this->root_path = $root_path;
        $this->php_ext = $php_ext;
    }

    public function base() {
        $this->user->session_begin();
        $this->auth->acl($this->user->data);

        if ($_POST && $this->config['mrb_enable']) {
            if (!isset($_POST['login']) || !isset($_POST['pwd'])) {
                echo json_encode(array('authenticated' => false));
            } else {
                $salt = $this->config['mrb_hashkey'];
                // Decrypt MushRaider password
                $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_ECB);
                $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
                $password = utf8_decode(trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $salt, stripslashes($_POST['pwd']), MCRYPT_MODE_ECB, $iv)));
                $username = $_POST['login'];
                // Test if phpBB connects user
                $result = $this->auth->login($username, htmlspecialchars($password));
                $error = $result['error_msg'];
                if ($result['status'] == LOGIN_SUCCESS)
                 {
                    $admins = explode(',', $this->config['mrb_admin']);
                    $officers = explode(',', $this->config['mrb_officers']);
                    $members = explode(',', $this->config['mrb_members']);
                    //User was successfully logged into phpBB
                    $phpbbUserRow = $result['user_row'];
                    $user_id = $phpbbUserRow['user_id'];
                    $userInfos = array();
                    // Here we need to find if user is admin, officer or member!
                    foreach ($admins as $admin_group) {
                        if (!isset($userRole) && group_memberships($admin_group,$user_id,true)) {
                            $userRole = 'admin';
                        }
                    }
                    foreach ($officers as $officer_group) {
                        if (!isset($userRole) && group_memberships($officer_group,$user_id,true)) {
                            $userRole = 'officer';
                        }
                    }
                    foreach ($members as $member_group) {
                        if (!isset($userRole) && group_memberships($member_group,$user_id,true)) {
                            $userRole = 'member';
                        }
                    }

                    if (isset($userRole)) {
                        // Everything is OK!
                        $isAuthenticated = true;
                        $userInfos['email'] = $phpbbUserRow['user_email'];
                        $userInfos['role'] = $userRole;
                    } else {
                        $isAuthenticated = false;
                    }

                    // Return json to mushraider
                    $userInfos['authenticated'] = $isAuthenticated;
                    echo json_encode($userInfos);
                 }
                else {
                    echo json_encode(array('authenticated' => false));
                }
            }
        } else {
            // If we don't come from mushraider, send them back to home...
            redirect(append_sid("{$this->root_path}index.$this->php_ext"));
        }
    }

?>
