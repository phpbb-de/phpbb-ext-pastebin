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
	'COPY_PASTE'				=> 'Source code',
	'COPY_PASTE_EXPLAIN'		=> 'Here, you can copy the source and paste it into your preferred editor.',
	'SELECT_CODE'				=> 'Mark all source code',

	'DELETE_SNIPPET'			=> 'Delete entry',
	'DELETE_SNIPPET_CONFIRM'	=> 'Do you really want to delete the entry entitled “%s”? This cannot be undone.',
	'DELETE_SNIPPET_EXPLAIN'	=> 'Deletes the entry from the database. This cannot be undone.',
	'DOWNLOAD_SNIPPET'			=> 'Download as file',
	'DOWNLOAD_SNIPPET_EXPLAIN'	=> 'Alternatively, you can %sdownload%s the whole entry as a file.',

	'ERR_NO_BODY'				=> 'You did not enter any code or upload a valid file.',
	'ERR_NO_TITLE'				=> 'You did not enter a title for your entry.',
	'ERR_NO_DESC'				=> 'You did not enter a description for your entry.',

	'HIGHLIGHT_LANG'			=> 'Syntax highlighting',

	'LATEST_SNIPPETS'			=> 'Last entries',

	'MODERATE_SNIPPET'			=> 'Edit entry',
	'MODERATE_SNIPPET_EXPLAIN'	=> 'Edit or delete entry.',

	'NO_VALID_SNIPPET'			=> 'You did not select a valid entry.',
	'NO_SNIPPETS'				=> 'There are currently no entries in the pastebin.',

	'PASTEBIN'					=> 'Pastebin',
	'PASTEBIN_AUTH_NO_VIEW'		=> 'You are not authorized to view this page.',
	'PASTEBIN_AUTH_NO_POST'		=> 'You are not authorized to enter a new entry.',
	'PASTEBIN_CONFIRM'			=> 'Visual confirmation',
	'PASTEBIN_CONFIRM_EXPLAIN'	=> 'Enter the code exactly as you see it; capitalization will be ignored, zero is not used.',
	'PASTEBIN_DISALLOWED_EXTENSION'	=> 'The file extension %s is not allowed.',
	'PASTEBIN_EMPTY_FILEUPLOAD'	=> 'The uploaded file is empty.',
	'PASTEBIN_EXPLAIN'			=> 'In the pastebin, you can paste code snippets or whole files, for example to add a link to them to a support topic.',
	'PASTEBIN_HELLO'			=> 'Did someone direct you here?',
	'PASTEBIN_HELLO_EXPLAIN'	=> 'If you were directed here, please add the desired file or enter the code into the text area below and send the url to the person who sent you here.',
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
	'PASTEBIN_UPLOAD'			=> 'Upload file',
	'PASTEBIN_UPLOAD_EXPLAIN'	=> 'If you selected a file for upload, code entered into the text area below will be ignored!',
	'PASTEBIN_VIEW'				=> 'View entry - %s',
	'PASTEBIN_WRONG_FILESIZE'	=> 'The file is too large (Maximum file size is %1d %2s)',

	'PRUNING_MONTHS'			=> 'Storage duration',
	'PRUNING_MONTH_SHORT'		=> 'months',

	'RETURN_PASTEBIN'			=> '%sReturn to Pastebin%s',
	'RETURN_SNIPPET'			=> '%sShow entry%s',

	'SHORT_PRUNABLE'			=> 'Prunable',
	'SNIPPET_NEW'				=> 'New entry',
	'SNIPPET_DESC'				=> 'Description',
	'SNIPPET_DOWNLOAD'			=> 'Download entry',
	'SNIPPET_HILIT'				=> 'Show highlighted entry',
	'SNIPPET_HIGHLIGHT'			=> 'Syntax highlighting',
	'SNIPPET_MODERATED'			=> 'The entry was sucessfully edited.',
	'SNIPPET_TEXT'				=> 'Your code',
	'SNIPPET_TITLE'				=> 'Title',
	'SNIPPET_PLAIN'				=> 'Show simple entry.',
	'SNIPPET_PRUNABLE'			=> 'Entry prunable',
	'SNIPPET_PRUNABLE_EXPLAIN'	=> 'If this option is disabled, the entry will not be entered in the monthly list of prunable entries.',
	'SNIPPET_SUBMITTED'			=> 'Your entry was sucessfully created.',
));

