<?php
/**
 *
 * Pastebin extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2026 Crizzo <https://www.phpBB.de>
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace phpbbde\pastebin\migrations;

class v300 extends \phpbb\db\migration\migration
{
	public static function depends_on()
	{
		return array(
			'\phpbbde\pastebin\migrations\pastebin',
			'\phpbbde\pastebin\migrations\v206',
		);
	}

	public function update_schema()
	{
		return [
			'add_columns' => [
				$this->table_prefix . 'pastebin' => [
					'snippet_secret' => ['BOOL', 0],
					'snippet_hash' => ['VCHAR:64', ''],
				]
			],
			'add_tables' => [
				$this->table_prefix . 'pastebin_langs' => [
					'COLUMNS' => [
						'lang_id'				=> ['UINT', null, 'auto_increment'],
						'lang_name'				=> ['VCHAR:100', ''],
						'lang_name_clean'		=> ['VCHAR:100', ''],
						'lang_active'			=> ['BOOL', 0],
						'lang_file_extension'	=> ['VCHAR:255', ''],
						'lang_file_autosyntax'	=> ['VCHAR:255', ''],
					],
					'PRIMARY_KEY' => 'lang_id',
				],
			],
		];
	}

	public function update_data()
	{
		return [
			['custom', [[$this, 'add_langs_for_highligher']]],
			// Add new module to ACP
			['module.add', [
				'acp',
				'ACP_CAT_DOT_MODS',
				'ACP_PASTEBIN_TITLE'
			]],
			['module.add', [
				'acp',
				'ACP_PASTEBIN_TITLE',
				[
					'module_basename' => '\phpbbde\pastebin\acp\acp_pastebin_module',
					'modes' => ['settings', 'languages'],
				],
			]],
			['config.add', ['pastebin_allow_secret_snippets', 1]],
			['config.add', ['pastebin_default_prune_months', 1]],
			['config.add', ['pastebin_highlight_css', 'highlight-tempest.css']],
			['config.add', ['pastebin_prune_min_days', 1]],
			['config.add', ['pastebin_prune_max_days', 180]],
			// Remove version entry in database
			['config.remove', ['pastebin_version']],
		];
	}

	public function add_langs_for_highligher()
	{
		$data = [
			['Apache'		, 'apache'		, '0'	, ''			, ''			],
			['BBCode'		, 'bbcode'		, '1'	, ''			, ''			],
			['Base'			, 'base'		, '0'	, ''			, ''			],
			['Bash'			, 'bash'		, '1'	, 'sh'			, 'sh'			],
			['Blade'		, 'blade'		, '0'	, ''			, ''			],
			['CSS'			, 'css'			, '1'	, 'css'			, 'css'			],
			['Diff'			, 'diff'		, '1'	, 'diff'		, 'diff'		],
			['DocComment'	, 'doc'			, '0'	, 'doc'			, 'doc'			],
			['DockerFile'	, 'dockerfile'	, '0'	, ''			, ''			],
			['DotEnv'		, 'dotenv'		, '0'	, ''			, ''			],
			['Ellison'		, 'ellison'		, '0'	, ''			, ''			],
			['GDscript'		, 'gdscript'	, '0'	, 'gd'			, 'gd'			],
			['Graphql'		, 'graphql'		, '0'	, 'ts'			, 'ts'			],
			['HTML'			, 'html'		, '1'	, 'html, htm'	, 'htm'			],
			['Ini'			, 'ini'			, '1'	, 'ini'			, 'ini'			],
			['JavaScript'	, 'js'			, '1'	, 'js'			, 'js'			],
			['JSON'			, 'json'		, '1'	, 'json'		, 'json'		],
			['Markdown'		, 'markdown'	, '1'	, 'md'			, 'md'			],
			['Nginx'		, 'nginx'		, '1'	, 'conf'		, 'conf'		],
			['PHP'			, 'php'			, '1'	, 'php'			, 'php'			],
			['Python'		, 'python'		, '1'	, 'py'			, 'py'			],
			['SCSS'			, 'scss'		, '1'	, 'scss'		, 'scss'		],
			['SQL'			, 'sql'			, '1'	, 'sql'			, 'sql'			],
			['Svelte'		, 'svelte'		, '1'	, 'js'			, ''			],
			['Terminal'		, 'terminal'	, '1'	, ''			, ''			],
			['Terraform'	, 'terraform'	, '0'	, 'go'			, 'go'			],
			['Text'			, 'text'		, '1'	, 'txt'			, 'txt'			],
			['Twig'			, 'twig'		, '1'	, 'twig, html'	, 'twig, html'	],
			['TypeScript'	, 'ts'			, '0'	, 'ts'			, 'ts'			],
			['XML'			, 'xml'			, '1'	, 'xml'			, 'xml'			],
			['YAML'			, 'yaml'		, '1'	, 'yml, yaml'	, 'yml, yaml'	],
		];

		foreach ($data as $langs)
		{
			$sql_ary[] = [
				'lang_name'				=> $langs[0],
				'lang_name_clean'		=> $langs[1],
				'lang_active'			=> $langs[2],
				'lang_file_extension'	=> $langs[3],
				'lang_file_autosyntax'	=> $langs[4],
			];
		}

		$this->db->sql_multi_insert($this->table_prefix . 'pastebin_langs', $sql_ary);
	}

	public function revert_schema()
	{
		return [
			'drop_tables' => [
				$this->table_prefix . 'pastebin_langs',
			],
		];
	}
}
