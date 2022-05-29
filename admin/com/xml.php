<?php

class xmlController
{
    protected $xml;
    protected $userRecord;

    private static $requiredVariables = [
        'username',
        'usertoken',
        'requesttype',
        'requestmethod',
        'details'
    ];

    private static $newUserProperties = [
        'groupid' => null,
        'trialuser' => '0',
        'username' => null,
        'password' => null,
        'fullname' => null,
        'emailaddress' => null,
        'usertimezone' => null,
        'gettingstarted' => 0,
        'status' => 1,
        'textfooter' => '',
        'htmlfooter' => '',
        'admintype' => 'c',
        'listadmintype' => 'c',
        'segmentadmintype' => 'c',
        'templateadmintype' => 'c',
        'smtpserver' => '',
        'smtpusername' => '',
        'smtppassword' => '',
        'smtpport' => '',
        'infotips' => 1,
        'usewysiwyg' => 1,
        'enableactivitylog' => 1,
        'editownsettings' => 0,
        'xmlapi' => 0,
        'xmltoken' => '',
        'user_language' => '',
        'adminnotify_email' => '',
        'adminnotify_send_flag' => '0',
        'adminnotify_send_threshold' => 0,
        'adminnotify_send_emailtext' => '',
        'adminnotify_import_flag' => '0',
        'adminnotify_import_threshold' => 0,
        'adminnotify_import_emailtext' => '',
    ];

    public function __construct()
    {
        if (!extension_loaded('SimpleXML')) {
            throw new RuntimeException('The XML-API requires the SimpleXML extension to be loaded.');
        }

        if (defined('IEM_SYSTEM_ACTIVE') && !IEM_SYSTEM_ACTIVE) {
            throw new RuntimeException('Error: Please contact your system admin!');
        }

        $tempXMLString = IEM::requestGetPOST('xml', '', 'trim');

        if (empty($tempXMLString)) {
            $tempXMLString = file_get_contents('php://input');
        }

        $tempXMLString = trim($tempXMLString);

        if (empty($tempXMLString)) {
            throw new InvalidArgumentException('No data has been given to the XML-API.');
        }

        $this->setXml($tempXMLString);
        $this->verifyRequiredFields();
        $this->authenticate();
    }

    public function routeRequest()
    {
        $userRecord = $this->getUserRecord();

        if ($this->getRequestType() == 'authentication' && $this->getRequestMethod() == 'xmlapitest') {
            self::SendResponse(true, [
                'user' => [
                    'userid' => $userRecord['userid'],
                    'username' => $userRecord['username']
                ]
            ]);
            return;
        }

        if ($this->isOldApiClass()) {
            require_once SENDSTUDIO_API_DIRECTORY."/{$this->getRequestType()}.php";
            $apiClass = ucwords(strtolower($this->getRequestType())) . '_API';

            if (!class_exists($apiClass, false)) {
                self::SendResponse(false, 'Invalid request type');
                return;
            }

            if ($apiClass == 'User_API') {
                $handlerObject = new User_API($userRecord['userid']);
            } else {
                $handlerObject = new $apiClass;
            }
        } elseif ($this->apiClassExists()) {
            $handlerObject = $this->getRequestType();
        } else {
            self::SendResponse(false, 'Invalid request type');
            return;
        }
        
        $handlerMethod = strtolower($this->getRequestMethod());

        $functionParams = $this->CreateFunctionParams($this->xml->details[0]);
        if (!method_exists($this, $handlerMethod)) {
            $this->forwardRequest($handlerObject, $handlerMethod, $functionParams);
        } else {
            $this->$handlerMethod($functionParams, $handlerObject);
        }
    }
    
