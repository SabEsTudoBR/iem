<?php
/**
 * This file contains the 'debug' addon which enable/disable email,bounce, trigger and autoresponder debugging
 *
 * @package Interspire_Addons
 * @subpackage Addons_debug
 *
 *  @author Imran Khan
 */

/**
 * Make sure the base Interspire_Addons class is defined.
 */
 if (!class_exists('Interspire_Addons', false)) {
	require_once(dirname(dirname(__FILE__)) . '/interspire_addons.php');
}

/**
 * This class handles debug management
 *
 * @uses Interspire_Addons
 * @uses Interspire_Addons_Exception
 */
class Addons_debug extends Interspire_Addons
{
	/**
	 * Install
	 * This addon has no settings to it can automatically be configured and enabled when it's installed
	 *
	 * @uses enabled
	 * @uses configured
	 * @uses Interspire_Addons::Install
	 * @uses Interspire_Addons_Exception
	 *
	 * @throws Throws an Interspire_Addons_Exception if something in the install process fails.
	 * @return True Returns true if everything works ok.
	 */
	public function Install()
	{   
	    try {
			$this->InstallSettings(); 
			$status = parent::Install();
			 
		} catch (Interspire_Addons_Exception $e) {
			throw new Exception("Unable to install addon {$this->GetId()} " . $e->getMessage());
		}
		return true;
	}
	 
	/**
	 * DbPermissionCheck
	 * Checks if the database user has sufficient privileges to upgrade and will not allow upgrades to continue if not.
	 *
	 * @see DbVersionCheck
	 *
	 * @return Void Does not return anything.
	 */
	public function InstallSettings()
	{
		$this->db = IEM::getDatabase();
		 
		$query = "SHOW TABLES LIKE '[|PREFIX|]debugging_addon_settings'";
		$check_table_name = $this->db->FetchOne($query);
		if($check_table_name != $this->db->TablePrefix.'debugging_addon_settings'){		
			$queries = "CREATE TABLE [|PREFIX|]debugging_addon_settings(area varchar(50), areavalue text) character set utf8 engine=innodb";
			$this->db->Query($queries);
		}
		$this->enabled = true;
		$this->configured = true;
		  
		$status_query = "SELECT areavalue FROM [|PREFIX|]debugging_addon_settings where area='EMAIL_DEBUG'";
		$EmailDebug = $this->db->FetchOne($status_query);
		if(empty($EmailDebug)){
			$query = "INSERT INTO " . SENDSTUDIO_TABLEPREFIX . "debugging_addon_settings(area, areavalue) VALUES('EMAIL_DEBUG', '0')";
			$result = $this->db->Query($query);
		}
		$status_bounce_query = "SELECT areavalue FROM [|PREFIX|]debugging_addon_settings where area='BOUNCE_DEBUG'";
		$BounceDebug = $this->db->FetchOne($status_bounce_query);
		if(empty($BounceDebug)){
			$query = "INSERT INTO " . SENDSTUDIO_TABLEPREFIX . "debugging_addon_settings(area, areavalue) VALUES('BOUNCE_DEBUG', '0')";
			$result = $this->db->Query($query);
		}
		$status_triggeremail_query = "SELECT areavalue FROM [|PREFIX|]debugging_addon_settings where area='TRIGGEREMAIL_DEBUG'";
		$BounceTriggeremailDebug = $this->db->FetchOne($status_triggeremail_query);
		if(empty($BounceTriggeremailDebug)){
			$query = "INSERT INTO " . SENDSTUDIO_TABLEPREFIX . "debugging_addon_settings(area, areavalue) VALUES('TRIGGEREMAIL_DEBUG', '0')";
			$result = $this->db->Query($query);
		}
		$status_autoresponder_query = "SELECT areavalue FROM [|PREFIX|]debugging_addon_settings where area='AUTORESPONDER_DEBUG'";
		$BounceAutoresponderDebug = $this->db->FetchOne($status_autoresponder_query);
		if(empty($BounceAutoresponderDebug)){
			$query = "INSERT INTO " . SENDSTUDIO_TABLEPREFIX . "debugging_addon_settings(area, areavalue) VALUES('AUTORESPONDER_DEBUG', '0')";
			$result = $this->db->Query($query);
		}
	}
	
