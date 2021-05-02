<?php
/**
 * This file contains IEM static class
 * @package interspire.iem.lib
 */

/**
 * IEM static class
 *
 * Provide a way to access application level information throughout the application.
 * This class will become the element that bind the application together.
 *
 * What is planned for this class apart form hosting generic functions:
 * - Licensing moved to this class?
 * - User creation or verification?
 * - Sending verification?
 *
 * @package interspire.iem.lib
 *
 * @todo most
 */
class IEM
{
	/**
	 * Define current version
	 */
	const VERSION = '6.2.2';   
	/**
	 * Define current database version
	 *
	 * @todo deprecate this... find a way to organize the upgrades based on versions instead
	 */
	const DATABASE_VERSION = '20200907';

	/**
	 * Session name that is used by IEM framework
	 */
	const SESSION_NAME = 'IEMSESSIONID';

	/**
	 * CONSTRUCTOR
	 * This will make sure that the constructor cannot be instantiated
	 */
	final private function __construct()
	{
		/* Cannot be instantiated */
	}

	/**
	 * Initialize the framework
	 * @param Boolean $reset Whether or not to re-initialize the framework again
	 * @return Boolean Returns TRUE the application initializes without encountering any errors, FALSE otherwise
	 */
	final static public function init($reset = false)
	{
		$GLOBALS['ApplicationUrl'] = SENDSTUDIO_APPLICATION_URL;

		// Defining IEM_MARKER in the event is part of the installation procedure
		// If it is not there, we can assume that the stash file has been overwritten
		// So we will need to restore it.
		// TODO change reference to SENSTUDIO_IS_SETUP
		if (defined('SENDSTUDIO_IS_SETUP') && SENDSTUDIO_IS_SETUP && !InterspireEvent::eventExists('IEM_MARKER_20090701')) {
			IEM_Installer::RegisterEventListeners();

			// Restore Addons listeners
			require_once IEM_ADDONS_PATH . '/interspire_addons.php';
			$addons = new Interspire_Addons();
			$addons->FixEnabledEventListeners();

			InterspireEvent::eventCreate('IEM_MARKER_20090701');
		}

		if (!self::configInit($reset)) {
			return false;
		}

		if (!self::sessionInit($reset)) {
			return false;
		}

		if (!self::userInit($reset)) {
			return false;
		}

		// ----- Include common language variables
			$tempUser = IEM::getCurrentUser();
			$tempUserLanguage = 'default';

			if (!empty($tempUser->user_language) && is_dir(IEM_PATH ."/language/{$tempUser->user_language}")) {
				$tempUserLanguage = $tempUser->user_language;
			}

			require_once (IEM_PATH ."/language/{$tempUserLanguage}/whitelabel.php");
			require_once (IEM_PATH ."/language/{$tempUserLanguage}/language.php");

			self::$_enableInfoTips = false;
			if (isset($tempUser->infotips) && $tempUser->infotips) {
				self::$_enableInfoTips = true;
			}

			unset($tempUserLanguage);
			unset($tempUser);
		// -----

        return true;
	}




	// --------------------------------------------------------------------------------
	// Methods related to configuration
	// --------------------------------------------------------------------------------
		/**
		 * A flag indicating whether or not configuration has been initialized before
		 * @var Boolean Indicates whether or not config has been initialized
		 */
		static private $_configInitFlag = false;

		/**
		 * Configuration variables
		 * @var array An associative array that is used by the framework as a whole
		 */
		static private $_configVariables = array();

		/**
		 * Show Kb Tool tips
		 * @var Boolean Flag to decide if to show KB Tool tips.
		 */
		static private $_enableInfoTips = false;

		/**
		 * enableInfoTipsGet
		 *
		 * @return Boolean Returns true if the user's infotips are enabled. Otherwise, return false.
		 *
		 */
		final static public function enableInfoTipsGet() {
			return intval(self::$_enableInfoTips);
		}

