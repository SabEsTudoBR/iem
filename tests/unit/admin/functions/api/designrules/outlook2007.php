<?php
require_once(dirname(__FILE__) . '/_common.php');

class admin_functions_api_designrules_outlook2007 extends IEM_Tests_Unit_DesignRuleTests
{
	public function test_Remove_InlineStyle_Azimuth()
	{
		$html = "
			<body style=\"azimuth: inherit; somedummyproperty:1px;\">
				<p>Some contents</p>
			</body>
		";

		$expected = "
			<body style=\"; somedummyproperty:1px;\">
				<p>Some contents</p>
			</body>
		";

		$object = new Design_Rules_Check_API('Outlook 2007');
		$processed = $object->Process($html, true);

		$this->assertEquals($this->_normalizeSpace($expected), $this->_normalizeSpace($processed));
	}

	public function test_Remove_InlineStyle_Azimuth_2()
	{
		$html = "
			<body style = \"azimuth: inherit\">
				<p>Some contents</p>
			</body>
		";

		$expected = "
			<body style = \"\">
				<p>Some contents</p>
			</body>
		";

		$object = new Design_Rules_Check_API('Outlook 2007');
		$processed = $object->Process($html, true);

		$this->assertEquals($this->_normalizeSpace($expected), $this->_normalizeSpace($processed));
	}

	public function test_Remove_InlineStyle_BackgroundAttachment()
	{
		$html = "
			<body style=\"   background-attachment: scroll; somedummyproperty:1px;\">
				<p>Some contents</p>
			</body>
		";

		$expected = "
			<body style=\"; somedummyproperty:1px;\">
				<p>Some contents</p>
			</body>
		";

		$object = new Design_Rules_Check_API('Outlook 2007');
		$processed = $object->Process($html, true);

		$this->assertEquals($this->_normalizeSpace($expected), $this->_normalizeSpace($processed));
	}

	public function test_Remove_InlineStyle_BackgroundAttachment_2()
	{
		$html = "
			<body style = \"background-attachment: scroll\">
				<p>Some contents</p>
			</body>
		";

		$expected = "
			<body style = \"\">
				<p>Some contents</p>
			</body>
		";

		$object = new Design_Rules_Check_API('Outlook 2007');
		$processed = $object->Process($html, true);

		$this->assertEquals($this->_normalizeSpace($expected), $this->_normalizeSpace($processed));
	}

	public function test_Remove_InlineStyle_BackgroundImage()
	{
		$html = "
			<body style=\"   background-image: url(file:///C|/Program%20Files/Bradbury/TopStyle3/IMG.gif); somedummyproperty:1px;\">
				<p>Some contents</p>
			</body>
		";

		$expected = "
			<body style=\"; somedummyproperty:1px;\">
				<p>Some contents</p>
			</body>
		";

		$object = new Design_Rules_Check_API('Outlook 2007');
		$processed = $object->Process($html, true);

		$this->assertEquals($this->_normalizeSpace($expected), $this->_normalizeSpace($processed));
	}

	public function test_Remove_InlineStyle_BackgroundImage_2()
	{
		$html = "
			<body style = \"background-image: inherit\">
				<p>Some contents</p>
			</body>
		";

		$expected = "
			<body style = \"\">
				<p>Some contents</p>
			</body>
		";

		$object = new Design_Rules_Check_API('Outlook 2007');
		$processed = $object->Process($html, true);

		$this->assertEquals($this->_normalizeSpace($expected), $this->_normalizeSpace($processed));
	}

	public function test_Remove_InlineStyle_BackgroundPosition()
	{
		$html = "
			<body style=\"   background-position: inherit; somedummyproperty:1px;\">
				<p>Some contents</p>
			</body>
		";

		$expected = "
			<body style=\"; somedummyproperty:1px;\">
				<p>Some contents</p>
			</body>
		";

		$object = new Design_Rules_Check_API('Outlook 2007');
		$processed = $object->Process($html, true);

		$this->assertEquals($this->_normalizeSpace($expected), $this->_normalizeSpace($processed));
	}

	public function test_Remove_InlineStyle_BackgroundPosition_2()
	{
		$html = "
			<body style = \"background-position: inherit\">
				<p>Some contents</p>
			</body>
		";

		$expected = "
			<body style = \"\">
				<p>Some contents</p>
			</body>
		";

		$object = new Design_Rules_Check_API('Outlook 2007');
		$processed = $object->Process($html, true);

		$this->assertEquals($this->_normalizeSpace($expected), $this->_normalizeSpace($processed));
	}

	public function test_Remove_InlineStyle_BackgroundRepeat()
	{
		$html = "
			<body style=\"   background-repeat: repeat-y; somedummyproperty:1px;\">
				<p>Some contents</p>
			</body>
		";

		$expected = "
			<body style=\"; somedummyproperty:1px;\">
				<p>Some contents</p>
			</body>
		";

		$object = new Design_Rules_Check_API('Outlook 2007');
		$processed = $object->Process($html, true);

		$this->assertEquals($this->_normalizeSpace($expected), $this->_normalizeSpace($processed));
	}

	public function test_Remove_InlineStyle_BackgroundRepeat_2()
	{
		$html = "
			<body style = \"background-repeat: repeat-y\">
				<p>Some contents</p>
			</body>
		";

		$expected = "
			<body style = \"\">
				<p>Some contents</p>
			</body>
		";

		$object = new Design_Rules_Check_API('Outlook 2007');
		$processed = $object->Process($html, true);

		$this->assertEquals($this->_normalizeSpace($expected), $this->_normalizeSpace($processed));
	}

	public function test_Remove_InlineStyle_BackgroundSpacing()
	{
		$html = "
			<body style=\"   background-spacing: 1px; somedummyproperty:1px;\">
				<p>Some contents</p>
			</body>
		";

		$expected = "
			<body style=\"; somedummyproperty:1px;\">
				<p>Some contents</p>
			</body>
		";

		$object = new Design_Rules_Check_API('Outlook 2007');
		$processed = $object->Process($html, true);

		$this->assertEquals($this->_normalizeSpace($expected), $this->_normalizeSpace($processed));
	}

	public function test_Remove_InlineStyle_BackgroundSpacing_2()
	{
		$html = "
			<body style = \"background-spacing: 1px\">
				<p>Some contents</p>
			</body>
		";

		$expected = "
			<body style = \"\">
				<p>Some contents</p>
			</body>
		";

		$object = new Design_Rules_Check_API('Outlook 2007');
		$processed = $object->Process($html, true);

		$this->assertEquals($this->_normalizeSpace($expected), $this->_normalizeSpace($processed));
	}

