<?php
App::uses('UsersCoursesController', 'Controller');
App::uses('AppControllerTestCase', 'Test/Case/Controller');

/**
 * UsersCoursesController Test Case
 */
class UsersCoursesControllerTest extends AppControllerTestCase
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

	public function testIndex()
	{
		$result = $this->testAction('/users_courses', ['method' => 'get']);
		debug($result);
	}
}
