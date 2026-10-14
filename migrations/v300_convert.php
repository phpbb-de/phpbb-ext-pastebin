<?php
/**
 *
 * Pastebin extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2026 Crizzo <https://www.phpBB.de>
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace phpbbde\pastebin\migrations;

class v300_convert extends \phpbb\db\migration\migration
{
	public static function depends_on()
	{
		return [
			'\phpbbde\pastebin\migrations\v300',
			'\phpbbde\pastebin\migrations\v300_salt',
		];
	}

	public function update_data()
	{
		return [
			['custom', [[$this, 'generate_hashes']]],
		];
	}

	public function generate_hashes(): void
	{
		$snippets_table = $this->table_prefix . 'pastebin';
		$users_table    = $this->table_prefix . 'users';
		$salt           = $this->config['pastebin_salt'];
		$batch          = 500;

		if ($salt === '')
		{
			return;
		}

		// Load already given hashes, allows restart on failure
		$used = [];
		$sql = 'SELECT snippet_hash FROM ' . $snippets_table . " WHERE snippet_hash <> ''";
		$result = $this->db->sql_query($sql);
		while ($row = $this->db->sql_fetchrow($result))
		{
			$used[$row['snippet_hash']] = true;
		}
		$this->db->sql_freeresult($result);

		do
		{
			$sql = 'SELECT s.snippet_id, s.snippet_title, s.snippet_time, u.username_clean
			FROM ' . $snippets_table . ' s
			LEFT JOIN ' . $users_table . " u
				ON u.user_id = s.snippet_author
			WHERE s.snippet_hash = ''";
			$result = $this->db->sql_query_limit($sql, $batch);
			$rows = $this->db->sql_fetchrowset($result);
			$this->db->sql_freeresult($result);

			foreach ($rows as $row)
			{
				$hash = hash('sha256', $row['snippet_title'] . $row['username_clean'] . (int) $row['snippet_time'] . $salt);

				if (isset($used[$hash]))
				{
					$hash = hash('sha256', $hash . $row['snippet_id']);
				}
				$used[$hash] = true;

				$sql = 'UPDATE ' . $snippets_table . "
				SET snippet_hash = '" . $this->db->sql_escape($hash) . "'
				WHERE snippet_id = " . (int) $row['snippet_id'];
				$this->db->sql_query($sql);
			}
		}
		while (count($rows) === $batch);
	}
}
