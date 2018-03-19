<?php
require_once(dirname(__FILE__) . '/_common.php');

class admin_functions_api_designrules_gmail extends IEM_Tests_Unit_DesignRuleTests
{
	public function test_Remove_Everything_Before_Body_Tag()
	{
		$contents = "
			<html>
				<head>
					<title>Hello World</title>
					<style>
						body { margin:0; padding:0; }
						p { margin-left: 5px; }
					</style>
				</head>
				<body style=\"somebogusstyle:#f7f7f7;\">
					<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" bgcolor=\"#f7f7f7\">
					</table>
				</body>
			</html>
		";

		$condition = $this->_normalizeSpace("
			<body style=\"somebogusstyle:#f7f7f7;\">
				<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" bgcolor=\"#f7f7f7\">
				</table>
			</body>
		");


		$api = new Design_Rules_Check_API('GMail');
		$processed = $this->_normalizeSpace($api->Process($contents, true));

		$this->assertEquals($condition, $processed);
	}

	public function test_Remove_Everything_Before_Body_Tag_2()
	{
		$contents = "
			<body style=\"somebogusstyle:#f7f7f7;\">
				<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" bgcolor=\"#f7f7f7\">
				</table>
			</body>
		";

		$condition = $this->_normalizeSpace("
			<body style=\"somebogusstyle:#f7f7f7;\">
				<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" bgcolor=\"#f7f7f7\">
				</table>
			</body>
		");


		$api = new Design_Rules_Check_API('GMail');
		$processed = $this->_normalizeSpace($api->Process($contents, true));

		$this->assertEquals($condition, $processed);
	}

	public function test_Remove_Everything_Before_Body_Tag_3()
	{
		$contents = "
			<body style=\"somebogusstyle:#f7f7f7;\">
				<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" bgcolor=\"#f7f7f7\">
				</table>
			</body>
			<script type=\"javascript\">
				print '<body>Hello</body>';
			</script>
		";

		$condition = $this->_normalizeSpace("
			<body style=\"somebogusstyle:#f7f7f7;\">
				<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" bgcolor=\"#f7f7f7\">
				</table>
			</body>
		");


		$api = new Design_Rules_Check_API('GMail');
		$processed = $this->_normalizeSpace($api->Process($contents, true));

		$this->assertEquals($condition, $processed);
	}

	public function test_Remove_Background_Image()
	{
		$contents = "
			<body style=\"somebogusstyle:#f7f7f7;   background-image:url('someimage.jpg');\">
				<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" bgcolor=\"#f7f7f7\">
				</table>
			</body>
		";

		$condition = $this->_normalizeSpace("
			<body style=\"somebogusstyle:#f7f7f7;;\">
				<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" bgcolor=\"#f7f7f7\">
				</table>
			</body>
		");


		$api = new Design_Rules_Check_API('GMail');
		$processed = $this->_normalizeSpace($api->Process($contents, true));

		$this->assertEquals($condition, $processed);
	}

	public function test_Remove_Background_Image_2()
	{
		$contents = "
			<body style=\"somebogusstyle:#f7f7f7;   background-image:url('someimage.jpg')\">
				<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" bgcolor=\"#f7f7f7\">
				</table>
			</body>
		";

		$condition = $this->_normalizeSpace("
			<body style=\"somebogusstyle:#f7f7f7;\">
				<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" bgcolor=\"#f7f7f7\">
				</table>
			</body>
		");


		$api = new Design_Rules_Check_API('GMail');
		$processed = $this->_normalizeSpace($api->Process($contents, true));

		$this->assertEquals($condition, $processed);
	}

	public function test_Remove_Background_Position()
	{
		$contents = "
			<body style=\"somebogusstyle:#f7f7f7;   background-position: top left ;\">
				<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" bgcolor=\"#f7f7f7\">
				</table>
			</body>
		";

		$condition = $this->_normalizeSpace("
			<body style=\"somebogusstyle:#f7f7f7;;\">
				<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" bgcolor=\"#f7f7f7\">
				</table>
			</body>
		");


		$api = new Design_Rules_Check_API('GMail');
		$processed = $this->_normalizeSpace($api->Process($contents, true));

		$this->assertEquals($condition, $processed);
	}

	public function test_Remove_Background_Position_2()
	{
		$contents = "
			<body style=\" somebogusstyle:#f7f7f7;   background-position: top left   \">
				<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" bgcolor=\"#f7f7f7\">
				</table>
			</body>
		";

		$condition = $this->_normalizeSpace("
			<body style=\" somebogusstyle:#f7f7f7;\">
				<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" bgcolor=\"#f7f7f7\">
				</table>
			</body>
		");


		$api = new Design_Rules_Check_API('GMail');
		$processed = $this->_normalizeSpace($api->Process($contents, true));

		$this->assertEquals($condition, $processed);
	}

	public function test_Remove_Background_Repeat()
	{
		$contents = "
			<body style=\" somebogusstyle:#f7f7f7;   background-repeat: no-repeat;  \">
				<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" bgcolor=\"#f7f7f7\">
				</table>
			</body>
		";

		$condition = $this->_normalizeSpace("
			<body style=\" somebogusstyle:#f7f7f7;;  \">
				<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" bgcolor=\"#f7f7f7\">
				</table>
			</body>
		");


		$api = new Design_Rules_Check_API('GMail');
		$processed = $this->_normalizeSpace($api->Process($contents, true));

		$this->assertEquals($condition, $processed);
	}

	public function test_Remove_Background_Repeat_2()
	{
		$contents = "
			<body style=\" somebogusstyle:#f7f7f7;   background-repeat: no-repeat  \">
				<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" bgcolor=\"#f7f7f7\">
				</table>
			</body>
		";

		$condition = $this->_normalizeSpace("
			<body style=\" somebogusstyle:#f7f7f7;\">
				<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" bgcolor=\"#f7f7f7\">
				</table>
			</body>
		");


		$api = new Design_Rules_Check_API('GMail');
		$processed = $this->_normalizeSpace($api->Process($contents, true));

		$this->assertEquals($condition, $processed);
	}

	public function test_Remove_Background_Color()
	{
		$contents = "
			<body style=\" somebogusstyle:#f7f7f7;   background-color: #f7f7f7;  \">
				<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" bgcolor=\"#f7f7f7\">
				</table>
			</body>
		";

		$condition = $this->_normalizeSpace("
			<body style=\" somebogusstyle:#f7f7f7;; \">
				<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" bgcolor=\"#f7f7f7\">
				</table>
			</body>
		");


		$api = new Design_Rules_Check_API('GMail');
		$processed = $this->_normalizeSpace($api->Process($contents, true));

		$this->assertEquals($condition, $processed);
	}

	public function test_Remove_Background_Color_2()
	{
		$contents = "
			<body style=\" somebogusstyle: #f7f7f7;   background-color: #f7f7f7  \">
				<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" bgcolor=\"#f7f7f7\">
				</table>
			</body>
		";

		$condition = $this->_normalizeSpace("
			<body style=\" somebogusstyle: #f7f7f7;\">
				<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" bgcolor=\"#f7f7f7\">
				</table>
			</body>
		");


		$api = new Design_Rules_Check_API('GMail');
		$processed = $this->_normalizeSpace($api->Process($contents, true));

		$this->assertEquals($condition, $processed);
	}

	public function test_Remove_Background_Combine()
	{
		$contents = "
			<body style=\"
				margin: 0px;
				background-image: url(stars.gif);
				somebogusstyle1: none;
				background-repeat: no-repeat;
				somebogusstyle2: none;
				background-position: top left;

				padding: 0px;  \">
				<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" bgcolor=\"#f7f7f7\">
				</table>
			</body>
		";

		$condition = $this->_normalizeSpace("
			<body style=\" margin: 0px;; somebogusstyle1: none;; somebogusstyle2: none;; padding: 0px; \">
				<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" bgcolor=\"#f7f7f7\">
				</table>
			</body>
		");


		$api = new Design_Rules_Check_API('GMail');
		$processed = $this->_normalizeSpace($api->Process($contents, true));

		$this->assertEquals($condition, $processed);
	}

	public function test_Remove_Bottom()
	{
		$contents = "
			<body style=\" somebogusstyle1: none; bottom: 0px; border-bottom: 1px;\">
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


		$api = new Design_Rules_Check_API('GMail');
		$processed = $this->_normalizeSpace($api->Process($contents, true));

		$this->assertEquals($condition, $processed);
	}

	public function test_Remove_Bottom_2()
	{
		$contents = "
			<body style=\"bottom: 0px\">
				<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" bgcolor=\"#f7f7f7\">
				</table>
			</body>
		";

		$condition = $this->_normalizeSpace("
			<body style=\"\">
				<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" bgcolor=\"#f7f7f7\">
				</table>
			</body>
		");


		$api = new Design_Rules_Check_API('GMail');
		$processed = $this->_normalizeSpace($api->Process($contents, true));

		$this->assertEquals($condition, $processed);
	}

	public function test_Remove_Top()
	{
		$contents = "
			<body style=\"top: 0px\">
				<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" bgcolor=\"#f7f7f7\">
				</table>
			</body>
		";

		$condition = $this->_normalizeSpace("
			<body style=\"\">
				<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" bgcolor=\"#f7f7f7\">
				</table>
			</body>
		");


		$api = new Design_Rules_Check_API('GMail');
		$processed = $this->_normalizeSpace($api->Process($contents, true));

		$this->assertEquals($condition, $processed);
	}

	public function test_Remove_Top_2()
	{
		$contents = "
			<body style=\" somebogusstyle1: none; top: 0px; border-bottom: 1px;\">
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


		$api = new Design_Rules_Check_API('GMail');
		$processed = $this->_normalizeSpace($api->Process($contents, true));

		$this->assertEquals($condition, $processed);
	}

	public function test_Remove_Left()
	{
		$contents = "
			<body style=\" somebogusstyle1: none; left: 0px; border-bottom: 1px;\">
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


		$api = new Design_Rules_Check_API('GMail');
		$processed = $this->_normalizeSpace($api->Process($contents, true));

		$this->assertEquals($condition, $processed);
	}

	public function test_Remove_Left_2()
	{
		$contents = "
			<body style=\"left: 0px\">
				<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" bgcolor=\"#f7f7f7\">
				</table>
			</body>
		";

		$condition = $this->_normalizeSpace("
			<body style=\"\">
				<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" bgcolor=\"#f7f7f7\">
				</table>
			</body>
		");


		$api = new Design_Rules_Check_API('GMail');
		$processed = $this->_normalizeSpace($api->Process($contents, true));

		$this->assertEquals($condition, $processed);
	}

	public function test_Remove_Right()
	{
		$contents = "
			<body style=\" somebogusstyle1: none; Right: 0px; border-bottom: 1px;\">
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


		$api = new Design_Rules_Check_API('GMail');
		$processed = $this->_normalizeSpace($api->Process($contents, true));

		$this->assertEquals($condition, $processed);
	}

	public function test_Remove_Right_2()
	{
		$contents = "
			<body style=\"rIght: 0px\">
				<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" bgcolor=\"#f7f7f7\">
				</table>
			</body>
		";

		$condition = $this->_normalizeSpace("
			<body style=\"\">
				<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" bgcolor=\"#f7f7f7\">
				</table>
			</body>
		");


		$api = new Design_Rules_Check_API('GMail');
		$processed = $this->_normalizeSpace($api->Process($contents, true));

		$this->assertEquals($condition, $processed);
	}

	public function test_Remove_Top_Bottom_Left_Right()
	{
		$contents = "
			<body style=\" somebogusstyle1: none; bottom: 0px; top: 0px; border-bottom: 1px; left:0px; right:0px;\">
				<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" bgcolor=\"#f7f7f7\">
				</table>
			</body>
		";

		$condition = $this->_normalizeSpace("
			<body style=\" somebogusstyle1: none;;; border-bottom: 1px;;;\">
				<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" bgcolor=\"#f7f7f7\">
				</table>
			</body>
		");


		$api = new Design_Rules_Check_API('GMail');
		$processed = $this->_normalizeSpace($api->Process($contents, true));

		$this->assertEquals($condition, $processed);
	}

	public function test_Remove_Top_Bottom_Left_Right_2()
	{
		$contents = "
			<body style=\"bottom: 0px; top: 0px; left:0px; right:0px;\">
				<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" bgcolor=\"#f7f7f7\">
				</table>
			</body>
		";

		$condition = $this->_normalizeSpace("
			<body style=\";;;;\">
				<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" bgcolor=\"#f7f7f7\">
				</table>
			</body>
		");


		$api = new Design_Rules_Check_API('GMail');
		$processed = $this->_normalizeSpace($api->Process($contents, true));

		$this->assertEquals($condition, $processed);
	}

	public function test_Remove_Clear()
	{
		$contents = "
			<body style=\" somebogusstyle1: none; clear: left; border-bottom: 1px;\">
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


		$api = new Design_Rules_Check_API('GMail');
		$processed = $this->_normalizeSpace($api->Process($contents, true));

		$this->assertEquals($condition, $processed);
	}

	public function test_Remove_Clear_2()
	{
		$contents = "
			<body style=\"clear: right;\">
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


		$api = new Design_Rules_Check_API('GMail');
		$processed = $this->_normalizeSpace($api->Process($contents, true));

		$this->assertEquals($condition, $processed);
	}

	public function test_Remove_Clip()
	{
		$contents = "
			<body style=\" somebogusstyle1: none; clip: rect(10px, 5px, 10px, 5px); border-bottom: 1px;\">
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


		$api = new Design_Rules_Check_API('GMail');
		$processed = $this->_normalizeSpace($api->Process($contents, true));

		$this->assertEquals($condition, $processed);
	}

	public function test_Remove_Clip_2()
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


		$api = new Design_Rules_Check_API('GMail');
		$processed = $this->_normalizeSpace($api->Process($contents, true));

		$this->assertEquals($condition, $processed);
	}

	public function test_Remove_Cursor()
	{
		$contents = "
			<body style=\" somebogusstyle1: none; cursor: hand; border-bottom: 1px;\">
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


		$api = new Design_Rules_Check_API('GMail');
		$processed = $this->_normalizeSpace($api->Process($contents, true));

		$this->assertEquals($condition, $processed);
	}

	public function test_Remove_Cursor_2()
	{
		$contents = "
			<body style=\"cursor: pointer;\">
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


		$api = new Design_Rules_Check_API('GMail');
		$processed = $this->_normalizeSpace($api->Process($contents, true));

		$this->assertEquals($condition, $processed);
	}

	public function test_Remove_Filter()
	{
		$contents = "
			<body style=\" somebogusstyle1: none; filter:alpha(opacity=40); border-bottom: 1px;\">
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


		$api = new Design_Rules_Check_API('GMail');
		$processed = $this->_normalizeSpace($api->Process($contents, true));

		$this->assertEquals($condition, $processed);
	}

	public function test_Remove_Filter_2()
	{
		$contents = "
			<body style=\"filter:alpha(opacity=40);\">
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


		$api = new Design_Rules_Check_API('GMail');
		$processed = $this->_normalizeSpace($api->Process($contents, true));

		$this->assertEquals($condition, $processed);
	}

	public function test_Remove_Float()
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


		$api = new Design_Rules_Check_API('GMail');
		$processed = $this->_normalizeSpace($api->Process($contents, true));

		$this->assertEquals($condition, $processed);
	}

	public function test_Remove_Float_2()
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


		$api = new Design_Rules_Check_API('GMail');
		$processed = $this->_normalizeSpace($api->Process($contents, true));

		$this->assertEquals($condition, $processed);
	}

	public function test_Remove_HeaderFontFamily()
	{
		$contents = "
			<body style=\" somebogusstyle1: none; font-family: Arial Verdana; border-bottom: 1px;\">
				<h1 style=\" somebogusstyle1: none; font-family: Arial Verdana; border-bottom: 1px;\">Some Header</h1>
				<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" bgcolor=\"#f7f7f7\">
				</table>
			</body>
		";

		$condition = $this->_normalizeSpace("
			<body style=\" somebogusstyle1: none; font-family: Arial Verdana;  border-bottom: 1px;\">
				<h1 style=\" somebogusstyle1: none;; border-bottom: 1px;\">Some Header</h1>
				<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" bgcolor=\"#f7f7f7\">
				</table>
			</body>
		");


		$api = new Design_Rules_Check_API('GMail');
		$processed = $this->_normalizeSpace($api->Process($contents, true));

		$this->assertEquals($condition, $processed);
	}

	public function test_Remove_HeaderFontFamily_2()
	{
		$contents = "
			<body style=\"font-family: Lucida Monospace;\">
				<h1 style=\"font-family: Lucida Monospace;\">Header 1</h1>
				<h2 style=\"font-family: Lucida Monospace;\">Header 2</h2>
				<h3 style=\"font-family: Lucida Monospace;\">Header 3</h3>
				<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" bgcolor=\"#f7f7f7\">
				</table>
			</body>
		";

		$condition = $this->_normalizeSpace("
			<body style=\"font-family: Lucida Monospace;\">
				<h1 style=\";\">Header 1</h1>
				<h2 style=\";\">Header 2</h2>
				<h3 style=\";\">Header 3</h3>
				<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" bgcolor=\"#f7f7f7\">
				</table>
			</body>
		");


		$api = new Design_Rules_Check_API('GMail');
		$processed = $this->_normalizeSpace($api->Process($contents, true));

		$this->assertEquals($condition, $processed);
	}

	public function test_Remove_Height()
	{
		$contents = "
			<body style=\" somebogusstyle1: none; height: 1px; line-height: 1px;\">
				<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" bgcolor=\"#f7f7f7\">
				</table>
			</body>
		";

		$condition = $this->_normalizeSpace("
			<body style=\" somebogusstyle1: none;; line-height: 1px;\">
				<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" bgcolor=\"#f7f7f7\">
				</table>
			</body>
		");


		$api = new Design_Rules_Check_API('GMail');
		$processed = $this->_normalizeSpace($api->Process($contents, true));

		$this->assertEquals($condition, $processed);
	}

	public function test_Remove_Height_2()
	{
		$contents = "
			<body style=\"height: 1px;\">
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


		$api = new Design_Rules_Check_API('GMail');
		$processed = $this->_normalizeSpace($api->Process($contents, true));

		$this->assertEquals($condition, $processed);
	}

	public function test_Remove_ListStyleImage()
	{
		$contents = "
			<body style=\" somebogusstyle1: none; list-style-image: url(blueball.gif); line-height: 1px;\">
				<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" bgcolor=\"#f7f7f7\">
				</table>
			</body>
		";

		$condition = $this->_normalizeSpace("
			<body style=\" somebogusstyle1: none;; line-height: 1px;\">
				<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" bgcolor=\"#f7f7f7\">
				</table>
			</body>
		");


		$api = new Design_Rules_Check_API('GMail');
		$processed = $this->_normalizeSpace($api->Process($contents, true));

		$this->assertEquals($condition, $processed);
	}

	public function test_Remove_ListStyleImage_2()
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


		$api = new Design_Rules_Check_API('GMail');
		$processed = $this->_normalizeSpace($api->Process($contents, true));

		$this->assertEquals($condition, $processed);
	}

	public function test_Remove_Opacity()
	{
		$contents = "
			<body style=\" somebogusstyle1: none; opacity:0.4; line-height: 1px;\">
				<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" bgcolor=\"#f7f7f7\">
				</table>
			</body>
		";

		$condition = $this->_normalizeSpace("
			<body style=\" somebogusstyle1: none;; line-height: 1px;\">
				<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" bgcolor=\"#f7f7f7\">
				</table>
			</body>
		");


		$api = new Design_Rules_Check_API('GMail');
		$processed = $this->_normalizeSpace($api->Process($contents, true));

		$this->assertEquals($condition, $processed);
	}

	public function test_Remove_Opacity_2()
	{
		$contents = "
			<body style=\"opacity:0.4;\">
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


		$api = new Design_Rules_Check_API('GMail');
		$processed = $this->_normalizeSpace($api->Process($contents, true));

		$this->assertEquals($condition, $processed);
	}

	public function test_Remove_Position()
	{
		$contents = "
			<body style=\" somebogusstyle1: none; position:top left; line-height: 1px;\">
				<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" bgcolor=\"#f7f7f7\">
				</table>
			</body>
		";

		$condition = $this->_normalizeSpace("
			<body style=\" somebogusstyle1: none;; line-height: 1px;\">
				<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" bgcolor=\"#f7f7f7\">
				</table>
			</body>
		");


		$api = new Design_Rules_Check_API('GMail');
		$processed = $this->_normalizeSpace($api->Process($contents, true));

		$this->assertEquals($condition, $processed);
	}

	public function test_Remove_Position_2()
	{
		$contents = "
			<body style=\"position:top left;\">
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


		$api = new Design_Rules_Check_API('GMail');
		$processed = $this->_normalizeSpace($api->Process($contents, true));

		$this->assertEquals($condition, $processed);
	}

	public function test_Remove_Visibility()
	{
		$contents = "
			<body style=\" somebogusstyle1: none; visibility:hidden; line-height: 1px;\">
				<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" bgcolor=\"#f7f7f7\">
				</table>
			</body>
		";

		$condition = $this->_normalizeSpace("
			<body style=\" somebogusstyle1: none;; line-height: 1px;\">
				<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" bgcolor=\"#f7f7f7\">
				</table>
			</body>
		");


		$api = new Design_Rules_Check_API('GMail');
		$processed = $this->_normalizeSpace($api->Process($contents, true));

		$this->assertEquals($condition, $processed);
	}

	public function test_Remove_Visibility_2()
	{
		$contents = "
			<body style=\"visibility:hidden;\">
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


		$api = new Design_Rules_Check_API('GMail');
		$processed = $this->_normalizeSpace($api->Process($contents, true));

		$this->assertEquals($condition, $processed);
	}

	public function test_Remove_ZIndex()
	{
		$contents = "
			<body style=\" somebogusstyle1: none; z-index:1; line-height: 1px;\">
				<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" bgcolor=\"#f7f7f7\">
				</table>
			</body>
		";

		$condition = $this->_normalizeSpace("
			<body style=\" somebogusstyle1: none;; line-height: 1px;\">
				<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" bgcolor=\"#f7f7f7\">
				</table>
			</body>
		");


		$api = new Design_Rules_Check_API('GMail');
		$processed = $this->_normalizeSpace($api->Process($contents, true));

		$this->assertEquals($condition, $processed);
	}

	public function test_Remove_ZIndex_2()
	{
		$contents = "
			<body style=\"z-index: 10000;\">
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


		$api = new Design_Rules_Check_API('GMail');
		$processed = $this->_normalizeSpace($api->Process($contents, true));

		$this->assertEquals($condition, $processed);
	}
}