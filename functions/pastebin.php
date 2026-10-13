<?php
/**
 * @package pastebin
 * @copyright (c) 2015 gn#36 & 2026 Crizzo
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
	protected array $data;

	/** @var array|null Cache für aktive Sprachen [lang_name_clean => lang_name] */
	private ?array $active_langs = null;

	/**
	 * Constructor
	 * @param string $php_ext
	 * @param \phpbb\db\driver\driver_interface $db
	 * @param \phpbb\language\language	$language
	 * @param \phpbb\user $user
	 * @param $pastebin_table
	 * @param $pastebin_langs_table
	 */
	function __construct(
		protected \phpbb\db\driver\driver_interface $db,
		protected \phpbb\language\language $language,
		protected \phpbb\user $user,
		protected $php_ext,
		protected $pastebin_table,
		protected $pastebin_langs_table)
	{
		$this->empty_data();
	}

	/**
	 * Removes all pastebin data and replaces them by the default.
	 */
	function empty_data()
	{
		$this->data = array(
			'snippet_id' 		=> 0,
			'snippet_author' 	=> $this->user->data['user_id'],
			'snippet_time' 		=> time(),
			'snippet_prune_on' 	=> 0,
			'snippet_title' 	=> '',
			'snippet_desc' 		=> '',
			'snippet_text' 		=> '',
			'snippet_prunable' 	=> false,
			'snippet_highlight' => 'text',
			'snippet_secret'	=> 0,
			'snippet_hash'		=> '',
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
		$this->db->sql_freeresult($result);

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

	// ArrayAccess
	//

	public function offsetExists(mixed $offset): bool
	{
		return isset($this->data[$offset]);
	}

	public function offsetGet(mixed $offset): mixed
	{
		if (!isset($this->data[$offset]))
		{
			throw new \Exception('Invalid offset');
		}
		return $this->data[$offset];
	}

	public function offsetSet(mixed $offset, mixed $value): void
	{
		if (!isset($this->data[$offset]))
		{
			throw new \Exception('Invalid offset');
		}

		$this->data[$offset] = $value;
	}

	public function offsetUnset(mixed $offset): void
	{
		// still needed, even if empty
	}

	/**
	 * Returns all file extensions from active languages (for uploading)
	 */
	public function get_allowed_extensions(): array
	{
		$exts = [];

		$sql = 'SELECT lang_file_extension
		FROM ' . $this->pastebin_langs_table . '
		WHERE lang_active = 1';
		$result = $this->db->sql_query($sql);
		$exts_db = $this->db->sql_fetchrowset($result);
		$this->db->sql_freeresult($result);

		foreach ($exts_db as $row)
		{
			$exts_db_entry	= preg_replace('/[^a-z,]/', '', strtolower($row['lang_file_extension']));
			$exts			= array_merge($exts, preg_split('/,/', $exts_db_entry, -1, PREG_SPLIT_NO_EMPTY));
		}

		return array_values(array_unique($exts)) ?: ['txt'];
	}

	/**
	 * Returns all active languages as [lang_name_clean => lang_name]
	 */
	public function get_active_languages(): array
	{
		if ($this->active_langs === null)
		{
			$this->active_langs = [];

			$sql = 'SELECT lang_name, lang_name_clean
                FROM ' . $this->pastebin_langs_table . '
                WHERE lang_active = 1
                ORDER BY lang_name ASC';
			$result = $this->db->sql_query($sql);
			while ($row = $this->db->sql_fetchrow($result))
			{
				$this->active_langs[$row['lang_name_clean']] = $row['lang_name'];
			}
			$this->db->sql_freeresult($result);
		}

		return $this->active_langs;
	}

	/**
	 * Building the option html elements
	 */
	public function highlight_select(string $selected): string
	{
		$html = '';
		foreach ($this->get_active_languages() as $clean => $name)
		{
			$sel = ($clean === $selected) ? ' selected' : '';
			$html .= '<option value="' . htmlspecialchars($clean) . '"' . $sel . '>' . htmlspecialchars($name) . '</option>';
		}

		return $html;
	}

	/**
	 * Returns only active languages, if not gives a fallback
	 */
	public function validate_language(string $lang): string
	{
		$langs = $this->get_active_languages();

		if (isset($langs[$lang]))
		{
			return $lang;
		}

		return isset($langs['php']) ? 'php' : (array_key_first($langs) ?? 'text');
	}

	/**
	 * Gets the file extensions from phpbb_pastebin_langs
	 */
	public function get_file_extension(string $lang_clean): string
	{
		$sql = 'SELECT lang_file_extension
			FROM ' . $this->pastebin_langs_table . "
			WHERE lang_name_clean = '" . $this->db->sql_escape($lang_clean) . "'";
		$result = $this->db->sql_query_limit($sql, 1);
		$ext = $this->db->sql_fetchfield('lang_file_extension');
		$this->db->sql_freeresult($result);

		return $ext ? ltrim($ext, '.') : 'txt';
	}
}
