<?php
/**
* The Webhook API. It handles 'Webhooks' reqeusts and responses.
*
* @author Imran Khan <imran.khan@interspire.com>
*
* @package API
* @subpackage Webhook_API
*/

/**
* Load up the base API class if we need to.
*/
require_once(dirname(__FILE__) . '/api.php');

/**
* This will handle Webhooks.
*
* @package API
* @subpackage Webhooks_API
*/
class Webhooks_API extends API
{
	
	/**
	* The webhookid is the ID of the webhook.
	*
	* @var Int
	*/
	var $webhookid = 0;
	
	/**
	* The webhook_type_id is 'List=1 , Campaign=2'.
	*
	* @var Varchar
	*/
	var $webhook_type_id = 0;
	
	/**
	* The webhook_event_type_id is 'Subscribe=1 , Unsubscribe=2'.
	*
	* @var Varchar
	*/
	var $webhook_event_type_id = 0;
	
	/**
	* The id(ListID or CampaignID) that is loaded. By default is 0.
	*
	* @var Int
	*/
	var $id = 0;
	
	/**
	* The Webhookdata that is loaded and save data in serialize format. 
	*
	* @var serialize
	*/
	var $webhookdata = [];
	
	/**
	* The timestamp of when the list was created (integer)
	*
	* @var Int
	*/
	var $createdate = 0;

	/**
	* The timestamp of when the event was fired (integer)
	*
	* @var Int
	*/
	var $eventdate = 0;

	/**
	* The active is the status of the webhook.
	*
	* @var Int
	*/
	var $active = 1;
	
	/**
	* The ownerid of the owner of this list.
	*
	* @var Int
	*/
	var $ownerid = 0;


	/**
	* Constructor
	* Sets up the database object, also check 'curl' extension availibility.
	*
	* @see GetDb
	*
	* @return True|Load If curl extension is enabled, so this will always return true.
	*/
	function __construct()
	{
		$this->GetDb();
		if (!extension_loaded("curl")) {
		  // cURL is not loaded...
		  trigger_error("cURL is not loaded", E_USER_NOTICE);
		  return false;
		}

		return true;
	}
	
	/**
	* CreateWebhook
	* This function creates a webhook based on the current class vars.
	*
	* @return Boolean Returns true if it worked, false if it fails.
	*/
	function CreateWebhook()
	{

		$createdate = $this->GetServerTime();
		if ((int)$this->createdate > 0) {
			$createdate = (int)$this->createdate;
		}
		
		foreach($this->webhookdata as $webhook) {	 
				 
			 $query = "INSERT INTO " . SENDSTUDIO_TABLEPREFIX . "webhooks
			(webhook_type_id, id, webhook_url, webhook_event_type_id, createdate, active, ownerid) 
			VALUES ('".$this->webhook_type_id."', '" . $this->id . "', 
			'" . $this->Db->Quote($webhook['url']). "','".$webhook['event']."', '" . $createdate . "', 
			'" . (int)$this->active . "', '" . $this->Db->Quote($this->ownerid) . "')";
		
			$result = $this->Db->Query($query);
			
			if(!$result) {
				return false;
			}
		}
		return true;
	}
	
	/**
	* GetEventTypeName
	* This will return the Event Type Name based on the typeid passed in.
	*
	* @param String $event Events of Webhook ['ScheduleCampaigFn', 'CampaignSent'].
	*
	* @return event_type_name if it worked, false if it fails.
	*/
	function GetEventTypeName($typeid){
		$query = "SELECT event_type_name FROM " . SENDSTUDIO_TABLEPREFIX . "webhook_event_types 
		where  event_type_id='".$typeid."'";
		$result = $this->Db->Query($query);
		
		if (!$result) {
			return false;
		}
		$row = $this->Db->Fetch($result);
		
		return $row['event_type_name'];
	}
	