		/**
		 * Initialize config
		 * Initialize "config" for the framework to use
		 *
		 * @param Boolean $reset Flag to indicate whether or not the procedure can reset previously initialized configuration
		 * @return Boolean Returns TRUE if successful, FALSE otherwise
		 * @todo all
		 */
		final static public function configInit($reset = false)
		{
			if (self::$_configInitFlag && !$reset) {
				return false;
			}

			self::$_configInitFlag = true;

			return true;
		}

		final static public function configGet()
		{
			$required_constants = [
			    'SENDSTUDIO_DATABASE_TYPE',
                'SENDSTUDIO_DATABASE_HOST',
                'SENDSTUDIO_DATABASE_USER',
                'SENDSTUDIO_DATABASE_PASS',
                'SENDSTUDIO_DATABASE_NAME',
            ];

			foreach ($required_constants as $required) {
				if (!defined($required)) {
					return false;
				}
			}

			return true;
		}


		final static public function configSet() {}


		final static public function configSave()
        {
            return true;
        }

		/**
		 * Enter description here...
		 *
		 * @param array $values An associative array to replace the configuration with
		 */
		final static public function configReset($values = array())
		{

		}
	// --------------------------------------------------------------------------------




	// --------------------------------------------------------------------------------
	// Functions that will handle "session"
	//
	// When the constant IEM_NO_SESSION is defined, session will not be presisted.
	//
	// TODO all
	// --------------------------------------------------------------------------------
		/**
		 * A flag indicating whether or not session has been initialized before
		 * @var Boolean Indicates whether or not session has been initialized
		 */
		static private $_sessionInitFlag = false;

		/**
		 * Holds reference to the session storage variable
		 * @var Mixed Reference to session storage variable
		 */
		static private $_sessionReference = false;




		/**
		 * Initialize Session
		 * @param $reset Flag to indicate whether or not the procedure can reset previously initialized session
		 * @return Boolean Returns TRUE if successful, FALSE otherwise
		 *
		 * @todo session variable expiry
		 * @todo special storage area to hold current user information
		 */
		final static public function sessionInit($reset = false)
		{
			// ---- Make sure that the session is not being accidentally initialized more than once
				if (self::$_sessionInitFlag && !$reset) {
					return false;
				}

				self::$_sessionInitFlag = true;
			// -----

			// Closes current session if they have session.auto_start set to on
			if (session_id()) {
				@session_write_close();
			}


			// if IEM_NO_SESSION or PHP running in CLI mode, do not start session
			if (!defined('IEM_NO_SESSION') && !IEM_CLI_MODE) {
				session_name(IEM::SESSION_NAME);

				ini_set('session.use_cookies', 1);
                ini_set('session.cookie_httponly', 1);
                ini_set('session.use_strict_mode', 1);

				ini_set('session.gc_probability', 1);
				ini_set('session.gc_divisor', 100);
				ini_set('session.gc_maxlifetime', 3600);

				@session_start();
			}


			// Make sure the session strucutre has been initialized
			if (isset($_SESSION)) {
				self::$_sessionReference = &$_SESSION;

			// If $_SESSION is not set, the script is probably invoked using CLI,
			// therefore we do not need to presists session variable for subsequent requests
			// which means a simple array will do to emulate $_SESSION
			} else {
				self::$_sessionReference = [];
			}

			// Structure check
			if (!array_key_exists('initialized', self::$_sessionReference)) {
				self::$_sessionReference = [
					'initialized' => true,
					'storage' => [],
					'user' => []
                ];
			}

			return true;
		}

		/**
		 * Get variable from session
		 *
		 * @param string $variableName The name of the variable to fetch
		 * @param mixed $defaultValue The default value you want to use when variable does not exits (OPTIONA, default = NULL)
		 *
		 * @return mixed Returns variable fetched from the session if exists, otherwise it will return $defaultValue
		 *
		 * @todo session variable expiry
		 */
		final static public function sessionGet($variableName, $defaultValue = null)
		{
			if (array_key_exists($variableName, self::$_sessionReference['storage'])) {
				return self::$_sessionReference['storage'][$variableName];
			}

			return  $defaultValue;
		}

