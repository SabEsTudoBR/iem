<?php
/**
 * This file does the charting for the statistics areas.
 *
 *
 * TODO: This file SHOULD NOT BE CALLED DIRECTLY from the request.
 * All request should be directed to index.php
 *
 * @version     $Id: stats_chart.php,v 1.40 2008/02/13 22:25:51 tye Exp $
 * @author Chris <chris@interspire.com>
 *
 * @package SendStudio
 * @subpackage SendStudio_Functions
 */

// Make sure that the IEM controller does NOT redirect request.
if (!defined('IEM_NO_CONTROLLER')) {
	define('IEM_NO_CONTROLLER', true);
}

/**
* Since we are calling this file differently, we need to include init ourselves and then include the base sendstudio functions.
*/
require_once(dirname(__FILE__) . '/../index.php');
require_once(dirname(__FILE__) . '/sendstudio_functions.php');

/**
* This file does the charting for the main index page.
* The class is called in this file (chart wouldn't work by passing it like other sendstudio pages).
* Doing it this way means easy access to all regular sendstudio functions and restrictions (eg userid's etc).
*
* @package SendStudio
* @subpackage SendStudio_Functions
*/
class StatsChart extends SendStudio_Functions
{

	/**
	* Contains an array of chart information that is used to display the chart.
	* This is shared by a few functions so it's easier to reference a class variable rather than a global variable or passing it around everywhere.
	*
	* @see Process
	* @see SetupChartDates
	* @see SetupChart_Subscribers
	* @see SetupChart
	*
	* @var Array
	*/
	var $chart = array();

	/**
	* More chart information.
	* This holds descriptions and so on for the chart.
	*
	* @see Process
	* @see SetupChartDates
	* @see SetupChart_Subscribers
	* @see SetupChart
	*/
	var $chart_details = array('bold' => false);

	/**
	* A reference to the stats api. This is used for easy access.
	*
	* @see SetupChartDates
	* @see SetupChart_Subscribers
	* @see SetupChart
	*
	* @var Object
	*/
	var $stats_api = null;

	/**
	* Constructor
	* Sets up the database connection.
	* Checks you are logged in before anything else.
	* If you are not logged in, you are kicked back to the login screen.
	*
	* @see IEM::getDatabase()
	*
	* @return Void Doesn't return anything
	*/
	function __construct()
	{ 
		if (!IEM::getCurrentUser()) {
			if (defined('SENDSTUDIO_APPLICATION_URL') && SENDSTUDIO_APPLICATION_URL !== false) {
				header('Location: ' . SENDSTUDIO_APPLICATION_URL . '/admin/index.php');
			} else {
				header('Location: ../index.php');
			}
			exit;
		}

		$db = IEM::getDatabase();
		$this->Db = &$db;
		$this->LoadLanguageFile('Stats');

		$stats_api = $this->GetApi('Stats');
		$this->stats_api = &$stats_api;

		$this->chart['chart_data'] = array();

		$this->chart['chart_data'][0][0] = '';
		$this->chart['chart_data'][1] = array();
		$this->chart['chart_data'][1][0] = 'Totals';

		$this->chart['chart_value_text'] = array();
		$this->chart['chart_value_text'][1] = array();

		$this->chart['chart_type'] = 'column';

	}

