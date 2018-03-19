<?php
/**
 * This file contains a procedure that will obfuscate specified PHP code.
 * At present it will do the following:
 * - Remove comments from the file
 *
 * @package interspire.iem.internals
 */

main(isset($argv)? $argv : array());
return 1;

/**
 * main
 * Main procedure
 *
 * @param Array $argv Argument from command line
 * @return Void Does not return anything
 */
function main($argv)
{
	// Arguments must be an array
	if (!is_array($argv)) {
		return;
	}

	// Remove first argument
	array_shift($argv);

	// Script need a file to feed on
	if (count($argv) == 0) {
		return;
	}

	$files = get_file_names($argv[0]);

	if (!is_array($files)) {
		return;
	}

	foreach ($files as $file) {
		$file_contents = file_get_contents($file);
		$temp = obfuscate($file_contents);
		if (!empty($temp)) {
			file_put_contents($file, $temp);
		}
	}
}

/**
 * get_file_names
 * Get a list of file names given a location in the file system
 *
 * @param String $location Location
 * @return Array|FALSE Returns an array of files if successful, FALSE otherwise
 */
function get_file_names($location)
{
	// File or directory must be readable and writable
	if (!is_readable($location) || !is_writable($location)) {
		return false;
	}

	if (is_dir($location)) {
		$files = array();

		$resources = scandir($location);
		foreach ($resources as $resource) {
			if (in_array($resource, array('.', '..'))) {
				continue;
			}

			$temp = get_file_names($location . '/' . $resource);
			if (!$temp) {
				return false;
			}

			$files = array_merge($files, $temp);
		}

		return $files;
	} else {
		return array($location);
	}
}

/**
 * obfuscate
 * Obfuscate code string
 *
 * @param String $string Code to be obfuscated
 * @return String|FALSE Obfuscated code, FALSE otherwise
 */
function obfuscate($string)
{
	$tokens = token_get_all($string);
	$processed = array();
	$previous = 0;
	foreach ($tokens as $token) {
		if (is_array($token)) {
			list($type, $string, $line) = $token;

			switch ($type) {
				// Whitespace
				case T_WHITESPACE:
					if ($previous == T_WHITESPACE) {
						$string = '';
					} else {
						$string = ' ';
					}
				break;

				// Comments
				case T_DOC_COMMENT:
				case T_COMMENT:
					$string = '';
				break;
			}
		} else {
			$string = $token;
			$type = '';
		}

		$previous = $type;
		$processed[] = $string;
	}

	if (empty($processed)) {
		return false;
	}

	return implode('', $processed);
}