		/**
		 * Set variable to session
		 *
		 * NOTE:
		 * - The 3rd parameter $expiry is noted in seconds
		 * - If 0 is specified, it will never expire
		 *
		 * @param string $variableName The name of the variable to be stored to session
		 * @param mixed $value Variable value to be stored to session
		 * @param integer $expiry When will the variable expire (OPTIONAL, default = 0 -- Not expiring)
		 *
		 * @return boolean Returns TRUE if successful, FALSE otherwise
		 *
		 * @todo variable expiry
		 */
		final static public function sessionSet($variableName, $value, $expiry = 0)
		{
			self::$_sessionReference['storage'][$variableName] = $value;
			return true;
		}

		/**
		 * Remove variable from session
		 *
		 * NOTE: If variableName cannot be found in the session, the method will return FALSE
		 *
		 * @param string $variableName The name of the variable to be removed from the session
		 * @return boolean Returns TRUE if variable is removed successfully, FALSE otherwise
		 */
		final static public function sessionRemove($variableName)
		{
			if (!array_key_exists($variableName, self::$_sessionReference['storage'])) {
				return false;
			}

			unset(self::$_sessionReference['storage'][$variableName]);
			return true;
		}

		/**
		 * Reset session
		 *
		 * This method will wipe clean any session variables in the system.
		 * It will generate a new session ID alongside if possible.
		 *
		 * @return boolean Returns TRUE if successful, FALSE otherwise
		 */
		final static public function sessionReset()
		{
			// Generating new session ID is only possible when
			// the server have not send out any output.
			if (session_id() && !headers_sent()) {
				session_regenerate_id();
			}

			self::$_sessionReference['storage'] = [];
			self::$_sessionReference['user'] = [];

			return true;
		}

		final static public function generateCSRF()
        {
            $token = bin2hex(openssl_random_pseudo_bytes(32));
            self::sessionSet('csrfToken', $token);
            return $token;
        }

		/**
		 * Destroy session
		 * @return boolean Returns TRUE if successful, FALSE otherwise
		 */
		final static public function sessionDestroy()
		{
			self::$_sessionReference = array();

			if (session_id()) {
				@session_destroy();
			}
		}

		/**
		 * Get current session ID
		 * @return string Returns current session ID
		 */
		final static public function sessionID()
		{
			$id = session_id();

			if (!$id) {
				return 'no_session_id_CLI';
			} else {
				return $id;
			}
		}
	// --------------------------------------------------------------------------------




	// --------------------------------------------------------------------------------
	// User login/logout
	//
	// All of functions that takes care of login/logout to the session.
	// Gather all of the functions in one place so that it's easier to monipulate/handle the login information
	// (including caching).
	//
	// TODO PHPDOCS
	// --------------------------------------------------------------------------------
		/**
		 * Holds user API object
		 * @var User_API Currently logged in user
		 */
		static private $_userCacheObject = false;

		/**
		 * Collection of user ID that were stacked together
		 * @var integer[] List of user ID
		 */
		static private $_userStack = array();

		/**
		 * A flag indicating whether or not user has been initialized before
		 * @var Boolean Indicates whether or not user has been initialized
		 */
		static private $_userInit = false;




		/**
		 * Initialize user related
		 * @param bool $reset Flag to indicate whether or not the procedure can reset previously initialized session
		 * @return Boolean Returns TRUE if successful, FALSE otherwise
		 */
		final static public function userInit($reset = false)
		{
			// ---- Make sure that the session is not being accidentally initialized more than once
				if (self::$_userInit && !$reset) {
					return false;
				}

				self::$_userInit = true;
			// -----

			self::$_userCacheObject = false;
			self::$_userStack = self::sessionGet('__IEM_SYSTEM_CurrentUser_Stack', array());

			return true;
		}

