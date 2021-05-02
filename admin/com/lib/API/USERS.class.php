<?php

class API_USERS implements IEM_baseAPI
{

    public static function getRecords($countonly = false, $condition = false, $limit = 0, $offset = 0, $sortby = false, $sortdesc = false)
    {

    }

    public static function saveRecord(&$record)
    {

    }

    public static function getRecordByID($id)
    {
        $userid = (int)$id;
        $db = IEM::getDatabase();

        $rs = $db->Query("SELECT * FROM [|PREFIX|]users WHERE userid = {$userid}");
        if (!$rs) {
            trigger_error(__CLASS__ . '::' . __METHOD__ . ' - Unable to get user record' . $db->Error(), E_USER_NOTICE);
        }

        $row = $db->Fetch($rs);
        $db->FreeResult($rs);

        if (empty($row)) {
            return false;
        }

        return new record_Users($row);
    }


    public static function deleteRecordByID($id, $deleteAllOwnedData = true)
    {
        $userid = (int)$id;

        if ($deleteAllOwnedData) {
            $obj    = new HelperUserDelete();
            $status = $obj->_deleteUser($userid);
            return empty($status['status']) ? false : true;
        }

        $db = IEM::getDatabase();
        $db->StartTransaction();

        $query  = "DELETE FROM [|PREFIX|]users WHERE userid = {$userid}";
        $result = $db->Query($query);

        if (!$result) {
            $db->RollbackTransaction();
            trigger_error(__CLASS__.'::'.__METHOD__.' - Unable to delete user record'.$db->Error(), E_USER_NOTICE);
            return false;
        }

        if (!del_user_dir($userid)) {
            $db->RollbackTransaction();
            trigger_error(__CLASS__.'::'.__METHOD__.' - User files/data was not found?', E_USER_NOTICE);
            return false;
        }

        $db->CommitTransaction();

        return true;
    }

	/**
	 * Returns a record_Users object from the passed parameter.
	 * 
	 * @param int|record_Users $user
	 * 
	 * @return record_Users
	 */
    static public function getUser($user)
    {
        if ($user instanceof record_Users) {
			return $user;
        }
        
		return self::getRecordByID($user);
    }

	/**
	 * Generate password hash
	 *
	 * @param String $password Plaintext password
	 * @param String $token User token
	 *
	 * @return String Returns a password hash
	 */
	static public function generatePasswordHash($password, $token)
	{
		return md5(md5($token) . md5($password));
	}

	/**
	 * Generate and return a new unique token
	 *
	 * A unique token generated from this method will have a maximum length of 128.
	 * It will also use the username as an additional salt to the token.
	 *
	 * @param String $username The username which the token will be generated against
	 * @return String Returns a new unique token
	 */
	static public function generateUniqueToken($username)
	{
		$token = time() . rand(10, 5000) . sha1(rand(10, 5000)) . md5(__FILE__);
		$token = str_shuffle($token);
		$token = sha1($token) . md5(microtime()) . md5($username);

		return $token;
	}

	// --------------------------------------------------------------------------------
	// Methods related to credits
	// --------------------------------------------------------------------------------
		const CREDIT_USAGETYPE_SENDCAMPAIGN			= 'send_campaign';
		const CREDIT_USAGETYPE_SENDTRIGGER			= 'send_trigger';
		const CREDIT_USAGETYPE_SENDAUTORESPONDER	= 'send_autoresponder';


