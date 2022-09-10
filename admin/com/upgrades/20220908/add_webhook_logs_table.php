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
* add_webhook_logs_table
*
* @see Upgrade_API
*
* @package SendStudio
*/
class add_webhook_logs_table extends Upgrade_API
{
	/**
	* RunUpgrade
	* Runs the add_user_credit_summary_table upgrade
	*
	* @return boolean Returns TRUE if successful, FALSE otherwise
	*/
	function RunUpgrade()
	{
		if ($this->TableExists('webhook_logs')) {
			return true;
		}

		$query = "
			CREATE TABLE IF NOT EXISTS " . SENDSTUDIO_TABLEPREFIX . "webhooks (
			  webhookid 			int(11) 		NOT NULL AUTO_INCREMENT,
			  webhook_type_id 		int(11) 		NOT NULL,
			  webhook_event_type_id	int(11) 		NOT NULL,
			  id 					int(11) 		DEFAULT 0 COMMENT '(listid or newsletterid)',
			  webhook_url 			varchar(2048) 	NOT NULL,
			  createdate 			int(11) 		NOT NULL DEFAULT 0,
			  active 				int(1) 			NOT NULL DEFAULT 1,
			  ownerid 				int(11) 		NOT NULL DEFAULT 0,
			  PRIMARY KEY (webhookid)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
			";

		$result = $this->Db->Query($query);
		return $result;
	}
}
