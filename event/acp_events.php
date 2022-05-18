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
class acp_events implements EventSubscriberInterface
{
	public static function getSubscribedEvents()
	{
		return array(
			'core.permissions'			=> 'add_permissions',
		);
	}

	/**
	* Add permissions for setting topic based posts per page settings.
	*
	* @param object $event The event object
	* @return null
	* @access public
	*/
	public function add_permissions($event)
	{

		$event['permissions'] = array_merge($event['permissions'], array(
			// User perms
			'u_pastebin_view'			=> array('lang' => 'ACL_U_PASTEBIN_VIEW', 'cat' => 'pastebin'),
			'u_pastebin_post'			=> array('lang' => 'ACL_U_PASTEBIN_POST', 'cat' => 'pastebin'),
			'u_pastebin_post_novc'		=> array('lang' => 'ACL_U_PASTEBIN_POST_NOVC', 'cat' => 'pastebin'),
			'u_pastebin_post_notlim'	=> array('lang' => 'ACL_U_PASTEBIN_POST_NOTLIM', 'cat' => 'pastebin'),
			'u_pastebin_edit'			=> array('lang' => 'ACL_U_PASTEBIN_EDIT', 'cat' => 'pastebin'),
			'u_pastebin_delete'			=> array('lang' => 'ACL_U_PASTEBIN_DELETE', 'cat' => 'pastebin'),

			// Moderator perms
			'm_pastebin_edit'			=> array('lang' => 'ACL_M_PASTEBIN_EDIT', 'cat' => 'pastebin'),
			'm_pastebin_delete'			=> array('lang' => 'ACL_M_PASTEBIN_DELETE', 'cat' => 'pastebin'),
			'm_pastebin_post_notlim'	=> array('lang' => 'ACL_M_PASTEBIN_POST_NOTLIM', 'cat' => 'pastebin'),
		));

		$cats 					= $event['categories'];
		$cats['pastebin'] 		= 'ACL_CAT_PASTEBIN';
		$event['categories'] 	= $cats;
	}
}
