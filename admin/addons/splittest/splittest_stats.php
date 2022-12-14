<?php
/**
 * This file handles displaying/processing of split test stats.
 *
 * @package Interspire_Addons
 * @subpackage Addons_splittest
 */

/**
 * If this class has been called from outside the Addons_splittest class, then die.
 * It's going to be an invalid request.
 */
if (!class_exists('Addons_splittest', false)) {
	die;
}

/**
* Include the base sendstudio functions.
*/
require_once(dirname(dirname(dirname(__FILE__))). '/functions/sendstudio_functions.php');
/**
 * Addons_splittest_Stats
 * This handles all processing/displaying etc of stats for split tests.
 *
 * @usedby Addons_splittest
 */
class Addons_splittest_Stats extends Addons_splittest
{

	/**
	 * addon_id
	 * We need to masquerade as the 'splittest' addon
	 * So we get the right template path, template url,
	 * admin url and so on from the parent Interspire_Addons system.
	 *
	 * @var String
	 *
	 * @usedby Interspire_Addons::__construct
	 */
	protected $addon_id = 'splittest';


	/**
	 * base_url
	 * The URL that points to split test stats.
	 *
	 * @var String
	 */
	protected $base_url;


	/**
	 * __construct
	 *
	 * Calls the parent constructor to set up various options including:
	 * - template path
	 * - template url
	 * - admin url
	 * - include the language file(s)
	 * etc
	 *
	 * @uses Interspire_Addons::__construct
	 *
	 * @return Void Doesn't return anything.
	 */
	public function __construct()
	{
		parent::__construct();
		$this->base_url = $this->admin_url . '&Action=Stats';
	}

	/**
	 * The control flow entry point from the stats add-on.
	 *
	 * @return String The HTML to output.
	 */
	public function Route()
	{
        $ssf = new SendStudio_Functions();
        $ssf->LoadLanguageFile('stats');
		$split_id = $this->_getGetRequest('splitid', null);
		$subAction = $this->_getGetRequest('SubAction', null);
		$GLOBALS['ShowTab'] =1; 
		if (!is_null($split_id)) {
			$stats_api = $this->GetApi('Splittest_Stats');
			$this->GetApi();
			if (in_array($subAction, ['Export', 'MultiExport'])) {
			$user = IEM::getCurrentUser();
			$lists = $user->GetLists();
			$listids = array_keys($lists);		
			$jobid = $this->_getGetRequest('jobid', null);
		  	$exportFileName = $this->_getGETRequest('exportFileName', null);  
		
			if ($subAction == 'MultiExport') {
				$jobids = $this->_getGetRequest('jobids', null);
				$splitName = 'splittests';
			} else {
				
				$jobids = [$jobid];
				$splitName = null;
			    $splitName = 'splittests';
			}
			 
			$exportOpen = $this->_getGETRequest('Export', null);
			
			switch (strtolower($exportOpen)) {
				case 'exportopen':				
					$stats = $stats_api->GetStats($listids, [], false, 0, 1, false, $jobid);					
				    $exportFileName = self::ExportStats($stats, $jobids, $splitName,'openedSplit');
					$GLOBALS['ShowTab'] = 2; 
				break;
				case 'exportbounce':				
					$stats = $stats_api->GetStats($listids, [], false, 0, 1, false, $jobid);
					$exportFileName = self::ExportStats($stats, $jobids, $splitName,'bounceSplit');					 				
					$GLOBALS['ShowTab'] = 4; 
				break;
				case 'exportunsub':
					$stats = $stats_api->GetStats($listids, [], false, 0, 1, false, $jobid);
					$exportFileName = self::ExportStats($stats, $jobids, $splitName,'unsubSplit');
					$GLOBALS['ShowTab'] = 5; 
				break;
				case 'exportclick':				 
					$stats = $stats_api->GetStats($listids, [], false, 0, 1, false, $jobid);
					$exportFileName = self::ExportStats($stats, $jobids, $splitName,'clickedSplit');
					$GLOBALS['ShowTab'] = 3; 
				break;				 
				default:
					$exportFileName = self::ExportStats($stats, $jobids, $splitName,null);
					$GLOBALS['ShowTab'] =1;
				break;
			}	
 			
			 $ssf = new SendStudio_Functions ( );
			 $http = SENDSTUDIO_APPLICATION_URL . "/admin/temp/$exportFileName"; 
			 
			 $GLOBALS['Message_Export'] =  $ssf->PrintSuccess('Export_Newsletter_Open_Statistics',$http);
			 
			} 
		  
			return $this->ShowStats($split_id);
		}
		return $this->ManageStats();
	}

