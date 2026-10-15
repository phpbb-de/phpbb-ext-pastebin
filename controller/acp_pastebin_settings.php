<?php

/**
 *
 * @package phpBB.de - Pastebin
 * @copyright (c) 2026 phpBB.de, Crizzo
 * @license https://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 *
 */

namespace phpbbde\pastebin\controller;

class acp_pastebin_settings
{
	protected string $u_action;

	public function __construct(
		protected \phpbb\auth\auth $auth,
		protected \phpbb\config\config $config,
		protected \phpbb\db\driver\driver_interface $db,
		protected \phpbb\language\language $language,
		protected \phpbb\request\request_interface $request,
		protected \phpbb\template\template $template,
		protected string $root_path,
		protected $highlighter_css,
	)
	{

	}

	public function module_settings(): void
	{

		$form_name = 'acp_pastebin_settings';
		add_form_key($form_name);
		$error = '';

		// Grab the available CSS files from: 'ext/phpbbde/pastebin/vendor/tempest/highlight/src/Themes/Css/'
		$css_dir = $this->root_path . $this->highlighter_css;
		$css_files = [];
		foreach (glob($css_dir . '/*.css') ?: [] as $file)
		{
			$css_files[] = basename($file);
		}
		sort($css_files, SORT_NATURAL | SORT_FLAG_CASE);

		if ($this->request->is_set_post('submit'))
		{
			$css_file = $this->request->variable('pastebin_highlight_css', '');

			if (!check_form_key($form_name))
			{
				$error = $this->language->lang('FORM_INVALID');
			}
			else if ($css_file !== '' && !in_array($css_file, $css_files, true))
			{
				$error = $this->language->lang('PASTEBIN_HIGHLIGHT_CSS_INVALID');
			}

			$min_days = $this->request->variable('pastebin_prune_min_days', 1);
			$max_days = $this->request->variable('pastebin_prune_max_days', 180);

			if (empty($error) && ($min_days < 1 || $max_days < $min_days))
			{
				$error = $this->language->lang('PASTEBIN_PRUNE_LIMITS_INVALID');
			}

			if (empty($error))
			{
				$this->config->set('pastebin_allow_secret_snippets', $this->request->variable('pastebin_allow_secret_snippets', false));
				$this->config->set('pastebin_default_prune_months', $this->request->variable('pastebin_default_prune_months', 0));
				$this->config->set('pastebin_highlight_css', $css_file);
				$this->config->set('pastebin_prune_min_days', $min_days);
				$this->config->set('pastebin_prune_max_days', $max_days);

				trigger_error($this->language->lang('PASTEBIN_UPDATED') . adm_back_link($this->u_action));
			}
		}

		foreach ($css_files as $name)
		{
			$this->template->assign_block_vars('css_files', [
				'NAME'		=> $name,
				'SELECTED' 	=> ($name === $this->config['pastebin_highlight_css']),
			]);
		}

		$this->template->assign_vars(array(
			'ERRORS' => $error,
			'U_ACTION' => $this->u_action,

			'PASTEBIN_ALLOWED_SECRET' => $this->config['pastebin_allow_secret_snippets'],
			'PASTEBIN_DEFAULT_PRUNE_MONTHS_VALUE' => $this->config['pastebin_default_prune_months'],

			'PASTEBIN_PRUNE_MIN_DAYS' => (int) $this->config['pastebin_prune_min_days'],
			'PASTEBIN_PRUNE_MAX_DAYS' => (int) $this->config['pastebin_prune_max_days'],
		));

	}

	public function set_page_url(string $u_action): void
	{
		$this->u_action = $u_action;
	}
}
