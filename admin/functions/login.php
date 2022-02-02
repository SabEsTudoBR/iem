<?php
/**
* Include the base sendstudio functions.
*/
require_once(dirname(__FILE__) . '/sendstudio_functions.php');

/**
* Class for the login page. Will show the login screen, authenticate and set the session details as it needs to.
*
* @package SendStudio
* @subpackage SendStudio_Functions
*/
class Login extends SendStudio_Functions
{

	public function __construct()
	{
		$this->LoadLanguageFile();
	}

	/**
	* Process
	* All the action happens here.
	* If you are not logged in, it will print the login form.
	* Submitting that form will then try to authenticate you.
	* If you are successfully authenticated, you get redirected back to the main index page (quickstats etc).
	* Otherwise, will show an error message and the login form again.
	*
	* @see ShowLoginForm
	* @uses AuthenticationSystem::Authenticate()
	*
	* @return Void Doesn't return anything. Checks the action and passes it off to the appropriate area.
	*/
    public function Process()
	{
		$action = IEM::requestGetGET('Action', '', 'strtolower');
		
		$GLOBALS['ResetPassError'] = 'block';
		switch ($action) {
			case 'forgotpass':
				$this->ShowForgotForm();
			break;

			case 'changepassword':
				if (!IEM::sessionGet('ForgotUser')) {
					$this->ShowForgotForm('login_error', GetLang('BadLogin_Link'));
					break;
				}

				$userapi = new User_API();
				$loaded = $userapi->Load(IEM::sessionGet('ForgotUser'));

				if (!$loaded) {
					$this->ShowForgotForm('login_error', GetLang('BadLogin_Link'));
					break;
				}

				$password = IEM::requestGetPOST('ss_password', false);
				$confirm = IEM::requestGetPOST('ss_password_confirm', false);
				
				#Added password rules.
				$auth_pass = new AuthenticationSystem();
                $result_auth_pass= $auth_pass->AuthenticatePassword($password);
				
				if ($result_auth_pass === -1) {
					 $this->ShowForgotForm_Step2($userapi->Get('username'), 'login_error', GetLang('NoValidPassword'));
					break;
				}

				if ($password == false || ($password != $confirm)) {
					$this->ShowForgotForm_Step2($userapi->Get('username'), 'login_error', GetLang('PasswordsDontMatch'));
					break;
				}

				$userapi->password = $password;
				$userapi->Save();

				$code = $this->get_unique_code('forgot_password');

				$userapi->ResetForgotCode($code);

				$this->ShowLoginForm('login_success', GetLang('PasswordUpdated'));
			break;

			case 'sendpass':
				$user = new User_API();
				$username = IEM::requestGetPOST('ss_username', '');

				$username = preg_replace('/\s+/', ' ', $username);
				$username = trim($username);

				$founduser = $user->Find($username);
				if (!$founduser) {
					$this->ShowForgotForm('login_error', GetLang('BadLogin_Forgot'));
					break;
				}

				$user->Load($founduser, false);

				$code = $this->get_unique_code('forgot_password');

				$user->ResetForgotCode($code);

				$link = SENDSTUDIO_APPLICATION_URL . '/admin/index.php?Page=Login&Action=ConfirmCode&user=' . $founduser . '&code=' . $code;

				$message = sprintf(GetLang('ChangePasswordEmail'), $link);

				$email_api = $this->GetApi('Email');
				$email_api->Set('CharSet', SENDSTUDIO_CHARSET);
				$email_api->Set('Multipart', false);
				$email_api->AddBody('text', $message);
				$email_api->Set('Subject', GetLang('ChangePasswordSubject'));

				$email_api->Set('FromAddress', SENDSTUDIO_EMAIL_ADDRESS);
				$email_api->Set('ReplyTo', SENDSTUDIO_EMAIL_ADDRESS);
				$email_api->Set('BounceAddress', SENDSTUDIO_EMAIL_ADDRESS);

				$email_api->SetSmtp(SENDSTUDIO_SMTP_SERVER, SENDSTUDIO_SMTP_USERNAME, @base64_decode(SENDSTUDIO_SMTP_PASSWORD), SENDSTUDIO_SMTP_PORT);

				$user_fullname = $user->Get('fullname');

				$email_api->AddRecipient($user->emailaddress, $user_fullname, 't');

				$send_return = $email_api->Send();
				if($send_return['success'] === 0) {
					$this->ShowForgotForm_Step2($username,'login_success', sprintf(GetLang('ChangePassword_SendingFailed'), $user->emailaddress));
				} else {
					$this->ShowForgotForm_Step2($username,'login_success', sprintf(GetLang('ChangePassword_Emailed'), $user->emailaddress));
				}
			break;
			case 'updatepassword':
				$user = IEM::requestGetGET('user', false, 'intval');
				$code = IEM::requestGetPOST('code', false, 'trim');
				  
				$this->userid = $user ;
				$userapi = new User_API();
				$loaded = $userapi->Load($user);
				if (!$loaded || $userapi->Get('forgotpasscode') != $code) {
					$this->ShowForgotForm('login_error', GetLang('BadLogin_Link'));
					break;
				}  
               
				$password = IEM::requestGetPOST('ss_password', false);
				$confirm = IEM::requestGetPOST('ss_password_confirm', false);
				
				if ($password == false || ($password != $confirm)) {
					$this->ShowForgotForm_Step2($userapi->Get('username'), 'resetpassword', GetLang('PasswordsDontMatch'));
					break;
				}
				
				$userapi->password =  $password ;
				$userapi->Save();

				$code = $this->get_unique_code('forgot_password');

				$userapi->ResetForgotCode($code);

				$this->ShowLoginForm('login_success', GetLang('PasswordUpdated'));
			break;
			 
			case 'resetpassword':
				$user = IEM::requestGetGET('user', false, 'intval');
				$code = IEM::requestGetGET('code', false, 'trim');
 
				if (empty($user) || empty($code)) {
					$this->ShowResetPasswordError('login_error', GetLang('BadLogin_Link'));
					break;
				}

				$userapi = new User_API();
				$loaded = $userapi->Load($user, false);

				if (!$loaded || $userapi->Get('forgotpasscode') != $code) { 
					$GLOBALS['ResetPassError']= 'none'; 
					$this->ShowResetPasswordError('login_error', GetLang('Something_wrong'));
					break;
				} 
				$GLOBALS['UpdatePassword']= "updatepassword&user=".$user;
				IEM::sessionSet('ResetUser', $user);
				$GLOBALS['CODE']= $code;
				$this->ShowForgotForm_Step2($userapi->Get('username'),'resetpassword',true);
			break;
			 
			case 'confirmcode':
				$user = IEM::requestGetGET('user', false, 'intval');
				$code = IEM::requestGetGET('code', false, 'trim');

				if (empty($user) || empty($code)) {
					$this->ShowForgotForm('login_error', GetLang('BadLogin_Link'));
					break;
				}

				$userapi = new User_API();
				$loaded = $userapi->Load($user, false);

				if (!$loaded || $userapi->Get('forgotpasscode') != $code) {
					$this->ShowForgotForm('login_error', GetLang('BadLogin_Link'));
					break;
				}

				IEM::sessionSet('ForgotUser', $user);
				
				$this->ShowForgotForm_Step2($userapi->Get('username'));
			break;

			case 'login':
				$auth_system = new AuthenticationSystem();
				$username = IEM::requestGetPOST('ss_username', '');
				$password = IEM::requestGetPOST('ss_password', '');
				$result = $auth_system->Authenticate($username, $password);
				if ($result === -1) {
					$this->ShowLoginForm('login_error', GetLang('PleaseWaitAWhile'));
					break;
				} elseif ($result === -2) {
					$this->ShowLoginForm('login_error', GetLang('FreeTrial_Expiry_Login'));
					break;
				} elseif (!$result) {
					$this->ShowLoginForm('login_error', GetLang('BadLogin'));
					break;
				} elseif ($result && defined('IEM_SYSTEM_ACTIVE') && !IEM_SYSTEM_ACTIVE) {
					$msg = (isset($result['admintype']) && $result['admintype'] == 'a') ? 'ApplicationInactive_Admin' : 'ApplicationInactive_Regular';
					$this->ShowLoginForm('login_error', GetLang($msg));
					break;
				}
				 
				if (!empty($result)) {
					 
					//send email					
					$user_api = new User_API();
					$username = IEM::requestGetPOST('ss_username', '');
					
					$username = preg_replace('/\s+/', ' ', $username);
					$username = trim($username);

					$founduser = $user_api->Find($username);
					
					if (!$founduser) {
						$this->ShowForgotForm('login_error', GetLang('BadLogin_Forgot'));
						break;
					}  

					$userid = $founduser;
					
					$user_api->Load($founduser, false);
					
					if($user_api->getSettingValue('SECURITY_TWO_FACTOR_AUTH') == 1){
						IEM::sessionSet('userid', $userid);
						IEM::sessionSet('SendotpCount',0);
						IEM::sessionSet('otp_try', 0);
						
						$userapi = new User_API();
						$loaded = $userapi->Load($userid, false);
						$GLOBALS['USERID'] =  $userid;
			
						$code = $this->get_unique_code('otp');								 
						$user_api->OTP($code,$founduser);
						
						$otp_email_content = sprintf(GetLang('EmailContent_OTP'), $code); 
						$message = $otp_email_content;
						 
						$email_api = $this->GetApi('Email');
						$email_api->Set('CharSet', SENDSTUDIO_CHARSET);
						$email_api->Set('Multipart', false);
						$email_api->AddBody('text', $message);
						$email_api->Set('Subject', GetLang('EmailSubject_OTP'));

						$email_api->Set('FromAddress', SENDSTUDIO_EMAIL_ADDRESS);
						$email_api->Set('ReplyTo', SENDSTUDIO_EMAIL_ADDRESS);
						$email_api->Set('BounceAddress', SENDSTUDIO_EMAIL_ADDRESS);

						$email_api->SetSmtp(SENDSTUDIO_SMTP_SERVER, SENDSTUDIO_SMTP_USERNAME, @base64_decode(SENDSTUDIO_SMTP_PASSWORD), SENDSTUDIO_SMTP_PORT);

						$user_fullname = $user_api->Get('fullname');
						$email_api->AddRecipient($user_api->emailaddress, $user_fullname, 't');
						
						$send_return = $email_api->Send();
						
						if($send_return['success'] === 0) {
							$this->ShowOTPForm('otp_sending_error', GetLang('EmailNotSent_OTP'), $userid);
						} else {
							$message = sprintf(GetLang('Help_OTP'), $this->mask_emailaddress($user_api->emailaddress));
						
							$this->ShowOTPForm('otp_Success', $message, $userid);
						}
						 
					} else {
						IEM::userLogin($result['userid']);
						$redirect = $this->_validateTakeMeToRedirect(IEM::requestGetPOST('ss_takemeto', 'index.php'));

						header('Location: ' . SENDSTUDIO_APPLICATION_URL . '/admin/' . $redirect);
						exit();
					}
					break;
				}
				 
			break;
			case 'resendotp':
				$user_api = new User_API();
				$userid = IEM::requestGetGET('userid', '');
				$code = $this->get_unique_code('otp');
				IEM::sessionSet('otp_try', 0);
				
				$founduser = $userid;

				if(empty(IEM::sessionGet('userid')) || IEM::sessionGet('userid') != $userid) {
					$this->ShowOTPForm('user_error',GetLang('Something_wrong'),$userid);
					break;
				}
				
				if(IEM::sessionGet('SendotpCount')){
					$count = IEM::sessionGet('SendotpCount');
					$count = $count+1;
					IEM::sessionSet('SendotpCount', $count);
				} else {
					IEM::sessionSet('SendotpCount',1);
				}
				
				if(IEM::sessionGet('SendotpCount') > SENDSTUDIO_SECURITY_TWO_FACTOR_AUTH_RESEND_LINK_TIME){
					$this->ShowOTPForm('user_error',GetLang('ResendLimit_OTP'),$userid); 
					break;
				}
				
				$user_api->Load($founduser, false);
				$GLOBALS['USERID'] =  $userid;				 
				$user_api->OTP($code,$founduser);
				
				$otp_email_content= sprintf(GetLang('EmailContent_OTP'), $code); 
				$message = $otp_email_content;

				$email_api = $this->GetApi('Email');
				$email_api->Set('CharSet', SENDSTUDIO_CHARSET);
				$email_api->Set('Multipart', false);
				$email_api->AddBody('text', $message);
				$email_api->Set('Subject', GetLang('EmailSubject_OTP'));

				$email_api->Set('FromAddress', SENDSTUDIO_EMAIL_ADDRESS);
				$email_api->Set('ReplyTo', SENDSTUDIO_EMAIL_ADDRESS);
				$email_api->Set('BounceAddress', SENDSTUDIO_EMAIL_ADDRESS);

				$email_api->SetSmtp(SENDSTUDIO_SMTP_SERVER, SENDSTUDIO_SMTP_USERNAME, @base64_decode(SENDSTUDIO_SMTP_PASSWORD), SENDSTUDIO_SMTP_PORT);

				$user_fullname = $user_api->Get('fullname');
				$email_api->AddRecipient($user_api->emailaddress, $user_fullname, 't');
				$send_return = $email_api->Send();
				
				if($send_return['success'] === 0) {
					$this->ShowOTPForm('otp_sending_error', $message=GetLang('EmailNotSent_OTP'), $userid);
				} else {
					$message = sprintf(GetLang('Help_OTP'), $this->mask_emailaddress($user_api->emailaddress));
					$this->ShowOTPForm('otp_Success', $message, $userid);
				}		 
				 
			break;
			case 'confirmotp':
				$userid = IEM::requestGetGET('user', false, 'intval');
				$otp = IEM::requestGetPost('otp', false, 'trim');
				
				if(empty(IEM::sessionGet('userid')) || IEM::sessionGet('userid') != $userid) {
					$this->ShowOTPForm('user_error',GetLang('Something_wrong'),$userid);
					break;
				}
				
				
				$userapi = new User_API();
				$loaded = $userapi->Load($userid, false);
				
				if (!$loaded ) {										 
					$this->ShowOTPForm('user_error', GetLang('Something_wrong'),$userid);
					break;
				}
				if (empty($userid) || empty($otp)) {
					$this->ShowOTPForm('otp_Error', GetLang('Bad_OTP'),$userid);
					break;
				}
				
				$GLOBALS['USERID'] =  $userid;
				
				if (!$loaded || $userapi->Get('otp') != $otp) {					
					
					$count_try = IEM::sessionGet('otp_try');
					IEM::sessionSet('otp_try', ++$count_try);
						
					if(IEM::sessionGet('otp_try') > SENDSTUDIO_SECURITY_TWO_FACTOR_AUTH_ATTEMPTS){				 					
						$this->ShowOTPForm('otp_sending_error', GetLang('Attemps_OTP'),$userid);
						break;
					} else { 
						$this->ShowOTPForm('otp_Error', GetLang('Bad_OTP'),$userid);
						break;
					}
				}

				IEM::userLogin($userid);
				$redirect = $this->_validateTakeMeToRedirect(IEM::requestGetPOST('ss_takemeto', 'index.php'));

				header('Location: ' . SENDSTUDIO_APPLICATION_URL . '/admin/' . $redirect);
				exit();
			break;

			default:
                $msg = false; $template = false;
				if ($action == 'logout') {
					$this->LoadLanguageFile('Logout');
				}
				$this->ShowLoginForm($template, $msg);
			break;
		}
	}

