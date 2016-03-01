<?php
App::uses('RecordsQuestion', 'Model');

/**
 * RecordsQuestion Test Case
 */
class RecordsQuestionTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.records_question',
		'app.record',
		'app.group',
		'app.content',
		'app.course',
		'app.contents_question',
		'app.user',
		'app.users_group',
		'app.users_course',
		'app.question'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->RecordsQuestion = ClassRegistry::init('RecordsQuestion');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->RecordsQuestion);

		parent::tearDown();
	}

}
