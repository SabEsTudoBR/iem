<?php
/**
 * Class that will allow you to create and decode a license key
 *
 * @property string $host Plaintext domain of which the license key is assigned to
 * @property string $host_md5 MD5 hash of domain of which the license key is assigned to
 * @property string $edition "Edition" that is assigned to the key
 * @property string $expiry Expiry date
 * @property string $version License key version
 * @property integer $users Number of users that the license key allow you to have (0 = means unlimited)
 * @property integer $lists The number of lists that the license key allow you to have (0 = means unlimited)
 * @property integer $subscribers The number of subscribers that the license key allow (0 = means unlimited)
 * @property boolean $nfr Whether or not this installation is an NFR
 * @property string $agencyid A unique MD5 hash that identify an agency (empty = Not agency edition)
 * @property integer $trialusers Number of trial uses that the license key allow you to have (0 = means unlimited)
 * @property integer $trialemails Number of emails a trial user can send (0 DOES NOT means unlimited)
 * @property integer $trialdays Number of days a trial user have before their account expired (0 DOES NOT means it does not expire)
 * @property integer $reactivation_days Number of days the installation need to reactivate itself (-1 = Do not need to reactivate, 0 = NOT VALID, >0 = days)
 * @property integer $reactivation_grace Number of days the installation can function without being reactivated
 */
class IEMLicense
{
	const EDITION_STARTER		= 'PARALLELS STARTER';
	const EDITION_PRO			= 'PARALLELS PROFESSIONAL';
	const EDITION_ULTIMATE		= 'PARALLELS ULTIMATE';
	const EDITION_NORMAL		= 'NORMAL';
	const EDITION_TRIAL			= 'TRIAL';

	const EXCEPTION_INVALID_KEY			= 1;
	const EXCEPTION_INVALID_PROPERTY	= 2;
	const EXCEPTION_INVALID_EXPIRYDATE	= 4;
	const EXCEPTION_NEED_NUMERIC		= 5;
	const EXCEPTION_INVALID_AGENCYID	= 6;
	const EXCEPTION_INVALID_VERSION		= 7;

	static protected $validEditions = false;
	static protected $NFRLimits = false;

	protected $licenseKey = false;
	protected $licenseVariables = array(
		'host'					=> false,
		'host_md5'				=> false,
		'edition'				=> self::EDITION_NORMAL,
		'expiry'				=> '',
		'version'				=> '5.0',
		'users'					=> 0,
		'lists'					=> 0,
		'subscribers'			=> 0,
		'nfr'					=> false,
		'agencyid'				=> '',
		'trialusers'			=> 0,
		'trialemails'			=> 100,
		'trialdays'				=> 30,
		'reactivation_days'		=> -1,
		'reactivation_grace'	=> 0,
	);

	protected $flagDontUpdate = false;



	/**
	 * CONSTRUCTOR
	 * @param string $licenseKey Intitialize the class with this license key (OPTIONAL)
	 */
	public function __construct($licenseKey = false)
	{
		// ----- Make sure that "valid edition" is initialized
			// Default value restrictions for each "edition"
			// if it is set to NULL, it will NOT take on the default value
			if (!self::$validEditions) {
				self::$validEditions = array(
					self::EDITION_NORMAL		=> array('users' => null,	'lists' => 0,	'subscribers' => 0),
					self::EDITION_STARTER		=> array('users' => 5,		'lists' => 0,	'subscribers' => 5000),
					self::EDITION_PRO			=> array('users' => 20,		'lists' => 0,	'subscribers' => 30000),
					self::EDITION_ULTIMATE		=> array('users' => 50,		'lists' => 0,	'subscribers' => 100000),
					self::EDITION_TRIAL			=> array('users' => null,	'lists' => 2,	'subscribers' => 100),
				);


				foreach (self::$validEditions as $tempKey => $tempValue) {
					$newKey = $tempKey;

					if ($tempKey == self::EDITION_NORMAL) {
						$newKey = md5($tempKey);
					}

					self::$validEditions[$tempKey]['key'] = $newKey;
				}
			}


			// Default restrictions for NFR, it have the same rule as "editions"
			if (!self::$NFRLimits) {
				self::$NFRLimits = array('users' => null, 'lists' => 2, 'subscribers' => 1000);
			}
		// -----

		// No need to initialize the class with a license key,
		// so we don't continue.
		if (empty($licenseKey)) {
			return;
		}

		if (!$this->licenseSet($licenseKey)) {
			throw new Exception('Invalid license key specified', self::EXCEPTION_INVALID_KEY);
		}
	}