    public function printLoginHeader()
    {
        header('Content-type: text/html; charset="' . SENDSTUDIO_CHARSET . '"');

        $this->LoadLanguageFile();

        return $this->ParseTemplate('header_login');
    }

	/**
	* ShowLoginForm
	* This shows the login form.
	* If there is a template to use in the data/templates folder it will use that as the login form.
	* Otherwise it uses the default one below. If you pass in a message it will show that message above the login form.
	*
	* @param bool|string $template Uses the template passed in for the message (eg success / error).
	* @param bool|string $msg Prints the message passed in above the login form (eg unsuccessful attempt).
	*
	* @see FetchTemplate
	* @see PrintHeader
	* @see PrintFooter
	*
	* @return Void Doesn't return anything, just prints the login form.
	*/
    public function ShowLoginForm($template=false, $msg=false)
	{
		if (!IEM::getCurrentUser()) {
			$this->GlobalAreas['InfoTips'] = '';
		}

        $this->printLoginHeader();

		$GLOBALS['Message'] = GetLang('Help_Login');

		if ($template && $msg) {
			switch ($template) {
				case 'login_error':
					$GLOBALS['Error'] = $msg;
				break;
				case 'login_success':
					$this->GlobalAreas['Success'] = $msg;
				break;
			}
			$GLOBALS['Message'] = $this->ParseTemplate($template,true);
		}

		$username = IEM::requestGetPOST('ss_username', false);
		if ($username) {
			$GLOBALS['ss_username'] = htmlspecialchars($username, ENT_QUOTES, SENDSTUDIO_CHARSET);
 		}

		$GLOBALS['ss_takemeto'] = 'index.php';

		$this->GlobalAreas['SubmitAction'] = 'Login';

		$this->ParseTemplate('login');

		$this->PrintFooter(true);
	}
	