	/**
	 * Uninstall
	 * This is called when the addon is uninstalled in the main application.
	 *
	 * @uses default_settings
	 * @uses Interspire_Addons::Install
	 * @uses Interspire_Addons_Exception
	 *
	 * @throws Throws an Interspire_Addons_Exception if something goes wrong with the install process.
	 * @return True Returns true if all goes ok with the install.
	 */
	public function Uninstall()
	{      
		try {
			
			$status = parent::Uninstall();
			$this->UninstallSettings();
		} catch (Interspire_Addons_Exception $e) {
			$this->db->RollbackTransaction();
			throw new Exception("Unable to uninstall addon $this->GetId();" . $e->getMessage());
		}

		$this->db->CommitTransaction();
		return true;
	}
	
	public function UninstallSettings()
	{
			$this->db = IEM::getDatabase();
			$query = "DROP TABLE [|PREFIX|]debugging_addon_settings";
           if($this->db->Query($query)) {
			    $this->enabled = false;
			    $this->configured = false;
				return true;
			}
			return false;		
	}
	/**
	 * GetEventListeners
	 * This addon puts itself in the 'tools' text menu at the top of the page.
	 * It uses the 'IEM_SENDSTUDIOFUNCTIONS_GENERATETEXTMENULINKS' event to do this.
	 *
	 * @return Array Returns an array containing the 'IEM_SENDSTUDIOFUNCTIONS_GENERATETEXTMENULINKS' event to listen to.
	 */
	public function GetEventListeners()
	{  
		return
			array (
				array (
					'eventname' => 'IEM_SENDSTUDIOFUNCTIONS_GENERATETEXTMENULINKS',
					'trigger_details' => array (
						'Addons_debug',
						'GetTextMenuItems',
					),
					'trigger_file' => '{%IEM_ADDONS_PATH%}/debug/debug.php'
				),
			);
	}

	/**
	 * GetTextMenuItems
	 * This checks the addon is installed & enabled before displaying in the 'tools' menu at the top of the page.
	 *
	 * @param EventData_IEM_SENDSTUDIOFUNCTIONS_GENERATETEXTMENULINKS $data The existing text menu items. This addon puts itself into the tools menu.
	 *
	 * @uses Load
	 * @uses enabled
	 *
	 * @see SendStudio_Functions::GenerateTextMenuLinks
	 *
	 * @return Void The menu is passed in by reference, so it's manipulated directly.
	 *
	 * @uses EventData_IEM_SENDSTUDIOFUNCTIONS_GENERATETEXTMENULINKS
	 */
	static public function GetTextMenuItems(EventData_IEM_SENDSTUDIOFUNCTIONS_GENERATETEXTMENULINKS $data)
	{  
		$user = IEM::getCurrentUser();
		if (!$user->Admin()) {
			return;
		}

		try {
			$me = new self;
			$me->Load();
		} catch (Exception $e) {
			return;
		}

		if (!$me->enabled) {
			return;
		}

		if (!isset($data->data['tools'])) {
			$data->data['tools'] = array();
		}

		$data->data['tools'][] = array (
			'text' => GetLang('Addon_debug_Menu_Text'),
			'link' => $me->admin_url,
			'description' => GetLang('Addon_debug_Menu_Description'),
		);
		unset($me);
	}

