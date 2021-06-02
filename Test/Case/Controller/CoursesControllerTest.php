<?php
App::uses('CoursesController', 'Controller');
App::uses('AppControllerTestCase', 'Test/Case/Controller');

/**
 * CoursesController Test Case
 */
class CoursesControllerTest extends AppControllerTestCase
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

	public function testAdminIndex()
	{
		$result = $this->testAction('/admin/courses/index/1', ['method' => 'get']);
		debug($result);
	}

	public function testAdminAdd()
	{
		$result = $this->testAction('/admin/courses/add/1', ['method' => 'get']);
		debug($result);
	}

	public function testAdminEdit()
	{
		$result = $this->testAction('/admin/courses/edit/1/1', ['method' => 'get']);
		debug($result);
	}

	public function testAdminDelete()
	{
		$result = $this->testAction('/admin/courses/index/1', ['method' => 'delete']);
		debug($result);
	}

	public function testAdminOrder()
	{
		$result = $this->testAction('/admin/courses/order/1', ['method' => 'post']);
		debug($result);
	}
}