	/**
	* Process
	* Does all of the work. Includes the chart, works out the data, prints it out.
	* It works out the type of calendar you're viewing (monthly, daily, weekly etc) and sets appropriate variables.
	* The stats api works out what type of calendar it is. It is done there so the stats file can make use of it as well for displaying date/time information.
	*
	* @see calendar_type
	* @see daily_stats_type
	* @see stats_type
	* @see chart_details
	* @see SetupChartDates
	* @see SetupChart_Subscribers
	* @see SetupChart
	* @see Stats_API::GetSubscriberGraphData
	* @see Stats_API::GetGraphData
	* @see Stats_API::CalculateStatsType
	* @see chart
	*
	* @return Void Prints out the chard, doesn't return anything.
	*/
	function Process()
	{
		$user = IEM::getCurrentUser();

		$this->LoadLanguageFile('Stats');

        $idx = !empty($_GET['i']) && $_GET['i'] == 1;
		$this->stats_api->CalculateStatsType($idx);

		$calendarDates = $user->GetSettings('CalendarDates');
        $statid = !empty($_GET['statid']) ? (int)$_GET['statid'] : 0;
        $statid = IEM::sessionGet('ListStatistics', $statid);
        $restrictions = '';
        $chartTitle = '';
        $chartArea = !empty($_GET['Area']) ? strtolower($_GET['Area']) : false;
		switch ($chartArea) {
			case 'autoresponder':
			case 'list':
			case 'subscriberdomains':
                $chartArea = ucwords($chartArea);
			break;

			default:
                $chartArea = 'Newsletter';
		}

        $chartType = !empty($_GET['graph']) ? strtolower($_GET['graph']) : false;
        switch ($chartType) {
			case 'bouncechart':
				$restrictions = isset($calendarDates['bounces']) ? $calendarDates['bounces'] : '';
				$this->chart['chart_data'][1][0] = GetLang('Stats_TotalBouncedEmails');

				$this->chart['chart_type'] = 'column';
				$this->chart['chart_data'][1][0] = GetLang('SoftBounces');
				$this->chart['chart_data'][2][0] = GetLang('HardBounces');
			break;

			case 'userchart':
                $restrictions = IEM::ifsetor($calendarDates['usersummary'], '');
				$this->chart['chart_data'][1][0] = GetLang('Stats_TotalEmailsSent');
			break;

			case 'openchart':
				$restrictions = IEM::ifsetor($calendarDates['opens'], '');
				$this->chart['chart_data'][1][0] = GetLang('Stats_TotalOpens');
			break;

			case 'forwardschart':
				$restrictions = IEM::ifsetor($calendarDates['forwards'], '');
				$this->chart['chart_data'][1][0] = GetLang('Stats_TotalForwards');
			break;

			case 'unsubscribechart':
				$restrictions = IEM::ifsetor($calendarDates['unsubscribes'], '');
				$this->chart['chart_data'][1][0] = GetLang('Stats_TotalUnsubscribes');
			break;

			case 'linkschart':
				$restrictions = IEM::ifsetor($calendarDates['clicks'], '');
				$this->chart['chart_data'][1][0] = GetLang('Stats_TotalClicks');
			break;

			case 'subscribersummary':
				$restrictions = IEM::ifsetor($calendarDates['subscribers'], '');
				$this->chart['chart_type'] = 'column';
				$this->chart['chart_data'][1][0] = GetLang('Unconfirms');
				$this->chart['chart_data'][2][0] = GetLang('Confirms');
				$this->chart['chart_data'][3][0] = GetLang('Unsubscribes');
				$this->chart['chart_data'][4][0] = GetLang('Bounces');
				$this->chart['chart_data'][5][0] = GetLang('Forwards');
			break;

			// use 'custom_pie' chart type to send data to the plotting software and produce a pie chart
			case 'custom_pie':
				// expects a data string in the format "john:123,paul:101,george:153,ringo:139"
				$chartType = false;
				$this->chart['chart_type'] = 'pie';
				$data = explode(',', $this->_getGETRequest('data', ''));

				for ($i = 0; $i < count($data); $i++ ) {
					$values = explode(':', $data[$i]);
					$this->chart['chart_data'][0][$i+1] = $values[0];
					$this->chart['chart_data'][1][$i+1] = $values[1];
				}

				$this->chart['chart_value_text'][1][1] = 7;
				$this->chart['chart_value_text'][1][2] = 8;
				$this->chart['chart_value_text'][1][3] = 9;

			break;

			// use 'custom_bar' chart type to send data to the plotting software and produce a bar chart
			// expects following url parameters :
			// data=john:56:23:9,paul:32:9:1,george:98:43:12 & xLabels=albums,eps,singles
			case 'custom_bar':
				$xLabels = explode(',', $this->_getGETRequest('xLabels', ''));
				$data = explode(',', $this->_getGETRequest('data', ''));
                $chartType = false;
				$this->chart['chart_type'] = 'column';

				$chartData = [];
				$xAxisLabels = [];
				$xAxisLabels[0] = '';

				// Add the X Axis Elements
                $xLabelsCount = count($xLabels);
				for ($i = 0; $i < $xLabelsCount; $i++ ) {
					$xAxisLabels[] = $xLabels[$i];
				}
                $chartData[] = $xAxisLabels;

				// Add the data and legend information
				// the first item in each xData array is the legend text the remainder are data value elements
                $dataCount = count($data);
				for ($i = 0; $i < $dataCount; $i++ ) {
					$xData = [];
					$values = explode(':', $data[$i]);
                    $valuesCount = count($values);
					for ($j = 0; $j < $valuesCount; $j++) {
						$xData[] = $values[$j];
					}
                    $chartData[] = $xData;
				}

				$this->chart['chart_data'] = $chartData;
				$this->chart['axis_category'] = array('skip' => 0);

			break;

			default:
				// this is for the "summary" pages where it breaks down opens/unopened/bounces
				// the summary pages are all pie charts.
                $chartType = false;

				$this->chart['chart_type'] = 'pie';

				if (strtolower($chartArea) === 'subscriberdomains') {
                    $graphPos = 1;
                    $chartTitle = GetLang('ListStatistics_Snapshot_PerDomain');
					$domainDetails = IEM::sessionGet('SubscriberDomains');
					$total = empty($domainDetails) ? 0 : array_sum($domainDetails);
					if ($total == 0) {
						$this->chart['chart_type'] = 'column';
					} else {
						foreach ($domainDetails as $name => $count) {
                            $percent = $total > 0 ? $this->FormatNumber(($count / $total) * 100) : 0;
							$this->chart['chart_data'][0][$graphPos] = sprintf(GetLang('Summary_Domain_Name'), $name, $percent);
							$this->chart['chart_data'][1][$graphPos] = $count;
							$this->chart['chart_value_text'][1][$graphPos] = $this->FormatNumber($count);
                            $graphPos++;
						}
					}

					break;
				}

				$opens = $unopened = $bounces = 0;

				if (isset($_GET['Opens'])) {
					$opens = (int)$_GET['Opens'];
				}

				if (isset($_GET['Unopened'])) {
					$unopened = (int)$_GET['Unopened'];
				}

				if (isset($_GET['Bounced'])) {
					$bounces = (int)$_GET['Bounced'];
				}

				if (isset($_GET['Heading']) && $_GET['Heading'] == 'User') {
                    $chartTitle = GetLang('User_Summary_Graph');
				} else {
                    $chartTitle = GetLang($chartArea . '_Summary_Graph');
				}

				if ($opens == 0 && $bounces == 0 && $unopened == 0) {
					$unopened = 1;
				}

				$total = $opens + $unopened + $bounces;

                $opensPercent = $total > 0 ? $this->FormatNumber(($opens / $total) * 100) : 0;
                $unopenedPercent = $total > 0 ? $this->FormatNumber(($unopened / $total) * 100) : 0;
                $bouncesPercent = $total > 0 ? $this->FormatNumber(($bounces / $total) * 100) : 0;

				$this->chart['chart_data'][0][1] = sprintf(GetLang('Summary_Graph_Opened'), $opensPercent);
				$this->chart['chart_data'][0][2] = sprintf(GetLang('Summary_Graph_Unopened'), $unopenedPercent);
				$this->chart['chart_data'][0][3] = sprintf(GetLang('Summary_Graph_Bounced'), $bouncesPercent);

				$this->chart['chart_data'][1][1] = $opens;
				$this->chart['chart_data'][1][2] = $unopened;
				$this->chart['chart_data'][1][3] = $bounces;

				$this->chart['chart_value_text'][1][1] = $this->FormatNumber($opens);
				$this->chart['chart_value_text'][1][2] = $this->FormatNumber($unopened);
				$this->chart['chart_value_text'][1][3] = $this->FormatNumber($bounces);

		}

		if ($chartType) {
			$chartTitle = GetLang($chartArea . '_Summary_Graph_' . $chartType);

			$this->SetupChartDates($chartType);
			$listid = 0;
			if (isset($_GET['Area'], $_GET['statid']) && strtolower($_GET['Area']) === 'list') {
				$listid = (int)$_GET['statid'];
			}
			if (isset($_GET['List'])) {
				$listid = (int)$_GET['List'];
			}

			switch ($chartType) {
				case 'bouncechart':
					$data = $this->stats_api->GetBounceGraphData($this->stats_api->stats_type, $restrictions, $statid, $listid);
					$this->SetupChart_BounceSummary($data);
				break;

				case 'subscribersummary':
					if (isset($_GET['i']) && $_GET['i'] == 1) {
						$data = IEM::sessionGet('IndexSubscriberGraphData');
					} else {
						$data = IEM::sessionGet('SubscriberGraphData');
					}

					$this->SetupChart_SubscriberSummary($data);
				break;

				case 'userchart':
					$data = IEM::sessionGet('userchart_data');
					$this->SetupChart($data);
				break;

				default:
					$data = $this->stats_api->GetGraphData($statid, $this->stats_api->stats_type, $restrictions, $chartType, $listid);
					$this->SetupChart($data);
				break;
			}
		}

		// Prints the chart as a gif or png
		if (isset($_GET['GetAsImg'])) {
			// graphpite causes lots of notices and warning, so turn those off
			error_reporting(E_PARSE | E_ERROR);
 
			// Turn off error handling, it breaks the chart generation
			set_error_handler('ord');

			require_once(SENDSTUDIO_LIB_DIRECTORY . '/amcharts/graphpite.php');
			// Width & height are fixed at 650x300 for printing
			$chart = new Chart_Image(650, 300, $chartTitle);
			$chart->Generate($this->chart);
			$chart->PrintImage();

			// Restore error handling
			error_reporting(E_ALL);
			restore_error_handler();
		} else {
            $data = [];

            switch ($this->chart['chart_type']) {
                case 'pie':
                    $radius = 5;
                    foreach ($this->chart['chart_data'][0] as $key => $val) {
                        if ($val != '') {
                            $data[] = [
                                'title' => $val,
                                'labelRadius' => $radius,
                                'value' => $this->chart['chart_data'][1][$key]
                            ];
                        }
                        $radius += 5;
                    }
                break;

                case 'column':
                    $labels = array_shift($this->chart['chart_data']);
                    $data['valueFields'] = [];
                    $data['chartData'] = [];
                    foreach ($labels as $key => $label) {
                        if (empty($label)) {
                            continue;
                        }
                        $data['chartData'][$key]['category'] = $label;
                    }

                    foreach ($this->chart['chart_data'] as $key => $graph) {
                        $graph = array_reverse($graph, true);
                        $title = array_pop($graph);
                        if (!in_array($title, $data['valueFields'], false)) {
                            $data['valueFields'][] = $title;
                        }
                        $graph = array_reverse($graph, true);
                        foreach ($graph as $categoryId => $value) {
                            $data['chartData'][$categoryId][$title] = $value;
                        }
                    }
                break;
            }

            header('Content-Type: application/json');
            echo json_encode($data);
		}
	}

