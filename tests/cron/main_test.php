<?php

/**
 *
 * @package testing
 * @copyright (c) 2015 phpBB.de, gn#36
 * @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 *
 */

class phpbbde_cron_main_test extends phpbb_database_test_case
{
	static protected function setup_extensions()
	{
		return array('phpbbde/pastebin');
	}

	public function getDataSet()
	{
		return $this->createXMLDataSet(dirname(__FILE__) . '/fixtures/three_pastebin_entries.xml');
	}

	public function setUp()
	{
		parent::setUp();

		$this->cache = $this->getMockBuilder('\phpbb\cache\service')->disableOriginalConstructor()->getMock();
		$this->log = $this->getMockBuilder('\phpbb\log\log')->disableOriginalConstructor()->getMock();
	}

	public function test_construct()
	{
		$task = $this->get_task();
		$this->assertInstanceOf('\phpbb\cron\task\base', $task);
	}

	public function test_is_runnable()
	{
		$task = $this->get_task();
		$this->assertEquals($task->is_runnable(), true);
	}

	public function test_should_run()
	{
		// 1: Has not run ever
		$task = $this->get_task();
		$this->assertEquals($task->should_run(), true);

		// 2: Has just run
		$task = $this->get_task(time() - 1);
		$this->assertEquals($task->should_run(), false);
	}

	public function test_run()
	{
		$task = $this->get_task();
		$task->run();
		$sql = 'SELECT count(*) as cnt FROM phpbb_pastebin';

		$result = $this->db->sql_query($sql);
		$row = $this->db->sql_fetchrow($result);
		$this->assertEquals($row['cnt'], 2);

		$sql = 'SELECT snippet_id FROM phpbb_pastebin';
		$result = $this->db->sql_query($sql);
		$rows = $this->db->sql_fetchrowset($result);
		$this->assertEquals($rows, array(array('snippet_id' => 1), array('snippet_id' => 3)));
	}

	private function get_task($last_run = 0)
	{
		global $phpbb_root_path, $phpEx;
		$pastebin_path = dirname(__FILE__) . '/../../';
		$db = $this->new_dbal();
		$this->db = $db;

		$this->config = new \phpbb\config\config(array(
			'phpbbde_pastebin_prune_last_run' => $last_run,
			'phpbbde_pastebin_version' => '0.2.2',
		));

		return new \phpbbde\pastebin\cron\main($this->cache, $this->config, $db, $this->log, $pastebin_path, $phpbb_root_path, $phpEx, 86400, 'phpbb_pastebin');
	}
}