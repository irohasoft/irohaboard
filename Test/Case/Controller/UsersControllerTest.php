<?php
App::uses('UsersController', 'Controller');
App::uses('AppControllerTestCase', 'Test/Case/Controller');

/**
 * UsersController Test Case
 */
class UsersControllerTest extends AppControllerTestCase
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

	public function testIogin()
	{
		$result = $this->testAction('/users/login', ['method' => 'get']);
		debug($result);
	}

	public function testLogout()
	{
		/*
		$result = $this->testAction('/users/logout', ['method' => 'get']);
		debug($result);
		*/
	}

	public function testAdminIndex()
	{
		$result = $this->testAction('/admin/users/index/1', ['method' => 'get']);
		debug($result);
	}

	public function testAdminAdd()
	{
		$result = $this->testAction('/admin/users/add/1', ['method' => 'get']);
		debug($result);
	}

	public function testAdminEdit()
	{
		$result = $this->testAction('/admin/users/edit/1/1', ['method' => 'get']);
		debug($result);
	}

	public function testAdminDelete()
	{
		$result = $this->testAction('/admin/users/index/1', ['method' => 'delete']);
		debug($result);
	}

	public function testAdminClear()
	{
		$result = $this->testAction('/admin/users/clear/1', ['method' => 'delete']);
		debug($result);
	}

	public function testSetting()
	{
		$result = $this->testAction('/users/setting', ['method' => 'get']);
		debug($result);
	}

	public function testAdminSetting()
	{
		$result = $this->testAction('/admin/users/setting', ['method' => 'get']);
		debug($result);
	}

	public function testAdminLogin()
	{
		$result = $this->testAction('/admin/users/login', ['method' => 'get']);
		debug($result);
	}

	public function testAdminLogout()
	{
		/*
		$result = $this->testAction('/admin/users/logout', ['method' => 'get']);
		debug($result);
		*/
	}

	public function testAdminImport()
	{
		$result = $this->testAction('/admin/users/import', ['method' => 'get']);
		debug($result);
	}

	public function testAdminExport()
	{
		/*
		$result = $this->testAction('/admin/users/export', ['method' => 'get']);
		debug($result);
		*/
	}
}
