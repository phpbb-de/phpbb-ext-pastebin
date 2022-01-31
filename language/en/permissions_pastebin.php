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

// Adding the permissions
$lang = array_merge($lang, array(
	// Category
	'ACL_CAT_PASTEBIN' => 'Pastebin',

	// User perms
	'ACL_U_PASTEBIN_VIEW'			=> 'Can view pastebin entries',
	'ACL_U_PASTEBIN_POST'			=> 'Can post pastebin entries',
	'ACL_U_PASTEBIN_POST_NOVC'		=> 'Can post pastebin entries without visual confirmation',
	'ACL_U_PASTEBIN_POST_NOTLIM'	=> 'Can post non-pruned pastebin entries',
	'ACL_U_PASTEBIN_EDIT'			=> 'Can edit own pastebin entries (Storage duration, Syntax highlighting, Source code)',
	'ACL_U_PASTEBIN_DELETE'			=> 'Can delete own pastebin entries',

	// Moderator perms
	'ACL_M_PASTEBIN_EDIT'			=> 'Can edit pastebin entries (Storage duration, Syntax highlighting, Source code)',
	'ACL_M_PASTEBIN_DELETE'			=> 'Can delete pastebin entries',
	'ACL_M_PASTEBIN_POST_NOTLIM'	=> 'Can deactivate pruning of selected pastebin entries',
));
