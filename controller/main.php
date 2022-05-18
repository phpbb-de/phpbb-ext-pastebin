<?php

/**
 *
 * @package phpBB.de pastebin
 * @copyright (c) 2015 phpBB.de, gn#36
 * @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 *
 */

namespace phpbbde\pastebin\controller;

use Symfony\Component\HttpFoundation\Response;

class main
{
	const CONFIRM_PASTEBIN = 5;

	const SECONDS_DAY   = 86400;
	const SECONDS_WEEK  = 604800;
	const SECONDS_MONTH = 2592000;
	const SECONDS_YEAR  = 31536000;

	/** @var \phpbb\auth\auth */
	protected $auth;

	/** @var \phpbb\cache\service */
	protected $cache;

	/** @var \phpbb\config\config */
	protected $config;

	/** @var \phpbb\request\request */
	protected $request;

	/** @var \phpbb\db\driver\driver_interface */
	protected $db;

	/** @var \phpbb\template\template */
	protected $template;

	/** @var \phpbb\user */
	protected $user;

	/* @var \phpbb\language\language */
	protected $language;

	/** @var \phpbb\files\factory */
	protected $factory;

	/** @var \phpbb\controller\helper */
	protected $helper;

	/** @var string */
	protected $root_path;

	/** @var string */
	protected $php_ext;

	/** @var \phpbbde\pastebin\functions\pastebin */
	protected $pastebin;

	/** @var \phpbbde\pastebin\functions\utility */
	protected $util;

	/** @var \phpbb\captcha\factory */
	protected $captcha_factory;

	/** @var string */
	protected $geshi_lang;