	/**
	* ShowResetPasswordError
	* This shows the send reset password error form. If the template and message are passed in, there will be an error message shown. If one is not present, nothing is shown.
	*
	* @param String $template If there is a template (will either be success or error template) use that as a message.
	* @param String $msg This also tells us what's going on (password has been reset and so on).
	*
	* @see PrintHeader
	* @see ParseTemplate
	* @see PrintFooter
	*
	* @return Void Doesn't return anything, only prints out the form.
	*/
    public function ShowResetPasswordError($template=false, $msg=false)
	{
		$this->printLoginHeader();
		
		if ($template && $msg) {
			switch (strtolower($template)) {
				case 'login_error':
					$GLOBALS['Error'] = $msg;
				break;
				case 'login_success':
					$this->GlobalAreas['Success'] = $msg;
				break;
			}
			$GLOBALS['Message'] = $this->ParseTemplate($template, true, false);
		}

		$this->ParseTemplate('resetpassword_error');

		$this->PrintFooter(true);
	}
	
	/**
	* ShowForgotForm
	* This shows the forgot password form and handles the multiple stages of actions. If the template and message are passed in, there will be a success/error message shown. If one is not present, nothing is shown.
	*
	* @param String $template If there is a template (will either be success or error template) use that as a message.
	* @param String $msg This also tells us what's going on (password has been reset and so on).
	*
	* @see PrintHeader
	* @see ParseTemplate
	* @see PrintFooter
	*
	* @return Void Doesn't return anything, only prints out the form.
	*/
    public function ShowForgotForm($template=false, $msg=false)
	{
		$this->printLoginHeader();

		$GLOBALS['Message'] = GetLang('Help_ForgotPassword');

		if ($template && $msg) {
			switch (strtolower($template)) {
				case 'login_error':
					$GLOBALS['Error'] = $msg;
				break;
				case 'login_success':
					$this->GlobalAreas['Success'] = $msg;
				break;
			}
			$GLOBALS['Message'] = $this->ParseTemplate($template, true, false);
		}

		$GLOBALS['SubmitAction'] = 'SendPass';

		$this->ParseTemplate('ForgotPassword');

		$this->PrintFooter(true);
	}
	/**
	* ShowOTPForm
	* This shows the OTP form and handles the confirm OTP actions. If the template and message are passed in, there will be a success/error message shown. If one is not present, nothing is shown.
	*
	* @param String $template If there is a template (will either be success or error template) use that as a message.
	* @param String $msg This also tells us what's going on (otp has been validated and so on).
	* @param String $userid This is the current user ID passed in).
	*
	* @see PrintHeader
	* @see ParseTemplate
	* @see PrintFooter
	*
	* @return Void Doesn't return anything, only prints out the form.
	*/
	public function ShowOTPForm($template=false, $msg=false, $userid='')
	{
		$this->printLoginHeader();
		$page = "";
		
		if ($template && $msg) {
			switch (strtolower($template)) {
				case 'otp_error':
					$GLOBALS['Error'] = $msg;
					$page = 'otp';
				break;

				case 'otp_success':
					
					$GLOBALS['Success'] = $msg;
					$page = 'otp';
				break;
				case 'user_error':
					$GLOBALS['Error'] = $msg;
					$page = 'otp_user_error';
				break;
				case 'otp_sending_error':
					$GLOBALS['Error'] = $msg;
					$page = 'otp_not_sending';
				break;
				    
			}
			 
			$GLOBALS['Message'] = $this->ParseTemplate($template, true, false);
		}
		$GLOBALS['ss_takemeto'] = 'index.php';

		$this->GlobalAreas['SubmitAction'] = 'ConfirmOtp&user='.$userid;
		$GLOBALS['SubmitAction'] = 'ConfirmOtp&user='.$userid;
		 $this->ParseTemplate($page);   

		$this->PrintFooter(true);
		 		
	}
	
