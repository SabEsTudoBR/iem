<?php
/**
* Language file variables for the login page.
*
* @see GetLang
*
* @version     $Id: login.php,v 1.11 2008/01/08 04:09:40 scott Exp $
* @author Chris <chris@interspire.com>
*
* @package SendStudio
* @subpackage Language
*/

/**
* Here are all of the variables for the login area... Please backup before you start!
*/


define('LNG_Login', 'Login');
define('LNG_UserName', 'Username');
define('LNG_EnterPassword', 'Password');
define('LNG_NoUsername', 'Please enter your username.');
define('LNG_NoPassword', 'Please enter a password.');
define('LNG_Something_wrong', 'Something went wrong.');
/**
* Forgot password page.
*/
define('LNG_ForgotPasswordDetails', 'Enter your details below.');
define('LNG_NewPassword', 'New Password');
define('LNG_BadLogin_Forgot', 'That username doesn\'t exist. Please try again.');
define('LNG_ChangePasswordSubject', 'Change your password');
define('LNG_ChangePasswordEmail', 'You have recently chosen to change your control panel password. To confirm this, please click on the following link: %s');
define('LNG_PasswordUpdated', 'Your password has been updated successfully. Please login below.');
define('LNG_BadLogin_Link', 'The link you received in the email is invalid. Please try again.');
define('LNG_ChangePassword', 'Change Password');

define('LNG_LoginTitle', 'Login');

define('LNG_ChangePassword_SendingFailed', '<b>Important:</b> Email did not send, please contact your system administrator.');
define('LNG_NoValidPassword', 'Password should be at least 8 characters in length and should include at least one upper case letter, one number, and one special character.');
define('LNG_ValidPassword_Help', 'Password should be at least 8 characters in length and should include at least one upper case letter, one number, and one special character.');

/**
***************************
* Changed/added in v5.0
***************************
*/
define('LNG_SendPassword', 'Send Email');
define('LNG_OTP', 'OTP');
define('LNG_Bad_OTP', 'Wrong OTP, please enter correct OTP');
define('LNG_EmailNotSent_OTP', 'OTP Not Sent. Please contact your administrator!');
define('LNG_Help_OTP', 'Check your Email for OTP and enter the OTP which sent to your Email Address: %s');
define('LNG_EmailSubject_OTP', 'Your One Time Password(OTP) request for Secure Login');
define('LNG_EmailContent_OTP', 'Your One Time Password: %s');

define('LNG_BadLogin', 'The username or password you provided are incorrect. Please check them and try again.');
define('LNG_Help_ForgotPassword', 'Fill in the form to generate a new password. An email will be sent to you containing a link which you must click to confirm your password change.');
define('LNG_Help_Login', 'Login with your username and password below.');
define('LNG_Help_Password_Confirm_Password', 'Please enter new and confirm password.');    
define('LNG_Help_Password_Reset_Email', 'Please enter new and confirm password.'); 
define('LNG_ChangePassword_Emailed', 'Please check your inbox/junk mail folder. You\'ve just been sent an email that contains a link to change your password.');
define('LNG_ForgotPasswordReminder', '<a href="index.php?Page=Login&Action=ForgotPass" style="font-size: 11px;">Forgot your password?</a>');

define('LNG_TakeMeTo', 'Take Me To');
define('LNG_TakeMeTo_HomePage', 'Home Page');
define('LNG_TakeMeTo_Contacts', 'My Contacts');
define('LNG_TakeMeTo_Lists', 'My Contact Lists');
define('LNG_TakeMeTo_Campaign', 'My Email Campaigns');
define('LNG_TakeMeTo_Autoresponder', 'My Autoresponder');
define('LNG_TakeMeTo_Statistics', 'My Campaign Statistics');
define('LNG_TakeMeTo_Segments', 'My Segments');

/**
***************************
* Changed/Added in v5.0.10
***************************
*/
define('LNG_PleaseWaitAWhile', 'Multiple failed login attempts detected. Please wait 15 minutes before attempting to login again.');

/**
***************************
* Changed/Added in v5.7.0
***************************
*/
define('LNG_ApplicationInactive_Admin', 'This software has been disabled due to a possible license violation. If you feel you have received this message in error, please contact Interspire.');
define('LNG_ApplicationInactive_Regular', 'This application is currently down for maintenance and is not available. Please try again later.');