	/**
	* PostListWebhook
	* This will post the Payload to Webhook URL.
	*
	* @param String $listid list ID of contact list.
	* @param String $subscriberid The message to send the owner.
	* @param String $event Events of Webhook ['Subscribe', 'Unsubscribe', 'Bounce'].
	*
	* @return Void Doesn't return anything.
	*/
	function PostListWebhook($listid=0, $subscriberid=0, $event='')
	{
	
		if($event <= 0 || $listid <= 0 || $subscriberid <= 0) {
			return false;
		}
		
		if($this->eventdate <= 0) {
			$this->eventdate = $this->GetServerTime();
		}
		
		  $query = "SELECT l.name as list_name, ls.emailaddress, wet.event_type_name, wh.webhook_event_type_id, ls.confirmed,
			ls.unsubscribed, ls.bounced, ls.confirmdate, wh.webhook_url FROM " . SENDSTUDIO_TABLEPREFIX . "lists as l
			INNER JOIN " . SENDSTUDIO_TABLEPREFIX . "list_subscribers AS ls on l.listid = ls.listid
			INNER JOIN " . SENDSTUDIO_TABLEPREFIX . "webhooks AS wh on l.listid = wh.id
			INNER JOIN " . SENDSTUDIO_TABLEPREFIX . "webhook_event_types AS wet on wh.webhook_event_type_id = wet.event_type_id
			WHERE wh.id='". $listid."' and wh.webhook_type_id = '1' and  ls.subscriberid ='".$subscriberid."'";
					
		$result_data = $this->Db->Query($query);
		
		if(!$result_data) {
			return false;
		}
		
		// This is for multiple Webhooks
		while ($row = $this->Db->Fetch($result_data)) {

			if($row['webhook_event_type_id'] != $event) {
				continue;
			}

			if($row['webhook_url'] == "") {
				return false;
			} 
			if($row['webhook_event_type_id'] <= 0) {
				return false;
			}
			
			if($row['webhook_event_type_id']  == 1) {
				$payload = [
					'event'				=> $row['event_type_name'],
					'listname'			=> $row['list_name'],
					'emailaddress'		=> $row['emailaddress'],
					'confirmed'			=> $row['confirmed'],
					'confirmdate'		=> $row['confirmdate']
				];
				
				require_once(dirname(__FILE__) . '/subscribers.php');
				$subscriberapi = new Subscribers_API();
				$customfield_data = $subscriberapi->LoadSubscriberCustomFields($subscriberid, $listid);
				foreach($customfield_data as $customfield) {
					$payload['customfields'][ $customfield['fieldname'] ] = $customfield['data'];
				}
				
			} elseif ($row['webhook_event_type_id']  == 2) {
				$unsubscribed = ($row['unsubscribed'] > 0) ? '1' : '0';
				
				$unsubscribedate = $this->eventdate;

				$payload = [
					'event'				=> $row['event_type_name'],
					'listname'			=> $row['list_name'],
					'emailaddress'		=> $row['emailaddress'],
					'unsubscribed'		=> 1,
					'unsubscribedate'	=> $unsubscribedate
				];
				
			} elseif ($row['webhook_event_type_id']  == 3) {
				
				$bouncedate = $this->eventdate;
				
				$payload = [
					'event'				=> $row['event_type_name'],
					'listname'			=> $row['list_name'],
					'emailaddress'		=> $row['emailaddress'],
					'bounced'			=> 1,
					'bouncedate'		=> $bouncedate
				];
			}
			
			// Log Webhook Payload
			$payload_log["url"] = $row['webhook_url'];
			$payload_log["payload"] = $payload;
			
			$payload_serialized = serialize($payload_log);
			
			
			trigger_error("PostListWebhook has been Triggered for an event '".$row['event_type_name']."'", E_USER_NOTICE);
			
			//****************Webhook CURL***************\\
			// API URL
			$url = $row['webhook_url'];
			
			// Initialize curl
			$curl = curl_init();

			$jsonEncodedData = json_encode($payload);

			$opts = [
				CURLOPT_URL             => $url,
				CURLOPT_RETURNTRANSFER  => true,
				CURLOPT_CUSTOMREQUEST   => 'POST',
				CURLOPT_POST            => 1,
				CURLOPT_POSTFIELDS      => $jsonEncodedData,
				CURLOPT_HTTPHEADER  	=> ['Content-Type: application/json','Content-Length: ' . strlen($jsonEncodedData)]                                                                     
			];


			// Set curl options
			curl_setopt_array($curl, $opts);

			// Get the results
			$result = curl_exec($curl);
			
			$webhook_response = $result;			
			$this->WebhookLog($payload_serialized, $webhook_response, '1', $row['webhook_event_type_id']);
	
			// Close resource
			curl_close($curl);
	
		} //foreach Loop End
	}

