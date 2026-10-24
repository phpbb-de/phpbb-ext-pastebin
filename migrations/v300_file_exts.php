<?php
/**
 * @copyright (c) 2026 Crizzo <https://www.phpBB.de>
 * @license GNU General Public License, version 2 (GPL-2.0)
 */

namespace phpbbde\pastebin\migrations;

class v300_file_exts extends \phpbb\db\migration\migration
{
	public static function depends_on()
	{
		return ['\phpbbde\pastebin\migrations\v300'];
	}

	public function effectively_installed()
	{
		return $this->db_tools->sql_column_exists($this->table_prefix . 'pastebin', 'snippet_file_ext');
	}

	public function update_schema()
	{
		return [
			'add_columns' => [
				$this->table_prefix . 'pastebin' => [
					'snippet_file_ext' => ['VCHAR:16', ''],
				],
			],
		];
	}

	public function revert_schema()
	{
		return [
			'drop_columns' => [
				$this->table_prefix . 'pastebin' => [
					'snippet_file_ext',
				],
			],
		];
	}
}
