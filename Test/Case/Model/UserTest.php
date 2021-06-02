<?php
App::uses('User', 'Model');

/**
 * User Test Case
 */
class UserTest extends CakeTestCase
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
		$this->User = ClassRegistry::init('User');
		$this->Record = ClassRegistry::init('Record');
	}

	/**
	 * tearDown method
	 *
	 * @return void
	 */
	public function tearDown()
	{
		unset($this->User);
		parent::tearDown();
	}

	public function testDeleteUserRecords()
	{
		$user_id = 1;
		
		// 学習履歴を削除
		$this->User->deleteUserRecords($user_id);

		// 指定したユーザの学習履歴の件数を取得
		$cnt = $this->Record->find()->where(['Record.user_id' => $user_id])->count();
		
		$this->assertEquals($cnt, 0);
	}
}