	/**
	* PostCampaignWebhook
	* This will post the Payload to Webhook URL.
	*
	* @param String $event Events of Webhook ['ScheduleCampaign', 'CampaignSent'].
	*
	* @return Void Doesn't return anything.
	*/
	function PostCampaignWebhook($event='')
	{
		$send_details = IEM::sessionGet('SendDetails');
		$newsletterid = $send_details['Newsletter'];
		$SendStartTime = $send_details['SendStartTime'];
		$SendEndTime = isset($send_details['SendEndTime']) ? $send_details['SendEndTime'] : "";
		$StatID = $send_details['StatID'];
		$SendSize = $send_details['SendSize'];

		require_once(dirname(__FILE__) . '/newsletters.php');
		$newsletter_api = new Newsletters_API();
		
		//Get Newsletter details
		$newsletter_detail = $newsletter_api->GetRecordByID($newsletterid);
		$this->webhook_type_id = 2;
		$webhooks_arr = $this->GetWebhooks($newsletterid);
		
		if(empty($webhooks_arr)) {
			return false;
		}
		
	
		// This is for multiple Webhooks
		foreach($webhooks_arr as $webhook_row) {
			 
			if($webhook_row['webhook_event_type_id'] != $event) {
				continue;
			}
			
			$event_type_name = $this->GetEventTypeName($webhook_row['webhook_event_type_id']);

			if($webhook_row['webhook_url'] == '') {
				return false;
			} 
			
			if($webhook_row['webhook_event_type_id'] <= 0) {
				return false;
			}
			
			if($webhook_row['webhook_event_type_id']  == $event) {
				
				$payload = [
					'event'					=> $event_type_name,
					'CampaignId'			=> $newsletterid,
					'CampaignName'			=> $newsletter_detail['name'],
					'CampaignSubject'		=> $newsletter_detail['subject'],
					'CampaignCreatedDate'	=> $newsletter_detail['createdate'],
					'SendStartTime'			=> $SendStartTime,
					'SendEndTime'			=> $SendEndTime,
					'Recipients' 			=> $send_details['SendSize']
				];
				

			} elseif($webhook_row['webhook_event_type_id']  == $event) {
				
				$payload = [
					'event'					=> $event_type_name,
					'CampaignId'			=> $newsletterid,
					'CampaignName'			=> $newsletter_detail['name'],
					'CampaignSubject'		=> $newsletter_detail['subject'],
					'CampaignCreatedDate'	=> $newsletter_detail['createdate'],
					'SendStartTime'			=> $SendStartTime,
					'SendEndTime'			=> $SendEndTime,
					'Recipients' 			=> $send_details['SendSize']
				];
				
			} 
			
			// Log Webhook Payload
			$payload_log["url"] = $webhook_row['webhook_url'];
			$payload_log["payload"] = $payload;
			
			$payload_serialized = serialize($payload_log);
			
			trigger_error("PostCampaignWebhook has been Triggered for an event '".$event_type_name."'", E_USER_NOTICE);

			//****************Webhook CURL***************\\
			// API URL
			$url = $webhook_row['webhook_url'];
			
			// Initialize curl
			$curl = curl_init();

			$jsonEncodedData = json_encode($payload);

			$opts = [
				CURLOPT_URL             => $url,
				CURLOPT_RETURNTRANSFER  => true,
				CURLOPT_CUSTOMREQUEST   => 'POST',
				CURLOPT_POST            => 1,
				CURLOPT_POSTFIELDS      => $jsonEncodedData,
				CURLOPT_HTTPHEADER  	=> ['Content-Type: application/json','Content-Length: ' . strlen($jsonEncodedData)]                                                                     
			];

			// Set curl options
			curl_setopt_array($curl, $opts);

			// Get the results
			$result = curl_exec($curl);
			
			$webhook_response = $result;	
			
			$this->WebhookLog($payload_serialized, $webhook_response, '2', $webhook_row['webhook_event_type_id']);
	
			// Close resource
			curl_close($curl);
	
		} //foreach Loop End		
	}
	
	/**
	* GetWebhooks
	* Get a list of webhooks based on the criteria passed in.
	*
	* @param Mixed $webhooktype This is used to restrict which webhook type this will fetch information for. If this is not passed in (it's null), then all webhooks are checked. If this is not null, it will be an array of webhook's to page through. This is so a user is restricted as to which webhooks they are shown.
	* @param Array $sortinfo An array of sorting information - what to sort by and what direction.
	* @param Boolean $countonly Whether to only get a count of webhooks, rather than the information.
	* @param Int $start Where to start in the list. This is used in conjunction with perpage for paging.
	* @param Int|String $perpage How many results to return (max).
	*
	* @see ValidSorts
	* @see DefaultOrder
	* @see DefaultDirection
	*
	* @return Mixed Returns false if it couldn't retrieve list information. Otherwise returns the count (if specified), or an array of webhooks.
	*/
	function GetWebhooks($id=0)
	{
		
		if ($id <= 0) {
			return false;
		}

		$query = "SELECT * FROM " . SENDSTUDIO_TABLEPREFIX . "webhooks where id='".$id."'  and webhook_type_id='".$this->webhook_type_id."'";
		
		$result = $this->Db->Query($query);
		if (!$result) {
			list($error, $level) = $this->Db->GetError();
			trigger_error($error, $level);
			return false;
		}
		$return_webhooks = [];
		while ($row = $this->Db->Fetch($result)) {
			$return_webhooks[] = $row;
		}

		return $return_webhooks;
	}
	