	/**
	* mask_emailaddress
	* This function will mask/partially hide email address i.e. It gives an idea about who the user might be, without revealing the actual email address.
	*
	* @param String $emailaddress This email address will be masked.
	*
	* @return it returns masked email address.
	*/
	function mask_emailaddress($email)
	{
		if(empty($email)) {
			return false;
		}
		
		$em   = explode("@",$email);
		$name = implode('@', array_slice($em, 0, count($em)-1));
		$len  = floor(strlen($name)/2);
		$masked = substr($name,0, $len) . str_repeat('*', $len) . "@" . end($em);
		
		return $masked;
	}
	
	/**
	* mask_emailaddress
	* This function will mask/partially hide email address i.e. It gives an idea about who the user might be, without revealing the actual email address.
	*
	* @param String $emailaddress This email address will be masked.
	*
	* @return it returns masked email address.
	*/
	function get_unique_code($code_type='otp')
	{
		$return_code = '';
		
		if ($code_type) {
			switch (strtolower($code_type)) {
				case 'otp':
					$return_code = 	rand(100000,999999);
				break;
				case 'forgot_password':
					$return_code = 	md5(uniqid(rand(), true));;
				break;
			}
		}
		
		return $return_code;
	}
	
	/**
	* ShowForgotForm_Step2
	* This shows the form for changing the password. It will show the password/password confirm boxes for the user to fill in.
	*
	* @param String $username The username to show in the form. This is not editable, it is just shown for reference.
	* @param bool|string $template If there is a template (will either be success or error template) use that as a message.
	* @param bool|string $msg This also tells us what's going on (password has been reset and so on).
	*
	* @see PrintHeader
	* @see ParseTemplate
	* @see PrintFooter
	*
	* @return Void Doesn't return anything, only prints out the form.
	*/
    public function ShowForgotForm_Step2($username='', $template=false, $msg=false)
	{
        $this->printLoginHeader();

		$GLOBALS['UserName'] = htmlspecialchars($username, ENT_QUOTES, SENDSTUDIO_CHARSET);

		if ($template && $msg) {
			switch (strtolower($template)) {
				case 'login_error':
					$GLOBALS['Error'] = $msg;
					$template_page = 'ForgotPassword_Step2';
				break;
				case 'login_success':
					$GLOBALS['Message'] = $msg;
					$template = false;
					$template_page = 'ForgotPassword_Sendpass';
				break;
				case 'resetpassword':
					$GLOBALS['Message'] = $msg;
					$template = false;
					$template_page = 'resetpassword';
				break;
			}
			if ($template) {
				$GLOBALS['Message'] = $this->ParseTemplate($template, true, false);
			}
		} else {
			$template_page = 'ForgotPassword_Step2';
			$GLOBALS['Message'] = GetLang('Help_Password_Confirm_Password');
		}

		$GLOBALS['SubmitAction'] = 'ChangePassword';

		$this->ParseTemplate($template_page);

		$this->PrintFooter(true);
	}