	/**
	 * MAGIC: Setter
	 *
	 * @param string $property Property name
	 * @param mixed $value Property value
	 */
	public function __set($property, $value)
	{
		if (!array_key_exists($property, $this->licenseVariables)) {
			throw new Exception('Property not available', self::EXCEPTION_INVALID_PROPERTY);
		}

		switch ($property) {
			case 'host':
				$value = trim($value);
				if (empty($value)) {
					return;
				}
				$value = preg_replace('/^((http|https)\:\/\/)?(.*?)\/.*/', '${3}', $value);
				$this->licenseVariables['host_md5'] = md5($value);
			break;

			case 'host_md5':
				$this->licenseVariables['host'] = false;
			break;

			case 'edition':
				$value = strtoupper($value);
				if (!array_key_exists($value, self::$validEditions)) {
					$value = self::EDITION_NORMAL;
				}
			break;

			case 'expiry':
				$value = trim($value);
				if (empty($value)) {
					break;
				}
				$temp = strtotime($value);
				if (!$temp) {
					throw new Exception('Invalid expiry date specified. You can use ISO date or string that can be converted by strtotime() function.', self::EXCEPTION_INVALID_EXPIRYDATE);
				}

				$value = date('Y/m/d', $temp);
			break;

			case 'version':
				$value = trim($value);
				if (!preg_match('/^(\d+)\.(\d+)/', $value, $temp)) {
					throw new Exception('Invalid version specified. The format of the version number is X.X (where X are digits). Example: 5.5 or 5.6.7', self::EXCEPTION_INVALID_VERSION);
				}

				$value = "{$temp[1]}.{$temp[2]}";
			break;

			case 'users':
			case 'lists':
			case 'subscribers':
				if (!is_numeric($value)) {
					throw new Exception('The property: users, lists, subscribers need to be numeric.', self::EXCEPTION_NEED_NUMERIC);
				}

				$value = abs(intval($value));
			break;

			case 'nfr':
				$value = !!$value;
			break;

			case 'agencyid':
				if (preg_match('/\-|\:/', $value)) {
					throw new Exception('AgencyID cannot contains - (dash) or : (colon).', self::EXCEPTION_INVALID_AGENCYID);
				}
			break;

			case 'trialusers':
			case 'trialemails':
			case 'trialdays':
			case 'reactivation_grace':
				if (!is_numeric($value)) {
					throw new Exception('The property: trialusers, trialemails, trialdays, reactivation_grace need to be numeric.', self::EXCEPTION_NEED_NUMERIC);
				}

				$value = abs(intval($value));
			break;

			case 'reactivation_days':
				if (!is_numeric($value)) {
					throw new Exception('The property: reactivation_days need to be positive numbers.', self::EXCEPTION_NEED_NUMERIC);
				}

				$value = intval($value);
				if ($value <= 0) {
					$value = -1;
				}
			break;

			default:
				if (!is_numeric($value)) {
					$value = trim($value);
				}
			break;
		}

		$oldValue = $this->licenseVariables[$property];
		$this->licenseVariables[$property] = $value;

		if (!$this->flagDontUpdate && !empty($this->licenseVariables['host_md5']) && !$this->_generateLicenseKey()) {
			$this->licenseVariables[$property] = $oldValue;
			throw new Exception('Unable to generate license key with the property that you specified.', self::EXCEPTION_NEED_NUMERIC);
		}
	}

	/**
	 * MAGIC: Getter
	 *
	 * @param string $property Property name
	 * @return mixed Return property value
	 */
	public function __get($property)
	{
		if (!array_key_exists($property, $this->licenseVariables)) {
			throw new Exception('Property not available', self::EXCEPTION_INVALID_PROPERTY);
		}

		return $this->licenseVariables[$property];
	}




	/**
	 * Set new license key
	 * This method will allow you to set (and decrypt) a license key
	 *
	 * @param string $licenseKey License key
	 * @return boolean Returns TRUE if successful, FALSE otherwise
	 */
	public function licenseSet($licenseKey)
	{
		return $this->_decryptLicenseKey($licenseKey);
	}

