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
		$sql = 'SELECT lang_id, lang_name, lang_name_clean, lang_file_extension, lang_file_autosyntax, lang_active
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

			$column_autosyntax_update_list	= $this->column_request_and_diff($langs_db, 'lang_file_autosyntax', 'lang_autosyntax', '');
			$column_active_update_list		= $this->column_request_and_diff($langs_db, 'lang_active', 'lang_active', '0');

			$this->update_lang_settings($column_autosyntax_update_list, 'lang_file_autosyntax');
			$this->update_lang_settings($column_active_update_list, 'lang_active');


			/* Log the change to admin log */
			$this->log->add(
				'admin',
				$this->user->data['user_id'],
				$this->user->ip,
				'LOG_ACP_PASTEBIN_LANGS_UPDATED',
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

	private function column_request_and_diff(array $table_db, string $column_name_db, string $column_name_req, string $array_filler): array
	{
		/* Creates an array (extract) from the language table, using `lang_id` as the key */
		$column_db		= array_combine(array_column($table_db, 'lang_id'), array_column($table_db, $column_name_db));
		/* Retrieves column from the form */
		$column_req		= $this->request->variable($column_name_req, ['']);
		/* Creates a placeholder array using the IDs from the table and sets all values ​​to the array filler */
		$column_filled	= array_combine(array_keys($column_db), array_fill(1, count($column_db), $array_filler));
		/* Combines the placeholder array with the column from the form to enable the creation of a diff array */
		$column_req		= array_replace($column_filled, $column_req);
		/* Generates a diff array so that only the necessary records are modified */
		$column_diff	= array_diff_assoc($column_req, $column_db);

		return $column_diff;
	}

	private function update_lang_settings(array $update_list, string $column_name): void
	{
		foreach ($update_list as $lang_id => $column_value)
		{
			$sql = 'UPDATE ' . $this->pastebin_lang_tables . '
					SET ' . $column_name . ' = "' . $this->db->sql_escape($column_value) . '"
					WHERE lang_id = ' . (int) $lang_id;
			$result = $this->db->sql_query($sql);
			$this->db->sql_freeresult($result);
		}
	}
}
