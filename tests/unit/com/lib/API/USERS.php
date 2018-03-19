<?php
require_once(dirname(__FILE__) . '/_common.php');

class com_lib_API_USERS extends PHPUnit_Framework_TestCase
{
	/**
	 * Warning system will return an empty string because user
	 * haven't reached the warning threshold yet.
	 */
	public function test_CreditWhichWarning_ReturnEmpty_1()
	{
		$userobject = new record_Users();
		$userobject->credit_warning_time = null;
		$userobject->credit_warning_percentage = null;

		$warnings = $this->_getEmulatedWarningRecords();
		$actual = API_USERS::creditWhichWarning($userobject, 100, $warnings);
		$expected = array();

		$this->assertEquals($expected, $actual);
	}

	/**
	 * Warning system will return an empty string because
	 * warning for that threshold has already been sent out.
	 */
	public function test_CreditWhichWarning_ReturnEmpty_2()
	{
		$userobject = new record_Users();
		$userobject->credit_warning_time = mktime(0, 0, 0, date('n'), 1, date('Y'));
		$userobject->credit_warning_percentage = 75;

		$warnings = $this->_getEmulatedWarningRecords();
		$actual = API_USERS::creditWhichWarning($userobject, 51, $warnings);
		$expected = array();

		$this->assertEquals($expected, $actual);
	}

	/**
	 * Warning system will return an empty string because
	 * all warning threshold has been superseded.
	 */
	public function test_CreditWhichWarning_ReturnEmpty_3()
	{
		$userobject = new record_Users();
		$userobject->credit_warning_time = mktime(0, 0, 0, date('n'), 1, date('Y'));
		$userobject->credit_warning_percentage = 0;

		$warnings = $this->_getEmulatedWarningRecords();
		$actual = API_USERS::creditWhichWarning($userobject, 25, $warnings);
		$expected = array();

		$this->assertEquals($expected, $actual);
	}

	/**
	 * Warning system will return an appropriate warning record
	 * because threshold has been reached, and user record indicate
	 * that no warnings have yet to sent to this user at all.
	 */
	public function test_CreditWhichWarning_Return_1()
	{
		$userobject = new record_Users();
		$userobject->credit_warning_time = null;
		$userobject->credit_warning_percentage = null;

		$warnings = $this->_getEmulatedWarningRecords();
		$actual = API_USERS::creditWhichWarning($userobject, 75, $warnings);
		$expected = $warnings[1];

		$this->assertEquals($expected, $actual);
	}

	/**
	 * Warning system will return an appropriate warning record
	 * because threshold has been reached, and user record indicate
	 * that no warnings have yet to sent to this user this month
	 */
	public function test_CreditWhichWarning_Return_2()
	{
		$userobject = new record_Users();
		$userobject->credit_warning_time = mktime(0, 0, 0, (date('n') - 1), 1, date('Y')); // last month
		$userobject->credit_warning_percentage = 25;

		$warnings = $this->_getEmulatedWarningRecords();
		$actual = API_USERS::creditWhichWarning($userobject, 75, $warnings);
		$expected = $warnings[1];

		$this->assertEquals($expected, $actual);
	}

	/**
	 * Warning system will return an appropriate warning record
	 * because threshold has been reached, and user record indicate
	 * that only the first warning have been sent to this user this month.
	 */
	public function test_CreditWhichWarning_Return_3()
	{
		$userobject = new record_Users();
		$userobject->credit_warning_time = mktime(0, 0, 0, date('n'), 1, date('Y'));
		$userobject->credit_warning_percentage = 75;

		$warnings = $this->_getEmulatedWarningRecords();
		$actual = API_USERS::creditWhichWarning($userobject, 50, $warnings);
		$expected = $warnings[0];

		$this->assertEquals($expected, $actual);
	}

	/**
	 * Border case: not sending, it's just above the required threshold
	 */
	public function test_CreditWhichWarning_BorderCase_1()
	{
		$userobject = new record_Users();
		$userobject->credit_warning_time = mktime(0, 0, 0, date('n'), 1, date('Y'));
		$userobject->credit_warning_percentage = null;

		$warnings = $this->_getEmulatedWarningRecords();
		$actual = API_USERS::creditWhichWarning($userobject, 76, $warnings);
		$expected = array();

		$this->assertEquals($expected, $actual);
	}

	/**
	 * Border case: not sending, it's just above the required threshold for the second threshold to take affect
	 */
	public function test_CreditWhichWarning_BorderCase_2()
	{
		$userobject = new record_Users();
		$userobject->credit_warning_time = mktime(0, 0, 0, date('n'), 1, date('Y'));
		$userobject->credit_warning_percentage = 75;

		$warnings = $this->_getEmulatedWarningRecords();
		$actual = API_USERS::creditWhichWarning($userobject, 51, $warnings);
		$expected = array();

		$this->assertEquals($expected, $actual);
	}

