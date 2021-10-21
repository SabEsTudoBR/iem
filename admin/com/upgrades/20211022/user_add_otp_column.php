<?php
/**
* This file is part of the upgrade process.
*
* @author Imran Khan <imran.khan@interspire.com>
*
* @package SendStudio
*/

/**
* Do a sanity check to make sure the upgrade api has been included.
*/
if (!class_exists('Upgrade_API', false)) {
	exit;
}

/**
* This class runs one change for the upgrade process.
* The Upgrade_API looks for a RunUpgrade method to call.
* That should return false for failure
* It should return true for success or if the change has already been made.
*
* @package SendStudio
*/
class user_add_otp_column extends Upgrade_API
{
	/**
	 * RunUpgrde
	 * Run current upgrade
	 * @return Boolean Returns TRUE if successful, FALSE otherwise
	 */
	function RunUpgrade()
	{
		if ($this->ColumnExists('users', 'otp')) {
			return true;
		}

		$query = "ALTER TABLE " . SENDSTUDIO_TABLEPREFIX . "users ADD COLUMN otp varchar(50) DEFAULT NULL";

		$result = $this->Db->Query($query);
		
		if ($result == false) {
			return false;
		}

		return true;
	}
}
