<?php
/**
* @package pastebin
* @version 0.2.0
* @copyright (c) 2009 3Di (2007 eviL3), 2015 gn#36
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*/

namespace phpbbde\pastebin\functions;

class utility
{
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

	/** @var string */
	protected $php_ext;

	/* @var \phpbb\language\language */
	protected $language;

	/**
	 * Constructor
	 * @param string $php_ext
	 * @param \phpbb\language\language	$language
	 */
	function __construct(
		$geshi_dir,
		$php_ext,
		\phpbb\language\language $language)
	{
		$this->geshi_dir	= $geshi_dir;
		$this->geshi_list	= $this->geshi_list();
		$this->php_ext 		= $php_ext;
		$this->language		= $language;
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
		$geshi_list = array();

		$d = dir($this->geshi_dir);
		while (false !== ($file = $d->read()))
		{
			if (in_array($file, array('.', '..')))
			{
				continue;
			}

			if (($substr_end = strpos($file, ".$this->php_ext")) !== false)
			{
				$geshi_list[] = substr($file, 0, $substr_end);
			}
		}
		$d->close();

		sort($geshi_list);

		return $geshi_list;
	}

	/**
	 * Highlight select box for geshi languages
	 */
	function highlight_select($default = 'text')
	{
		// Programming languages
		// these are used by geshi
		$programming_langs = array(
			'text',
			'php',
			'sql',
			'html5',
			'css',
			'javascript',
			'xml',
			'diff',
			'robots',
		);

		if (!in_array($default, $this->geshi_list))
		{
			$default = 'text';
		}

		$output = '';
		$lang_prefix = 'PASTEBIN_LANGS_';
		foreach ($programming_langs as $code)
		{
			if (in_array($code, $this->geshi_list))
			{
				$output .= '<option' . (($default == $code) ? ' selected="selected"' : '') . ' value="' . htmlentities($code, ENT_QUOTES) . '">' . $this->language->lang($lang_prefix . strtoupper($code)) . '</option>';
			}
		}

		return $output;
	}
}
