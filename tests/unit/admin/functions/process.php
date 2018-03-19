<?php
require_once(dirname(__FILE__) . '/_common.php');
require_once(UNITTEST_IEM_BASE_DIRECTORY . '/admin/functions/process.php');
require_once(UNITTEST_IEM_BASE_DIRECTORY . '/internals/IEMLicense.class.php');

class admin_functions_process extends PHPUnit_Framework_TestCase
{
	public function test_license_object_localhost_default()
	{
		$lobj = new LICENSE();
		$cobj = new IEMLicense();

		$version = preg_replace('/^(\d+\.\d+)\.\d+/', '${1}${2}', IEM::VERSION);

		$cobj->host = 'localhost';
		$cobj->version = $version;
		$key = $cobj->licenseGet();

		$lobj->DecryptKey($key);

		$this->assertEquals($cobj->host_md5, $lobj->GetDomain());
		$this->assertEquals(0, $lobj->GetUsers());
		$this->assertEquals('', $lobj->GetEdition());
		$this->assertEquals('', $lobj->GetExpires());
		$this->assertEquals(0, $lobj->GetLists());
		$this->assertEquals(0, $lobj->GetSubscribers());
		$this->assertEquals($version, $lobj->GetVersion());
		$this->assertEquals(false, $lobj->GetNFR());

		if (!$lobj->GetAgencyId()) {
			return;
		}

		$this->assertEquals('', $lobj->GetAgencyID());
		$this->assertEquals(0, $lobj->GetTrialAccountLimit());
		$this->assertEquals($cobj->trialemails, $lobj->GetTrialAccountEmail());
		$this->assertEquals($cobj->trialdays, $lobj->GetTrialAccountDays());
		$this->assertEquals(-1, $lobj->GetPingbackDays());
		$this->assertEquals(0, $lobj->GetPingbackGrace());
	}

	public function test_license_object_localhost_agency()
	{
		$lobj = new LICENSE();
		$cobj = new IEMLicense();

		$version = preg_replace('/^(\d+\.\d+)\.\d+/', '${1}${2}', IEM::VERSION);
		$agencyid = 'TEstAgencyID108i293';

		$cobj->host = 'localhost';
		$cobj->version = $version;
		$cobj->agencyid = $agencyid;
		$key = $cobj->licenseGet();

		$lobj->DecryptKey($key);
		
		if (!$lobj->GetAgencyId()) {
			$this->markTestSkipped("Test is only for agency edition.");
		}

		$this->assertEquals($cobj->host_md5, $lobj->GetDomain());
		$this->assertEquals(0, $lobj->GetUsers());
		$this->assertEquals('', $lobj->GetEdition());
		$this->assertEquals('', $lobj->GetExpires());
		$this->assertEquals(0, $lobj->GetLists());
		$this->assertEquals(0, $lobj->GetSubscribers());
		$this->assertEquals($version, $lobj->GetVersion());
		$this->assertEquals(false, $lobj->GetNFR());
		$this->assertEquals($agencyid, $lobj->GetAgencyID());
		$this->assertEquals(0, $lobj->GetTrialAccountLimit());
		$this->assertEquals($cobj->trialemails, $lobj->GetTrialAccountEmail());
		$this->assertEquals($cobj->trialdays, $lobj->GetTrialAccountDays());
		$this->assertEquals(-1, $lobj->GetPingbackDays());
		$this->assertEquals(0, $lobj->GetPingbackGrace());
	}

	public function test_license_object_localhost_normal_userlimit()
	{
		$lobj = new LICENSE();
		$cobj = new IEMLicense();

		$version = preg_replace('/^(\d+\.\d+)\.\d+/', '${1}${2}', IEM::VERSION);

		$cobj->host = 'localhost';
		$cobj->version = $version;
		$cobj->users = 10;
		$cobj->trialusers = 100;
		$key = $cobj->licenseGet();

		$lobj->DecryptKey($key);

		$this->assertEquals($cobj->host_md5, $lobj->GetDomain());
		$this->assertEquals(10, $lobj->GetUsers());
		$this->assertEquals('', $lobj->GetEdition());
		$this->assertEquals('', $lobj->GetExpires());
		$this->assertEquals(0, $lobj->GetLists());
		$this->assertEquals(0, $lobj->GetSubscribers());
		$this->assertEquals($version, $lobj->GetVersion());
		$this->assertEquals(false, $lobj->GetNFR());

		if (!$lobj->GetAgencyId()) {
			return;
		}

		$this->assertEquals('', $lobj->GetAgencyID());
		$this->assertEquals(0, $lobj->GetTrialAccountLimit());
		$this->assertEquals($cobj->trialemails, $lobj->GetTrialAccountEmail());
		$this->assertEquals($cobj->trialdays, $lobj->GetTrialAccountDays());
		$this->assertEquals(-1, $lobj->GetPingbackDays());
		$this->assertEquals(0, $lobj->GetPingbackGrace());
	}

	public function test_license_object_localhost_agency_userlimit()
	{
		$lobj = new LICENSE();
		$cobj = new IEMLicense();

		$version = preg_replace('/^(\d+\.\d+)\.\d+/', '${1}${2}', IEM::VERSION);
		$agencyid = 'TEstAgencyID108i293';

		$cobj->host = 'localhost';
		$cobj->version = $version;
		$cobj->agencyid = $agencyid;
		$cobj->users = 10;
		$cobj->trialusers = 100;
		$key = $cobj->licenseGet();

		$lobj->DecryptKey($key);
		
		if (!$lobj->GetAgencyId()) {
			$this->markTestSkipped("Test is only for agency edition.");
		}

		$this->assertEquals($cobj->host_md5, $lobj->GetDomain());
		$this->assertEquals(10, $lobj->GetUsers());
		$this->assertEquals('', $lobj->GetEdition());
		$this->assertEquals('', $lobj->GetExpires());
		$this->assertEquals(0, $lobj->GetLists());
		$this->assertEquals(0, $lobj->GetSubscribers());
		$this->assertEquals($version, $lobj->GetVersion());
		$this->assertEquals(false, $lobj->GetNFR());
		$this->assertEquals($agencyid, $lobj->GetAgencyID());
		$this->assertEquals(100, $lobj->GetTrialAccountLimit());
		$this->assertEquals($cobj->trialemails, $lobj->GetTrialAccountEmail());
		$this->assertEquals($cobj->trialdays, $lobj->GetTrialAccountDays());
		$this->assertEquals(-1, $lobj->GetPingbackDays());
		$this->assertEquals(0, $lobj->GetPingbackGrace());
	}
}