    protected function forwardRequest($handlerObject, $handlerMethod, $functionParams)
    {
        if (!is_callable([$handlerObject, $handlerMethod]) and !is_callable("{$handlerObject}::{$handlerMethod}")) {
            self::SendResponse(false, 'Invalid request type');
            return;
        }
        
        $response = false;
        $handlerMethodReflector = new ReflectionMethod($handlerObject, $handlerMethod);
        $handlerMethodParameterReflector = $handlerMethodReflector->getParameters();
        $newFunctionParams = false;
        
        if (count($handlerMethodParameterReflector) == 0) {
            foreach($functionParams as $name => $value) {
                $handlerObject->set($name,$value);
            }
            $response = $handlerMethodReflector->invoke($handlerObject);
        } else {
            foreach ($handlerMethodParameterReflector as &$handlerMethodParam) {
                $response[] = $handlerMethodParam->getName();
                if (array_key_exists($handlerMethodParam->getName(), $functionParams)) {
                    $newFunctionParams[] = $functionParams[$handlerMethodParam->getName()];
                } else {
                    $newFunctionParams[] = $handlerMethodParam->getDefaultValue();
                }
            }
            if (is_object($handlerObject)) {
                $response = $handlerMethodReflector->invokeArgs($handlerObject, $newFunctionParams);
            } else {
                $response = $handlerMethodReflector->invokeArgs(null, $newFunctionParams);
            }
        }

        if(is_array($response) && isset($response[0]) && $response[0] === false){
            if(isset($response[1]) && !empty($response[1])){
                self::SendResponse(false, $response[1]);
                return;
            } else {
                self::SendResponse(false, $response);
                return;
            }
        }

        if(is_array($response) && isset($response[0]) && $response[0] === true){
            if(isset($response[1])){
                $response = (empty($response[1])) ? null : $response[1];
                self::SendResponse(true, $response);
                return;
            } else {
                self::SendResponse(true, null);
                return;
            }
        }

        if (empty($response)) {
            self::SendResponse(false, $response);
            return;
        }

        self::SendResponse(true, $response);
        return;
    }

    public function getRequestMethod()
    {
        return $this->xml->requestmethod;
    }

    public function getRequestType()
    {
        return $this->xml->requesttype;
    }

    public function getUserRecord()
    {
        return $this->userRecord;
    }

    protected function setXml($xmlString)
    {
        libxml_use_internal_errors(true);

        $this->xml = new SimpleXmlElement($xmlString, LIBXML_PARSEHUGE);

        if ($this->xml === false) {
            throw new InvalidArgumentException('The XML you provided is not valid. Please check your XML document and try again.');
        }

        $this->xml->requesttype = preg_replace('/[^\w]/', '_', $this->xml->requesttype);
        $this->xml->requestmethod = preg_replace('/[^\w]/', '_', $this->xml->requestmethod);

        return true;
    }

    protected function verifyRequiredFields()
    {
        foreach (self::$requiredVariables as $variable) {
            if (empty($this->xml->$variable)) {
                throw new InvalidArgumentException("The XML format you have sent is invalid. The following field is required: {$variable}");
            }
        }
    }

    protected function authenticate()
    {
        $userRecord = (new AuthenticationSystem())->Authenticate((string) $this->xml->username, null, (string) $this->xml->usertoken);

        if (empty($userRecord) || !isset($userRecord['userid'])) {
            throw new RuntimeException('Unable to check user details.');
        }

        $this->userRecord = $userRecord;
		$GLOBALS['userid'] = $userRecord['userid'];
    }

    protected function isOldApiClass()
    {
        return is_readable(SENDSTUDIO_API_DIRECTORY."/{$this->getRequestType()}.php");
    }

    protected function apiClassExists()
    {
        return substr($this->getRequestType(), 0, 4) == 'API_' && class_exists($this->getRequestType(), true);
    }

    /**
     * Send response back
     * It will print out a response and stop the script execution
     *
     * @param boolean $status Indicates whether or not to respond with a "SUCCESS" or "FAILED" response
     * @param string|array $data Data to be sent back
     */
    public static function SendResponse($status, $data) {
        echo '<?xml version="1.0" encoding="' . SENDSTUDIO_CHARSET . '" ?>';
        echo "\n<response>\n";

        if ($status) {
            echo "<status>SUCCESS</status>\n";
            echo "<data>" . trim(self::CreateOutput($data)) . "</data>";
        } else {
            echo "<status>FAILED</status>\n";
            echo "<errormessage>";
            if(is_array($data)){
                foreach($data as $str){
                    echo htmlspecialchars($str, ENT_QUOTES, SENDSTUDIO_CHARSET);
                }
            } else {
                echo htmlspecialchars($data, ENT_QUOTES, SENDSTUDIO_CHARSET);
            }
            echo "</errormessage>\n";

        }

        echo "</response>\n";
        exit;
    }

