<?php

/**
 *
 */

namespace phpbbde\pastebin\controller;

class acp_pastebin_languages
{
	public function __construct(
		protected \phpbb\auth\auth $auth,
		protected \phpbb\db\driver\driver_interface $db,
		protected \phpbb\language\language $language,
		protected \phpbb\request\request_interface $request,
		protected \phpbb\template\template $template,
		protected $pastebin_lang_tables,
	)
	{
	}

	public function module_settings(): void
	{
		// Get content of pastebin_langs to display the table
		$sql = 'SELECT *
				FROM ' . $this->pastebin_lang_tables . '
				ORDER BY lang_name_clean ASC';
		$result = $this->db->sql_query($sql);
		$langs_db = $this->db->sql_fetchrowset($result);
		$this->db->sql_freeresult($result);

		$form_name = 'ACP_PASTEBIN_LANG_SETTINGS';

		if ($this->request->is_set_post('submit'))
		{
			if (!check_form_key($form_name))
			{
				trigger_error($this->language->lang('FORM_INVALID') . adm_back_link($this->u_action), E_USER_WARNING);
			}

			$activated_lang_db	= array_combine(array_column($langs_db, 'lang_id'), array_column($langs_db, 'lang_active'));
			$activated_lang_req	= $this->request->variable('lang_active', ['']);
			$activated_lang_all	= array_replace(array_fill(1, count($activated_lang_db), '0'), $activated_lang_req);
			$lang_update_list	= array_diff_assoc($activated_lang_all, $activated_lang_db);

			// Make a loop for write lang_active = 1 for checked and 0 for not checked
			foreach ($lang_update_list as $lang_id => $lang_active)
			{
				$this->change_lang_settings($lang_id, $lang_active);
			}

			trigger_error($this->language->lang('ACP_PASTEBIN_LANGS_UPDATED') . adm_back_link($this->u_action));
		}

		$this->template->assign_vars(array(
			'U_ACTION'	=> $this->u_action,
			'PB_LANGS'	=> $langs_db,
		));

		add_form_key($form_name);
	}

	public function set_page_url(string $u_action): void
	{
		$this->u_action = $u_action;
	}

	private function change_lang_settings($lang_id, $lang_active): void
	{
		$sql = 'UPDATE ' . $this->pastebin_lang_tables . '
				SET lang_active=' . $lang_active . '
				WHERE lang_id = ' . (int) $lang_id;

		$result = $this->db->sql_query($sql);

		$this->db->sql_freeresult($result);
	}
}
