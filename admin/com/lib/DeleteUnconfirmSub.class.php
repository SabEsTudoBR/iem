<?php
/**
 * This file contains DeleteUnconfirmSub functions.
 *
 * @package interspire.iem.lib
 */
require_once SENDSTUDIO_API_DIRECTORY. '/subscribers.php';
/**
 * Maintenance class
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
class DeleteUnconfirmSub
{

	/**
	 * Database access layer
	 * @var Db Database access layer
	 */
	private $_db = null;



	/**
	 * CONSTRUCTOR
	 * @return DeleteUnconfirmSub Returns an instance of this object
	 */
	public function __construct()
	{  
		$this->_db = IEM::getDatabase();
	}




	/**
	 * DeleteUnConfirmSubscriber
	 * Deleting Unconfirmed Subscribers.
	 *
	 *
	 * @return boolean Returns TRUE if successful, FALSE otherwise
	 */
	public function DeleteUnConfirmSubscriber()
	{   
		$selectQuery = "
			SELECT	emailaddress,listid,subscribedate,subscriberid FROM 	[|PREFIX|]list_subscribers
			WHERE	confirmed =0 ";
			
		$result = $this->_db->Query($selectQuery);
		if (!$result) {
			return false;
		}
		
		$subscribers = new Subscribers_API() ;
		while ($row = $this->_db->Fetch($result)) {
			$dateAfter = strtotime('+'.SENDSTUDIO_SECURITY_AUTO_DELETE_UNCONFIRM_DAYS.' day', $row['subscribedate']);
			$currentDateTime = strtotime(date("Y-m-d h:i:sa"));			
			
			if($currentDateTime > $dateAfter){
				$subscribers->DeleteSubscriber($row['emailaddress'], $row['listid'], $row['subscriberid']);
			}		 
		}
		 
 
		return true;
	}
	 
}