	/**
	* SetupChartDates
	* This sets default values for the charts, works out 'skip' criteria and puts the basic chart information together, ready for SetupChart_Subscribers and SetupChart to use.
	*
	* @see calendar_type
	* @see chart
	* @see SetupChart_SubscriberSummary
	* @see SetupChart
	*
	* @return Void Doesn't return anything, updates data in the chart class variable.
	*/
	function SetupChartDates($chart_type=false)
	{
		$num_areas = 1;

		if ($chart_type == 'subscribersummary') {
			$num_areas = 5;
		}

		if ($chart_type == 'bouncechart') {
			$num_areas = 2;
		}

		$now = getdate();

		switch ($this->stats_api->calendar_type) {
			case 'last24hours':
				$this->chart['axis_category']['skip'] = 2;
				/**
				* Here we go backwards so "now" is on the far right hand side
				* and yesterday is on the left
				*/
				$hours_now = $now['hours'];

				$server_time = AdjustTime(array($hours_now, 1, 1, 1, 1, $now['year']), true, null, true);

				$this->chart['chart_data'][0][24] = $this->PrintDate($server_time, GetLang('Daily_Time_Display'));

				for ($i = 1; $i <= $num_areas; $i++) {
					$this->chart['chart_data'][$i][24] = 0;
					$this->chart['chart_value_text'][$i][24] = 0;
				}

				$i = 23;
				while ($i > 0) {
					$hours_now--;

					$server_time = AdjustTime(array($hours_now, 1, 1, 1, 1, $now['year']), true, null, true);
					$this->chart['chart_data'][0][$i] = $this->PrintDate($server_time, GetLang('Daily_Time_Display'));

					for ($x = 1; $x <= $num_areas; $x++) {
						$this->chart['chart_data'][$x][$i] = 0;
						$this->chart['chart_value_text'][$x][$i] = 0;
					}

					$i--;
				}
			break;

			case 'today':
			case 'yesterday':
				$this->chart['axis_category']['skip'] = 2;

				for ($i = 0; $i < 24; $i++) {
					$server_time = AdjustTime(array($i, 1, 1, 1, 1, $now['year']), true);
					$this->chart['chart_data'][0][$i + 1] = $this->PrintDate($server_time, GetLang('Daily_Time_Display'));

					for ($x = 1; $x <= $num_areas; $x++) {
						$this->chart['chart_data'][$x][$i + 1] = 0;
						$this->chart['chart_value_text'][$x][$i + 1] = 0;
					}
				}
			break;

			case 'last7days':
				$this->chart['axis_category']['skip'] = 0;

				$today = $now['0'];

				$this->chart['chart_data'][0][7] = GetLang($this->days_of_week[$now['wday']]);

				for ($t = 1; $t <= $num_areas; $t++) {
					$this->chart['chart_data'][$t][7] = 0;
					$this->chart['chart_value_text'][$t][7] = 0;
				}

				$date = $today;
				$i = 6;
				while ($i > 0) {
					$date = $date - 86400; // take off one day each time.
					$datenow = getdate($date);
					$this->chart['chart_data'][0][$i] = GetLang($this->days_of_week[$datenow['wday']]);

					for ($x = 1; $x <= $num_areas; $x++) {
						$this->chart['chart_data'][$x][$i] = 0;
						$this->chart['chart_value_text'][$x][$i] = 0;
					}
					$i--;
				}
			break;

			case 'last30days':
				$this->chart['axis_category']['skip'] = 1;

				$today = $now['0'];
				$this->chart['chart_data'][0][30] = $this->PrintDate($today, GetLang('DOM_Number_Display'));

				for ($x = 1; $x <= $num_areas; $x++) {
					$this->chart['chart_data'][$x][30] = 0;
					$this->chart['chart_value_text'][$x][30] = 0;
				}

				$date = $today;
				$i = 29;
				while ($i > 0) {
					$date = $date - 86400; // take off one day each time.
					$this->chart['chart_data'][0][$i] = $this->PrintDate($date, GetLang('DOM_Number_Display'));

					for ($x = 1; $x <= $num_areas; $x++) {
						$this->chart['chart_data'][$x][$i] = 0;
						$this->chart['chart_value_text'][$x][$i] = 0;
					}

					$i--;
				}
			break;

			case 'thismonth':
			case 'lastmonth':
				if ($this->stats_api->calendar_type == 'thismonth') {
					$month = $now['mon'];
				} else {
					$month = $now['mon'] - 1;
				}

				$timestamp = AdjustTime(array(1, 1, 1, $month, 1, $now['year']), true);

				$days_of_month = date('t', $timestamp);

				for ($i = 1; $i <= $days_of_month; $i++) {
					$this->chart['chart_data'][0][$i] = $this->PrintDate($timestamp, GetLang('DOM_Number_Display'));

					for ($x = 1; $x <= $num_areas; $x++) {
						$this->chart['chart_data'][$x][$i] = 0;
						$this->chart['chart_value_text'][$x][$i] = 0;
					}

					$timestamp += 86400;
				}
			break;

			default:
				$this->chart['axis_category']['skip'] = 0;
				if ($this->stats_api->stats_type != 'monthly' && $this->stats_api->stats_type != 'last7days') {
					$month = $now['mon'];
					for ($i = 1; $i <= 12; $i++) {
						$this->chart['chart_data'][0][$i] = GetLang($this->Months[$month]);
						$month--;
						if ($month == 0) {
							$month = 12;
						}

						for ($x = 1; $x <= $num_areas; $x++) {
							$this->chart['chart_data'][$x][$i] = 0;
							$this->chart['chart_value_text'][$x][$i] = 0;
						}
					}
				}

				$user = IEM::getCurrentUser();

				$calendar_settings = $user->GetSettings('Calendar');

				if ($this->stats_api->stats_type == 'monthly') {
					$month = $calendar_settings['From']['Mth'];
					$year = $calendar_settings['From']['Yr'];
					$timestamp = AdjustTime(array(1, 1, 1, $month, 1, $year), true);

					$days_of_month = date('t', $timestamp);

					for ($i = 1; $i <= $days_of_month; $i++) {
						$this->chart['chart_data'][0][$i] = $this->PrintDate($timestamp, GetLang('DOM_Number_Display'));

						for ($x = 1; $x <= $num_areas; $x++) {
							$this->chart['chart_data'][$x][$i] = 0;
							$this->chart['chart_value_text'][$x][$i] = 0;
						}

						$timestamp += 86400;
					}
				}

				if ($this->stats_api->stats_type == 'last7days') {
					$this->chart['axis_category']['skip'] = 0;

					$today = $now['0'];

					$this->chart['chart_data'][0][7] = GetLang($this->days_of_week[$now['wday']]);

					for ($t = 1; $t <= $num_areas; $t++) {
						$this->chart['chart_data'][$t][7] = 0;
						$this->chart['chart_value_text'][$t][7] = 0;
					}

					$date = $today;
					$i = 6;
					while ($i > 0) {
						$date = $date - 86400; // take off one day each time.
						$datenow = getdate($date);
						$this->chart['chart_data'][0][$i] = GetLang($this->days_of_week[$datenow['wday']]);

						for ($x = 1; $x <= $num_areas; $x++) {
							$this->chart['chart_data'][$x][$i] = 0;
							$this->chart['chart_value_text'][$x][$i] = 0;
						}
						$i--;
					}
				}
			break;
		}
	}