	/**
	 * Get license key
	 * This method will return return current license key
	 * @return string|FALSE Returns license key if available, FALSE otherwise
	 */
	public function licenseGet()
	{
		return $this->licenseKey;
	}

	/**
	 * Update license key variables from an array
	 * @param array $newVariable Variables to update license key with
	 * @return boolean Returns TRUE if successful, FALSE otherwise
	 */
	public function variableSetArray($newVariable)
	{
		$this->flagDontUpdate = true;
		foreach ($newVariable as $key => $value) {
			$this->{$key} = $value;
		}
		$this->flagDontUpdate = false;

		return $this->_generateLicenseKey();
	}

	/**
	 * Get existing variable arrays
	 * @return array Returns exisitng license variable
	 */
	public function variableGetArray()
	{
		return $this->licenseVariables;
	}

	public function getEditions()
	{
		static $editions = null;

		if (is_null($editions)) {
			$editions = array_keys(self::$validEditions);
		}
		return $editions;
	}




	/**
	 * Generate license key based on license variable array
	 * @return boolean Returns TRUE if successful, FALSE otherwise
	 */
	protected function _generateLicenseKey()
	{
		$licenseKey = false;
		$licenseVariables = $this->licenseVariables;

		$encodedHost = $licenseVariables['host_md5'];
		$encodedVersion;

		// ----- Take care of the editions and their limit
			$temp = self::$validEditions[$licenseVariables['edition']];
			foreach ($temp as $tempIndex => $tempValue) {
				if (array_key_exists($tempIndex, $licenseVariables) && !is_null($tempValue)) {
					$licenseVariables[$tempIndex] = $tempValue;
				}
			}
		// -----


		// ----- NFR overwrote "Edition" limits
			if ($licenseVariables['nfr']) {
				$temp = self::$NFRLimits;
				foreach ($temp as $tempIndex => $tempValue) {
					if (array_key_exists($tempIndex, $licenseVariables) && !is_null($tempValue)) {
						$licenseVariables[$tempIndex] = $tempValue;
					}
				}
			}
		// -----


		// ----- Transform "version"
			$tempEncodedVersion = str_replace('.', 'a', $licenseVariables['version']);
			$tempEncodedVersionLength = strlen($tempEncodedVersion);

			$tempVessel = substr(md5($tempEncodedVersion . $encodedHost), 0, 16);

			$tempLocation = doubleval(hexdec($encodedHost{30})) % 8;
			$tempReplaceLength = $tempEncodedVersionLength + 1;

			$encodedVersion = preg_replace("/^(.{{$tempLocation}}).{{$tempReplaceLength}}(.*$)/", "\${1}{$tempEncodedVersionLength}{$tempEncodedVersion}\${2}", $tempVessel);
		// -----


		$licenseKey = $encodedHost;
		$licenseKey .= '-' . self::$validEditions[$licenseVariables['edition']]['key'];
		$licenseKey .= '-' . implode('.', explode('/', $licenseVariables['expiry']));
		$licenseKey .= '-v<' . $encodedVersion . '>';
		$licenseKey .= '-' . $licenseVariables['users'];
		$licenseKey .= '-' . $licenseVariables['lists'];
		$licenseKey .= '-' . $licenseVariables['subscribers'];
		$licenseKey .= '-' . $encodedHost{10} . ($licenseVariables['nfr']? "\r#" : "\n#");
		$licenseKey .= ":{$licenseVariables['agencyid']}:{$licenseVariables['trialusers']}:{$licenseVariables['trialemails']}:{$licenseVariables['trialdays']}";
		$licenseKey .= ":" .($licenseVariables['reactivation_days'] + 1000) . ":{$licenseVariables['reactivation_grace']}:";
		$licenseKey .= dechex(doubleval(sprintf('%u', crc32($licenseKey))));

		$licenseKey = 'IEM-' . base64_encode($licenseKey);

		$this->licenseKey = $licenseKey;
		$this->licenseVariables = $licenseVariables;

		return true;
	}

