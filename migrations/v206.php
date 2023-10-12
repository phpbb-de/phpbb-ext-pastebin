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

class v206 extends \phpbb\db\migration\migration
{
	public static function depends_on()
	{
		return array(
			'\phpbbde\pastebin\migrations\v205',
		);
	}

	public function update_data()
	{
		$data = array(
			// Update version
			array('config.update', array('pastebin_version', '2.0.6')),
		);
		return $data;
	}
}
