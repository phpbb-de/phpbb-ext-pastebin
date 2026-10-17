<?php
/**
*
* pastebin [German]
*
* @package language
* @version 3.0.0
* Translator Mahony http://www.sportschulekang.de and http://nationsofmetal.na.funpic.de/forum/
* @copyright (c) 2026 phpBB.de
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
	'PASTEBIN_COPY_PASTE'				=> 'Quellcode',
	'PASTEBIN_COPY_PASTE_EXPLAIN'		=> 'Hier kannst du den Code kopieren und ihn in deinen bevorzugten Editor einfügen.',
	'PASTEBIN_SELECT_CODE'				=> 'Quellcode markieren',

	'PASTEBIN_ELLIPSIS'					=> '…',

	'PASTEBIN_CONFIRM_CODE_WRONG'		=> 'Der eingegebene Bestätigungscode ist falsch.',

	'PASTEBIN_DELETE_SNIPPET'			=> 'Eintrag löschen',
	'PASTEBIN_DELETE_SNIPPET_CONFIRM'	=> 'Soll der Eintrag mit dem Titel “%s” wirklich gelöscht werden? Diese Aktion kann nicht rückgängig gemacht werden.',
	'PASTEBIN_DELETE_SNIPPET_EXPLAIN'	=> 'Löscht den Eintrag aus der Datenbank. Diese Aktion kann nicht rückgängig gemacht werden.',
	'PASTEBIN_DOWNLOAD_SNIPPET'			=> 'Eintrag herunterladen',
	'PASTEBIN_DOWNLOAD_SNIPPET_EXPLAIN'	=> 'Alternativ kannst du den gesamten Eintrag auch als Datei %sherunterladen%s.',
	'PASTEBIN_SNIPPET_SECRET'			=> 'Eintrag privat schalten',
	'PASTEBIN_SNIPPET_SECRET_SHORT'		=> 'Privat',

	'DISALLOWED_EXTENSION'	=> 'Die Dateierweiterung %s ist nicht erlaubt',

	'PASTEBIN_ERR_NO_BODY'				=> 'Du hast keinen Code eingefügt oder keine gültige Datei zum Upload ausgewählt.',
	'PASTEBIN_ERR_NO_TITLE'				=> 'Du hast keinen Titel für den Eintrag eingegeben.',
	'PASTEBIN_ERR_NO_DESC'				=> 'Du hast keine Beschreibung für den Eintrag eingegeben.',
	'PASTEBIN_ERR_WRONG_SECRET'			=> 'Ungültiger Wert für privates Snippet.',
	'PASTEBIN_FORM_INVALID'				=> 'Ungültiges Formular.',

	'PASTEBIN_HIGHLIGHT_LANG'			=> 'Syntaxhervorhebung',

	'PASTEBIN_LATEST_SNIPPETS'			=> 'Letzte Einträge',

	'PASTEBIN_MODERATE_SNIPPET'			=> 'Eintrag bearbeiten',
	'PASTEBIN_MODERATE_SNIPPET_EXPLAIN'	=> 'Eintrag bearbeiten oder löschen.',

	'PASTEBIN_NO_VALID_SNIPPET'			=> 'Du hast keinen Eintrag ausgewählt.',
	'PASTEBIN_NO_SNIPPETS'				=> 'Es gibt derzeit keine Einträge im Pastebin.',

	'PASTEBIN'					=> 'Pastebin',
	'PASTEBIN_AUTH_NO_VIEW'		=> 'Du hast keine Berechtigung, die Seite anzuzeigen.',
	'PASTEBIN_AUTH_NO_POST'		=> 'Du hast keine Berechtigung, einen Eintrag zu erstellen.',
	'PASTEBIN_CONFIRM'			=> 'Anti-Spam-Bot-Maßnahmen',
	'PASTEBIN_CONFIRM_EXPLAIN'	=> 'Bitte löse die Anti-Spam-Bot-Maßnahmen, damit dein Eintrag abgesendet werden kann.',
	'PASTEBIN_EMPTY_FILEUPLOAD'	=> 'Die hochgeladene Datei ist leer.',
	'PASTEBIN_EXPLAIN'			=> 'Im Pastebin kannst du Codeabschnitte oder auch ganze Dateien ablegen, um sie zum Beispiel später in einem Supportthema zu verlinken.',
	'PASTEBIN_HELLO'			=> 'Hat dich jemand hierher geschickt?',
	'PASTEBIN_HELLO_EXPLAIN'	=> 'Wenn du hier her geschickt wurdest, füge bitte die gewünschte Datei oder den Code in das unten stehende Textfeld ein und gib die URL der Person, die dich hierher geschickt hat.',
	'PASTEBIN_INSTALLED'		=> 'Das Pastebin wurde erfolgreich installiert.',
	'PASTEBIN_INVALID_FILENAME'	=> '%s ist ein ungültiger Dateiname',
	'PASTEBIN_NOT_UPLOADED'		=> 'Upload der Datei fehlgeschlagen.',
	'PASTEBIN_NO_AUTH'			=> 'Information',
	'PASTEBIN_NO_AUTH_EXPLAIN'	=> 'Du bist nicht befugt, neue Snippets hinzuzufügen.',
	'PASTEBIN_NO_AUTH_GUEST_EXPLAIN' => 'Du musst dich anmelden um neue Snippets hinzufügen zu können.',
	'PASTEBIN_PARTIAL_UPLOAD'	=> 'Die Datei wurde nur teilweise hochgeladen.',
	'PASTEBIN_PHP_SIZE_NA'		=> 'Die Datei überschreitet die maximale Dateigröße.',
	'PASTEBIN_PHP_SIZE_OVERRUN'	=> 'Die Datei überschreitet die maximale Dateigröße von %d MiB.',
	'PASTEBIN_POST'				=> 'Neuen Eintrag erstellen',
	'PASTEBIN_POST_EXPLAIN'		=> 'Bitte gib nun einen Titel für deinen Eintrag an und wähle die Syntaxhervorhebung und Speicherdauer aus. Optional kannst du auch noch eine Beschreibung für deinen Eintrag angegeben. Zum Schluss lädst du deinen Code als Datei hoch <em>oder</em> fügst ihn einfach per Copy&Paste in das Eingabefeld ein.',
	'PASTEBIN_TOO_MANY'			=> 'Du hast die maximal zulässige Anzahl von Anmeldeversuchen überschritten. Bitte versuche es zu einem späteren Zeitpunkt erneut.',
	'PASTEBIN_UPDATED'			=> 'Das Pastebin wurde erfolgreich auf die neueste Version upgedatet.',
	'PASTEBIN_UPLOAD'			=> 'Datei hochladen',
	'PASTEBIN_UPLOAD_EXPLAIN'	=> 'Hast du eine Datei zum Hochladen ausgewählt, dann wird evtl. vorhandener Code im unteren Eingabefeld ignoriert!',
	'PASTEBIN_VIEW'				=> 'Eintrag ansehen - %s',
	'PASTEBIN_WRONG_FILESIZE'	=> 'Die Datei ist zu groß. (Maximale Dateigröße ist %1d %2s)',

	'PASTEBIN_PRUNING_DURATION'			=> 'Speicherdauer',

	'PASTEBIN_RETURN_PASTEBIN'			=> '%sZurück zum Pastebin%s',
	'PASTEBIN_RETURN_SNIPPET'			=> '%sEintrag anzeigen%s',

	'PASTEBIN_SHORT_PRUNABLE'			=> 'Prunable - automatisch löschbar',
	'PASTEBIN_INFINITE'					=> 'Unendlich',
	'PASTEBIN_SNIPPET_NEW'				=> 'Neuer Eintrag',
	'PASTEBIN_SNIPPET_DESC'				=> 'Beschreibung',
	'PASTEBIN_SNIPPET_DOWNLOAD'			=> 'Eintrag herunterladen',
	'PASTEBIN_SNIPPET_HILIT'			=> 'zeige hervorgehobenen Eintrag',
	'PASTEBIN_SNIPPET_HIGHLIGHT'		=> 'Syntaxhervorhebung',
	'PASTEBIN_SNIPPET_MODERATED'		=> 'Das Snippet wurde erfolgreich bearbeitet.',
	'PASTEBIN_SNIPPET_TEXT'				=> 'Dein Code',
	'PASTEBIN_SNIPPET_TITLE'			=> 'Titel',
	'PASTEBIN_SNIPPET_CREATION_TIME'	=> 'Snippet erstellt',
	'PASTEBIN_SNIPPET_PRUNE_TIME'		=> 'Snippet wird automatisch gelöscht',
	'PASTEBIN_SNIPPET_PLAIN'			=> 'zeige einfaches Snippet',
	'PASTEBIN_SNIPPET_PRUNABLE'			=> 'Eintrag prunable - automatisch löschbar',
	'PASTEBIN_SNIPPET_PRUNABLE_EXPLAIN'	=> 'Wenn diese Option deaktiviert ist, wird der Eintrag nicht in die monatlich automatisch zu löschenden Einträge aufgenommen.',
	'PASTEBIN_SNIPPET_SUBMITTED'		=> 'Dein Eintrag wurde erfolgreich erstellt.',
	'PASTEBIN_SNIPPET_SAVE'				=> 'Änderungen speichern',

	'PASTEBIN_UNIT_DAYS'						=> 'Tage',
	'PASTEBIN_UNIT_WEEKS'						=> 'Wochen',
	'PASTEBIN_UNIT_MONTHS'						=> 'Monate',
	'PASTEBIN_PRUNE_LIMITS'						=> 'Erlaubt: %1$d bis %2$d Tage',

	// Language keys for Syntax highlighting dropdown
	'PASTEBIN_LANGS_BASE'		=> 'Base',
	'PASTEBIN_LANGS_BLADE'		=> 'Blade',
	'PASTEBIN_LANGS_CSS' 		=> 'CSS',
	'PASTEBIN_LANGS_DIFF' 		=> 'diff',
	'PASTEBIN_LANGS_DOCCOMMENT'	=> 'DocComment',
	'PASTEBIN_LANGS_GDSCRIPT'	=> 'GDScript',
	'PASTEBIN_LANGS_HTML' 		=> 'HTML',
	'PASTEBIN_LANGS_JAVASCRIPT'	=> 'JavaScript',
	'PASTEBIN_LANGS_JSON' 		=> 'Json',
	'PASTEBIN_LANGS_PHP' 		=> 'PHP',
	'PASTEBIN_LANGS_ROBOTS'		=> 'Robots.txt',
	'PASTEBIN_LANGS_SQL' 		=> 'SQL',
	'PASTEBIN_LANGS_TEXT'		=> 'Text',
	'PASTEBIN_LANGS_TWIG' 		=> 'Twig',
	'PASTEBIN_LANGS_XML' 		=> 'XML',
	'PASTEBIN_LANGS_YAML' 		=> 'Yaml',

	// FAQ on pastebin_post.html
	'PASTEBIN_FAQ_TAG' 		=> 'Tag',
	'PASTEBIN_FAQ_DESC'		=> 'Beschreibung',

	'PASTEBIN_FAQ_EM'			=> 'Mit diesem Tag um deinen Code wird der Abschnitt <em>kursiv</em> dargestellt e.g. <pre>{_ echo "test" _}</pre>',
	'PASTEBIN_FAQ_STRONG'		=> 'Mit diesem Tag um deinen Code wird der Abschnitt <strong>fett</strong> dargestellt e.g. <pre>{* echo "test" *}</pre>',
	'PASTEBIN_FAQ_BLURRED'		=> 'Mit diesem Tag um deinen Code wird der Abschnitt verschwommen dargestellt e.g. <pre>{~ echo "test" ~}</pre>',
	'PASTEBIN_FAQ_ADDITION'		=> 'Mit diesem Tag können Zeilen oder Code-Abschnitte markiert werden, die ergänzt werden sollen, sie sind grün hinterlegt e.g. <pre>{+ echo "test" +}</pre>',
	'PASTEBIN_FAQ_DELETION'		=> 'Mit diesem Tag können Zeilen oder Code-Abschnitte markiert werden, die gelöscht werden sollen, sie sind rot hinterlegt e.g. <pre>{- echo "test" -}</pre>',
));
