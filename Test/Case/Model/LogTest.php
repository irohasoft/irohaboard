<?php
App::uses('Log', 'Model');

/**
 * Log Test Case
 */
class LogTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.log',
		'app.user',
		'app.content',
		'app.course',
		'app.users_course',
		'app.record',
		'app.group',
		'app.records_question',
		'app.question',
		'app.users_group'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Log = ClassRegistry::init('Log');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Log);

		parent::tearDown();
	}

}
