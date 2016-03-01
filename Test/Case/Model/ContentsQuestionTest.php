<?php
App::uses('ContentsQuestion', 'Model');

/**
 * ContentsQuestion Test Case
 */
class ContentsQuestionTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.contents_question',
		'app.group',
		'app.course'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->ContentsQuestion = ClassRegistry::init('ContentsQuestion');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->ContentsQuestion);

		parent::tearDown();
	}

}
