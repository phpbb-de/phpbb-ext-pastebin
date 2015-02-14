<?php

/**
 *
 * @package phpBB.de pastebin
 * @copyright (c) 2015 phpBB.de, gn#36
 * @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 *
 */

namespace phpbbde\pastebin\controller;

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

	/** @var \phpbb\controller\helper */
	protected $helper;

	/** @var string */
	protected $root_path;

	/** @var string */
	protected $php_ext;

	/** @var \phpbbde\pastebin\functions\pastebin */
	protected $pastebin;

	/** @var \phpbb\captcha\factory */
	protected $captcha_factory;

	/** @var string */
	protected $ext_path;

	/** @var string */
	protected $geshi_path;

	/** @var string */
	protected $geshi_lang;

	/**
	 * Construct
	 *
	 * @param \phpbb\auth\auth $auth
	 * @param \phpbb\cache\service $cache
	 * @param \phpbb\request\request $request
	 * @param \phpbb\db\driver\driver_interface $db
	 * @param \phpbb\template\template $template
	 * @param \phpbb\user $user
	 * @param \phpbb\controller\helper $helper
	 * @param \phpbbde\pastebin\functions\pastebin $pastebin
	 * @param string $root_path
	 * @param string $php_ext
	 */
	public function __construct(\phpbb\auth\auth $auth, \phpbb\cache\service $cache, \phpbb\config\config $config, \phpbb\request\request $request, \phpbb\db\driver\driver_interface $db, \phpbb\template\template $template, \phpbb\user $user, \phpbb\controller\helper $helper, \phpbb\captcha\factory $captcha_factory, \phpbbde\pastebin\functions\pastebin $pastebin, $root_path, $php_ext)
	{
		$this->auth = $auth;
		$this->cache = $cache;
		$this->config = $config;
		$this->request = $request;
		$this->db = $db;
		$this->template = $template;
		$this->user = $user;
		$this->helper = $helper;
		$this->root_path = $root_path;
		$this->php_ext = $php_ext;
		$this->pastebin = $pastebin;
		$this->captcha_factory = $captcha_factory;

		global $phpbb_container;
		$this->geshi_path = $phpbb_container->getParameter('phpbbde.pastebin.geshi');
		$this->ext_path   = $phpbb_container->getParameter('phpbbde.pastebin.path');
		$this->geshi_lang = $phpbb_container->getParameter('phpbbde.pastebin.geshilangs');
	}

	/**
	 * Handle all calls
	 * @param string $name
	 */
	public function handle($name = '')
	{
		$this->user->add_lang_ext('phpbbde/pastebin', 'pastebin');

		// Adding links to the breadcrumbs
		$this->template->assign_block_vars('navlinks', array(
				'FORUM_NAME'	=> $this->user->lang['PASTEBIN'],
				'U_VIEW_FORUM'	=> $this->helper->route('phpbbde_pastebin_main_controller'),
		));

		$this->display_pb();

		return $this->helper->render('pastebin_body.html', $this->user->lang['PASTEBIN']);
	}

	/**
	 * Adjust table naming correctly
	 * @param string $name
	 * @return string
	 */
	private function table($name)
	{
		global $phpbb_container;
		return $phpbb_container->getParameter('tables.phpbbde.pastebin.' . $name);
	}

	/**
	 * Handle all Pastebin display
	 */
	private function display_pb()
	{
		$pastebin 	= $this->pastebin;
		$template 	= $this->template;
		$db 		= $this->db;
		$auth 		= $this->auth;
		$user 		= $this->user;

		// Request variables
		$mode			= $this->request->variable('mode', '');
		$confirm_id		= $this->request->variable('confirm_id', '');
		$confirm_code	= $this->request->variable('confirm_code', '');
		$submit			= isset($_POST['submit']) ? true : false;

		// Some default values
		$error = $s_hidden_fields = array();

		// Latest snippets
		$sql = $db->sql_build_query('SELECT', array(
				'SELECT'	=> 'pb.snippet_id, pb.snippet_time, pb.snippet_title, pb.snippet_desc, u.user_id, u.username, u.user_colour',
				'FROM'		=> array(
						$this->table('pastebin')	=> 'pb',
						USERS_TABLE		=> 'u',
				),
				'WHERE'		=> 'pb.snippet_author = u.user_id',
				'ORDER_BY'	=> 'pb.snippet_time DESC'
		));
		$result = $db->sql_query_limit($sql, 20);

		while ($row = $db->sql_fetchrow($result))
		{
			$template->assign_block_vars('latest_snippets', array(
				'URL'			=> $this->helper->route('phpbbde_pastebin_main_controller', array('mode'=>'view', 's' => $row['snippet_id'])),
				'DESC'			=> $row['snippet_desc'],
				'TITLE'			=> $row['snippet_title'],
				'TITLE_SHORT'	=> (utf8_strlen($row['snippet_title']) > 12) ? utf8_substr($row['snippet_title'], 0, 12) . '...' : $row['snippet_title'],
				'AUTHOR_FULL'	=> get_username_string('full', $row['user_id'], $row['username'], $row['user_colour']),
			));
		}
		$db->sql_freeresult($result);

		// Default template variables
		$template->assign_vars(array(
			'U_PASTEBIN'	=> $this->helper->route('phpbbde_pastebin_main_controller'),

			'S_MODE'		=> $mode,
			'S_FORM_ACTION'	=> $this->helper->route('phpbbde_pastebin_main_controller'),

			'S_AUTH_VIEW'	=> ($auth->acl_get('u_pastebin_view')) ? true : false,
			'S_AUTH_POST'	=> ($auth->acl_get('u_pastebin_post')) ? true : false,
			'S_AUTH_EDIT'	=> ($auth->acl_get('m_pastebin_edit')) ? true : false,
			'S_AUTH_DELETE'	=> ($auth->acl_get('m_pastebin_delete')) ? true : false,
		));

		// Now let's decide what to do
		switch ($mode)
		{
			case 'post':
				// process submitted data from the posting form
				if (!$auth->acl_get('u_pastebin_post'))
				{
					trigger_error('PASTEBIN_AUTH_NO_POST');
				}

				if (!$submit)
				{
					break;
				}

				$data = array(
						'snippet_title'		=> utf8_normalize_nfc(str_replace("\n", '', $this->request->variable('snippet_title', '', true))),
						'snippet_desc'		=> utf8_normalize_nfc(str_replace("\n", '', $this->request->variable('snippet_desc', '', true))),
						'snippet_text'		=> utf8_normalize_nfc($this->request->variable('snippet_text', '', true)),
						'snippet_prunable'	=> 1,
						'snippet_highlight'	=> $this->request->variable('snippet_highlight', ''),
						'snippet_prune_on'	=> max(1, min(6, $this->request->variable('pruning_months', 0))),
				);

				if($this->auth->acl_get('u_pastebin_post_notlim') && $this->request->variable('pruning_months',0) == -1)
				{
					//Infinite Time...
					$data['snippet_prunable'] = 0;
				}

				$snippet_contents = $data['snippet_text'];

				if (empty($data['snippet_title']))
				{
					$error[] = $user->lang['ERR_NO_TITLE'];
				}

				if (!$pastebin->geshi_check($data['snippet_highlight']))
				{
					$data['snippet_highlight'] = 'text';
				}

				$filedata = $this->request->file('fileupload');
				if (isset($_FILES['fileupload']) && $filedata['name'] != 'none' && trim($filedata['name']))
				{
					include_once($phpbb_root_path . 'includes/functions_upload.' . $phpEx);
					$upload = new fileupload('PASTEBIN_');

					$upload->set_allowed_extensions(array('txt', 'mod', 'php', 'xml', 'html'));

					$file = $upload->form_upload('fileupload');

					if (!sizeof($file->error))
					{
						$snippet_contents = utf8_normalize_nfc(utf8_convert_message(@file_get_contents($file->filename)));
					}

					$file->remove();

					$error = array_merge($error, $file->error);
				}

				if (empty($snippet_contents))
				{
					$error[] = $user->lang['ERR_NO_BODY'];
				}

				if(!check_form_key('pastebinform'))
				{
					$error[] = $user->lang['FORM_INVALID'];
				}

				// Visual Confirmation handling (borrowed from includes/ucp/ucp_register.php)
				if (!$auth->acl_get('u_pastebin_post_novc'))
				{
					$user->add_lang('ucp');

					$captcha = $this->captcha_factory->get_instance($this->config['captcha_plugin']);
					$captcha->init($this::CONFIRM_PASTEBIN);


					if (!$captcha->is_solved())
					{
						$error[] = $user->lang['CONFIRM_CODE_WRONG'];
					}
					else
					{
						$captcha->garbage_collect($this::CONFIRM_PASTEBIN);
					}
				}

				if (!empty($error))
				{
					// We have errors, we don't insert here, but instead go back to the posting page and tell the user what he did wrong
					$s_error = implode('<br />', $error);
				}
				else
				{
					// Everything went fine :)
					$sql_ary = array(
							'snippet_author'	=> $user->data['user_id'],
							'snippet_time'		=> time(),
							'snippet_title'		=> $data['snippet_title'],
							'snippet_desc'		=> $data['snippet_desc'],
							'snippet_text'		=> $snippet_contents,
							'snippet_prunable'	=> (int) $data['snippet_prunable'],
							'snippet_highlight'	=> $data['snippet_highlight'],
							'snippet_prune_on'	=> time() + $this::SECONDS_MONTH * $data['snippet_prune_on'],
					);

					$sql = 'INSERT INTO ' . $this->table('pastebin') . ' ' . $db->sql_build_array('INSERT', $sql_ary);
					$db->sql_query($sql);

					$snippet_id = $db->sql_nextid();

					$redirect_url = $this->helper->route('phpbbde_pastebin_main_controller', array('mode' => "view", 's' => $snippet_id));

					// Uncomment for instant redirect :)
					//redirect($redirect_url);

					meta_refresh(3, $redirect_url);
					trigger_error($user->lang['SNIPPET_SUBMITTED'] . '<br /><br />' . sprintf($user->lang['RETURN_SNIPPET'], '<a href="' . $redirect_url . '">', '</a>'));
				}

				break;

			case 'view':
			case 'download':
			case 'moderate':

				// for all of these we have to check if the entry exists

				$snippet_id	= $this->request->variable('s', 0);

				$sql = $db->sql_build_query('SELECT', array(
						'SELECT'	=> 'pb.*, u.user_id, u.username, u.user_colour',
						'FROM'		=> array(
								$this->table('pastebin')	=> 'pb',
								USERS_TABLE		=> 'u',
						),
						'WHERE'		=> "pb.snippet_author = u.user_id AND pb.snippet_id = $snippet_id",
				));
				$result = $db->sql_query($sql);
				$data   = $db->sql_fetchrow($result);
				$db->sql_freeresult($result);

				if (!$data)
				{
					$message = $user->lang['NO_VALID_SNIPPET'];
					$message .= '<br /><br />';
					$message .= sprintf($user->lang['RETURN_PASTEBIN'], '<a href="' . $this->helper->route('phpbbde_pastebin_main_controller') . '">', '</a>');

					trigger_error($message);
				}

				if ($mode == 'view')
				{
					if (!$auth->acl_get('u_pastebin_view'))
					{
						trigger_error('PASTEBIN_AUTH_NO_VIEW');
					}

					page_header(sprintf($user->lang['PASTEBIN_VIEW'], $data['snippet_title']));

					$snippet_text = $data['snippet_text'];

					$highlight = (isset($_REQUEST['highlight'])) ? $this->request->variable('highlight', '') : $data['snippet_highlight'];

					if (!$pastebin->geshi_check($highlight))
					{
						$highlight = 'php';
					}

					// highlight using geshi (http://qbnz.com/highlighter/)
					//require($this->ext_path . 'vendor/autoload.' . $this->php_ext);
					require($this->geshi_path . 'geshi.' . $this->php_ext);

					$code = htmlspecialchars_decode($snippet_text);

					$geshi = new \GeSHi($code, $highlight, $pastebin->geshi_dir);
					$geshi->set_header_type(GESHI_HEADER_NONE);
					$geshi->enable_line_numbers(GESHI_NORMAL_LINE_NUMBERS, 100);

					$code = $geshi->parse_code();

					$snippet_text_display = &$code;

					$s_hidden_fields = array_merge($s_hidden_fields, array(
							's'		=> $snippet_id,
					));

					$snippet_download_url = $this->helper->route('phpbbde_pastebin_main_controller', array("mode" => "download", "s" => $data['snippet_id']));

					$template->assign_vars(array(
						'SNIPPET_TEXT_ORIG'		=> $snippet_text,
						'SNIPPET_TEXT_DISPLAY'	=> $snippet_text_display,

						'SNIPPET_DESC_V'		=> $data['snippet_desc'],
						'SNIPPET_TITLE_V'		=> $data['snippet_title'],
						'SNIPPET_AUTHOR'		=> $data['username'],
						'SNIPPET_AUTHOR_ID'		=> $data['user_id'],
						'SNIPPET_AUTHOR_COLOUR'	=> $data['user_colour'],
						'SNIPPET_AUTHOR_FULL'	=> get_username_string('full', $data['user_id'], $data['username'], $data['user_colour']),
						'SNIPPET_DATE'			=> $user->format_date($data['snippet_time']),

						'HIGHLIGHT_SELECT_MOD'	=> $pastebin->highlight_select($data['snippet_highlight']),
						'DOWNLOAD_SNIPPET_EXPLAIN'	=> sprintf($user->lang['DOWNLOAD_SNIPPET_EXPLAIN'], '<a href="' . $snippet_download_url . '">', '</a>'),

						'U_SNIPPET'				=> $this->helper->route('phpbbde_pastebin_main_controller', array("mode" => "view", "s" => $data['snippet_id'])),
						'U_SNIPPET_DOWNLOAD'	=> $snippet_download_url,

						'S_HIGHLIGHT'			=> $highlight,
						'S_HIDDEN_FIELDS_V'		=> build_hidden_fields($s_hidden_fields),
						'S_FORM_ACTION_MOD'		=> $this->helper->route('phpbbde_pastebin_main_controller', array(), true, $user->session_id),
					));

				}
				else if ($mode == 'download')
				{
					if (!$auth->acl_get('u_pastebin_view'))
					{
						trigger_error('PASTEBIN_AUTH_NO_VIEW');
					}

					// Thanks download.php
					$snippet_text = htmlspecialchars_decode(utf8_decode($row['snippet_text']));

					$filename = htmlspecialchars_decode($row['snippet_title']) . '.txt';

					$user_agent = $this->request->server('HTTP_USER_AGENT', '');
					if (strpos($user_agent, 'MSIE') !== false || strpos($user_agent, 'Safari') !== false || strpos($user_agent, 'Konqueror') !== false)
					{
						$filename = "filename=" . rawurlencode($filename);
					}
					else
					{
						$filename = "filename*=UTF-8''" . rawurlencode($filename);
					}

					header('Pragma: public');

					// Send out the Headers. Do not set Content-Disposition to inline please, it is a security measure for users using the Internet Explorer.
					header('Content-Type: text/plain');
					header("Content-Disposition: attachment; $filename");

					if ($size = @strlen($snippet_text))
					{
						header("Content-Length: $size");
					}

					@set_time_limit(0);

					echo $snippet_text;

					flush();
					exit;
				}
				else if ($mode == 'moderate')
				{
					$delete			= (isset($_POST['delete_snippet'])) ? true : false;
					$prunable		= (isset($_POST['snippet_prunable'])) ? true : false;
					$highlight		= $this->request->variable('snippet_highlight', '');
					$pruning_months	= max(1, min(6, $this->request->variable('pruning_months', 0)));

					if (!$auth->acl_get('m_pastebin_edit') || ($delete && !$auth->acl_get('m_pastebin_delete')))
					{
						trigger_error('PASTEBIN_AUTH_NO_VIEW');
					}

					if (isset($_POST['cancel']))
					{
						//redirect(append_sid("{$root_path}support/pastebin.$phpEx", "mode=view&amp;s=$snippet_id"));
						redirect($this->helper->route('phpbbde_pastebin_main_controller', "mode=view&amp;s=$snippet_id"));
					}

					if ($delete)
					{
						// Confirm box
						if (!confirm_box(true))
						{
							$hidden = build_hidden_fields(array('mode' => 'moderate', 's' => $snippet_id, 'delete_snippet' => 1));
							confirm_box(false, sprintf($user->lang['DELETE_SNIPPET_CONFIRM'], $row['snippet_title']), $hidden);
						}
						else
						{
							$sql = 'DELETE FROM ' . $this->table('pastebin') . '
								WHERE snippet_id = ' . $snippet_id;
							$redirect_append = '';
						}
					}
					else
					{
						$sql = 'UPDATE ' . $this->table('pastebin') . ' SET ' . $db->sql_build_array('UPDATE', array(
								'snippet_prunable'	=> (int) $prunable,
								'snippet_highlight'	=> $highlight,
								'snippet_prune_on'	=> $row['snippet_time'] + ($pruning_months * $this::SECONDS_MONTH),
						)) . ' WHERE snippet_id = ' . $snippet_id;
						$redirect_append = "mode=view&amp;s=$snippet_id";
					}
					$db->sql_query($sql);

					$redirect_url = $this->helper->route('phpbbde_pastebin_main_controller', $redirect_append);

					// Uncomment for instant redirect :)
					//redirect($redirect_url);

					$message = $user->lang['SNIPPET_MODERATED'];
					$message .= '<br /><br />';
					$message .= sprintf($user->lang['RETURN_' . ((!$delete) ? 'SNIPPET' : 'PASTEBIN')], '<a href="' . $redirect_url . '">', '</a>');

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
		$confirm_image = '';
		if (!$auth->acl_get('u_pastebin_post_novc'))
		{
			if(!isset($captcha))
			{
				$captcha = $this->captcha_factory->get_instance($this->config['captcha_plugin']);
				$captcha->init($this::CONFIRM_PASTEBIN);
			}
			$this->template->assign_var('CAPTCHA_TEMPLATE', $captcha->get_template());
		}

		$pruning_months_select = '';
		$prune_month = $this->request->variable('pruning_months', 0);
		for ($i = 1; $i < 7; $i++)
		{
			if(isset($data['snippet_prune_on']) && isset($data['snippet_time']))
			{
				$selected = ($data['snippet_prune_on'] - $data['snippet_time'] == $i * $this::SECONDS_MONTH) ? ' selected="selected"' : '';
			}
			elseif($prune_month)
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
		if((isset($data['snippet_prunable']) && !$data['snippet_prunable']) || $this->auth->acl_get('u_pastebin_post_notlim'))
		{
			if(isset($data['snippet_prunable']))
			{
				$selected = ($data['snippet_prunable'] == 0 || $prune_month == -1) ? ' selected="selected"' : '';
			}
			else
			{
				$selected = '';
			}
			$pruning_months_select .= '<option' . $selected . ' value="-1">' . $this->user->lang['INFINITE'] . '</option>';
		}

		if(!isset($highlight))
		{
			$highlight = isset($data['snippet_highlight']) ? $data['snippet_highlight'] : 'php';
		}
		$highlight_select = $pastebin->highlight_select($highlight);

		add_form_key('pastebinform');

		$template->assign_vars(array(
				'SNIPPET_TITLE'		=> isset($data['snippet_title']) ? $data['snippet_title'] : '',
				'SNIPPET_DESC'		=> isset($data['snippet_desc']) ? $data['snippet_desc'] : '',
				'AUTHOR_FULL'		=> isset($data['username']) ? get_username_string('full', $data['user_id'], $data['username'], $data['user_colour']) : '',
				'SNIPPET_TEXT'		=> isset($data['snippet_text']) ? $data['snippet_text'] : '',
				//'SNIPPET_PRUNABLE'	=> isset($data['snippet_prunable']) ? $data['snippet_prunable'] : true,

				'HIGHLIGHT_SELECT'	=> $highlight_select,
				'PRUNING_MONTHS_SELECT'	=> $pruning_months_select,

				'FILESIZE'			=> $this->config['max_filesize'],

				'CONFIRM_IMG'		=> $confirm_image,

				'S_FORM_ENCTYPE'	=> ' enctype="multipart/form-data"',
				'S_ERROR'			=> (isset($s_error)) ? $s_error : '',
				'S_HIDDEN_FIELDS'	=> (sizeof($s_hidden_fields)) ? build_hidden_fields($s_hidden_fields) : '',
				'S_CONFIRM_CODE'	=> (!$auth->acl_get('u_pastebin_post_novc')) ? true : false,
		));
	}
}
