<?php
/**
* permissions_pastebin (Pastebin Permission Set) [German]
*
* @package language
* Translator Mahony http://www.sportschulekang.de and http://nationsofmetal.na.funpic.de/forum/
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
	'ACL_U_PASTEBIN_VIEW'			=> 'Kann Snippets sehen',
	'ACL_U_PASTEBIN_POST'			=> 'Kann Snippets posten',
	'ACL_U_PASTEBIN_POST_NOVC'		=> 'Kann Snippets posten ohne visuelle Bestätigung',
	'ACL_U_PASTEBIN_POST_NOTLIM'	=> 'Kann Snippets dauerhaft in den Pastebin einstellen',
	'ACL_U_PASTEBIN_EDIT'			=> 'Kann eigene Snippets bearbeiten (Speicherdauer, Syntaxhervorhebung, Quellcode)',
	'ACL_U_PASTEBIN_DELETE'			=> 'Kann eigene Snippets löschen',

	// Moderator perms,
	'ACL_M_PASTEBIN_EDIT'			=> 'Kann Snippets editieren (Speicherdauer, Syntaxhervorhebung, Quellcode)',
	'ACL_M_PASTEBIN_DELETE'			=> 'Kann Snippets löschen',
	'ACL_M_PASTEBIN_POST_NOTLIM'	=> 'Kann die automatische Löschung von Snippets deaktivieren',
));