// these are used by geshi
$lang['PASTEBIN_LANGUAGES'] = array(
	'text'				=> 'Text',
	'php'				=> 'PHP',
	'sql'				=> 'SQL',
	'html4strict'		=> 'HTML',
	'css'				=> 'CSS',
	'javascript'		=> 'Javascript',
//	'java'				=> 'Java',
	'xml'				=> 'XML',
//	'asp'				=> 'ASP',
//	'c'					=> 'C',
//	'cpp'				=> 'C++',
//	'csharp'			=> 'C#',
	'perl'				=> 'Perl',
//	'vb'				=> 'Visual Basic',
	'diff'				=> 'Diff',
	'robots'			=> 'robots.txt',
	'smarty'			=> 'Smarty',

/**
* You can activate further languages by uncommenting any of these lines.
*/

/*
	'actionscript'		=> 'ActionScript',
	'ada'				=> 'Ada',
	'apache'			=> 'Apache',
	'applescript'		=> 'AppleScript',
	'asm'				=> 'x86 Assembler',
	'autoit'			=> 'AutoIt',
	'bash'				=> 'Bash',
	'blitzbasic'		=> 'BlitzBasic',
	'bnf'				=> 'BNF',
	'c_mac'				=> 'C (Mac)',
	'caddcl'			=> 'CAD DCL',
	'cadlisp'			=> 'CAD Lisp',
	'cfdg'				=> 'CFDG',
	'cfm'				=> 'ColdFusion',
	'cpp-qt'			=> 'C++ (QT)',
	'css-gen.cfg'		=> 'C#',
	'c_mac'				=> 'C (Mac)',
	'd'					=> 'D',
	'delphi'			=> 'Delphi',
	'div'				=> 'DIV',
	'dos'				=> 'DOS',
	'eiffel'			=> 'Eiffel',
	'fortran'			=> 'Fortran',
	'freebasic'			=> 'FreeBasic',
	'gml'				=> 'GML',
	'groovy'			=> 'Groovy',
	'idl'				=> 'Uno Idl',
	'ini'				=> 'INI',
	'inno'				=> 'Inno',
	'io'				=> 'Io',
	'java5'				=> 'Java(TM) 2 Platform Standard Edition 5.0',
	'latex'				=> 'LaTeX',
	'lisp'				=> 'Lisp',
	'lua'				=> 'Lua',
	'matlab'			=> 'Matlab M',
	'mirc'				=> 'mIRC Scripting',
	'mpasm'				=> 'Microchip Assembler',
	'mysql'				=> 'MySQL',
	'nsis'				=> 'NSIS',
	'objc'				=> 'Objective C',
	'ocaml-brief'		=> 'OCaml',
	'ocaml'				=> 'OCaml',
	'oobas'				=> 'OpenOffice.org Basic',
	'oracle8'			=> 'Oracle 8 SQL',
	'pascal'			=> 'Pascal',
	'php-brief'			=> 'PHP (brief)',
	'ruby'				=> 'Ruby',
	'sas'				=> 'SAS',
	'scheme'			=> 'Scheme',
	'sdlbasic'			=> 'sdlBasic',
	'smalltalk'			=> 'Smalltalk',
	'tcl'				=> 'TCL',
	'thinbasic'			=> 'thinBasic',
	'tsql'				=> 'T-SQL',
	'plsql'				=> 'PL/SQL',
	'python'			=> 'Python',
	'qbasic'			=> 'QBasic/QuickBASIC',
	'rails'				=> 'Rails',
	'reg'				=> 'Microsoft Registry',
	'vbnet'				=> 'vb.net',
	'vhdl'				=> 'VHDL',
	'visualfoxpro'		=> 'Visual Fox Pro',
	'winbatch'			=> 'Winbatch',
	'xpp'				=> 'X++',
	'z80'				=> 'ZiLOG Z80 Assembler',
*/
);