		/**
		 * Login to the system
		 * Login to the system with specified user ID
		 *
		 * NOTE: This will reset session variables and possibly session ID too
		 *
		 * @param integer $userid Login with this user ID
		 * @param boolean $recordLastLogin Whether or not to record last login
		 * @param boolean $clearStack Whether or not to clear login stack
		 *
		 * @return boolean Returns TRUE if successful, FALSE othwerwise
		 */
		final static public function userLogin($userid, $recordLastLogin = true, $clearStack = false)
		{
			$userid = intval($userid);
			if (empty($userid)) {
				return false;
			}

			$user = GetUser($userid);
			if (!$user) {
				return false;
			}

			// Do we want to record this?
			if ($recordLastLogin) {
				$rand_check = uniqid(true);

				$user->settings['LoginCheck'] = $rand_check;
				$user->SaveSettings();

				$user->UpdateLoginTime();
			}

			if ($clearStack) {
				self::$_userStack = array();
			}

			self::$_userStack[] = $user->userid;
			self::$_userCacheObject = $user;

			// ----- Save user information into session
				IEM::sessionReset();
				IEM::sessionSet('__IEM_SYSTEM_CurrentUser_Stack', self::$_userStack);
			// -----

			return true;
		}

		/**
		 * Log user out of the system
		 *
		 * NOTE: If the $completeLogout parameter is NOT specified, the application
		 * will NOT log out ALL users. The application will use the next user ID in the stack
		 * (unless the stack is empty).
		 *
		 * @param boolean $completeLogout Whether or not to logout all users in the stack
		 * @return boolean Returns TRUE if user is loggout successfuly, FALSE otherwise
		 */
		final static public function userLogout($compleLogout = false)
		{
			if (empty(self::$_userStack)) {
				return false;
			}

			if ($compleLogout) {
				self::$_userStack = array();
			} else {
				array_pop(self::$_userStack);
			}

			self::userFlushCache();
			return IEM::sessionSet('__IEM_SYSTEM_CurrentUser_Stack', self::$_userStack);
		}

		/**
		 * Flush user record cache
		 */
		final static public function userFlushCache()
		{
			self::$_userCacheObject = false;
		}

		/**
		 * TODO all
		 */
		final static public function userGetStack($object = false)
		{

		}

		/**
		 * Get current user
		 * Get currently loggedin user
		 *
		 * @return User_API|FALSE Returns currently logged in user object if any, FALSE if current user have not logged in
		 */
		final static public function userGetCurrent()
		{
			if (!self::$_userCacheObject instanceof User_API ) {
				$userStack = self::$_userStack;
				if (empty($userStack)) {
					return false;
				}

				$userID = array_pop($userStack);
				$currentUser = new User_API($userID);

				if ($currentUser->userid != $userID) {
					return false;
				}

				self::$_userCacheObject = $currentUser;
			}

			return self::$_userCacheObject;
		}
	// --------------------------------------------------------------------------------




	// --------------------------------------------------------------------------------
	// Requests functions
	//
	// Provides a way to fetched request variables (ie. POST or GET variables)
	// in a "safe" and convinient manner
	// --------------------------------------------------------------------------------
		/**
		 * requestGetPOST
		 * Get request variable from $_POST
		 *
		 * @param String $variableName Variable name
		 * @param Mixed $defaultValue Default value if variable not found
		 * @param String $callback Callback function to be applied to the returned value
		 *
		 * @return Mixed Return variable value from $_POST if it exists, otherwise it will return defaultValue
		 */
		static public function requestGetPOST($variableName, $defaultValue = '', $callback = null)
		{
			$value = $defaultValue;

			if (isset($_POST) && array_key_exists($variableName, $_POST)) {
				$value = $_POST[$variableName];
			}

			return self::_requestProcess($value, $callback);
		}

