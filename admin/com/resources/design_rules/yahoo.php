<?php
/**
* WARNING - IF YOU ARE NOT HIGHLY PROFICIENT WITH REGULAR EXPRESSIONS, DO NOT TOUCH THIS FILE.
*
* If you decide to modify this file, CREATE A BACKUP FIRST.
*
* Please read the README.TXT in this directory.
*/

$GLOBALS['Design_Rules']['Yahoo'] = array (
	array(
		'regular_expression' => '%<link%',
		'description' => 'Yahoo renames any link elements to &lt;xlink&gt;. This will disable the link action.',
		'replacement' => '<xlink',
		'match_offset' => 0
	),  
	array(
		'regular_expression' => '%(style\s*?=\s*?)(["\'])(.*?)(\s*?background-position\s*?:.*?)(\2|;)%',
		'description' => 'Yahoo doesn\'t show the CSS style: background-position',
		'use_preg_replace' => 1,
		'replacement' => '$1$2$3$5',
		'match_offset' => 2
	),	 
	array(
	    'regular_expression' => '%(style\s*?=\s*?)(["\'])(.*?)(\s*?clip\s*?:.*?)(\2|;)%',		
		'description' => 'Yahoo doesn\'t show the CSS style: clip',
		'use_preg_replace' => 1,
		'replacement' => '$1$2$3$5',
		'match_offset' => 2
	),
	array(
		'regular_expression' => '%(style\s*?=\s*?)(["\'])(.*?)(\s*?list-style-position\s*?:.*?)(\2|;)%',
		'description' => 'Yahoo doesn\'t show the CSS style: list-style-position',
		'use_preg_replace' => 1,
		'replacement' => '$1$2$3$5',
		'match_offset' => 2
	),
	array(
		'regular_expression' => '%(style\s*?=\s*?)(["\'])(.*?)(\s*?position\s*?:.*?)(\2|;)%',
		'description' => 'Yahoo doesn\'t show the CSS style: position',
		'use_preg_replace' => 1,
		'replacement' => '$1$2$3$5',
		'match_offset' => 2
	),
	array(
		'regular_expression' => '%(style\s*?=\s*?)(["\'])(.*?)(\s*?text-fill-color\s*?:.*?)(\2|;)%',
		'description' => 'Yahoo doesn\'t show the CSS3 style: text-fill-color',
		'use_preg_replace' => 1,
		'replacement' => '$1$2$3$5',
		'match_offset' => 2
	),
	array(
		'regular_expression' => '%(style\s*?=\s*?)(["\'])(.*?)(\s*?text-fill-stroke\s*?:.*?)(\2|;)%',
		'description' => 'Yahoo doesn\'t show the CSS3 style: text-fill-stroke',
		'use_preg_replace' => 1,
		'replacement' => '$1$2$3$5',
		'match_offset' => 4
	),
	array(
		'regular_expression' => '%(style\s*?=\s*?)(["\'])(.*?)(\s*?background\s*?:.*?)(\2|;)%',
		'description' => 'Yahoo doesn\'t show the CSS3 style: background',
		'use_preg_replace' => 1,
		'replacement' => '$1$2$3$5',
		'match_offset' => 4
	),
	array(
		'regular_expression' => '%(style\s*?=\s*?)(["\'])(.*?)(\s*?box-shadow\s*?:.*?)(\2|;)%',
		'description' => 'Yahoo doesn\'t show the CSS3 style: box-shadow',
		'use_preg_replace' => 1,
		'replacement' => '$1$2$3$5',
		'match_offset' => 2
	),
	array(
		'regular_expression' => '%(style\s*?=\s*?)(["\'])(.*?)(\s*?border-radius\s*?:.*?)(\2|;)%',
		'description' => 'Yahoo doesn\'t show the CSS3 style: border-radius',
		'use_preg_replace' => 1,
		'replacement' => '$1$2$3$5',
		'match_offset' => 2
	),
	array(
		'regular_expression' => '%(style\s*?=\s*?)(["\'])(.*?)(\s*?border-image\s*?:.*?)(\2|;)%',
		'description' => 'Yahoo doesn\'t show the CSS3 style: border-image',
		'use_preg_replace' => 1,
		'replacement' => '$1$2$3$5',
		'match_offset' => 4
	),
	array(
		'regular_expression' => '%(style\s*?=\s*?)(["\'])(.*?)(\s*?Opacity\s*?:.*?)(\2|;)%',
		'description' => 'Yahoo doesn\'t show the CSS3 style: Opacity ',
		'use_preg_replace' => 1,
		'replacement' => '$1$2$3$5',
		'match_offset' => 4
	),	 
	array(      
		'regular_expression' => '%<canvas.*?</canvas>%',  
		'description' => 'Yahoo removes any canvas elements tags',
		'replacement' => '',
		'match_offset' => 0
	),
	array(      
		'regular_expression' =>'%<audio\s(.*?)</audio>%', 
		'description' => 'Yahoo removes any audio elements tags',
		'replacement' => '',
		'match_offset' => 0
	),
	array(      
		'regular_expression' => '%<video\s(.*?)</video>%',
		'description' => 'Yahoo removes any video elements tags',
		'replacement' => '',
		'match_offset' => 0
	),
	array(
		'regular_expression' => '%(<style.*?>)(\s.*?)(.*?)(\s*?background-position\s*?:.*?)(;|})%', 		 
		'description' => 'Yahoo doesn\'t show the internal CSS style:background-position',
		'use_preg_replace' => 1,
		'replacement' => '$1$2$3$5',
		'match_offset' => 4
	),
	array(
		'regular_expression' => '%(<style.*?>)(\s.*?)(.*?)(\s*?Opacity\s*?:.*?)(;|})%', 		 
		'description' => 'Yahoo doesn\'t show the internal CSS style:Opacity',
		'use_preg_replace' => 1,
		'replacement' => '$1$2$3$5',
		'match_offset' => 4
	),
	array(
		'regular_expression' => '%(<style.*?>)(\s.*?)(.*?)(\s*?border-image\s*?:.*?)(;|})%', 		 
		'description' => 'Yahoo doesn\'t show the internal CSS style:border-image',
		'use_preg_replace' => 1,
		'replacement' => '$1$2$3$5',
		'match_offset' => 4
	),
	array(
		'regular_expression' => '%(<style.*?>)(\s.*?)(.*?)(\s*?border-radius\s*?:.*?)(;|})%', 		 
		'description' => 'Yahoo doesn\'t show the internal CSS style:border-radius',
		'use_preg_replace' => 1,
		'replacement' => '$1$2$3$5',
		'match_offset' => 4
	),
	array(
		'regular_expression' => '%(<style.*?>)(\s.*?)(.*?)(\s*?box-shadow\s*?:.*?)(;|})%', 		 
		'description' => 'Yahoo doesn\'t show the internal   CSS style:box-shadow ',
		'use_preg_replace' => 1,
		'replacement' => '$1$2$3$5',
		'match_offset' => 4
	),
	array(
		'regular_expression' => '%(<style.*?>)(\s.*?)(.*?)(\s*?[^-]bottom\s*?:.*?)(;|})%', 		 
		'description' => 'Yahoo doesn\'t show the internal CSS style:bottom ',
		'use_preg_replace' => 1,
		'replacement' => '$1$2$3$5',
		'match_offset' => 4
	),
	array(
		'regular_expression' => '%(<style.*?>)(\s.*?)(.*?)(\s*?clip\s*?:.*?)(;|})%', 		 
		'description' => 'Yahoo doesn\'t show the internal CSS style:clip ',
		'use_preg_replace' => 1,
		'replacement' => '$1$2$3$5',
		'match_offset' => 4
	),
	array(
		'regular_expression' => '%(<style.*?>)(\s.*?)(.*?)(\s*?[^-]right\s*?:.*?)(;|})%', 		 
		'description' => 'Yahoo doesn\'t show the internal   CSS style:right',
		'use_preg_replace' => 1,
		'replacement' => '$1$2$3$5',
		'match_offset' => 4
	),
	array(
		'regular_expression' => '%(<style.*?>)(\s.*?)(.*?)(\s*?[^-]left\s*?:.*?)(;|})%', 		 
		'description' => 'Yahoo doesn\'t show the internal   CSS style:left',
		'use_preg_replace' => 1,
		'replacement' => '$1$2$3$5',
		'match_offset' => 4
	),
	// example <div style="background: color url('path') others;">
	array(
		'regular_expression' => '%(<style.*?>)(\s.*?)(.*?)(\s*?background\s*?:.*?)(;|})%', 		 
		'description' => 'Yahoo doesn\'t show the    CSS style:background ',
		'use_preg_replace' => 1,
		'replacement' => '$1$2$3$5',
		'match_offset' => 4
	),
	array(
		'regular_expression' => '%(<style.*?>)(\s.*?)(.*?)(\s*?text-fill-stroke\s*?:.*?)(;|})%', 		 
		'description' => 'Yahoo doesn\'t show the    CSS style:text-fill-stroke ',
		'use_preg_replace' => 1,
		'replacement' => '$1$2$3$5',
		'match_offset' => 4
	),
	array(
		'regular_expression' => '%(<style.*?>)(\s.*?)(.*?)(\s*?text-fill-color\s*?:.*?)(;|})%', 		 
		'description' => 'Yahoo doesn\'t show the    CSS style:text-fill-color ',
		'use_preg_replace' => 1,
		'replacement' => '$1$2$3$5',
		'match_offset' => 4
	),
	array(
		'regular_expression' => '%(<style.*?>)(\s.*?)(.*?)(\s*?[^-]position\s*?:.*?)(;|})%', 		 
		'description' => 'Yahoo doesn\'t show the internal CSS style:position',
		'use_preg_replace' => 1,
		'replacement' => '$1$2$3$5',
		'match_offset' => 4
	),
	array(
		'regular_expression' => '%(<style.*?>)(\s.*?ul)(.*?)(\s*?list-style-position\s*?:.*?)(;)%',
		'description' => 'Yahoo doesn\'t show the internal  CSS style for table:list-style-position ', 
		'use_preg_replace' => 1,
		'replacement' => '$1$2$3$5',
		'match_offset' => 4
	),
);