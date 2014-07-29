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
define('IN_PHPBB', true);
$phpbb_root_path = (defined('PHPBB_ROOT_PATH')) ? PHPBB_ROOT_PATH : '../';
$phpEx = substr(strrchr(__FILE__, '.'), 1);

include($phpbb_root_path . 'common.' . $phpEx);
include($phpbb_root_path . 'includes/functions_user.' . $phpEx);
// Start session management
$user->session_begin();
$auth->acl($user->data);

// Add lang file
$user->setup(array('mods/mushraider_bridge',));

if ($_POST && $config['mrb_enable']) {
    if (!isset($_POST['login']) || !isset($_POST['pwd'])) {
        echo json_encode(array('authenticated' => false));
    } else {
        $salt = $config['mrb_hashkey'];
        // Decrypt MushRaider password
        $iv_size = mcrypt_get_iv_size(MCRYPT_BLOWFISH, MCRYPT_MODE_ECB);
        $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
        $password = trim(mcrypt_decrypt(MCRYPT_BLOWFISH, $salt, $_POST['pwd'], MCRYPT_MODE_ECB, $iv));
        $username = $_POST['login'];
        // Test if phpBB connects user
        $result = $auth->login($username, $password);
        $error = $result['error_msg'];
        if ($result['status'] == LOGIN_SUCCESS)
         {
            $admins = explode(',', $config['mrb_admin']);
            $officers = explode(',', $config['mrb_officers']);
            $members = explode(',', $config['mrb_members']);
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
    redirect(append_sid("{$phpbb_root_path}index.$phpEx"));
}

?>