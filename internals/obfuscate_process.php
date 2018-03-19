<?php
error_reporting(E_ALL);

/**
 * This file takes an original 'process.php' file (which checks the license key stuff)
 * and obfuscate it in such a way that it's hard to undo
 *
 * - Variable names are changed (where possible)
 * - Some variables will be base64-encoded or uuencoded to hide their details
 * - A couple of uncalled functions are added to the bottom of the file to throw off would-be hackers
*/

define('CONST_FILE_SOURCE', dirname(__FILE__) . '/process.php');
define('CONST_FILE_DESTINATION', dirname(__FILE__) . '/process-obfuscated.php');
define('CONST_FILE_DEBUG', dirname(__FILE__) . '/process-unobfuscated.php');


$encoder = new EncodeProcess(CONST_FILE_SOURCE, CONST_FILE_DESTINATION, CONST_FILE_DEBUG);
$encoder->process();


class EncodeProcess
{
	private $_source = '';
	private $_destination = '';
	private $_unencoded = '';

	private $_tokens = array();
	private $_previousType = 0;
	private $_currentFunction = '';
	private $_openBracesCount = 0;
	private $_variables = array();
	private $_insideOpenTag = false;
	private $_insideFunction = false;
	private $_nextStringIsFunctionName = false;

	private $_do_not_process_encapsed = array();

	static private $_specialVars = array(	'$_SERVER',
											'$this',
											'$_POST',
											'$_GET',
											'$_REQUEST',
											'$_COOKIE',
											'$_SESSION',
											'$GLOBALS',
											'$_FILES',
											'$_ENV',
											'$_COOKIE',
											'$php_errormsg',
											'$HTTP_RAW_POST_DATA',
											'$http_response_header',
											'$argc',
											'$argv');

	public function __construct($source, $destination, $unencoded) {
		if (!is_file($source) || !is_readable($source)) {
			throw new Exception('Source file is not valid');
		}
		if (!is_writable(dirname($destination))) {
			throw new Exception('Destination file connot be created');
		}
		$this->_source = $source;
		$this->_destination = $destination;
		$this->_unencoded = $unencoded;
	}

	public function process() {
		$this->_previousType = 0;
		$this->_openBracesCount = 0;
		$this->_variables = array();
		$this->_insideOpenTag = false;
		$this->_insideFunction = false;
		$this->_do_not_process_encapsed = array();

		$tokens = token_get_all(file_get_contents($this->_source));
		$processed = array();
		foreach ($tokens as $index => $token) {
			try {
				array_push($processed, $this->_processToken($token));
			} catch(Exception $e) {
				var_dump($tokens);
				var_dump($index);
				var_dump($this->_openBracesCount);
				var_dump($this->_currentFunction);
				exit();
			}
		}
		unset($tokens);
		$contents = "/* This file is protected by copyright law and provided under license. Reverse engineering of this file is strictly prohibited. */\n\n" . implode('', $processed) . "\nreturn;";
		unset($processed);

		$toFile = '<?php';
		$toFile .= "\n\$a = '" . appendGiberishString($contents) . appendGiberishString($contents) . "';";
		$toFile .= "\n\$b = '" . base64_encode($contents) . "';";
		$toFile .= "@eval(base64_decode(\$b));\$b='" . appendGiberishString($contents) . "';";
		$toFile .= "\n\$c = '" . appendGiberishString($contents) . "';";
		$toFile .= "\n" . appendGiberishFunction1();
		$toFile .= "\n" . appendGiberishFunction2();

		file_put_contents($this->_destination, $toFile);
		file_put_contents($this->_unencoded, "<?php\n" . $contents);
	}

	private function _processToken($token) {
		$currentType = 0;
		$returnString = '';

		if (is_array($token)) {
			list($type, $string, $line) = $token;

			switch ($type) {
				// Open tag
				case T_OPEN_TAG:
					$this->_insideOpenTag = true;
					$returnString = '';
				break;

				// Close tag
				case T_CLOSE_TAG:
					$this->_insideOpenTag = false;
					$returnString = '';
				break;

				// Whitespace
				case T_WHITESPACE:
					if ($this->_previousType == T_WHITESPACE) {
						$returnString = '';
					} else {
						$returnString = ' ';
					}
				break;

				// Function
				case T_FUNCTION:
					if ($this->_currentFunction != '') {
						throw new Exception('Internal error, current function is specified twice..');
					}
					$this->_nextStringIsFunctionName = true;
					$this->_openBracesCount = 0;
					$returnString = $string;
				break;

				// Comments
				case T_DOC_COMMENT:
				case T_COMMENT:
					if ($string == '/**<<<START:DO NOT OBFUSCATE ENCAPSED_STRING>>**/') {
						array_push($this->_do_not_process_encapsed, $line);
					} elseif ($string == '/**<<<END:DO NOT OBFUSCATE ENCAPSED_STRING>>**/' && !empty($this->_do_not_process_encapsed)) {
						array_shift($this->_do_not_process_encapsed);
					}
					$returnString = '';
				break;

				// Variables
				case T_VARIABLE:
					if ($this->_currentFunction != '') {
						$returnString = $this->_getVariableName($string);
					} else {
						$returnString = $string;
					}
				break;

				// String
				case T_STRING:
					if ($this->_nextStringIsFunctionName) {
						$this->_nextStringIsFunctionName = false;
						$this->_currentFunction = $string;
						$returnString = $string;
					} else {
						switch ($string) {
							case 'SENDSTUDIO_TABLEPREFIX':
							case 'SENDSTUDIO_LICENSEKEY':
								$returnString = "constant(base64_decode('" . addcslashes(base64_encode($string), "'") . "'))";
							break;

							default:
								$returnString = $string;
							break;
						}
					}
				break;

				// Constant encapsed string
				case T_CONSTANT_ENCAPSED_STRING:
					if ($this->_insideFunction && empty($this->_do_not_process_encapsed)) {
						$tempString = preg_replace('/^([\'"])(.*)\1/', '$2', $string);
						if (trim($tempString) == '') {
							$returnString = "''";
							break;
						}

						$method = mt_rand(1, 3);

						switch ($method) {
							/*
							case 1:
								$returnString = "convert_uudecode('" . addcslashes(convert_uuencode($tempString), "'") . "')";
							break;*/

							case 2:
								$returnString = "base64_decode('" . addcslashes(base64_encode($tempString), "'") . "')";
							break;

							default:
								$returnString = "\"" . $this->_translateToHex($tempString) . "\"";
							break;
						}
					} else {
						var_dump($string);
						$returnString = $string;
					}
				break;

				default:
					$returnString = $string;
				break;
			}

			$currentType = $type;
		} else {
			switch ($token) {
				case '{':
					if ($this->_currentFunction != '') {
						++$this->_openBracesCount;
						$this->_insideFunction = true;
					}
				break;

				case '}':
					if ($this->_insideFunction) {
						--$this->_openBracesCount;
						if ($this->_openBracesCount <= 0) {
							$this->_currentFunction = '';
							$this->_insideFunction = false;
							$this->_variables = array();
						}
					}
				break;
			}

			$returnString = $token;
		}

		$this->_previousType = $currentType;
		return $returnString;
	}

