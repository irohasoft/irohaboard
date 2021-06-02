<?php
App::uses('RecordsController', 'Controller');
App::uses('AppControllerTestCase', 'Test/Case/Controller');

/**
 * RecordsController Test Case
 */
class RecordsControllerTest extends AppControllerTestCase
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
		$result = $this->testAction('/admin/records/index/1', ['method' => 'get']);
		debug($result);
	}

	public function testAdd()
	{
		$result = $this->testAction('/records/add/1/1/1/1', ['method' => 'get']);
		debug($result);
	}
}
