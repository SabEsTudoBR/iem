<?php
require_once(dirname(__FILE__) . '/_common.php');

class admin_functions_api_designrules_hotmail extends IEM_Tests_Unit_DesignRuleTests
{
	public function test_Remove_Style_Before_Body()
	{
		$html = "
			<style type=\"text/stylesheet\">
				input { somebogusstyle: 1px; }
			</style>
			<body>
				<style type=\"text/stylesheet\">
					input { somebogusstyle: 1px; }
				</style>
				<p>Some contents</p>
			</body>
		";

		$expected = "
			<body>
				<style type=\"text/stylesheet\">
					input { somebogusstyle: 1px; }
				</style>
				<p>Some contents</p>
			</body>
		";

		$object = new Design_Rules_Check_API('Hotmail');
		$processed = $object->Process($html, true);

		$this->assertEquals($this->_normalizeSpace($expected), $this->_normalizeSpace($processed));
	}

	public function test_Remove_Style_Before_Body_2()
	{
		$html = "
			<style type=\"text/stylesheet\">
				input { somebogusstyle: 1px; }
			</style>
			<style type=\"text/stylesheet\">
				input { somebogusstyle: 1px; }
			</style>
			<body>
				<style type=\"text/stylesheet\">
					input { somebogusstyle: 1px; }
				</style>
				<p>Some contents</p>
			</body>
		";

		$expected = "
			<body>
				<style type=\"text/stylesheet\">
					input { somebogusstyle: 1px; }
				</style>
				<p>Some contents</p>
			</body>
		";

		$object = new Design_Rules_Check_API('Hotmail');
		$processed = $object->Process($html, true);

		$this->assertEquals($this->_normalizeSpace($expected), $this->_normalizeSpace($processed));
	}

	public function test_Remove_Style_Before_Body_3()
	{
		$html = "
			<style type=\"text/stylesheet\">
				input { somebogusstyle: 1px; }
			</ style>
			<style type=\"text/stylesheet\">
				input { somebogusstyle: 1px; }
			</ style>
			<body>
				<style type=\"text/stylesheet\">
					input { somebogusstyle: 1px; }
				</ style>
				<p>Some contents</p>
			</ body>
		";

		$expected = "
			<body>
				<style type=\"text/stylesheet\">
					input { somebogusstyle: 1px; }
				</ style>
				<p>Some contents</p>
			</ body>
		";

		$object = new Design_Rules_Check_API('Hotmail');
		$processed = $object->Process($html, true);

		$this->assertEquals($this->_normalizeSpace($expected), $this->_normalizeSpace($processed));
	}

	public function test_Remove_Import_In_Style()
	{
		$html = "
			<body>
				<style type=\"text/stylesheet\">
					@import url(somecss);
				</ style>
				<p>Some contents</p>
			</ body>
		";

		$expected = "
			<body>
				<style type=\"text/stylesheet\">
					;
				</ style>
				<p>Some contents</p>
			</ body>
		";

		$object = new Design_Rules_Check_API('Hotmail');
		$processed = $object->Process($html, true);

		$this->assertEquals($this->_normalizeSpace($expected), $this->_normalizeSpace($processed));
	}

	public function test_Remove_Import_In_Style_2()
	{
		$html = "
			<style type=\"text/stylesheet\">
				@import url(somecss);
				input { somebogusstyle: 1px; }
			</ style>
			<style type=\"text/stylesheet\">
				input { somebogusstyle: 1px; }
			</ style>
			<body>
				<style type=\"text/stylesheet\">
					@import url(somecss);
					input { somebogusstyle: 1px; }
				</ style>
				<style type=\"text/stylesheet\">@import url(somecss);</style>
				<p>Some contents</p>
			</ body>
		";

		$expected = "
			<body>
				<style type=\"text/stylesheet\">
					;
					input { somebogusstyle: 1px; }
				</ style>
				<style type=\"text/stylesheet\">;</style>
				<p>Some contents</p>
			</ body>
		";

		$object = new Design_Rules_Check_API('Hotmail');
		$processed = $object->Process($html, true);

		$this->assertEquals($this->_normalizeSpace($expected), $this->_normalizeSpace($processed));
	}

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
			</head>
			<body>
				<p>Some contents</p>
			</body>
		";

		$object = new Design_Rules_Check_API('Hotmail');
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
			</head>
			<body>
				<p>Some contents</p>
			</body>
		";

		$object = new Design_Rules_Check_API('Hotmail');
		$processed = $object->Process($html, true);

