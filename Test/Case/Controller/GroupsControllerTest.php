<?php
App::uses('GroupsController', 'Controller');
App::uses('AppControllerTestCase', 'Test/Case/Controller');

/**
 * GroupsController Test Case
 */
class GroupsControllerTest extends AppControllerTestCase
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
		$result = $this->testAction('/admin/groups/index/1', ['method' => 'get']);
		debug($result);
	}

	public function testAdminAdd()
	{
		$result = $this->testAction('/admin/groups/add/1', ['method' => 'get']);
		debug($result);
	}

	public function testAdminEdit()
	{
		$result = $this->testAction('/admin/groups/edit/1/1', ['method' => 'get']);
		debug($result);
	}

	public function testAdminDelete()
	{
		$result = $this->testAction('/admin/groups/index/1', ['method' => 'delete']);
		debug($result);
	}
}