    /**
     * CreateFunctionParams
     * Creates an array of function parameters to pass directly to the php-api
     * This is a recursive function.
     * If multiple elements are found for a particular area, it's turned into an array.
     *
     * @param array $params
     * @return array Returns an array of function parameters to pass to the php-api. The array key is the 'field name' and the value is the data from the xml document.
     */
    public function CreateFunctionParams($params) {
        if (empty($params)) {
            return [];
        }

        $return = [];

        foreach ($params as $subKey => $subParams) {
            $subKey = trim($subKey);

            $childParams = $subParams->children();
            if (empty($childParams)) {
                if (array_key_exists($subKey, $return)) {
                    if (!is_array($return[$subKey])) {
                        $return[$subKey] = [$return[$subKey]];
                    }

                    array_push($return[$subKey], trim((string) $subParams));
                } else {
                    $return[$subKey] = trim((string) $subParams);
                }
                continue;
            }

            if (!isset($return[$subKey])) {
                $return[$subKey] = [];
            } else {
                if (!is_array($return[$subKey])) {
                    $return[$subKey] = [$return[$subKey]];
                }
            }
            $subParams = $this->CreateFunctionParams($childParams);

            /**
             * If it's an 'addsubscribertolist' request type, then we need to
             * make sure the custom fields sub-items are a multi-dimensional array
             * so we end up with:
             *
             * [0] => array
             * (
             * 	[fieldid]	=> 'A',
             * 	[value]		=> 'B',
             * ),
             * [1] => array
             * (
             * 	[fieldid]	=> 'C',
             * 	[value]		=> 'D',
             * );
             *
             */
            if ($this->getRequestType() == 'subscribers' && strtolower($this->getRequestMethod()) == 'addsubscribertolist') {
                if (count(array_keys($subParams)) > 1) {
					if(array_key_exists('value', $subParams) && isset($subParams['value'])){
						$subParams['value'] = $subParams['value'];
                    }
                    $return[$subKey][] = $subParams;
                } else {
                    $return[$subKey] = $subParams;
                }
            } else {
                $return[$subKey] = $subParams;
            }
        }
        return $return;
    }

    /**
     * CreateOutput
     * This is a recursive function that creates a valid xml response and handles whether the output passed in is
     * - just a string (return an escaped version of the output)
     * - an array (return an xml based tree)
     * - an array with numeric id's (eg '<0>xyz</0>')
     * - a multidimensional array (eg '<0><listname>xyz</listname></0>')
     *
     * @param Mixed $output The output to display can be a string, a single-element array or multi-dimensional array.
     *
     * @return string Returns a formatted xml document.
     */
    public static function CreateOutput($output='', $returnXML = true) {
        if (!is_array($output)) {
            return sprintf('%s', htmlspecialchars($output, ENT_QUOTES, SENDSTUDIO_CHARSET)) . "\n";
        }

        $xmlOutput = '';
        foreach ($output as $name => $data) {
            if (is_numeric($name)) {
                $name = 'item';
            }
            $quoted_name = htmlspecialchars($name, ENT_QUOTES, SENDSTUDIO_CHARSET);

            if (!is_array($data)) {
                $xmlOutput .= sprintf('<%s>%s</%s>', $quoted_name, htmlspecialchars($data, ENT_QUOTES, SENDSTUDIO_CHARSET), $quoted_name);
                continue;
            }

            $xmlOutput .= sprintf('<%s>', $quoted_name);

            if (is_array($data)) {
                $arrayXmlOutput = null;
                foreach ($data as $k => $v) {
                    if (is_array($v)) {
                        $arrayXmlOutput[] = self::CreateOutput([$k => $v], false);
                        continue;
                    }
                    if (is_numeric($k)) {
                        $k = 'item';
                    }
                    $k_quoted = htmlspecialchars($k, ENT_QUOTES, SENDSTUDIO_CHARSET);
                    $xmlOutput .= sprintf('<%s>%s</%s>', $k_quoted, htmlspecialchars($v, ENT_QUOTES, SENDSTUDIO_CHARSET), $k_quoted);
                }

                if(!is_null($arrayXmlOutput) && is_array($arrayXmlOutput)){
                    $xmlOutput .= '<items>' . implode('', $arrayXmlOutput) . '</items>';
                }
            }
            $xmlOutput .= sprintf('</%s>', $quoted_name);
        }

        if($returnXML == false){
            return $xmlOutput;
        }

        try {
            $xmlObj = new SimpleXmlElement($xmlOutput, LIBXML_PARSEHUGE);
        }
        catch(Exception $e){
            $xmlOutput = '<wrapper>' . $xmlOutput . '</wrapper>';
            $xmlObj = new SimpleXmlElement($xmlOutput, LIBXML_PARSEHUGE);
        }
        
        return str_replace(['<?xml version="1.0"?>', '<wrapper>', '</wrapper>'], '', $xmlObj->asXML());
    }

