<?php
require_once dirname(__FILE__) . '/../../../../_common.php';
require_once UNITTEST_IEM_ADMIN_DIRECTORY . '/functions/api/spam_check.php';

class IEM_Tests_Unit_SpamRuleTests extends PHPUnit_Framework_TestCase
{
	protected function _normalizeSpace($text)
	{
		$text = preg_replace('/\s+/s', ' ', $text);
		return trim($text);
	}
}
