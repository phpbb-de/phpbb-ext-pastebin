<?php
/**
 * @package pastebin
 * @copyright (c) 2015 gn#36
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 */

namespace phpbbde\pastebin\functions;

/**
 * Class for database interaction with pastebin entries
 *
 * @author gn#36
 *
 */
class pastebin implements \ArrayAccess
{
	/** @var array */
	protected $data;

	/** @var array */
	protected $file_ext;

	/** @var \phpbb\db\driver\driver_interface */
	protected $db;

	/** @var \phpbb\user */
	protected $user;

	/** @var string */
	protected $pastebin_table;

	function __construct(\phpbb\db\driver\driver_interface $db, \phpbb\user $user, $pastebin_table)
	{
		$this->db = $db;
		$this->user = $user;
		$this->pastebin_table = $pastebin_table;
		$this->empty_data();

		$this->file_ext = array(
			'text'				=> 'txt',
			'php'				=> 'php',
			'sql'				=> 'sql',
			'html4strict'		=> 'htm',
			'css'				=> 'css',
			'javascript'		=> 'js',
			'java'				=> 'java',
			'xml'				=> 'xml',
			'asp'				=> 'asp',
			'c'					=> 'c',
			'cpp'				=> 'cpp',
			'csharp'			=> 'cs',
			'perl'				=> 'pl',
			'vb'				=> 'vbs',
			'diff'				=> 'diff',
			'robots'			=> 'txt',
			'smarty'			=> 'html',

			'actionscript'		=> 'as',
			'ada'				=> 'ada',
			'apache'			=> 'txt',
			'applescript'		=> 'scrpt',
			'asm'				=> 'asm',
			'autoit'			=> 'txt',
			'bash'				=> 'sh',
			'blitzbasic'		=> 'bas',
			'bnf'				=> 'bnf',
			'c_mac'				=> 'c',
			'caddcl'			=> 'dcl',
			'cadlisp'			=> 'lisp',
			'cfdg'				=> 'cfd',
			'cfm'				=> 'cfm',
			'cpp-qt'			=> 'cpp',
			'css-gen.cfg'		=> 'cfg',
			'c_mac'				=> 'c',
			'd'					=> 'd',
			'delphi'			=> 'dpr',
			'div'				=> 'div',
			'dos'				=> 'bat',
			'eiffel'			=> 'E',
			'fortran'			=> 'F',
			'freebasic'			=> 'bas',
			'gml'				=> 'gml',
			'groovy'			=> 'groovy',
			'idl'				=> 'idl',
			'ini'				=> 'ini',
			'inno'				=> 'ino',
			'io'				=> 'io',
			'java5'				=> 'java',
			'latex'				=> 'tex',
			'lisp'				=> 'lsp',
			'lua'				=> 'lua',
			'matlab'			=> 'm',
			'mirc'				=> 'mrc',
			'mpasm'				=> 'asm',
			'mysql'				=> 'sql',
			'nsis'				=> 'nsh',
			'objc'				=> 'C',
			'ocaml-brief'		=> 'ml',
			'ocaml'				=> 'ml',
			'oobas'				=> 'bas',
			'oracle8'			=> 'sql',
			'pascal'			=> 'p',
			'php-brief'			=> 'php',
			'ruby'				=> 'rb',
			'sas'				=> 'sas',
			'scheme'			=> 's',
			'sdlbasic'			=> 'bas',
			'smalltalk'			=> 'st',
			'tcl'				=> 'tcl',
			'thinbasic'			=> 'bas',
			'tsql'				=> 'sql',
			'plsql'				=> 'sql',
			'python'			=> 'py',
			'qbasic'			=> 'bas',
			'rails'				=> 'rb',
			'reg'				=> 'reg',
			'vbnet'				=> 'vbs',
			'vhdl'				=> 'vhdl',
			'visualfoxpro'		=> 'fky',
			'winbatch'			=> 'bat',
			'xpp'				=> 'xpp',
			'z80'				=> 'z80',
		);

	}

	/**
	 * Removes all pastebin data and replaces them by the default.
	 */
	function empty_data()
	{
		$this->data = array(
			'snippet_id' => 0,
			'snippet_author' => $this->user->data['user_id'],
			'snippet_time' => time(),
			'snippet_prune_on' => 0,
			'snippet_title' => '',
			'snippet_desc' => '',
			'snippet_text' => '',
			'snippet_prunable' => false,
			'snippet_highlight' => 'text',
		);
	}

	/**
	 * Load pastebin from DB. Returns true if entry was found, false otherwise
	 * @param int $id
	 * @return boolean
	 */
	function load($id)
	{
		$sql = 'SELECT * FROM ' . $this->pastebin_table . ' WHERE snippet_id = ' . (int) $id;
		$result = $this->db->sql_query($sql);
		$row = $this->db->sql_fetchrow($result);
		if ($row)
		{
			$this->data = $row;
			return true;
		}

		return false;
	}

	/**
	 * Load changes from array. Unknown entries are ignored.
	 *
	 * @param array $data
	 */
	function load_from_array($data)
	{
		foreach ($this->data as $key => $value)
		{
			if (isset($data[$key]))
			{
				$this->data[$key] = $data[$key];
			}
		}
	}

	/**
	 * Store changes in the database
	 */
	function submit()
	{
		if ($this->data['snippet_id'])
		{
			// Update
			$sql = 'UPDATE ' . $this->pastebin_table . ' SET ' . $this->db->sql_build_array('UPDATE', $this->data) . ' WHERE snippet_id = ' . (int) $this->data['snippet_id'];
			$this->db->sql_query($sql);
		}
		else
		{
			// Insert
			$row = $this->data;
			unset($row['snippet_id']);
			$sql = 'INSERT INTO ' . $this->pastebin_table . ' ' . $this->db->sql_build_array('INSERT', $row);
			$this->db->sql_query($sql);
			$this->data['snippet_id'] = $this->db->sql_nextid();
		}
	}

	/**
	 * Deletes the current snippet from the database
	 */
	function delete()
	{
		$sql = 'DELETE FROM ' . $this->pastebin_table . '
			WHERE snippet_id = ' . (int) $this->data['snippet_id'];
		$this->db->sql_query($sql);
		$this->empty_data();
	}

	/**
	 * Returns file extension for this entry depending on syntax highlighting.
	 *
	 * This will probably not always be correct, but more often than always using "txt".
	 */
	function file_ext()
	{
		if (isset($this->file_ext[$this->data['snippet_highlight']]))
		{
			return $this->file_ext[$this->data['snippet_highlight']];
		}
		return 'txt';
	}

	// ArrayAccess
	//

	function offsetExists($offset)
	{
		return isset($this->data[$offset]);
	}

	function offsetGet($offset)
	{
		if (!isset($this->data[$offset]))
		{
			throw new \Exception('Invalid offset');
		}
		return $this->data[$offset];
	}

	function offsetSet($offset, $value)
	{
		if (!isset($this->data[$offset]))
		{
			throw new \Exception('Invalid offset');
		}

		$this->data[$offset] = $value;
	}

	function offsetUnset($offset)
	{

	}
}
