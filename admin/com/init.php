<?php
/**
 * This file contains the main procedure and classes for IEM to run.
 *
 * Every files or call need to include this file one way or another.
 * There are constants that can be define which will change the startup behaviour of the application.
 *
 * A full list of constants which can influence the application when defined are:
 * - IEM_NO_CONTROLLER => Will prevent controller from being invoked by initialization process
 * - IEM_NO_SESSION => This will prevent session files from being created. You will still be able to use IEM::session*, but it will not be presisted accross requests
 * - IEM_CLI_MODE => The script is being run from the command line. REQUEST_URI will not be initialised.
 * - IEM_CRON_JOB => The script was invoked through a cron job. Some sending behaviour is different depending on cron vs manual sending. IEM_CLI_MODE will be assumed.
 *
 *
 * @package interspire.iem
 *
 * @authors Chris Smith
 * @authors Scott Tedmanson
 * @authors Tye Shavik
 * @authors Hendri Kurniawan
 * @authors Michael Knight
 * @authors David Chandra
 * @authors Yudi Tukiaty
 * @authors Micheline Nakhle
 * @authors John Tuck
 * @authors Imran Khan
 */

/**
 * IEM auto loading function defined using spl_autoload_register.
 *
 * @param string $className
 */
function __iem_autoload_function($className)
{
	if (class_exists($className, false)) {
		return;
	}

	// parse the path from the class name
	$paths = [
        __DIR__ . '/lib/' . str_replace('_', '/', $className),
        dirname(__DIR__) . '/functions/lib/' . str_replace('_', '/', $className),
    ];

    foreach ($paths as $path) {
        /*
         * The .class.php naming convention is now deprecated.
         */
        if (is_readable($path . '.class.php')) {
            require $path . '.class.php';
        } elseif (is_readable($path . '.php')) {
            require $path . '.php';
        }
    }
}

/**
 * Shutdown function
 * Function that will be called when the script finishes execution.
 */
function __iem_shutdown_function()
{
	$tempEvent = new EventData_IEM_SYSTEM_SHUTDOWN_BEFORE();
	$tempEvent->trigger();
	unset($tempEvent);

	if (!IEM::configSave()) {
		trigger_error('Cannot save configuration variable to file', E_USER_WARNING);
	}

	$tempEvent = new EventData_IEM_SYSTEM_SHUTDOWN_AFTER();
	$tempEvent->trigger();
	unset($tempEvent);
}

/**
 * Exception handler
 * Function that handle rouge exception
 *
 * @param Exception $e Exception that needs to be handled
 */
function __iem_exception_handler(Throwable $e)
{
	// Make sure IEM_PATH is defined
	if (!defined('IEM_PATH')) {
		define('IEM_PATH', dirname(__FILE__));
	}

	$title	= 'The application cannot proceed!';
	$header	= file_get_contents(IEM_PATH . '/templates/upgrade_header.tpl');
	$body	= file_get_contents(IEM_PATH . '/templates/upgrade_body.tpl');
	$footer	= file_get_contents(IEM_PATH . '/templates/upgrade_footer.tpl');
	$msg	= '<p>An internal error occured. Please contact your administrator and describe the steps you took before you encounter this error message.</p><p>Error msg: ' . htmlentities($e->getMessage(), ENT_QUOTES) . '</p>';

	if (class_exists('InterspireStashException', false) && $e instanceof InterspireStashException && in_array($e->getCode(), array(InterspireStashException::CANNOT_READ_DATA, InterspireStashException::CANNOT_WRITE_DATA))) {
		$msg  = '<p>';
		$msg .= 'Please make sure the following directory (including its contents) are writable:';
		$msg .= '<ul><li>' . preg_replace('/\s/', '&nbsp;', htmlentities(realpath(IEM_PATH . '/storage'), ENT_QUOTES)) . '</li></ul>';
		$msg .= '</p>';
		$msg .= '<p><input type=\'button\' value=\'Try Again\' style=\'margin-bottom:20px; font-size:11px\' onclick="document.location.href=\'./index.php\'" /></p>';
	}

	// manually replace the tokens
	$header = str_replace('%%LNG_ControlPanel%%', $title, $header);
	$header = str_replace('%%GLOBAL_CHARSET%%', 'UTF-8', $header);
	$body   = str_replace('{$title}', $title, $body);
	$body   = str_replace('{$msg}', $msg, $body);
	$footer = str_replace('%%LNG_Copyright%%', '', $footer);

	echo $header . $body . $footer;
	
	exit(1);
}