	/**
	 * Admin_Action_Default
	 * This is the first page shown when you view the addon.
	 *
	 * @return Void Does not return anything.
	 */
	public function Admin_Action_Default()
	{ 
	  
		require_once(dirname(__FILE__) . '/api/debug.php');
		
		$this->db = IEM::getDatabase();
		
		if(isset($_GET['action'])) {
			switch ($_GET['action']) {
				case 'emaildebug':
					
					$Emaildebug = empty($_POST['emaildebug'])? 0 : 1;
					$ShowTab = 1;
					$query = "UPDATE [|PREFIX|]debugging_addon_settings SET areavalue ='".$Emaildebug."' where area ='EMAIL_DEBUG' ";
					if($this->db->Query($query))
						FlashMessage(GetLang ( 'Addon_debug_Update_Success' ), SS_FLASH_MSG_SUCCESS, 'index.php?Page=Addons&Addon=debug&Tab=1');
					else
						FlashMessage(GetLang ( 'Addon_debug_Update_Failure' ), SS_FLASH_MSG_ERROR, 'index.php?Page=Addons&Addon=debug&Tab=1');
				
					break;
				case'bouncedebug':

					$Bouncedebug = empty($_POST['bouncedebug'])? 0 : 1;
					$ShowTab = 2;			
					$query = "UPDATE [|PREFIX|]debugging_addon_settings SET areavalue =".$Bouncedebug." where area ='BOUNCE_DEBUG' ";
					if($this->db->Query($query))
						FlashMessage(GetLang ( 'Addon_debug_Update_Success' ), SS_FLASH_MSG_SUCCESS, 'index.php?Page=Addons&Addon=debug&Tab=2');
					else
						FlashMessage(GetLang ( 'Addon_debug_Update_Failure' ), SS_FLASH_MSG_ERROR, 'index.php?Page=Addons&Addon=debug&Tab=2');
					
					break;
				case'triggeremaildebug':
				
					$Triggeremaildebug = empty($_POST['triggeremaildebug'])? 0 : 1;
					$ShowTab = 3;			
					$query = "UPDATE [|PREFIX|]debugging_addon_settings SET areavalue ='".$Triggeremaildebug."' where area ='TRIGGEREMAIL_DEBUG'";
					if($this->db->Query($query))
						FlashMessage(GetLang ( 'Addon_debug_Update_Success' ), SS_FLASH_MSG_SUCCESS, 'index.php?Page=Addons&Addon=debug&Tab=3');
					else
						FlashMessage(GetLang ( 'Addon_debug_Update_Failure' ), SS_FLASH_MSG_ERROR, 'index.php?Page=Addons&Addon=debug&Tab=3');
				break;
			
			     //for auto responder 
			    case'autoresponderdebug':			
					$Autoresponderdebug = empty($_POST['autoresponderdebug'])? 0 : 1;
					$ShowTab = 4;			
					$query = "UPDATE [|PREFIX|]debugging_addon_settings SET areavalue ='".$Autoresponderdebug."' where area ='AUTORESPONDER_DEBUG' ";
					if($this->db->Query($query))
						FlashMessage(GetLang('Addon_debug_Update_Success'), SS_FLASH_MSG_SUCCESS, 'index.php?Page=Addons&Addon=debug&Tab=4');
					else
						FlashMessage(GetLang('Addon_debug_Update_Failure'), SS_FLASH_MSG_ERROR, 'index.php?Page=Addons&Addon=debug&Tab=4');
				break;
			}
		}
		
		if(isset($_GET['action'])) {			 
			 
			//CLEAN EMAIL DEBUG
			if($_GET['action']=='CleanupEmailDebug') { 
				if (is_file(TEMP_DIRECTORY . '/email.debug.log' )) {
					if (@unlink(TEMP_DIRECTORY . '/email.debug.log')) {
						FlashMessage(GetLang ( 'Email_Log_Deleted'), SS_FLASH_MSG_SUCCESS,  'index.php?Page=Addons&Addon=debug&Tab=1');
					}
				}
			}
			 
			 //for bounce debug
			if($_GET['action']=='CleanupBounceDebug') { 
				if (is_file(TEMP_DIRECTORY . '/bounce.debug.log' )) {
					if (@unlink(TEMP_DIRECTORY . '/bounce.debug.log')) {
						FlashMessage(GetLang ( 'Bounce_Log_Deleted'), SS_FLASH_MSG_SUCCESS, 'index.php?Page=Addons&Addon=debug&Tab=2');
					}
				}
			}
		}
		$flash_messages = GetFlashMessages();
		 
		//email debug
		if (is_file(TEMP_DIRECTORY.'/email.debug.log')) {
			$LogFilelink = SENDSTUDIO_TEMP_URL.'/email.debug.log';				 
			$DownloadlinkEmail = '<a href="'.$LogFilelink.'" target="_blank">'.GetLang ( 'Download_Log_File_Text').' </a>';
			$GenerateDeleteButtonEmail = '<input type="button" value="'.GetLang('DeleteLogExportFile').'" class="SmallButton" style="width: 120px" onClick="javascript: document.location=\'index.php?Page=Addons&Addon=debug&action=CleanupEmailDebug\';">';			
		} else {
			$LogFilelink ='';
			$DownloadlinkEmail = GetLang('Log_File_Not_Exist');
			$GenerateDeleteButtonEmail = '';							
		}
       
		if (is_file(TEMP_DIRECTORY.'/bounce.debug.log')) { 	
			$LogFilelinkBonceDebug = SENDSTUDIO_TEMP_URL.'/bounce.debug.log';				 
			$Downloadlink_bounce_debug = '<a href="'.$LogFilelinkBonceDebug.'" target="_blank">'.GetLang ( 'Download_Log_File_Text').'</a>';
			$GenerateDeleteButtonBounce = '<input type="button" value="'.GetLang('DeleteLogExportFile').'" class="SmallButton" style="width: 120px" onClick="javascript: document.location=\'index.php?Page=Addons&Addon=debug&action=CleanupBounceDebug\';">';				
		} else {
			$Downloadlink_bounce_debug = GetLang('Log_File_Not_Exist');
			$GenerateDeleteButtonBounce = '';							
		}
		
		
		$logsFileList  = [];
		$TriggerLoglogsFileList  = [];
		$logsAutoresponderFileList  = [];
		$bounce_debug_status = $this->db->Query("SELECT * FROM [|PREFIX|]debugging_addon_settings   where area ='BOUNCE_DEBUG' ");		 
		$row = $this->db->Fetch($bounce_debug_status);
		$Bouncedebug = $row['areavalue'];
		//for email active 
		$email_debug_status = $this->db->Query("SELECT * FROM [|PREFIX|]debugging_addon_settings where area ='EMAIL_DEBUG' ");		 
		$row = $this->db->Fetch($email_debug_status);
		$Emaildebug = $row['areavalue'];
		//for trigger 
		$trigger_email_debug_status = $this->db->Query("SELECT * FROM [|PREFIX|]debugging_addon_settings where area ='TRIGGEREMAIL_DEBUG' ");		 
		$row = $this->db->Fetch($trigger_email_debug_status);		 
		$TriggerEmaildebug = $row['areavalue'];
		
		if (defined('TEMP_DIRECTORY')) {
			if ($handle = opendir(TEMP_DIRECTORY)) {
				
				while (false !== ($entry = readdir($handle))) {
					//check trigger emails logs file
					if (preg_match('%^triggeremails_debug(.*)%i', $entry)) {
						 $TriggerLoglogsFileList[] = array(
							'filename'=> $entry						    
						);
					}
					///check autoresponder  logs file
					if (preg_match('%^autoresponder_debug(.*)%i', $entry)) {
					     	 
						$logsAutoresponderFileList[] = array(
							'filename'=> $entry						
						);
						 
					}
				}
			}
		}
		/////
		/*-----Total Trigger emails logs files------------------*/
		$triggeremail_number_of_logs = count($TriggerLoglogsFileList);	
		
		/*-----Total Autoresponder emails logs files------------------*/   
		$logsAutoresponder_number_of_logs = count($logsAutoresponderFileList);		
		
		$autoresponder_debug_status = $this->db->Query("SELECT * FROM [|PREFIX|]debugging_addon_settings where area ='AUTORESPONDER_DEBUG' ");		 
		$row = $this->db->Fetch($autoresponder_debug_status);		 
		$Autoresponderdebug = $row['areavalue'];
		$ShowTab = empty($_GET['Tab'])? 1 : $_GET['Tab'];
		
		$this->template_system->Assign('ShowTab', $ShowTab);
		$this->template_system->Assign('Bouncedebug', $Bouncedebug);
		$this->template_system->Assign('Emaildebug', $Emaildebug);
		$this->template_system->Assign('Autoresponderdebug', $Autoresponderdebug);
		$this->template_system->Assign('TriggerEmaildebug', $TriggerEmaildebug);
		$this->template_system->Assign('flash_messages', $flash_messages);
		$this->template_system->Assign('GenerateDeleteButtonEmail', $GenerateDeleteButtonEmail);
		$this->template_system->Assign('GenerateDeleteButtonBounce', $GenerateDeleteButtonBounce);
		$this->template_system->Assign('EmailDebugLogFile', $DownloadlinkEmail, false);
		$this->template_system->Assign('bounceDebugLogFile', $Downloadlink_bounce_debug, false);
		$this->template_system->Assign('TotalAutoresponderFiles', $logsAutoresponder_number_of_logs, false);
		$this->template_system->Assign('TotalTriggerEmailFiles', $triggeremail_number_of_logs, false);
		$this->template_system->Assign('AdminUrl', $this->admin_url, false);		 
		$this->template_system->ParseTemplate('default');
		
	}
	
 
	/**
	 * Admin_Action_Delete
	 * This is called when the 'delete' option is clicked when viewing a list of log files.
	 * If we call this method directly it won't work as it checks we are doing a form post.
	 *
	 * If that check is ok, then it goes through the id's posted to make sure they are all int values
	 * These values are then given to the database to delete.
	 *
	 * If there are no logs to delete, then it creates a 'flashmessage' to display an error and also redirects the user back to the default action.
	 *
	 * If there are logs to delete then that action is performed then the user is directed back to the default action.
	 *
	 * @see Admin_Action_Default
	 * @uses FlashMessage
	 *
	 * @return Void Doesn't return anything.
	 */
	public function Admin_Action_Delete()
	{    
		if (empty($_POST) || !isset($_POST['logfiles']) || empty($_POST['logfiles'])) {
			FlashMessage(GetLang('Addon_systemlog_no_logs_chosen'), SS_FLASH_MSG_ERROR, $this->admin_url);
			return;
		}
		 
		foreach ($_POST['logfiles'] as $file_to_be_deleted) {
			if(file_exists(TEMP_DIRECTORY."/".$file_to_be_deleted)) {
				unlink(TEMP_DIRECTORY."/".$file_to_be_deleted);
			}
		}
		 
		if($_GET['action'] == 'autoresponderdebug'){
			FlashMessage(GetLang('Logs_File_Deleted'), SS_FLASH_MSG_SUCCESS, 'index.php?Page=Addons&Addon=debug&Tab=4');
	    }else {
			FlashMessage(GetLang('Logs_File_Deleted'), SS_FLASH_MSG_SUCCESS, 'index.php?Page=Addons&Addon=debug&Tab=3');
		}
	}
   
