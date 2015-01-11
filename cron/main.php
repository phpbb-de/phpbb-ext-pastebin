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
	/** @var string phpBB root path */
	protected $root_path;

	/** @var string phpEx */
	protected $php_ext;

	/** @var \phpbb\db\driver\driver_interface */
	protected $db;

	/** @var \phpbb\cache\service */
	protected $cache;

	/** @var \phpbb\config\config */
	protected $config;

	/** @var \phpbb\log\log_interface */
	protected $log;

	public function __construct(\phpbb\cache\service $cache, \phpbb\config\config $config, \phpbb\db\driver\driver_interface $db, \phpbb\log\log_interface $log, $pastebin_path, $root_path, $php_ext)
	{
		$this->cache = $cache;
		$this->config = $config;
		$this->db = $db;
		$this->log = $log;
		$this->pastebin_path = $pastebin_path;
		$this->root_path = $root_path;
		$this->php_ext = $php_ext;
	}

	/**
	 * Run this cronjob (and delete prunable tasks)
	 * @see \phpbb\cron\task\task::run()
	 */
	public function run()
	{
		$now = time();
		$sql = 'DELETE FROM ' . $this->table('pastebin') . '
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
		global $phpbb_container;

		$now = time();

		return $now > $this->config['phpbbde_pastebin_prune_last_run'] + $phpbb_container->getParameter('phpbbde.pastebin.cron.prune_interval');
	}

	/**
	 * Adjust table naming correctly
	 * @param string $name
	 * @return string
	 */
	private function table($name)
	{
		global $phpbb_container;
		return $phpbb_container->getParameter('tables.phpbbde.pastebin.' . $name);
	}
}