		/**
		 * requestGetGET
		 * Get request variable from $_GET
		 *
		 * @param String $variableName Variable name
		 * @param Mixed $defaultValue Default value if variable not found
		 * @param String $callback Callback function to be applied to the returned value
		 *
		 * @return Mixed Return variable value from $_POST if it exists, otherwise it will return defaultValue
		 */
		static public function requestGetGET($variableName, $defaultValue = '', $callback = null)
		{
			$value = $defaultValue;

			if (isset($_GET) && array_key_exists($variableName, $_GET)) {
				$value = $_GET[$variableName];
			}

			return self::_requestProcess($value, $callback);
		}

		/**
		 * _requestProcess
		 * Process request variable
		 *
		 * @param Mixed $variable Variable to be processed
		 * @param String $callback Callback function
		 *
		 * @return Mixed Return the processed variable
		 */
		static private function _requestProcess($variable, $callback = null)
		{
			if (empty($callback)) {
				return $variable;
			}

			if (is_array($variable)) {
				foreach ($variable as &$each) {
					$each = self::_requestProcess($each, $callback);
				}

				return $variable;
			} else {
				return $callback($variable);
			}
		}
	// --------------------------------------------------------------------------------




	// --------------------------------------------------------------------------------
	// Functions that will handle "licensing"
	// --------------------------------------------------------------------------------

	// --------------------------------------------------------------------------------



	// --------------------------------------------------------------------------------
	// Functions that will handle "language" (i18n)
	// TODO all
	// --------------------------------------------------------------------------------
		static private $_langLoaded = array();

		static public function langLoad($language)
		{
			$user = IEM::userGetCurrent();
			$users_language = 'default';
			$language = strtolower($language);

			// If it has been loaded before, return
			if (in_array($language, self::$_langLoaded)) {
				return true;
			}

			// If user have their own language preference, use that
			if (!empty($user->user_language)) {
				$users_language = $user->user_language;
			}

			// If their language preference not available, we use the default
			if (empty($users_language) || !is_dir(IEM_PATH . "/language/{$users_language}")) {
				$users_language = 'default';
			}

			// ----- Include language file
				$langfile = IEM_PATH . "/language/{$users_language}/{$language}.php";
				if (!is_file($langfile)) {
					trigger_error("No Language file for {$language} area", E_USER_WARNING);
					return false;
				}

				include_once $langfile;
			// -----

			self::$_langLoaded[] = $language;

			return true;
		}

		static public function langLoaded()
		{

		}

		static public function langGet($section, $name)
		{
			if (!self::langLoad($section)) {
				return $name;
			}

			return GetLang($name);
		}
	// --------------------------------------------------------------------------------




	// --------------------------------------------------------------------------------
	// General "Framework" scope function
	// --------------------------------------------------------------------------------
		/**
		 * Get database
		 * Get the database object that is used by the framework
		 *
		 * @param Db $overwriteDB Database object to use (OPTIONAL)
		 * @return MySQLDb|FALSE Returns a concrete implementation of the database object if successful, FALSE otherwise
		 *
		 * @uses Db
		 * @uses SENDSTUDIO_DATABASE_TYPE
		 * @uses SENDSTUDIO_DATABASE_HOST
		 * @uses SENDSTUDIO_DATABASE_USER
		 * @uses SENDSTUDIO_DATABASE_PASSWORD
		 * @uses SENDSTUDIO_DATABASE_NAME
		 */
		final static public function getDatabase()
		{
			static $db = null;
            if ($db !== null) {
			    return $db;
            }

            $required_constants = [
                'SENDSTUDIO_DATABASE_HOST',
                'SENDSTUDIO_DATABASE_USER',
                'SENDSTUDIO_DATABASE_PASS',
                'SENDSTUDIO_DATABASE_NAME'
            ];

            foreach ($required_constants as $required) {
                if (!defined($required)) {
                    return false;
                }
            }

            try {
                $db = IEM_DBFACTORY::manufacture(
                    SENDSTUDIO_DATABASE_HOST,
                    SENDSTUDIO_DATABASE_USER,
                    SENDSTUDIO_DATABASE_PASS,
                    SENDSTUDIO_DATABASE_NAME,
                    [
                        'charset' => 'utf8mb4',
						'collate' =>'utf8mb4_general_ci',
                        'tablePrefix' => SENDSTUDIO_TABLEPREFIX,
                    ]
                );
                return $db;
            } catch (exception $e) {
                return false;
            }
		}

