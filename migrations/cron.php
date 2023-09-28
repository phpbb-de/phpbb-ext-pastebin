<?php

/**
 *
 * @package phpBB.de pastebin
 * @copyright (c) 2015 phpBB.de, gn#36
 * @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 *
 */

namespace phpbbde\pastebin\migrations;

class cron extends \phpbb\db\migration\migration
{
	public static function depends_on()
	{
		return array('\phpbbde\pastebin\migrations\pastebin');
	}

	public function update_data()
	{
		return array(
			array('permission.add', array('u_pastebin_post_notlim')),
			array('permission.add', array('m_pastebin_post_notlim', true, 'm_pastebin_delete')),
			array('config.add', array('phpbbde_pastebin_prune_last_run', '0')),
		);
	}

}
