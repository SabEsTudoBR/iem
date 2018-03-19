<?php
/**
 * Addons_surveys_api
 * API functions for surveys
 */
class Addons_survey_api extends API
{
	/**
	 * Holds the different question types. Values are loaded in __construct
	 * @var array
	 */
	public $question_types;

	/**
	 * Holds values set using __set
	 * @var array
	 */
	private $data = [];

	/**
	 * validSorts
	 * Valid columns to sort surveys by. The first column is the default
	 * @var array
	 */
	public static $validSorts = [
	    'name',
        'created',
        'updated',
        'responsecount'
    ];

	public function __set($var, $val)
	{
		$this->data[$var] = $val;
	}

	public function __get($var)
	{
		if (array_key_exists($var, $this->data)) {
			return $this->data[$var];
		}
		return false;
	}


	/**
	 * populateFormData
	 * @param $table_keys - the key structure of the table
	 * @param $form- the actual form
	 * Populating all the data variable with the form.
	 */
	public function populateFormData($table_keys, $form)
	{
		$this->_columns = $table_keys;
		if (is_array($form)) {
			foreach ($form as $key => $val) {
				if (in_array($key, $table_keys)){
					$this->$key = $val;
				}
			}
		}
	}

	/**
	 * Load
	 * Loads a survey from the database
	 *
	 * @param Int $surveyid The surveyid to load
	 *
	 * @return Boolean Returns true on success, false on failure
	 */
	public function Load($surveyid)
	{
		$surveyid = (int)$surveyid;
		$prefix = $this->Db->TablePrefix;
		$query = "SELECT * FROM {$prefix}surveys WHERE id = {$surveyid}";

		$result = $this->Db->Query($query);
		$survey = $this->Db->Fetch($result);

		return $this->_loadData($survey);
	}


	/**
	 * _loadData
	 * Loads a specified survey and questions
	 *
	 * @param bool|array $survey The survey to load. Supported fields are:
	 * array(
	 *	'name' => 'Name of survey'
	 * )
     *
     * @return bool
	 */
	private function _loadData($survey = false)
	{
		if (!is_array($survey)) {
			return false;
		} else {
			foreach ($survey as $key => $val) {
				if ($key == 'id') {
					$this->data[$key] = (int)$val;
				} else {
					$this->data[$key] = $val;
				}
			}
		}
		return true;
	}

	/**
	 * Get
	 * @param string $varname
	 *
	 * Overide the parent function to look inside the data container
	 *
	 * @return mixed
	 */
	public function Get($varname = '', $default = null)
	{
		if (isset($this->data[$varname])) {
			return $this->data[$varname];
		}

		return $default;
	}


	public function GetData()
	{
		return $this->data;
	}

