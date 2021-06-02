<?php
App::uses('UpdatesController', 'Controller');
App::uses('AppControllerTestCase', 'Test/Case/Controller');

/**
 * UpdatesController Test Case
 */
class UpdatesControllerTest extends AppControllerTestCase
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
		$result = $this->testAction('/update/', ['method' => 'get']);
		debug($result);
	}

	public function testError()
	{
		$result = $this->testAction('/update/error', ['method' => 'get']);
		debug($result);
	}
}
