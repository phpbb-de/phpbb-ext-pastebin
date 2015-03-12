<?php
/**
 *
 * @package phpBB.de pastebin
 * @copyright (c) 2015 phpBB.de, gn#36
 * @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 *
 */

namespace phpbbde\pastebin\event;

/**
 * @ignore
 */
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Event listener
 */
class base_events implements EventSubscriberInterface
{
	static public function getSubscribedEvents()
	{
		return array(
			'core.page_header_after'	=> 'page_header_after',
			'core.viewonline_overwrite_location' => 'viewonline_page',
		);
	}

	/**
	 * Constructor
	 *
	 * @param \phpbb\auth\auth			$auth		Auth object
	 * @param \phpbb\template\template	$template	Template object
	 * @param \phpbb\controller\helper	$helper 	Helper
	 * @param string			$phpbb_root_path		phpBB root path (community/)
	 * @param string			$php_ext				php file extension (php)
	 * @param string			$root_path				php file extension (...phpbb.de/)
	 */
	public function __construct(\phpbb\auth\auth $auth, \phpbb\template\template $template, \phpbb\controller\helper $helper, \phpbb\user $user, $phpbb_root_path, $php_ext)
	{
		$this->auth = $auth;
		$this->template = $template;
		$this->phpbb_root_path = $phpbb_root_path;
		$this->helper = $helper;
		$this->php_ext = $php_ext;
		$this->user = $user;
	}

	public function page_header_after($event)
	{
		$this->user->add_lang_ext('phpbbde/pastebin', 'global');

		$this->template->assign_vars(array(
				// Main Menu
				'U_PASTEBIN' => $this->helper->route('phpbbde_pastebin_main_controller'),
		));
	}

	public function viewonline_page($event)
	{
		if ($event['on_page'][1] == 'app')
		{
			if(strrpos($event['row']['session_page'], 'app.' . $this->php_ext . '/pastebin') === 0)
			{
				$event['location'] = $this->user->lang('PASTEBIN_VIEWONLINE');
				$event['location_url'] = $this->helper->route('phpbbde_pastebin_main_controller');
			}
		}
	}
}