	/** @var string */
	protected $pastebin_table;

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
	 * @param \phpbbde\pastebin\functions\utility $util
	 * @param string $root_path
	 * @param string $php_ext
	 */
	public function __construct(
		\phpbb\auth\auth $auth,
		\phpbb\cache\service $cache,
		\phpbb\config\config $config,
		\phpbb\request\request $request,
		\phpbb\db\driver\driver_interface $db,
		\phpbb\template\template $template,
		\phpbb\user $user,
		\phpbb\language\language $language,
		\phpbb\files\factory $factory,
		\phpbb\controller\helper $helper,
		\phpbb\captcha\factory $captcha_factory,
		\phpbbde\pastebin\functions\utility $util,
		\phpbbde\pastebin\functions\pastebin $pastebin,
		$root_path,
		$php_ext,
		$geshi_lang,
		$pastebin_table)
	{
		$this->auth = $auth;
		$this->cache = $cache;
		$this->config = $config;
		$this->request = $request;
		$this->db = $db;
		$this->template = $template;
		$this->user = $user;
		$this->language = $language;
		$this->factory = $factory;
		$this->helper = $helper;
		$this->root_path = $root_path;
		$this->php_ext = $php_ext;
		$this->pastebin = $pastebin;
		$this->util = $util;
		$this->captcha_factory = $captcha_factory;

		$this->pastebin_table = $pastebin_table;
		$this->geshi_lang = $geshi_lang;
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
		$mode			= $this->request->variable('mode', '');
		$snippet_id		= $this->request->variable('s', 0);
		$submit			= $this->request->is_set_post('submit');

		if (in_array($mode, array('view', 'download', 'moderate', 'edit_snippet')))
		{
			// for all of these we have to check if the entry exists

			$sql = $this->db->sql_build_query('SELECT', array(
				'SELECT'	=> 'pb.*, u.user_id, u.username, u.user_colour',
				'FROM'		=> array(
					$this->pastebin_table	=> 'pb',
					USERS_TABLE		=> 'u',
				),
				'WHERE'		=> "pb.snippet_author = u.user_id AND pb.snippet_id = $snippet_id",
			));
			$result = $this->db->sql_query($sql);
			$data   = $this->db->sql_fetchrow($result);
			$this->db->sql_freeresult($result);

			if (!$data)
			{
				$message = $this->language->lang('PASTEBIN_NO_VALID_SNIPPET');
				$message .= '<br /><br />';
				$message .= $this->language->lang('PASTEBIN_RETURN_PASTEBIN', '<a href="' . $this->helper->route('phpbbde_pastebin_main_controller') . '">', '</a>');

				trigger_error($message);
			}

			$this->pastebin->load_from_array($data);
			$snippet = $this->pastebin;

			$this->template->assign_vars(array(
				'S_AUTH_EDIT'	=> ($this->auth->acl_get('m_pastebin_edit') || ($this->auth->acl_get('u_pastebin_edit') && $snippet['snippet_author'] == $this->user->data['user_id'])) ? true : false,
				'S_AUTH_DELETE'	=> ($this->auth->acl_get('m_pastebin_delete') || ($this->auth->acl_get('u_pastebin_delete') && $snippet['snippet_author'] == $this->user->data['user_id'])) ? true : false,
			));
		}

		// Some default values
		$error = $s_hidden_fields = array();

		// Latest snippets
		$sql = $this->db->sql_build_query('SELECT', array(
				'SELECT'	=> 'pb.snippet_id, pb.snippet_time, pb.snippet_title, pb.snippet_desc, u.user_id, u.username, u.user_colour',
				'FROM'		=> array(
						$this->pastebin_table	=> 'pb',
						USERS_TABLE		=> 'u',
				),
				'WHERE'		=> 'pb.snippet_author = u.user_id',
				'ORDER_BY'	=> 'pb.snippet_time DESC'
		));
		$result = $this->db->sql_query_limit($sql, 20);

		while ($row = $this->db->sql_fetchrow($result))
		{
			$this->template->assign_block_vars('latest_snippets', array(
				'URL'			=> $this->helper->route('phpbbde_pastebin_main_controller', array('mode'=>'view', 's' => $row['snippet_id'])),
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

			'S_AUTH_VIEW'	=> ($this->auth->acl_get('u_pastebin_view')) ? true : false,
			'S_AUTH_POST'	=> ($this->auth->acl_get('u_pastebin_post')) ? true : false,
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
						'snippet_id'	=> $snippet_id,
						'snippet_text'	=> $this->request->variable('edit_snippet', '', true),
					];

					$snippet->load_from_array($data);
					$snippet->submit();

					$redirect_append = array("mode"=>"view","s"=>$snippet_id);
					$redirect_url = $this->helper->route('phpbbde_pastebin_main_controller', $redirect_append);

					$message = $this->language->lang('PASTEBIN_SNIPPET_MODERATED');
					$message .= '<br /><br />';
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
						'snippet_text'		=> $this->request->variable('snippet_text', '', true),
						'snippet_prunable'	=> 1,
						'snippet_highlight'	=> $this->request->variable('snippet_highlight', ''),
						'snippet_prune_on'	=> max(1, min(6, $this->request->variable('pruning_months', 0))),
				);

				if ($this->auth->acl_get('u_pastebin_post_notlim') && $this->request->variable('pruning_months',0) == -1)
				{
					//Infinite Time...
					$data['snippet_prunable'] = 0;
				}

				$snippet_contents = $data['snippet_text'];

				if (empty($data['snippet_title']))
				{
					$error[] = $this->language->lang('PASTEBIN_ERR_NO_TITLE');
				}

				if (!$this->util->geshi_check($data['snippet_highlight']))
				{
					$data['snippet_highlight'] = 'text';
				}

				$filedata = $this->request->file('fileupload');

				if (!empty($filedata) && $filedata['name'] != 'none' && trim($filedata['name']))
				{
					$upload = $this->factory->get('files.upload');

					$allowed_extensions = array('txt', 'php', 'html', 'xml', 'md', 'json', 'yml', 'js', 'diff', 'sql', 'pl');

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
					$s_error = implode('<br />', $error);
				}
				else
				{
					// Everything went fine :)
					$sql_ary = array(
							'snippet_author'	=> $this->user->data['user_id'],
							'snippet_time'		=> time(),
							'snippet_title'		=> $data['snippet_title'],
							'snippet_desc'		=> $data['snippet_desc'],
							'snippet_text'		=> $snippet_contents,
							'snippet_prunable'	=> (int) $data['snippet_prunable'],
							'snippet_highlight'	=> $data['snippet_highlight'],
							'snippet_prune_on'	=> time() + $this::SECONDS_MONTH * $data['snippet_prune_on'],
					);

					// Okay, captcha, your job is done.
					if (!$this->auth->acl_get('u_pastebin_post_novc') && isset($captcha) && $captcha->is_solved() === true)
					{
						$captcha->reset();
					}

					$sql = 'INSERT INTO ' . $this->pastebin_table . ' ' . $this->db->sql_build_array('INSERT', $sql_ary);
					$this->db->sql_query($sql);

					$snippet_id = $this->db->sql_nextid();

					$redirect_url = $this->helper->route('phpbbde_pastebin_main_controller', array('mode' => "view", 's' => $snippet_id));

					meta_refresh(3, $redirect_url);
					trigger_error($this->language->lang('PASTEBIN_SNIPPET_SUBMITTED') . '<br /><br />' . $this->language->lang('PASTEBIN_RETURN_SNIPPET', '<a href="' . $redirect_url . '">', '</a>'));
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

					$highlight = ($this->request->is_set('highlight')) ? $this->request->variable('highlight', '') : $data['snippet_highlight'];

					if (!$this->util->geshi_check($highlight))
					{
						$highlight = 'php';
					}

					$code = htmlspecialchars_decode($snippet_text);

					$geshi = new \GeSHi($code, $highlight, $this->util->geshi_dir);
					$geshi->set_header_type(GESHI_HEADER_NONE);
					$geshi->enable_line_numbers(GESHI_NORMAL_LINE_NUMBERS, 100);

					$code = $geshi->parse_code();

					$snippet_text_display = &$code;

					$s_hidden_fields = array_merge($s_hidden_fields, array(
							's'		=> $snippet_id,
					));

					$snippet_download_url = $this->helper->route('phpbbde_pastebin_main_controller', array("mode" => "download", "s" => $data['snippet_id']));

					$this->template->assign_vars(array(
						'SNIPPET_TEXT_ORIG'		=> $snippet_text,
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

						'HIGHLIGHT_SELECT_MOD'	=> $this->util->highlight_select($data['snippet_highlight']),
						'DOWNLOAD_SNIPPET_EXPLAIN'	=> $this->language->lang('PASTEBIN_DOWNLOAD_SNIPPET_EXPLAIN', '<a href="' . $snippet_download_url . '">', '</a>'),

						'U_SNIPPET'				=> $this->helper->route('phpbbde_pastebin_main_controller', array("mode" => "view", "s" => $data['snippet_id'])),
						'U_SNIPPET_DOWNLOAD'	=> $snippet_download_url,

						'S_HIGHLIGHT'			=> $highlight,
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
					$snippet_text = htmlspecialchars_decode(utf8_decode($data['snippet_text']));

					$filename = htmlspecialchars_decode($data['snippet_title']) . '.' . $this->pastebin->file_ext();

					$user_agent = $this->request->server('HTTP_USER_AGENT', '');
					if (strpos($user_agent, 'MSIE') !== false || strpos($user_agent, 'Safari') !== false || strpos($user_agent, 'Konqueror') !== false)
					{
						$filename = "filename=" . rawurlencode($filename);
					}
					else
					{
						$filename = "filename*=UTF-8''" . rawurlencode($filename);
					}

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
					$highlight		= $this->request->variable('snippet_highlight', '');
					$pruning_months	= $this->request->variable('pruning_months', 0);
					$prunable		= $pruning_months != -1;

					$auth_edit = ($this->auth->acl_get('m_pastebin_edit') || ($this->auth->acl_get('u_pastebin_edit') && $this->user->data['user_id'] == $snippet['snippet_author']));
					$auth_delete =  ($this->auth->acl_get('m_pastebin_delete') || ($this->auth->acl_get('u_pastebin_delete') && $this->user->data['user_id'] == $snippet['snippet_author']));

					// Generic permissions check
					if (!$auth_edit && !$auth_delete)
					{
						trigger_error('PASTEBIN_AUTH_NO_VIEW');
					}

					if ($this->request->is_set_post('cancel'))
					{
						redirect($this->helper->route('phpbbde_pastebin_main_controller', array("mode"=>"view","s"=>$snippet_id)));
					}

					if ($delete && $auth_delete)
					{
						// Confirm box
						if (!confirm_box(true))
						{
							$hidden = build_hidden_fields(array('mode' => 'moderate', 's' => $snippet_id, 'delete_snippet' => 1));
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
							'snippet_prune_on'	=> $data['snippet_time'] + ($pruning_months * $this::SECONDS_MONTH),
						));
						$snippet->submit();

						$redirect_append = array("mode"=>"view","s"=>$snippet_id);
					}
					else
					{
						trigger_error('PASTEBIN_NOT_AUTH_EDIT');
					}

					$redirect_url = $this->helper->route('phpbbde_pastebin_main_controller', $redirect_append);

					$message = $this->language->lang('PASTEBIN_SNIPPET_MODERATED');
					$message .= '<br /><br />';
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

		$pruning_months_select = '';
		$prune_month = $this->request->variable('pruning_months', 0);
		for ($i = 1; $i < 7; $i++)
		{
			if (isset($data['snippet_prune_on']) && isset($data['snippet_time']))
			{
				$selected = ($data['snippet_prune_on'] - $data['snippet_time'] == $i * $this::SECONDS_MONTH) ? ' selected="selected"' : '';
			}
			else if ($prune_month)
			{
				$selected = ($i == $prune_month) ? ' selected="selected"' : '';
			}
			else
			{
				$selected = ($i == 1) ? ' selected="selected"' : '';
			}
			$pruning_months_select .= '<option' . $selected . ' value="' . $i . '">' . $i . '</option>';
		}

		//Allow infinite storage if it is already set and we are editing, or if the user is allowed to
		if ((isset($data['snippet_prunable']) && !$data['snippet_prunable']) || $this->auth->acl_get('u_pastebin_post_notlim'))
		{
			if (isset($data['snippet_prunable']))
			{
				$selected = ($data['snippet_prunable'] == 0 || $prune_month == -1) ? ' selected="selected"' : '';
			}
			else
			{
				$selected = '';
			}
			$pruning_months_select .= '<option' . $selected . ' value="-1">' . $this->language->lang('PASTEBIN_INFINITE') . '</option>';
		}

		if (!isset($highlight))
		{
			$highlight = isset($data['snippet_highlight']) ? $data['snippet_highlight'] : 'php';
		}
		$highlight_select = $this->util->highlight_select($highlight);

		$captcha_in_use = $this->config['captcha_plugin'];
		$is_recaptcha = strpos($captcha_in_use, 'recaptcha');

		add_form_key('pastebinform');

		$this->template->assign_vars(array(
				'SNIPPET_TITLE'		=> isset($data['snippet_title']) ? $data['snippet_title'] : '',
				'SNIPPET_DESC'		=> isset($data['snippet_desc']) ? $data['snippet_desc'] : '',
				'AUTHOR_FULL'		=> isset($data['username']) ? get_username_string('full', $data['user_id'], $data['username'], $data['user_colour']) : '',
				'SNIPPET_TEXT'		=> isset($data['snippet_text']) ? $data['snippet_text'] : '',

				'HIGHLIGHT_SELECT'	=> $highlight_select,
				'PRUNING_MONTHS_SELECT'	=> $pruning_months_select,

				'FILESIZE'			=> $this->config['max_filesize'],

				'PASTEBIN_IS_RECAPTCHA'		=> $is_recaptcha,

				'S_FORM_ENCTYPE'	=> ' enctype="multipart/form-data"',
				'S_ERROR'			=> (isset($s_error)) ? $s_error : '',
				'S_HIDDEN_FIELDS'	=> build_hidden_fields($s_hidden_fields),
				'S_CONFIRM_CODE'	=> !$this->auth->acl_get('u_pastebin_post_novc'),
		));
	}
}
