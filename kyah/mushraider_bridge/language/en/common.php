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
* DO NOT CHANGE
*/
if (!defined('IN_PHPBB'))
{
    exit;
}

if (empty($lang) || !is_array($lang))
{
    $lang = array();
}

$lang = array_merge($lang, array(
    'ACP_MUSHRAIDER_BRIDGE'                 => 'MushRaider Bridge',
    'MUSHRAIDER_BRIDGE'                     => 'MushRaider Bridge',
    'ACP_MUSHRAIDER_BRIDGE_CONFIG'          => 'Global',
    'ACP_MUSHRAIDER_BRIDGE_CONFIG_TITLE'    => 'MushRaider Bridge Configuration',
    'ACP_MUSHRAIDER_BRIDGE_PERMS'           => 'Permissions',
    'ACP_MUSHRAIDER_BRIDGE_PERMS_TITLE'     => 'MushRaider Permissions Configuration',
    
    'MUSHRAIDER_BRIDGE_ENABLE'              => 'Enable MushRaider Bridge?',
    'MUSHRAIDER_BRIDGE_KEY'                 => 'MushRaider API Key',
    'MUSHRAIDER_BRIDGE_KEY_EXPLAIN'         => 'This is the API key you generated in MushRaider\'s configuration panel',
    'MUSHRAIDER_THIRD_PARTY_URL'            => 'MushRaider Third party url',
    'MUSHRAIDER_THIRD_PARTY_URL_EXPLAIN'    => 'This is the URL you need to configure in MushRaider\'s bridge configuration' ,
    'MUSHRAIDER_BRIDGE_ADMINS'              => 'Administrators',
    'MUSHRAIDER_BRIDGE_ADMINS_EXPLAIN'      => 'List of phpBB groups which are Administrators of MushRaider /!\ Any groups not in the lists will not be allowed to connect to MushRaider /!\ ',
    'MUSHRAIDER_BRIDGE_OFFICERS'            => 'Officers',
    'MUSHRAIDER_BRIDGE_OFFICERS_EXPLAIN'    => 'List of phpBB groups which are Officers on MushRaider /!\ Any groups not in the lists will not be allowed to connect to MushRaider /!\ ',
    'MUSHRAIDER_BRIDGE_MEMBERS'             => 'Members',
    'MUSHRAIDER_BRIDGE_MEMBERS_EXPLAIN'     => 'List of phpBB groups which are simple Members of MushRaider /!\ Any groups not in the lists will not be allowed to connect to MushRaider /!\ ',
    
));

?>
