<?php
require_once(dirname(__FILE__) . '/_common.php');

class admin_functions_api_spamrules_interspire extends IEM_Tests_Unit_SpamRuleTests
{
	public function setup()
	{
		$this->api = new Spam_Check_API();
	}

	public function test_Text_Single_Rule()
	{
		$text = 'freepic';

		$expected = array(
			'text' => array(
				'rating' => Spam_Check_API::RATING_NOT_SPAM,
				'score' => 0.4,
				'broken_rules' => array(
					array('Contains the word \'freepic\'', '0.4'),
				),
			),
			'html' => array()
		);

		$actual = $this->api->Process($text);
		$this->assertEquals($expected, $actual);
	}

	public function test_Text_Multiple_Rules()
	{
		$text = 'freepic, picpost';

		$expected = array(
			'text' => array(
				'rating' => Spam_Check_API::RATING_NOT_SPAM,
				'score' => 1.6,
				'broken_rules' => array(
					array('Contains the word \'freepic\'', '0.4'),
					array('Contains the word \'picpost\'', '1.2'),
				),
			),
			'html' => array()
		);

		$actual = $this->api->Process($text);
		$this->assertEquals($expected, $actual);
	}

	public function test_Text_And_HTML_Single_Rule()
	{
		$text = $html = 'freepic';

		$result = array(
			'rating' => Spam_Check_API::RATING_NOT_SPAM,
			'score' => 0.4,
			'broken_rules' => array(
				array('Contains the word \'freepic\'', '0.4'),
			),
		);
		$expected = array(
			'text' => $result,
			'html' => $result,
		);

		$actual = $this->api->Process($text, $html);
		$this->assertEquals($expected, $actual);
	}

	public function test_No_Text_Rule()
	{
		$html = 'freepic';

		$expected = array(
			'text' => array(
				'rating' => Spam_Check_API::RATING_NOT_SPAM,
				'score' => 1.2,
				'broken_rules' => array(
					array('Message only has text/html MIME parts', '1.2'),
				),
			),
			'html' => array(
				'rating' => Spam_Check_API::RATING_NOT_SPAM,
				'score' => 0.4,
				'broken_rules' => array(
					array('Contains the word \'freepic\'', '0.4'),
				),
			),
		);

		$actual = $this->api->Process(false, $html);
		$this->assertEquals($expected, $actual);
	}

	public function test_Text_And_HTML_Different()
	{
		$text = 'dog';
		$html = 'cat'; // Should create a 0% similarity

		$result = array(
			'rating' => Spam_Check_API::RATING_NOT_SPAM,
			'score' => 1.5,
			'broken_rules' => array(
				array('HTML and text parts are different', '1.5'),
			),
		);
		$expected = array(
			'text' => $result,
			'html' => $result,
		);

		$actual = $this->api->Process($text, $html);
		$this->assertEquals($expected, $actual);
	}
}