	/**
	 * Create
	 * Creates a new survey.
	 *
	 * @return Int Returns the surveyid of the new survey
	 */
	public function Create()
	{
		$prefix = $this->Db->TablePrefix;
		$this->created = $this->GetServerTime();
		$user = IEM::getCurrentUser();
		$userid = $user->userid;

		$tablefields = implode(',', $this->_columns);

		$query = "INSERT INTO {$prefix}surveys ({$tablefields})
				  VALUES ('" . $this->Db->Quote($this->name) . "',"
							 . $userid . ",'"
				  			 . $this->Db->Quote($this->description) . "','"
							 . $this->Db->Quote($this->created) . "','"
							 . $this->Db->Quote($this->surveys_header) . "','"
							 . $this->Db->Quote($this->surveys_header_text) . "','"
							 . $this->Db->Quote($this->surveys_header_logo) . "','"
							 . $this->Db->Quote($this->email) . "','"
							 . $this->Db->Quote($this->email_feedback) . "','"
							 . $this->Db->Quote($this->after_submit) . "','"
							 . $this->Db->Quote($this->show_message) . "','"
							 . $this->Db->Quote($this->show_uri) . "','"
							 . $this->Db->Quote($this->error_message) . "','"
							 . $this->Db->Quote($this->submit_button_text) . "')";



		if (SENDSTUDIO_DATABASE_TYPE == 'pgsql') {
			$query .= ' RETURNING id;';
		}

		$results = $this->Db->Query($query);

		if ($results === false) {
			return false;
		}

		if (SENDSTUDIO_DATABASE_TYPE == 'pgsql') {
			$surveyid = $this->Db->FetchOne($results);
		} else {
			$surveyid = $this->Db->LastId();
		}

		return $surveyid;
	}

	public function deleteWidgetsNotIn(array $widgetsIds, $surveyId)
	{
		$prefix = $this->Db->TablePrefix;
		$widgetsIdString = implode(',', $widgetsIds);
		$sql = 	"DELETE FROM {$prefix}surveys_widgets WHERE surveys_id = {$surveyId} AND id NOT IN ({$widgetsIdString})";
		return $this->Db->Query($sql);
	}

	public function getWidgets($formId)
	{
		$widgets = [];
		$prefix = $this->Db->TablePrefix;

		$sql = 	"SELECT * FROM {$prefix}surveys_widgets WHERE surveys_id = {$formId} ORDER BY display_order ASC;";

		$results = $this->Db->Query($sql);
		if ($results === false || empty($results)) {
			return false;
		}

		while ($row = $this->Db->Fetch($results)) {
			foreach ($row as &$value) {
				$value = htmlspecialchars($value);
			}
			$widgets[] = $row;
		}
		return $widgets;
	}

	/**
	 * getFields
	 * @param $widgetId
     * @return bool|array
	 *
	 * Returns the field given a specific widget ID
	 */
	public function getFields($widgetId)
	{
		$widgetId = intval($widgetId);
		$prefix = $this->Db->TablePrefix;

		$sql = "SELECT * FROM {$prefix}surveys_fields WHERE surveys_widget_id = {$widgetId}";

		$results = $this->Db->Query($sql);
		if ($results === false || empty($results)) {
			return false;
		}

        $fields = [];
        while ($row = $this->Db->Fetch($results)) {
			$fields[] = $row;
		}
		return $fields;
	}

	public function setId($formId)
	{
		$this->id = (int)$formId;
	}

	public function getId()
	{
		return (int)$this->id;
	}

	public function Update($surveyId)
	{
		$where = 'id = ' . $surveyId;

		$surveys_data = $this->data;

		if (isset($surveys_data['_columns'])) {
			unset($surveys_data['_columns']);
		}

		if (isset($surveys_data['id'])) {
			unset($surveys_data['id']);
		}

		$surveys_data['updated'] = $this->GetServerTime();

		$this->Db->UpdateQuery('surveys', $surveys_data, $where);
	}


	/**
	 * getSurveyContent
	 * Render the actual survey question for the specified form id that passed .
	 *
	 * @return string rendered template
	 *
	 * @param int $surveyId
	 * @param IEM_InterspireTemplate $tpl
	 */
	public function getSurveyContent($surveyId, $tpl)
	{
		$success_message =  IEM::sessionGet('survey.addon.' . $surveyId . '.successMessage');
		if ($success_message) {
				IEM::sessionRemove('survey.addon.' . $surveyId . '.successMessage');
				$tpl->Assign('successMessage', $success_message);
				return $tpl->ParseTemplate('survey_success');
		}

		$tpl->Assign('action', 'surveys_submit.php?ajax=1&formId=' . $surveyId);

		// check for valid ID
		if (!isset($surveyId)) {
			return '';
		}

		require_once('widgets.php');
		$widgets_api = new Addons_survey_widgets_api();
		$loadRes = $this->Load($surveyId);
		if ($loadRes === false) {
			return 'invalid form id';
		}

		$surveyData = $this->GetData();

		$widgets = $this->getWidgets($surveyId);
		if ($widgets) {
			$widgetErrors   = IEM::sessionGet('survey.addon.' . $surveyId . '.widgetErrors');

			foreach ($widgets as $k => &$widget) {
				if ($widget['is_visible'] == 1 || $widget['type'] == 'section.break') {
				// $widget->className = Interspire_String::camelCase($widget->type, true);
				// Getting error from form..
                                        $widget['className'] = 'Widget_' . str_replace('.', '_', $widget['type']);
					$widgets_api->SetId($widget['id']);
					$widget['fields'] = $widgets_api->getFields(false);
					// if there are errors for this widget, set them

					if ($widgetErrors && count($widgetErrors[$widget['id']]) > 0) {
							$widget['errors'] = $widgetErrors[$widget['id']];
					}

					// randomize the fields if told to do so
					if ($widget['is_random'] == 1) {
						shuffle($widget['fields']);
					}

					// tack on an other field if one exists
					 if ($otherField = $widgets_api->getOtherField()) {
						$otherField['value'] = '__other__';
						$widget['fields'][] = $otherField;
					}

					// if it is a file widget, then grab the file types
					if ($widget['type'] == 'file') {
						$widget['fileTypes']    = preg_split('/\s*,\s*/', $widget['allowed_file_types']);
						$widget['lastFileType'] = array_pop($widget['fileTypes']);
					}

					// assign the widget information to the view
					$tpl->Assign('widget', $widget);

					// render the widget template
					$widget['template'] = $tpl->parseTemplate('widget.front.' . $widget['type'], true);
				} else {
					unset($widgets[$k]);
				}
			}
			// clear the widget errors session variable
			IEM::sessionRemove('survey.addon.' . $surveyId . '.widgetErrors');
		}

		// assign the form, widget and widget-field data to the template
		$tpl->Assign('errorMessage', IEM::sessionGet('survey.addon.' . $surveyId . '.errorMessage'));
		$tpl->Assign('successMessage', IEM::sessionGet('survey.addon.' . $surveyId . '.successMessage'));

		$tpl->Assign('survey', $surveyData);
		$tpl->Assign('widgets', $widgets);

		// unset the message that was set, so it doesn't get displayed again
		IEM::sessionRemove('survey.addon.' . $surveyId . '.errorMessage');
		IEM::sessionRemove('survey.addon.' . $surveyId . '.successMessage');

		return $tpl->ParseTemplate('survey');
	}


	/**
	 * GetSurveys
	 * Retrieves a list of surveys from the database
	 *
	 * @param Int $ownerid The user to fetch surveys for
	 * @param Int $pageid The page to start retrieving results from. This is ignored if perpage is set to 'all'
	 * @param Int $perpage The number of entries per page. Specify 'all' to retrieve all results.
	 * @param array|bool $search_info Restrict results to certain values. Key names should be the column names. Example:
	 * array(
	 * 	'eventtype' => 'Email', 'listid' => 4
	 * )
	 * Specify an array to select multiple values:
	 * array(
	 * 	'eventtype' => array('Email','Phone Call')
	 * )
	 * Date restrictions are specified using the 'restrictions' key:
	 * array(
	 * 	'restrictions' => 'eventdate >= 1216250000 AND eventdate < 1216252570'
	 * )
	 * @param array $sort_details Column and direction to sort the results by
	 * @param Boolean $count_only Specify true to return the number of surveys. Specify false (default) to return a list of surveys
	 *
	 * @return array Returns an array of results
	 */
	public function GetSurveys($ownerid = 0,$pageid=1,$perpage=20,$search_info = false,$sort_details = array(),$count_only = false)
	{
		$prefix = $this->Db->TablePrefix;
		$ownerid = (int)$ownerid;

		$sortby = (isset($sort_details['SortBy'])) ? $sort_details['SortBy'] : '';
		$sortdirection = (isset($sort_details['Direction'])) ? strtolower($sort_details['Direction']) : '';

		if (!in_array($sortby, self::$validSorts)) {
			$sortby = self::$validSorts[0];
		}

		if (!in_array($sortdirection,array('asc','desc'))) {
			$sortdirection = 'asc';
		}

		if ($perpage == 'all') {
			$perpage = 0;
			$offset = 0;
		} else {
			$perpage = (int)$perpage;
			$offset = ($pageid - 1) * $perpage;
		}

		$user = GetUser($ownerid);
		$query_user = "";
		if (empty($user->group->systemadmin)) {
			$query_user = " s.userid = $ownerid AND";
		}

		if ($count_only) {
			$query = "SELECT COUNT(*) FROM {$prefix}surveys s";
                        if (empty($user->group->systemadmin)) {
			 	$query .= " WHERE s.userid = $ownerid";
			}
		} else {
			$query = "SELECT s.*,u.username as username, (SELECT count(id) FROM {$prefix}surveys_response WHERE {$prefix}surveys_response.surveys_id = s.id) as responseCount
						FROM {$prefix}surveys s, {$prefix}users u
						WHERE {$query_user} u.userid = s.userid
						ORDER BY $sortby $sortdirection";

			if ($perpage) {
				$query .= " LIMIT $perpage";
			}
			if ($offset) {
				$query .= " OFFSET $offset";
			}
		}


		$result = $this->Db->Query($query);

		if ($count_only) {
			$count = $this->Db->FetchOne($result);
			return $count;
		}

		$return = array();
		while ($row = $this->Db->Fetch($result)) {
			$return[] = $row;
		}

		return $return;
	}

	/**
	 * Deletes a surveys, together with its Widgets and Fields
	 *
	 * @param Int $surveyid The surveyid to delete
	 *
	 * @return Void Returns nothing
	 */
	public function Delete($surveyid)
	{
		$surveyid = (int)$surveyid;
		$prefix = $this->Db->TablePrefix;

		// First Delete all Widgets Associated with the form,
		$widgets = $this->getWidgets($surveyid);
		foreach ($widgets as $key=>$widget) {
				// for each widget delete all the fields related ..
				$query = "DELETE FROM {$prefix}surveys_fields WHERE surveys_widget_id = {$widget['id']}";
				$this->Db->Query($query);
		}

		// Delete the actual widget,
		$query = "DELETE FROM {$prefix}surveys_widgets WHERE surveys_id = {$surveyid}";
		$this->Db->Query($query);

		// Lastly delete the actual suvey
		$query = "DELETE FROM {$prefix}surveys WHERE id = $surveyid";
		$this->Db->Query($query);

		// Delete all the responses and response value as well..
		$query = "DELETE sr, srv
				  FROM {$prefix}surveys_response as sr, {$prefix}surveys_response_value as srv
				  WHERE sr.id = srv.surveys_response_id and
				  sr.surveys_id = {$surveyid}";


		// Delete all the files uploaded from the survey folders..
		$survey_images_dir = TEMP_DIRECTORY . DIRECTORY_SEPARATOR . 'surveys' . DIRECTORY_SEPARATOR . $surveyid;

		if (is_dir($survey_images_dir)) {
		// Delete using our library file
				$dir = new IEM_FileSystem_Directory($survey_images_dir);
				$dir->delete();
		}

		$this->Db->Query($query);
	}

	/***
	 * _deleteDirectory
	 * Recursively delete the directory
	 *
	 */
	private function _deleteDirectory($dir) {
	     if (!file_exists($dir)) return true;
	        if (!is_dir($dir)) return unlink($dir);
	        foreach (scandir($dir) as $item) {
	            if ($item == '.' || $item == '..') continue;
	            if (!$this->_deleteDirectory($dir.DIRECTORY_SEPARATOR.$item)) return false;
	        }
	        return rmdir($dir);
	}


	/**
	 * getResponses
	 * Retrieves a list of responses for this form.
	 *
     * @param int $surveyId
	 * @return Mixed
	 */

	public function getResponses($surveyId)
	{
	    if (empty($surveyId)) {
	        return [];
        }
        
		$prefix = $this->Db->TablePrefix;

		$query = "
            SELECT *
            FROM  {$prefix}surveys_response
            WHERE surveys_id = {$surveyId}
            ORDER BY datetime
        ";

		$result = $this->Db->Query($query);

		$return = [];
		while ($row = $this->Db->Fetch($result)) {
			$return[] = $row;
		}

		return $return;
	}

	/**
	 * getResponses
	 * Retrieves a list of responses ID associated with the Survey.
	 * This is later use to create Responses number based on index
	 *
	 * @return Mixed
	 */

	public function getResponsesId($surveyId)
	{
		$prefix = $this->Db->TablePrefix;
		$query = "SELECT id FROM  {$prefix}surveys_response WHERE surveys_id = {$surveyId} ORDER BY id";
		$result = $this->Db->Query($query);
		$return = [];
		$counter = 1;
		while ($row = $this->Db->Fetch($result)) {
			$return[$counter] = $row['id'];
			$counter++;
		}
		return $return;
	}

	/**
	 * Retrieves the number of responses that are associated to this form.
	 *
	 * @return Integer
	 */
	public function getResponseCount($surveyId)
	{
		$responses = $this->getResponses($surveyId);

		if ($responses) {
			return count($responses);
		} else {
			return 0;
		}
	}


	/**
     * @param User_API $user
     * @return bool
	 */
	public function checkValidSurveyAccess($user, $surveyId)
	{
		$prefix = $this->Db->TablePrefix;

		if (!$user->isAdmin()) {
				$query = "SELECT * FROM  {$prefix}surveys WHERE id = {$surveyId} and userid = {$user->userid}";
				$result = $this->Db->Query($query);
				$return = [];
				if (!empty($result)) {
					while ($row = $this->Db->Fetch($result)) {
						$return[] = $row;
					}
					if (empty($return)) {
						return false;
					}
				}
			}
		return true;
	}

	/**
	 * getResponseByIndex
	 * Retrieves a response by its index.
	 *
	 * @param Mixed $index - An integer or string number of the response to retrieve by its index.
     * @param int $surveyId
     *
	 * @return Mixed
	 */
	public function getResponseByIndex($index = 0, $surveyId)
	{
		// get a list of responses
		$prefix = $this->Db->TablePrefix;

		$query = "
				SELECT *
				FROM  {$prefix}surveys_response
				WHERE
					surveys_id = {$surveyId}
				ORDER BY
					datetime
				LIMIT {$index}, 1
			;";

		$result = $this->Db->Query($query);

		$return = array();
		while ($row = $this->Db->Fetch($result)) {
			$return[] = $row;
		}

		// get a list of responses
		return $return;
	}

	/**
	 * Retrieves a response by its index.
	 *
	 * @param Mixed $number - An integer or string number of the response to retrieve by its number.
     * @param int $surveyId
	 *
	 * @return Mixed
	 */
	public function getResponseByNumber($number = 1, $surveyId)
	{
		return $this->getResponseByIndex($number - 1, $surveyId);
	}
}
