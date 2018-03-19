<?php
require_once(dirname(__FILE__) . '/_common.php');

class admin_functions_api_designrules_yahoo extends IEM_Tests_Unit_DesignRuleTests
{
	public function test_Remove_Link_Element()
	{
		$html = "
			<head>
				<LINK REL=\"StyleSheet\" HREF=\"somecss.css\" TITLE=\"Contemporary\" />
			</head>
			<body>
				<LINK REL=\"StyleSheet\" HREF=\"somecss.css\" TITLE=\"Contemporary\" />
				<p>Some contents</p>
			</body>
		";

		$expected = "
			<head>
				<xlink REL=\"StyleSheet\" HREF=\"somecss.css\" TITLE=\"Contemporary\" />
			</head>
			<body>
				<xlink REL=\"StyleSheet\" HREF=\"somecss.css\" TITLE=\"Contemporary\" />
				<p>Some contents</p>
			</body>
		";

		$object = new Design_Rules_Check_API('Yahoo');
		$processed = $object->Process($html, true);

		$this->assertEquals($this->_normalizeSpace($expected), $this->_normalizeSpace($processed));
	}

	public function test_Remove_Link_Element_2()
	{
		$html = "
			<head>
				<LINK REL=\"StyleSheet\" HREF=\"somecss.css\" TITLE=\"Contemporary\" />
			</head>
			<body>
				<LINK REL=\"StyleSheet\" HREF=\"somecss.css\" TITLE=\"Contemporary\" />
				<p>Some contents</p>
			</body>
		";

		$expected = "
			<head>
				<xlink REL=\"StyleSheet\" HREF=\"somecss.css\" TITLE=\"Contemporary\" />
			</head>
			<body>
				<xlink REL=\"StyleSheet\" HREF=\"somecss.css\" TITLE=\"Contemporary\" />
				<p>Some contents</p>
			</body>
		";

		$object = new Design_Rules_Check_API('Yahoo');
		$processed = $object->Process($html, true);

		$this->assertEquals($this->_normalizeSpace($expected), $this->_normalizeSpace($processed));
	}

	public function test_Remove_InlineStyle_Backgound_Position()
	{
		$html = "
			<body>
				<p style=\"
					somedummyproperty:1px;
					background-position: top left;
				\">Some contents</p>
			</body>
		";

		$expected = "
			<body>
				<p style=\"
					somedummyproperty:1px;;
				\">Some contents</p>
			</body>
		";

		$object = new Design_Rules_Check_API('Yahoo');
		$processed = $object->Process($html, true);

		$this->assertEquals($this->_normalizeSpace($expected), $this->_normalizeSpace($processed));
	}

	public function test_Remove_InlineStyle_Backgound_Position_2()
	{
		$html = "
			<body>
				<p style=\"background-position: top left\">Some contents</p>
			</body>
		";

		$expected = "
			<body>
				<p style=\"\">Some contents</p>
			</body>
		";

		$object = new Design_Rules_Check_API('Yahoo');
		$processed = $object->Process($html, true);

		$this->assertEquals($this->_normalizeSpace($expected), $this->_normalizeSpace($processed));
	}

	public function test_Remove_InlineStyle_Clip()
	{
		$html = "
			<body>
				<p>The clip property is here cutting an image:</p>
				<p>
					<img border=\"0\" src=\"bookasp20.gif\" style=\"somepcss:1px; clip : rect(0px 50px 200px 0px);\" />
				</p>
			</body>
		";

		$expected = "
			<body>
				<p>The clip property is here cutting an image:</p>
				<p>
					<img border=\"0\" src=\"bookasp20.gif\" style=\"somepcss:1px;;\" />
				</p>
			</body>
		";

		$object = new Design_Rules_Check_API('Yahoo');
		$processed = $object->Process($html, true);

		$this->assertEquals($this->_normalizeSpace($expected), $this->_normalizeSpace($processed));
	}

	public function test_Remove_InlineStyle_Clip_2()
	{
		$html = "
			<body>
				<p>The clip property is here cutting an image:</p>
				<p>
					<img border=\"0\" src=\"bookasp20.gif\" style = \"clip : rect(0px 50px 200px 0px);\" />
				</p>
			</body>
		";

		$expected = "
			<body>
				<p>The clip property is here cutting an image:</p>
				<p>
					<img border=\"0\" src=\"bookasp20.gif\" style = \";\" />
				</p>
			</body>
		";

		$object = new Design_Rules_Check_API('Yahoo');
		$processed = $object->Process($html, true);

		$this->assertEquals($this->_normalizeSpace($expected), $this->_normalizeSpace($processed));
	}

	public function test_Remove_InlineStyle_ListStylePosition()
	{
		$html = "
			<body>
				<p>Some text here</p>
				<ul style= \"list-style-position: outside\">
					<li>One</li>
					<li>Two</li>
					<li>Three</li>
				</ul>
			</body>
		";

		$expected = "
			<body>
				<p>Some text here</p>
				<ul style= \"\">
					<li>One</li>
					<li>Two</li>
					<li>Three</li>
				</ul>
			</body>
		";

		$object = new Design_Rules_Check_API('Yahoo');
		$processed = $object->Process($html, true);

		$this->assertEquals($this->_normalizeSpace($expected), $this->_normalizeSpace($processed));
	}

	public function test_Remove_InlineStyle_ListStylePosition_2()
	{
		$html = "
			<body>
				<p>Some text here</p>
				<ul style =\"somep:2px;list-style-position: inside\">
					<li>One</li>
					<li>Two</li>
					<li>Three</li>
				</ul>
			</body>
		";

		$expected = "
			<body>
				<p>Some text here</p>
				<ul style =\"somep:2px;\">
					<li>One</li>
					<li>Two</li>
					<li>Three</li>
				</ul>
			</body>
		";

		$object = new Design_Rules_Check_API('Yahoo');
		$processed = $object->Process($html, true);

		$this->assertEquals($this->_normalizeSpace($expected), $this->_normalizeSpace($processed));
	}

	public function test_Remove_InlineStyle_Position()
	{
		$html = "
			<body>
				<p>Some text here</p>
				<p>
					<img border=\"0\" src=\"bookasp20.gif\" style= \"somepcss:1px; position: top left;\" />
				</p>
			</body>
		";

		$expected = "
			<body>
				<p>Some text here</p>
				<p>
					<img border=\"0\" src=\"bookasp20.gif\" style= \"somepcss:1px;;\" />
				</p>
			</body>
		";

		$object = new Design_Rules_Check_API('Yahoo');
		$processed = $object->Process($html, true);

		$this->assertEquals($this->_normalizeSpace($expected), $this->_normalizeSpace($processed));
	}

	public function test_Remove_InlineStyle_Position_2()
	{
		$html = "
			<body>
				<p>Some text here</p>
				<p>
					<img border=\"0\" src=\"bookasp20.gif\" style = ' position: top left' />
				</p>
			</body>
		";

		$expected = "
			<body>
				<p>Some text here</p>
				<p>
					<img border=\"0\" src=\"bookasp20.gif\" style = '' />
				</p>
			</body>
		";

		$object = new Design_Rules_Check_API('Yahoo');
		$processed = $object->Process($html, true);

		$this->assertEquals($this->_normalizeSpace($expected), $this->_normalizeSpace($processed));
	}
}