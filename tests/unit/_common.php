<?php
require_once 'PHPUnit/Framework.php';
define('UNITTEST_BASE_DIRECTORY', dirname(__FILE__));
define('UNITTEST_IEM_BASE_DIRECTORY', dirname(dirname(dirname(__FILE__))));
define('UNITTEST_IEM_ADMIN_DIRECTORY', UNITTEST_IEM_BASE_DIRECTORY . '/admin');

// Make sure that the IEM controller does NOT redirect request.
if (!defined('IEM_NO_CONTROLLER')) {
	define('IEM_NO_CONTROLLER', true);
}

require_once UNITTEST_IEM_ADMIN_DIRECTORY . '/index.php';
