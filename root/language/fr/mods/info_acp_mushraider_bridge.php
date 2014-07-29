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
    'ACP_MUSHRAIDER_BRIDGE_CONFIG'          => 'Configuration',
    'ACP_MUSHRAIDER_BRIDGE_CONFIG_TITLE'    => 'Configuration de MushRaider Bridge',
    'ACP_MUSHRAIDER_BRIDGE_PERMS'           => 'Permissions',
    'ACP_MUSHRAIDER_BRIDGE_PERMS_TITLE'     => 'Configuration des permissions de MushRaider',
    
    'MUSHRAIDER_BRIDGE_ENABLE'              => 'Activer le Bridge MushRaider?',
    'MUSHRAIDER_BRIDGE_KEY'                 => 'HashKey du bridge MushRaider',
    'MUSHRAIDER_BRIDGE_KEY_EXPLAIN'         => 'Il s\'agit de la clé que vous avez entré dans le panneau de configuration de MushRaider',
    'MUSHRAIDER_BRIDGE_ADMINS'              => 'Administrateurs',
    'MUSHRAIDER_BRIDGE_ADMINS_EXPLAIN'      => 'Liste des groupes phpBB considérés comme administrateurs de MushRaider /!\ Seuls les groupes appartenant a une de ces listes peut se connecter a MushRaider /!\ ',
    'MUSHRAIDER_BRIDGE_OFFICERS'            => 'Officiers',
    'MUSHRAIDER_BRIDGE_OFFICERS_EXPLAIN'    => 'Liste des groupes phpBB considérés comme  Officiers de MushRaider /!\ Seuls les groupes appartenant a une de ces listes peut se connecter a MushRaider /!\  ',
    'MUSHRAIDER_BRIDGE_MEMBERS'             => 'Membres',
    'MUSHRAIDER_BRIDGE_MEMBERS_EXPLAIN'     => 'Liste des groupes phpBB considérés comme Membres de MushRaider /!\ Seuls les groupes appartenant a une de ces listes peut se connecter a MushRaider /!\ ',
    
));

?>