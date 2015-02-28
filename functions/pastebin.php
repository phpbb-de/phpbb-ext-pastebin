<?php
/**
* @package phpBB3
* @version 0.2.0
* @copyright (c) 2009 3Di (2007 eviL3), 2015 gn#36
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*/

namespace phpbbde\pastebin\functions;

class pastebin
{
	/**
	 * Version of the pastebin mod
	 *
	 * @var int
	 */
	var $version	= '0.2.2';

	/**
	 * Geshi directory
	 *
	 * @var string
	 */
	var $geshi_dir	= '';

	/**
	 * List of geshi installed langs
	 *
	 * @var array
	 */
	var $geshi_list	= array();

	/**
	 * Constructor
	 */
	function __construct($geshi_dir)
	{
		$this->geshi_dir	= $geshi_dir;
		$this->geshi_list	= $this->geshi_list();
	}


	/**
	 * Check if $needle is in one of geshis supported languages
	 */
	function geshi_check($needle)
	{
		return in_array($needle, $this->geshi_list);
	}

	/**
	 * List of all geshi langs
	 */
	function geshi_list()
	{
		global $phpEx;

		$geshi_list = array();

		$d = dir($this->geshi_dir);
		while (false !== ($file = $d->read()))
		{
			if (in_array($file, array('.', '..')))
			{
				continue;
			}

			if (($substr_end = strpos($file, ".$phpEx")) !== false)
			{
				@sort($geshi_list[] = substr($file, 0, $substr_end));
			}
		}
		$d->close();

		return $geshi_list;
	}

	/**
	 * Highlight select box for geshi languages
	 */
	function highlight_select($default = 'text')
	{
		global $user;
		if (!in_array($default, $this->geshi_list))
		{
			$default = 'text';
		}

		$output = '';
		foreach ($user->lang['PASTEBIN_LANGUAGES'] as $code => $name)
		{
			if (in_array($code, $this->geshi_list))
			{
				$output .= '<option' . (($default == $code) ? ' selected="selected"' : '') . ' value="' . htmlentities($code, ENT_QUOTES) . '">' . $name . '</option>';
			}
		}

		return $output;
	}
}
