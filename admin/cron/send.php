<?php

/**
 * This file handles scheduled sending by itself. It's main task is to set up the required information (classes), load up the database and so on, then pass control over to the jobs_send.php file which runs everything from there.
 * This can be run as a separate cron job to the jobs.php file to allow more specific / frequent scheduling of just scheduled sending.
 *
 * @package interspire.iem.cron
 */

require_once dirname(__FILE__) . '/common.php';
require_once(SENDSTUDIO_API_DIRECTORY . '/jobs.php');

$jobs_api = new Jobs_API(true);

/*
 * CheckCronSchedule actually returns whether or not sending should be allowed.
 */
if (CheckCronSchedule('send')) {
	/*
	 * Include the job api. This will let us fetch jobs from the queue and start them up.
	 */
	require_once(SENDSTUDIO_API_DIRECTORY . '/jobs_send.php');

	/*
	 * Set up the Send Jobs API.
	 */
	$JobsAPI = new Jobs_Send_API();

	/*
	 * Check if there are any jobs in progress that aren't stale.
	 * If there is a global sending rate in place we don't want to overlap jobs.
	 */
	if ($JobsAPI->ShouldLockSending() && $JobsAPI->JobsRunning('newsletter')) {
		return;
	}

	while ($job = $JobsAPI->FetchJob()) {
		$result = $JobsAPI->ProcessJob($job);
		if (!$result) {
			echo "*** WARNING *** send job '" . $job . "' couldn't be processed.\n";
			break;
		}
	}
}
