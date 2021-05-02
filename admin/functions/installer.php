<?php
/**
* This file has the install functions in it.
*
* @version     $Id: installer.php,v 1.37 2008/02/20 22:06:02 chris Exp $
* @author Chris <chris@interspire.com>
*
* @package SendStudio
* @subpackage SendStudio_Functions
*/

/**
 * Make sure that the installer CANNOT be loaded again if the application has already been installed
 */
	$tempFile = dirname(__FILE__) . '/../includes/config.php';
	if (is_file($tempFile)) {
		require_once $tempFile;
		if (defined('SENDSTUDIO_IS_SETUP') && SENDSTUDIO_IS_SETUP == 1) {
			header('Location: ' . SENDSTUDIO_APPLICATION_URL);
			die('Redirecting .... to <a href="' . SENDSTUDIO_APPLICATION_URL . '">' . SENDSTUDIO_APPLICATION_URL . '</a>');
		}
	}
	unset($tempFile);
/**
 * -----
 */


/**
* Include the base sendstudio functions.
*/
require_once(dirname(__FILE__) . '/sendstudio_functions.php');

/**
 * Include the whitelabel file.
 */
require_once(IEM_PATH . '/language/default/whitelabel.php');

/**
* Class for the welcome page. Includes quickstats and so on.
*
* @package SendStudio
* @subpackage SendStudio_Functions
*/
class Installer extends SendStudio_Functions
{
	/**
	 * Installer API
	 */
	private $_api;

	public function __construct()
	{
	    // if iem is already installed, then we redirect to the home page
	    if (IEM::isInstalled()) {
	        header('Location: index.php');
	        exit;
	    }
		$this->_api = new IEM_Installer();
	}