    /**
     * Fix for outdated XML API documentation
     * Changes the 'list' parameter to 'listid' and removes the 'list' parameter
     *
     * @param array $functionParams
     * @param mixed $handlerObject
     */
    public function deletesubscriber($functionParams, $handlerObject)
    {
        if (isset($functionParams['list'])) {
            $functionParams['listid'] = $functionParams['list'];
            unset($functionParams['list']);
        }

        list($status, $msg) = call_user_func_array([$handlerObject, 'DeleteSubscriber'], $functionParams);

        if (!$status) {
            self::SendResponse(false, $msg);
        } else {
            self::SendResponse(true,"");
        }
    }

    /**
     * addsubscribertolist is an xml-api specific function.
     * It checks if the subscriber is already on the list (if it is, it returns)
     * If they are not on the list, they are added.
     * Then each of the custom fields are associated with the subscriber if necessary.
     * The php-api has this as a multiple step process, but the xml-api does it all in one go.
     *
     * @param array $functionParams
     * @param mixed $handlerObject
     *
     * @see Subscriber_API::IsSubscriberOnList
     * @see Subscriber_API::AddToList
     * @see Subscriber_API::SaveSubscriberCustomField
     */
    public function addsubscribertolist($functionParams, $handlerObject)
    {
        $params = [];
        $params['emailaddress'] = (isset($functionParams['emailaddress'])) ? $functionParams['emailaddress'] : false;
        $params['mailinglist'] = (isset($functionParams['mailinglist'])) ? $functionParams['mailinglist'] : false;

        // check if they are on the list already.
        $subid = call_user_func_array([$handlerObject, 'IsSubscriberOnList'], $params);
        if ($subid) {
            self::SendResponse(false, $functionParams['emailaddress']. ' already exists in the given list');
            return;
        }

        $params['add_to_autoresponders'] = (isset($functionParams['add_to_autoresponders'])) ? $functionParams['add_to_autoresponders'] : true;

        $db = IEM::getDatabase();
        $db->StartTransaction();

        if (isset($functionParams['format'])) {
            $format = strtolower($functionParams['format']);
            $formats = ['t', 'text', 'h', 'html'];
            if (in_array($format, $formats)) {
                $handlerObject->format = substr($format, 0, 1);
            }
        }

        if (isset($functionParams['ipaddress'])) {
            if (SENDSTUDIO_IPTRACKING) {
                $handlerObject->requestip = $functionParams['ipaddress'];
            }
        }

        if (isset($functionParams['confirmed'])) {
            $sub_confirmed = 0;
            $confirmed = strtolower($functionParams['confirmed']);
            if ($confirmed == 'yes' || $confirmed == 'y' || $confirmed == 'true' || $confirmed == '1') {
                $sub_confirmed = 1;
            }
            if (isset($functionParams['ipaddress'])) {
                if (SENDSTUDIO_IPTRACKING) {
                    $handlerObject->confirmip = $functionParams['ipaddress'];
                }
            }
            $handlerObject->confirmed = $sub_confirmed;
        }

        // now try to add them to the list.
        $subscriber_id = call_user_func_array([$handlerObject, 'AddToList'], $params);
        if (!$subscriber_id) {
            $db->RollbackTransaction();
            self::SendResponse(false, 'Failed adding subcriber to the list');
        }

        require_once(SENDSTUDIO_LANGUAGE_DIRECTORY . '/default/frontend.php');

        /**
         * we don't need to include the api/lists.php file
         * because the AddToList function in the subscribers api does it already
         * because it checks the list exists before anything else.
         */
        $lists_api = new Lists_API();
        $lists_api->Load($functionParams['mailinglist']);

        $listowneremail = $lists_api->Get('owneremail');
        $listownername = $lists_api->Get('ownername');

        require_once(IEM_PATH . '/ext/interspire_email/email.php');
        $emailapi = new Email_API();

        $emailapi->SetSMTP(
            SENDSTUDIO_SMTP_SERVER,
            SENDSTUDIO_SMTP_USERNAME,
            @base64_decode(SENDSTUDIO_SMTP_PASSWORD),
            SENDSTUDIO_SMTP_PORT
        );

        $emailapi->Set('CharSet', SENDSTUDIO_CHARSET);

        $emailapi->Set('Subject', GetLang('SubscriberNotification_Subject'));
        $emailapi->Set('FromName', $listownername);
        $emailapi->Set('FromAddress', $listowneremail);
        $emailapi->Set('ReplyTo', $functionParams['emailaddress']);
        $emailapi->Set('BounceAddress', SENDSTUDIO_EMAIL_ADDRESS);

        $emailapi->Set('Subject', sprintf(GetLang('SubscriberNotification_Subject_Lists'), $lists_api->name));

        $body = '';
        $body .= sprintf(GetLang('SubscriberNotification_Field'), GetLang('EmailAddress'), $functionParams['emailaddress']);

        // no custom fields to process? just return the subscriber id.
        if (!isset($functionParams['customfields'])) {

            $body .= sprintf(GetLang('SubscriberNotification_Lists'), $lists_api->name);

            $emailbody = sprintf(GetLang('SubscriberNotification_Body'), $body);

            $emailapi->AddBody('text', $emailbody);

            if ($lists_api->notifyowner) {
                $emailapi->AddRecipient($lists_api->owneremail, $lists_api->ownername, 't');
                $emailapi->Send();
            }

            $db->CommitTransaction();
            self::SendResponse(true, $subscriber_id);
            return;
        }

        require_once(SENDSTUDIO_API_DIRECTORY . '/customfields.php');
        $customfields_api = new CustomFields_API();

        // if there is only one custom field, then it's not converted into a multi-dimensional array with each custom field being an 'item'.
        // instead we just get a single element.
        $subscriber_customfields = (isset($functionParams['customfields']['item'])) ? $functionParams['customfields']['item'] : $functionParams['customfields'];

        foreach ($subscriber_customfields as $k => $details) {
            $loaded = $customfields_api->Load($details['fieldid']);
            if (!$loaded) {
                $db->RollbackTransaction();
                self::SendResponse(false, "Unable to load field id '" . $details['fieldid'] . "'");
                return;
            }

            // See if specific custom fields need data transformation
            switch ($customfields_api->fieldtype) {
                // Custom fields that require multiple values need to be converted to array
                case 'checkbox':
                    if (!is_array($details['value'])) {
                        $details['value'] = [$details['value']];
                    }
                    break;

                case 'date':
                    require_once(SENDSTUDIO_API_DIRECTORY.'/customfields_date.php');
                    $cfdateapi = new CustomFields_Date_API($details['fieldid']);
                    $details['value'] = $cfdateapi->CheckData($details['value'],true);
                    if ($details['value'] !== false) {
                        $details['value'] = $details['value']['dd']."/".$details['value']['mm']."/".$details['value']['yy'];
                    }
                    break;
            }

            $valid_value = $details['value'] !== false ? $customfields_api->ValidData($details['value']) : false;

            if (!$valid_value) {
                $db->RollbackTransaction();
                self::SendResponse(false, "The data provided for field '" . $customfields_api->GetFieldName() . "' is invalid (you provided '" . $details['value'] . "')");
                return;
            }

            $handlerObject->SaveSubscriberCustomField($subscriber_id, $details['fieldid'], $details['value']);

            $fieldvalue = $customfields_api->GetRealValue($details['value']);
            if ($fieldvalue == '') {
                $fieldvalue = GetLang('SubscriberNotification_EmptyField');
            }
            $fieldname = $customfields_api->GetFieldName();
            $body .= sprintf(GetLang('SubscriberNotification_Field'), $fieldname, $fieldvalue);
        }

        $body .= sprintf(GetLang('SubscriberNotification_Lists'), $lists_api->name);

        $emailbody = sprintf(GetLang('SubscriberNotification_Body'), $body);

        $emailapi->AddBody('text', $emailbody);

        if ($lists_api->notifyowner) {
            $emailapi->AddRecipient($lists_api->owneremail, $lists_api->ownername, 't');
            $emailapi->Send();
        }

        $db->CommitTransaction();
        self::SendResponse(true, $subscriber_id);
    }

