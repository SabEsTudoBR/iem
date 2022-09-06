<?php
/**
* This file is part of the upgrade process.
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
* add_webhook_types_table
*
* @see Upgrade_API
*
* @package SendStudio
*/
class add_webhook_types_table extends Upgrade_API
{
	/**
	* RunUpgrade
	* Runs the add_user_credit_summary_table upgrade
	*
	* @return boolean Returns TRUE if successful, FALSE otherwise
	*/
	function RunUpgrade()
	{
		if ($this->TableExists('webhook_types')) {
			return true;
		}

		$queries[] = "
			CREATE TABLE IF NOT EXISTS " . SENDSTUDIO_TABLEPREFIX . "webhook_types (
				webhook_type_id 	int(11) 		NOT NULL,
				webhook_type_name 	varchar(100) 	NOT NULL,
				PRIMARY KEY (webhook_type_id)
			) 	ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
			";
			
		$queries[] = "
			INSERT INTO " . SENDSTUDIO_TABLEPREFIX . "webhook_types (webhook_type_id, webhook_type_name) VALUES 
			(1, 'List'), 
			(2, 'Campaign');
			";

		foreach ($queries as $query) {
			$result = $this->Db->Query($query);
			if (!$result) {
				return false;
			}
		}
		
		return true;
	}
}
