<?php
/**
 * @package pastebin
 * @copyright (c) 2026 Crizzo
 * @license https://opensource.org/license/GPL-2.0 GNU Public License
 */

namespace phpbbde\pastebin\functions;

/**
 * Class for commonly used functions
 *
 * @author Crizzo
 *
 */
class pb_common
{
	/** @var array */
	protected array $data;

	/** @var array|null Cache für aktive Sprachen [lang_name_clean => lang_name] */
	private ?array $active_langs = null;

	// Constants for time calculations like prunning
	const int SECONDS_DAY   = 86400;
	const int SECONDS_WEEK  = 604800;
	const int SECONDS_MONTH = 2592000;

	const PRUNE_UNITS = [
		'days'   => self::SECONDS_DAY,
		'weeks'  => self::SECONDS_WEEK,
		'months' => self::SECONDS_MONTH,
	];

	/**
	 * Constructor
	 * @param \phpbb\config\config $config
	 * @param \phpbb\db\driver\driver_interface $db
	 * @param $pastebin_langs_table
	 */
	function __construct(
		protected \phpbb\config\config $config,
		protected \phpbb\db\driver\driver_interface $db,
		protected $pastebin_langs_table)
	{
	}
	/**
	 * Returns all file extensions from active languages (for uploading)
	 */
	public function get_allowed_extensions(): array
	{
		$exts = [];

		$sql = 'SELECT lang_file_extension
		FROM ' . $this->pastebin_langs_table . '
		WHERE lang_active = 1';
		$result = $this->db->sql_query($sql);
		$exts_db = $this->db->sql_fetchrowset($result);
		$this->db->sql_freeresult($result);

		foreach ($exts_db as $row)
		{
			$exts_db_entry	= preg_replace('/[^a-z,]/', '', strtolower($row['lang_file_extension']));
			$exts			= array_merge($exts, preg_split('/,/', $exts_db_entry, -1, PREG_SPLIT_NO_EMPTY));
		}

		return array_values(array_unique($exts)) ?: ['txt'];
	}

	/**
	 * Returns all active languages as [lang_name_clean => lang_name]
	 */
	public function get_active_languages(): array
	{
		if ($this->active_langs === null)
		{
			$this->active_langs = [];

			$sql = 'SELECT lang_name, lang_name_clean
                FROM ' . $this->pastebin_langs_table . '
                WHERE lang_active = 1
                ORDER BY lang_name ASC';
			$result		= $this->db->sql_query($sql);
			$langs_db	= $this->db->sql_fetchrowset($result);
			$this->db->sql_freeresult($result);

			foreach ($langs_db as $row)
			{
				$this->active_langs[$row['lang_name_clean']] = $row['lang_name'];
			}
		}

		return $this->active_langs;
	}

	/**
	 * Building the option html elements
	 */
	public function highlight_select(string $selected): string
	{
		$html = '';
		foreach ($this->get_active_languages() as $clean => $name)
		{
			$sel = ($clean === $selected) ? ' selected' : '';
			$html .= '<option value="' . utf8_htmlspecialchars($clean) . '"' . $sel . '>' . utf8_htmlspecialchars($name) . "</option>\n";
		}

		return $html;
	}

	/**
	 * Returns only active languages, if not gives a fallback
	 */
	public function validate_language(string $lang): string
	{
		$langs = $this->get_active_languages();

		if (isset($langs[$lang]))
		{
			return $lang;
		}

		return isset($langs['php']) ? 'php' : (array_key_first($langs) ?? 'text');
	}

	/**
	 * Gets the file extensions from phpbb_pastebin_langs
	 */
	public function get_file_extension(string $lang_clean): string
	{
		$sql = 'SELECT lang_file_extension
			FROM ' . $this->pastebin_langs_table . "
			WHERE lang_name_clean = '" . $this->db->sql_escape($lang_clean) . "'";
		$result = $this->db->sql_query_limit($sql, 1);
		$ext = $this->db->sql_fetchfield('lang_file_extension');
		$this->db->sql_freeresult($result);

		return $ext ? ltrim($ext, '.') : 'txt';
	}

	/**
	 * Uses min/max value from ACP, returns in seconds
	 *
	 * @return int[] [min, max]
	 */
	public function get_prune_limits(): array
	{
		$min_days = max(1, (int) $this->config['pastebin_prune_min_days']);
		$max_days = max($min_days, (int) $this->config['pastebin_prune_max_days']);

		return [$min_days * self::SECONDS_DAY, $max_days * self::SECONDS_DAY];
	}

	/**
	 * Calculates the prune date with amount and unit, limits it to min/max from ACP setting
	 */
	public function calculate_prune_on(int $amount, string $unit, ?int $base_time = null): int
	{
		if (!isset(self::PRUNE_UNITS[$unit]))
		{
			$unit = 'months';
		}

		[$min, $max] = $this->get_prune_limits();

		$duration = max(1, $amount) * self::PRUNE_UNITS[$unit];
		$duration = max($min, min($max, $duration));

		return ($base_time ?? time()) + $duration;
	}

	/**
	 * Calculates the seconds back to fitting days, weeks and months for the template output
	 *
	 * @return array [amount, unit]
	 */
	public function split_prune_duration(int $seconds): array
	{
		foreach (['months', 'weeks', 'days'] as $unit)
		{
			if ($seconds >= self::PRUNE_UNITS[$unit] && $seconds % self::PRUNE_UNITS[$unit] === 0)
			{
				return [intdiv($seconds, self::PRUNE_UNITS[$unit]), $unit];
			}
		}

		return [max(1, (int) round($seconds / self::SECONDS_DAY)), 'days'];
	}
}