	/**
	 * Admin_Action_DeleteAll
	 * This is called when the 'delete all' option is clicked when viewing a list of log items.
	 * If we call this method directly it won't work as it checks we are doing a form post.
	 *
	 * If there are no logs to delete, then it creates a 'flashmessage' to display an error and also redirects the user back to the default action.
	 *
	 * If there are logs to delete then that action is performed then the user is directed back to the default action.
	 *
	 * @see Admin_Action_Default
	 * @uses FlashMessage
	 *
	 * @return Void Doesn't return anything.
	 */
	public function Admin_Action_DeleteAll()
	{  
        if(!(isset($_GET['Action']) && $_GET['Action'] == 'DeleteAll')){
			FlashMessage("This method cannot be accessed directly.", SS_FLASH_MSG_ERROR, 'index.php?Page=Addons&Addon=debug&Tab=3');
			return;                   
        }
		$filepath = TEMP_DIRECTORY."/"; // Make sure this path is correct 
		if($_GET['action'] == 'autoresponderdebug'){	
 		   $response = array_map('unlink', glob( "$filepath"."autoresponder_debug*") ?: []); // check folder is empty or not
		} else {
			$response = array_map('unlink', glob( "$filepath"."triggeremails_debug*") ?: []); // check folder is empty or not
		
		}	
		if(count($response) > 0 ){
			if($_GET['action'] == 'autoresponderdebug'){
				FlashMessage(GetLang('Addon_systemlog_all_logsdeleted'), SS_FLASH_MSG_SUCCESS, 'index.php?Page=Addons&Addon=debug&Tab=4');
		    }else {
				FlashMessage(GetLang('Addon_systemlog_all_logsdeleted'), SS_FLASH_MSG_SUCCESS, 'index.php?Page=Addons&Addon=debug&Tab=3');
		    }
		} else {
			if($_GET['action'] == 'autoresponderdebug'){
				FlashMessage("No file to be deleted!", SS_FLASH_MSG_SUCCESS, 'index.php?Page=Addons&Addon=debug&Tab=4');
			}else {
				FlashMessage("No file to be deleted!", SS_FLASH_MSG_SUCCESS, 'index.php?Page=Addons&Addon=debug&Tab=3');
			
			}
		}
	}

}