	/**
	* SetupChart_SubscriberSummary
	* This goes through the 4 areas (subscribes, unsubscribes, bounces and forwards) and fills in the chart data based on the data that is passed in.
	* The data is fetched by the Process function based on different criteria and passed here.
	*
	* @var Array $data The array of data to fill the graph up with. This will be a multidimensional array containing 'subscribes', 'unsubscribes', 'bounces' and 'forwards' (anything else is ignored).
	*
	* @see Process
	* @see calendar_type
	* @see days_of_week
	*
	* @return Void Doesn't return anything. Puts everything in the chart class variable.
	*/
	function SetupChart_SubscriberSummary($data=array())
	{
		$areas = array('unconfirms', 'confirms', 'unsubscribes', 'bounces', 'forwards');
		$now = getdate();

		switch ($this->stats_api->calendar_type) {
			case 'today':
			case 'yesterday':
			case 'last24hours':
				// we have to work out which element we're updating and the easiest way is based on the "name" of the item (eg 4pm).
				foreach ($areas as $k => $area) {
					foreach ($data[$area] as $p => $details) {
						$hr = $details['hr'];
						$count = $details['count'];
						$hr_date = $this->PrintDate(mktime($hr, 1, 1, 1, 1, $now['year']), GetLang('Daily_Time_Display'));
						$pos = array_search($hr_date, $this->chart['chart_data'][0]);
						$this->chart['chart_data'][($k+1)][$pos] = $count;
						$this->chart['chart_value_text'][($k+1)][$pos] = $this->FormatNumber($count);
					}
				}

			break;

			case 'last7days':
			// we have to work out which element we're updating and the easiest way is based on the "name" of the item (eg "Sun" or "Mon").
				foreach ($areas as $k => $area) {
					foreach ($data[$area] as $p => $details) {
						$count = $details['count'];
						$dow = $details['dow'];
						$text_dow = GetLang($this->days_of_week[$dow]);
						$pos = array_search($text_dow, $this->chart['chart_data'][0]);

						$this->chart['chart_data'][($k+1)][$pos] = $count;
						$this->chart['chart_value_text'][($k+1)][$pos] = $this->FormatNumber($count);
					}
				}
			break;

			case 'thismonth':
			case 'lastmonth':
			case 'last30days':
				// we have to work out which element we're updating and the easiest way is based on the "name" of the item (eg 4pm).
				foreach ($areas as $k => $area) {
					foreach ($data[$area] as $p => $details) {
						$dom = $details['dom'];
						$count = $details['count'];
						$pos = array_search($dom, $this->chart['chart_data'][0]);
						if ($pos !== false && $pos !== null) {
							$this->chart['chart_data'][($k+1)][$pos] = $count;
							$this->chart['chart_value_text'][($k+1)][$pos] = $this->FormatNumber($count);
						}
					}
				}
			break;

			default:
				foreach ($areas as $k => $area) {
					foreach ($data[$area] as $p => $details) {
						$mth = '';
						if (isset($details['mth'])) {
							$mth = $details['mth'];
							$idx = $now['mon'] - $mth;
							if ($idx < 0) {
								$idx = 12 + $idx;
							}
							$idx++;
						}

						if (isset($details['dow'])) {
							$idx = $details['dow'];
						}

						if (isset($details['dom'])) {
							$idx = $details['dom'];
						}

						$count = $details['count'];
						$this->chart['chart_data'][($k + 1)][$idx] = $count;
						$this->chart['chart_value_text'][($k + 1)][$idx] = $this->FormatNumber($count);
					}
				}
			break;
		}
	}

