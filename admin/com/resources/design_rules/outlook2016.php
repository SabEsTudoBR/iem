<?php
/**
* WARNING - IF YOU ARE NOT HIGHLY PROFICIENT WITH REGULAR EXPRESSIONS, DO NOT TOUCH THIS FILE.
*
* If you decide to modify this file, CREATE A BACKUP FIRST.
*https://www.campaignmonitor.com/blog/email-marketing/2019/05/css3-support-in-email-clients/
*https://www.emailonacid.com/blog/article/email-development/how-to-code-emails-for-outlook-2016/
https://www.campaignmonitor.com/css/flexbox/display/
* Please read the README.TXT in this directory.
*/

$GLOBALS['Design_Rules']['Outlook 2016'] = array (
	 array(
		'regular_expression' => '%(style\s*?=\s*?)(["\'])(.*?)(\s*?word-wrap\s*?:.*?)(\2|;)%',
		'description' => 'Outlook 2016 doesn\'t show the CSS style: word-wrap',
		'use_preg_replace' => 1,
		'replacement' => '$1$2$3$5',
		'match_offset' => 2
	),
	array(
		'regular_expression' => '%(style\s*?=\s*?)(["\'])(.*?)(\s*?margin\s*?:.*?)(\2|;)%',
		'description' => 'Outlook 2016 doesn\'t support the CSS style: margin',
		'use_preg_replace' => 1,
		'replacement' => '$1$2$3$5',
		'match_offset' => 1
	),
	array(
		'regular_expression' => '%<link.*?>%',
		'description' => 'Outlook 2016  removes any link elements',
		'replacement' => '',
		'match_offset' => 0
	),
	array(
		'regular_expression' => '%(style\s*?=\s*?)(["\'])(.*?)(\s*?Padding\s*?:.*?)(\2|;)%',
		'description' => 'Outlook 2016 doesn\'t support the CSS style: Padding',
		'use_preg_replace' => 1,
		'replacement' => '$1$2$3$5',
		'match_offset' => 1
	),
	array(
		'regular_expression' => '%(div\s*?=\s*?)(["\'])(.*?)(\s*?width\s*?:.*?)(\2|;)%',
		'description' => 'Outlook 2016 doesn\'t support the CSS div: width',
		'use_preg_replace' => 1,
		'replacement' => '$1$2$3$5',
		'match_offset' => 1
	),	
	array(
		'regular_expression' => '%(div\s*?=\s*?)(["\'])(.*?)(\s*?height\s*?:.*?)(\2|;)%',
		'description' => 'Outlook 2016 doesn\'t support the CSS div: height',
		'use_preg_replace' => 1,
		'replacement' => '$1$2$3$5',
		'match_offset' => 1
	),
	
	array(
		'regular_expression' => '%(style\s*?=\s*?)(["\'])(.*?)(\s*?background-color\s*?:.*?)(\2|;)%',
		'description' => 'Outlook 2016 doesn\'t support the CSS  style: background-color',
		'use_preg_replace' => 1,
		'replacement' => '$1$2$3$5',
		'match_offset' => 4
	),
	array(
		'regular_expression' => '%(style\s*?=\s*?)(["\'])(.*?)(\s*?background-attachment\s*?:.*?)(\2|;)%',
		'description' => 'Outlook 2016 doesn\'t show the CSS style: background-attachment',
		'use_preg_replace' => 1,
		'replacement' => '$1$2$3$5',
		'match_offset' => 2
	),
	array(
		'regular_expression' => '%(style\s*?=\s*?)(["\'])(.*?)(\s*?background-blend-mode\s*?:.*?)(\2|;)%',
		'description' => 'Outlook 2016 doesn\'t show the CSS style: background-blend-mode',
		'use_preg_replace' => 1,
		'replacement' => '$1$2$3$5',
		'match_offset' => 2
	),
	array(
		'regular_expression' => '%(style\s*?=\s*?)(["\'])(.*?)(\s*?background-clip\s*?:.*?)(\2|;)%',
		'description' => 'Outlook 2016 doesn\'t show the CSS style: background-clip',
		'use_preg_replace' => 1,
		'replacement' => '$1$2$3$5',
		'match_offset' => 2
	),
	array(
		'regular_expression' => '%(style\s*?=\s*?)(["\'])(.*?)(\s*?background-image\s*?:.*?)(\2|;)%',
		'description' => 'Outlook 2016 doesn\'t show the CSS style: background-image',
		'use_preg_replace' => 1,
		'replacement' => '$1$2$3$5',
		'match_offset' => 2
	),
	array(
		'regular_expression' => '%(style\s*?=\s*?)(["\'])(.*?)(\s*?background-origin\s*?:.*?)(\2|;)%',
		'description' => 'Outlook 2016 doesn\'t show the CSS style: background-origin',
		'use_preg_replace' => 1,
		'replacement' => '$1$2$3$5',
		'match_offset' => 2
	),
	array(
		'regular_expression' => '%(style\s*?=\s*?)(["\'])(.*?)(\s*?background-position\s*?:.*?)(\2|;)%',
		'description' => 'Outlook 2016 doesn\'t show the CSS style: background-position',
		'use_preg_replace' => 1,
		'replacement' => '$1$2$3$5',
		'match_offset' => 2
	),
	array(
		'regular_expression' => '%(style\s*?=\s*?)(["\'])(.*?)(\s*?(?<!border-|margin-)bottom\s*?:.*?)(\2|;)%',
		'description' => 'Outlook 2016 doesn\'t show the CSS style: bottom',
		'use_preg_replace' => 1,
		'replacement' => '$1$2$3$5',
		'match_offset' => 2
	),
	array(
		'regular_expression' => '%(style\s*?=\s*?)(["\'])(.*?)(\s*?background-repeat\s*?:.*?)(\2|;)%',
		'description' => 'Outlook 2016 doesn\'t show the CSS style: background-repeat',
		'use_preg_replace' => 1,
		'replacement' => '$1$2$3$5',
		'match_offset' => 2
	),
	array(
		'regular_expression' => '%(style\s*?=\s*?)(["\'])(.*?)(\s*?isolations*?:.*?)(\2|;)%',
		'description' => 'Outlook 2016 doesn\'t show the CSS style:isolations',
		'use_preg_replace' => 1,
		'replacement' => '$1$2$3$5',
		'match_offset' => 2
	),
	array(
		'regular_expression' => '%(style\s*?=\s*?)(["\'])(.*?)(\s*?mix-blend-mode*?:.*?)(\2|;)%',
		'description' => 'Outlook 2016 doesn\'t show the CSS style:mix-blend-mode',
		'use_preg_replace' => 1,
		'replacement' => '$1$2$3$5',
		'match_offset' => 2
	),
	 array(
		'regular_expression' => '%(style\s*?=\s*?)(["\'])(.*?)(\s*?list-style-image\s*?:.*?)(\2|;)%',
		'description' => 'Outlook 2016 doesn\'t show the CSS style: list-style-image',
		'use_preg_replace' => 1,
		'replacement' => '$1$2$3$5',
		'match_offset' => 2
	),	
	 array(
		'regular_expression' => '%(style\s*?=\s*?)(["\'])(.*?)(\s*?list-style-position\s*?:.*?)(\2|;)%',
		'description' => 'Outlook 2016 doesn\'t show the CSS style: list-style-position',
		'use_preg_replace' => 1,
		'replacement' => '$1$2$3$5',
		'match_offset' => 2
	),
	 array(
		'regular_expression' => '%(style\s*?=\s*?)(["\'])(.*?)(\s*?border-spacing\s*?:.*?)(\2|;)%',
		'description' => 'Outlook 2016 doesn\'t show the CSS style: border-spacing',
		'use_preg_replace' => 1,
		'replacement' => '$1$2$3$5',
		'match_offset' => 2
	),
	array(
		'regular_expression' => '%(style\s*?=\s*?)(["\'])(.*?)(\s*?caption-side\s*?:.*?)(\2|;)%',
		'description' => 'Outlook 2016 doesn\'t show the CSS style: caption-side',
		'use_preg_replace' => 1,
		'replacement' => '$1$2$3$5',
		'match_offset' => 2
	),
	array(
		'regular_expression' => '%(style\s*?=\s*?)(["\'])(.*?)(\s*?empty-cells\s*?:.*?)(\2|;)%',
		'description' => 'Outlook 2016 doesn\'t show the CSS style: empty-cells',
		'use_preg_replace' => 1,
		'replacement' => '$1$2$3$5',
		'match_offset' => 2
	),
	array(
		'regular_expression' => '%(style\s*?=\s*?)(["\'])(.*?)(\s*?table-layout\s*?:.*?)(\2|;)%',
		'description' => 'Outlook 2016 doesn\'t show the CSS style: table-layout',
		'use_preg_replace' => 1,
		'replacement' => '$1$2$3$5',
		'match_offset' => 2
	),
	array(
		'regular_expression' => '%(style\s*?=\s*?)(["\'])(.*?)(\s*?animation-delay\s*?:.*?)(\2|;)%',
		'description' => 'Outlook 2016 doesn\'t show the CSS style: animation-delay',
		'use_preg_replace' => 1,
		'replacement' => '$1$2$3$5',
		'match_offset' => 2
	),
	array(
		'regular_expression' => '%(style\s*?=\s*?)(["\'])(.*?)(\s*?animation-direction\s*?:.*?)(\2|;)%',
		'description' => 'Outlook 2016 doesn\'t show the CSS style:animation-direction',
		'use_preg_replace' => 1,
		'replacement' => '$1$2$3$5',
		'match_offset' => 2
	),
	array(
		'regular_expression' => '%(style\s*?=\s*?)(["\'])(.*?)(\s*?animation-fill-mode\s*?:.*?)(\2|;)%',
		'description' => 'Outlook 2016 doesn\'t show the CSS style:animation-fill-mode',
		'use_preg_replace' => 1,
		'replacement' => '$1$2$3$5',
		'match_offset' => 2
	),
	array(
		'regular_expression' => '%(style\s*?=\s*?)(["\'])(.*?)(\s*?animation-iteration-count\s*?:.*?)(\2|;)%',
		'description' => 'Outlook 2016 doesn\'t show the CSS style:animation-iteration-count',
		'use_preg_replace' => 1,
		'replacement' => '$1$2$3$5',
		'match_offset' => 2
	),
	array(
		'regular_expression' => '%(style\s*?=\s*?)(["\'])(.*?)(\s*?animation-name\s*?:.*?)(\2|;)%',
		'description' => 'Outlook 2016 doesn\'t show the CSS style:animation-name',
		'use_preg_replace' => 1,
		'replacement' => '$1$2$3$5',
		'match_offset' => 2
	),
	array(
		'regular_expression' => '%(style\s*?=\s*?)(["\'])(.*?)(\s*?animation-play-state\s*?:.*?)(\2|;)%',
		'description' => 'Outlook 2016 doesn\'t show the CSS style:animation-play-state',
		'use_preg_replace' => 1,
		'replacement' => '$1$2$3$5',
		'match_offset' => 2
	),
	array(
		'regular_expression' => '%(style\s*?=\s*?)(["\'])(.*?)(\s*?backface-visibility\s*?:.*?)(\2|;)%',
		'description' => 'Outlook 2016 doesn\'t show the CSS style:backface-visibility',
		'use_preg_replace' => 1,
		'replacement' => '$1$2$3$5',
		'match_offset' => 2
	),
	array(
		'regular_expression' => '%(style\s*?=\s*?)(["\'])(.*?)(\s*?transition-delay\s*?:.*?)(\2|;)%',
		'description' => 'Outlook 2016 doesn\'t show the CSS style:transition-delay',
		'use_preg_replace' => 1,
		'replacement' => '$1$2$3$5',
		'match_offset' => 2
	),
	array(
		'regular_expression' => '%(style\s*?=\s*?)(["\'])(.*?)(\s*?transition-duration\s*?:.*?)(\2|;)%',
		'description' => 'Outlook 2016 doesn\'t show the CSS style:transition-duration',
		'use_preg_replace' => 1,
		'replacement' => '$1$2$3$5',
		'match_offset' => 2
	),
	array(
		'regular_expression' => '%(style\s*?=\s*?)(["\'])(.*?)(\s*?transition-property\s*?:.*?)(\2|;)%',
		'description' => 'Outlook 2016 doesn\'t show the CSS style:transition-property',
		'use_preg_replace' => 1,
		'replacement' => '$1$2$3$5',
		'match_offset' => 2
	),
	array(
		'regular_expression' => '%(style\s*?=\s*?)(["\'])(.*?)(\s*?transition-timing-function\s*?:.*?)(\2|;)%',
		'description' => 'Outlook 2016 doesn\'t show the CSS style:transition-timing-function',
		'use_preg_replace' => 1,
		'replacement' => '$1$2$3$5',
		'match_offset' => 2
	),
	array(
		'regular_expression' => '%(style\s*?=\s*?)(["\'])(.*?)(\s*?filter\s*?:.*?)(\2|;)%',
		'description' => 'Outlook 2016 doesn\'t show the CSS style:filter',
		'use_preg_replace' => 1,
		'replacement' => '$1$2$3$5',
		'match_offset' => 2
	),
	array(
		'regular_expression' => '%(style\s*?=\s*?)(["\'])(.*?)(\s*?backdrop-filter\s*?:.*?)(\2|;)%',
		'description' => 'Outlook 2016 doesn\'t show the CSS style:backdrop-filter',
		'use_preg_replace' => 1,
		'replacement' => '$1$2$3$5',
		'match_offset' => 2
	),
	array(
		'regular_expression' => '%(style\s*?=\s*?)(["\'])(.*?)(\s*?break-after\s*?:.*?)(\2|;)%',
		'description' => 'Outlook 2016 doesn\'t show the CSS style:break-after',
		'use_preg_replace' => 1,
		'replacement' => '$1$2$3$5',
		'match_offset' => 2
	),
	array(
		'regular_expression' => '%(style\s*?=\s*?)(["\'])(.*?)(\s*?break-before\s*?:.*?)(\2|;)%',
		'description' => 'Outlook 2016 doesn\'t show the CSS style:break-before',
		'use_preg_replace' => 1,
		'replacement' => '$1$2$3$5',
		'match_offset' => 2
	),
	array(
		'regular_expression' => '%(style\s*?=\s*?)(["\'])(.*?)(\s*?break-inside\s*?:.*?)(\2|;)%',
		'description' => 'Outlook 2016 doesn\'t show the CSS style:break-inside',
		'use_preg_replace' => 1,
		'replacement' => '$1$2$3$5',
		'match_offset' => 2
	),
	array(
		'regular_expression' => '%(style\s*?=\s*?)(["\'])(.*?)(\s*?column-count\s*?:.*?)(\2|;)%',
		'description' => 'Outlook 2016 doesn\'t show the CSS style:column-count',
		'use_preg_replace' => 1,
		'replacement' => '$1$2$3$5',
		'match_offset' => 2
	),
	array(
		'regular_expression' => '%(style\s*?=\s*?)(["\'])(.*?)(\s*?column-rule\s*?:.*?)(\2|;)%',
		'description' => 'Outlook 2016 doesn\'t show the CSS style:column-rule',
		'use_preg_replace' => 1,
		'replacement' => '$1$2$3$5',
		'match_offset' => 2
	),
	array(
		'regular_expression' => '%(style\s*?=\s*?)(["\'])(.*?)(\s*?display\s*?:.*?)(\2|;)%',
		'description' => 'Outlook 2016 doesn\'t show the CSS style:display',
		'use_preg_replace' => 1,
		'replacement' => '$1$2$3$5',
		'match_offset' => 2
	),
	array(
		'regular_expression' => '%(style\s*?=\s*?)(["\'])(.*?)(\s*?flex-flow\s*?:.*?)(\2|;)%',
		'description' => 'Outlook 2016 doesn\'t show the CSS style:flex-flow',
		'use_preg_replace' => 1,
		'replacement' => '$1$2$3$5',
		'match_offset' => 2
	),
	array(
		'regular_expression' => '%(style\s*?=\s*?)(["\'])(.*?)(\s*?align-items\s*?:.*?)(\2|;)%',
		'description' => 'Outlook 2016 doesn\'t show the CSS style:align-items',
		'use_preg_replace' => 1,
		'replacement' => '$1$2$3$5',
		'match_offset' => 2
	),
	array(
		'regular_expression' => '%(style\s*?=\s*?)(["\'])(.*?)(\s*?outline\s*?:.*?)(\2|;)%',
		'description' => 'Outlook 2016 doesn\'t show the CSS style:outline',
		'use_preg_replace' => 1,
		'replacement' => '$1$2$3$5',
		'match_offset' => 2
	),
	array(
		'regular_expression' => '%(style\s*?=\s*?)(["\'])(.*?)(\s*?top\s*?:.*?)(\2|;)%',
		'description' => 'Outlook 2016 doesn\'t show the CSS style:top',
		'use_preg_replace' => 1,
		'replacement' => '$1$2$3$5',
		'match_offset' => 2
	),
	array(
		'regular_expression' => '%(style\s*?=\s*?)(["\'])(.*?)(\s*?z-index\s*?:.*?)(\2|;)%',
		'description' => 'Outlook 2016 doesn\'t show the CSS style:z-index',
		'use_preg_replace' => 1,
		'replacement' => '$1$2$3$5',
		'match_offset' => 2
	),
	array(
		'regular_expression' => '%(style\s*?=\s*?)(["\'])(.*?)(\s*?overflow\s*?:.*?)(\2|;)%',
		'description' => 'Outlook 2016 doesn\'t show the CSS style:overflow',
		'use_preg_replace' => 1,
		'replacement' => '$1$2$3$5',
		'match_offset' => 2
	),
		array(
		'regular_expression' => '%(style\s*?=\s*?)(["\'])(.*?)(\s*?resize\s*?:.*?)(\2|;)%',
		'description' => 'Outlook 2016 doesn\'t show the CSS style:resize',
		'use_preg_replace' => 1,
		'replacement' => '$1$2$3$5',
		'match_offset' => 2
	),
	array(
		'regular_expression' => '%(style\s*?=\s*?)(["\'])(.*?)(\s*?opacity\s*?:.*?)(\2|;)%',
		'description' => 'Outlook 2016 doesn\'t show the CSS style:opacity',
		'use_preg_replace' => 1,
		'replacement' => '$1$2$3$5',
		'match_offset' => 2
	),
	array(
		'regular_expression' => '%(style\s*?=\s*?)(["\'])(.*?)(\s*?left\s*?:.*?)(\2|;)%',
		'description' => 'Outlook 2016 doesn\'t show the CSS style:left',
		'use_preg_replace' => 1,
		'replacement' => '$1$2$3$5',
		'match_offset' => 2
	),
	array(
		'regular_expression' => '%(style\s*?=\s*?)(["\'])(.*?)(\s*?float\s*?:.*?)(\2|;)%',
		'description' => 'Outlook 2016 doesn\'t show the CSS style:float',
		'use_preg_replace' => 1,
		'replacement' => '$1$2$3$5',
		'match_offset' => 2
	),
	 
);
