<?php
require_once dirname(__FILE__) . '/IEMLicense.class.php';
$lobj = new IEMLicense();
$editions = $lobj->getEditions();
?>
	<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
	<input type="hidden" name="Action" value="Encrypt">
	<table>
		<tr>
			<th>
				&nbsp;
			</th>
			<th align="left">
				Create a license key
			</th>
		</tr>
		<tr>
			<td>
				Host name:
			</td>
			<td>
				<input type="text" name="host" value="<?php echo (!isset($_POST['host'])) ? '' : $_POST['host']; ?>">
			</td>
		</tr>
		<tr>
			<td>
				Number of users:
			</td>
			<td>
				<input type="text" name="users" value="<?php echo (!isset($_POST['users'])) ? '5' : (int)$_POST['users']; ?>">
			</td>
		</tr>
		<tr>
			<td>
				Edition:
			</td>
			<td>
				<select name="edition">
					<?php
						$edition_default = 'NORMAL';
						if (isset($_POST['edition'])) $edition_default = $_POST['edition'];
						foreach ($editions as $edition) {
							$selected = '';
							if ($edition_default == $edition) {
								$selected = ' SELECTED';
							}
							echo '<option value="' . $edition . '"' . $selected . '>' . ucwords(strtolower($edition)) . '</option>';
						}
					?>
				</select>
			</td>
		</tr>
		<tr>
			<td>
				Expiry Date:
			</td>
			<td>
				Year:&nbsp;<input type="text" name="expiry[year]" value="<?php echo (!isset($_POST['expiry'])) ? '' : $_POST['expiry']['year']; ?>" size="5">&nbsp;
				Month:&nbsp;<input type="text" name="expiry[month]" value="<?php echo (!isset($_POST['expiry'])) ? '' : $_POST['expiry']['month']; ?>" size="5">&nbsp;
				Day:&nbsp;<input type="text" name="expiry[day]" value="<?php echo (!isset($_POST['expiry'])) ? '' : $_POST['expiry']['day']; ?>" size="5">&nbsp;
			</td>
		</tr>
		<tr>
			<td>
				Version:
			</td>
			<td>
				<select name="version">
					<option value="">Version 5.0.x</option>
					<option value="5.5">Version 5.5.x</option>
					<option value="5.7" selected="selected">Version 5.7.x</option>
				</select>
			</td>
		</tr>
		<tr>
			<td>
				NFR:
			</td>
			<td>
				<select name="nfr">
					<option value="0" selected="selected">NO</option>
					<option value="1">YES</option>
				</select>
			</td>
		</tr>
		<tr>
			<td>
				Agency Edition:
			</td>
			<td>
				<input type="text" name="agencyid" value="<?php echo (!isset($_POST['agencyid'])) ? '0' : (int)$_POST['agencyid']; ?>" />
			</td>
		</tr>
		<tr>
			<td>
				Number of trial user:
			</td>
			<td>
				<input type="text" name="trialusers" value="<?php echo (!isset($_POST['trialusers'])) ? '0' : (int)$_POST['trialusers']; ?>" />
			</td>
		</tr>
		<tr>
			<td>
				Number of emails a trial user can have:
			</td>
			<td>
				<input type="text" name="trialemails" value="<?php echo (!isset($_POST['trialemails'])) ? '0' : (int)$_POST['trialemails']; ?>" />
			</td>
		</tr>
		<tr>
			<td>
				Number of days a trial user can have:
			</td>
			<td>
				<input type="text" name="trialdays" value="<?php echo (!isset($_POST['trialdays'])) ? '0' : (int)$_POST['trialdays']; ?>" />
			</td>
		</tr>
		<tr>
			<td>
				Number of days the application need to ping us back:
			</td>
			<td>
				<input type="text" name="reactivation_days" value="<?php echo (!isset($_POST['reactivation_days'])) ? '-1' : (int)$_POST['reactivation_days']; ?>" />
			</td>
		</tr>
		<tr>
			<td>
				Number of grace period to give:
			</td>
			<td>
				<input type="text" name="reactivation_grace" value="<?php echo (!isset($_POST['reactivation_grace'])) ? '0' : (int)$_POST['reactivation_grace']; ?>" />
			</td>
		</tr>
		<tr>
			<td colspan="2">
				&nbsp;&nbsp;<input type="submit" value="Go" />
			</td>
		</tr>
	</table>
	</form>

	<br/><br/>
	<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
	<input type="hidden" name="Action" value="Decrypt">
		<table>
			<tr>
				<th>
					&nbsp;
				</th>
				<th align="left">
					Decrypt a license key
				</th>
			</tr>
			<tr>
				<td>
					Enter a license key to decrypt:
				</td>
				<td>
					<input type="text" name="License" value="" size="170">
				</td>
			</tr>
			<tr>
				<td colspan="2">
					&nbsp;&nbsp;<input type="submit" value="Go">
				</td>
			</tr>
		</table>
	</form>
