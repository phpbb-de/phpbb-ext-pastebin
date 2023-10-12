<?php
/**
 *
 * Pastebin extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2020 Crizzo <https://www.phpBB.de>
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace phpbbde\pastebin\migrations;

class v205 extends \phpbb\db\migration\migration
{
	public static function depends_on()
	{
		return array(
			'\phpbbde\pastebin\migrations\v204',
		);
	}

	public function update_data()
	{
		$data = array(
			// Update to version 2.0.5
			array('config.update', array('pastebin_version', '2.0.5')),
		);
		return $data;
	}
}