    public function createnewuser($functionParams)
    {
        $this->editexistinguser($functionParams, false);
    }

    public function editexistinguser($functionParams, $editMode)
    {
        $tempRequired = [];
        $paramUserid = IEM::ifsetor($functionParams['userid'], false);
        $paramUsername = IEM::ifsetor($functionParams['username'], false);
        if (!$paramUsername) {
            self::SendResponse(false, 'username cannot be empty.');
            return;
        }

        if ($editMode && !$paramUserid) {
            self::SendResponse(false, 'userid cannot be empty.');
            return;
        }

        if (!$editMode) {
            foreach (self::$newUserProperties as $key => $value) {
                if (is_null($value) && !isset($functionParams[$key])) {
                    array_push($tempRequired, $key);
                }
            }
            if (!isset($functionParams['permissions'])) {
                array_push($tempRequired, 'permissions');
            }
        }

        if (count($tempRequired) != 0) {
            self::SendResponse(false, 'Invalid parameters specified to use this function.');
            return;
        }

        $user = New User_API();
        $warnings = [];

        if ($editMode && !$user->Load($paramUserid, true)) {
            self::SendResponse(false, 'Cannot load user record.');
            return;
        }

        if ($this->checkUsernameTaken($editMode, $paramUsername, $paramUserid)) {
            self::SendResponse(false, 'Username is already taken.');
            return;
        }

        foreach (self::$newUserProperties as $key => $value) {
            if ($editMode) {
                if (isset($functionParams[$key])) {
                    $user->Set($key, $functionParams[$key]);
                }
                continue;
            }

            $tempValue = $value;
            if (isset($functionParams[$key])) {
                $tempValue = $functionParams[$key];
            }
            $user->Set($key, $tempValue);
        }

        if (SENDSTUDIO_MAXHOURLYRATE > 0) {
            if ($user->Get('perhour') == 0 || ($user->Get('perhour') > SENDSTUDIO_MAXHOURLYRATE)) {
                $user_hourly = $this->FormatNumber($user->Get('perhour'));
                if ($user->Get('perhour') == 0) {
                    $user_hourly = GetLang('UserPerHour_Unlimited');
                }
                $warnings[] = sprintf(GetLang('UserPerHourOverMaxHourlyRate'), $this->FormatNumber(SENDSTUDIO_MAXHOURLYRATE), $user_hourly);
            }
        }

        if (isset($functionParams['segments'])) {
            $user->RevokeSegmentAccess();

            $access = [];
            if (!is_array($functionParams['segments'])) {
                $access[$functionParams['segments']] = 1;
            } else {
                foreach ($functionParams['segments'] as $segmentid) {
                    $access[$segmentid] = 1;
                }
            }

            $user->GrantSegmentAccess($access);
        }

        if ($editMode) {
            if (!$user->Save(true)) {
                self::SendResponse(false, 'Unable to update user');
                return;
            }

            // Run any customized modifications we need to. Will load xml-api-editexistinguser.php if it exists
            $file = IEM_PATH . '/custom/xml-api-editexistinguser.php';
            if (is_readable($file)) {
                include $file;
            }

            self::SendResponse(true, $user->userid);
            return;
        }

        // Create user
        $result = $user->Create();
        if ($result == '-1') {
            self::SendResponse(false, 'The license will not allow you to create more users.');
            return;
        } elseif (!$result) {
            self::SendResponse(false, 'Unable to create user');
            return;
        }

        // Run any customized modifications we need to. Will load xml-api-createnewuser.php if it exists
        $file = IEM_PATH . '/custom/xml-api-createnewuser.php';
        if (is_readable($file)) {
            include $file;
        }

        self::SendResponse(true, intval($result));
    }

    public function checkUsernameTaken($editMode, $paramUsername, $currentUserId)
    {
        $user = New User_API();
        $existingUser = $user->Find($paramUsername);
        if ($existingUser !== false) {
            if ($editMode && $existingUser == $currentUserId) {
                return false;
            }
            return true;
        }
        return false;
    }

    public function FormatNumber($number = 0, $decimalplaces = 0)
    {
        return number_format(
            (float) $number, $decimalplaces,
            GetLang('NumberFormat_Dec'),
            GetLang('NumberFormat_Thousands')
        );
    }
}
