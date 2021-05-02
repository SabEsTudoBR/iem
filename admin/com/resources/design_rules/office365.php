<?php
/**
* WARNING - IF YOU ARE NOT HIGHLY PROFICIENT WITH REGULAR EXPRESSIONS, DO NOT TOUCH THIS FILE.
*
* If you decide to modify this file, CREATE A BACKUP FIRST.
*
* Please read the README.TXT in this directory.
*/

$GLOBALS['Design_Rules']['Office 365'] = array (
	array(	
		'regular_expression' => '%(style\s*?=\s*?)(["\'])(.*?)(\s*?background-image\s*?:.*?)(\2|;)%',			 
		'description' => 'Office 365 doesn\'t show the CSS style: background-image',
		'use_preg_replace' => 1,
		'replacement' => '$1$2$3$5',
		'match_offset' => 4
	),
	array(
		'regular_expression' => '%(style\s*?=\s*?)(["\'])(.*?)(\s*?text-decoration\s*?:.*?)(\2|;)%',			 
		'description' => 'Office 365 doesn\'t show the CSS style: text-decoration',
		'use_preg_replace' => 1,
		'replacement' => '$1$2$3$5',
		'match_offset' => 4
	),
	array(
		'regular_expression' => '%(<link[^>]*?>)%',
		'description' => 'Office 365 removes any link elements',
		'replacement' => '',
		'match_offset' => 1
	),
	array(
		'regular_expression' => '%@import.*?(\r|\n|\<|;)%',
		'description' => 'Office 365 will strip out any @import tags and any CSS tags found within the <style> tags.',
		'use_preg_replace' => 1,
		'replacement' => '$1',
		'match_offset' => 1
	),
);
