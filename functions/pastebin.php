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
	 * @param \phpbb\db\driver\driver_interface $db
	 * @param \phpbb\user $user
	 * @param $pastebin_table
	 */
	function __construct(
		protected \phpbb\db\driver\driver_interface $db,
		protected \phpbb\user $user,
		protected $pastebin_table)
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
}