	/**
	* Process
	* Works out which step we are up to in the install process and passes it off for the other methods to handle.
	*
	* @return Void Works out which step you are up to and that's it.
	*/
	public function Process()
	{
        $errors = $db_errors = $permission_errors = $server_errors = array();

		// Check permissions
		list($error, $msgs) = $this->_api->CheckPermissions();
		if ($error) {
			$permission_errors = $msgs;
		}
		// Check some server settings
		list($error, $msgs) = $this->_api->CheckServerSettings();
		if ($error) {
			$server_errors = $msgs;
		}

		$step = 0;
		if (isset($_GET['Step'])) {
			$step = (int)$_GET['Step'];
		}

		switch ($step) {
			case '1':
				$lk_check        = array();
				$required_fields = array(
					'applicationurl'         => 'application url',
					'contactemail'           => 'application email address',
					'admin_username'         => 'administrator username',
					'admin_password'         => 'administrator password',
					'admin_password_confirm' => 'confirmation password'
				);

				if (isset($_POST['licensekey'])) {
					$lk_check['licensekey'] = 'license key';
				} else {
					$lk_check['contactname'] = 'your name';
					$lk_check['contactphone'] = 'your phone number';
					$lk_check['country'] = 'your country';
				}

				// put the lk_check details as the first thing - since the license key box is the first thing on the page.
				$required_fields = array_merge($lk_check, $required_fields);

				foreach ($required_fields as $field => $desc) {
					$show_n = false;
					if (in_array(substr(strtolower($desc), 0, 1), array('a','e','i','o','u'))) {
						$show_n = true;
					}

					switch ($field) {
						case 'contactname':
						case 'contactphone':
						case 'country':
							$error_message = 'Please enter ' . $desc;
						break;

						default:
							$error_message = 'Please enter a' . (($show_n) ? 'n' : '') . ' ' . $desc;
						break;
					}

					if (!isset($_POST[$field]) || $_POST[$field] == '') {
						${$field} = '';
						$errors[] = $error_message;
						continue;
					}

					${$field} = $_POST[$field];
				}

				if (isset($_POST['licensekey'])) {
					$lk = $_POST['licensekey'];
				} else {
					// Retrieve the license key
					$lk = false;
					$request = array(
						'contactname' => $contactname,
						'contactemail' => $contactemail,
						'applicationurl' => $applicationurl,
						'contactphone' => $contactphone,
						'country' => $country,
						);
					$lk = IEM_Installer::GetLicenseKey($request);
					$_POST['licensekey'] = $lk; // this will ensure it stays in the form if there's an error
				}

				// Check the license key for validity
				list($error, $msg) = IEM_Installer::ValidateLicense($lk, $_POST['dbtype']);

				switch ($error) {
					case IEM_Installer::FIELD_INVALID:
						$errors[] = 'The license key that you entered is invalid. The URL that you generated this license key must match the URL that you are attempting to install on. Please contact Interspire at support@interspire.com to obtain a new key or purchase one from <a href="https://www.interspire.com/pricing" target="_blank">Interspire</a>.';
					break;
					case IEM_Installer::DB_UNSUPPORTED:
						$errors[] = $msg;
					break;
				}

				// Verify the admin password confirmation checks out
				if (isset($_POST['admin_password']) && $_POST['admin_password'] != '') {
					if (isset($_POST['admin_password_confirm']) && $_POST['admin_password_confirm'] != '') {
						if ($_POST['admin_password'] != $_POST['admin_password_confirm']) {
							$errors[] = 'Your passwords do not match. Please enter your password again and confirm it to make sure they are the same';
						}
					}
				}
				
				// Validate password rules.
				$auth_pass = new AuthenticationSystem();
                $result_auth_pass= $auth_pass->AuthenticatePassword($_POST['admin_password']);
				
				if ($result_auth_pass === -1) {
					$errors[] = 'Password should be at least 8 characters in length and should include at least one upper case letter, one number, and one special character.';
						
				}
				// Collect database settings
				if (!isset($_POST['dbtype'])) {
					$errors[] = 'Please choose the type of database you want to use';
				}

				if ($_POST['dbtype'] != 'mysql') {
                    $errors[] = 'Please choose a valid database type. We currently only support MySQL';
				} else {
					$found_missing_db_field = false;
					$required_db_fields = array ('dbusername' => 'database username', 'dbhostname' => 'database hostname', 'dbname' => 'database name');
					foreach ($required_db_fields as $field => $desc) {
					    $field = 'mysql_'.$field;
						if (!isset($_POST[$field]) || $_POST[$field] == '') {
							$errors[] = 'Missing required field: '.$desc;
							$found_missing_db_field = true;
							continue;
						}
					}

					// Return to form with error messages if there were errors.
					// We do this here so that it won't load the DB schema unless everything else has checked out so far.
					if (count(array_merge($permission_errors, $server_errors, $errors, $db_errors))) {
						$this->ShowForm($permission_errors, $server_errors, $errors, $db_errors);
						break;
					}

					// Collect the required settings
					$settings = array();

					$from_form = array (
						'DATABASE_TYPE'		=> 'dbtype',
						'LICENSEKEY'		=> 'licensekey',
						'APPLICATION_URL'	=> 'applicationurl',
						'EMAIL_ADDRESS'		=> 'contactemail',
					);

					foreach ($from_form as $option => $post_field) {
						$settings[$option] = $_POST[$post_field];
					}

					$db_fields_from_form = array (
						'DATABASE_USER'	=> 'dbusername',
						'DATABASE_PASS'	=> 'dbpassword',
						'DATABASE_HOST'	=> 'dbhostname',
						'DATABASE_NAME'	=> 'dbname',
						'TABLEPREFIX'	=> 'tableprefix',
					);

					foreach ($db_fields_from_form as $option => $post_field) {
						if ($settings['DATABASE_TYPE'] == 'mysql') {
							$post_field = 'mysql_' . $post_field;
						}
						$settings[$option] = $_POST[$post_field];
					}

					// Load the required settings into the API
					$this->_api->LoadRequiredSettings($settings);

					if (!$found_missing_db_field) {

						// Set up the database
						list($errcode, $msg) = $this->_api->SetupDatabase();
						switch ($errcode) {
							case IEM_Installer::SUCCESS:
								// nothing to do
								break;
							case IEM_Installer::DB_CONN_FAILED:
								$errors[] = 'Interspire Email Marketer was unable to connect to the database. Please check the settings and try again. The error message is: <br/>' . $msg;
								break;
							case IEM_Installer::DB_BAD_VERSION:
								$errors[] = 'Interspire Email Marketer requires ' . $msg['product'] . ' ' . $msg['req_version'] . ' or above to work properly. Your server is running ' . $msg['version'] . '. To complete the installation, your web host must upgrade ' . $msg['product'] . ' to this version. Please note that this is not a software problem and it is something only your web host can change.';
								break;
							case IEM_Installer::DB_UNSUPPORTED:
								$errors[] = 'This database type is not supported.';
								break;
							case IEM_Installer::DB_ALREADY_INSTALLED:
								$errors[] = 'Interspire Email Marketer seems to be already installed in this database. To continue with this installation, you will need to delete the data from this database or select a different database. You may need to contact your administrator or web hosting provider to do this.';
								break;
							case IEM_Installer::DB_OLD_INSTALL:
								$errors[] = 'An older version of Interspire Email Marketer is already installed in this database.<br><br>If you would like to install a fresh copy of Interspire Email Marketer, then either delete the data from this database (contact your administrator or web host if you need help) or select a new database.';
								break;
							case IEM_Installer::DB_INSUFFICIENT_PRIV:
								$errors[] = 'The database user does not have sufficient privileges to install the database. Please ensure the database user has permission to CREATE, CREATE INDEX, INSERT, SELECT, UPDATE, DELETE, ALTER and DROP.';
								break;
							case IEM_Installer::DB_QUERY_ERROR:
								foreach ($msg as $errmsg) {
									$db_errors[] = 'Unable to run the following query: ' . $errmsg;
								}
								break;
							default:
								$errors[] = 'There was an error setting up the database. Please contact your host about this problem. The error was: ' . $msg;
								break;
						}
					}
				}

				// Save the default settings into the database
				if (empty($errors) && empty($db_errors)) {
					list($error, $msg) = $this->_api->SaveDefaultSettings();
					
					if ($error) {
						$errors[] = 'There was a problem loading the default settings. The error was: ' . $msg;
					}
				}

				// Register the Event Listeners
				try {
					IEM_Installer::RegisterEventListeners();
				} catch (Exception $e) {
					$errors[] = 'There was a problem registering the Event Listeners.';
				}

				// Return to form with error messages if there were errors
				if (count(array_merge($permission_errors, $server_errors, $errors, $db_errors))) {
					$this->ShowForm($permission_errors, $server_errors, $errors, $db_errors);
					break;
				}

				// If we get to this point then the installation has been successful.

				// Create the default custom fields
				$this->_api->CreateCustomFields();

				// Install the default add-ons
				$this->_api->RegisterAddons();

				// Generate server stats image
				$server_info_image = '';
				if (isset($_POST['serverinfo']) && $_POST['serverinfo'] == 1) {
					require_once(IEM_PATH . '/ext/server_stats/server_stats.php');
					$server_stats_info = serverStats_Send('install', '', IEM::VERSION, 'SS');
					if ($server_stats_info['InfoSent'] === false) {
						$server_info_image = $server_stats_info['InfoImage'];
					}
				}

				$this->PrintInstallHeader();
				?>
					<div id="box" style="width: 600px; top: 20px; margin:auto;">
						<br /><br /><br /><br />
						<table style="margin:auto;"><tr><td style="border:solid 2px #DDD; padding:20px; background-color:#FFF; width:450px">
						<table>
						  <tr>
							<td class="Heading1">
								<img src="images/logo.jpg" />
							</td>
						  </tr>
						  <tr>
							<td style="padding:10px 0px 5px 0px">
								Interspire Email Marketer has been installed successfully. Your control panel username is 
								<b style='font-size:14px; color:#FF5C1B'><?php echo $_POST['admin_username']; ?></b> and your password is <b
								style='font-size:14px; color:#FF5C1B'><?php echo $_POST['admin_password']; ?></b>.
								<br /><br />

								<input type="button" value="Login Now" onclick="document.location.href='./index.php'" style="font-size:11px" />
							</td>
						  </tr>
						</table>
						</td></tr></table>
						<div style="padding:10px; margin-bottom:20px; text-align:center" class="InstallPageFooter">
							Powered by <a href="https://www.interspire.com/" target="_blank">Interspire Email Marketer</a> &copy; 2004-<?php echo date('Y'); ?> Interspire Pty. Ltd.
						</div>
					</div>
				<?php
				echo $server_info_image;
				$this->PrintInstallFooter();
			break;

			default:
				$this->ShowForm($permission_errors, $server_errors);
		}
	}

