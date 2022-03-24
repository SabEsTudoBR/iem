<?php
/**
 * This file contains DeleteBounced functions.
 *
 * @package interspire.iem.lib
 */
require_once SENDSTUDIO_API_DIRECTORY. '/subscribers.php';
/**
 * DeleteBounced class
 *
 * This class provide all maintainace functions currently the maintanance provides
 * - Clearing up stall or user error import temp files
 * - Clearing up stall export data
 * - Clearing up unused cookies
 *
 *
 * @package interspire.iem.lib 
 *
 */
class DeleteBounced
{ 
	/**
	 * IMPORT_EXPIRY_TIME
	 * @var import files expiry time to be 5 days old
	 */
	//const IMPORT_EXPIRY_TIME = 432000;
	const DELETE_TIME = "+5 day";
	//define('DELETE_TIME',"+5 day"); 

	/**
	 * Database access layer
	 * @var Db Database access layer
	 */
	private $_db = null;



	/**
	 * CONSTRUCTOR
	 * @return DeleteBounced Returns an instance of this object
	 */
	public function __construct()
	{  
		$this->_db = IEM::getDatabase();
	}

	/**
	 * DeleteBouncedSubscriber
	 * Auto-delete bounced addresses.
	 *
	 * @return boolean Returns TRUE if successful, FALSE otherwise
	 *
	 */
	public function DeleteBouncedSubscriber()
	{
		$selectQuery = "
			SELECT	emailaddress,listid,subscribedate,subscriberid FROM [|PREFIX|]list_subscribers
			WHERE	bounced =1"; 
		   $result = $this->_db->Query($selectQuery);
		if (!$result) {
			return false;
		}
		$subscribers = new Subscribers_API() ;
		while ($row = $this->_db->Fetch($result)) {
			$dateAfter = strtotime('+'.SENDSTUDIO_SECURITY_AUTO_DELETE_BOUNCED_DAYS.' day', $row['subscribedate']);
		
			$currentDateTime = strtotime(date("Y-m-d h:i:sa"));			
			 
			if($currentDateTime > $dateAfter){
				$subscribers->DeleteSubscriber($row['emailaddress'], $row['listid'], $row['subscriberid']);	 
			}		 
		}
		 
 
		return true;
	}
	 
}