	public function test_Remove_InlineStyle_Combined_Backgrounds()
	{
		$html = "
			<body style = \"
				background-spacing: 1px;
				background-repeat: repeat-y;
				somedummyproperties: 5px ;
				background-position: inherit;
				background-image: url(file:///C|/Program%20Files/Bradbury/TopStyle3/IMG.gif);
				dummmy2: none;
				background-attachment: scroll\">

				<p>Some contents</p>
			</body>
		";

		$expected = "
			<body style = \";;
				somedummyproperties: 5px ;;;
				dummmy2: none;\">

				<p>Some contents</p>
			</body>
		";

		$object = new Design_Rules_Check_API('Outlook 2007');
		$processed = $object->Process($html, true);

		$this->assertEquals($this->_normalizeSpace($expected), $this->_normalizeSpace($processed));
	}

	public function test_Remove_InlineStyle_bottom()
	{
		$html = "
			<body style=\"   bottom: 1px; somedummyproperty:1px;\">
				<p>Some contents</p>
			</body>
		";

		$expected = "
			<body style=\"; somedummyproperty:1px;\">
				<p>Some contents</p>
			</body>
		";

		$object = new Design_Rules_Check_API('Outlook 2007');
		$processed = $object->Process($html, true);

		$this->assertEquals($this->_normalizeSpace($expected), $this->_normalizeSpace($processed));
	}

	public function test_Remove_InlineStyle_bottom_2()
	{
		$html = "
			<body style = \"bottom: 1px\">
				<p>Some contents</p>
			</body>
		";

		$expected = "
			<body style = \"\">
				<p>Some contents</p>
			</body>
		";

		$object = new Design_Rules_Check_API('Outlook 2007');
		$processed = $object->Process($html, true);

		$this->assertEquals($this->_normalizeSpace($expected), $this->_normalizeSpace($processed));
	}

	public function test_Remove_InlineStyle_bottom_3()
	{
		$html = "
			<body style=\"   border-bottom: 1px solid #000000; bottom: 1px; somedummyproperty:1px;\">
				<p>Some contents</p>
			</body>
		";

		$expected = "
			<body style=\"   border-bottom: 1px solid #000000;; somedummyproperty:1px;\">
				<p>Some contents</p>
			</body>
		";

		$object = new Design_Rules_Check_API('Outlook 2007');
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

		$object = new Design_Rules_Check_API('Outlook 2007');
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

		$object = new Design_Rules_Check_API('Outlook 2007');
		$processed = $object->Process($html, true);

		$this->assertEquals($this->_normalizeSpace($expected), $this->_normalizeSpace($processed));
	}

	public function test_Remove_InlineStyle_Clip()
	{
		$contents = "
			<body style=\" somebogusstyle1: none; clip: rect(10px, 5px, 10px, 5px)\">
				<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" bgcolor=\"#f7f7f7\">
				</table>
			</body>
		";

		$condition = $this->_normalizeSpace("
			<body style=\" somebogusstyle1: none;\">
				<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" bgcolor=\"#f7f7f7\">
				</table>
			</body>
		");


		$api = new Design_Rules_Check_API('Outlook 2007');
		$processed = $this->_normalizeSpace($api->Process($contents, true));

		$this->assertEquals($condition, $processed);
	}

	public function test_Remove_InlineStyle_Clip_2()
	{
		$contents = "
			<body style=\"clip: rect(10px, 5px, 10px, 5px);\">
				<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" bgcolor=\"#f7f7f7\">
				</table>
			</body>
		";

		$condition = $this->_normalizeSpace("
			<body style=\";\">
				<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" bgcolor=\"#f7f7f7\">
				</table>
			</body>
		");


		$api = new Design_Rules_Check_API('Outlook 2007');
		$processed = $this->_normalizeSpace($api->Process($contents, true));

		$this->assertEquals($condition, $processed);
	}

	public function test_Remove_InlineStyle_Content()
	{
		$contents = "
			<body style=\" somebogusstyle1: none; content: close-quote; \">
				<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" bgcolor=\"#f7f7f7\">
				</table>
			</body>
		";

		$condition = $this->_normalizeSpace("
			<body style=\" somebogusstyle1: none;; \">
				<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" bgcolor=\"#f7f7f7\">
				</table>
			</body>
		");


		$api = new Design_Rules_Check_API('Outlook 2007');
		$processed = $this->_normalizeSpace($api->Process($contents, true));

		$this->assertEquals($condition, $processed);
	}

	public function test_Remove_InlineStyle_Content_2()
	{
		$contents = "
			<body style = \"content: close-quote;\">
				<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" bgcolor=\"#f7f7f7\">
				</table>
			</body>
		";

		$condition = $this->_normalizeSpace("
			<body style = \";\">
				<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" bgcolor=\"#f7f7f7\">
				</table>
			</body>
		");


		$api = new Design_Rules_Check_API('Outlook 2007');
		$processed = $this->_normalizeSpace($api->Process($contents, true));

		$this->assertEquals($condition, $processed);
	}

	public function test_Remove_InlineStyle_CounterIncrement()
	{
		$contents = "
			<body style=\" somebogusstyle1: none; counter-increment: inherit; \">
				<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" bgcolor=\"#f7f7f7\">
				</table>
			</body>
		";

		$condition = $this->_normalizeSpace("
			<body style=\" somebogusstyle1: none;; \">
				<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" bgcolor=\"#f7f7f7\">
				</table>
			</body>
		");


		$api = new Design_Rules_Check_API('Outlook 2007');
		$processed = $this->_normalizeSpace($api->Process($contents, true));

		$this->assertEquals($condition, $processed);
	}

	public function test_Remove_InlineStyle_CounterIncrement_2()
	{
		$contents = "
			<body style = \"counter-increment: inherit;\">
				<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" bgcolor=\"#f7f7f7\">
				</table>
			</body>
		";

		$condition = $this->_normalizeSpace("
			<body style = \";\">
				<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" bgcolor=\"#f7f7f7\">
				</table>
			</body>
		");


		$api = new Design_Rules_Check_API('Outlook 2007');
		$processed = $this->_normalizeSpace($api->Process($contents, true));

		$this->assertEquals($condition, $processed);
	}

	public function test_Remove_InlineStyle_CounterReset()
	{
		$contents = "
			<body style=\" somebogusstyle1: none; counter-reset: none; \">
				<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" bgcolor=\"#f7f7f7\">
				</table>
			</body>
		";

		$condition = $this->_normalizeSpace("
			<body style=\" somebogusstyle1: none;; \">
				<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" bgcolor=\"#f7f7f7\">
				</table>
			</body>
		");


		$api = new Design_Rules_Check_API('Outlook 2007');
		$processed = $this->_normalizeSpace($api->Process($contents, true));

		$this->assertEquals($condition, $processed);
	}

	public function test_Remove_InlineStyle_CounterReset_2()
	{
		$contents = "
			<body style = \"counter-reset: none;\">
				<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" bgcolor=\"#f7f7f7\">
				</table>
			</body>
		";

		$condition = $this->_normalizeSpace("
			<body style = \";\">
				<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" bgcolor=\"#f7f7f7\">
				</table>
			</body>
		");


		$api = new Design_Rules_Check_API('Outlook 2007');
		$processed = $this->_normalizeSpace($api->Process($contents, true));

		$this->assertEquals($condition, $processed);
	}

	public function test_Remove_InlineStyle_Combined_Counters()
	{
		$contents = "
			<body style=\" somebogusstyle1: none; counter-reset: none; counter-increment: none; somestyle2: 50px; \">
				<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" bgcolor=\"#f7f7f7\">
				</table>
			</body>
		";

		$condition = $this->_normalizeSpace("
			<body style=\" somebogusstyle1: none;;; somestyle2: 50px; \">
				<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" bgcolor=\"#f7f7f7\">
				</table>
			</body>
		");


		$api = new Design_Rules_Check_API('Outlook 2007');
		$processed = $this->_normalizeSpace($api->Process($contents, true));

		$this->assertEquals($condition, $processed);
	}

	public function test_Remove_InlineStyle_Cue()
	{
		$contents = "
			<body style=\" somebogusstyle1: none; cue: url(file:///C|/Program%20Files/Bradbury/TopStyle3/IMG.gif) url(file:///C|/Program%20Files/Bradbury/TopStyle3/IMG.gif); \">
				<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" bgcolor=\"#f7f7f7\">
				</table>
			</body>
		";

		$condition = $this->_normalizeSpace("
			<body style=\" somebogusstyle1: none;; \">
				<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" bgcolor=\"#f7f7f7\">
				</table>
			</body>
		");


		$api = new Design_Rules_Check_API('Outlook 2007');
		$processed = $this->_normalizeSpace($api->Process($contents, true));

		$this->assertEquals($condition, $processed);
	}

	public function test_Remove_InlineStyle_Cue_2()
	{
		$contents = "
			<body style = \"cue: url(file:///C|/Program%20Files/Bradbury/TopStyle3/IMG.gif) url(file:///C|/Program%20Files/Bradbury/TopStyle3/IMG.gif);\">
				<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" bgcolor=\"#f7f7f7\">
				</table>
			</body>
		";

		$condition = $this->_normalizeSpace("
			<body style = \";\">
				<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" bgcolor=\"#f7f7f7\">
				</table>
			</body>
		");


		$api = new Design_Rules_Check_API('Outlook 2007');
		$processed = $this->_normalizeSpace($api->Process($contents, true));

		$this->assertEquals($condition, $processed);
	}

	public function test_Remove_InlineStyle_Cue_3()
	{
		$contents = "
			<body style=\" somebogusstyle1: none; cue-after: inherit; cue-before: inherit; \">
				<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" bgcolor=\"#f7f7f7\">
				</table>
			</body>
		";

		$condition = $this->_normalizeSpace("
			<body style=\" somebogusstyle1: none;;; \">
				<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" bgcolor=\"#f7f7f7\">
				</table>
			</body>
		");


		$api = new Design_Rules_Check_API('Outlook 2007');
		$processed = $this->_normalizeSpace($api->Process($contents, true));

		$this->assertEquals($condition, $processed);
	}

	public function test_Remove_InlineStyle_Cue_4()
	{
		$contents = "
			<body style=\" somebogusstyle1: none; cue: url(file:///C|/Program%20Files/Bradbury/TopStyle3/IMG.gif) url(file:///C|/Program%20Files/Bradbury/TopStyle3/IMG.gif); cue-after: inherit; cue-before: inherit; \">
				<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" bgcolor=\"#f7f7f7\">
				</table>
			</body>
		";

		$condition = $this->_normalizeSpace("
			<body style=\" somebogusstyle1: none;;;; \">
				<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" bgcolor=\"#f7f7f7\">
				</table>
			</body>
		");


		$api = new Design_Rules_Check_API('Outlook 2007');
		$processed = $this->_normalizeSpace($api->Process($contents, true));

		$this->assertEquals($condition, $processed);
	}

	public function test_Remove_InlineStyle_Cursor()
	{
		$contents = "
			<body style=\" somebogusstyle1: none; cursor: crosshair; \">
				<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" bgcolor=\"#f7f7f7\">
				</table>
			</body>
		";

		$condition = $this->_normalizeSpace("
			<body style=\" somebogusstyle1: none;; \">
				<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" bgcolor=\"#f7f7f7\">
				</table>
			</body>
		");


		$api = new Design_Rules_Check_API('Outlook 2007');
		$processed = $this->_normalizeSpace($api->Process($contents, true));

		$this->assertEquals($condition, $processed);
	}

	public function test_Remove_InlineStyle_Cursor_2()
	{
		$contents = "
			<body style = \"cursor: crosshair;\">
				<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" bgcolor=\"#f7f7f7\">
				</table>
			</body>
		";

		$condition = $this->_normalizeSpace("
			<body style = \";\">
				<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" bgcolor=\"#f7f7f7\">
				</table>
			</body>
		");


		$api = new Design_Rules_Check_API('Outlook 2007');
		$processed = $this->_normalizeSpace($api->Process($contents, true));

		$this->assertEquals($condition, $processed);
	}

	public function test_Remove_InlineStyle_Display()
	{
		$contents = "
			<body style=\" somebogusstyle1: none; display: inline; \">
				<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" bgcolor=\"#f7f7f7\">
				</table>
			</body>
		";

		$condition = $this->_normalizeSpace("
			<body style=\" somebogusstyle1: none;; \">
				<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" bgcolor=\"#f7f7f7\">
				</table>
			</body>
		");


		$api = new Design_Rules_Check_API('Outlook 2007');
		$processed = $this->_normalizeSpace($api->Process($contents, true));

		$this->assertEquals($condition, $processed);
	}

	public function test_Remove_InlineStyle_Display_2()
	{
		$contents = "
			<body style = \"display : none;\">
				<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" bgcolor=\"#f7f7f7\">
				</table>
			</body>
		";

		$condition = $this->_normalizeSpace("
			<body style = \";\">
				<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" bgcolor=\"#f7f7f7\">
				</table>
			</body>
		");


		$api = new Design_Rules_Check_API('Outlook 2007');
		$processed = $this->_normalizeSpace($api->Process($contents, true));

		$this->assertEquals($condition, $processed);
	}

	public function test_Remove_InlineStyle_Elevation()
	{
		$contents = "
			<body style=\" somebogusstyle1: none; elevation: below; \">
				<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" bgcolor=\"#f7f7f7\">
				</table>
			</body>
		";

		$condition = $this->_normalizeSpace("
			<body style=\" somebogusstyle1: none;; \">
				<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" bgcolor=\"#f7f7f7\">
				</table>
			</body>
		");


		$api = new Design_Rules_Check_API('Outlook 2007');
		$processed = $this->_normalizeSpace($api->Process($contents, true));

		$this->assertEquals($condition, $processed);
	}

	public function test_Remove_InlineStyle_Elevation_2()
	{
		$contents = "
			<body style = \"elevation :above;\">
				<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" bgcolor=\"#f7f7f7\">
				</table>
			</body>
		";

		$condition = $this->_normalizeSpace("
			<body style = \";\">
				<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" bgcolor=\"#f7f7f7\">
				</table>
			</body>
		");


		$api = new Design_Rules_Check_API('Outlook 2007');
		$processed = $this->_normalizeSpace($api->Process($contents, true));

		$this->assertEquals($condition, $processed);
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

		$object = new Design_Rules_Check_API('Outlook 2007');
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

		$object = new Design_Rules_Check_API('Outlook 2007');
		$processed = $object->Process($html, true);

		$this->assertEquals($this->_normalizeSpace($expected), $this->_normalizeSpace($processed));
	}

	public function test_Remove_InlineStyle_Float()
	{
		$contents = "
			<body style=\" somebogusstyle1: none; float: left; border-bottom: 1px;\">
				<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" bgcolor=\"#f7f7f7\">
				</table>
			</body>
		";

		$condition = $this->_normalizeSpace("
			<body style=\" somebogusstyle1: none;; border-bottom: 1px;\">
				<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" bgcolor=\"#f7f7f7\">
				</table>
			</body>
		");


		$api = new Design_Rules_Check_API('Outlook 2007');
		$processed = $this->_normalizeSpace($api->Process($contents, true));

		$this->assertEquals($condition, $processed);
	}

	public function test_Remove_InlineStyle_Float_2()
	{
		$contents = "
			<body style=\"float: right;\">
				<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" bgcolor=\"#f7f7f7\">
				</table>
			</body>
		";

		$condition = $this->_normalizeSpace("
			<body style=\";\">
				<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" bgcolor=\"#f7f7f7\">
				</table>
			</body>
		");


		$api = new Design_Rules_Check_API('Outlook 2007');
		$processed = $this->_normalizeSpace($api->Process($contents, true));

		$this->assertEquals($condition, $processed);
	}

	public function test_Remove_InlineStyle_FontSizeAdjust()
	{
		$html = "
			<body style=\"font-size-adjust: 4px; somedummyproperty:1px;\">
				<p>Some contents</p>
			</body>
		";

		$expected = "
			<body style=\"; somedummyproperty:1px;\">
				<p>Some contents</p>
			</body>
		";

		$object = new Design_Rules_Check_API('Outlook 2007');
		$processed = $object->Process($html, true);

		$this->assertEquals($this->_normalizeSpace($expected), $this->_normalizeSpace($processed));
	}

	public function test_Remove_InlineStyle_FontSizeAdjust_2()
	{
		$html = "
			<body style = \"font-size-adjust : 4px\">
				<p>Some contents</p>
			</body>
		";

		$expected = "
			<body style = \"\">
				<p>Some contents</p>
			</body>
		";

		$object = new Design_Rules_Check_API('Outlook 2007');
		$processed = $object->Process($html, true);

		$this->assertEquals($this->_normalizeSpace($expected), $this->_normalizeSpace($processed));
	}

	public function test_Remove_InlineStyle_FontStrech()
	{
		$html = "
			<body style=\"font-stretch: ultra-condensed; somedummyproperty:1px;\">
				<p>Some contents</p>
			</body>
		";

		$expected = "
			<body style=\"; somedummyproperty:1px;\">
				<p>Some contents</p>
			</body>
		";

		$object = new Design_Rules_Check_API('Outlook 2007');
		$processed = $object->Process($html, true);

		$this->assertEquals($this->_normalizeSpace($expected), $this->_normalizeSpace($processed));
	}

	public function test_Remove_InlineStyle_FontStrech_2()
	{
		$html = "
			<body style = \"font-stretch: ultra-condensed\">
				<p>Some contents</p>
			</body>
		";

		$expected = "
			<body style = \"\">
				<p>Some contents</p>
			</body>
		";

		$object = new Design_Rules_Check_API('Outlook 2007');
		$processed = $object->Process($html, true);

		$this->assertEquals($this->_normalizeSpace($expected), $this->_normalizeSpace($processed));
	}

	public function test_Remove_InlineStyle_Height()
	{
		$html = "
			<body style=\"height: 2px; somedummyproperty:1px;\">
				<p>Some contents</p>
			</body>
		";

		$expected = "
			<body style=\"; somedummyproperty:1px;\">
				<p>Some contents</p>
			</body>
		";

		$object = new Design_Rules_Check_API('Outlook 2007');
		$processed = $object->Process($html, true);

		$this->assertEquals($this->_normalizeSpace($expected), $this->_normalizeSpace($processed));
	}

	public function test_Remove_InlineStyle_Height_2()
	{
		$html = "
			<body style = \"  height: 2px\">
				<p>Some contents</p>
			</body>
		";

		$expected = "
			<body style = \"\">
				<p>Some contents</p>
			</body>
		";

		$object = new Design_Rules_Check_API('Outlook 2007');
		$processed = $object->Process($html, true);

		$this->assertEquals($this->_normalizeSpace($expected), $this->_normalizeSpace($processed));
	}

	public function test_Remove_InlineStyle_Height_3()
	{
		$html = "
			<body style = \"  height: 2px; someprop-height : 6px;\">
				<p>Some contents</p>
			</body>
		";

		$expected = "
			<body style = \"; someprop-height : 6px;\">
				<p>Some contents</p>
			</body>
		";

		$object = new Design_Rules_Check_API('Outlook 2007');
		$processed = $object->Process($html, true);

		$this->assertEquals($this->_normalizeSpace($expected), $this->_normalizeSpace($processed));
	}

	public function test_Remove_InlineStyle_Left()
	{
		$html = "
			<body style=\"left: 2px; somedummyproperty:1px;\">
				<p>Some contents</p>
			</body>
		";

		$expected = "
			<body style=\"; somedummyproperty:1px;\">
				<p>Some contents</p>
			</body>
		";

		$object = new Design_Rules_Check_API('Outlook 2007');
		$processed = $object->Process($html, true);

		$this->assertEquals($this->_normalizeSpace($expected), $this->_normalizeSpace($processed));
	}

	public function test_Remove_InlineStyle_Left_2()
	{
		$html = "
			<body style = \"  left: 2px\">
				<p>Some contents</p>
			</body>
		";

		$expected = "
			<body style = \"\">
				<p>Some contents</p>
			</body>
		";

		$object = new Design_Rules_Check_API('Outlook 2007');
		$processed = $object->Process($html, true);

		$this->assertEquals($this->_normalizeSpace($expected), $this->_normalizeSpace($processed));
	}

	public function test_Remove_InlineStyle_Left_3()
	{
		$html = "
			<body style = \"  left: 2px; someprop-left : 6px; left-someprop: 5px;\">
				<p>Some contents</p>
			</body>
		";

		$expected = "
			<body style = \"; someprop-left : 6px; left-someprop: 5px;\">
				<p>Some contents</p>
			</body>
		";

		$object = new Design_Rules_Check_API('Outlook 2007');
		$processed = $object->Process($html, true);

		$this->assertEquals($this->_normalizeSpace($expected), $this->_normalizeSpace($processed));
	}

	public function test_Remove_InlineStyle_LineBreak()
	{
		$html = "
			<body style=\"line-break: 2px; somedummyproperty:1px;\">
				<p>Some contents</p>
			</body>
		";

		$expected = "
			<body style=\"; somedummyproperty:1px;\">
				<p>Some contents</p>
			</body>
		";

		$object = new Design_Rules_Check_API('Outlook 2007');
		$processed = $object->Process($html, true);

		$this->assertEquals($this->_normalizeSpace($expected), $this->_normalizeSpace($processed));
	}

	public function test_Remove_InlineStyle_LineBreak_2()
	{
		$html = "
			<body style = \"  line-break: 2px\">
				<p>Some contents</p>
			</body>
		";

		$expected = "
			<body style = \"\">
				<p>Some contents</p>
			</body>
		";

		$object = new Design_Rules_Check_API('Outlook 2007');
		$processed = $object->Process($html, true);

		$this->assertEquals($this->_normalizeSpace($expected), $this->_normalizeSpace($processed));
	}

	public function test_Remove_InlineStyle_ListStyleImage()
	{
		$contents = "
			<body style=\" somebogusstyle1: none; list-style-image: url(blueball.gif);\">
				<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" bgcolor=\"#f7f7f7\">
				</table>
			</body>
		";

		$condition = $this->_normalizeSpace("
			<body style=\" somebogusstyle1: none;;\">
				<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" bgcolor=\"#f7f7f7\">
				</table>
			</body>
		");


		$api = new Design_Rules_Check_API('Outlook 2007');
		$processed = $this->_normalizeSpace($api->Process($contents, true));

		$this->assertEquals($condition, $processed);
	}

	public function test_Remove_InlineStyle_ListStyleImage_2()
	{
		$contents = "
			<body style=\"list-style-image: url(blueball.gif);\">
				<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" bgcolor=\"#f7f7f7\">
				</table>
			</body>
		";

		$condition = $this->_normalizeSpace("
			<body style=\";\">
				<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" bgcolor=\"#f7f7f7\">
				</table>
			</body>
		");


		$api = new Design_Rules_Check_API('Outlook 2007');
		$processed = $this->_normalizeSpace($api->Process($contents, true));

		$this->assertEquals($condition, $processed);
	}

	public function test_Remove_InlineStyle_ListStylePosition()
	{
		$contents = "
			<body style=\" somebogusstyle1: none; list-style-position: inside;\">
				<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" bgcolor=\"#f7f7f7\">
				</table>
			</body>
		";

		$condition = $this->_normalizeSpace("
			<body style=\" somebogusstyle1: none;;\">
				<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" bgcolor=\"#f7f7f7\">
				</table>
			</body>
		");


		$api = new Design_Rules_Check_API('Outlook 2007');
		$processed = $this->_normalizeSpace($api->Process($contents, true));

		$this->assertEquals($condition, $processed);
	}

	public function test_Remove_InlineStyle_ListStylePosition_2()
	{
		$contents = "
			<body style=\"list-style-position: inside;\">
				<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" bgcolor=\"#f7f7f7\">
				</table>
			</body>
		";

		$condition = $this->_normalizeSpace("
			<body style=\";\">
				<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" bgcolor=\"#f7f7f7\">
				</table>
			</body>
		");


		$api = new Design_Rules_Check_API('Outlook 2007');
		$processed = $this->_normalizeSpace($api->Process($contents, true));

		$this->assertEquals($condition, $processed);
	}

	public function test_Remove_InlineStyle_Combined_ListStyles()
	{
		$contents = "
			<body style=\"list-style-position: inside;list-style-image: url(blueball.gif);\">
				<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" bgcolor=\"#f7f7f7\">
				</table>
			</body>
		";

		$condition = $this->_normalizeSpace("
			<body style=\";;\">
				<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" bgcolor=\"#f7f7f7\">
				</table>
			</body>
		");


		$api = new Design_Rules_Check_API('Outlook 2007');
		$processed = $this->_normalizeSpace($api->Process($contents, true));

		$this->assertEquals($condition, $processed);
	}

	public function test_Remove_InlineStyle_Marginheight()
	{
		$html = "
			<body style=\"marginheight: 5px; somedummyproperty:1px;\">
				<p>Some contents</p>
			</body>
		";

		$expected = "
			<body style=\"; somedummyproperty:1px;\">
				<p>Some contents</p>
			</body>
		";

		$object = new Design_Rules_Check_API('Outlook 2007');
		$processed = $object->Process($html, true);

		$this->assertEquals($this->_normalizeSpace($expected), $this->_normalizeSpace($processed));
	}

	public function test_Remove_InlineStyle_Marginheight_2()
	{
		$html = "
			<body style = \"marginheight  : 5px\">
				<p>Some contents</p>
			</body>
		";

		$expected = "
			<body style = \"\">
				<p>Some contents</p>
			</body>
		";

		$object = new Design_Rules_Check_API('Outlook 2007');
		$processed = $object->Process($html, true);

		$this->assertEquals($this->_normalizeSpace($expected), $this->_normalizeSpace($processed));
	}

	public function test_Remove_InlineStyle_Markeroffset()
	{
		$html = "
			<body style=\"marker-offset: auto; somedummyproperty:1px;\">
				<p>Some contents</p>
			</body>
		";

		$expected = "
			<body style=\"; somedummyproperty:1px;\">
				<p>Some contents</p>
			</body>
		";

		$object = new Design_Rules_Check_API('Outlook 2007');
		$processed = $object->Process($html, true);

		$this->assertEquals($this->_normalizeSpace($expected), $this->_normalizeSpace($processed));
	}

	public function test_Remove_InlineStyle_Markeroffset_2()
	{
		$html = "
			<body style = \" marker-offset : auto\">
				<p>Some contents</p>
			</body>
		";

		$expected = "
			<body style = \"\">
				<p>Some contents</p>
			</body>
		";

		$object = new Design_Rules_Check_API('Outlook 2007');
		$processed = $object->Process($html, true);

		$this->assertEquals($this->_normalizeSpace($expected), $this->_normalizeSpace($processed));
	}

	public function test_Remove_InlineStyle_MaxHeight()
	{
		$html = "
			<body style=\"max-height:1px; somedummyproperty:1px;\">
				<p>Some contents</p>
			</body>
		";

		$expected = "
			<body style=\"; somedummyproperty:1px;\">
				<p>Some contents</p>
			</body>
		";

		$object = new Design_Rules_Check_API('Outlook 2007');
		$processed = $object->Process($html, true);

		$this->assertEquals($this->_normalizeSpace($expected), $this->_normalizeSpace($processed));
	}

	public function test_Remove_InlineStyle_MaxHeight_2()
	{
		$html = "
			<body style = \" max-height  :  1px\">
				<p>Some contents</p>
			</body>
		";

		$expected = "
			<body style = \"\">
				<p>Some contents</p>
			</body>
		";

		$object = new Design_Rules_Check_API('Outlook 2007');
		$processed = $object->Process($html, true);

		$this->assertEquals($this->_normalizeSpace($expected), $this->_normalizeSpace($processed));
	}

	public function test_Remove_InlineStyle_MaxWidth()
	{
		$html = "
			<body style=\"max-width:1px; somedummyproperty:1px;\">
				<p>Some contents</p>
			</body>
		";

		$expected = "
			<body style=\"; somedummyproperty:1px;\">
				<p>Some contents</p>
			</body>
		";

		$object = new Design_Rules_Check_API('Outlook 2007');
		$processed = $object->Process($html, true);

		$this->assertEquals($this->_normalizeSpace($expected), $this->_normalizeSpace($processed));
	}

	public function test_Remove_InlineStyle_MaxWidth_2()
	{
		$html = "
			<body style = \" max-width  :  1px\">
				<p>Some contents</p>
			</body>
		";

		$expected = "
			<body style = \"\">
				<p>Some contents</p>
			</body>
		";

		$object = new Design_Rules_Check_API('Outlook 2007');
		$processed = $object->Process($html, true);

		$this->assertEquals($this->_normalizeSpace($expected), $this->_normalizeSpace($processed));
	}

	public function test_Remove_InlineStyle_MinHeight()
	{
		$html = "
			<body style=\"min-height:1px; somedummyproperty:1px;\">
				<p>Some contents</p>
			</body>
		";

		$expected = "
			<body style=\"; somedummyproperty:1px;\">
				<p>Some contents</p>
			</body>
		";

		$object = new Design_Rules_Check_API('Outlook 2007');
		$processed = $object->Process($html, true);

		$this->assertEquals($this->_normalizeSpace($expected), $this->_normalizeSpace($processed));
	}

	public function test_Remove_InlineStyle_MinHeight_2()
	{
		$html = "
			<body style = \" min-height  :  1px\">
				<p>Some contents</p>
			</body>
		";

		$expected = "
			<body style = \"\">
				<p>Some contents</p>
			</body>
		";

		$object = new Design_Rules_Check_API('Outlook 2007');
		$processed = $object->Process($html, true);

		$this->assertEquals($this->_normalizeSpace($expected), $this->_normalizeSpace($processed));
	}

	public function test_Remove_InlineStyle_MinWidth()
	{
		$html = "
			<body style=\"min-width:1px; somedummyproperty:1px;\">
				<p>Some contents</p>
			</body>
		";

		$expected = "
			<body style=\"; somedummyproperty:1px;\">
				<p>Some contents</p>
			</body>
		";

		$object = new Design_Rules_Check_API('Outlook 2007');
		$processed = $object->Process($html, true);

		$this->assertEquals($this->_normalizeSpace($expected), $this->_normalizeSpace($processed));
	}

	public function test_Remove_InlineStyle_MinWidth_2()
	{
		$html = "
			<body style = \" min-width  :  1px\">
				<p>Some contents</p>
			</body>
		";

		$expected = "
			<body style = \"\">
				<p>Some contents</p>
			</body>
		";

		$object = new Design_Rules_Check_API('Outlook 2007');
		$processed = $object->Process($html, true);

		$this->assertEquals($this->_normalizeSpace($expected), $this->_normalizeSpace($processed));
	}

	public function test_Remove_InlineStyle_Combined_MixMaxWidth()
	{
		$html = "
			<body style=\"min-width:1px; max-width: 100px; somedummyproperty:1px;\">
				<p>Some contents</p>
			</body>
		";

		$expected = "
			<body style=\";; somedummyproperty:1px;\">
				<p>Some contents</p>
			</body>
		";

		$object = new Design_Rules_Check_API('Outlook 2007');
		$processed = $object->Process($html, true);

		$this->assertEquals($this->_normalizeSpace($expected), $this->_normalizeSpace($processed));
	}

	public function test_Remove_InlineStyle_Combined_MixMaxHeight()
	{
		$html = "
			<body style=\"min-height:1px; max-height: 100px; somedummyproperty:1px;\">
				<p>Some contents</p>
			</body>
		";

		$expected = "
			<body style=\";; somedummyproperty:1px;\">
				<p>Some contents</p>
			</body>
		";

		$object = new Design_Rules_Check_API('Outlook 2007');
		$processed = $object->Process($html, true);

		$this->assertEquals($this->_normalizeSpace($expected), $this->_normalizeSpace($processed));
	}

	public function test_Remove_InlineStyle_Combined_MixMaxHeightWidth()
	{
		$html = "
			<body style=\"min-height:1px; max-height: 100px; somedummyproperty:1px;min-width:1px; max-width: 100px \">
				<p>Some contents</p>
			</body>
		";

		$expected = "
			<body style=\";; somedummyproperty:1px;;\">
				<p>Some contents</p>
			</body>
		";

		$object = new Design_Rules_Check_API('Outlook 2007');
		$processed = $object->Process($html, true);

		$this->assertEquals($this->_normalizeSpace($expected), $this->_normalizeSpace($processed));
	}

	public function test_Remove_InlineStyle_Orphans()
	{
		$html = "
			<body style=\" orphans: inherit; somedummyproperty:1px; \">
				<p>Some contents</p>
			</body>
		";

		$expected = "
			<body style=\"; somedummyproperty:1px; \">
				<p>Some contents</p>
			</body>
		";

		$object = new Design_Rules_Check_API('Outlook 2007');
		$processed = $object->Process($html, true);

		$this->assertEquals($this->_normalizeSpace($expected), $this->_normalizeSpace($processed));
	}

	public function test_Remove_InlineStyle_Orphans_2()
	{
		$html = "
			<body style =\" orphans: inherit  \">
				<p>Some contents</p>
			</body>
		";

		$expected = "
			<body style =\"\">
				<p>Some contents</p>
			</body>
		";

		$object = new Design_Rules_Check_API('Outlook 2007');
		$processed = $object->Process($html, true);

		$this->assertEquals($this->_normalizeSpace($expected), $this->_normalizeSpace($processed));
	}

	public function test_Remove_InlineStyle_Outline()
	{
		$html = "
			<body style=\" outline: Aqua dashed medium; somedummyproperty:1px; \">
				<p>Some contents</p>
			</body>
		";

		$expected = "
			<body style=\"; somedummyproperty:1px; \">
				<p>Some contents</p>
			</body>
		";

		$object = new Design_Rules_Check_API('Outlook 2007');
		$processed = $object->Process($html, true);

		$this->assertEquals($this->_normalizeSpace($expected), $this->_normalizeSpace($processed));
	}

	public function test_Remove_InlineStyle_Outline_2()
	{
		$html = "
			<body style =\" outline  : Aqua dashed medium  \">
				<p>Some contents</p>
			</body>
		";

		$expected = "
			<body style =\"\">
				<p>Some contents</p>
			</body>
		";

		$object = new Design_Rules_Check_API('Outlook 2007');
		$processed = $object->Process($html, true);

		$this->assertEquals($this->_normalizeSpace($expected), $this->_normalizeSpace($processed));
	}

	public function test_Remove_InlineStyle_OutlineColor()
	{
		$html = "
			<body style=\" outline-color: Aqua; somedummyproperty:1px; \">
				<p>Some contents</p>
			</body>
		";

		$expected = "
			<body style=\"; somedummyproperty:1px; \">
				<p>Some contents</p>
			</body>
		";

		$object = new Design_Rules_Check_API('Outlook 2007');
		$processed = $object->Process($html, true);

		$this->assertEquals($this->_normalizeSpace($expected), $this->_normalizeSpace($processed));
	}

	public function test_Remove_InlineStyle_OutlineColor_2()
	{
		$html = "
			<body style =\" outline-color: Aqua \">
				<p>Some contents</p>
			</body>
		";

		$expected = "
			<body style =\"\">
				<p>Some contents</p>
			</body>
		";

		$object = new Design_Rules_Check_API('Outlook 2007');
		$processed = $object->Process($html, true);

		$this->assertEquals($this->_normalizeSpace($expected), $this->_normalizeSpace($processed));
	}

	public function test_Remove_InlineStyle_OutlineStyle()
	{
		$html = "
			<body style=\" outline-style: dashed; somedummyproperty:1px; \">
				<p>Some contents</p>
			</body>
		";

		$expected = "
			<body style=\"; somedummyproperty:1px; \">
				<p>Some contents</p>
			</body>
		";

		$object = new Design_Rules_Check_API('Outlook 2007');
		$processed = $object->Process($html, true);

		$this->assertEquals($this->_normalizeSpace($expected), $this->_normalizeSpace($processed));
	}

	public function test_Remove_InlineStyle_OutlineStyle_2()
	{
		$html = "
			<body style =\" outline-style: dashed \">
				<p>Some contents</p>
			</body>
		";

		$expected = "
			<body style =\"\">
				<p>Some contents</p>
			</body>
		";

		$object = new Design_Rules_Check_API('Outlook 2007');
		$processed = $object->Process($html, true);

		$this->assertEquals($this->_normalizeSpace($expected), $this->_normalizeSpace($processed));
	}

	public function test_Remove_InlineStyle_OutlineWidth()
	{
		$html = "
			<body style=\" outline-width: 2px; somedummyproperty:1px; \">
				<p>Some contents</p>
			</body>
		";

		$expected = "
			<body style=\"; somedummyproperty:1px; \">
				<p>Some contents</p>
			</body>
		";

		$object = new Design_Rules_Check_API('Outlook 2007');
		$processed = $object->Process($html, true);

		$this->assertEquals($this->_normalizeSpace($expected), $this->_normalizeSpace($processed));
	}

	public function test_Remove_InlineStyle_OutlineWidth_2()
	{
		$html = "
			<body style =\" outline-width : 2px\">
				<p>Some contents</p>
			</body>
		";

		$expected = "
			<body style =\"\">
				<p>Some contents</p>
			</body>
		";

		$object = new Design_Rules_Check_API('Outlook 2007');
		$processed = $object->Process($html, true);

		$this->assertEquals($this->_normalizeSpace($expected), $this->_normalizeSpace($processed));
	}

	public function test_Remove_InlineStyle_Combined_Outlines()
	{
		$html = "
			<body style=\" outline-width: 2px; outline-style: solid; somedummyproperty:1px; outline-color: red \">
				<p>Some contents</p>
			</body>
		";

		$expected = "
			<body style=\";; somedummyproperty:1px;\">
				<p>Some contents</p>
			</body>
		";

		$object = new Design_Rules_Check_API('Outlook 2007');
		$processed = $object->Process($html, true);

		$this->assertEquals($this->_normalizeSpace($expected), $this->_normalizeSpace($processed));
	}

	public function test_Remove_InlineStyle_Overflow()
	{
		$html = "
			<body style=\" overflow: visible; somedummyproperty:1px; \">
				<p>Some contents</p>
			</body>
		";

		$expected = "
			<body style=\"; somedummyproperty:1px; \">
				<p>Some contents</p>
			</body>
		";

		$object = new Design_Rules_Check_API('Outlook 2007');
		$processed = $object->Process($html, true);

		$this->assertEquals($this->_normalizeSpace($expected), $this->_normalizeSpace($processed));
	}

	public function test_Remove_InlineStyle_Overflow_2()
	{
		$html = "
			<body style =\" overflow : visible \">
				<p>Some contents</p>
			</body>
		";

		$expected = "
			<body style =\"\">
				<p>Some contents</p>
			</body>
		";

		$object = new Design_Rules_Check_API('Outlook 2007');
		$processed = $object->Process($html, true);

		$this->assertEquals($this->_normalizeSpace($expected), $this->_normalizeSpace($processed));
	}

	public function test_Remove_InlineStyle_OverflowX()
	{
		$html = "
			<body style=\" overflow-x: visible; somedummyproperty:1px; \">
				<p>Some contents</p>
			</body>
		";

		$expected = "
			<body style=\"; somedummyproperty:1px; \">
				<p>Some contents</p>
			</body>
		";

		$object = new Design_Rules_Check_API('Outlook 2007');
		$processed = $object->Process($html, true);

		$this->assertEquals($this->_normalizeSpace($expected), $this->_normalizeSpace($processed));
	}

	public function test_Remove_InlineStyle_OverflowX_2()
	{
		$html = "
			<body style =\" overflow-x : visible \">
				<p>Some contents</p>
			</body>
		";

		$expected = "
			<body style =\"\">
				<p>Some contents</p>
			</body>
		";

		$object = new Design_Rules_Check_API('Outlook 2007');
		$processed = $object->Process($html, true);

		$this->assertEquals($this->_normalizeSpace($expected), $this->_normalizeSpace($processed));
	}

	public function test_Remove_InlineStyle_OverflowY()
	{
		$html = "
			<body style=\" overflow-y: visible; somedummyproperty:1px; \">
				<p>Some contents</p>
			</body>
		";

		$expected = "
			<body style=\"; somedummyproperty:1px; \">
				<p>Some contents</p>
			</body>
		";

		$object = new Design_Rules_Check_API('Outlook 2007');
		$processed = $object->Process($html, true);

		$this->assertEquals($this->_normalizeSpace($expected), $this->_normalizeSpace($processed));
	}

	public function test_Remove_InlineStyle_OverflowY_2()
	{
		$html = "
			<body style =\" overflow-y : visible \">
				<p>Some contents</p>
			</body>
		";

		$expected = "
			<body style =\"\">
				<p>Some contents</p>
			</body>
		";

		$object = new Design_Rules_Check_API('Outlook 2007');
		$processed = $object->Process($html, true);

		$this->assertEquals($this->_normalizeSpace($expected), $this->_normalizeSpace($processed));
	}

	public function test_Remove_InlineStyle_Combined_Overflows()
	{
		$html = "
			<body style=\" overflow-x: hidden; propother: none; overflow-y: visible; somedummyproperty:1px; \">
				<p>Some contents</p>
			</body>
		";

		$expected = "
			<body style=\"; propother: none;; somedummyproperty:1px; \">
				<p>Some contents</p>
			</body>
		";

		$object = new Design_Rules_Check_API('Outlook 2007');
		$processed = $object->Process($html, true);

		$this->assertEquals($this->_normalizeSpace($expected), $this->_normalizeSpace($processed));
	}

	public function test_Remove_InlineStyle_Pause()
	{
		$html = "
			<body style=\" pause: 10%; somedummyproperty:1px; \">
				<p>Some contents</p>
			</body>
		";

		$expected = "
			<body style=\"; somedummyproperty:1px; \">
				<p>Some contents</p>
			</body>
		";

		$object = new Design_Rules_Check_API('Outlook 2007');
		$processed = $object->Process($html, true);

		$this->assertEquals($this->_normalizeSpace($expected), $this->_normalizeSpace($processed));
	}

	public function test_Remove_InlineStyle_Pause_2()
	{
		$html = "
			<body style =\" pause : 10% \">
				<p>Some contents</p>
			</body>
		";

		$expected = "
			<body style =\"\">
				<p>Some contents</p>
			</body>
		";

		$object = new Design_Rules_Check_API('Outlook 2007');
		$processed = $object->Process($html, true);

		$this->assertEquals($this->_normalizeSpace($expected), $this->_normalizeSpace($processed));
	}

	public function test_Remove_InlineStyle_PauseAfter()
	{
		$html = "
			<body style=\" pause-after: 10%; somedummyproperty:1px; \">
				<p>Some contents</p>
			</body>
		";

		$expected = "
			<body style=\"; somedummyproperty:1px; \">
				<p>Some contents</p>
			</body>
		";

		$object = new Design_Rules_Check_API('Outlook 2007');
		$processed = $object->Process($html, true);

		$this->assertEquals($this->_normalizeSpace($expected), $this->_normalizeSpace($processed));
	}

	public function test_Remove_InlineStyle_PauseAfter_2()
	{
		$html = "
			<body style =\" pause-after : 10% \">
				<p>Some contents</p>
			</body>
		";

		$expected = "
			<body style =\"\">
				<p>Some contents</p>
			</body>
		";

		$object = new Design_Rules_Check_API('Outlook 2007');
		$processed = $object->Process($html, true);

		$this->assertEquals($this->_normalizeSpace($expected), $this->_normalizeSpace($processed));
	}

	public function test_Remove_InlineStyle_PauseBefore()
	{
		$html = "
			<body style=\" pause-before: 10%; somedummyproperty:1px; \">
				<p>Some contents</p>
			</body>
		";

		$expected = "
			<body style=\"; somedummyproperty:1px; \">
				<p>Some contents</p>
			</body>
		";

		$object = new Design_Rules_Check_API('Outlook 2007');
		$processed = $object->Process($html, true);

		$this->assertEquals($this->_normalizeSpace($expected), $this->_normalizeSpace($processed));
	}

	public function test_Remove_InlineStyle_PauseBefore_2()
	{
		$html = "
			<body style =\" pause-before : 10% \">
				<p>Some contents</p>
			</body>
		";

		$expected = "
			<body style =\"\">
				<p>Some contents</p>
			</body>
		";

		$object = new Design_Rules_Check_API('Outlook 2007');
		$processed = $object->Process($html, true);

		$this->assertEquals($this->_normalizeSpace($expected), $this->_normalizeSpace($processed));
	}

	public function test_Remove_InlineStyle_Combined_Pauses()
	{
		$html = "
			<body style=\" pause-before: 5%; pause-after: 10%; somedummyproperty:1px; \">
				<p>Some contents</p>
			</body>
		";

		$expected = "
			<body style=\";; somedummyproperty:1px; \">
				<p>Some contents</p>
			</body>
		";

		$object = new Design_Rules_Check_API('Outlook 2007');
		$processed = $object->Process($html, true);

		$this->assertEquals($this->_normalizeSpace($expected), $this->_normalizeSpace($processed));
	}

	public function test_Remove_InlineStyle_Pitch()
	{
		$html = "
			<body style=\" pitch: x-low; somedummyproperty:1px; \">
				<p>Some contents</p>
			</body>
		";

		$expected = "
			<body style=\"; somedummyproperty:1px; \">
				<p>Some contents</p>
			</body>
		";

		$object = new Design_Rules_Check_API('Outlook 2007');
		$processed = $object->Process($html, true);

		$this->assertEquals($this->_normalizeSpace($expected), $this->_normalizeSpace($processed));
	}

	public function test_Remove_InlineStyle_Pitch_2()
	{
		$html = "
			<body style =\" pitch :x-low \">
				<p>Some contents</p>
			</body>
		";

		$expected = "
			<body style =\"\">
				<p>Some contents</p>
			</body>
		";

		$object = new Design_Rules_Check_API('Outlook 2007');
		$processed = $object->Process($html, true);

		$this->assertEquals($this->_normalizeSpace($expected), $this->_normalizeSpace($processed));
	}

	public function test_Remove_InlineStyle_PitchRange()
	{
		$html = "
			<body style=\" pitch-range: inherit; somedummyproperty:1px; \">
				<p>Some contents</p>
			</body>
		";

		$expected = "
			<body style=\"; somedummyproperty:1px; \">
				<p>Some contents</p>
			</body>
		";

		$object = new Design_Rules_Check_API('Outlook 2007');
		$processed = $object->Process($html, true);

		$this->assertEquals($this->_normalizeSpace($expected), $this->_normalizeSpace($processed));
	}

	public function test_Remove_InlineStyle_PitchRange_2()
	{
		$html = "
			<body style =\" pitch-range :inherit \">
				<p>Some contents</p>
			</body>
		";

		$expected = "
			<body style =\"\">
				<p>Some contents</p>
			</body>
		";

		$object = new Design_Rules_Check_API('Outlook 2007');
		$processed = $object->Process($html, true);

		$this->assertEquals($this->_normalizeSpace($expected), $this->_normalizeSpace($processed));
	}

	public function test_Remove_InlineStyle_Position()
	{
		$html = "
			<body style=\" position: top; somedummyproperty:1px; \">
				<p>Some contents</p>
			</body>
		";

		$expected = "
			<body style=\"; somedummyproperty:1px; \">
				<p>Some contents</p>
			</body>
		";

		$object = new Design_Rules_Check_API('Outlook 2007');
		$processed = $object->Process($html, true);

		$this->assertEquals($this->_normalizeSpace($expected), $this->_normalizeSpace($processed));
	}

	public function test_Remove_InlineStyle_Position_2()
	{
		$html = "
			<body style =\" position :bottom \">
				<p>Some contents</p>
			</body>
		";

		$expected = "
			<body style =\"\">
				<p>Some contents</p>
			</body>
		";

		$object = new Design_Rules_Check_API('Outlook 2007');
		$processed = $object->Process($html, true);

		$this->assertEquals($this->_normalizeSpace($expected), $this->_normalizeSpace($processed));
	}

	public function test_Remove_InlineStyle_Position_3()
	{
		$html = "
			<body style =\" someproperty-position :inherit \">
				<p>Some contents</p>
			</body>
		";

		$expected = "
			<body style =\" someproperty-position :inherit \">
				<p>Some contents</p>
			</body>
		";

		$object = new Design_Rules_Check_API('Outlook 2007');
		$processed = $object->Process($html, true);

		$this->assertEquals($this->_normalizeSpace($expected), $this->_normalizeSpace($processed));
	}

	public function test_Remove_InlineStyle_Quotes()
	{
		$html = "
			<body style=\" quotes: none; somedummyproperty:1px; \">
				<p>Some contents</p>
			</body>
		";

		$expected = "
			<body style=\"; somedummyproperty:1px; \">
				<p>Some contents</p>
			</body>
		";

		$object = new Design_Rules_Check_API('Outlook 2007');
		$processed = $object->Process($html, true);

		$this->assertEquals($this->_normalizeSpace($expected), $this->_normalizeSpace($processed));
	}

	public function test_Remove_InlineStyle_Quotes_2()
	{
		$html = "
			<body style =\" quotes : none \">
				<p>Some contents</p>
			</body>
		";

		$expected = "
			<body style =\"\">
				<p>Some contents</p>
			</body>
		";

		$object = new Design_Rules_Check_API('Outlook 2007');
		$processed = $object->Process($html, true);

		$this->assertEquals($this->_normalizeSpace($expected), $this->_normalizeSpace($processed));
	}

	public function test_Remove_InlineStyle_Quotes_3()
	{
		$html = "
			<body style =\" someproperty-quotes :inherit \">
				<p>Some contents</p>
			</body>
		";

		$expected = "
			<body style =\" someproperty-quotes :inherit \">
				<p>Some contents</p>
			</body>
		";

		$object = new Design_Rules_Check_API('Outlook 2007');
		$processed = $object->Process($html, true);

		$this->assertEquals($this->_normalizeSpace($expected), $this->_normalizeSpace($processed));
	}

	public function test_Remove_InlineStyle_Richness()
	{
		$html = "
			<body style=\" richness: inherit; somedummyproperty:1px; \">
				<p>Some contents</p>
			</body>
		";

		$expected = "
			<body style=\"; somedummyproperty:1px; \">
				<p>Some contents</p>
			</body>
		";

		$object = new Design_Rules_Check_API('Outlook 2007');
		$processed = $object->Process($html, true);

		$this->assertEquals($this->_normalizeSpace($expected), $this->_normalizeSpace($processed));
	}

	public function test_Remove_InlineStyle_Richness_2()
	{
		$html = "
			<body style =\" richness : inherit \">
				<p>Some contents</p>
			</body>
		";

		$expected = "
			<body style =\"\">
				<p>Some contents</p>
			</body>
		";

		$object = new Design_Rules_Check_API('Outlook 2007');
		$processed = $object->Process($html, true);

		$this->assertEquals($this->_normalizeSpace($expected), $this->_normalizeSpace($processed));
	}

	public function test_Remove_InlineStyle_Richness_3()
	{
		$html = "
			<body style =\" someproperty-richness :inherit \">
				<p>Some contents</p>
			</body>
		";

		$expected = "
			<body style =\" someproperty-richness :inherit \">
				<p>Some contents</p>
			</body>
		";

		$object = new Design_Rules_Check_API('Outlook 2007');
		$processed = $object->Process($html, true);

		$this->assertEquals($this->_normalizeSpace($expected), $this->_normalizeSpace($processed));
	}

	public function test_Remove_InlineStyle_Right()
	{
		$html = "
			<body style=\" right: 5px; somedummyproperty:1px; \">
				<p>Some contents</p>
			</body>
		";

		$expected = "
			<body style=\"; somedummyproperty:1px; \">
				<p>Some contents</p>
			</body>
		";

		$object = new Design_Rules_Check_API('Outlook 2007');
		$processed = $object->Process($html, true);

		$this->assertEquals($this->_normalizeSpace($expected), $this->_normalizeSpace($processed));
	}

	public function test_Remove_InlineStyle_Right_2()
	{
		$html = "
			<body style =\" right: 5px \">
				<p>Some contents</p>
			</body>
		";

		$expected = "
			<body style =\"\">
				<p>Some contents</p>
			</body>
		";

		$object = new Design_Rules_Check_API('Outlook 2007');
		$processed = $object->Process($html, true);

		$this->assertEquals($this->_normalizeSpace($expected), $this->_normalizeSpace($processed));
	}

	public function test_Remove_InlineStyle_Right_3()
	{
		$html = "
			<body style =\" someproperty-rright :2px \">
				<p>Some contents</p>
			</body>
		";

		$expected = "
			<body style =\" someproperty-rright :2px \">
				<p>Some contents</p>
			</body>
		";

		$object = new Design_Rules_Check_API('Outlook 2007');
		$processed = $object->Process($html, true);

		$this->assertEquals($this->_normalizeSpace($expected), $this->_normalizeSpace($processed));
	}

	public function test_Remove_InlineStyle_Speak()
	{
		$html = "
			<body style=\" speak: normal; somedummyproperty:1px; \">
				<p>Some contents</p>
			</body>
		";

		$expected = "
			<body style=\"; somedummyproperty:1px; \">
				<p>Some contents</p>
			</body>
		";

		$object = new Design_Rules_Check_API('Outlook 2007');
		$processed = $object->Process($html, true);

		$this->assertEquals($this->_normalizeSpace($expected), $this->_normalizeSpace($processed));
	}

	public function test_Remove_InlineStyle_Speak_2()
	{
		$html = "
			<body style =\" speak : normal \">
				<p>Some contents</p>
			</body>
		";

		$expected = "
			<body style =\"\">
				<p>Some contents</p>
			</body>
		";

		$object = new Design_Rules_Check_API('Outlook 2007');
		$processed = $object->Process($html, true);

		$this->assertEquals($this->_normalizeSpace($expected), $this->_normalizeSpace($processed));
	}

	public function test_Remove_InlineStyle_SpeakHeader()
	{
		$html = "
			<body style=\" speak-header: once; somedummyproperty:1px; \">
				<p>Some contents</p>
			</body>
		";

		$expected = "
			<body style=\"; somedummyproperty:1px; \">
				<p>Some contents</p>
			</body>
		";

		$object = new Design_Rules_Check_API('Outlook 2007');
		$processed = $object->Process($html, true);

		$this->assertEquals($this->_normalizeSpace($expected), $this->_normalizeSpace($processed));
	}

	public function test_Remove_InlineStyle_SpeakHeader_2()
	{
		$html = "
			<body style =\" speak-header : always \">
				<p>Some contents</p>
			</body>
		";

		$expected = "
			<body style =\"\">
				<p>Some contents</p>
			</body>
		";

		$object = new Design_Rules_Check_API('Outlook 2007');
		$processed = $object->Process($html, true);

		$this->assertEquals($this->_normalizeSpace($expected), $this->_normalizeSpace($processed));
	}

	public function test_Remove_InlineStyle_SpeakNumeral()
	{
		$html = "
			<body style=\" speak-numeral: continuous; somedummyproperty:1px; \">
				<p>Some contents</p>
			</body>
		";

		$expected = "
			<body style=\"; somedummyproperty:1px; \">
				<p>Some contents</p>
			</body>
		";

		$object = new Design_Rules_Check_API('Outlook 2007');
		$processed = $object->Process($html, true);

		$this->assertEquals($this->_normalizeSpace($expected), $this->_normalizeSpace($processed));
	}

	public function test_Remove_InlineStyle_SpeakNumeral_2()
	{
		$html = "
			<body style =\" speak-numeral : continuous \">
				<p>Some contents</p>
			</body>
		";

		$expected = "
			<body style =\"\">
				<p>Some contents</p>
			</body>
		";

		$object = new Design_Rules_Check_API('Outlook 2007');
		$processed = $object->Process($html, true);

		$this->assertEquals($this->_normalizeSpace($expected), $this->_normalizeSpace($processed));
	}

	public function test_Remove_InlineStyle_SpeakPunctuation()
	{
		$html = "
			<body style=\" speak-punctuation: code; somedummyproperty:1px; \">
				<p>Some contents</p>
			</body>
		";

		$expected = "
			<body style=\"; somedummyproperty:1px; \">
				<p>Some contents</p>
			</body>
		";

		$object = new Design_Rules_Check_API('Outlook 2007');
		$processed = $object->Process($html, true);

		$this->assertEquals($this->_normalizeSpace($expected), $this->_normalizeSpace($processed));
	}

	public function test_Remove_InlineStyle_SpeakPunctuation_2()
	{
		$html = "
			<body style =\" speak-punctuation : code \">
				<p>Some contents</p>
			</body>
		";

		$expected = "
			<body style =\"\">
				<p>Some contents</p>
			</body>
		";

		$object = new Design_Rules_Check_API('Outlook 2007');
		$processed = $object->Process($html, true);

		$this->assertEquals($this->_normalizeSpace($expected), $this->_normalizeSpace($processed));
	}

	public function test_Remove_InlineStyle_Combined_Speaks()
	{
		$html = "
			<body style=\" speak:spell-out; someprop2: none  ;   speak-punctuation: code; somedummyproperty:1px; speak-header:always; speak-numeral: digit \">
				<p>Some contents</p>
			</body>
		";

		$expected = "
			<body style=\"; someprop2: none  ;; somedummyproperty:1px;;\">
				<p>Some contents</p>
			</body>
		";

		$object = new Design_Rules_Check_API('Outlook 2007');
		$processed = $object->Process($html, true);

		$this->assertEquals($this->_normalizeSpace($expected), $this->_normalizeSpace($processed));
	}

	public function test_Remove_InlineStyle_SpeechRate()
	{
		$html = "
			<body style=\" speech-rate: x-fast; somedummyproperty:1px; \">
				<p>Some contents</p>
			</body>
		";

		$expected = "
			<body style=\"; somedummyproperty:1px; \">
				<p>Some contents</p>
			</body>
		";

		$object = new Design_Rules_Check_API('Outlook 2007');
		$processed = $object->Process($html, true);

		$this->assertEquals($this->_normalizeSpace($expected), $this->_normalizeSpace($processed));
	}

	public function test_Remove_InlineStyle_SpeechRate_2()
	{
		$html = "
			<body style =\" speech-rate : x-fast \">
				<p>Some contents</p>
			</body>
		";

		$expected = "
			<body style =\"\">
				<p>Some contents</p>
			</body>
		";

		$object = new Design_Rules_Check_API('Outlook 2007');
		$processed = $object->Process($html, true);

		$this->assertEquals($this->_normalizeSpace($expected), $this->_normalizeSpace($processed));
	}

	public function test_Remove_InlineStyle_Stress()
	{
		$html = "
			<body style=\" stress: very; somedummyproperty:1px; \">
				<p>Some contents</p>
			</body>
		";

		$expected = "
			<body style=\"; somedummyproperty:1px; \">
				<p>Some contents</p>
			</body>
		";

		$object = new Design_Rules_Check_API('Outlook 2007');
		$processed = $object->Process($html, true);

		$this->assertEquals($this->_normalizeSpace($expected), $this->_normalizeSpace($processed));
	}

	public function test_Remove_InlineStyle_Stress_2()
	{
		$html = "
			<body style =\" stress : very \">
				<p>Some contents</p>
			</body>
		";

		$expected = "
			<body style =\"\">
				<p>Some contents</p>
			</body>
		";

		$object = new Design_Rules_Check_API('Outlook 2007');
		$processed = $object->Process($html, true);

		$this->assertEquals($this->_normalizeSpace($expected), $this->_normalizeSpace($processed));
	}

	public function test_Remove_InlineStyle_TableLayout()
	{
		$html = "
			<body style=\" table-layout: fixed; somedummyproperty:1px; \">
				<p>Some contents</p>
			</body>
		";

		$expected = "
			<body style=\"; somedummyproperty:1px; \">
				<p>Some contents</p>
			</body>
		";

		$object = new Design_Rules_Check_API('Outlook 2007');
		$processed = $object->Process($html, true);

		$this->assertEquals($this->_normalizeSpace($expected), $this->_normalizeSpace($processed));
	}

	public function test_Remove_InlineStyle_TableLayout_2()
	{
		$html = "
			<body style =\" table-layout :fixed \">
				<p>Some contents</p>
			</body>
		";

		$expected = "
			<body style =\"\">
				<p>Some contents</p>
			</body>
		";

		$object = new Design_Rules_Check_API('Outlook 2007');
		$processed = $object->Process($html, true);

		$this->assertEquals($this->_normalizeSpace($expected), $this->_normalizeSpace($processed));
	}

	public function test_Remove_InlineStyle_TextShadow()
	{
		$html = "
			<body style=\" text-shadow: aqua; somedummyproperty:1px; \">
				<p>Some contents</p>
			</body>
		";

		$expected = "
			<body style=\"; somedummyproperty:1px; \">
				<p>Some contents</p>
			</body>
		";

		$object = new Design_Rules_Check_API('Outlook 2007');
		$processed = $object->Process($html, true);

		$this->assertEquals($this->_normalizeSpace($expected), $this->_normalizeSpace($processed));
	}

	public function test_Remove_InlineStyle_TextShadow_2()
	{
		$html = "
			<body style =\" text-shadow : aqua \">
				<p>Some contents</p>
			</body>
		";

		$expected = "
			<body style =\"\">
				<p>Some contents</p>
			</body>
		";

		$object = new Design_Rules_Check_API('Outlook 2007');
		$processed = $object->Process($html, true);

		$this->assertEquals($this->_normalizeSpace($expected), $this->_normalizeSpace($processed));
	}

	public function test_Remove_InlineStyle_TextTransform()
	{
		$html = "
			<body style=\" text-transform: capitalized; somedummyproperty:1px; \">
				<p>Some contents</p>
			</body>
		";

		$expected = "
			<body style=\"; somedummyproperty:1px; \">
				<p>Some contents</p>
			</body>
		";

		$object = new Design_Rules_Check_API('Outlook 2007');
		$processed = $object->Process($html, true);

		$this->assertEquals($this->_normalizeSpace($expected), $this->_normalizeSpace($processed));
	}

	public function test_Remove_InlineStyle_TextTransform_2()
	{
		$html = "
			<body style =\" text-transform :  capitalized \">
				<p>Some contents</p>
			</body>
		";

		$expected = "
			<body style =\"\">
				<p>Some contents</p>
			</body>
		";

		$object = new Design_Rules_Check_API('Outlook 2007');
		$processed = $object->Process($html, true);

		$this->assertEquals($this->_normalizeSpace($expected), $this->_normalizeSpace($processed));
	}

	public function test_Remove_InlineStyle_Top()
	{
		$html = "
			<body style=\" top: 2px; somedummyproperty:1px; \">
				<p>Some contents</p>
			</body>
		";

		$expected = "
			<body style=\"; somedummyproperty:1px; \">
				<p>Some contents</p>
			</body>
		";

		$object = new Design_Rules_Check_API('Outlook 2007');
		$processed = $object->Process($html, true);

		$this->assertEquals($this->_normalizeSpace($expected), $this->_normalizeSpace($processed));
	}

	public function test_Remove_InlineStyle_Top_2()
	{
		$html = "
			<body style =\" top: 2px \">
				<p>Some contents</p>
			</body>
		";

		$expected = "
			<body style =\"\">
				<p>Some contents</p>
			</body>
		";

		$object = new Design_Rules_Check_API('Outlook 2007');
		$processed = $object->Process($html, true);

		$this->assertEquals($this->_normalizeSpace($expected), $this->_normalizeSpace($processed));
	}

	public function test_Remove_InlineStyle_Top_3()
	{
		$html = "
			<body style =\" someotherprop-top: 2px \">
				<p>Some contents</p>
			</body>
		";

		$expected = "
			<body style =\" someotherprop-top: 2px \">
				<p>Some contents</p>
			</body>
		";

		$object = new Design_Rules_Check_API('Outlook 2007');
		$processed = $object->Process($html, true);

		$this->assertEquals($this->_normalizeSpace($expected), $this->_normalizeSpace($processed));
	}

	public function test_Remove_InlineStyle_UnicodeBidi()
	{
		$html = "
			<body style=\" unicode-bidi: embed; somedummyproperty:1px; \">
				<p>Some contents</p>
			</body>
		";

		$expected = "
			<body style=\"; somedummyproperty:1px; \">
				<p>Some contents</p>
			</body>
		";

		$object = new Design_Rules_Check_API('Outlook 2007');
		$processed = $object->Process($html, true);

		$this->assertEquals($this->_normalizeSpace($expected), $this->_normalizeSpace($processed));
	}

	public function test_Remove_InlineStyle_UnicodeBidi_2()
	{
		$html = "
			<body style =\" unicode-bidi :embed\">
				<p>Some contents</p>
			</body>
		";

		$expected = "
			<body style =\"\">
				<p>Some contents</p>
			</body>
		";

		$object = new Design_Rules_Check_API('Outlook 2007');
		$processed = $object->Process($html, true);

		$this->assertEquals($this->_normalizeSpace($expected), $this->_normalizeSpace($processed));
	}

	public function test_Remove_InlineStyle_Visibility()
	{
		$html = "
			<body style=\" visibility: hidden; somedummyproperty:1px; \">
				<p>Some contents</p>
			</body>
		";

		$expected = "
			<body style=\"; somedummyproperty:1px; \">
				<p>Some contents</p>
			</body>
		";

		$object = new Design_Rules_Check_API('Outlook 2007');
		$processed = $object->Process($html, true);

		$this->assertEquals($this->_normalizeSpace($expected), $this->_normalizeSpace($processed));
	}

	public function test_Remove_InlineStyle_Visibility_2()
	{
		$html = "
			<body style =\" visibility: hidden\">
				<p>Some contents</p>
			</body>
		";

		$expected = "
			<body style =\"\">
				<p>Some contents</p>
			</body>
		";

		$object = new Design_Rules_Check_API('Outlook 2007');
		$processed = $object->Process($html, true);

		$this->assertEquals($this->_normalizeSpace($expected), $this->_normalizeSpace($processed));
	}

	public function test_Remove_InlineStyle_VoiceFamily()
	{
		$html = "
			<body style=\" voice-family: female; somedummyproperty:1px; \">
				<p>Some contents</p>
			</body>
		";

		$expected = "
			<body style=\"; somedummyproperty:1px; \">
				<p>Some contents</p>
			</body>
		";

		$object = new Design_Rules_Check_API('Outlook 2007');
		$processed = $object->Process($html, true);

		$this->assertEquals($this->_normalizeSpace($expected), $this->_normalizeSpace($processed));
	}

	public function test_Remove_InlineStyle_VoiceFamily_2()
	{
		$html = "
			<body style =\" voice-family : female\">
				<p>Some contents</p>
			</body>
		";

		$expected = "
			<body style =\"\">
				<p>Some contents</p>
			</body>
		";

		$object = new Design_Rules_Check_API('Outlook 2007');
		$processed = $object->Process($html, true);

		$this->assertEquals($this->_normalizeSpace($expected), $this->_normalizeSpace($processed));
	}

	public function test_Remove_InlineStyle_Volume()
	{
		$html = "
			<body style=\" volume: soft; somedummyproperty:1px; \">
				<p>Some contents</p>
			</body>
		";

		$expected = "
			<body style=\"; somedummyproperty:1px; \">
				<p>Some contents</p>
			</body>
		";

		$object = new Design_Rules_Check_API('Outlook 2007');
		$processed = $object->Process($html, true);

		$this->assertEquals($this->_normalizeSpace($expected), $this->_normalizeSpace($processed));
	}

	public function test_Remove_InlineStyle_Volume_2()
	{
		$html = "
			<body style =\" volume : soft\">
				<p>Some contents</p>
			</body>
		";

		$expected = "
			<body style =\"\">
				<p>Some contents</p>
			</body>
		";

		$object = new Design_Rules_Check_API('Outlook 2007');
		$processed = $object->Process($html, true);

		$this->assertEquals($this->_normalizeSpace($expected), $this->_normalizeSpace($processed));
	}

	public function test_Remove_InlineStyle_Width()
	{
		$html = "
			<body style=\" width: 3px; somedummyproperty:1px; \">
				<p>Some contents</p>
			</body>
		";

		$expected = "
			<body style=\"; somedummyproperty:1px; \">
				<p>Some contents</p>
			</body>
		";

		$object = new Design_Rules_Check_API('Outlook 2007');
		$processed = $object->Process($html, true);

		$this->assertEquals($this->_normalizeSpace($expected), $this->_normalizeSpace($processed));
	}

	public function test_Remove_InlineStyle_Width_2()
	{
		$html = "
			<body style =\" width :3px\">
				<p>Some contents</p>
			</body>
		";

		$expected = "
			<body style =\"\">
				<p>Some contents</p>
			</body>
		";

		$object = new Design_Rules_Check_API('Outlook 2007');
		$processed = $object->Process($html, true);

		$this->assertEquals($this->_normalizeSpace($expected), $this->_normalizeSpace($processed));
	}

	public function test_Remove_InlineStyle_Width_3()
	{
		$html = "
			<body style =\" someotherpropertiy-width :3px\">
				<p>Some contents</p>
			</body>
		";

		$expected = "
			<body style =\" someotherpropertiy-width :3px\">
				<p>Some contents</p>
			</body>
		";

		$object = new Design_Rules_Check_API('Outlook 2007');
		$processed = $object->Process($html, true);

		$this->assertEquals($this->_normalizeSpace($expected), $this->_normalizeSpace($processed));
	}

	public function test_Remove_InlineStyle_Windows()
	{
		$html = "
			<body style=\" windows: inherit; somedummyproperty:1px; \">
				<p>Some contents</p>
			</body>
		";

		$expected = "
			<body style=\"; somedummyproperty:1px; \">
				<p>Some contents</p>
			</body>
		";

		$object = new Design_Rules_Check_API('Outlook 2007');
		$processed = $object->Process($html, true);

		$this->assertEquals($this->_normalizeSpace($expected), $this->_normalizeSpace($processed));
	}

	public function test_Remove_InlineStyle_Windows_2()
	{
		$html = "
			<body style =\" windows :inherit\">
				<p>Some contents</p>
			</body>
		";

		$expected = "
			<body style =\"\">
				<p>Some contents</p>
			</body>
		";

		$object = new Design_Rules_Check_API('Outlook 2007');
		$processed = $object->Process($html, true);

		$this->assertEquals($this->_normalizeSpace($expected), $this->_normalizeSpace($processed));
	}

	public function test_Remove_InlineStyle_WordSpacing()
	{
		$html = "
			<body style=\" word-spacing: inherit; somedummyproperty:1px; \">
				<p>Some contents</p>
			</body>
		";

		$expected = "
			<body style=\"; somedummyproperty:1px; \">
				<p>Some contents</p>
			</body>
		";

		$object = new Design_Rules_Check_API('Outlook 2007');
		$processed = $object->Process($html, true);

		$this->assertEquals($this->_normalizeSpace($expected), $this->_normalizeSpace($processed));
	}

	public function test_Remove_InlineStyle_WordSpacing_2()
	{
		$html = "
			<body style =\" word-spacing :inherit\">
				<p>Some contents</p>
			</body>
		";

		$expected = "
			<body style =\"\">
				<p>Some contents</p>
			</body>
		";

		$object = new Design_Rules_Check_API('Outlook 2007');
		$processed = $object->Process($html, true);

		$this->assertEquals($this->_normalizeSpace($expected), $this->_normalizeSpace($processed));
	}

	public function test_Remove_InlineStyle_ZIndex()
	{
		$html = "
			<body style=\" z-index: 5; somedummyproperty:1px; \">
				<p>Some contents</p>
			</body>
		";

		$expected = "
			<body style=\"; somedummyproperty:1px; \">
				<p>Some contents</p>
			</body>
		";

		$object = new Design_Rules_Check_API('Outlook 2007');
		$processed = $object->Process($html, true);

		$this->assertEquals($this->_normalizeSpace($expected), $this->_normalizeSpace($processed));
	}

	public function test_Remove_InlineStyle_ZIndex_2()
	{
		$html = "
			<body style =\" z-index   :1000\">
				<p>Some contents</p>
			</body>
		";

		$expected = "
			<body style =\"\">
				<p>Some contents</p>
			</body>
		";

		$object = new Design_Rules_Check_API('Outlook 2007');
		$processed = $object->Process($html, true);

		$this->assertEquals($this->_normalizeSpace($expected), $this->_normalizeSpace($processed));
	}

	public function test_Remove_InlineStyle_StyleProperty_URL()
	{
		$html = "
			<body style=\" backgroundproperty: url(somebg.gif); somedummyproperty:1px; \">
				<p>Some contents</p>
			</body>
		";

		$expected = "
			<body style=\" backgroundproperty:; somedummyproperty:1px; \">
				<p>Some contents</p>
			</body>
		";

		$object = new Design_Rules_Check_API('Outlook 2007');
		$processed = $object->Process($html, true);

		$this->assertEquals($this->_normalizeSpace($expected), $this->_normalizeSpace($processed));
	}

	public function test_Remove_InlineStyle_StyleProperty_URL_2()
	{
		$html = "
			<body style =\" backgroundproperty: url('somebg.gif')\">
				<p>Some contents</p>
			</body>
		";

		$expected = "
			<body style =\" backgroundproperty:\">
				<p>Some contents</p>
			</body>
		";

		$object = new Design_Rules_Check_API('Outlook 2007');
		$processed = $object->Process($html, true);

		$this->assertEquals($this->_normalizeSpace($expected), $this->_normalizeSpace($processed));
	}

	public function test_Remove_Element_BDO()
	{
		$html = "
			<body>
				<p>Some contents</p>
				<bdo>Some Text here</bdo>
			</body>
		";

		$expected = "
			<body>
				<p>Some contents</p>
				Some Text here
			</body>
		";

		$object = new Design_Rules_Check_API('Outlook 2007');
		$processed = $object->Process($html, true);

		$this->assertEquals($this->_normalizeSpace($expected), $this->_normalizeSpace($processed));
	}

	public function test_Remove_Element_BDO_2()
	{
		$html = "
			<body>
				<p>Some contents</p>
				<bdo someotherproperty=\"100%\" style=\"someproperty: center;\">Some Text here</bdo>
			</body>
		";

		$expected = "
			<body>
				<p>Some contents</p>
				Some Text here
			</body>
		";

		$object = new Design_Rules_Check_API('Outlook 2007');
		$processed = $object->Process($html, true);

		$this->assertEquals($this->_normalizeSpace($expected), $this->_normalizeSpace($processed));
	}

	public function test_Remove_Element_BDO_3()
	{
		$html = "
			<body>
				<p>Some contents</p>
				<bdo someotherproperty=\"100%\" style=\"someproperty: center;\">Some Text here</bdo>
				<div>Some of the bdo does not compute</div>
			</body>
		";

		$expected = "
			<body>
				<p>Some contents</p>
				Some Text here
				<div>Some of the bdo does not compute</div>
			</body>
		";

		$object = new Design_Rules_Check_API('Outlook 2007');
		$processed = $object->Process($html, true);

		$this->assertEquals($this->_normalizeSpace($expected), $this->_normalizeSpace($processed));
	}

	public function test_Remove_Element_BUTTON()
	{
		$html = "
			<body>
				<p>Some contents</p>
				<button>Some button value</button>
			</body>
		";

		$expected = "
			<body>
				<p>Some contents</p>
				Some button value
			</body>
		";

		$object = new Design_Rules_Check_API('Outlook 2007');
		$processed = $object->Process($html, true);

		$this->assertEquals($this->_normalizeSpace($expected), $this->_normalizeSpace($processed));
	}

	public function test_Remove_Element_BUTTON_2()
	{
		$html = "
			<body>
				<p>Some contents</p>
				<button someproperty=\"property value\">Some button value</button>
			</body>
		";

		$expected = "
			<body>
				<p>Some contents</p>
				Some button value
			</body>
		";

		$object = new Design_Rules_Check_API('Outlook 2007');
		$processed = $object->Process($html, true);

		$this->assertEquals($this->_normalizeSpace($expected), $this->_normalizeSpace($processed));
	}

	public function test_Remove_Element_INPUT()
	{
		$html = "
			<body>
				<p>Some contents</p>
				<input somepirpop=\"fsdf\" value=\"dsdf\" />
			</body>
		";

		$expected = "
			<body>
				<p>Some contents</p>
				[ Input Area ]
			</body>
		";

		$object = new Design_Rules_Check_API('Outlook 2007');
		$processed = $object->Process($html, true);

		$this->assertEquals($this->_normalizeSpace($expected), $this->_normalizeSpace($processed));
	}

	public function test_Remove_Element_TEXTAREA()
	{
		$html = "
			<body>
				<p>Some contents</p>
				<textarea>Some button value</textarea>
			</body>
		";

		$expected = "
			<body>
				<p>Some contents</p>
				Some button value
			</body>
		";

		$object = new Design_Rules_Check_API('Outlook 2007');
		$processed = $object->Process($html, true);

		$this->assertEquals($this->_normalizeSpace($expected), $this->_normalizeSpace($processed));
	}

	public function test_Remove_Element_TEXTAREA_2()
	{
		$html = "
			<body>
				<p>Some contents</p>
				<textarea someproperty=\"property value\">Some button value</textarea>
			</body>
		";

		$expected = "
			<body>
				<p>Some contents</p>
				Some button value
			</body>
		";

		$object = new Design_Rules_Check_API('Outlook 2007');
		$processed = $object->Process($html, true);

		$this->assertEquals($this->_normalizeSpace($expected), $this->_normalizeSpace($processed));
	}

	public function test_Remove_Element_FORM()
	{
		$html = "
			<body>
				<p>Some contents</p>
				<form action=\"Something.php\">
					Some Text
				</form>
			</body>
		";

		$expected = "
			<body>
				<p>Some contents</p>
					Some Text
			</body>
		";

		$object = new Design_Rules_Check_API('Outlook 2007');
		$processed = $object->Process($html, true);

		$this->assertEquals($this->_normalizeSpace($expected), $this->_normalizeSpace($processed));
	}

	public function test_Remove_Element_FORM_2()
	{
		$html = "
			<body>
				<p>Some contents</p>
				<form action=\"Something.php\">
					<div>
						Please enter your name: <input value=\"test\" name=\"testname\" />
					</div>
				</form>
			</body>
		";

		$expected = "
			<body>
				<p>Some contents</p>
					<div>
						Please enter your name: [ Input Area ]
					</div>
			</body>
		";

		$object = new Design_Rules_Check_API('Outlook 2007');
		$processed = $object->Process($html, true);

		$this->assertEquals($this->_normalizeSpace($expected), $this->_normalizeSpace($processed));
	}

	public function test_Remove_Element_IFRAME()
	{
		$html = "
			<body>
				<p>Some contents</p>
				<iframe src=\"someotherpage.html\"></iframe>
			</body>
		";

		$expected = "
			<body>
				<p>Some contents</p>
			</body>
		";

		$object = new Design_Rules_Check_API('Outlook 2007');
		$processed = $object->Process($html, true);

		$this->assertEquals($this->_normalizeSpace($expected), $this->_normalizeSpace($processed));
	}

	public function test_Remove_Element_IFRAME_2()
	{
		$html = "
			<body>
				<p>Some contents</p>
				<iframe src=\"someotherpage.html\" />
			</body>
		";

		$expected = "
			<body>
				<p>Some contents</p>
			</body>
		";

		$object = new Design_Rules_Check_API('Outlook 2007');
		$processed = $object->Process($html, true);

		$this->assertEquals($this->_normalizeSpace($expected), $this->_normalizeSpace($processed));
	}

	public function test_Remove_Element_ISINDEX()
	{
		$html = "
			<body>
				<isindex>
				<p>Some contents</p>
			</body>
		";

		$expected = "
			<body>
				[ Input Area ]
				<p>Some contents</p>
			</body>
		";

		$object = new Design_Rules_Check_API('Outlook 2007');
		$processed = $object->Process($html, true);

		$this->assertEquals($this->_normalizeSpace($expected), $this->_normalizeSpace($processed));
	}

	public function test_Remove_Element_MENU()
	{
		$html = "
			<body>
				<p>Some contents</p>
				<menu></menu>
			</body>
		";

		$expected = "
			<body>
				<p>Some contents</p>
			</body>
		";

		$object = new Design_Rules_Check_API('Outlook 2007');
		$processed = $object->Process($html, true);

		$this->assertEquals($this->_normalizeSpace($expected), $this->_normalizeSpace($processed));
	}

	public function test_Remove_Element_MENU_2()
	{
		$html = "
			<body>
				<p>Some contents</p>
				<menu>
					Some Contents here
				</menu>
			</body>
		";

		$expected = "
			<body>
				<p>Some contents</p>
					Some Contents here
			</body>
		";

		$object = new Design_Rules_Check_API('Outlook 2007');
		$processed = $object->Process($html, true);

		$this->assertEquals($this->_normalizeSpace($expected), $this->_normalizeSpace($processed));
	}

	public function test_Remove_Element_NOFRAMES()
	{
		$html = "
			<body>
				<p>Some contents</p>
				<noframes></noframes>
			</body>
		";

		$expected = "
			<body>
				<p>Some contents</p>
			</body>
		";

		$object = new Design_Rules_Check_API('Outlook 2007');
		$processed = $object->Process($html, true);

		$this->assertEquals($this->_normalizeSpace($expected), $this->_normalizeSpace($processed));
	}

	public function test_Remove_Element_NOFRAMES_2()
	{
		$html = "
			<body>
				<p>Some contents</p>
				<noframes>
					Some Contents here
				</noframes>
			</body>
		";

		$expected = "
			<body>
				<p>Some contents</p>
			</body>
		";

		$object = new Design_Rules_Check_API('Outlook 2007');
		$processed = $object->Process($html, true);

		$this->assertEquals($this->_normalizeSpace($expected), $this->_normalizeSpace($processed));
	}

	public function test_Remove_Element_NOSCRIPT()
	{
		$html = "
			<body>
				<p>Some contents</p>
				<noscript></noscript>
			</body>
		";

		$expected = "
			<body>
				<p>Some contents</p>
			</body>
		";

		$object = new Design_Rules_Check_API('Outlook 2007');
		$processed = $object->Process($html, true);

		$this->assertEquals($this->_normalizeSpace($expected), $this->_normalizeSpace($processed));
	}

	public function test_Remove_Element_NOSCRIPT_2()
	{
		$html = "
			<body>
				<p>Some contents</p>
				<noscript>
					Some Contents here
				</noscript>
			</body>
		";

		$expected = "
			<body>
				<p>Some contents</p>
			</body>
		";

		$object = new Design_Rules_Check_API('Outlook 2007');
		$processed = $object->Process($html, true);

		$this->assertEquals($this->_normalizeSpace($expected), $this->_normalizeSpace($processed));
	}

	public function test_Remove_Element_SELECT()
	{
		$html = "
			<body>
				<p>Some contents</p>
				<select>
					<option value=\"1\">One</option>
					<option value=\"2\">Two</option>
				</select>
			</body>
		";

		$expected = "
			<body>
				<p>Some contents</p>
				[ Option ]
			</body>
		";

		$object = new Design_Rules_Check_API('Outlook 2007');
		$processed = $object->Process($html, true);

		$this->assertEquals($this->_normalizeSpace($expected), $this->_normalizeSpace($processed));
	}

	public function test_Remove_Element_SELECT_2()
	{
		$html = "
			<body>
				<p>Some contents</p>
				<form action=\"Somepage.html\">
					<select>
						<option value=\"1\">One</option>
						<option value=\"2\">Two</option>
					</select>
				</form>
			</body>
		";

		$expected = "
			<body>
				<p>Some contents</p>
				[ Option ]
			</body>
		";

		$object = new Design_Rules_Check_API('Outlook 2007');
		$processed = $object->Process($html, true);

		$this->assertEquals($this->_normalizeSpace($expected), $this->_normalizeSpace($processed));
	}

	public function test_Remove_Element_OBJECT()
	{
		$html = "
			<body>
				<p>Some contents</p>
				<OBJECT CLASSID=\"yahtzee.py\" CODETYPE=\"application/x-python\" STANDBY=\"Ready to play Yahtzee?\" TITLE=\"My Yahtzee Game\">
					Yahtzee is my <EM>favorite</EM> game!
				</OBJECT>
			</body>
		";

		$expected = "
			<body>
				<p>Some contents</p>
			</body>
		";

		$object = new Design_Rules_Check_API('Outlook 2007');
		$processed = $object->Process($html, true);

		$this->assertEquals($this->_normalizeSpace($expected), $this->_normalizeSpace($processed));
	}

	public function test_Remove_Element_APPLET()
	{
		$html = "
			<body>
				<p>Some contents</p>
				<APPLET CODE=\"MyApplet.class\" WIDTH=100 HEIGHT=100>
					<PARAM NAME=TEXT VALUE=\"Hi There\">
					<P>Hi There<P>
				</APPLET>
			</body>
		";

		$expected = "
			<body>
				<p>Some contents</p>
			</body>
		";

		$object = new Design_Rules_Check_API('Outlook 2007');
		$processed = $object->Process($html, true);

		$this->assertEquals($this->_normalizeSpace($expected), $this->_normalizeSpace($processed));
	}

	public function test_Remove_Element_PARAM()
	{
		$html = "
			<body>
				<p>Some contents</p>
				<param name=\"BorderStyle\" value=\"1\">
				<param name=\"MousePointer\" value=\"0\" />
				<param name=\"Enabled\" value=\"1\">
				<param name=\"Min\" value=\"0\" />
				<param name=\"Max\" value=\"10\" />
			</body>
		";

		$expected = "
			<body>
				<p>Some contents</p>
			</body>
		";

		$object = new Design_Rules_Check_API('Outlook 2007');
		$processed = $object->Process($html, true);

		$this->assertEquals($this->_normalizeSpace($expected), $this->_normalizeSpace($processed));
	}

	public function test_Remove_Element_Q()
	{
		$html = "
			<body>
				<p>Some contents</p>
				<q>Some contents</q>
			</body>
		";

		$expected = "
			<body>
				<p>Some contents</p>
				Some contents
			</body>
		";

		$object = new Design_Rules_Check_API('Outlook 2007');
		$processed = $object->Process($html, true);

		$this->assertEquals($this->_normalizeSpace($expected), $this->_normalizeSpace($processed));
	}

	public function test_Remove_Element_Script()
	{
		$html = "
			<body>
				<p>Some contents</p>
				<script type=\"javascript\">
					var tired = true;
					var reason = 'repetitive coding of unit test';
				</script>
			</body>
		";

		$expected = "
			<body>
				<p>Some contents</p>
			</body>
		";

		$object = new Design_Rules_Check_API('Outlook 2007');
		$processed = $object->Process($html, true);

		$this->assertEquals($this->_normalizeSpace($expected), $this->_normalizeSpace($processed));
	}

	public function test_Remove_Property_A_AccessKey()
	{
		$html = "
			<body>
				<p>Some contents</p>
				<a href=\"test.html\" accesskey=\"l\">Link... Press the 'l' key to follow this link</a>
			</body>
		";

		$expected = "
			<body>
				<p>Some contents</p>
				<a href=\"test.html\" >Link... Press the 'l' key to follow this link</a>
			</body>
		";

		$object = new Design_Rules_Check_API('Outlook 2007');
		$processed = $object->Process($html, true);

		$this->assertEquals($this->_normalizeSpace($expected), $this->_normalizeSpace($processed));
	}

	public function test_Remove_Property_A_AccessKey_2()
	{
		$html = "
			<body>
				<p>Some contents</p>
				<a href=\"test.html\" accesskey=\"l\">Link... Press the 'l' key to follow this link</a>
				accesskey = 'h'
			</body>
		";

		$expected = "
			<body>
				<p>Some contents</p>
				<a href=\"test.html\" >Link... Press the 'l' key to follow this link</a>
				accesskey = 'h'
			</body>
		";

		$object = new Design_Rules_Check_API('Outlook 2007');
		$processed = $object->Process($html, true);

		$this->assertEquals($this->_normalizeSpace($expected), $this->_normalizeSpace($processed));
	}

	public function test_Remove_Property_BODY_URL()
	{
		$html = "
			<body background=\"someurl/mkldlfk/kdsjf.html\">
				<p>Some contents</p>
			</body>
		";

		$expected = "
			<body>
				<p>Some contents</p>
			</body>
		";

		$object = new Design_Rules_Check_API('Outlook 2007');
		$processed = $object->Process($html, true);

		$this->assertEquals($this->_normalizeSpace($expected), $this->_normalizeSpace($processed));
	}

	public function test_Remove_Property_BODY_URL_2()
	{
		$html = "
			<body background=somebg.gif>
				<p>Some contents</p>
			</body>
		";

		$expected = "
			<body>
				<p>Some contents</p>
			</body>
		";

		$object = new Design_Rules_Check_API('Outlook 2007');
		$processed = $object->Process($html, true);

		$this->assertEquals($this->_normalizeSpace($expected), $this->_normalizeSpace($processed));
	}

	public function test_Remove_Property_FRAME_NORESIZE()
	{
		$html = "
			<body>
				<p>Some contents</p>
				<FRAME SRC=\"recipetitlebar.html\" NAME=TITLE NORESIZE>
			</body>
		";

		$expected = "
			<body>
				<p>Some contents</p>
				<FRAME SRC=\"recipetitlebar.html\" NAME=TITLE>
			</body>
		";

		$object = new Design_Rules_Check_API('Outlook 2007');
		$processed = $object->Process($html, true);

		$this->assertEquals($this->_normalizeSpace($expected), $this->_normalizeSpace($processed));
	}

	public function test_Remove_Property_FRAME_NORESIZE_2()
	{
		$html = "
			<body>
				<p>Some contents</p>
				<FRAME SRC=\"recipetitlebar.html\" NAME=TITLE NORESIZE=\"true\" />
			</body>
		";

		$expected = "
			<body>
				<p>Some contents</p>
				<FRAME SRC=\"recipetitlebar.html\" NAME=TITLE />
			</body>
		";

		$object = new Design_Rules_Check_API('Outlook 2007');
		$processed = $object->Process($html, true);

		$this->assertEquals($this->_normalizeSpace($expected), $this->_normalizeSpace($processed));
	}

	public function test_Remove_Property_FRAME_NORESIZE_3()
	{
		$html = "
			<body>
				<p>Some contents</p>
				<FRAME SRC=\"recipetitlebar.html\" NAME=TITLE NORESIZE=\"noresize\" />
			</body>
		";

		$expected = "
			<body>
				<p>Some contents</p>
				<FRAME SRC=\"recipetitlebar.html\" NAME=TITLE />
			</body>
		";

		$object = new Design_Rules_Check_API('Outlook 2007');
		$processed = $object->Process($html, true);

		$this->assertEquals($this->_normalizeSpace($expected), $this->_normalizeSpace($processed));
	}

	public function test_Remove_Property_FRAME_NORESIZE_4()
	{
		$html = "
			<body>
				<p>Some contents</p>
				<FRAME SRC=\"recipetitlebar.html\" NAME=TITLE NORESIZE=noresize />
			</body>
		";

		$expected = "
			<body>
				<p>Some contents</p>
				<FRAME SRC=\"recipetitlebar.html\" NAME=TITLE />
			</body>
		";

		$object = new Design_Rules_Check_API('Outlook 2007');
		$processed = $object->Process($html, true);

		$this->assertEquals($this->_normalizeSpace($expected), $this->_normalizeSpace($processed));
	}

	public function test_Remove_Property_FRAME_ZEROBORDER()
	{
		$html = "
			<body>
				<p>Some contents</p>
				<FRAME SRC=\"recipetitlebar.html\" frameborder=0>
			</body>
		";

		$expected = "
			<body>
				<p>Some contents</p>
				<FRAME SRC=\"recipetitlebar.html\">
			</body>
		";

		$object = new Design_Rules_Check_API('Outlook 2007');
		$processed = $object->Process($html, true);

		$this->assertEquals($this->_normalizeSpace($expected), $this->_normalizeSpace($processed));
	}

	public function test_Remove_Property_FRAME_ZEROBORDER_2()
	{
		$html = "
			<body>
				<p>Some contents</p>
				<FRAME SRC=\"recipetitlebar.html\" NAME=TITLE frameborder=\"0\" />
			</body>
		";

		$expected = "
			<body>
				<p>Some contents</p>
				<FRAME SRC=\"recipetitlebar.html\" NAME=TITLE />
			</body>
		";

		$object = new Design_Rules_Check_API('Outlook 2007');
		$processed = $object->Process($html, true);

		$this->assertEquals($this->_normalizeSpace($expected), $this->_normalizeSpace($processed));
	}

	public function test_Remove_Property_FRAME_ZEROBORDER_3()
	{
		$html = "
			<body>
				<p>Some contents</p>
				<FRAME SRC=\"recipetitlebar.html\" NAME=TITLE frameborder='0' />
			</body>
		";

		$expected = "
			<body>
				<p>Some contents</p>
				<FRAME SRC=\"recipetitlebar.html\" NAME=TITLE />
			</body>
		";

		$object = new Design_Rules_Check_API('Outlook 2007');
		$processed = $object->Process($html, true);

		$this->assertEquals($this->_normalizeSpace($expected), $this->_normalizeSpace($processed));
	}

	public function test_Remove_Property_FRAME_ZEROBORDER_4()
	{
		$html = "
			<body>
				<p>Some contents</p>
				<FRAME SRC=\"recipetitlebar.html\" NAME=TITLE frameborder=10 />
			</body>
		";

		$expected = "
			<body>
				<p>Some contents</p>
				<FRAME SRC=\"recipetitlebar.html\" NAME=TITLE frameborder=10 />
			</body>
		";

		$object = new Design_Rules_Check_API('Outlook 2007');
		$processed = $object->Process($html, true);

		$this->assertEquals($this->_normalizeSpace($expected), $this->_normalizeSpace($processed));
	}

	public function test_Remove_Property_FRAME_SCROLLING()
	{
		$html = "
			<body>
				<p>Some contents</p>
				<FRAME SRC=\"recipetitlebar.html\" scrolling=yes>
			</body>
		";

		$expected = "
			<body>
				<p>Some contents</p>
				<FRAME SRC=\"recipetitlebar.html\">
			</body>
		";

		$object = new Design_Rules_Check_API('Outlook 2007');
		$processed = $object->Process($html, true);

		$this->assertEquals($this->_normalizeSpace($expected), $this->_normalizeSpace($processed));
	}

	public function test_Remove_Property_FRAME_SCROLLING_2()
	{
		$html = "
			<body>
				<p>Some contents</p>
				<FRAME SRC=\"recipetitlebar.html\" NAME=TITLE  scrolling=\"no\" />
			</body>
		";

		$expected = "
			<body>
				<p>Some contents</p>
				<FRAME SRC=\"recipetitlebar.html\" NAME=TITLE />
			</body>
		";

		$object = new Design_Rules_Check_API('Outlook 2007');
		$processed = $object->Process($html, true);

		$this->assertEquals($this->_normalizeSpace($expected), $this->_normalizeSpace($processed));
	}

	public function test_Remove_Property_FRAME_SCROLLING_3()
	{
		$html = "
			<body>
				<p>Some contents</p>
				<FRAME SRC=\"recipetitlebar.html\" NAME=TITLE  scrolling=auto />
			</body>
		";

		$expected = "
			<body>
				<p>Some contents</p>
				<FRAME SRC=\"recipetitlebar.html\" NAME=TITLE />
			</body>
		";

		$object = new Design_Rules_Check_API('Outlook 2007');
		$processed = $object->Process($html, true);

		$this->assertEquals($this->_normalizeSpace($expected), $this->_normalizeSpace($processed));
	}

	public function test_Remove_Property_FRAME_SCROLLING_4()
	{
		$html = "
			<body>
				<p>Some contents</p>
				<FRAME SRC=\"recipetitlebar.html\" NAME=TITLE  scrolling />
			</body>
		";

		$expected = "
			<body>
				<p>Some contents</p>
				<FRAME SRC=\"recipetitlebar.html\" NAME=TITLE />
			</body>
		";

		$object = new Design_Rules_Check_API('Outlook 2007');
		$processed = $object->Process($html, true);

		$this->assertEquals($this->_normalizeSpace($expected), $this->_normalizeSpace($processed));
	}

	public function test_Remove_Property_FRAME_LONGDESC()
	{
		$html = "
			<body>
				<p>Some contents</p>
				<FRAME SRC=\"recipetitlebar.html\" longdesc=justanotherframe>
			</body>
		";

		$expected = "
			<body>
				<p>Some contents</p>
				<FRAME SRC=\"recipetitlebar.html\">
			</body>
		";

		$object = new Design_Rules_Check_API('Outlook 2007');
		$processed = $object->Process($html, true);

		$this->assertEquals($this->_normalizeSpace($expected), $this->_normalizeSpace($processed));
	}

	public function test_Remove_Property_FRAME_LONGDESC_2()
	{
		$html = "
			<body>
				<p>Some contents</p>
				<FRAME SRC=\"recipetitlebar.html\" NAME=TITLE longdesc=justanotherframe />
			</body>
		";

		$expected = "
			<body>
				<p>Some contents</p>
				<FRAME SRC=\"recipetitlebar.html\" NAME=TITLE />
			</body>
		";

		$object = new Design_Rules_Check_API('Outlook 2007');
		$processed = $object->Process($html, true);

		$this->assertEquals($this->_normalizeSpace($expected), $this->_normalizeSpace($processed));
	}

	public function test_Remove_Property_FRAME_LONGDESC_3()
	{
		$html = "
			<body>
				<p>Some contents</p>
				<FRAME SRC=\"recipetitlebar.html\" NAME=TITLE longdesc=\"justanotherframe\" />
			</body>
		";

		$expected = "
			<body>
				<p>Some contents</p>
				<FRAME SRC=\"recipetitlebar.html\" NAME=TITLE />
			</body>
		";

		$object = new Design_Rules_Check_API('Outlook 2007');
		$processed = $object->Process($html, true);

		$this->assertEquals($this->_normalizeSpace($expected), $this->_normalizeSpace($processed));
	}

	public function test_Remove_Property_FRAME_LONGDESC_4()
	{
		$html = "
			<body>
				<p>Some contents</p>
				<FRAME SRC=\"recipetitlebar.html\" NAME=TITLE longdesc=\"justanotherframe\">
			</body>
		";

		$expected = "
			<body>
				<p>Some contents</p>
				<FRAME SRC=\"recipetitlebar.html\" NAME=TITLE>
			</body>
		";

		$object = new Design_Rules_Check_API('Outlook 2007');
		$processed = $object->Process($html, true);

		$this->assertEquals($this->_normalizeSpace($expected), $this->_normalizeSpace($processed));
	}

	public function test_Remove_Property_IMG_LONGDESC()
	{
		$html = "
			<body>
				<p>Some contents</p>
				<img SRC=\"recipetitlebar.gif\" someotherprop=\"Image for testing\" longdesc=justanotherimage>
			</body>
		";

		$expected = "
			<body>
				<p>Some contents</p>
				<img SRC=\"recipetitlebar.gif\" someotherprop=\"Image for testing\">
			</body>
		";

		$object = new Design_Rules_Check_API('Outlook 2007');
		$processed = $object->Process($html, true);

		$this->assertEquals($this->_normalizeSpace($expected), $this->_normalizeSpace($processed));
	}

	public function test_Remove_Property_IMG_LONGDESC_2()
	{
		$html = "
			<body>
				<p>Some contents</p>
				<img SRC=\"recipetitlebar.gif\" someotherprop=\"Image for testing\" longdesc=justanotherimage />
			</body>
		";

		$expected = "
			<body>
				<p>Some contents</p>
				<img SRC=\"recipetitlebar.gif\" someotherprop=\"Image for testing\" />
			</body>
		";

		$object = new Design_Rules_Check_API('Outlook 2007');
		$processed = $object->Process($html, true);

		$this->assertEquals($this->_normalizeSpace($expected), $this->_normalizeSpace($processed));
	}

	public function test_Remove_Property_IMG_LONGDESC_3()
	{
		$html = "
			<body>
				<p>Some contents</p>
				<img SRC=\"recipetitlebar.gif\" someotherprop=\"Image for testing\" longdesc=\"justanotherimage\" />
			</body>
		";

		$expected = "
			<body>
				<p>Some contents</p>
				<img SRC=\"recipetitlebar.gif\" someotherprop=\"Image for testing\" />
			</body>
		";

		$object = new Design_Rules_Check_API('Outlook 2007');
		$processed = $object->Process($html, true);

		$this->assertEquals($this->_normalizeSpace($expected), $this->_normalizeSpace($processed));
	}

	public function test_Remove_Property_IMG_LONGDESC_4()
	{
		$html = "
			<body>
				<p>Some contents</p>
				<img SRC=\"recipetitlebar.gif\" someotherprop=\"Image for testing\" longdesc=\"justanotherimage\">
			</body>
		";

		$expected = "
			<body>
				<p>Some contents</p>
				<img SRC=\"recipetitlebar.gif\" someotherprop=\"Image for testing\">
			</body>
		";

		$object = new Design_Rules_Check_API('Outlook 2007');
		$processed = $object->Process($html, true);

		$this->assertEquals($this->_normalizeSpace($expected), $this->_normalizeSpace($processed));
	}

	public function test_Remove_Property_Inline_JavaScript()
	{
		$html = "
			<body>
				<p onblur=\"alert('>');\">Some contents</p>
			</body>
		";

		$expected = "
			<body>
				<p>Some contents</p>
			</body>
		";

		$object = new Design_Rules_Check_API('Outlook 2007');
		$processed = $object->Process($html, true);

		$this->assertEquals($this->_normalizeSpace($expected), $this->_normalizeSpace($processed));
	}

	public function test_Remove_Property_Inline_JavaScript_2()
	{
		$html = "
			<body>
				<p onclick=\"javascript:clikcme('p'); \" onblur=\"alert('p');\" >Some contents</p>
			</body>
		";

		$expected = "
			<body>
				<p>Some contents</p>
			</body>
		";

		$object = new Design_Rules_Check_API('Outlook 2007');
		$processed = $object->Process($html, true);

		$this->assertEquals($this->_normalizeSpace($expected), $this->_normalizeSpace($processed));
	}

	public function test_Remove_Property_Inline_JavaScript_3()
	{
		$html = "
			<body>
				<p onclick=\"javascript:clikcme('p'); \" onblur=\"alert('p');\" >Some contents</p>
				<p>onblur=\"alert('p');\"</p>
			</body>
		";

		$expected = "
			<body>
				<p>Some contents</p>
				<p>onblur=\"alert('p');\"</p>
			</body>
		";

		$object = new Design_Rules_Check_API('Outlook 2007');
		$processed = $object->Process($html, true);

		$this->assertEquals($this->_normalizeSpace($expected), $this->_normalizeSpace($processed));
	}

	public function test_Remove_Property_A_TABINDEX()
	{
		$html = "
			<body>
				<p>Some contents</p>
				<a tabindex=\"5\" href=\"index.html\">hello</a>
			</body>
		";

		$expected = "
			<body>
				<p>Some contents</p>
				<a href=\"index.html\">hello</a>
			</body>
		";

		$object = new Design_Rules_Check_API('Outlook 2007');
		$processed = $object->Process($html, true);

		$this->assertEquals($this->_normalizeSpace($expected), $this->_normalizeSpace($processed));
	}

	public function test_Remove_Property_A_TABINDEX_2()
	{
		$html = "
			<body>
				<p>Some contents</p>
				<a tabindex=5 href=\"index.html\">hello</area>
			</body>
		";

		$expected = "
			<body>
				<p>Some contents</p>
				<a href=\"index.html\">hello</area>
			</body>
		";

		$object = new Design_Rules_Check_API('Outlook 2007');
		$processed = $object->Process($html, true);

		$this->assertEquals($this->_normalizeSpace($expected), $this->_normalizeSpace($processed));
	}

	public function test_Remove_Property_AREA_TABINDEX()
	{
		$html = "
			<body>
				<p>Some contents</p>
				<area tabindex=\"5\" href=\"index.html\">hello</area>
			</body>
		";

		$expected = "
			<body>
				<p>Some contents</p>
				<area href=\"index.html\">hello</area>
			</body>
		";

		$object = new Design_Rules_Check_API('Outlook 2007');
		$processed = $object->Process($html, true);

		$this->assertEquals($this->_normalizeSpace($expected), $this->_normalizeSpace($processed));
	}

	public function test_Remove_Property_AREA_TABINDEX_2()
	{
		$html = "
			<body>
				<p>Some contents</p>
				<area tabindex=5 href=\"index.html\">hello</area>
			</body>
		";

		$expected = "
			<body>
				<p>Some contents</p>
				<area href=\"index.html\">hello</area>
			</body>
		";

		$object = new Design_Rules_Check_API('Outlook 2007');
		$processed = $object->Process($html, true);

		$this->assertEquals($this->_normalizeSpace($expected), $this->_normalizeSpace($processed));
	}

	public function test_Remove_Property_TITLE()
	{
		$html = "
			<body>
				<p>Some contents</p>
				<a href=\"somelink.html\" title=\"Some title\" someproperty>somelink here</a>
			</body>
		";

		$expected = "
			<body>
				<p>Some contents</p>
				<a href=\"somelink.html\" someproperty>somelink here</a>
			</body>
		";

		$object = new Design_Rules_Check_API('Outlook 2007');
		$processed = $object->Process($html, true);

		$this->assertEquals($this->_normalizeSpace($expected), $this->_normalizeSpace($processed));
	}

	public function test_Remove_Property_IMG_ALT()
	{
		$html = "
			<body>
				<p>Some contents</p>
				<img src=\"somelink.gif\" alt=\"Alternative text\" someproperty />
			</body>
		";

		$expected = "
			<body>
				<p>Some contents</p>
				<img src=\"somelink.gif\" someproperty />
			</body>
		";

		$object = new Design_Rules_Check_API('Outlook 2007');
		$processed = $object->Process($html, true);

		$this->assertEquals($this->_normalizeSpace($expected), $this->_normalizeSpace($processed));
	}

	public function test_Remove_Property_IMG_ALT_2()
	{
		$html = "
			<body>
				<p>Some contents</p>
				<img src=\"somelink.gif\" alt=\"Alternative text\" someproperty>
			</body>
		";

		$expected = "
			<body>
				<p>Some contents</p>
				<img src=\"somelink.gif\" someproperty>
			</body>
		";

		$object = new Design_Rules_Check_API('Outlook 2007');
		$processed = $object->Process($html, true);

		$this->assertEquals($this->_normalizeSpace($expected), $this->_normalizeSpace($processed));
	}

	public function test_Remove_Property_TD_COLSPAN()
	{
		$html = "
			<body>
				<p>Some contents</p>
				<table>
					<tr someotherproperty>
						<td someotherproperty>Row 1</td>
						<td someotherproperty>Row 2</td>
					</tr>
					<tr someotherproperty>
						<td colspan=\"0\" someotherproperty>Row 2</td>
						<td someotherproperty>Row 2</td>
						<td someotherproperty>Row 2</td>
					</td>
				</table>
			</body>
		";

		$expected = "
			<body>
				<p>Some contents</p>
				<table>
					<tr someotherproperty>
						<td someotherproperty>Row 1</td>
						<td someotherproperty>Row 2</td>
					</tr>
					<tr someotherproperty>
						<td someotherproperty>Row 2</td>
						<td someotherproperty>Row 2</td>
						<td someotherproperty>Row 2</td>
					</td>
				</table>
			</body>
		";

		$object = new Design_Rules_Check_API('Outlook 2007');
		$processed = $object->Process($html, true);

		$this->assertEquals($this->_normalizeSpace($expected), $this->_normalizeSpace($processed));
	}

	public function test_Remove_Property_TR_ROWSPAN()
	{
		$html = "
			<body>
				<p>Some contents</p>
				<table>
					<tr someotherproperty>
						<td someotherproperty>Row 1</td>
						<td someotherproperty>Row 2</td>
					</tr>
					<tr rowspan=\"0\" someotherproperty>
						<td someotherproperty>Row 2</td>
						<td someotherproperty>Row 2</td>
					</td>
				</table>
			</body>
		";

		$expected = "
			<body>
				<p>Some contents</p>
				<table>
					<tr someotherproperty>
						<td someotherproperty>Row 1</td>
						<td someotherproperty>Row 2</td>
					</tr>
					<tr someotherproperty>
						<td someotherproperty>Row 2</td>
						<td someotherproperty>Row 2</td>
					</td>
				</table>
			</body>
		";

		$object = new Design_Rules_Check_API('Outlook 2007');
		$processed = $object->Process($html, true);

		$this->assertEquals($this->_normalizeSpace($expected), $this->_normalizeSpace($processed));
	}
}