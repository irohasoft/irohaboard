<?php
App::uses('Record', 'Model');

/**
 * Record Test Case
 */
class RecordTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.record',
		'app.group',
		'app.content',
		'app.course',
		'app.contents_question',
		'app.user',
		'app.users_group',
		'app.users_course'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Record = ClassRegistry::init('Record');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Record);

		parent::tearDown();
	}

}
