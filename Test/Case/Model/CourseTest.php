<?php
App::uses('Course', 'Model');

/**
 * Course Test Case
 */
class CourseTest extends CakeTestCase
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
		$this->Course = ClassRegistry::init('Course');
	}

	/**
	 * tearDown method
	 *
	 * @return void
	 */
	public function tearDown()
	{
		unset($this->Course);
		parent::tearDown();
	}

	public function testSetOrder()
	{
		$id_list = [2, 1];
		
		$this->Course->setOrder($id_list);
		
		$list = $this->Course->find()->where(['Course.id IN ' => $id_list])->order('Course.sort_no')->all();
		
		$result = [];
		
		foreach($list as $row)
		{
			$result[] = $row['Course']['id'];
		}
		
		$this->assertEquals($id_list, $result);
	}

	public function testHasRight()
	{
		$user_id	= 1;
		$course_id	= 1;
		
		$result = $this->Course->hasRight($user_id, $course_id);
		$this->assertEquals($result, true);
		
		$user_id	= 1;
		$course_id	= 2;
		
		$result = $this->Course->hasRight($user_id, $course_id);
		$this->assertEquals($result, false);
	}

	public function testDeleteCourse()
	{
		$course_id = 1;
		
		$result = $this->Course->find()->where(['Course.id' => $course_id])->count();
		$this->assertEquals($result, 1);
		
		$this->Course->deleteCourse($course_id);
		$result = $this->Course->find()->where(['Course.id' => $course_id])->count();
		
		$this->assertEquals($result, 0);
	}
}
