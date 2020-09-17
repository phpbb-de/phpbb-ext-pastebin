<?php
/**
* @package pastebin
* @version 0.2.0
* @copyright (c) 2009 3Di (2007 eviL3), 2015 gn#36
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*/

namespace phpbbde\pastebin\functions;

class utility
{
	/**
	 * Geshi directory
	 *
	 * @var string
	 */
	var $geshi_dir	= '';

	/**
	 * List of geshi installed langs
	 *
	 * @var array
	 */
	var $geshi_list	= array();

	/** @var string */
	protected $php_ext;

	/**
	 * Constructor
	 * @param string $php_ext
	 */
	function __construct(
		$geshi_dir,
		$php_ext)
	{
		$this->geshi_dir	= $geshi_dir;
		$this->geshi_list	= $this->geshi_list();
		$this->php_ext 		= $php_ext;
	}


	/**
	 * Check if $needle is in one of geshis supported languages
	 */
	function geshi_check($needle)
	{
		return in_array($needle, $this->geshi_list);
	}

	/**
	 * List of all geshi langs
	 */
	function geshi_list()
	{
		$geshi_list = array();

		$d = dir($this->geshi_dir);
		while (false !== ($file = $d->read()))
		{
			if (in_array($file, array('.', '..')))
			{
				continue;
			}

			if (($substr_end = strpos($file, ".$this->php_ext")) !== false)
			{
				$geshi_list[] = substr($file, 0, $substr_end);
			}
		}
		$d->close();

		sort($geshi_list);

		return $geshi_list;
	}

	/**
	 * Highlight select box for geshi languages
	 */
	function highlight_select($default = 'text')
	{
		// Programming languages
		// these are used by geshi
		$programming_langs = array(
			'text'				=> 'Text',
			'php'				=> 'PHP',
			'sql'				=> 'SQL',
			'html4strict'		=> 'HTML',
			'css'				=> 'CSS',
			'javascript'		=> 'Javascript',
			'xml'				=> 'XML',
			'diff'				=> 'Diff',
			'robots'			=> 'robots.txt',

			/**
			 * Wenn eine weitere Sprache aktiviert werden soll, muss die Datei in den includes/geshi/ Ordner
			 * kopiert werden, und der Eintrag der Sprachdatei wieder nach aktiviert werden.
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

		if (!in_array($default, $this->geshi_list))
		{
			$default = 'text';
		}

		$output = '';
		foreach ($programming_langs as $code => $name)
		{
			if (in_array($code, $this->geshi_list))
			{
				$output .= '<option' . (($default == $code) ? ' selected="selected"' : '') . ' value="' . htmlentities($code, ENT_QUOTES) . '">' . $name . '</option>';
			}
		}

		return $output;
	}
}
