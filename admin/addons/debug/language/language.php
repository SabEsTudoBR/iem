<?php
/**
 * This is the language file for the 'debug' addon.
 *
 * @package Interspire_Addons
 * @subpackage Addons_debug
 */

define('LNG_Addon_debug_Menu_Text', 'Debugging Management');
define('LNG_Email_Debug', 'Email Debug');
define('LNG_Enable_Disable', ' Enable/Disable ');
define('LNG_Email_Off', 'Debug Email OFF');
define('LNG_Email_On_Off', 'Debug Email ON/OFF');
define('LNG_UseEmailDebug', 'Email Debugging');
define('LNG_HLP_UseEmailDebug', 'Choose this option if you want to enable/disable email debug.');
define('LNG_DeleteLogExportFile', 'Delete log File');
define('LNG_UseBounceDebug', 'Bounce Debugging');
define('LNG_HLP_UseBounceDebug', 'Choose this option if you want to enable/disable bounce debug.');

define('LNG_Manage_Debug_Log_File', 'Manage Debug Log File');
define('LNG_Total_Files', 'Total Debug Files');
define('LNG_Bounce_Debug', 'Bounce Debug');
define('LNG_Bounce_On_Off', 'Debug Bounce ON/OFF');
define('LNG_Bounce_Off', 'Debug Bounce OFF');
define('LNG_Email_Debugging', 'Email Debugging');
define('LNG_Delete_Debug_Logfile', 'Delete Log File');
define('LNG_ViewLogFile', 'View Log File');

define('LNG_Email_Log_Deleted', 'Email debug log file has been deleted');
define('LNG_Bounce_Log_Deleted', 'Bounce debug log file has been deleted');
define('LNG_Autoresponder_Log_Deleted', 'Autoresponder debug log files have been deleted');
define('LNG_Logs_File_Deleted', 'Logs file have been deleted');
define('LNG_Debug_Actions', 'Action');
define('LNG_Title_DebugLog_Filename', 'File Name');
define('LNG_Title_DebugLog_date', 'Date');
define('LNG_Title_DebugLog_size', 'Size');

define('LNG_Log_File_Not_Exist', 'Log File No Exist');

define('LNG_Download_Log_File_Text', 'Download Log File');

define('LNG_TriggerEmail_Debug', 'Trigger Email Debug');
define('LNG_TriggerEmail_On_Off', 'Debug Trigger Email ON/OFF');
define('LNG_JobTriggerEmails_Debugging', 'Trigger Email Debugging');

define('LNG_UseTriggerEmailDebug', 'Trigger Email Debugging');
define('LNG_HLP_UseTriggerEmailDebug', 'Choose this option if you want to enable/disable trigger email debug.');

define('LNG_Autoresponder_Debug', 'Autoresponder Debug');
define('LNG_Autoresponder_On_Off', 'Debug Autoresponder ON/OFF');
define('LNG_UseAutoresponderDebug', 'Autoresponder Debugging');
define('LNG_HLP_UseAutoresponderDebug', 'Choose this option if you want to enable/disable autoresponder debug.');
define('LNG_Autoresponder_Debugging', 'Autoresponder Debugging'); 

define('LNG_Bounce_Debugging', 'Bounce Debugging');
define('LNG_Message', 'Email/Bounce Debugging (ON/OFF),download and delete Log file.');
define('LNG_Addon_debug_Menu_Description', 'Checks the integrity of the database.');
 
define('LNG_Addon_debug_Heading', 'Check Database Integrity');
define('LNG_Addon_debug_Intro', 'Click the button below to check your database for errors.');
define('LNG_Addon_debug_Heading_Checked', 'Database Check Finished');
define('LNG_Addon_debug_Heading_Repaired', 'Database Repair Finished');

define('LNG_Addon_debug_Button_Start', 'Check Database Now...');
define('LNG_Addon_debug_Button_Continue', 'Continue');
define('LNG_Addon_debug_Button_FixProblems', 'Fix Problems');

define('LNG_Addon_debug_Begin', 'Click "' . LNG_Addon_debug_Button_Start . '" to begin.');

define('LNG_Addon_debug_ProgressTitleCheck', 'Database Check in Progress...');
define('LNG_Addon_debug_ProgressIntroCheck', 'Please wait while your database is checked for errors...');
define('LNG_Addon_debug_ProgressTitleFix', 'Database Repair in Progress...');
define('LNG_Addon_debug_ProgressIntroFix', 'Please wait while your database is being repaired...');
define('LNG_Addon_debug_CheckingTable', 'Checking table %s (step %s of %s)');

define('LNG_Addon_debug_Problems', 'There were %d problem(s) found with your database and they are listed below.');
define('LNG_Addon_debug_NoProblems', 'There were no problems found with your database.');
define('LNG_Addon_debug_Repaired', 'Your database was repaired successfully and should now be free from errors.');

define('LNG_Addon_debug_Problem_NotPresent', '%d table(s) are not present');
define('LNG_Addon_debug_Problem_Corrupt', '%d table(s) are marked as corrupt');
define('LNG_Addon_debug_Problem_MissingColumns', '%d table(s) are missing columns');
define('LNG_Addon_debug_Problem_MissingIndexes', '%d table(s) are missing indexes');

define('LNG_Addon_debug_Advice', "Please note that only missing indexes and some types of table corruption can be fixed. If you have corrupt or missing tables that can not be fixed, please contact your web host. If you have missing table columns, please contact support.");

define('LNG_Addon_debug_DisplayReport', 'View Check Code');
define('LNG_Addon_debug_DisplayReport_Intro', 'The check code for your database is shown below. If you cannot repair your database using this wizard, please send the code below to the support team for help.');
define('LNG_Addon_debug_DisplayError', '(display errors)');
define('LNG_Addon_debug_Update_Success', 'Record has been updated successfully.');
define('LNG_Addon_debug_Update_Failure', 'Something went wrong when trying to update the debugging.');
