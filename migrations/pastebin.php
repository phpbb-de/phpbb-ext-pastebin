<?php

/**
 *
 * @package phpBB.de pastebin
 * @copyright (c) 2014 phpBB.de, gn#36
 * @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 *
 */

namespace phpbbde\pastebin\migrations;

class pastebin extends \phpbb\db\migration\migration
{
	static public function depends_on()
	{
		return array();
	}

	public function effectively_installed()
	{
		return !empty($this->config['pastebin_version']) && version_compare($this->config['pastebin_version'], '0.2.2', '>=');
	}

	public function update_schema()
	{
		return array(
			'add_tables' => array(
				$this->table_prefix . 'pb' => array(
					'COLUMNS' => array(
						'snippet_id' 		=> array('UINT:8', null, 'auto_increment'),
						'snippet_author' 	=> array('UINT:8', 0),
						'snippet_time' 		=> array('UINT:11', 0),
						'snippet_prune_on'  => array('UINT:11', 0),
						'snippet_title'		=> array('VCHAR:100', ''),
						'snippet_desc'		=> array('VCHAR:100', ''),
						'snippet_text'		=> array('MTEXT_UNI', ''),
						'snippet_prunable'  => array('UINT:1', 0),
						'snippet_highlight'	=> array('VCHAR:30', ''),
					),
					'PRIMARY_KEY' => 'snippet_id',
					'KEYS' => array(
						'snippet_author' => array('INDEX', 'snippet_author'),
					),
				)
			),
		);
	}

	public function revert_schema()
	{
		return array(
			'drop_tables' => array($this->table_prefix . 'pb'),
		);
	}

	public function update_data()
	{
		return array(
				array('permission.add', array('u_pastebin_view')),
				array('permission.add', array('u_pastebin_post')),
				array('permission.add', array('u_pastebin_post_novc')),
				array('permission.add', array('m_pastebin_edit', true, 'm_edit')),
				array('permission.add', array('m_pastebin_delete', true, 'm_delete')),
				array('config.add', array('pastebin_version', '0.2.2')),
		);
	}

	public function revert_data()
	{
		return array(
				array('permission.remove', array('u_pastebin_view')),
				array('permission.remove', array('u_pastebin_post')),
				array('permission.remove', array('u_pastebin_post_novc')),
				array('permission.remove', array('m_pastebin_edit')),
				array('permission.remove', array('m_pastebin_delete')),
				array('config.remove', array('pastebin_version')),
		);
	}
}
