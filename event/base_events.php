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
	public static function getSubscribedEvents()
	{
		return array(
			'core.page_header_after'	=> 'page_header_after',
			'core.viewonline_overwrite_location' => 'viewonline_page',
		);
	}

	/** @var \phpbb\template\template */
	protected $template;

	/** @var \phpbb\user */
	protected $user;

	/* @var \phpbb\language\language */
	protected $language;

	/** @var \phpbb\controller\helper */
	protected $helper;

	/** @var string */
	protected $root_path;

	/** @var string */
	protected $php_ext;

	/**
	 * Constructor
	 *
	 * @param \phpbb\template\template	$template	Template object
	 * @param \phpbb\controller\helper	$helper 	Helper
	 * @param \phpbb\user $user
	 * @param \phpbb\language\language	$language
	 * @param string			$phpbb_root_path		phpBB root path (community/)
	 * @param string			$php_ext				php file extension (php)
	 * @param string			$root_path				php file extension (...phpbb.de/)
	 */
	public function __construct(
		\phpbb\template\template $template,
		\phpbb\controller\helper $helper,
		\phpbb\user $user,
		\phpbb\language\language $language,
		$root_path,
		$php_ext
	)
	{
		$this->template = $template;
		$this->root_path = $root_path;
		$this->helper = $helper;
		$this->php_ext = $php_ext;
		$this->user = $user;
		$this->language = $language;
	}

	public function page_header_after($event)
	{
		$this->language->add_lang('global', 'phpbbde/pastebin');

		$this->template->assign_vars(array(
				// Main Menu
				'U_PASTEBIN' => $this->helper->route('phpbbde_pastebin_main_controller'),
		));
	}

	public function viewonline_page($event)
	{
		if ($event['on_page'][1] == 'app')
		{
			if (strrpos($event['row']['session_page'], 'app.' . $this->php_ext . '/pastebin') === 0)
			{
				$event['location'] = $this->language->lang('PASTEBIN_VIEWONLINE');
				$event['location_url'] = $this->helper->route('phpbbde_pastebin_main_controller');
			}
		}
	}
}