		/**
		 * Record credit usage
		 * This function will record credit usage for a particular user.
		 *
		 * @param record_Users|integer $user User record object or user ID
		 * @param string $usagetype Usage type (see class constansts CREDIT_USAGETYPE_* for valid types)
		 * @param integer $creditused The number of credits that are being used up
		 * @param integer $jobid Associate job ID (OPTIONAL, default = 0)
		 * @param integer $statid Associate statistic ID (OPTIONAL, default = 0)
		 * @param integer $time Time of which the credit is being used (OPTIONAL, default = now)
		 *
		 * @return boolean Returns TRUE if successful, FALSE otherwise
		 */
		static public function creditUse($user, $usagetype, $creditused, $jobid = 0, $statid = 0, $time = 0, $evaluateWarnings = true)
		{
			$userid = 0;
			$usagetype = strtolower($usagetype);
			$creditused = intval($creditused);
			$jobid = intval($jobid);
			$statid = intval($statid);
			$time = intval($time);
			$db = IEM::getDatabase();

			static $validTypes = null;
			
			if (is_null($validTypes)) {
				$validTypes = array(
					self::CREDIT_USAGETYPE_SENDAUTORESPONDER,
					self::CREDIT_USAGETYPE_SENDCAMPAIGN,
					self::CREDIT_USAGETYPE_SENDTRIGGER
				);
			}

			if (!($user instanceof record_Users)) {
				$userid = (int)$user;
				$user = self::getRecordByID($userid);
			}


			if (!$user) {
				trigger_error('API_USERS::creditUse -- Invalid user specified.', E_USER_NOTICE);
				return false;
			}

			if (!in_array($usagetype, $validTypes)) {
				trigger_error("API_USERS::creditUse -- Invalid credit type '{$usagetype}'.", E_USER_NOTICE);
				return false;
			}

			if ($creditused < 1) {
				trigger_error('API_USERS::creditUse -- Credit cannot be less than 1.', E_USER_NOTICE);
				return false;
			}

			if ($jobid < 0) {
				trigger_error('API_USERS::creditUse -- Invalid jobid specified.', E_USER_NOTICE);
				return false;
			}

			if ($statid < 0) {
				trigger_error('API_USERS::creditUse -- Invalid statid specified.', E_USER_NOTICE);
				return false;
			}

			if ($time < 0) {
				trigger_error('API_USERS::creditUse -- Time cannot be negative.', E_USER_NOTICE);
				return false;
			}

			// If user has unlimited emails credit, we don't need to record this
			$usersApi = new User_API($user->userid);

			if ($usersApi->hasUnlimitedCredit()) {
				return true;
			}

            if ($usagetype == self::CREDIT_USAGETYPE_SENDTRIGGER && !SENDSTUDIO_CREDIT_INCLUDE_TRIGGERS) {
                return true;
            }

            if ($usagetype == self::CREDIT_USAGETYPE_SENDAUTORESPONDER && !SENDSTUDIO_CREDIT_INCLUDE_AUTORESPONDERS) {
                return true;
            }

			$time = ($time == 0 ? time() : $time);

			$db->StartTransaction();

			$tempStatus = $db->Query("
				INSERT INTO [|PREFIX|]user_credit (userid, transactiontype, transactiontime, credit, jobid, statid)
				VALUES ({$userid}, '{$usagetype}', {$time}, -{$creditused}, {$jobid}, {$statid})
			");

			if (!$tempStatus) {
				$db->RollbackTransaction();
				trigger_error("API_USERS::creditUse -- Unable to insert credit usage into database: " . $db->Error(), E_USER_NOTICE);
				return false;
			}

			$db->CommitTransaction();

			if ($evaluateWarnings) {
				return self::creditEvaluateWarnings($userid);
			} else {
				return true;
			}
		}

		static public function creditAvailableThisHour($user)
		{
			$user      = self::getUser($user);
			$userGroup = API_USERGROUPS::getRecordById($user->groupid);
			$limit     = (int) $userGroup['limit_hourlyemailsrate'];
            
			// No limit, returns TRUE
			if ($limit == 0) {
				return true;
			}
			
			$used = $user->getUsedHourlyCredit();
			
			if (!$used) {
			    return $limit;
			}
            
			return $limit - $used;
		}

		/**
		 * Get available monthy credit for this month
		 *
		 * @param record_Users|integer $user User record object or user ID
		 * @param boolean $percentage Whether or not you want to return the available credit as a percentage
		 * @return integer|boolean Returns TRUE if user has unlimited credit, an integer if user has a limit, FALSE if it encountered any error
		 */
		static public function creditAvailableThisMonth($user, $percentage = false, $queuetime = 0)
		{
			$user      = self::getUser($user);
			$userGroup = API_USERGROUPS::getRecordById($user->groupid);
			$limit     = (int) $userGroup['limit_emailspermonth'];
            
			// No limit, returns TRUE
			if ($limit == 0) {
				return true;
			}
			
			// get the amount of used credit
			$used = $user->getUsedMonthlyCredit($queuetime);
			
			// If no credits have been used this month, return permonth
			if (!$used) {
				return $limit;
			}
			
			// calculate how much credit is left
			$tempCreditLeft = $limit - ABS($used);

			if (!$percentage) {
				return $tempCreditLeft;
			}

			return ($tempCreditLeft / $limit * 100);
		}

		/**
		 * Get available fixed credit.
		 *
		 * @param record_Users|integer $user User record object or user ID
		 * 
		 * @return integer|boolean Returns TRUE if user has unlimited credit, an integer if user has a limit, FALSE if it encountered any error
		 */
		static public function creditAvailableFixed($user)
		{
			$user      = self::getUser($user);
			$userGroup = API_USERGROUPS::getRecordById($user->groupid);
			$limit     = (int) $userGroup['limit_totalemailslimit'];
			
			if ($limit == 0) {
			    return true;
			}
			
			$used = $user->getUsedCredit();
			
			if (!$used) {
			    return $limit;
			}
			
			return $limit - $used;
		}

		/**
		 * Get total available credit
		 *
		 * @param record_Users|integer $user User record object or user ID
		 * @return integer|boolean Returns TRUE if user has unlimited credit, an integer if user has a limit, FALSE if it encountered any error
		 *
		 * @todo all
		 */
		static public function creditAvailableTotal($user)
		{
			$userobject = null;

			if ($user instanceof record_Users) {
				$userobject = $user;
			} else {
				$userobject = self::getRecordByID($user);
			}

			if (empty($userobject)) {
				trigger_error(__CLASS__ . '::' . __METHOD__ . ' -- User is not specified', E_USER_NOTICE);
				
				return false;
			}

			$fixed   = self::creditAvailableFixed($userobject);
			$monthly = self::creditAvailableThisMonth($userobject);
			$hourly  = self::creditAvailableThisHour($userobject);

			// If either functions return FALSE, propagate it.
			if ($fixed === false || $monthly === false || $hourly === false) {
				return false;
			}

			if ($fixed === true) {
				return $monthly;
			} elseif ($monthly === true) {
				return $fixed;
			}

			return $fixed + $monthly + $hourly;
		}

		/**
		 * Evaluate credit warning conditions
		 *
		 * This method will evaluate credit warnings for a particular user.
		 * It will dispatch warning emails accrodingly.
		 *
		 * @param record_Users|integer $user User record object or user ID
		 * @return boolean Returns TRUE if successful, FALSE otherwise
		 *
		 * @todo fixed credits does not have warnings yet
		 */
		static public function creditEvaluateWarnings($user)
		{
			
			$userobject = null;
			$warnings = null;
			$this_month = mktime(0, 0, 0, date('n'), 1, date('Y'));
			$credit_left = null;


			// ----- PRE
				if ($user instanceof record_Users) {
					$userobject = $user;
				} else {
					$userobject = self::getRecordByID($user);
				}

				if (empty($userobject)) {
					trigger_error(__CLASS__ . '::' . __METHOD__ . ' -- User is not specified', E_USER_NOTICE);
					return false;
				}
			// -----

			// Credit warnings are not enabled
			if (!SENDSTUDIO_CREDIT_WARNINGS) {
				return true;
			}


			require_once(IEM_PUBLIC_PATH . '/functions/api/settings.php');
			$tempSettingsAPI = new Settings_API();
			$warnings = $tempSettingsAPI->GetCreditWarningsSettings();

			// Does not hany any warnings setup? Well... we can't continue then.
			if (empty($warnings)) {
				return true;
			}

			$credit_left = self::creditAvailableTotal($userobject);
			
			//unlimited credit
			if($credit_left === true){
			    return true;
			}
			
			$whichlevel = self::creditWhichWarning($userobject, $credit_left, $warnings);

			// If $whichlevel contains FALSE, that means there was something wrong
			// when trying to figure out which warning level it should send out.
			if ($whichlevel === false) {
				return true;
			}
			
			$userGroup = API_USERGROUPS::getRecordById($userobject->groupid);
            if(!isset($userGroup['limit_emailspermonth'])){ return false; }
            $userobject_permonth = (int)$userGroup['limit_emailspermonth'];

			$fixed   = self::creditAvailableFixed($userobject);
			$monthly = self::creditAvailableThisMonth($userobject);
			
		    if($fixed === true) {
		        $userobject_permonth = $monthly;
		    } elseif ($monthly === true) {
				$userobject_permonth = $fixed;
			}	


			if (!empty($whichlevel)) {
				$tempNames = explode(' ', $userobject->fullname);
				$tempLastName = array_pop($tempNames);
				$tempFirstName = implode(' ', $tempNames);

				$available_custom_fields_key = array(
					'%%user_fullname%%',
					'%%user_firstname%%',
					'%%user_lastname%%',
					'%%credit_total%%',
					'%%credit_remains%%',
					'%%credit_remains_precentage%%',
					'%%credit_used%%',
					'%%credit_used_percentage%%'
				);

				$available_custom_fields_value = array(
					$userobject->fullname,
					$tempFirstName,
					$tempLastName,
					$userobject_permonth,
					intval($userobject_permonth * ($credit_left / 100)),
					intval($credit_left),
					intval($userobject_permonth * ((100 - $credit_left) / 100)),
					intval(100 - $credit_left)
				);

				$email_contents = str_replace($available_custom_fields_key, $available_custom_fields_value, $whichlevel['emailcontents']);
				$email_subject = str_replace($available_custom_fields_key, $available_custom_fields_value, $whichlevel['emailsubject']);

				// ----- We found which warnings it is that we want to send out
					require_once(IEM_PATH . '/ext/interspire_email/email.php');
					$emailapi = new Email_API();
					$emailapi->SetSmtp(SENDSTUDIO_SMTP_SERVER, SENDSTUDIO_SMTP_USERNAME, @base64_decode(SENDSTUDIO_SMTP_PASSWORD), SENDSTUDIO_SMTP_PORT);
					if ($userobject->smtpserver) {
						$emailapi->SetSmtp($userobject->smtpserver, $userobject->smtpusername, $userobject->smtppassword, $userobject->smtpport);
					}
					$emailapi->ClearRecipients();
					$emailapi->ForgetEmail();
					$emailapi->Set('forcechecks', false);
					$emailapi->AddRecipient($userobject->emailaddress, $userobject->fullname, 't');
					$emailapi->Set('FromName', false);
					$emailapi->Set('FromAddress', (defined('SENDSTUDIO_EMAIL_ADDRESS') ? SENDSTUDIO_EMAIL_ADDRESS : $userobject->emailaddress));
					$emailapi->Set('BounceAddress', SENDSTUDIO_EMAIL_ADDRESS);
					$emailapi->Set('CharSet', SENDSTUDIO_CHARSET);
					$emailapi->Set('Subject', $email_subject);
					$emailapi->AddBody('text', $email_contents);
					$status = $emailapi->Send();
					if ($status['success'] != 1) {
						trigger_error(__CLASS__ . '::' . __METHOD__ . ' -- Was not able to send email: ' . serialize($status['failed']), E_USER_NOTICE);
						return false;
					}
				// -----

				// ----- Update user record
					$db = IEM::getDatabase();
					$status = $db->Query("UPDATE [|PREFIX|]users SET credit_warning_time = {$this_month}, credit_warning_percentage = {$whichlevel['creditlevel']} WHERE userid = {$userobject->userid}");

					// Update user object in session
					// FIXME, we really need to make a special getter/setter for this
					$current_user = IEM::getCurrentUser();
					if ($current_user && $current_user->userid == $userobject->userid) {
						$current_user->credit_warning_time = $this_month;
						$current_user->credit_warning_percentage = $whichlevel['creditlevel'];
					}
				// -----
			}

			return true;
		}

		/**
		 * Work out which warning message need to be sent out
		 *
		 * This function will return appropriate warning records according to user's information.
		 * By itself it will not do anything (ie. will not be affecting anything in the system).
		 *
		 * NOTE:
		 * - The available warnings record can be fetch from Settings_API::GetCreditWarningsSettings() method for now
		 *
		 * @param record_Users $userobject User object
		 * @param integer $user_monthly_credit_available Currently available credit
		 * @param array $available_warnings Available warnings record
		 *
		 * @return array|FALSE Return credit warnings record (empty array if no warnings are necessary), FALSE otherwise
		 */
		static public function creditWhichWarning($userobject, $user_monthly_credit_available, $available_warnings)
		{
			if (empty($available_warnings)) {
				return false;
			}

			if (!($userobject instanceof record_Users)) {
				return false;
			}

			$this_month = mktime(0, 0, 0, date('n'), 1, date('Y'));
			$whichlevel = array(); // The default warning level is empty array

			// If warning has been sent out (this month), do not continue:
			// - credit_warning_percentage is smaller than $credit_left
			// - credit_warning_percentage is NOT null
			if ($userobject->credit_warning_time >= $this_month
					&& ($userobject->credit_warning_percentage <= $user_monthly_credit_available
						&& !is_null($userobject->credit_warning_percentage))) {

				return $whichlevel;
			}

			foreach ($available_warnings as $warning) {
				// If credit level is smaller than credit_left, continue
				if ($warning['creditlevel'] < $user_monthly_credit_available) {
					continue;
				}

				// Only take the smallest value
				if (!empty($whichlevel) && $whichlevel < $warning['creditlevel']) {
					continue;
				}

				// If the warning is not enabled, continue
				if (!$warning['enabled']) {
					continue;
				}

				// Because we only evaluate "monthly warnings", we skip any fix credit warnings
				if (!$warning['aspercentage']) {
					continue;
				}

				// Skip any warnings that have been sent out this month
				if ($userobject->credit_warning_time >= $this_month
						&& ($warning['creditlevel'] >= $userobject->credit_warning_percentage)
							&& !is_null($userobject->credit_warning_percentage)) {

					continue;
				}

				$whichlevel = $warning;
			}

			return $whichlevel;
		}
	// --------------------------------------------------------------------------------
}