	/**
	 * ManageStats
	 * Displays a list of statistics.
	 *
	 * @return String The HTML to output.
	 */
	private function ManageStats()
	{
		$user = IEM::getCurrentUser();

		$subAction = $this->_getPOSTRequest('SubAction', null);
		$jobid = $this->_getPOSTRequest('jobid', null);
		$exportFileName = $this->_getGETRequest('exportFileName', null);

		$stats_api = $this->GetApi('Splittest_Stats');
        $this->GetApi();
		$dateFormat = self::getDateFormat();

		$displayAll = true;
		$templateName = 'stats_display';

		if ($subAction == 'Delete' || $subAction == 'MultiDelete') {
			if ($subAction == 'Delete') {
				$jobids = [$jobid];
			} else {
				$jobids = $this->_getPOSTRequest('jobids', null);
			}
			// if they can't delete split tests, we won't let them delete split test stats
			$access = SplitTest_API::OwnsJobs($user->Get('userid'), $jobids);
			$access = $access && $user->HasAccess('splittest', 'delete');
			$access = $access || $user->Admin();
			if (!$access) {
				FlashMessage(GetLang('NoAccess'), SS_FLASH_MSG_ERROR, $this->base_url);
				exit;
			}
			if ($stats_api->DeleteSplittestStats($jobids)) {
				FlashMessage(GetLang('Addon_splittest_StatsDeleted_Success'), SS_FLASH_MSG_SUCCESS, $this->base_url);
			} else {
				FlashMessage(GetLang('Addon_splittest_StatsDeleted_Fail'), SS_FLASH_MSG_ERROR, $this->base_url);
			}
			exit;
		}

		// Get the listids associated with this user

		$lists = $user->GetLists();
		$listids = array_keys($lists);

		$number_of_stats = $stats_api->GetStats($listids, [], true);

		if ($number_of_stats == 0) {
			$curr_template_dir = $this->template_system->GetTemplatePath();
			$this->template_system->SetTemplatePath(SENDSTUDIO_TEMPLATE_DIRECTORY);
			$GLOBALS['Success'] = GetLang('Addon_splittest_Stats_NoneSent');
			$msg = $this->template_system->ParseTemplate('successmsg', true);
			$this->template_system->SetTemplatePath($curr_template_dir);
			$this->template_system->Assign('Addon_splittest_Stats_Empty', $msg, false);
			$this->template_system->Assign('AdminUrl', $this->admin_url, false);
			$this->template_system->Assign('SplitTest_Create_Button', $this->template_system->ParseTemplate('create_button', true));
			$this->template_system->ParseTemplate('stats_empty');
			return;
		}

		$paging = $this->SetupPaging($this->base_url, $number_of_stats);
		$page_number = $this->GetCurrentPage();
		$perpage = $this->GetPerPage();

		$sortdetails = $this->GetSortDetails();

		$stats = $stats_api->GetStats($listids, $sortdetails, false, $page_number, $perpage, $displayAll, $jobid);

		if (in_array($subAction, ['Export', 'MultiExport'])) {
			if ($subAction == 'MultiExport') {
				$jobids = $this->_getPOSTRequest('jobids', null);
				$splitName = 'splittests';
			} else {
				$jobids = [$jobid];
				$splitName = null;
			}
			if (!SplitTest_API::OwnsJobs($user->Get('userid'), $jobids) && !$user->Admin()) {
				FlashMessage(GetLang('NoAccess'), SS_FLASH_MSG_ERROR, $this->base_url);
				return;
			}
			 $exportFileName = self::ExportStats($stats, $jobids, $splitName,null);
			FlashMessage(sprintf(GetLang('Addon_splittest_Stats_DownloadExportedFile'), $exportFileName), SS_FLASH_MSG_SUCCESS, $this->base_url);
		}

		foreach ($stats as $stats_id => $stats_details) {
			$stats[$stats_id]['splitname'] = htmlspecialchars($stats_details['splitname'], ENT_QUOTES, SENDSTUDIO_CHARSET);
			$stats[$stats_id]['campaign_names'] = htmlspecialchars($stats_details['campaign_names'], ENT_QUOTES, SENDSTUDIO_CHARSET);
		}

		if ($exportFileName != null) {
			$this->template_system->Assign('exportFileName', $exportFileName);
		}
  
		$this->template_system->Assign('AdminUrl', $this->admin_url, false);
		$this->template_system->Assign('ApplicationUrl', $this->application_url, false);
		$this->template_system->Assign('Paging', $paging, false);
		$this->template_system->Assign('DateFormat', $dateFormat);
		$this->template_system->Assign('Statistics', $stats);
		$this->template_system->Assign('FlashMessages', GetFlashMessages());
		$this->template_system->ParseTemplate($templateName);
	}