	/**
	 * ShowForm
	 * Show install form
	 *
	 * @param Array $permission_errors File/dir permission error messages
	 * @param Array $server_errors Errors related to server configuration
	 * @param Array $install_errors Install error messages
	 * @param Array $db_errors DB error messages
	 *
	 * @return Void Returns nothing
	 */
	private function ShowForm($permission_errors=array(), $server_errors=array(), $install_errors=array(), $db_errors=array())
	{
		if (!defined('SENDSTUDIO_FREE_TRIAL')) {
			define('SENDSTUDIO_FREE_TRIAL', false);
		}

		$error_message = '';

		if (!empty($server_errors) || !empty($permission_errors)) {
			$error_message = '<h3>Installing Interspire Email Marketer</h3>';
			if (!empty($server_errors)) {
				$error_message .= 'The following configuration problems were found with the server and must be resolved before installation can continue:<br />';
				$error_message .= '<ul><li>' . implode('</li><li>', $server_errors) . '</li></ul>';
			} elseif (!empty($permission_errors)) {
				$error_message .= 'Before you can install Interspire Email Marketer you need to set the appropriate permissions on the files/folders listed below:<br/>';
				$error_message .= '<ul><li>' . implode('</li><li>', $permission_errors) . '</li></ul>';
			}
			$error_message .= '<input type=\'button\' value=\'Try Again\' style=\'margin-bottom:20px; font-size:11px\' onclick="window.location.href=\'./index.php\'" />';
		}

		$base_url = preg_replace('%/admin/index.php%', '', $_SERVER['PHP_SELF']);

		$http = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') ? 'https' : 'http';
		$applicationurl = $http . '://' . $_SERVER['HTTP_HOST'] . $base_url;

		$this->PrintInstallHeader();
		?>
		<form method="post" action="index.php?Page=Installer&amp;Step=1" onsubmit="return CheckForm(this);">
			<div id="box" style="width: 600px; top: 20px; margin:auto;">
				<br />
				<table style="margin:auto;">
					<tr>
						<td style="border:solid 2px #DDD; padding:20px; background-color:#FFF; width:450px">
							<table>
								<tr>
									<td class="Heading1">
										<img src="images/logo.jpg" />
									</td>
								</tr>
								<?php if (!empty($install_errors)) { ?>
									<?php $install_error_message = implode('<br/><br/>', $install_errors); ?>
									<tr>
										<td class="HelpInfo">
											<h3 style='padding-bottom:10px; color: red;'>Oops, something went wrong...</h3>
											<?php echo $install_error_message; ?>
										</td>
									</tr>
								<?php } elseif (!empty($db_errors)) { ?>
									<?php $db_error_message = implode('<br/><br/>', $db_errors); ?>
									<tr>
										<td class="HelpInfo">
											<h3 style='padding-bottom:10px; color: red;'>Oops, something went wrong...</h3>
											While trying to install the database the following errors occurred:<br/>
											<?php echo $db_error_message; ?>
										</td>
									</tr>
								<?php } else { ?>
									<tr>
										<td class="HelpInfo">
											<h3 style='padding-bottom:10px'>Install Interspire Email Marketer</h3>
											Complete the form below to install Interspire Email Marketer. Required fields are marked with an asterisk. Move your mouse over the help icons for help.<br/><br/>
										</td>
									</tr>
								<?php } ?>
								<tr>
									<td class="FormContent">
										<table>
											<?php if (!SENDSTUDIO_FREE_TRIAL) { ?>
												<tr>
													<td nowrap="nowrap" colspan="2"><h3>License Details</h3></td>
												</tr>
												<tr>
													<td nowrap="nowrap"><span class="required">*</span> License Key:</td>
													<td>
														<input	type="text"
																name="licensekey"
																id="licensekey"
																class="Field250"
																style="width: 200px;"
																value="<?php echo (isset($_POST['licensekey'])) ? htmlentities($_POST['licensekey'], ENT_QUOTES, 'UTF-8') : ''; ?>" />
														<img	onmouseout="HideHelp('keyhelp');"
																onmouseover="ShowHelp('keyhelp', 'License Key', 'Your license key allows you to install Interspire Email Marketer on one web site. You need to check your license key in your IEM order email before you can proceed with the installation wizard. Once you\'ve received your license key, paste it into this box.')"
																src="images/help.gif"
																width="24"
																height="16"
																border="0" />
														<div style="display:none" id="keyhelp"></div>
														<img id="kc" src="images/blank.gif" width="18" height="18" />
													</td>
												</tr>
											<?php } ?>
											<tr>
												<td nowrap="nowrap" colspan="2"><br/><h3>Email Marketer Details</h3></td>
											</tr>
											<tr>
												<td nowrap="nowrap"><span class="required">*</span> Application URL:</td>
												<td>
													<input	type="text"
															name="applicationurl"
															id="applicationurl"
															class="Field250"
															style="width: 200px;"
															value="<?php echo htmlentities($applicationurl, ENT_QUOTES, 'UTF-8'); ?>" />
													<img	onmouseout="HideHelp('applicationurl_help');"
															onmouseover="ShowHelp('applicationurl_help', 'Email Marketer Web Site', 'The full path to your email marketer as you would type it into a web browser. This does not include the admin/ folder.')"
															src="images/help.gif"
															width="24"
															height="16"
															border="0" />
													<div style="display:none" id="applicationurl_help"></div>
												</td>
											</tr>
											<tr>
												<td nowrap="nowrap" colspan="2"><br/><h3>Admin Account Details</h3></td>
											</tr>
											<?php if (SENDSTUDIO_FREE_TRIAL) { ?>
												<tr>
													<td align="left" colspan="2">
														Interspire Email Marketer will be installed as a fully functional 30 day free trial.
													</td>
												</tr>
												<tr>
													<td nowrap="nowrap"><span class="required">*</span> Your Name:</td>
													<td align="left">
														<input	type="text"
																name="contactname"
																id="contactname"
																class="Field250"
																style="width: 200px;"
																value="<?php echo (isset($_POST['contactname'])) ? htmlentities($_POST['contactname'], ENT_QUOTES, 'UTF-8') : ''; ?>" />
														<img 	onmouseout="HideHelp('namehelp');"
																onmouseover="ShowHelp('namehelp', 'Please enter your name', 'Before you can install the free trial, you must enter your name.')"
																src="images/help.gif"
																width="24"
																height="16"
																border="0" />
														<div style="display:none" id="namehelp"></div>
														<img id="kc" src="images/blank.gif" width="18" height="18" />
													</td>
												</tr>
												<tr>
													<td nowrap="nowrap"><span class="required">*</span> Phone Number:</td>
													<td align="left">
														<input	type="text"
																name="contactphone"
																id="contactphone"
																class="Field250"
																style="width: 200px;"
																value="<?php echo (isset($_POST['contactphone'])) ? htmlentities($_POST['contactphone'], ENT_QUOTES, 'UTF-8') : ''; ?>" />
														<img 	onmouseout="HideHelp('phonehelp');"
																onmouseover="ShowHelp('phonehelp', 'Please enter your phone number', 'Before you can install the free trial, you need to enter your phone number.')"
																src="images/help.gif"
																width="24"
																height="16"
																border="0" />
														<div style="display:none" id="phonehelp"></div>
														<img id="kc" src="images/blank.gif" width="18" height="18" />
													</td>
												</tr>
												<tr>
													<td nowrap="nowrap"><span class="required">*</span> Country:</td>
													<td align="left">
														<select id="country" name="country" class="Field250" style="width: 200px;">
															<option value="">-- Please choose your country --</option>
															<?php
																$countryList = IEM_Installer::GetCountryList();
																$requestCountry = isset($_POST['country'])? $_POST['country'] : 0;
															?>
															<?php foreach ($countryList as $country) { ?>
																<option value="<?php echo $country['country_name']; ?>"<?php if ($requestCountry === $country['country_name']) { echo 'selected="selected"'; } ?>><?php echo $country['country_name']; ?></option>
															<?php } ?>
															<?php
																unset($requestCountry);
																unset($countryList);
															?>
														</select>
														<img 	onmouseout="HideHelp('countryhelp');"
																onmouseover="ShowHelp('countryhelp', 'Please select your country', 'Before you can install the free trial, you need to select your country.')"
																src="images/help.gif"
																width="24"
																height="16"
																border="0" />
														<div style="display:none" id="countryhelp"></div>
														<img id="kc" src="images/blank.gif" width="18" height="18" />
													</td>
												</tr>
											<?php } ?>
											<tr>
												<td nowrap="nowrap"><span class="required">*</span> Email Address:</td>
												<td>
													<input	type="text"
															name="contactemail"
															id="contactemail"
															class="Field250"
															style="width: 200px;"
															value="<?php echo (isset($_POST['contactemail'])) ? htmlentities($_POST['contactemail'], ENT_QUOTES, 'UTF-8') : ''; ?>" />
													<img	onmouseout="HideHelp('contactemail_help');"
															onmouseover="ShowHelp('contactemail_help', 'Application Email Address', 'Type your email address here. Your email address is used when you need to retrieve or change the password for your user account.')"
															src="images/help.gif"
															width="24"
															height="16"
															border="0" />
													<div style="display:none" id="contactemail_help"></div>
												</td>
											</tr>
											<tr>
												<td nowrap="nowrap"><span class="required">*</span> Administrator Username:</td>
												<td>
												    <input  type="text"
												            name="admin_username"
												            id="admin_username"
												            class="Field250"
												            style="width: 200px;"
												            value="<?php echo (isset($_POST['admin_username'])) ? htmlentities($_POST['admin_username'], ENT_QUOTES, 'UTF-8') : ''; ?>" />
												    <img    onmouseout="HideHelp('admin_username_help');"
												            onmouseover="ShowHelp('admin_username_help', 'Administrator Username', 'Type the username you would like to user for the global administrator account.')"
												            src="images/help.gif"
												            width="24"
												            height="16"
												            border="0" />
												    <div style="display:none" id="admin_username_help"></div>
												</td>
											</tr>
											<tr>
												<td nowrap="nowrap"><span class="required">*</span> Choose a Password:</td>
												<td>
													<input	type="password"
															name="admin_password"
															id="admin_password"
															class="Field250"
															style="width: 200px;"
															value=""
															autocomplete="off" />
													<img	onmouseout="HideHelp('admin_password_help');"
															onmouseover="ShowHelp('admin_password_help', 'Please enter a password', 'Please choose a password for the global administrator account. <p><u>Password Policy</u>: Password should be at least 8 characters in length and should include at least one upper case letter, one number, and one special character.</p>')"
															src="images/help.gif"
															width="24"
															height="16"
															border="0" />
													<div style="display:none" id="admin_password_help"></div>
												</td>
											</tr>
											<tr>
												<td nowrap="nowrap"><span class="required">*</span> Confirm Your Password:</td>
												<td>
													<input	type="password"
															name="admin_password_confirm"
															id="admin_password_confirm"
															class="Field250"
															style="width: 200px;"
															value=""
															autocomplete="off" />
													<img	onmouseout="HideHelp('admin_password_confirm_help');"
															onmouseover="ShowHelp('admin_password_confirm_help', 'Confirm your password', 'Please confirm the administrator password.')"
															src="images/help.gif"
															width="24"
															height="16"
															border="0" />
													<div style="display:none" id="admin_password_confirm_help"></div>
												</td>
											</tr>
											<tr>
												<td nowrap="nowrap" colspan="2"><br/><h3>Database Details</h3></td>
											</tr>
											<tr>
												<td nowrap="nowrap" colspan="2">
													<input	type="radio"
															name="dbtype"
															id="dbtype_mysql"
															value="mysql"
															onclick="javascript:showDB('mysql');"
															CHECKED />
													<label for="dbtype_mysql">I want to use a MySQL Database</label>
												</td>
											</tr>
										</table>

										<table class="mysql_DBDetails">
											<tr>
												<td nowrap="nowrap" colspan="2"><br/><h3>MySQL Database Details</h3></td>
											</tr>
											<tr>
												<td nowrap="nowrap" colspan="2">
													<input	type="radio"
															name="mysql_db_choice"
															id="mysql_db_choice1"
															value="mysql_db_choice1"
															onclick="javascript:showDbDetails('mysql', 1);"
															<?php echo (isset($_POST['mysql_db_choice']) && $_POST['mysql_db_choice'] == 'mysql_db_choice1') ? ' CHECKED' : ''; ?> />
													<label for="mysql_db_choice1">I have already created a MySQL database</label>
												</td>
											</tr>
											<tr>
												<td nowrap="nowrap" colspan="2" >
													<input	type="radio"
															name="mysql_db_choice"
															id="mysql_db_choice2"
															value="mysql_db_choice2"
															onclick="javascript:showDbDetails('mysql', 2);"
															<?php echo (isset($_POST['mysql_db_choice']) && $_POST['mysql_db_choice'] == 'mysql_db_choice2') ? ' CHECKED' : ''; ?> />
													<label for="mysql_db_choice2">I have not created a MySQL database</label>
												</td>
											</tr>
										</table>

										<table class="mysql_DBDetails_created">
											<tr>
												<td nowrap="nowrap"><span class="required">*</span> Database User:</td>
												<td>
													<input type="text" name="mysql_dbusername" id="mysql_dbusername" class="Field250" style="width: 200px;" value="<?php echo (isset($_POST['mysql_dbusername'])) ? htmlentities($_POST['mysql_dbusername'], ENT_QUOTES, 'UTF-8') : ''; ?>" />
													<img onmouseout="HideHelp('mysql_dbusernamehelp');" onmouseover="ShowHelp('mysql_dbusernamehelp', 'Database User', 'The username of the MySQL account that has access to your database, such as \'root\'.')" src="images/help.gif" width="24" height="16" border="0" />
													<div style="display:none" id="mysql_dbusernamehelp"></div>
												</td>
											</tr>
											<tr>
												<td nowrap="nowrap">&nbsp;&nbsp; Database Password:</td>
												<td>
													<input type="password" name="mysql_dbpassword" id="mysql_dbpassword" class="Field250" style="width: 200px;" value="<?php echo (isset($_POST['mysql_dbpassword'])) ? htmlentities($_POST['mysql_dbpassword'], ENT_QUOTES, 'UTF-8') : ''; ?>" />
													<img onmouseout="HideHelp('mysql_dbpasshelp');" onmouseover="ShowHelp('mysql_dbpasshelp', 'Database Password', 'The password of the MySQL account that has access to your database. This field is optional as not all MySQL accounts have passwords.')" src="images/help.gif" width="24" height="16" border="0" />
													<div style="display:none" id="mysql_dbpasshelp"></div>
												</td>
											</tr>
											<tr>

												<td nowrap="nowrap"><span class="required">*</span> Database Hostname:</td>
												<td>
													<input type="text" name="mysql_dbhostname" id="mysql_dbhostname" class="Field250" style="width: 200px;" value="<?php echo (isset($_POST['mysql_dbhostname'])) ? htmlentities($_POST['mysql_dbhostname'], ENT_QUOTES, 'UTF-8') : 'localhost'; ?>" />
													<img onmouseout="HideHelp('mysql_dbhostnamehelp');" onmouseover="ShowHelp('mysql_dbhostnamehelp', 'Database Hostname', 'The server where your MySQL database is located. Most of the time this is simply \'localhost\', however your web host might have a separate database server. If this is the case then type your database hostname here, such as \'db1.myhost.com\'.')" src="images/help.gif" width="24" height="16" border="0" />
													<div style="display:none" id="mysql_dbhostnamehelp"></div>
												</td>
											</tr>
											<tr>
												<td nowrap="nowrap"><span class="required">*</span> Database Name:</td>
												<td>
													<input type="text" name="mysql_dbname" id="mysql_dbname" class="Field250" style="width: 200px;" value="<?php echo (isset($_POST['mysql_dbname'])) ? htmlentities($_POST['mysql_dbname'], ENT_QUOTES, 'UTF-8') : ''; ?>" />
													<img onmouseout="HideHelp('mysql_dbnamehelp');" onmouseover="ShowHelp('mysql_dbnamehelp', 'Database Name', 'The name of your MySQL database, which you should have already created from either your web hosting control panel or the database tool PHPMyAdmin.')" src="images/help.gif" width="24" height="16" border="0" />
													<div style="display:none" id="mysql_dbnamehelp"></div>
												</td>
											</tr>
											<tr>
												<td nowrap="nowrap">&nbsp;&nbsp; Database Table Prefix:</td>
												<td>
													<input type="text" name="mysql_tableprefix" id="mysql_tableprefix" class="Field250" style="width: 200px;" value="<?php echo (isset($_POST['mysql_tableprefix'])) ? htmlentities($_POST['mysql_tableprefix'], ENT_QUOTES, 'UTF-8') : 'email_'; ?>" />
													<img onmouseout="HideHelp('mysql_dbprefixhelp');" onmouseover="ShowHelp('mysql_dbprefixhelp', 'Database Table Prefix', 'An optional word or phrase that will be prefixed to each table in your MySQL database before they are created.<br /><br />If you have multiple applications installed and are using the same database then you can specify a table prefix to make it easier for you to tell which tables are used by which applications.')" src="images/help.gif" width="24" height="16" border="0" />
													<div style="display:none" id="mysql_dbprefixhelp"></div>
												</td>
											</tr>
										</table>

										<table class="mysql_DBDetails_help">
											<tr>
												<td colspan="2" class="HelpInfo">
													<h3 style="padding-bottom:10px">What is a MySQL Database?</h3>
													A MySQL database is where Interspire Email Marketer saves your list of products, orders, customers, etc. You need to create a MySQL database before the installer can continue:
												</td>
											</tr>
										</table>

										<table>
											<tr>
												<td nowrap="nowrap" colspan="2"><br/><h3>Server Configuration Details</h3></td>
											</tr>
											<tr>
												<td nowrap="nowrap" colspan="2">
													<input type="checkbox" name="serverinfo" id="serverinfo" value="1" checked="checked" />
													<label for="serverinfo">Send anonymous server details to Interspire (recommended)</label>
													<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
													<a href="#" onclick="alert('-- Server Configuration Details --\n\nIf you tick this box then various details about your web server will be sent back to Interspire anonymously. These details include which versions of PHP and MySQL you\'re running, server operating system, which extensions your server is running, etc.\n\nSending this information back to Interspire helps us to make our software as compatible as possible with as many different server configurations as possible.'); return false;" style="color:gray">What will be sent and why?</a>
												</td>
											</tr>
											<tr>
											<td>&nbsp;</td>
												<td>
													<br />
													<input type="submit" name="SubmitButton" value="Continue" class="FormButton" />
												</td>
											</tr>
											<tr>
												<td class="Gap"></td>
											</tr>
										</table>
									</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>

				<div style="padding:10px; margin-bottom:20px; text-align:center" class="InstallPageFooter">
					Powered by <a href="https://www.interspire.com/" target="_blank">Interspire Email Marketer</a> &copy; 2004-<?php echo date('Y'); ?> Interspire Pty. Ltd.
				</div>
			</div>
		</form>

		<div id="permissionsBox" style="display:none;">
			<div style="background-image:url('images/permissions_error.gif'); background-position:right bottom; background-repeat:no-repeat; height:100%; margin-right:20px;">
				<?php echo $error_message; ?>
			</div>
		</div>

		<?php if (!empty($permission_errors) || !empty($server_errors)) { ?>
			<script>
				$(document).ready(function() {
					tb_show('', '#TB_inline?height=300&width=450&inlineId=permissionsBox&modal=true', '');
				});
			</script>
		<?php } ?>

		<script>
			$(function() {
				$('form input').focus(function() { this.select(); });
			});

			function CheckForm(form)
			{
				<?php if (!SENDSTUDIO_FREE_TRIAL) { ?>
					if (form.licensekey.value == '') {
						alert("Please enter your license key.");
						form.licensekey.focus();
						return false;
					}
				<?php } else { ?>
					if ($.trim(form.contactname.value) == '') {
						alert("Please enter your name.");
						form.contactname.focus();
						return false;
					}

					if ($.trim(form.contactphone.value) == '') {
						alert("Please enter your phone number.");
						form.contactphone.focus();
						return false;
					}

					if (form.country.value == '') {
						alert("Please select your country.");
						form.country.focus();
						return false;
					}
				<?php } ?>

				if (form.applicationurl.value == '') {
					alert("Please enter the applications url.");
					form.applicationurl.focus();
					return false;
				}

				if ($('#contactemail').val().indexOf('@') == -1 || $('#contactemail').val().indexOf('.') == -1 || $('#contactemail').val().length <= 3) {
					alert('Please enter a valid email address.');
					$('#contactemail').focus();
					$('#contactemail').select();
					return false;
				}

				// validate the admin username
				// it is required
				if (form.admin_username.value == '') {
				    alert('Please enter an administrator username.');

				    form.admin_username.select();
				    form.admin_username.focus();

				    return false;
				}

				// validate the administrator password length
				if (form.admin_password.value == '' || form.admin_password.value.length < 8) {
					alert("Password should be at least 8 characters in length and should include at least one upper case letter, one number, and one special character.");
					form.admin_password.select();
					form.admin_password.focus();
					return false;
				}
				
				// validate the administrator password rules
				var re = /^(?=.*\d)(?=.*[!@#$%^&*])(?=.*[a-z])(?=.*[A-Z]).{8,}$/; 
				if(!re.test($('#admin_password').val())){   
					alert("Password should be at least 8 characters in length and should include at least one upper case letter, one number, and one special character.");
					$('#admin_password').focus().select();
					return false;
				}
				
				// the confirmation password is required
				if (form.admin_password_confirm.value == '') {
					alert("Please confirm the administrator password.");
					form.admin_password_confirm.focus();
					return false;
				}

				// the confirmation password must match the first administrator password
				if (form.admin_password.value != form.admin_password_confirm.value) {
					alert("The passwords do not match. Please try again.");
					form.admin_password_confirm.select();
					form.admin_password_confirm.focus();
					return false;
				}

				dbval = $("input[@name='dbtype']:checked").val();
				if (!dbval) {
					alert("Please choose the type of database you wish to use.");
					return false;
				}

				if (dbval == 'mysql') {
					$('#mysql_db_choice1').click();
					if (form.mysql_dbusername.value == '') {
						alert("Please enter your mysql username");
						form.mysql_dbusername.focus();
						return false;
					}

					if (form.mysql_dbhostname.value == '') {
						alert("Please enter your mysql hostname");
						form.mysql_dbhostname.focus();
						return false;
					}

					if (form.mysql_dbname.value == '') {
						alert("Please enter your mysql database name");
						form.mysql_dbname.focus();
						return false;
					}

					if (form.mysql_dbpassword.value.includes('"')) {
						alert("Your mysql database password contains a double quote which is known to cause issues.");
						form.mysql_dbpassword.focus();
						return false;
					}
				}

				return true;
			}

			function showDB(dbtype)
			{
				var display_classes = new Array (
						'DBDetails',
						'DBDetails_created',
						'DBDetails_help'
					);

				for (var j = 0; j < display_classes.length; j++) {
					classname = '.mysql_' + display_classes[j];
					$(classname).hide();
				}

				$('.mysql_DBDetails').show();
			}

			function showDbDetails(dbtype, choice)
			{
				showDB(dbtype);
				if (choice == 1) {
					$('.mysql_DBDetails_created').show();
				}
				if (choice == 2) {
					$('.mysql_DBDetails_help').show();
				}
			}

			$(document).ready(function() {
				dbval = $("input[@name='dbtype']:checked").val();
				if (dbval == 'mysql') {
					chosen_value = $("input[@name='mysql_db_choice']:checked").val();
					choice = 0;
					if (chosen_value == 'mysql_db_choice1') {
						choice = 1;
					}
					if (chosen_value == 'mysql_db_choice2') {
						choice = 2;
					}
					showDbDetails('mysql', choice);
					return;
				}
			});

		</script>
		<?php
		$this->PrintInstallFooter();
	}

	/**
	 * PrintHeader
	 * Print HTML header
	 * @return Void Returns nothing
	 */
	public function PrintInstallHeader()
	{
		?>
		<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
		<html>
		<head>
			<title>Interspire Email Marketer</title>
			<meta http-equiv="Content-Type" content="text/html; charset='UTF-8'" />
			<link rel="stylesheet" href="includes/styles/stylesheet.css" type="text/css" />
			<link rel="stylesheet" href="includes/styles/tabmenu.css" type="text/css" />
			<link rel="stylesheet" href="includes/styles/thickbox.css" type="text/css" />

			<!--[if IE]>
			<style type="text/css">
				@import url("includes/styles/ie.css");
			</style>
			<![endif]-->

			<script src="includes/js/jquery.js"></script>
			<script src="includes/js/jquery/thickbox.js"></script>
			<script src="includes/js/javascript.js"></script>
			<style>
				.InstallPageFooter {
					color:#676767;
					font-family:Tahoma,Arial;
					font-size:11px;
				}

				h3
				{
					margin: 1px;
					padding: 2px;
					font-size:13px;
				}

				.mysql_DBDetails,
				.mysql_DBDetails_created,
				.mysql_DBDetails_help,
				{
					padding:10px 10px 10px 20px;
					display: none;
				}

				.HelpInfo {
					background:lightyellow url(images/warning.gif) no-repeat scroll 10px 7px;
					border:1px solid #EAEAEA;
					margin:5px;
					padding:10px 10px 10px 36px;
				}

			</style>

		</head>

		<body>

		<?php
	}

	/**
	 * PrintFooter
	 * Print HTML footer
	 * @return Void Returns nothing
	 */
	public function PrintInstallFooter()
	{
		?>
		</body>
		</html>
		<?php
	}

}
