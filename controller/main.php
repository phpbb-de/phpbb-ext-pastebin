<?php

/**
 *
 * @package phpBB.de - Pastebin
 * @copyright (c) 2026 phpBB.de, gn#36, Crizzo
 * @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 *
 */

namespace phpbbde\pastebin\controller;

use Symfony\Component\HttpFoundation\Response;

class main
{
	const int CONFIRM_PASTEBIN = 5;
	/**
	 * Construct
	 *
	 * @param \phpbb\auth\auth $auth
	 * @param \phpbb\cache\service $cache
	 * @param \phpbb\request\request $request
	 * @param \phpbb\db\driver\driver_interface $db
	 * @param \phpbb\template\template $template
	 * @param \phpbb\user $user
	 * @param \phpbb\language\language	$language
	 * @param \phpbb\files\factory $factory
	 * @param \phpbb\controller\helper $helper
	 * @param \phpbbde\pastebin\functions\pastebin $pastebin
	 * @param string $root_path
	 * @param string $php_ext
	 */
	public function __construct(
		protected \phpbb\auth\auth $auth,
		protected \phpbb\cache\service $cache,
		protected \phpbb\config\config $config,
		protected \phpbb\request\request $request,
		protected \phpbb\db\driver\driver_interface $db,
		protected \phpbb\template\template $template,
		protected \phpbb\user $user,
		protected \phpbb\language\language $language,
		protected \phpbb\files\factory $factory,
		protected \phpbb\controller\helper $helper,
		protected \phpbb\path_helper $path_helper,
		protected \phpbb\captcha\factory $captcha_factory,
		protected \phpbbde\pastebin\functions\pastebin $pastebin,
		protected \phpbbde\pastebin\functions\pb_common $pb_common,
		protected $root_path,
		protected $php_ext,
		protected $highlighter_css,
		protected $pastebin_table,
		protected $pastebin_langs_table,
	)
	{
	}

	public function handle()
	{
		$this->language->add_lang('pastebin', 'phpbbde/pastebin');

		// Adding links to the breadcrumbs
		$this->template->assign_block_vars('navlinks', array(
				'FORUM_NAME'	=> $this->language->lang('PASTEBIN'),
				'U_VIEW_FORUM'	=> $this->helper->route('phpbbde_pastebin_main_controller'),
		));

		if ($response = $this->display_pb())
		{
			return $response;
		}

		return $this->helper->render('@phpbbde_pastebin/pastebin_body.html', $this->language->lang('PASTEBIN'));
	}

