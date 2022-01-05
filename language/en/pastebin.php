<?php
/**
*
* pastebin
*
* @package language
* @version 0.1.3
* @copyright (c) 2007 eviL3, gn#36
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
	'PASTEBIN_COPY_PASTE'				=> 'Source code',
	'PASTEBIN_COPY_PASTE_EXPLAIN'		=> 'Here, you can copy the source and paste it into your preferred editor.',
	'PASTEBIN_SELECT_CODE'				=> 'Mark all source code',

	'PASTEBIN_ELLIPSIS'					=> '…',

	'PASTEBIN_CONFIRM_CODE_WRONG'		=> 'The confirmation code you entered was incorrect.',

	'PASTEBIN_DELETE_SNIPPET'			=> 'Delete entry',
	'PASTEBIN_DELETE_SNIPPET_CONFIRM'	=> 'Do you really want to delete the entry entitled “%s”? This cannot be undone.',
	'PASTEBIN_DELETE_SNIPPET_EXPLAIN'	=> 'Deletes the entry from the database. This cannot be undone.',
	'PASTEBIN_DOWNLOAD_SNIPPET'			=> 'Download as file',
	'PASTEBIN_DOWNLOAD_SNIPPET_EXPLAIN'	=> 'Alternatively, you can %sdownload%s the whole entry as a file.',

	'DISALLOWED_EXTENSION'	=> 'The file extension %s is not allowed.',

	'PASTEBIN_ERR_NO_BODY'				=> 'You did not enter any code or a valid file for upload.',
	'PASTEBIN_ERR_NO_TITLE'				=> 'You did not enter a title for your entry.',
	'PASTEBIN_ERR_NO_DESC'				=> 'You did not enter a description for your entry.',
	'PASTEBIN_FORM_INVALID'				=> 'Invalid form.',

	'PASTEBIN_HIGHLIGHT_LANG'			=> 'Syntax highlighting',

	'PASTEBIN_LATEST_SNIPPETS'			=> 'Last entries',

	'PASTEBIN_MODERATE_SNIPPET'			=> 'Edit entry',
	'PASTEBIN_MODERATE_SNIPPET_EXPLAIN'	=> 'Edit or delete entry.',

	'PASTEBIN_NO_VALID_SNIPPET'			=> 'You did not select a valid entry.',
	'PASTEBIN_NO_SNIPPETS'				=> 'There are currently no entries in the pastebin.',

	'PASTEBIN'					=> 'Pastebin',
	'PASTEBIN_AUTH_NO_VIEW'		=> 'You are not authorized to view this page.',
	'PASTEBIN_AUTH_NO_POST'		=> 'You are not authorized to enter a new entry.',
	'PASTEBIN_CONFIRM'			=> 'Spambot countermeasures',
	'PASTEBIN_CONFIRM_EXPLAIN'	=> 'Please solve the spambot countermeasures to submit your entry.',
	'PASTEBIN_EMPTY_FILEUPLOAD'	=> 'The uploaded file is empty.',
	'PASTEBIN_EXPLAIN'			=> 'In the pastebin, you can paste code snippets or whole files, for example to add a link to them to a support topic.',
	'PASTEBIN_HELLO'			=> 'Did someone direct you here?',
	'PASTEBIN_HELLO_EXPLAIN'	=> 'If you were directed here, please add the desired file or enter the code into the text area below and send the url to the person who sent you here.',
	'PASTEBIN_INSTALLED'		=> 'Pastebin was successfully installed.',
	'PASTEBIN_INVALID_FILENAME'	=> '%s is an invalid filename',
	'PASTEBIN_NOT_UPLOADED'		=> 'The upload of the file failed.',
	'PASTEBIN_NO_AUTH'			=> 'Information',
	'PASTEBIN_NO_AUTH_EXPLAIN'	=> 'You cannot add new entries to the pastebin.',
	'PASTEBIN_NO_AUTH_GUEST_EXPLAIN' => 'You have to log in or register to add new entries to the pastebin.',
	'PASTEBIN_PARTIAL_UPLOAD'	=> 'The file was only partially uploaded.',
	'PASTEBIN_PHP_SIZE_NA'		=> 'The file is too large.',
	'PASTEBIN_PHP_SIZE_OVERRUN'	=> 'The file is larger than the allowed maximum of %d MiB.',
	'PASTEBIN_POST'				=> 'New entry',
	'PASTEBIN_POST_EXPLAIN'		=> 'Please enter a title, choose the language to highlight, and choose the storage duration. Optionally, you can add a description of the entry. Finally, you upload your code as a file <em>or</em> enter it in the text area below.',
	'PASTEBIN_TOO_MANY'			=> 'You exeeded the maximum number of login trials. Please try again later.',
	'PASTEBIN_UPDATED'			=> 'Pastebin was successfully updated to the latest version.',
	'PASTEBIN_UPLOAD'			=> 'Upload file',
	'PASTEBIN_UPLOAD_EXPLAIN'	=> 'If you selected a file for upload, code entered into the text area below will be ignored!',
	'PASTEBIN_VIEW'				=> 'View entry - %s',
	'PASTEBIN_WRONG_FILESIZE'	=> 'The file is too large (Maximum file size is %1d %2s)',

	'PASTEBIN_PRUNING_MONTHS'			=> 'Storage duration',
	'PASTEBIN_PRUNING_MONTH_SHORT'		=> 'months',

	'PASTEBIN_RETURN_PASTEBIN'			=> '%sReturn to Pastebin%s',
	'PASTEBIN_RETURN_SNIPPET'			=> '%sShow entry%s',

	'PASTEBIN_SHORT_PRUNABLE'			=> 'Prunable',
	'PASTEBIN_INFINITE'					=> 'Infinite',
	'PASTEBIN_SNIPPET_NEW'				=> 'New entry',
	'PASTEBIN_SNIPPET_DESC'				=> 'Description',
	'PASTEBIN_SNIPPET_DOWNLOAD'			=> 'Download entry',
	'PASTEBIN_SNIPPET_HILIT'				=> 'Show highlighted entry',
	'PASTEBIN_SNIPPET_HIGHLIGHT'			=> 'Syntax highlighting',
	'PASTEBIN_SNIPPET_MODERATED'			=> 'The entry was sucessfully edited.',
	'PASTEBIN_SNIPPET_TEXT'				=> 'Your code',
	'PASTEBIN_SNIPPET_TITLE'				=> 'Title',
	'PASTEBIN_SNIPPET_CREATION_TIME'		=> 'Entry created on',
	'PASTEBIN_SNIPPET_PRUNE_TIME'		=> 'Entry will be automatically deleted',
	'PASTEBIN_SNIPPET_PLAIN'				=> 'Show simple entry.',
	'PASTEBIN_SNIPPET_PRUNABLE'			=> 'Entry prunable',
	'PASTEBIN_SNIPPET_PRUNABLE_EXPLAIN'	=> 'If this option is disabled, the entry will not be entered in the monthly list of prunable entries.',
	'PASTEBIN_SNIPPET_SUBMITTED'			=> 'Your entry was sucessfully created.',
	'PASTEBIN_SNIPPET_SAVE'				=> 'Save edited snippet',

	// Language keys for Syntax highlighting dropdown
	'PASTEBIN_LANGS_TEXT'		=> 'Text',
	'PASTEBIN_LANGS_PHP' 		=> 'PHP',
	'PASTEBIN_LANGS_SQL' 		=> 'SQL',
	'PASTEBIN_LANGS_HTML5' 		=> 'HTML',
	'PASTEBIN_LANGS_CSS' 		=> 'CSS',
	'PASTEBIN_LANGS_JAVASCRIPT'	=> 'JavaScript',
	'PASTEBIN_LANGS_XML' 		=> 'XML',
	'PASTEBIN_LANGS_DIFF' 		=> 'diff',
	'PASTEBIN_LANGS_ROBOTS'		=> 'Robots.txt',
));
