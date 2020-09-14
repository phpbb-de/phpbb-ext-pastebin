<?php

/**
 *
 * @package phpBB.de pastebin
 * @copyright (c) 2015 phpBB.de, gn#36
 * @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 *
 */

namespace phpbbde\pastebin\cron;

class main extends \phpbb\cron\task\base
{
	/** @var \phpbb\db\driver\driver_interface */
	protected $db;

	/** @var \phpbb\config\config */
	protected $config;

	protected $prune_interval;
	protected $pastebin_table;

	public function __construct(\phpbb\config\config $config, \phpbb\db\driver\driver_interface $db, $prune_interval, $pastebin_table)
	{
		$this->config = $config;
		$this->db = $db;
		$this->prune_interval = $prune_interval;
		$this->pastebin_table = $pastebin_table;
	}

	/**
	 * Run this cronjob (and delete prunable tasks)
	 * @see \phpbb\cron\task\task::run()
	 */
	public function run()
	{
		$now = time();
		$sql = 'DELETE FROM ' . $this->pastebin_table . '
			WHERE snippet_prunable = 1 and snippet_prune_on < ' . $now;
		$this->db->sql_query($sql);
		$this->config->set('phpbbde_pastebin_prune_last_run', $now, true);
	}

	/**
	 * Returns whether this cron job can run
	 * @see \phpbb\cron\task\base::is_runnable()
	 * @return bool
	 */
	public function is_runnable()
	{
		return isset($this->config['phpbbde_pastebin_prune_last_run']);
	}

	/**
	 * Should this cron job run now because enough time has passed since last run?
	 * @see \phpbb\cron\task\base::should_run()
	 * @return bool
	 */
	public function should_run()
	{
		$now = time();

		return $now > $this->config['phpbbde_pastebin_prune_last_run'] + $this->prune_interval;
	}
}