	/**
	 * Border case: sending, on the threshold
	 */
	public function test_CreditWhichWarning_BorderCase_3()
	{
		$userobject = new record_Users();
		$userobject->credit_warning_time = mktime(0, 0, 0, date('n'), 1, date('Y'));
		$userobject->credit_warning_percentage = null;

		$warnings = $this->_getEmulatedWarningRecords();
		$actual = API_USERS::creditWhichWarning($userobject, 75, $warnings);
		$expected = $warnings[1];

		$this->assertEquals($expected, $actual);
	}

	/**
	 * Border case: sending, on the second level threshold
	 */
	public function test_CreditWhichWarning_BorderCase_4()
	{
		$userobject = new record_Users();
		$userobject->credit_warning_time = mktime(0, 0, 0, date('n'), 1, date('Y'));
		$userobject->credit_warning_percentage = 75;

		$warnings = $this->_getEmulatedWarningRecords();
		$actual = API_USERS::creditWhichWarning($userobject, 50, $warnings);
		$expected = $warnings[0];

		$this->assertEquals($expected, $actual);
	}

	/**
	 * Border case: sending, on the third level threshold
	 */
	public function test_CreditWhichWarning_BorderCase_5()
	{
		$userobject = new record_Users();
		$userobject->credit_warning_time = mktime(0, 0, 0, date('n'), 1, date('Y'));
		$userobject->credit_warning_percentage = 50;

		$warnings = $this->_getEmulatedWarningRecords();
		$actual = API_USERS::creditWhichWarning($userobject, 25, $warnings);
		$expected = $warnings[2];

		$this->assertEquals($expected, $actual);
	}

	/**
	 * Border case: not sending, last threshold already reached
	 */
	public function test_CreditWhichWarning_BorderCase_6()
	{
		$userobject = new record_Users();
		$userobject->credit_warning_time = mktime(0, 0, 0, date('n'), 1, date('Y'));
		$userobject->credit_warning_percentage = 0;

		$warnings = $this->_getEmulatedWarningRecords();
		$actual = API_USERS::creditWhichWarning($userobject, 0, $warnings);
		$expected = array();

		$this->assertEquals($expected, $actual);
	}

	/**
	 * Border case: sending, last threshold
	 */
	public function test_CreditWhichWarning_BorderCase_7()
	{
		$userobject = new record_Users();
		$userobject->credit_warning_time = mktime(0, 0, 0, date('n'), 1, date('Y'));
		$userobject->credit_warning_percentage = 25;

		$warnings = $this->_getEmulatedWarningRecords();
		$actual = API_USERS::creditWhichWarning($userobject, 0, $warnings);
		$expected = $warnings[3];

		$this->assertEquals($expected, $actual);
	}

	/**
	 * This shuld return a false, since the "warnings" variable is empty
	 */
	public function test_CreditWhichWarning_Failed_2()
	{
		$userobject = new record_Users();
		$userobject->credit_warning_time = null;
		$userobject->credit_warning_percentage = null;

		$this->assertFalse(API_USERS::creditWhichWarning($userobject, 0, array()));
	}

	/**
	 * This shuld return a false, since the "warnings" variable is empty
	 */
	public function test_CreditWhichWarning_Failed_1()
	{
		$userobject = array();
		$warnings = $this->_getEmulatedWarningRecords();

		$this->assertFalse(API_USERS::creditWhichWarning($userobject, 0, $warnings));
	}




	private function _getEmulatedWarningRecords()
	{
		return array(
			array(
				'creditwarningid' => 1,
				'enabled' => '1',
				'creditlevel' => 50,
				'aspercentage' => '1',
				'emailsubject' => 'subject 1',
				'emailcontents' => 'contents 1'
			),

			array(
				'creditwarningid' => 2,
				'enabled' => '1',
				'creditlevel' => 75,
				'aspercentage' => '1',
				'emailsubject' => 'subject 2',
				'emailcontents' => 'contents 2'
			),

			array(
				'creditwarningid' => 3,
				'enabled' => '1',
				'creditlevel' => 25,
				'aspercentage' => '1',
				'emailsubject' => 'subject 3',
				'emailcontents' => 'contents 3'
			),

			array(
				'creditwarningid' => 4,
				'enabled' => '1',
				'creditlevel' => 0,
				'aspercentage' => '1',
				'emailsubject' => 'subject 4',
				'emailcontents' => 'contents 4'
			)
		);
	}
}
