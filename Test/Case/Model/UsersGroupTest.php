<?php
App::uses('UsersGroup', 'Model');

/**
 * UsersGroup Test Case
 */
class UsersGroupTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.users_group',
		'app.user',
		'app.group',
		'app.content',
		'app.course',
		'app.contents_question',
		'app.record',
		'app.users_course'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->UsersGroup = ClassRegistry::init('UsersGroup');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->UsersGroup);

		parent::tearDown();
	}

}
