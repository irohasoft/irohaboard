<?php
App::uses('Content', 'Model');

/**
 * Content Test Case
 */
class ContentTest extends CakeTestCase
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
		$this->Content = ClassRegistry::init('Content');
	}

	/**
	 * tearDown method
	 *
	 * @return void
	 */
	public function tearDown()
	{
		unset($this->Content);
		parent::tearDown();
	}

	public function testGetContentRecord()
	{
		$user_id = 1;
		$course_id = 1;
		
		$records = $this->Content->getContentRecord($user_id, $course_id);
	}

	public function testSetOrder()
	{
		$id_list = [2, 1];
		
		$this->Content->setOrder($id_list);
		
		$list = $this->Content->find()->where(['Content.id IN ' => $id_list])->order('Content.sort_no')->all();
		
		$result = [];
		
		foreach($list as $row)
		{
			$result[] = $row['Content']['id'];
		}
		
		$this->assertEquals($id_list, $result);
	}

	public function testGetNextSortNo()
	{
		$next_no = $this->Content->getNextSortNo(1);
		
		$this->assertEquals($next_no, 3);
	}
}