		$this->assertEquals($this->_normalizeSpace($expected), $this->_normalizeSpace($processed));
	}

	public function test_Remove_InlineStyle_Backgound_Image()
	{
		$html = "
			<body style=\"background-image: url('marble.gif'); somedummyproperty:1px;\">
				<p>Some contents</p>
			</body>
		";

		$expected = "
			<body style=\"; somedummyproperty:1px;\">
				<p>Some contents</p>
			</body>
		";

		$object = new Design_Rules_Check_API('Hotmail');
		$processed = $object->Process($html, true);

		$this->assertEquals($this->_normalizeSpace($expected), $this->_normalizeSpace($processed));
	}

	public function test_Remove_InlineStyle_Backgound_Image_Combined()
	{
		$html = "
			<body>
				<p style=\"
					somedummyproperty:1px;
					background-image: url('marble.gif');
					background-position: top left;
  					background-spacing: 5px;
				\">Some contents</p>
			</body>
		";

		$expected = "
			<body>
				<p style=\"
					somedummyproperty:1px;;;;
				\">Some contents</p>
			</body>
		";

		$object = new Design_Rules_Check_API('Hotmail');
		$processed = $object->Process($html, true);

		$this->assertEquals($this->_normalizeSpace($expected), $this->_normalizeSpace($processed));
	}

	public function test_Remove_InlineStyle_Border()
	{
		$html = "
			<body>
				<p style=\"border:5px;\">Some contents</p>
			</body>
		";

		$expected = "
			<body>
				<p style=\";\">Some contents</p>
			</body>
		";

		$object = new Design_Rules_Check_API('Hotmail');
		$processed = $object->Process($html, true);

		$this->assertEquals($this->_normalizeSpace($expected), $this->_normalizeSpace($processed));
	}

	public function test_Remove_InlineStyle_Border_2()
	{
		$html = "
			<body>
				<p style=\"border:5px; SomeBogusProperty:2px;\">Some contents</p>
			</body>
		";

		$expected = "
			<body>
				<p style=\"; SomeBogusProperty:2px;\">Some contents</p>
			</body>
		";

		$object = new Design_Rules_Check_API('Hotmail');
		$processed = $object->Process($html, true);

		$this->assertEquals($this->_normalizeSpace($expected), $this->_normalizeSpace($processed));
	}

	public function test_Remove_InlineStyle_Border_3()
	{
		$html = "
			<body>
				<p style=\"border:5px\">Some contents</p>
			</body>
		";

		$expected = "
			<body>
				<p style=\"\">Some contents</p>
			</body>
		";

		$object = new Design_Rules_Check_API('Hotmail');
		$processed = $object->Process($html, true);

		$this->assertEquals($this->_normalizeSpace($expected), $this->_normalizeSpace($processed));
	}

	public function test_Remove_InlineStyle_Border_4()
	{
		$html = "
			<body>
				<p style=\"border-top:5px; border: 5px; border-left: 6px;\">Some contents</p>
			</body>
		";

		$expected = "
			<body>
				<p style=\"border-top:5px;; border-left: 6px;\">Some contents</p>
			</body>
		";

		$object = new Design_Rules_Check_API('Hotmail');
		$processed = $object->Process($html, true);

		$this->assertEquals($this->_normalizeSpace($expected), $this->_normalizeSpace($processed));
	}

	public function test_Remove_InlineStyle_Caption_Side()
	{
		$html = "
			<body>
				<table border=\"1\">
					<caption style=\"caption-side:bottom\">This is a caption</caption>
					<tr>
						<td>Peter</td>
						<td>Griffin</td>
					</tr>
					<tr>
						<td>Lois</td>
						<td>Griffin</td>
					</tr>
				</table>
			</body>
		";

		$expected = "
			<body>
				<table border=\"1\">
					<caption style=\"\">This is a caption</caption>
					<tr>
						<td>Peter</td>
						<td>Griffin</td>
					</tr>
					<tr>
						<td>Lois</td>
						<td>Griffin</td>
					</tr>
				</table>
			</body>
		";

		$object = new Design_Rules_Check_API('Hotmail');
		$processed = $object->Process($html, true);

		$this->assertEquals($this->_normalizeSpace($expected), $this->_normalizeSpace($processed));
	}

	public function test_Remove_InlineStyle_Caption_Side_2()
	{
		$html = "
			<body>
				<table border=\"1\">
					<caption style=\"someproperty:1px;caption-side:bottom;someproperty2:1px;\">This is a caption</caption>
					<tr>
						<td>Peter</td>
						<td>Griffin</td>
					</tr>
					<tr>
						<td>Lois</td>
						<td>Griffin</td>
					</tr>
				</table>
			</body>
		";

		$expected = "
			<body>
				<table border=\"1\">
					<caption style=\"someproperty:1px;;someproperty2:1px;\">This is a caption</caption>
					<tr>
						<td>Peter</td>
						<td>Griffin</td>
					</tr>
					<tr>
						<td>Lois</td>
						<td>Griffin</td>
					</tr>
				</table>
			</body>
		";

		$object = new Design_Rules_Check_API('Hotmail');
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

		$object = new Design_Rules_Check_API('Hotmail');
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

		$object = new Design_Rules_Check_API('Hotmail');
		$processed = $object->Process($html, true);

		$this->assertEquals($this->_normalizeSpace($expected), $this->_normalizeSpace($processed));
	}

	public function test_Remove_InlineStyle_EmptyCells()
	{
		$html = "
			<body>
				<table border=\"1\" style = \"empty-cells : show\">
					<tr>
						<td></td>
						<td>Griffin</td>
					</tr>
					<tr>
						<td>Lois</td>
						<td>Griffin</td>
					</tr>
				</table>
			</body>
		";

		$expected = "
			<body>
				<table border=\"1\" style = \"\">
					<tr>
						<td></td>
						<td>Griffin</td>
					</tr>
					<tr>
						<td>Lois</td>
						<td>Griffin</td>
					</tr>
				</table>
			</body>
		";

		$object = new Design_Rules_Check_API('Hotmail');
		$processed = $object->Process($html, true);

		$this->assertEquals($this->_normalizeSpace($expected), $this->_normalizeSpace($processed));
	}

	public function test_Remove_InlineStyle_EmptyCells_2()
	{
		$html = "
			<body>
				<table border=\"1\">
					<caption style=\"someproperty:1px;empty-cells : show;someproperty2:1px;\">This is a caption</caption>
					<tr>
						<td>Peter</td>
						<td>Griffin</td>
					</tr>
					<tr>
						<td>Lois</td>
						<td>Griffin</td>
					</tr>
				</table>
			</body>
		";

		$expected = "
			<body>
				<table border=\"1\">
					<caption style=\"someproperty:1px;;someproperty2:1px;\">This is a caption</caption>
					<tr>
						<td>Peter</td>
						<td>Griffin</td>
					</tr>
					<tr>
						<td>Lois</td>
						<td>Griffin</td>
					</tr>
				</table>
			</body>
		";

		$object = new Design_Rules_Check_API('Hotmail');
		$processed = $object->Process($html, true);

		$this->assertEquals($this->_normalizeSpace($expected), $this->_normalizeSpace($processed));
	}

	public function test_Remove_InlineStyle_Filter()
	{
		$html = "
			<body>
				<p>Some text here</p>
				<p>
					<img border=\"0\" src=\"bookasp20.gif\" style= \"somepcss:1px; filter:0.4;\" />
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

		$object = new Design_Rules_Check_API('Hotmail');
		$processed = $object->Process($html, true);

		$this->assertEquals($this->_normalizeSpace($expected), $this->_normalizeSpace($processed));
	}

	public function test_Remove_InlineStyle_Filter_2()
	{
		$html = "
			<body>
				<p>Some text here</p>
				<p>
					<img border=\"0\" src=\"bookasp20.gif\" style = ' filter: 0.4' />
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

		$object = new Design_Rules_Check_API('Hotmail');
		$processed = $object->Process($html, true);

		$this->assertEquals($this->_normalizeSpace($expected), $this->_normalizeSpace($processed));
	}

	public function test_Remove_InlineStyle_ListStyleImage()
	{
		$html = "
			<body>
				<p>Some text here</p>
				<ul style= \"list-style-image: url('hello.gif')\">
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

		$object = new Design_Rules_Check_API('Hotmail');
		$processed = $object->Process($html, true);

		$this->assertEquals($this->_normalizeSpace($expected), $this->_normalizeSpace($processed));
	}

	public function test_Remove_InlineStyle_ListStyleImage_2()
	{
		$html = "
			<body>
				<p>Some text here</p>
				<ul style =\"somep:2px;list-style-image: url('hello.gif')\">
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

		$object = new Design_Rules_Check_API('Hotmail');
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

		$object = new Design_Rules_Check_API('Hotmail');
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

		$object = new Design_Rules_Check_API('Hotmail');
		$processed = $object->Process($html, true);

		$this->assertEquals($this->_normalizeSpace($expected), $this->_normalizeSpace($processed));
	}

	public function test_Remove_InlineStyle_ListStyleImage_ListStylePosition()
	{
		$html = "
			<body>
				<p>Some text here</p>
				<ul style= \"list-style-position: outside; list-style-image: url('bullet.gif'); someproperty: 1px;\">
					<li>One</li>
					<li>Two</li>
					<li>Three</li>
				</ul>
			</body>
		";

		$expected = "
			<body>
				<p>Some text here</p>
				<ul style= \";; someproperty: 1px;\">
					<li>One</li>
					<li>Two</li>
					<li>Three</li>
				</ul>
			</body>
		";

		$object = new Design_Rules_Check_API('Hotmail');
		$processed = $object->Process($html, true);

		$this->assertEquals($this->_normalizeSpace($expected), $this->_normalizeSpace($processed));
	}

	public function test_Remove_InlineStyle_Margin()
	{
		$html = "
			<body>
				<p>Some text here</p>
				<ul style= \"margin: 1px;\">
					<li>One</li>
					<li>Two</li>
					<li>Three</li>
				</ul>
			</body>
		";

		$expected = "
			<body>
				<p>Some text here</p>
				<ul style= \";\">
					<li>One</li>
					<li>Two</li>
					<li>Three</li>
				</ul>
			</body>
		";

		$object = new Design_Rules_Check_API('Hotmail');
		$processed = $object->Process($html, true);

		$this->assertEquals($this->_normalizeSpace($expected), $this->_normalizeSpace($processed));
	}

	public function test_Remove_InlineStyle_Margin_2()
	{
		$html = "
			<body>
				<p>Some text here</p>
				<ul style= \"margin: 1px; someprop: 2px;\">
					<li>One</li>
					<li>Two</li>
					<li>Three</li>
				</ul>
			</body>
		";

		$expected = "
			<body>
				<p>Some text here</p>
				<ul style= \"; someprop: 2px;\">
					<li>One</li>
					<li>Two</li>
					<li>Three</li>
				</ul>
			</body>
		";

		$object = new Design_Rules_Check_API('Hotmail');
		$processed = $object->Process($html, true);

		$this->assertEquals($this->_normalizeSpace($expected), $this->_normalizeSpace($processed));
	}

	public function test_Remove_InlineStyle_Margin_3()
	{
		$html = "
			<body>
				<p>Some text here</p>
				<ul style= \"somepropagain: 211px; margin: 1px; someprop: 2px;\">
					<li>One</li>
					<li>Two</li>
					<li>Three</li>
				</ul>
			</body>
		";

		$expected = "
			<body>
				<p>Some text here</p>
				<ul style= \"somepropagain: 211px;; someprop: 2px;\">
					<li>One</li>
					<li>Two</li>
					<li>Three</li>
				</ul>
			</body>
		";

		$object = new Design_Rules_Check_API('Hotmail');
		$processed = $object->Process($html, true);

		$this->assertEquals($this->_normalizeSpace($expected), $this->_normalizeSpace($processed));
	}

	public function test_Remove_InlineStyle_Margin_4()
	{
		$html = "
			<body>
				<p>Some text here</p>
				<ul style= \"margin-left: 211px; margin: 1px; margin-top:50px; margin-right: 2px;\">
					<li>One</li>
					<li>Two</li>
					<li>Three</li>
				</ul>
			</body>
		";

		$expected = "
			<body>
				<p>Some text here</p>
				<ul style= \"margin-left: 211px;; margin-top:50px; margin-right: 2px;\">
					<li>One</li>
					<li>Two</li>
					<li>Three</li>
				</ul>
			</body>
		";

		$object = new Design_Rules_Check_API('Hotmail');
		$processed = $object->Process($html, true);

		$this->assertEquals($this->_normalizeSpace($expected), $this->_normalizeSpace($processed));
	}

	public function test_Remove_InlineStyle_Opacity()
	{
		$html = "
			<body>
				<p>Some text here</p>
				<p>
					<img border=\"0\" src=\"bookasp20.gif\" style= \"somepcss:1px; opacity:0.4;\" />
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

		$object = new Design_Rules_Check_API('Hotmail');
		$processed = $object->Process($html, true);

		$this->assertEquals($this->_normalizeSpace($expected), $this->_normalizeSpace($processed));
	}

	public function test_Remove_InlineStyle_Opacity_2()
	{
		$html = "
			<body>
				<p>Some text here</p>
				<p>
					<img border=\"0\" src=\"bookasp20.gif\" style = ' opacity: 0.4' />
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

		$object = new Design_Rules_Check_API('Hotmail');
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

		$object = new Design_Rules_Check_API('Hotmail');
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

		$object = new Design_Rules_Check_API('Hotmail');
		$processed = $object->Process($html, true);

		$this->assertEquals($this->_normalizeSpace($expected), $this->_normalizeSpace($processed));
	}
}