	/**
	 * Handle all Pastebin display
	 */
	private function display_pb()
	{
		// Request variables
		$mode				= $this->request->variable('mode', '');
		$snippet_hash		= $this->request->variable('s', '');
		$submit				= $this->request->is_set_post('submit');

		if (in_array($mode, array('view', 'download', 'moderate', 'edit_snippet')))
		{
			// for all of these we have to check if the entry exists
			$sql = $this->db->sql_build_query('SELECT', array(
				'SELECT'	=> 'pb.*, u.user_id, u.username, u.user_colour',
				'FROM'		=> array(
					$this->pastebin_table	=> 'pb',
					USERS_TABLE		=> 'u',
				),
				'WHERE'		=> "pb.snippet_author = u.user_id AND pb.snippet_hash = '" . $this->db->sql_escape($snippet_hash) . "'",
			));
			$result = $this->db->sql_query($sql);
			$data   = $this->db->sql_fetchrow($result);
			$this->db->sql_freeresult($result);

			if (!$data)
			{
				$message = $this->language->lang('PASTEBIN_NO_VALID_SNIPPET');
				$message .= '<br><br>';
				$message .= $this->language->lang('PASTEBIN_RETURN_PASTEBIN', '<a href="' . $this->helper->route('phpbbde_pastebin_main_controller') . '">', '</a>');

				trigger_error($message);
			}

			$this->pastebin->load_from_array($data);
			$snippet = $this->pastebin;

			$this->template->assign_vars([
				'S_AUTH_EDIT'	=> ($this->auth->acl_get('m_pastebin_edit') || ($this->auth->acl_get('u_pastebin_edit') && $snippet['snippet_author'] == $this->user->data['user_id'])) ? true : false,
				'S_AUTH_DELETE'	=> ($this->auth->acl_get('m_pastebin_delete') || ($this->auth->acl_get('u_pastebin_delete') && $snippet['snippet_author'] == $this->user->data['user_id'])) ? true : false,
			]);
		}

		// Some default values
		$error = $s_hidden_fields = [];

		// Latest snippets
		$sql = $this->db->sql_build_query('SELECT', [
				'SELECT'	=> 'pb.snippet_id, pb.snippet_time, pb.snippet_title, pb.snippet_desc, pb.snippet_secret, pb.snippet_hash, u.user_id, u.username, u.user_colour',
				'FROM'		=> array(
						$this->pastebin_table	=> 'pb',
						USERS_TABLE		=> 'u',
				),
				//  Show all snippets to everyone and secret ones just to their author
				'WHERE'		=> 'pb.snippet_author = u.user_id
					AND (pb.snippet_secret = 0
						OR pb.snippet_author = ' . (int) $this->user->data['user_id'] . ')',
				'ORDER_BY'	=> 'pb.snippet_time DESC'
		]);
		$result = $this->db->sql_query_limit($sql, 20);

		while ($row = $this->db->sql_fetchrow($result))
		{
			$this->template->assign_block_vars('latest_snippets', array(
				'URL'			=> $this->helper->route('phpbbde_pastebin_main_controller', array('mode'=>'view', 's' => $row['snippet_hash'])),
				'DESC'			=> $row['snippet_desc'],
				'TITLE'			=> $row['snippet_title'],
				'SNIPPET_TIME'	=> $this->user->format_date($row['snippet_time']),
				'TITLE_SHORT'	=> (utf8_strlen($row['snippet_title']) > 25) ? utf8_substr($row['snippet_title'], 0, 25) . $this->language->lang('PASTEBIN_ELLIPSIS') : $row['snippet_title'],
				'AUTHOR_FULL'	=> get_username_string('full', $row['user_id'], $row['username'], $row['user_colour']),
			));
		}
		$this->db->sql_freeresult($result);

		// Default template variables
		$this->template->assign_vars(array(
			'U_PASTEBIN'	=> $this->helper->route('phpbbde_pastebin_main_controller'),

			'S_MODE'		=> $mode,
			'S_FORM_ACTION'	=> $this->helper->route('phpbbde_pastebin_main_controller'),

			'S_AUTH_VIEW'	=> (bool) $this->auth->acl_get('u_pastebin_view'),
			'S_AUTH_POST'	=> (bool) $this->auth->acl_get('u_pastebin_post'),
		));

		// Now let's decide what to do
		switch ($mode)
		{
			case 'edit_snippet':
				if (!check_form_key('pastebinform'))
				{
					trigger_error('PASTEBIN_FORM_INVALID');
				}
				else
				{
					$data = [
						'snippet_hash'		=> $snippet_hash,
						'snippet_text'		=> $this->request->raw_variable('edit_snippet', ''),
					];

					$snippet->load_from_array($data);
					$snippet->submit();

					$redirect_append = array("mode"=>"view","s"=>$snippet_hash);
					$redirect_url = $this->helper->route('phpbbde_pastebin_main_controller', $redirect_append);

					$message = $this->language->lang('PASTEBIN_SNIPPET_MODERATED');
					$message .= '<br><br>';
					$message .= $this->language->lang('PASTEBIN_RETURN_SNIPPET', '<a href="' . $redirect_url . '">', '</a>');

					meta_refresh(3, $redirect_url);
					trigger_error($message);
				}

		break;

			case 'post':
				// process submitted data from the posting form
				if (!$this->auth->acl_get('u_pastebin_post'))
				{
					trigger_error('PASTEBIN_AUTH_NO_POST');
				}

				if (!$submit)
				{
					break;
				}

				$data = array(
						'snippet_title'		=> str_replace("\n", '', $this->request->variable('snippet_title', '', true)),
						'snippet_desc'		=> str_replace("\n", '', $this->request->variable('snippet_desc', '', true)),
						'snippet_text'		=> $this->request->raw_variable('snippet_text', ''),
						'snippet_prunable'	=> 1,
						'snippet_highlight' => $this->pb_common->validate_language($this->request->variable('snippet_highlight', '')),
						'snippet_secret'	=> $this->request->variable('snippet_secret', 0),
				);
				// Special case for pruning
				$prune_amount = $this->request->variable('pruning_value', 0);
				$prune_unit   = $this->request->variable('pruning_unit', 'months');

				if ($prune_unit === 'infinite' && $this->auth->acl_get('u_pastebin_post_notlim'))
				{
					$data['snippet_prunable'] = 0;
				}

				$snippet_contents = $data['snippet_text'];

				if (empty($data['snippet_title']))
				{
					$error[] = $this->language->lang('PASTEBIN_ERR_NO_TITLE');
				}

				if (!in_array($data['snippet_secret'], [0, 1]))
				{
					$error[] = $this->language->lang('PASTEBIN_ERR_WRONG_SECRET');
				}

				$filedata = $this->request->file('fileupload');

				if (!empty($filedata) && $filedata['name'] != 'none' && trim($filedata['name']))
				{
					$upload = $this->factory->get('files.upload');

					$allowed_extensions = $this->pb_common->get_allowed_extensions();

					$file = $upload
						->set_allowed_extensions($allowed_extensions)
						->handle_upload('files.types.form', 'fileupload');

					$upload->common_checks($file);

					if (!$file->error)
					{
						// Well, ugly solutions work, too
						$snippet_contents = @file_get_contents($file->get('filename'));
						// Check for UTF-8 encoding; because utf8_convert_message() will destroy utf8-characters in UTF-8 documents
						if (mb_detect_encoding($snippet_contents, 'UTF-8', true))
						{
							$snippet_contents = utf8_normalize_nfc($snippet_contents);
						}
						// e.g. ISO 8859-1 encoded files need the utf8_convert_message()
						else
						{
							$snippet_contents = utf8_normalize_nfc(utf8_convert_message($snippet_contents));
						}
					}

					$file->remove();

					$error = array_merge($error, $file->error);
				}
				// Generate the hash for the created snippet
				$snippet_title = $data['snippet_title'];
				$snippet_author = $this->user->data['username_clean'];
				$salt = $this->config['pastebin_salt'];
				$time = time();

				$snippet_hash =	hash('sha256', $snippet_title . $snippet_author . $time . $salt);

				if (empty($snippet_contents))
				{
					$error[] = $this->language->lang('PASTEBIN_ERR_NO_BODY');
				}

				if (!check_form_key('pastebinform'))
				{
					$error[] = $this->language->lang('PASTEBIN_FORM_INVALID');
				}

				// Visual Confirmation handling (borrowed from includes/ucp/ucp_register.php)
				if (!$this->auth->acl_get('u_pastebin_post_novc'))
				{
					$this->language->add_lang('ucp');

					$captcha = $this->captcha_factory->get_instance($this->config['captcha_plugin']);
					$captcha->init($this::CONFIRM_PASTEBIN);

					$vc_response = $captcha->validate($data);
					if ($vc_response !== false)
					{
						$error[] = $vc_response;
					}
				}

				if (!empty($error))
				{
					// Remove duplicate entries of the error array
					$error = array_unique($error);
					// We have errors, we don't insert here, but instead go back to the submit page and tell the user what he did wrong
					$s_error = implode('<br>', $error);
				}
				else
				{
					// Everything went fine :)
					$sql_ary = array(
							'snippet_author'	=> $this->user->data['user_id'],
							'snippet_time'		=> time(),
							'snippet_title'		=> $data['snippet_title'],
							'snippet_desc'		=> $data['snippet_desc'],
							'snippet_secret'	=> (int) $data['snippet_secret'],
							'snippet_text'		=> $snippet_contents,
							'snippet_prunable'	=> (int) $data['snippet_prunable'],
							'snippet_highlight'	=> $data['snippet_highlight'],
							'snippet_prune_on'	=> $data['snippet_prunable'] ? $this->pb_common->calculate_prune_on($prune_amount, $prune_unit) : 0,
							'snippet_hash'		=> $snippet_hash,
					);

					// Okay, captcha, your job is done.
					if (!$this->auth->acl_get('u_pastebin_post_novc') && isset($captcha) && $captcha->is_solved() === true)
					{
						$captcha->reset();
					}

					$sql = 'INSERT INTO ' . $this->pastebin_table . ' ' . $this->db->sql_build_array('INSERT', $sql_ary);
					$this->db->sql_query($sql);

					$redirect_url = $this->helper->route('phpbbde_pastebin_main_controller', array('mode' => "view", 's' => $snippet_hash));

					meta_refresh(3, $redirect_url);
					trigger_error($this->language->lang('PASTEBIN_SNIPPET_SUBMITTED') . '<br><br>' . $this->language->lang('PASTEBIN_RETURN_SNIPPET', '<a href="' . $redirect_url . '">', '</a>'));
				}

				break;

			case 'view':
			case 'download':
			case 'moderate':
				if ($mode == 'view')
				{
					if (!$this->auth->acl_get('u_pastebin_view'))
					{
						trigger_error('PASTEBIN_AUTH_NO_VIEW');
					}

					page_header($this->language->lang('PASTEBIN_VIEW', $data['snippet_title']));

					$snippet_text = $data['snippet_text'];

					$highlight = $this->request->is_set('highlight')
						? $this->pb_common->validate_language($this->request->variable('highlight', ''))
						: ((string) $data['snippet_highlight'] ?: 'text');

					$highlighter = new \Tempest\Highlight\Highlighter();
					$code = $highlighter->parse($snippet_text, $highlight);

					$snippet_text_display = &$code;

					$s_hidden_fields = array_merge($s_hidden_fields, array(
							's'		=> $snippet_hash,
					));

					$snippet_download_url = $this->helper->route('phpbbde_pastebin_main_controller', array("mode" => "download", "s" => $data['snippet_hash']));

					$this->template->assign_vars(array(
						'SNIPPET_TEXT_ORIG'		=> utf8_htmlspecialchars($snippet_text),
						'SNIPPET_TEXT_DISPLAY'	=> $snippet_text_display,

						'SNIPPET_TIME'			=> $this->user->format_date($data['snippet_time']),
						'SNIPPET_PRUNE_ON'		=> $data['snippet_prunable'] ? $this->user->format_date($data['snippet_prune_on']) : $this->language->lang('PASTEBIN_INFINITE'),
						'SNIPPET_DESC_V'		=> $data['snippet_desc'],
						'SNIPPET_TITLE_V'		=> $data['snippet_title'],
						'SNIPPET_AUTHOR'		=> $data['username'],
						'SNIPPET_AUTHOR_ID'		=> $data['user_id'],
						'SNIPPET_AUTHOR_COLOUR'	=> $data['user_colour'],
						'SNIPPET_AUTHOR_FULL'	=> get_username_string('full', $data['user_id'], $data['username'], $data['user_colour']),
						'SNIPPET_DATE'			=> $this->user->format_date($data['snippet_time']),
						'SNIPPET_SECRET'		=> $data['snippet_secret'],

						'HIGHLIGHT_SELECT_MOD' => $this->pb_common->highlight_select((string) $data['snippet_highlight']),
						'DOWNLOAD_SNIPPET_EXPLAIN'	=> $this->language->lang('PASTEBIN_DOWNLOAD_SNIPPET_EXPLAIN', '<a href="' . $snippet_download_url . '">', '</a>'),

						'U_HIGHLIGHT_CSS' => $this->path_helper->update_web_root_path($this->root_path . $this->highlighter_css . $this->config['pastebin_highlight_css']),
						'U_SNIPPET'	=> $this->helper->route('phpbbde_pastebin_main_controller', array("mode" => "view", "s" => $data['snippet_hash'])),
						'U_SNIPPET_DOWNLOAD'	=> $snippet_download_url,

						'S_HIGHLIGHT'			=> $highlight,
						'S_PASTEBIN_VIEW' => true,
						'S_HIDDEN_FIELDS_V'		=> build_hidden_fields($s_hidden_fields),
						'S_FORM_ACTION_MOD'		=> $this->helper->route('phpbbde_pastebin_main_controller', array(), true, $this->user->session_id),
					));

				}
				else if ($mode == 'download')
				{
					if (!$this->auth->acl_get('u_pastebin_view'))
					{
						trigger_error('PASTEBIN_AUTH_NO_VIEW');
					}

					// Thanks download.php
					$snippet_text = $data['snippet_text'];

					$filename = $data['snippet_title'] . '.' . $this->pb_common->get_file_extension($data['snippet_highlight']);

					$user_agent = $this->request->server('HTTP_USER_AGENT', '');
					if (strpos($user_agent, 'MSIE') !== false || strpos($user_agent, 'Safari') !== false || strpos($user_agent, 'Konqueror') !== false)
					{
						$filename = "filename=" . rawurlencode($filename);
					}
					else
					{
						$filename = "filename*=UTF-8''" . rawurlencode($filename);
					}
					// TODO: Internet Explorer stuff can be removed
					// Do not set Content-Disposition to inline please, it is a security measure for users using the Internet Explorer.
					$response = new Response($snippet_text, 200);
					$response->headers->set('Pragma', 'public');
					$response->headers->set('Content-Type', 'text/plain');
					$response->headers->set('Content-Disposition', "attachment; $filename");
					$response->setContent($snippet_text);

					if ($size = @strlen($snippet_text))
					{
						$response->headers->set('Content-Length', $size);
					}

					@set_time_limit(0);

					return $response;
				}
				else if ($mode == 'moderate')
				{
					$delete			= $this->request->is_set_post('delete_snippet');
					$highlight 		= $this->pb_common->validate_language($this->request->variable('snippet_highlight', ''));
					$prune_amount 	= $this->request->variable('pruning_value', 0);
					$prune_unit		= $this->request->variable('pruning_unit', 'months');
					$prunable		= !($prune_unit === 'infinite' && ($this->auth->acl_get('u_pastebin_post_notlim') || !$data['snippet_prunable']));
					$secret			= $this->request->variable('snippet_secret', 0);

					$auth_edit = ($this->auth->acl_get('m_pastebin_edit') || ($this->auth->acl_get('u_pastebin_edit') && $this->user->data['user_id'] == $snippet['snippet_author']));
					$auth_delete =  ($this->auth->acl_get('m_pastebin_delete') || ($this->auth->acl_get('u_pastebin_delete') && $this->user->data['user_id'] == $snippet['snippet_author']));

					// Generic permissions check
					if (!$auth_edit && !$auth_delete)
					{
						trigger_error('PASTEBIN_AUTH_NO_VIEW');
					}

					if ($this->request->is_set_post('cancel'))
					{
						redirect($this->helper->route('phpbbde_pastebin_main_controller', array("mode"=>"view","s"=>$snippet_hash))); // TODO
					}

					if ($delete && $auth_delete)
					{
						// Confirm box
						if (!confirm_box(true))
						{
							$hidden = build_hidden_fields(array('mode' => 'moderate', 's' => $snippet_hash, 'delete_snippet' => 1));
							confirm_box(false, $this->language->lang('PASTEBIN_DELETE_SNIPPET_CONFIRM', $data['snippet_title']), $hidden);
						}
						else
						{
							$snippet->delete();
							$redirect_append = array();
						}
					}
					else if ($auth_edit)
					{
						$snippet->load_from_array(array(
							'snippet_prunable'	=> (int) $prunable,
							'snippet_highlight'	=> $highlight,
							'snippet_prune_on'	=> $prunable ? $this->pb_common->calculate_prune_on($prune_amount, $prune_unit, (int) $data['snippet_time']) : 0,
							'snippet_secret'	=> $secret,
						));
						$snippet->submit();

						$redirect_append = array("mode"=>"view","s"=>$snippet_hash);
					}
					else
					{
						trigger_error('PASTEBIN_NOT_AUTH_EDIT');
					}

					$redirect_url = $this->helper->route('phpbbde_pastebin_main_controller', $redirect_append);

					$message = $this->language->lang('PASTEBIN_SNIPPET_MODERATED');
					$message .= '<br><br>';
					$message .= $this->language->lang('PASTEBIN_RETURN_' . ((!$delete) ? 'SNIPPET' : 'PASTEBIN'), '<a href="' . $redirect_url . '">', '</a>');

					meta_refresh(3, $redirect_url);
					trigger_error($message);
				}

				break;

			default:
				// Nothing here, scroll down ;)

				break;
		}

		$s_hidden_fields['mode'] = 'post';

		// Visual Confirmation - Show images (borrowed from includes/ucp/ucp_register.php)
		if (!$this->auth->acl_get('u_pastebin_post_novc'))
		{
			if (!isset($captcha))
			{
				$captcha = $this->captcha_factory->get_instance($this->config['captcha_plugin']);
				$captcha->init($this::CONFIRM_PASTEBIN);
			}
			$s_hidden_fields = array_merge($s_hidden_fields, $captcha->get_hidden_fields());

			$this->template->assign_var('PASTEBIN_CAPTCHA_TEMPLATE', $captcha->get_template());
		}

		// Define pre-selection
		if (isset($data['snippet_prunable']) && !$data['snippet_prunable'])
		{
			$prune_value	= 1;
			$prune_unit_sel = 'infinite';
		}
		else if (isset($data['snippet_prune_on'], $data['snippet_time']))
		{
			[$prune_value, $prune_unit_sel] = $this->pb_common->split_prune_duration((int) ($data['snippet_prune_on'] - $data['snippet_time']));
		}
		else if ($this->request->is_set_post('pruning_value'))
		{
			$prune_value	= $this->request->variable('pruning_value', 1);
			$prune_unit_sel = $this->request->variable('pruning_unit', 'months');
		}
		else
		{
			// Get default and limit to min/max values
			$default_duration = $this->pb_common->calculate_prune_on((int) $this->config['pastebin_default_prune_months'], 'months', 0);
			[$prune_value, $prune_unit_sel] = $this->pb_common->split_prune_duration($default_duration);
		}

		$pruning_unit_select = '';
		foreach (array_keys($this->pb_common::PRUNE_UNITS) as $unit)
		{
			$pruning_unit_select .= '<option value="' . $unit . '"' . (($unit === $prune_unit_sel) ? ' selected' : '') . '>'
				. $this->language->lang('PASTEBIN_UNIT_' . strtoupper($unit)) . '</option>';
		}

		// Allow infinite only if allowed or stored like this
		if ((isset($data['snippet_prunable']) && !$data['snippet_prunable']) || $this->auth->acl_get('u_pastebin_post_notlim'))
		{
			$infinite_selected = (isset($data['snippet_prunable']) && !$data['snippet_prunable']) || $prune_unit_sel === 'infinite';
			$pruning_unit_select .= '<option value="infinite"' . ($infinite_selected ? ' selected' : '') . '>'
				. $this->language->lang('PASTEBIN_INFINITE') . '</option>';
		}

		[$prune_min, $prune_max] = $this->pb_common->get_prune_limits();

		if (!isset($highlight))
		{
			$highlight = $this->pb_common->validate_language($data['snippet_highlight'] ?? 'php');
		}
		$highlight_select = $this->pb_common->highlight_select($highlight);

		$captcha_in_use = $this->config['captcha_plugin'];
		$is_recaptcha = strpos($captcha_in_use, 'recaptcha');

		add_form_key('pastebinform');

		$this->template->assign_vars(array(
				'SNIPPET_TITLE'		=> $data['snippet_title'] ?? '',
				'SNIPPET_DESC'		=> $data['snippet_desc'] ?? '',
				'AUTHOR_FULL'		=> isset($data['username']) ? get_username_string('full', $data['user_id'], $data['username'], $data['user_colour']) : '',
				'SNIPPET_TEXT'		=> utf8_htmlspecialchars($data['snippet_text'] ?? ''),

				'HIGHLIGHT_SELECT'		=> $highlight_select,
				'PRUNING_VALUE'			=> $prune_value,
				'PRUNING_UNIT_SELECT'	=> $pruning_unit_select,
				'PRUNE_MIN_DAYS'		=> (int) ($prune_min / $this->pb_common::SECONDS_DAY),
				'PRUNE_MAX_DAYS'		=> (int) ($prune_max / $this->pb_common::SECONDS_DAY),
				'PRUNE_LIMITS_TEXT'		=> $this->language->lang('PASTEBIN_PRUNE_LIMITS', (int) ($prune_min / $this->pb_common::SECONDS_DAY), (int) ($prune_max / $this->pb_common::SECONDS_DAY)),

				'SNIPPET_SECRET'		=> $data['snippet_secret'] ?? '',

				'FILESIZE'			=> $this->config['max_filesize'],

				'PASTEBIN_IS_RECAPTCHA'		=> $is_recaptcha,

				'S_FORM_ENCTYPE'	=> ' enctype="multipart/form-data"',
				'S_ERROR'			=> (isset($s_error)) ? $s_error : '',
				'S_HIDDEN_FIELDS'	=> build_hidden_fields($s_hidden_fields),
				'S_CONFIRM_CODE'	=> !$this->auth->acl_get('u_pastebin_post_novc'),
		));
	}
}