	/**
	 * ShowStats
	 * Displays statistics for a split test.
	 *
	 * @param Int $split_id The ID of a split test.
	 *
	 * @return String The HTML to output.
	 */
	private function ShowStats($split_id)
	{
		$user = IEM::getCurrentUser();
		$template_name = 'splittest_campaign_stats';
		$stats_api = $this->GetApi('Splittest_Stats');
        $this->GetApi();

        $date_format = self::getDateFormat();

		$jobid = $this->_getGETRequest('jobid', null);

		if (!SplitTest_API::OwnsSplitTests($user->Get('userid'), $split_id) && !$user->Admin()) {
			FlashMessage(GetLang('NoAccess'), SS_FLASH_MSG_ERROR, $this->base_url);
			return;
		}

		$stats = $stats_api->GetStats([$split_id], [], false, 0, 1, false, $jobid);
		$stats = $stats[$jobid];

		$stats['splitname'] = htmlspecialchars($stats['splitname'], ENT_QUOTES, SENDSTUDIO_CHARSET);
		$stats['campaign_names'] = htmlspecialchars($stats['campaign_names'], ENT_QUOTES, SENDSTUDIO_CHARSET);
		$stats['list_names'] = htmlspecialchars($stats['list_names'], ENT_QUOTES, SENDSTUDIO_CHARSET);

        $ssf = new SendStudio_Functions();
        $stats['finishtimeprint'] = $ssf->PrintTime($stats['finishtime'], true);
        $stats['starttimeprint'] = $ssf->PrintTime($stats['starttime'], true);


        $charts = $this->generateCharts($stats['splitname'], $stats['campaigns']);
		foreach ($charts as $type=>$data) {
			$stats[$type] = $data;
		}
		$this->template_system->Assign('ShowTab', $GLOBALS['ShowTab']);
		$this->template_system->Assign('AdminUrl', $this->admin_url, false);
		$this->template_system->Assign('ApplicationUrl', $this->application_url, false);
		$this->template_system->Assign('DateFormat', $date_format);
		$this->template_system->Assign('statsDetails', $stats);
		$this->template_system->Assign('FlashMessages', GetFlashMessages());
		$this->template_system->ParseTemplate($template_name);
	}