	/**
	* SetupChart_BounceSummary
	* This goes through the 2 areas (hard, soft) and fills in the chart data based on the data that is passed in.
	* The data is fetched by the Process function based on different criteria and passed here.
	*
	* @var Array $data The array of data to fill the graph up with. This will be a multidimensional array containing 'hard' and 'soft' (anything else is ignored).
	*
	* @see Process
	* @see calendar_type
	* @see days_of_week
	*
	* @return Void Doesn't return anything. Puts everything in the chart class variable.
	*/
	function SetupChart_BounceSummary($data=array())
	{
		$now = getdate();

		switch ($this->stats_api->calendar_type) {
			case 'today':
			case 'yesterday':
			case 'last24hours':
				foreach ($data as $p => $details) {
					if ($details['bouncetype'] == 'soft') {
						$k = 1;
					} else {
						$k = 2;
					}

					$hr = $details['hr'];
					$count = $details['count'];
					$hr_date = date(GetLang('Daily_Time_Display'), mktime($hr, 1, 1, 1, 1, $now['year']));
					$pos = array_search($hr_date, $this->chart['chart_data'][0]);

					$this->chart['chart_data'][$k][$pos] = $count;
					$this->chart['chart_value_text'][$k][$pos] = $this->FormatNumber($count);
				}
			break;

			case 'last7days':
				foreach ($data as $p => $details) {
					if ($details['bouncetype'] == 'soft') {
						$k = 1;
					} else {
						$k = 2;
					}

					$dow = $details['dow'];
					$count = $details['count'];
					$text_dow = GetLang($this->days_of_week[$dow]);
					$pos = array_search($text_dow, $this->chart['chart_data'][0]);

					$this->chart['chart_data'][$k][$pos] = $count;
					$this->chart['chart_value_text'][$k][$pos] = $this->FormatNumber($count);
				}
			break;

			case 'thismonth':
			case 'lastmonth':
			case 'last30days':
				foreach ($data as $p => $details) {
					if ($details['bouncetype'] == 'soft') {
						$k = 1;
					} else {
						$k = 2;
					}
					$count = $details['count'];

					$dom = $details['dom'];
					$pos = array_search($dom, $this->chart['chart_data'][0]);
						if ($pos !== false && $pos !== null) {
							$this->chart['chart_data'][$k][$pos] = $count;
							$this->chart['chart_value_text'][$k][$pos] = $this->FormatNumber($count);
					}
				}
			break;

			default:
				foreach ($data as $p => $details) {
					if ($details['bouncetype'] == 'soft') {
						$k = 1;
					} else {
						$k = 2;
					}

					$mth = '';
					if (isset($details['mth'])) {
						$mth = $details['mth'];
						$idx = $now['mon'] - $mth;
						if ($idx < 0) {
							$idx = 12 - $idx;
						}
						$idx++;
					}

					if (isset($details['dow'])) {
						$idx = $details['dow'];
					}

					if (isset($details['dom'])) {
						$idx = $details['dom'];
					}

					$count = $details['count'];
					$this->chart['chart_data'][$k][$idx] = $count;
					$this->chart['chart_value_text'][$k][$idx] = $this->FormatNumber($count);
				}
			break;
		}
	}

