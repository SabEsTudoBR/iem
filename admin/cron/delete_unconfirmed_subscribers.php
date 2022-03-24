<?php
/**
 * This file is for all maintenance purpose
 * - Clearing all exports queries that are stalled
 * - Clearing all the import files that are still there and failed..
 * - Clearing all the old session files that are older then 5 years days.
 *
 * @package interspire.iem.cron
 */

// Include CRON common file
require_once dirname(__FILE__) . '/common.php';
 

$delete_unconfirmed_check = CheckCronSchedule('deleteunconfirmsub');
if(SENDSTUDIO_SECURITY_AUTO_DELETE_UNCONFIRM > 0){
	
	if ($delete_unconfirmed_check) {
		
		$api = new DeleteUnconfirmSub();    
		$status = $api->DeleteUnConfirmSubscriber();
		if (!$status) {
			trigger_error(__FILE__ . '::' . __LINE__ . ' -- Unable to delete subscriber', E_USER_NOTICE);
		}

		 
	}
} 