	/**
	 * generateCharts
	 * Generates charts for summary, open, click, bounce and unsubscribe stats.
	 *
	 * @param String $splitname The name of the split test.
	 * @param String $campaigns Campaign data for the split test.
	 * @param String $subaction Can be 'print' to insert images instead of flash charts.
	 *
	 * @return Array The chart data to output.
	 */
	protected function generateCharts($splitname, $campaigns, $subaction=null)
	{
		require_once(SENDSTUDIO_LIB_DIRECTORY . '/amcharts/amcharts.php');
		$stats_api = $this->GetApi('Splittest_Stats');

		$statsChartUrl =  SENDSTUDIO_APPLICATION_URL . '/admin/stats_chart.php?graph=custom_bar&';

		$summaryDataURL = $statsChartUrl . $stats_api->barChartSummaryDataURL($campaigns, 'Opens,Clicks,Bounces,Unsubscribes');

		$charts = [];

		$charts['summary_chart'] = self::InsertChartImage('SplittestSummaryChart', $summaryDataURL, ['graph_title' => sprintf(GetLang('Addon_splittest_Stats_Summary'), $splitname)], $subaction);
		// Splittest Sumamry Open Rate Chart

		$openrateDataURL = $statsChartUrl . $stats_api->barChartDataURL($campaigns, 'emailopens_unique', true, true, true);
		$charts['openrate_chart'] = self::InsertChartImage('SplittestOpenChart', $openrateDataURL, ['graph_title' => sprintf(GetLang('Addon_splittest_Stats_Total_UniqueOpens'), $splitname)], $subaction);

		// Splittest Sumamry Link Clicks Chart
		$linkclicksDataURL = $statsChartUrl . $stats_api->barChartDataURL($campaigns, 'linkclicks', true, true, true);
		$charts['clickrate_chart'] = self::InsertChartImage('SplittestLinkChart', $linkclicksDataURL, ['graph_title' => sprintf(GetLang('Addon_splittest_Stats_Total_LinkClicks'), $splitname)], $subaction);

		// Splittest Bounce Count Chart
		$bounceDataURL = $statsChartUrl . $stats_api->barChartDataURL($campaigns, 'bouncecount_total', true, true);
		$charts['bouncerate_chart'] = self::InsertChartImage('SplittestBounceChart', $bounceDataURL, ['graph_title' => sprintf(GetLang('Addon_splittest_Stats_Total_Bounces'), $splitname)], $subaction);

		// Splittest Unsubscribe Count Chart
		$unsubscribeDataURL = $statsChartUrl . $stats_api->barChartDataURL($campaigns, 'unsubscribecount', true, true);
		$charts['unsubscribes_chart'] = self::InsertChartImage('SplittestUnsubscribeChart', $unsubscribeDataURL, ['graph_title' => sprintf(GetLang('Addon_splittest_Stats_Total_Unsubscribes'), $splitname)], $subaction);

		return $charts;
	}

	/**
	 * InsertChartImage
	 * Sets the variables to display a statistics chart.
	 *
	 * @param String $chartname The variable name for the chart.
	 * @param String $data_url The URL the chart should get data from.
	 * @param Array $settings An array of settings for the chart.
	 *
	 * @return String Returns nothing, sets the variables for displaying the chart.
	 */
	private static function InsertChartImage($chartname, $data_url, $settings=null, $subaction)
	{
		// If this page is for print we'll return an image
		if ($subaction == 'print' || $subaction == 'preview') {
			$params = [];
			if (is_array($settings)) {
				foreach ($settings as $key => $val) {
					$params[] = urlencode($key) . "=" . urlencode($val);
				}
			}
			if (self::hasNoData($data_url)) {
				return '';
			}
			$params = implode('&amp;', $params);

            $settingsApi = new Settings_API();
			if ($settingsApi->GDEnabled()) {
				return '<img src="' . $data_url . ( $params ? '&amp;' . $params : '') . '&amp;GetAsImg=1" style="display: block;" />';
			} else {
				return '<p>(' . GetLang('GD_Not_Enabled') . ')</p>';
			}
		} else {
			return AmCharts::InsertColumnChart($data_url,  $settings['graph_title']);
		}
	}

	/**
	 * hasNoData
	 * Checks whether the data to be fed to a graph is all zero or not.
	 *
	 * @param String $data_url The data URL passed to print_stats.php.
	 *
	 * @return Boolean True if the URL has no data, otherwise false.
	 */
	private static function hasNoData($data_url)
	{
		$data = substr($data_url, strpos($data_url, '&data='));
		// should look like &data=Click+my+links:0:0:0:0,Link+Click+Test:1:1:0:0
		if (preg_match_all('/\:\d+/', $data_url, $matches)) {
			foreach ($matches[0] as $match) {
				if ($match != ':0') {
					return false;
				}
			}
		}
		return true;
	}