	/**
	* GetWebhookById
	* Get available Webhook for a particular List.
	*
	* @param Integer $listid List ID is required, If List ID is not supplied, it will return false.
	*
	* @return Mixed Returns false If List ID is not supplied. Otherwise returns the count (if specified), or an array of webhooks.
	*/
	function GetWebhookById()
	{
		if ($this->id <= 0) {
			return false;
		}
		
		if(isset($GLOBALS['userid'])){
			$this->ownerid = $GLOBALS['userid'];
		}

		$query = "SELECT * FROM " . SENDSTUDIO_TABLEPREFIX . "webhooks where id='" .$this->id . "' and webhook_type_id='".$this->webhook_type_id."'";
		
		$result = $this->Db->Query($query);
		if (!$result) {
			return false;
		}
		
		$row = $this->Db->Fetch($result);
		return $row;
	}
	
	/**
	* DeleteWebhook
	* Delete a webhook from the database and finally reset all class vars.
	*
	* @param Int $webhookid webhookid of the webhook to delete. If not passed in, it will delete 'this' list.
	* @param Int $userid The userid that is deleting the list. This is used so the stats api can "hide" stats.
	*
	* @see Stats_API::HideStats
	* @see DeleteAllSubscribers
	*
	* @return Boolean True if it deleted the list, false otherwise.
	*
	*/
	function DeleteWebhook()
	{
		if ($this->id <= 0) {
			return false;
		}

		$this->Db->StartTransaction();

		 $query = "DELETE FROM " . SENDSTUDIO_TABLEPREFIX . "webhooks WHERE id='" . $this->id . "' and webhook_type_id='".$this->webhook_type_id."'";
		
		$result = $this->Db->Query($query);
		if (!$result) {
			return false;
		}
		
		$this->Db->CommitTransaction();

		return true;
	}
	
	/**
	* CopyWebhook
	* Copy Webhook details only.
	*
	* @param Int $int Webhookid to copy.
	*
	* @see LoadWebhook
	* @see Create
	* @see Save
	*
	* @return Array Returns an array of status (whether the copy worked or not) and a message to go with it. If the copy worked, then the message is 'false'.
	*/
	function CopyWebhook($newid)
	{
		
		$createdate = $this->GetServerTime();
		if ((int)$this->createdate > 0) {
			$createdate = (int)$this->createdate;
		}
	
		if ($this->id <= 0) {
			return false;
		}
	
		$webhooks_arr = $this->GetWebhooks($this->id);

		/**
			the Create method looks at the createdate class variable to see if it can use it, or if it should use 'now'.
			So we need to re-set it to 0.
		*/
		$this->createdate = 0;
		foreach($webhooks_arr as $webhook) {
			$query = "INSERT INTO " . SENDSTUDIO_TABLEPREFIX . "webhooks
				(webhook_type_id, id, webhook_url, webhook_event_type_id, createdate, active, ownerid) 
				VALUES ('".$webhook['webhook_type_id']."', '" . $newid . "', '" . $webhook['webhook_url']. "','".$webhook['webhook_event_type_id']."', 
				'" . $createdate . "', '" . (int)$this->active . "', '" . $this->ownerid . "')";
		
			$result = $this->Db->Query($query);
			
			if (!$result) {
				return false;
			}
		}
		return true;
	}
	
	
	/**
	* WebhookLog
	* This function creates a log entry based on the current class vars.
	*
	* @return Boolean Returns true if it worked, false if it fails.
	*/
	function WebhookLog($logmsg, $webhook_response, $webhook_type_id, $webhook_event_type_id)
	{

		$createdate = $this->GetServerTime();
	
		$query = "INSERT INTO " . SENDSTUDIO_TABLEPREFIX . "webhook_logs
		(webhook_type_id, webhook_event_type_id, logmsg, webhook_response, logdate) 
		VALUES ('" . $webhook_type_id. "','" . $webhook_event_type_id. "','" . $this->Db->Quote($logmsg). "' ,'" . $this->Db->Quote($webhook_response). "', '" . $createdate . "')";
	
		$result = $this->Db->Query($query);
		
		if(!$result) {
			return false;
		}
		return true;
	}
}