define('SENDSTUDIO_DISABLED_FUNCTIONS', ini_get('disable_functions'));

$disabled_functions = explode(',', SENDSTUDIO_DISABLED_FUNCTIONS);

if (in_array('ini_set', $disabled_functions)) {
	$turn_off_message = "The 'ini_set' function has been disabled by your systems administrator or website host.\nThe application requires this function to be active, please contact your systems administrator or website host to have it enabled again.\n";
	$turn_off_message .= "It will have to be removed from the 'disabled_functions' line in the php.ini file.\n";

	die(nl2br($turn_off_message));
}

// Avoid iframe loading. It prevents IEM app embedded into other sites.
header("X-Frame-Options: SAMEORIGIN");

// Set up PHP environment
error_reporting(E_ALL);

ini_set('short_tags', false);
$memlimit = ini_get('memory_limit');
$memlimit = strtolower($memlimit);
$memlimit = trim($memlimit,"m");
if((int)$memlimit < 64 ){
    ini_set('memory_limit', '64M');
}
ini_set('track_errors', true);
ini_set('magic_quotes_sybase', false);

date_default_timezone_set(date_default_timezone_get());

if (!defined('IEM_CLI_MODE')) {
	define('IEM_CLI_MODE', (defined('IEM_CRON_JOB') || php_sapi_name() == 'cli'));
}

// Initialize any environment that is CLI related
if (IEM_CLI_MODE) {


// Initialize any environment for other than CLI
} else {
	// With IIS, REQUEST_URI is not available, so we have to construct it.
	if (!isset($_SERVER['REQUEST_URI'])) {
		// The following variable will be set on IIS servers that have ISAPI_Rewrite:
		if (isset($_SERVER['HTTP_X_REWRITE_URL'])) {
			$_SERVER['REQUEST_URI'] = $_SERVER['HTTP_X_REWRITE_URL'];
		}
		// This should be an acceptable fallback:
		if (!isset($_SERVER['REQUEST_URI']) || empty($_SERVER['REQUEST_URI'])) {
			$_SERVER['REQUEST_URI'] = $_SERVER['SCRIPT_NAME'] . '?' . (isset($_SERVER['QUERY_STRING']) ? $_SERVER['QUERY_STRING'] : '');
		}
	}
	// It's a similar story with DOCUMENT_ROOT for IIS.
	if (!isset($_SERVER['DOCUMENT_ROOT'])) {
		// We need to be careful about which vars are available.
		if (isset($_SERVER['PATH_INFO'])) {
			$local_path = $_SERVER['PATH_INFO'];
		} else {
			$local_path = $_SERVER['SCRIPT_NAME'];
		}
		if (isset($_SERVER['PATH_TRANSLATED'])) {
			$absolute_path = $_SERVER['PATH_TRANSLATED'];
		} else {
			$absolute_path = $_SERVER['SCRIPT_FILENAME'];
		}
		$local_path = str_replace('/', DIRECTORY_SEPARATOR, $local_path);
		$_SERVER['DOCUMENT_ROOT'] = substr($absolute_path, 0, strpos($absolute_path, $local_path));
	}
}

// Define a constant path that is available in the application
// Load any customization made in the whitelabel directory
$tempFileName = dirname(__FILE__) . '/custom/init-path.php';
if (is_readable($tempFileName)) {
	include $tempFileName;
}
unset ($tempFileName);

if (!defined('IEM_PATH')) {
	define('IEM_PATH', dirname(__FILE__));
}

if (!defined('IEM_STORAGE_PATH')) {
	define('IEM_STORAGE_PATH', IEM_PATH . '/storage');
}

if (!defined('IEM_PUBLIC_PATH')) {
	define('IEM_PUBLIC_PATH', dirname(IEM_PATH));
}