	/**
	 * Decrypt license key
	 * @param string $licenseKey License key to decrypt
	 * @return boolean Returns TRUE if successful, FALSE otherwise
	 */
	protected function _decryptLicenseKey($licenseKey)
	{
		$crc32ok = false;
		$decodedLicenseKey;
		$licenseVariables = array('host' => false);


		// License key must be prefixed with IEM-
		if (substr($licenseKey, 0, 4) != 'IEM-') {
			return false;
		}

		$decodedLicenseKey = @base64_decode(substr($licenseKey, 4));
		if (substr_count($decodedLicenseKey, '-') !== 7) {
			return false;
		}


		// ----- Removing and comparing CRC32 values from the license key
			if(preg_match('/^(.*?)\:([\da-f]+)$/s', $decodedLicenseKey, $temp)) {
				list(, $decodedLicenseKey, $tempCRC32) =  $temp;

				if (dechex(doubleval(sprintf('%u', crc32($decodedLicenseKey . ':')))) != $tempCRC32) {
					return false;
				}

				$crc32ok = true;
			}
		// -----


		// Get the license variables into an easier to referenced variable
		list(
			$licenseVariables['host_md5'],
			$licenseVariables['edition'],
			$licenseVariables['expiry'],
			$licenseVariables['version'],
			$licenseVariables['users'],
			$licenseVariables['lists'],
			$licenseVariables['subscribers'],
			$licenseVariables['payload']) = explode('-', $decodedLicenseKey);


		// ----- Decode "edition"
			while (true) {
				foreach (self::$validEditions as $tempEdition => $tempValue) {
					if ($tempValue['key'] == $licenseVariables['edition']) {
						$licenseVariables['edition'] = $tempEdition;
						break 2;
					}
				}

				// This edition is NOT VALID
				return false;
			}
		// -----


		// ----- Decode expiry
			if (!empty($licenseVariables['expiry'])) {
				$temp = explode('.', $licenseVariables['expiry']);
				if (count($temp) != 3) {
					return false;
				}

				$licenseVariables['expiry'] = implode('/', $temp);
			}
			else {
				$licenseVariables['expiry'] = '';
			}
		// -----


		// ----- Decode version
			if (preg_match('/^v<(.*)>$/', $licenseVariables['version'], $temp)) {
				$tempLocation = doubleval(hexdec($licenseVariables['host_md5']{30})) % 8;
				$tempLength = $temp[1]{$tempLocation};
				$licenseVariables['version'] = str_replace('a', '.', substr($temp[1], $tempLocation + 1, $tempLength));
			}
			else {
				$licenseVariables['version'] = '5.0';
			}

			// Starting from 5.7 onwards, we do take account of crc32 field
			// So if CRC32 is not the same, return false;
			if (version_compare($licenseVariables['version'], '5.7') != -1 && !$crc32ok) {
				return false;
			}
		// -----


		// ----------------------------------------------------------------------
		// Decode "payload"
		//
		// Payload will contains a string of following values
		// depending on the version (separated by a colon ie. :):
		// - NFR flag
		// - Agency ID (number that will is assigned on our database
		// - Number of Trial Account
		// - Number of email a trial account can have
		// - The number of days a trial account will expire
		// - The number of days until the license require a "reactivation"
		// - The number of days an installation can go without being reactivated (grace period)
		// ----------------------------------------------------------------------
			if (version_compare('5.7', $licenseVariables['version']) == 1) {
				$licenseVariables['nfr'] = $licenseVariables['payload'];
				$licenseVariables['agencyid'] = '';
				$licenseVariables['trialusers'] = $licenseVariables['trialemails'] = $licenseVariables['trialdays'] = 0;
				$licenseVariables['reactivation_days'] = -1;
				$licenseVariables['reactivation_grace'] = 0;
			} else {
				if (substr_count($licenseVariables['payload'], ':') < 4) {
					return false;
				}

				list (
					$licenseVariables['nfr'],
					$licenseVariables['agencyid'],
					$licenseVariables['trialusers'],
					$licenseVariables['trialemails'],
					$licenseVariables['trialdays'],
					$licenseVariables['reactivation_days'],
					$licenseVariables['reactivation_grace']) = explode(':', $licenseVariables['payload']);

				$licenseVariables['reactivation_days'] -= 1000;
			}

			unset($licenseVariables['payload']);
		// ----------------------------------------------------------------------

		// Decode "NFR Flag"
		$licenseVariables['nfr'] = (!preg_match('/^' . $licenseVariables['host_md5']{10} . '\n#/', $licenseVariables['nfr']));

		$this->licenseKey = $licenseKey;
		$this->licenseVariables = $licenseVariables;

		return true;
	}
}