<?php
if (empty($_POST)) {
	exit;
}

if ($_POST['Action'] == 'Encrypt') {
	$mapping = array(
		'host',
		'expiry',
		'edition',
		'version',
		'users',
		'nfr',
		'agencyid',
		'trialusers',
		'trialemails',
		'trialdays',
		'reactivation_days',
		'reactivation_grace',
	);

	foreach ($mapping as $index) {
		$value = false;

		switch ($index) {
			case 'expiry':
				if (empty($_POST['expiry']['day']) || empty($_POST['expiry']['month']) || empty($_POST['expiry']['year'])) {
					continue;
				}

				$value = "{$_POST['expiry']['year']}-{$_POST['expiry']['month']}-{$_POST['expiry']['day']}";
			break;

			default:
				$value = $_POST[$index];
			break;
		}

		$lobj->{$index} = $value;
	}

	$licenseKey = $lobj->licenseGet();

	echo 'With this key you get ... <br/>';
	echo "domain: " . $lobj->host_md5 . "<br/>";
	echo "edition: " . $lobj->edition . "<br/>";
	echo "expiry: " . $lobj->expiry . "<br/>";
	echo 'Users: ' . number_format($lobj->users) . ' (0 means unlimited)<br/>';
	echo 'Lists: ' . number_format($lobj->lists) . ' (0 means unlimited)<br/>';
	echo 'Subscribers: ' . number_format($lobj->subscribers) . ' (0 means unlimited)<br/>';
	echo 'Version: ' . $lobj->version . '<br />';
	echo 'NFR: ' . ($lobj->nfr? 'TRUE' : 'FALSE') . '<br />';
	echo 'AgencyID: ' . $lobj->agencyid . '<br />';
	echo 'Trial Users: ' . number_format($lobj->trialusers) . ' (0 means unlimited)<br />';
	echo 'Trial Emails: ' . number_format($lobj->trialemails) . '<br />';
	echo 'Trial Days: ' . number_format($lobj->trialdays) . '<br />';
	echo 'Ping Back Days:' . number_format($lobj->reactivation_days) . '<br />';
	echo 'Ping Back Grace period:' . number_format($lobj->reactivation_grace) . '<br />';

	echo '<br/>';
	echo '<input type="text" name="" value="' . $licenseKey . '" size="170">';

	exit();
}

if ($_POST['Action'] == 'Decrypt') {
	$lobj->licenseSet($_POST['License']);

	echo "domain: " . $lobj->host_md5 . "<br/>";
	echo "edition: " . $lobj->edition . "<br/>";
	echo "expiry: " . (trim($lobj->expiry) == ''? 'Not expiring' : $lobj->expiry) . "<br/>";
	echo "users: " . number_format($lobj->users) . " (0 means unlimited) <br/>";
	echo "lists: " . number_format($lobj->lists) . " (0 means unlimited)<br/>";
	echo "subscribers: " . number_format($lobj->subscribers) . " (0 means unlimited)<br/>";
	echo "version: " . $lobj->version . "<br/>";
	echo "nfr: " . ($lobj->nfr ? 'TRUE' : 'FALSE'). "<br/>";
	echo 'AgencyID: ' . $lobj->agencyid . '<br />';
	echo 'Trial Users: ' . number_format($lobj->trialusers) . ' (0 means unlimited)<br />';
	echo 'Trial Emails: ' . number_format($lobj->trialemails) . '<br />';
	echo 'Trial Days: ' . number_format($lobj->trialdays) . '<br />';
	echo 'Ping Back Days:' . number_format($lobj->reactivation_days) . '<br />';
	echo 'Ping Back Grace period:' . number_format($lobj->reactivation_grace) . '<br />';
	exit();
}
