<?php
/**
* permissions_pastebin (Pastebin Permission Set) [German]
*
* @package language
* @version 0.1.3
* Translator Mahony http://www.sportschulekang.de and http://nationsofmetal.na.funpic.de/forum/
* @copyright (c) 2007 eviL3
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
	'acl_u_pastebin_view'		=> array('lang' => 'Kann Snippets sehen', 'cat' => 'pastebin'),
	'acl_u_pastebin_post'		=> array('lang' => 'Kann Snippets posten', 'cat' => 'pastebin'),
	'acl_u_pastebin_post_novc'	=> array('lang' => 'Kann Snippets posten ohne visuelle Bestätigung', 'cat' => 'pastebin'),

	// Moderator perms
	'acl_m_pastebin_edit'		=> array('lang' => 'Kann Snippets editieren', 'cat' => 'pastebin'),
	'acl_m_pastebin_delete'		=> array('lang' => 'Kann Snippets löschen', 'cat' => 'pastebin'),
));