if (!defined('IEM_ADDONS_PATH')) {
	define('IEM_ADDONS_PATH', IEM_PUBLIC_PATH . '/addons');
}

// Set spl_autoload, error and exception handler
spl_autoload_register('__iem_autoload_function');
set_exception_handler('__iem_exception_handler');

require_once IEM_PATH . '/event.php';
require_once IEM_PATH . '/init-legacy.php';

$tempEvent = new EventData_IEM_SYSTEM_STARTUP_BEFORE();
$tempEvent->trigger();
unset($tempEvent);

if(IEM::configGet() !== false){
	IEM::init();
}

register_shutdown_function('__iem_shutdown_function');

$tempEvent = new EventData_IEM_SYSTEM_STARTUP_AFTER();
$tempEvent->trigger();
unset($tempEvent);

$useController = false;
if (!defined('IEM_NO_CONTROLLER') || IEM_NO_CONTROLLER == false) {
    $useController = true;
}

if (IEM_CLI_MODE && (!IEM::isInstalled() || IEM::hasUpgrade())) {
    echo 'Application not installed or requires upgrade';
    exit(1);
}

if (!IEM::isInstalled() && !IEM::isInstalling()) {
    header('Location: index.php?Page=install');
    exit();
}

if (IEM::isInstalling()) {
    require_once SENDSTUDIO_FUNCTION_DIRECTORY . "/installer.php";
    $system = new Installer();
    $system->Process();
    exit();
}

if (IEM::hasUpgrade() && !IEM::isUpgrading()) {
    header('Location: index.php?Page=upgradenx');
    exit();
}

if (IEM::isUpgrading()) {
    require_once SENDSTUDIO_FUNCTION_DIRECTORY . "/upgradenx.php";
    $system = new UpgradeNX();
    $system->Process();
    exit();
}

ss9O24kwehbehb();

if ($useController) {
    if (!IEM::getCurrentUser() instanceOf User_API) {
        controller('login');
    } else {
        if (getNewPage() === 'AdminTools') {
            handleAdminTools();
        } else {
            $page = getPage();
            $non_allowable_pages = [
                'init',
                'sendstudio_functions',
                'install',
                'installer',
                'upgrade',
                'upgradenx',
                'login',
            ];
            if (in_array($page, $non_allowable_pages)) {
                IEM::redirectTo('index.php');
            } else {
                controller($page);
            }
        }
    }
    checkLogSystem();
    exit();
}

//-------------------------------------------------------------------

function controller($page)
{
    $url_parts = parse_url(SENDSTUDIO_APPLICATION_URL);
    $host = $url_parts['host'];

    if (isset($url_parts['port'])) {
        $host .= ':' . $url_parts['port'];
    }

    if ($host != $_SERVER['HTTP_HOST']) {
        header('Location: ' . SENDSTUDIO_APPLICATION_URL . '/admin/index.php');
        return;
    }

    handlePage($page);
}

function getNewPage()
{
    $newPage = IEM::requestGetGET('page', '');
    return preg_replace('/[^\w]/', '_', $newPage);
}

function getPage()
{
    $page = IEM::requestGetGET('Page', '', 'strtolower');
    $page = preg_replace('/[^\w]/', '_', $page);
    return is_file(SENDSTUDIO_FUNCTION_DIRECTORY."/{$page}.php") ? $page : 'index';
}

function handlePage($page)
{
    require_once SENDSTUDIO_FUNCTION_DIRECTORY . "/{$page}.php";
    $system = new $page();
    $system->Process();
    unset($system);
}

function handleAdminTools()
{
    require_once IEM_PATH . "/pages/AdminTools.class.php";

    $tempAction = 'page_' . preg_replace('/[^\w]/', '_', IEM::requestGetGET('action', 'index'));
    $adminTools = new page_AdminTools();

    if (!is_callable([$adminTools, $tempAction])) {
        $tempAction = 'page_index';
    }

    $adminTools->{$tempAction}();
}

function checkLogSystem()
{
    $logsystem = GetLogSystem();
    if ($logsystem) {
        $logsystem->PruneSystemLog();
        $logsystem->PruneAdminLog();
        unset($logsystem);
    }
}