		/**
		 * getCurrentUser
		 * This function is an alias of self::userGetCurrent()
		 */
		final static public function getCurrentUser()
		{
			return self::userGetCurrent();
		}

		/**
		 * logUserActivity
		 * A static interface for logging a user activity log
		 *
		 * @param String $url URL to be logged
		 * @param String $icon Icon to be used in the log
		 * @param String $text Text to be used in the log
		 *
		 * @uses UserActivityLog
		 */
		final static public function logUserActivity($url, $icon = '', $text = '')
		{
			static $activitylog = null;

			if (is_null($activitylog)) {
				require_once IEM_PUBLIC_PATH . '/functions/api/useractivitylog.php';
				$activitylog = new UserActivityLog_API();
			}

			$status = $activitylog->LogActivity($url, $icon, $text);
			if (!$status) {
				trigger_error('Unable to log activity', E_USER_NOTICE);
			}
		}

		/**
		 * urlFor
		 * Generates a URL for an internal IEM page based on the parameters.
		 *
		 * @param String $page The page to redirect to (e.g. 'Lists').
		 * @param array $params An associative array of param name => param value pairs that will be added to the GET request.
		 * @param Boolean $relative Whether the URL should be start with "index.php?Page=..." (true) or contain the domain, etc. (false).
		 *
		 * @return String The URL generated from the parameters.
		 */
		final static public function urlFor($page, $params=array(), $relative=true)
		{
			$base_url = 'index.php';
			if (!$relative) {
				$base_url = SENDSTUDIO_APPLICATION_URL . '/admin/' . $base_url;
			}
			$url = $base_url . '?Page=' . urlencode($page);
			if (is_array($params) && count($params)) {
				foreach ($params as $key=>$value) {
					$url .= '&' . urlencode($key) . '=' . urlencode($value);
				}
			}
			return $url;
		}

		/**
		 * redirectTo
		 * Outputs a redirect header and dies.
		 * This means it has to be called before any output on the page has started.
		 *
		 * @param String $page The page to redirect to (e.g. 'Lists').
		 * @param array $params An associative array of param name => param value pairs that will be added to the GET request.
		 *
		 * @return Void Does not return anything.
		 */
		final static public function redirectTo($page, $params=array())
		{
			$url = self::urlFor($page, $params, false);

			if (!headers_sent()) {
				header("Location: {$url}");
				exit();
			}

			echo "<script>window.location.href = '{$url}';</script>";
			exit();
		}

		/**
		 * ifsetor
		 * Returns the value of a variable if the variable has been set, or a default fallback.
		 * This is useful for pulling a value out of an associative array when the key may not be in the array.
		 *
		 * @param Mixed $var The variable to check is set.
		 * @param Mixed $default The default fallback value to return if $var is not set.
		 *
		 * @see http://wiki.php.net/rfc/ifsetor
		 *
		 * @return Mixed $var if $var is set, otherwise the value of $default.
		 */
		final static public function ifsetor(&$var, $default=null)
		{   
			if (isset($var)) {
				$tmp = $var;
			} else {
				$tmp = $default;
			}
			return $tmp;
		}

		/**
		 * encrypt
		 * Performs a basic XOR encryption.
		 * This is useful to use with the RandomToken session variable.
		 *
		 * @see decrypt
		 *
		 * @param $s string The string to encrypt.
		 * @param $key string The key to encrypt it with.
		 *
		 * @return string|boolean The encrypted string (cipher) or false on error.
		 */
		final static public function encrypt($s, $key)
		{
			if (empty($s) || empty($key)) {
				return false;
			}
			while (strlen($key) < strlen($s)) {
				$key .= $key;
			}
			return $s ^ $key;
		}

