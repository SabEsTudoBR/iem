<?php
require_once(dirname(__FILE__) . '/_common.php');

class admin_functions_api_designrules_outlook2003 extends IEM_Tests_Unit_DesignRuleTests
{
	public function test_Remove_InlineStyle_Backgound_spacing()
	{
		$html = "
			<body style=\"background-spacing: 2px; somedummyproperty:1px;\">
				<p>Some contents</p>
			</body>
		";

		$expected = "
			<body style=\"; somedummyproperty:1px;\">
				<p>Some contents</p>
			</body>
		";

		$object = new Design_Rules_Check_API('Outlook 2003');
		$processed = $object->Process($html, true);

		$this->assertEquals($this->_normalizeSpace($expected), $this->_normalizeSpace($processed));
	}

	public function test_Remove_InlineStyle_Backgound_spacing_2()
	{
		$html = "
			<body style = \"background-spacing: 2px\">
				<p>Some contents</p>
			</body>
		";

		$expected = "
			<body style = \"\">
				<p>Some contents</p>
			</body>
		";

		$object = new Design_Rules_Check_API('Outlook 2003');
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

		$object = new Design_Rules_Check_API('Outlook 2003');
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

		$object = new Design_Rules_Check_API('Outlook 2003');
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

		$object = new Design_Rules_Check_API('Outlook 2003');
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

		$object = new Design_Rules_Check_API('Outlook 2003');
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

		$object = new Design_Rules_Check_API('Outlook 2003');
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

		$object = new Design_Rules_Check_API('Outlook 2003');
		$processed = $object->Process($html, true);

		$this->assertEquals($this->_normalizeSpace($expected), $this->_normalizeSpace($processed));
	}

	public function test_Remove_InlineStyle_WhiteSpace()
	{
		$html = "
			<body>
				<div>This is a long sentace obtained from lipsum.com:</div>
				<p style=\"white-space: nowrap;\">Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Curabitur sapien. Suspendisse pharetra turpis at urna vulputate adipiscing. Cras arcu turpis, pulvinar sed, bibendum quis, euismod sed, libero. Pellentesque pede odio, tincidunt a, dignissim ac, sodales eu, massa. Phasellus ullamcorper sapien et dolor ultricies pharetra. Etiam convallis diam eget arcu. Nulla facilisi. Vivamus at est. Nullam dapibus volutpat ante. Donec est enim, feugiat in, fermentum ut, mollis dapibus, pede</p>
			</body>
		";

		$expected = "
			<body>
				<div>This is a long sentace obtained from lipsum.com:</div>
				<p style=\";\">Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Curabitur sapien. Suspendisse pharetra turpis at urna vulputate adipiscing. Cras arcu turpis, pulvinar sed, bibendum quis, euismod sed, libero. Pellentesque pede odio, tincidunt a, dignissim ac, sodales eu, massa. Phasellus ullamcorper sapien et dolor ultricies pharetra. Etiam convallis diam eget arcu. Nulla facilisi. Vivamus at est. Nullam dapibus volutpat ante. Donec est enim, feugiat in, fermentum ut, mollis dapibus, pede</p>
			</body>
		";

		$object = new Design_Rules_Check_API('Outlook 2003');
		$processed = $object->Process($html, true);

		$this->assertEquals($this->_normalizeSpace($expected), $this->_normalizeSpace($processed));
	}

	public function test_Remove_InlineStyle_WhiteSpace_2()
	{
		$html = "
			<body>
				<div>This is a long sentace obtained from lipsum.com:</div>
				<p style=\"someotherproperties: 5px; white-space: nowrap\">Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Curabitur sapien. Suspendisse pharetra turpis at urna vulputate adipiscing. Cras arcu turpis, pulvinar sed, bibendum quis, euismod sed, libero. Pellentesque pede odio, tincidunt a, dignissim ac, sodales eu, massa. Phasellus ullamcorper sapien et dolor ultricies pharetra. Etiam convallis diam eget arcu. Nulla facilisi. Vivamus at est. Nullam dapibus volutpat ante. Donec est enim, feugiat in, fermentum ut, mollis dapibus, pede</p>
			</body>
		";

		$expected = "
			<body>
				<div>This is a long sentace obtained from lipsum.com:</div>
				<p style=\"someotherproperties: 5px;\">Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Curabitur sapien. Suspendisse pharetra turpis at urna vulputate adipiscing. Cras arcu turpis, pulvinar sed, bibendum quis, euismod sed, libero. Pellentesque pede odio, tincidunt a, dignissim ac, sodales eu, massa. Phasellus ullamcorper sapien et dolor ultricies pharetra. Etiam convallis diam eget arcu. Nulla facilisi. Vivamus at est. Nullam dapibus volutpat ante. Donec est enim, feugiat in, fermentum ut, mollis dapibus, pede</p>
			</body>
		";

		$object = new Design_Rules_Check_API('Outlook 2003');
		$processed = $object->Process($html, true);

		$this->assertEquals($this->_normalizeSpace($expected), $this->_normalizeSpace($processed));
	}
}