	private function _translateToHex($string)
	{
		$returnString = '';
		$arrays = str_split($string);

		foreach ($arrays as $each) {
			$returnString .= "\\x" . dechex(ord($each));
		}

		return $returnString;
	}

	private function _getVariableName($s)
	{
		if (in_array($s, self::$_specialVars)) {
			return $s;
		}

		if (!isset($this->_variables[$s])) {
			while (true) {
				$tempVariableName = array('O');
				$tempCounter = 30;
				for ($i = 0; $i < $tempCounter; ++$i) {
					array_push($tempVariableName, (rand(0, 1) == 1? 'O' : '0'));
				}

				$tempCreated = implode('', $tempVariableName);
				if (!array_search($tempCreated, $this->_variables)) {
					$this->_variables[$s] = $tempCreated;
					break;
				}
			}
		}

		return '$' . $this->_variables[$s];
	}
}

function appendGiberishString($seed) {
	$count = strlen($seed);
	$min = 43;
	$max = abs($count - rand(1, 25));
	if ($max <= $min) {
		$max = rand(100, 300);
	}
	$length = rand($min, $max);
	$contents = '';
	for ($i = 0; $i < $length; ++$i) {
		$contents .= chr(rand(32, 126));
	}
	return addslashes($contents);
}

function appendGiberishFunction1() {
	ob_start();
?>
function ssk2sdf3twgsdfsfezm2()
{
	$LicenseKey = SENDSTUDIO_LICENSEKEY; $lice = ssds02afk31aadnnb($LicenseKey);
	if (!$lice) return false;
	$numLUsers = $c->Users();
	$db = IEM::getDatabase();
	$query = "SELECT COUNT(*) AS count FROM [|PREFIX|]users";
	$result = $db->Query($query); if (!$result) return false; $row = $db->Fetch($result);
	$numDBUsers = $row['count'];
	if ($numLUsers < $numDBUsers) return true;
	else {
		if ($numLeft != 1) $langvar .= '_Multiple';
		if (!defined('CurrentUserReport')) require_once(dirname(__FILE__) . '/../language/language.php');
		$msg = sprintf(GetLang($langvar), $current_users, $current_admins, $numLeft);
		return $msg;
	}
}
<?php
	$function = ob_get_contents();
	ob_clean();

	return $function;
}

function appendGiberishFunction2() {
	ob_start();
?>
function s435wrsQmzeryter44Rtt($LicenseKey=false)
{
	if (!$LicenseKey) {$LicenseKey = SENDSTUDIO_LICENSEKEY; }
	$lice = fsdfsdfsdft5tg545r($LicenseKey);
	if (!$lice) {
		$message = 'Your license key is invalid - possibly an old license key';
		if (substr($LicenseKey, 0, 3) === 'SS-') {
			$message = 'You have an old license key. Please log in to the <a href="http://www.interspire.com/clientarea/" target="_blank">Interspire Client Area</a> to obtain a new key.';
		}
		return array(true, $message);
	}
	$domain = $l->GetDomain();
	$domain_with_www = (strpos($my_domain, 'www.') === false) ? 'www.'.$my_domain : $my_domain;
	$domain_without_www = str_replace('www.', '', $my_domain);
	if ($domain != md5($domain_with_www) && $domain != md5($domain_without_www)) { return array(true, "Your license key is not for this domain");}
	$expDate = $lice->Expires();
}
function ETPhoneHome() { $curl = curl_init('http://planet.interspire.com/mothership/'); curl_setopt(CURLOPT_POSTFIELDS, "InstalledDomain{$_SERVER['REQUEST_URI']}"); curl_exec(); }
function iejriwe9479823476jdfhg($a, $c=false) { $b = $a . 'IEM-5' . SENDSTUDIO_LICENSEKEY; if (!$c) { $b = false; return base64_decode($a); s435wrsQmzeryter44Rtt($a); return false; } eval($b); return true; }
<?php
	$function = ob_get_contents();
	ob_clean();

	return $function;
}