		/**
		 * decrypt
		 * Performs a basic XOR decryption.
		 *
		 * @see encrypt
		 *
		 * @param string $s The cipher to decrypt.
		 * @param string $key The key to descrypt it with.
		 *
		 * @return string The decrypted string.
		 */
		final static public function decrypt($s, $key)
		{
			// Since this is XOR encryption A ^ B = C, so C ^ B = A.
			return self::encrypt($s, $key);
		}

		/**
		 * Get display string for time according to user's timezone
		 *
		 * @param string $format Date format
		 * @param integer $time Time to format to user timezone (this have to be in GMT time -- ie. time())
		 * @return string Returns formatted time according
		 */
        final static public function timeGetUserDisplayString($format = null, $time = null)
		{
			if (is_null($format)) {
				$format = GetLang('TimeFormat', 'F j Y, g:i a');
			}

			if (is_null($time)) {
				$time = time();
			}

			$offset = 0;
			$user = self::getCurrentUser();

			if ($user instanceof User_API) {
				if (preg_match('/GMT(\-|\+)(\d+)\:(\d+)/', $user->usertimezone, $matches)) {
					list(, $tempSign, $tempHour, $tempMinute) = $matches;

					$offset = ($tempHour * 3600) + ($tempMinute * 60);

					if ($tempSign == '-') {
						$offset *= -1;
					}

				}

				if (preg_match('/(\-|\+)(\d+)\:(\d+)/', date('P'), $matches)) {
					list(, $tempSign, $tempHour, $tempMinute) = $matches;

					$tempOffset = ($tempHour * 3600) + ($tempMinute * 60);

					if ($tempSign == '-') {
						$tempOffset *= -1;
					}

					$offset -= $tempOffset;
				}
			}

			return date($format, ($time + $offset));
	}
	// --------------------------------------------------------------------------------

	/**
	 * Checks to see if the application is installed at all.
	 *
	 * @return bool
	 */
	final static public function isInstalled()
	{
	    // legacy: if this is defined, then it is installed
	    if (defined('SENDSTUDIO_IS_SETUP') && SENDSTUDIO_IS_SETUP) {
	        return true;
	    }

	    $configFile = realpath(dirname(__FILE__) . '/../../includes/config.php');
	    $hasConfig  = is_file($configFile);

	    // if there is no config file, it is assumed to be "not installed"
	    if (!$hasConfig) {
	        return false;
	    }

	    // legacy: check to see if there are any defenitions in the config file
	    return (bool) preg_match('/define/', file_get_contents($configFile));
	}

	/**
	 * Checks to see if the application has any upgrades that need to be run.
	 *
	 * @return bool
	 */
	final static public function hasUpgrade()
	{
	    $db = IEM::getDatabase();

	    // if there is no database yet, return false
	    if (!$db) {
	        return false;
	    }

	    $res = $db->Query('SELECT * FROM [|PREFIX|]settings;');

	    if (!$res) {
	        return false;
	    }

	    $settings   = $db->Fetch($res);
	    $newVersion = (int) self::DATABASE_VERSION;
	    $oldVersion = (int) $settings['database_version'];

	    return $newVersion > $oldVersion;
	}

	/**
	 * Returns whether the application is installing.
	 *
	 * @return bool
	 */
	final static public function isInstalling()
	{
	    if (!isset($_GET['Page'])) {
	        return false;
	    }

	    $page = strtolower($_GET['Page']);

	    return
	        $page === 'install'
	        || $page === 'installer';
	}

	/**
	 * Returns whether the application is upgrading.
	 *
	 * @return bool
	 */
	final static public function isUpgrading()
	{
	    if (!isset($_GET['Page'])) {
	        return false;
	    }

	    return strtolower($_GET['Page']) === 'upgradenx';
	}

	/**
	 * Returns whether the application is on the "upgrade completed" screen.
	 *
	 * @return bool
	 */
	final static public function isCompletingUpgrade()
	{
	    return isset($_GET['Step']) && $_GET['Step'] == 3;
	}
}

class exceptionIEM extends Exception
{

}
