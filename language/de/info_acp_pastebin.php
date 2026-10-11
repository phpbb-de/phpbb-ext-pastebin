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
	'PASTEBIN_NAV_CONFIG' 		=> 'Pastebin - Einstellungen',
	'PASTEBIN_NAV_LANGS' 		=> 'Pastebin - Sprachen',

	'PASTEBIN_CONFIG' 			=> 'Pastebin Einstellungen',
	'PASTEBIN_CONFIG_EXPLAIN' 	=> 'Hier können Einstellungen für die Extension <em>Pastebin</em> vorgenommen werden. Eingestellt werden kann, ob geheime Snippets erstellt werden können und was der Standardwert für die Speicherdauer der Snippets ist.',

	'PASTEBIN_MAIN_SETTINGS' 			=> 'Pastebin - Einstellungen',
	'PASTEBIN_MAIN_SETTINGS_EXPLAIN' 	=> 'Richte die Hauptfunktionen der Extension <em>Pastebin</em> ein.',

	'PASTEBIN_ALLOW_SECRET_SNIPPETS' 			=> 'Erlaube geheime Snippets',
	'PASTEBIN_ALLOW_SECRET_SNIPPETS_EXPLAIN' 	=> 'Sofern aktiviert, können Benutzer auch geheime Snippets erstellen, die nur diejenigen
												anzeigen können, die den Link zum Snippet kennen.',

	'PASTEBIN_DEFAULT_PRUNE_MONTHS' 			=> 'Standard-Speicherdauer',
	'PASTEBIN_DEFAULT_PRUNE_MONTHS_EXPLAIN' 	=> 'Hiermit wird eingestellt, was bei der Speicherdauer beim Erstellen von Snippets vorausgewählt ist.',
	'PASTEBIN_MONTHS' 							=> 'Monate',

	'PASTEBIN_SETTINGS' 	=> 'Pastebin-Einstellungen',
	'PASTEBIN_UPDATED' 		=> 'Die Einstellungen der Pastebin wurden aktualisiert.',

	'PASTEBIN_LANG_CONFIG'			=> 'Pastebin - Language Configuration',
	'PASTEBIN_LANG_CONFIG_EXPLAIN'	=> 'Alle Eigenschaften einsehen und Sprachen aktivieren und deaktivieren.',

	// Lang-Keys for the Pastebin-Lang-ACP-page
	'PASTEBIN_LANG_SETTINGS'			=> 'Pastebin - Spracheinstellungen',
	'PASTEBIN_LANG_SETTINGS_EXPLAIN'	=> 'Auf dieser Seite können die verfügbaren Sprachen mit ihrer ID, Namen und Dateiendung eingesehen werden. Sie können aktiv oder nicht aktiv gesetzt werden.  
											Für nicht aktive Sprachen kann der entsprechende Highlighter vom Benutzer nicht im Dropdown-Menü ausgewählt werden.',

	// Table header - languages overview page
	'PASTEBIN_TABLE_LANG_ID' 			=> 'Sprach-ID',
	'PASTEBIN_TABLE_LANG_NAME' 			=> 'Name',
	'PASTEBIN_TABLE_LANG_NAME_CLEAN' 	=> 'Name (clean)',
	'PASTEBIN_TABLE_FILE_EXTENSIONS'	=> 'Dateiendungen',
	'PASTEBIN_TABLE_LANG_ACTIVE' 		=> 'Aktive Sprachen',
	]);
