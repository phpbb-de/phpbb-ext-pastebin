<?php

/**
 *
 */

namespace phpbbde\pastebin\acp;

class acp_pastebin_module
{
	public $page_title;
	public $tpl_name;
	public $u_action;

	public function main($id, $mode)
	{
		global $phpbb_container;

		$language = $phpbb_container->get('language');

		switch ($mode)
		{
			// Settings
			case 'settings':
				$this->tpl_name = 'acp_pastebin_settings';
				$this->page_title = $language->lang('PASTEBIN_NAV_TITLE') . ' - ' . $language->lang('PASTEBIN_NAV_CONFIG');

				$acp_controller = $phpbb_container->get('phpbbde.pastebin.settings.acp');

				break;
			// Langs overview
			case 'languages':
				$this->tpl_name = 'acp_pastebin_langs';
				$this->page_title = $language->lang('PASTEBIN_NAV_TITLE') . ' - ' . $language->lang('PASTEBIN_NAV_LANGS');

				$acp_controller = $phpbb_container->get('phpbbde.pastebin.languages.acp');

				break;
		}

		$acp_controller->set_page_url($this->u_action);
		$acp_controller->module_settings();
	}
}
