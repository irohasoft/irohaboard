<?php
App::uses('InfosController', 'Controller');
App::uses('AppControllerTestCase', 'Test/Case/Controller');

/**
 * InfosController Test Case
 */
class InfosControllerTest extends AppControllerTestCase
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
		$result = $this->testAction('/infos/index/1', ['method' => 'get']);
		debug($result);
	}

	public function testView()
	{
		$result = $this->testAction('/infos/view/1', ['method' => 'get']);
		debug($result);
	}

	public function testAdminIndex()
	{
		$result = $this->testAction('/admin/infos/index/1', ['method' => 'get']);
		debug($result);
	}

	public function testAdminAdd()
	{
		$result = $this->testAction('/admin/infos/add/1', ['method' => 'get']);
		debug($result);
	}

	public function testAdminEdit()
	{
		$result = $this->testAction('/admin/infos/edit/1/1', ['method' => 'get']);
		debug($result);
	}

	public function testAdminDelete()
	{
		$result = $this->testAction('/admin/infos/index/1', ['method' => 'delete']);
		debug($result);
	}
}
