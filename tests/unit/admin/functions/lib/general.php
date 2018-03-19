<?php
require_once(dirname(__FILE__) . '/_common.php');

class admin_functions_lib_general extends PHPUnit_Framework_TestCase
{
	public function test_GetRealIP()
	{
		$data = array(
			'127.0.0.1' => '127.0.0.1',
			'61.88.88.88' => '61.88.88.88',
			'0.0.0.0' => '',
			'::1' => '::1', // IPv6 loopback.
			'::ffff:10.0.0.1' => '10.0.0.1', // IPv6's deprecated IPv4 compatibility mode.
			);

		foreach ($data as $test => $expected) {
			$_SERVER['REMOTE_ADDR'] = $test;
			$actual = GetRealIp();
			$this->assertEquals($expected, $actual);
		}
	}

}
