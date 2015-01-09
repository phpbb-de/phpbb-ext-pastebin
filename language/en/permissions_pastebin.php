<?php
/**
* permissions_pastebin (Pastebin Permission Set)
*
* @package language
* @version 0.1.3
* @copyright (c) 2007 eviL3, gn#36
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
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

// DEVELOPERS PLEASE NOTE
//
// All language files should use UTF-8 as their encoding and the files must not contain a BOM.
//
// Placeholders can now contain order information, e.g. instead of
// 'Page %s of %s' you can (and should) write 'Page %1$s of %2$s', this allows
// translators to re-order the output of data while ensuring it remains correct
//
// You do not need this where single placeholders are used, e.g. 'Message %d' is fine
// equally where a string contains only two placeholders which are used to wrap text
// in a url you again do not need to specify an order e.g., 'Click %sHERE%s' is fine

// Adding new category
$lang['permission_cat']['pastebin']	= 'Pastebin';

// Adding new permission set
//$lang['permission_type']['pastebin_'] = 'Pastebin Permissions';

// Adding the permissions
$lang = array_merge($lang, array(
	// User perms
	'acl_u_pastebin_view'		=> array('lang' => 'Can view pastebin entries', 'cat' => 'pastebin'),
	'acl_u_pastebin_post'		=> array('lang' => 'Can post pastebin entries', 'cat' => 'pastebin'),
	'acl_u_pastebin_post_novc'	=> array('lang' => 'Can post pastebin entries without visual confirmation', 'cat' => 'pastebin'),

	// Moderator perms
	'acl_m_pastebin_edit'		=> array('lang' => 'Can edit pastebin entries', 'cat' => 'pastebin'),
	'acl_m_pastebin_delete'		=> array('lang' => 'Can delete pastebin entries', 'cat' => 'pastebin'),
));

?>