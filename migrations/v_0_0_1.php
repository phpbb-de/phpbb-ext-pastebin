<?php

/**
 *
 * @package phpBB.de pastebin
 * @copyright (c) 2015 phpBB.de, gn#36
 * @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 *
 */

namespace phpbbde\pastebin\migrations;

class v_0_0_1 extends \phpbb\db\migration\migration
{
	public static function depends_on()
	{
		return array(
			'\phpbbde\pastebin\migrations\pastebin',
			'\phpbbde\pastebin\migrations\cron'
		);
	}

	public function update_data()
	{
		return array(
			array('permission.add', array('u_pastebin_edit')),
			array('permission.add', array('u_pastebin_delete')),
		);
	}

}
