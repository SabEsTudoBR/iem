<?php
require_once dirname(__FILE__) . '/_common.php';

class AllTests
{
	public static function suite()
	{
		$suite = new PHPUnit_Framework_TestSuite();
		$current_directory = dirname(__FILE__);
		
		$prefix = str_replace(DIRECTORY_SEPARATOR, '_', preg_replace('/^' . preg_quote(UNITTEST_BASE_DIRECTORY, '/') . '(.*?)/', '${1}', $current_directory));
		if (!empty($prefix)) {
			$prefix .= '_';
			if ($prefix{0} == '_') $prefix = substr($prefix, 1);
		}
	
		$scandir = scandir($current_directory);
		foreach ($scandir as $resource) {
			if (in_array($resource, array('.', '..', 'AllTests.php', '.svn'))) continue;
			if ($resource{0} == '_') continue;
		
			if (is_file("{$current_directory}/{$resource}")) {
				require_once  "{$current_directory}/{$resource}";
			
				$temp = explode('.', $resource);
				array_pop($temp);
				$class = implode($temp);
			
				$suite->addTestSuite("{$prefix}{$class}");
			} elseif (is_file("{$current_directory}/{$resource}/AllTests.php")) {
				require_once "{$current_directory}/{$resource}/AllTests.php";
		
				$suite->addTest(call_user_func(array("{$prefix}{$resource}_AllTests", 'suite')));
			}
		}
	
		return $suite;
	}
}