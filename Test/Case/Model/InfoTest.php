<?php
App::uses('Info', 'Model');

/**
 * Info Test Case
 */
class InfoTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.info',
		'app.user',
		'app.group',
		'app.users_group',
		'app.content',
		'app.course',
		'app.users_course',
		'app.record',
		'app.records_question',
		'app.question'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Info = ClassRegistry::init('Info');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Info);

		parent::tearDown();
	}

}
