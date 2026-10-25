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
		protected \phpbb\log\log $log,
		protected \phpbb\request\request_interface $request,
		protected \phpbb\template\template $template,
		protected \phpbb\user $user,
		protected $pastebin_lang_tables,
	)
	{
	}

	public function module_settings(): void
	{
		/* Get content of pastebin_langs to display the table */
		$sql = 'SELECT lang_id, lang_name, lang_name_clean, lang_active, lang_file_extension
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

			/* Creates an array (extract) from the language table, using `lang_id` as the key and `lang_active` as the value */
			$activated_lang_db		= array_combine(array_column($langs_db, 'lang_id'), array_column($langs_db, 'lang_active'));
			/* Retrieves all checked (active) checkboxes from the form */
			$activated_lang_req		= $this->request->variable('lang_active', ['']);
			/* Creates a placeholder array using the IDs from the table and sets all values ​​to `0` */
			$activated_lang_filled	= array_combine(array_keys($activated_lang_db), array_fill(1, count($activated_lang_db), '0'));
			/* Combines the placeholder array with the active checkboxes from the form to enable the creation of a diff array */
			$activated_lang_req		= array_replace($activated_lang_filled, $activated_lang_req);
			/* Generates a diff array so that only the necessary records are modified */
			$lang_update_list		= array_diff_assoc($activated_lang_req, $activated_lang_db);

			/* Make a loop for write lang_active = 1 for checked and 0 for not checked */
			foreach ($lang_update_list as $lang_id => $lang_active)
			{
				$this->change_lang_settings($lang_id, $lang_active);
			}

			// Log the change to admin log
			$this->log->add(
				'admin',
				$this->user->data['user_id'],
				$this->user->ip,
				'ACP_PASTEBIN_LANGS_UPDATED',
				time()
			);

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