	/**
	* SetupChart
	* This goes through the data passed in and fills in the graph info.
	* The data is fetched by the Process function based on different criteria and passed here.
	*
	* @var Array $data The array of data to fill the graph up with.
	*
	* @see Process
	* @see calendar_type
	* @see days_of_week
	*
	* @return Void Doesn't return anything. Puts everything in the chart class variable.
	*/
	function SetupChart($data=array())
	{
		$now = getdate();

		switch ($this->stats_api->calendar_type) {
			case 'today':
			case 'yesterday':
			case 'last24hours':
				// we have to work out which element we're updating and the easiest way is based on the "name" of the item (eg 4pm).
				foreach ($data as $p => $details) {
					$hr = $details['hr'];
					$count = $details['count'];
					$hr_date = date(GetLang('Daily_Time_Display'), mktime($hr, 1, 1, 1, 1, $now['year']));
					$pos = array_search($hr_date, $this->chart['chart_data'][0]);
					$this->chart['chart_data'][1][$pos] = $count;
					$this->chart['chart_value_text'][1][$pos] = $this->FormatNumber($count);
				}
			break;

			case 'last7days':
				// we have to work out which element we're updating and the easiest way is based on the "name" of the item (eg 4pm).
				foreach ($data as $p => $details) {
					$count = $details['count'];

					$dow = $details['dow'];
					$text_dow = GetLang($this->days_of_week[$dow]);
					$pos = array_search($text_dow, $this->chart['chart_data'][0]);

					$this->chart['chart_data'][1][$pos] = $count;
					$this->chart['chart_value_text'][1][$pos] = $this->FormatNumber($count);
				}
			break;

			case 'thismonth':
			case 'lastmonth':
			case 'last30days':
				// we have to work out which element we're updating and the easiest way is based on the "name" of the item (eg 4pm).
				foreach ($data as $p => $details) {

					$dom = $details['dom'];
					$count = $details['count'];
					$pos = array_search($dom, $this->chart['chart_data'][0]);

					// If array_search() fails, that means this date isn't displayed on the chart
					// in which case the value should be skipped
					if ($pos !== null && $pos !== false) {
						$this->chart['chart_data'][1][$pos] = $count;
						$this->chart['chart_value_text'][1][$pos] = $this->FormatNumber($count);
					}
				}
			break;

			default:
				foreach ($data as $p => $details) {
					$mth = '';
					if (isset($details['mth'])) {
						$mth = $details['mth'];
						$idx = $now['mon'] - $mth;
						if ($idx < 0) {
							$idx = 12 + $idx;
						}
						$idx++;
					}

					if (isset($details['dow'])) {
						$idx = $details['dow'];
					}

					if (isset($details['dom'])) {
						$idx = $details['dom'];
					}

					$count = $details['count'];
					$this->chart['chart_data'][1][$idx] = $count;
					$this->chart['chart_value_text'][1][$idx] = $this->FormatNumber($count);
				}
			break;
		}
	}
}

header("Pragma: private");
header("Cache-control: private");

/**
* We need to call the chart ourselves because of the way the chart needs to get the data.
*/
$SSChart = new StatsChart();
$SSChart->Process();
