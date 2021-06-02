<?php
App::uses('Info', 'Model');

/**
 * Info Test Case
 */
class InfoTest extends CakeTestCase
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
		$this->Info = ClassRegistry::init('Info');
	}

	/**
	 * tearDown method
	 *
	 * @return void
	 */
	public function tearDown()
	{
		unset($this->Info);

		parent::tearDown();
	}

	public function testGetInfos()
	{
		$user_id = 1;
		$limit = 2;
		
		$result = $this->Info->getInfos($user_id, $limit);
		
		$this->assertEquals($result[0]['Info']['title'], 'お知らせ1');
	}

	public function testGetInfoOption()
	{
		$user_id = 1;
		$limit = 2;
		
		$this->Info->getInfoOption($user_id, $limit);
	}

	public function testHasRight()
	{
		$user_id	= 1;
		$info_id	= 1;
		
		$result = $this->Info->hasRight($user_id, $info_id);
		$this->assertEquals($result, true);
		
		$user_id	= 1;
		$info_id	= 2;
		
		$result = $this->Info->hasRight($user_id, $info_id);
		$this->assertEquals($result, false);
	}
}
