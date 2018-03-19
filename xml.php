<?php
// Make sure that the IEM controller does NOT redirect request.
if (!defined('IEM_NO_CONTROLLER')) {
    define('IEM_NO_CONTROLLER', true);
}

// Require base sendstudio functionality. This connects to the database, sets up our base paths and so on.
require_once dirname(__FILE__) . '/admin/index.php';
require_once dirname(__FILE__) . '/admin/com/xml.php';

// Set content type before anything else
header("Content-Type: text/xml");

try {
    (new xmlController())->routeRequest();
} catch (Exception $e) {
    xmlController::SendResponse(false, $e->getMessage());
}