	/**
	 * _validateTakeMeToRedirect
	 * Validate wheter or not "Take Me To" redirect string is a valid re-direct
	 *
	 * @param String $redirectString Re-direct string
	 * @return String Return a valid re-direct string
	 *
	 * @access private
	 */
    public function _validateTakeMeToRedirect($redirectString)
	{
		$defaultRedirect = 'index.php';
		$urlParts = parse_url($redirectString);

		// Don't bother checking if it is the default redirect
		if ($redirectString == $defaultRedirect) {
			return $redirectString;
		}

		// Must begin with index.php
		if (!preg_match('/^index.php/', $redirectString)) {
			return $defaultRedirect;
		}

		// Path must be index.php
		if (!isset($urlParts['path']) || strtolower($urlParts['path']) != 'index.php') {
			return $defaultRedirect;
		}

		// Query must exists
		if (!isset($urlParts['query'])) {
			return $defaultRedirect;
		}

		// Make into REQUEST string
		parse_str($urlParts['query'], $redirectRequest);

		// REQUEST redirect must have "Page" variable in it
		if (!isset($redirectRequest['Page'])) {
			return $defaultRedirect;
		}

		// Check if function exists
		if (!is_file(SENDSTUDIO_FUNCTION_DIRECTORY . '/' . strtolower($redirectRequest['Page']) . '.php')) {
			return $defaultRedirect;
		}

		return $redirectString;
	}
}
