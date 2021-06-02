<?php
App::uses('Group', 'Model');

/**
 * Group Test Case
 */
class GroupTest extends CakeTestCase
{
	/**
	 * Fixtures
	 *
	 * @var array
	 */
	public $fixtures = [
		'app.content',
		'app.contents_question',
		'app.course',
		'app.group',
		'app.groups_course',
		'app.info',
		'app.infos_group',
		'app.log',
		'app.record',
		'app.records_question',
		'app.setting',
		'app.user',
		'app.users_group',
		'app.users_course'
	];

	/**
	 * setUp method
	 *
	 * @return void
	 */
	public function setUp()
	{
		parent::setUp();
		$this->Group = ClassRegistry::init('Group');
	}

	/**
	 * tearDown method
	 *
	 * @return void
	 */
	public function tearDown()
	{
		unset($this->Group);
		parent::tearDown();
	}

	public function testGetUserIdByGroupID()
	{
		$group_id = 1;
		$result = $this->Group->getUserIdByGroupID($group_id);
		$this->assertEquals($result, [1]);
	}
}
