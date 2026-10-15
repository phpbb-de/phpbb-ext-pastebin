<?php

/**
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
	$lang = [];
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
//
// Some characters you may want to copy&paste:
// ’ « » “ ” … „ “

$lang = array_merge($lang, [
	// ACP
	'ACP_PASTEBIN_TITLE'	=>'Pastebin',

	'PASTEBIN_NAV_TITLE' 		=> 'Pastebin',
	'PASTEBIN_NAV_CONFIG' 		=> 'Pastebin - Settings',
	'PASTEBIN_NAV_LANGS' 		=> 'Pastebin - Languages',

	'PASTEBIN_CONFIG' 			=> 'Pastebin - Settings',
	'PASTEBIN_CONFIG_EXPLAIN' 	=> 'On this page the settings for the <em>Pastebin</em> extensions can be done. 
									Set up includes, if user can use secret snippets and the default value for store duration of the snippets.',

	'PASTEBIN_MAIN_SETTINGS' 			=> 'Pastebin - Settings',
	'PASTEBIN_MAIN_SETTINGS_EXPLAIN' 	=> 'Set up the main functions for the <em>Pastebin</em> extension.',

	'PASTEBIN_ALLOW_SECRET_SNIPPETS' 			=> 'Allow secret snippets',
	'PASTEBIN_ALLOW_SECRET_SNIPPETS_EXPLAIN' 	=> 'If activated, the users can create secret snippets, which can only be read by those people who know the URL.',

	'PASTEBIN_DEFAULT_PRUNE_MONTHS' 			=> 'Default store duration',
	'PASTEBIN_DEFAULT_PRUNE_MONTHS_EXPLAIN' 	=> 'With this value the default store duration for the snippets is defined. The users can change the value to the presets.',
	'PASTEBIN_MONTHS' 							=> 'Months',
	'PASTEBIN_PRUNE_MIN_DAYS'					=> 'Minimum storage duration',
	'PASTEBIN_PRUNE_MIN_DAYS_EXPLAIN'			=> 'Shortest duration the user can select.',
	'PASTEBIN_PRUNE_MAX_DAYS'					=> 'Maximum storage duration',
	'PASTEBIN_PRUNE_MAX_DAYS_EXPLAIN'			=> 'Longest duration the user can select.',
	'PASTEBIN_PRUNE_LIMITS_INVALID'				=> 'The minimum has to be at least "1" and cannot exceed the maximum.',

	// Select CSS
	'PASTEBIN_HIGHLIGHT_CSS_FILE'			=> 'Select Highlight CSS file',
	'PASTEBIN_HIGHLIGHT_CSS_FILE_EXPLAIN'	=> 'You can select between a few existing highlight styles.',
	'PASTEBIN_HIGHLIGHT_CSS_NONE'			=> 'No valid CSS file was selected.',
	'PASTEBIN_HIGHLIGHT_CSS_INVALID'		=> 'The selected CSS file is invalid.',

	'PASTEBIN_SETTINGS' 	=> 'Pastebin settings',
	'PASTEBIN_UPDATED' 		=> 'The Pastebin settings have been updated.',

	'PASTEBIN_LANG_CONFIG'			=> 'Pastebin - Language Configuration',
	'PASTEBIN_LANG_CONFIG_EXPLAIN'	=> 'See all lanuage properties and activate and deactivate languages.',

	// Lang-Keys for the Pastebin-Lang-ACP-page
	'PASTEBIN_LANG_SETTINGS'			=> 'Pastebin - Languages settings',
	'PASTEBIN_LANG_SETTINGS_EXPLAIN'	=> 'On this page the available languages can be seen by ID, name, file extension and set active or not active. For 
											not active languages the highlighter cannot be used by the users in the dropdown menu.',

	// Table header - languages overview page
	'PASTEBIN_TABLE_LANG_ID' 			=> 'Language-ID',
	'PASTEBIN_TABLE_LANG_NAME' 			=> 'Name',
	'PASTEBIN_TABLE_LANG_NAME_CLEAN' 	=> 'Name (clean)',
	'PASTEBIN_TABLE_FILE_EXTENSIONS'	=> 'File extensions',
	'PASTEBIN_TABLE_LANG_ACTIVE' 		=> 'Active languages',
]);
