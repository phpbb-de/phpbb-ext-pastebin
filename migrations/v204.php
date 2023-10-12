<?php
	/**
	 *
	 * Pastebin extension for the phpBB Forum Software package.
	 *
	 * @copyright (c) 2020 Crizzo <https://www.phpBB.de>
	 * @license GNU General Public License, version 2 (GPL-2.0)
	 *
	 */

	namespace phpbbde\pastebin\migrations;

	class v204 extends \phpbb\db\migration\migration
	{
		public static function depends_on()
		{
			return array(
				'\phpbbde\pastebin\migrations\v_0_0_1',
			);
		}

		public function update_data()
		{
			$data = array(
				array('config.update', array('pastebin_version', '2.0.4')),
			);

			// Check if user role exists and assign permission to user standard role
			if ($this->role_exists('ROLE_USER_STANDARD'))
			{
				$data[] = array('permission.permission_set', array('ROLE_USER_STANDARD', 'u_pastebin_post', 'role', true));
				$data[] = array('permission.permission_set', array('ROLE_USER_STANDARD', 'u_pastebin_edit', 'role', true));
				$data[] = array('permission.permission_set', array('ROLE_USER_STANDARD', 'u_pastebin_delete', 'role', true));
				$data[] = array('permission.permission_set', array('ROLE_USER_STANDARD', 'u_pastebin_post_novc', 'role', true));
				$data[] = array('permission.permission_set', array('ROLE_USER_STANDARD', 'u_pastebin_view', 'role', true));
			}
			// Check if moderator role exists and assign permission to moderator standard role
			if ($this->role_exists('ROLE_MOD_STANDARD'))
			{
				$data[] = array('permission.permission_set', array('ROLE_MOD_STANDARD', 'm_pastebin_delete', 'role', true));
				$data[] = array('permission.permission_set', array('ROLE_MOD_STANDARD', 'm_pastebin_edit', 'role', true));
				$data[] = array('permission.permission_set', array('ROLE_MOD_STANDARD', 'm_pastebin_post_notlim', 'role', true));
			}
			// Check if moderator role exists and assign permission to moderator full role
			if ($this->role_exists('ROLE_MOD_FULL'))
			{
				$data[] = array('permission.permission_set', array('ROLE_MOD_FULL', 'm_pastebin_delete', 'role', true));
				$data[] = array('permission.permission_set', array('ROLE_MOD_FULL', 'm_pastebin_edit', 'role', true));
				$data[] = array('permission.permission_set', array('ROLE_MOD_FULL', 'm_pastebin_post_notlim', 'role', true));
			}

			return $data;
		}
		/**
		 * Checks whether the given role does exist or not.
		 *
		 * @param String $role the name of the role
		 * @return true if the role exists, false otherwise
		 * Source: https://github.com/paul999/mention/
		 */
		private function role_exists($role)
		{
			$sql = 'SELECT role_id
		FROM ' . ACL_ROLES_TABLE . "
		WHERE role_name = '" . $this->db->sql_escape($role) . "'";
			$result = $this->db->sql_query_limit($sql, 1);
			$role_id = $this->db->sql_fetchfield('role_id');
			$this->db->sql_freeresult($result);
			return $role_id;
		}
	}