	/**
	 * ExportStats
	 * For a given Split Test Campaign save the data to a file on the filesystem.
	 *
	 * @param Array $stats Split Test Campaign send statistic for a single campaign send as derived from this GetStats API method.
	 *
	 * @return String $exportFileName The name of the file written to disk.
	 */
	private static function ExportStats($stats, $jobids, $splitName=null,$splitStatus)
	{  
		$dateFormat = self::getDateFormat(); 
		// if no split campaign name has been passed use the name of the first one we find
		$filename = ($splitName == null) ? $stats[$jobids[0]]['splitname'] : $splitName;

		$dateStr = date('Y-m-d_His');
		$temp = str_replace(" ", "_", strtolower($filename));
		$filenameSafe = '';
		for ($i=0; $i<strlen($temp); $i++) {
			if (preg_match('([0-9]|[a-z]|_)', $temp[$i])) {
				$filenameSafe = $filenameSafe . $temp[$i];
			}
		}
		$exportFileName = $filenameSafe . '__' . $dateStr . '.csv';
		$path = TEMP_DIRECTORY . '/';
		switch (strtolower($splitStatus)) {
				case 'openedsplit':				 
					$headers = implode(',', [
					'Email Campaigns',  'Opens', 'Unique Opens', 'Unique Opens (%)', 'Recipients','Recipients (%)','Send Size']
					);
					$exportFileName= "opened_".$exportFileName;
				break;
				case 'clickedsplit':
					$headers = implode(',', [
					'Email Campaigns',  'Unique Clicks', 'Unique Clicks (%)', 'Clicks', 'Recipients','Recipients (%)','Send Size']
					);
					$exportFileName= "clicked_".$exportFileName;
				break;
				case 'unsubsplit':
					$headers = implode(',', [
					'Newsletter Name',  'Unsubscribes', 'Total Unsubscribes (%)', 'Recipients','Recipients (%)','Send Size']
					);
					$exportFileName= "unsub_".$exportFileName;
				break;
				case 'bouncesplit':				 
					 $headers = implode(',', [
					'Newsletter Name',  'Soft', 'Hard', 'Unknown', 'Bounces (%)','Recipients','Recipients (%)','Send Size']
					);
					$exportFileName= "bounce_".$exportFileName;
					
				break;
				default:
					$headers = implode(',', [
					'Newsletter Name', 'Split Test Name', 'Split Type', 'Start Sending', 'Finished Sending', 'Recipients', 'Total Opened', 'Unique Opened', '% Opened', 'Recipients who Clicked Links','% Recipients who Clicked','Hard Bounced','Soft Bounced','Total Bounced','% Bounced','Unsubscribed','% Unsubscribed']
					);
					
				break;
			}        
		
			$file = $path . $exportFileName;

		// we shouldn't be overwriting anything becuase there is a timestamp in the filename - but just in case so as we don't create a fatal error
		if (file_exists($file)) {
			unlink($file);
		}
		
		$f = fopen($file, 'w');
		fwrite($f, $headers);
		fwrite($f, "\n");
		$sendStudio_Functions = new SendStudio_Functions;		 
		foreach ($stats as $job_id=>$statistics) {					 		
			if (in_array($job_id, $jobids, false)) {				
				switch (strtolower($splitStatus)) {
					case 'openedsplit':
						foreach ($statistics['campaigns'] as $id=>$data) {								    
							fwrite($f, $data['campaign_name'] . ',');
							fwrite($f, $data['stats_newsletters']['emailopens'] . ',');
							fwrite($f, $data['stats_newsletters']['emailopens_unique'] . ',');
							fwrite($f, $data['stats_newsletters']['percent_emailopens_unique'] . ',');
							fwrite($f, $data['stats_newsletters']['recipients'] . ',');
							fwrite($f, $data['stats_newsletters']['final_percent_emailopens'] . ',');
							fwrite($f, $data['stats_newsletters']['final_total_recipient_count'] . ',');
							
							fwrite($f, "\n");
						}
					break;
					case 'clickedsplit':
						foreach ($statistics['campaigns'] as $id=>$data) {								    
							fwrite($f, $data['campaign_name'] . ',');					 
							fwrite($f, $data['stats_newsletters']['linkclicks_unique'] . ',');
							fwrite($f, $data['stats_newsletters']['percent_linkclicks_unique'] . ',');
							fwrite($f, $data['stats_newsletters']['linkclicks'] . ',');
							fwrite($f, $data['stats_newsletters']['recipients'] . ',');
							fwrite($f, $data['stats_newsletters']['final_percent_linkclicks_unique'] . ',');					 
							fwrite($f, $data['stats_newsletters']['final_total_recipient_count'] . ',');							
							fwrite($f, "\n");
						}
					break;
					case 'unsubsplit':
						foreach ($statistics['campaigns'] as $id=>$data) {								    
							fwrite($f, $data['campaign_name'] . ',');					 
							fwrite($f, $data['stats_newsletters']['unsubscribecount'] . ',');
							fwrite($f, $data['stats_newsletters']['percent_unsubscribecount'] . ',');
							fwrite($f, $data['stats_newsletters']['recipients'] . ',');
							fwrite($f, $data['stats_newsletters']['final_percent_unsubscribecount'] . ',');
							fwrite($f, $data['stats_newsletters']['final_total_recipient_count'] . ',');								
							fwrite($f, "\n");
						}
					break;
					case 'bouncesplit':
					
						foreach ($statistics['campaigns'] as $id=>$data) {								    
							fwrite($f, $data['campaign_name'] . ',');					 
							fwrite($f, $data['stats_newsletters']['bouncecount_soft'] . ',');
							fwrite($f, $data['stats_newsletters']['bouncecount_hard'] . ',');
							fwrite($f, $data['stats_newsletters']['bouncecount_unknown'] . ',');
							fwrite($f, $data['stats_newsletters']['percent_bouncecount_total'] . ',');
							fwrite($f, $data['stats_newsletters']['recipients'] . ',');
							fwrite($f, $data['stats_newsletters']['final_percent_bouncecount_total'] . ','); 
							fwrite($f, $data['stats_newsletters']['final_total_recipient_count'] . ','); 
							fwrite($f, "\n");
						}
					break;
					default:
					foreach ($statistics['campaigns'] as $id=>$data) {								    
						fwrite($f, $data['campaign_name'] . ',');
						fwrite($f, $statistics['splitname'] . ',');
						fwrite($f, $statistics['splittype'] . ',');
						fwrite($f, $sendStudio_Functions->PrintDate($statistics['starttime'], $dateFormat) . ',');
						fwrite($f, $sendStudio_Functions->PrintDate($statistics['finishtime'], $dateFormat) . ',');
						fwrite($f, $data['stats_newsletters']['recipients'] . ',');
						fwrite($f, $data['stats_newsletters']['emailopens'] . ',');
						fwrite($f, $data['stats_newsletters']['emailopens_unique'] . ',');
						fwrite($f, $data['stats_newsletters']['percent_emailopens_unique'] . ',');
						fwrite($f, $data['stats_newsletters']['linkclicks_unique'] . ',');
						fwrite($f, $data['stats_newsletters']['percent_linkclicks_unique'] . ',');
						fwrite($f, $data['stats_newsletters']['bouncecount_hard'] . ',');
						fwrite($f, $data['stats_newsletters']['bouncecount_soft'] . ',');
						fwrite($f, $data['stats_newsletters']['bouncecount_total'] . ',');
						fwrite($f, $data['stats_newsletters']['percent_bouncecount_total'] . ',');
						fwrite($f, $data['stats_newsletters']['unsubscribecount'] . ',');
						fwrite($f, $data['stats_newsletters']['percent_unsubscribecount']);
						fwrite($f, "\n");
					}	
					break;
				}
				 
			}
		}
		fclose($f);
 
		return  $exportFileName;
	}
}
