<?php
App::uses('SettingsController', 'Controller');
App::uses('AppControllerTestCase', 'Test/Case/Controller');

/**
 * SettingsController Test Case
 */
class SettingsControllerTest extends AppControllerTestCase
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
		$result = $this->testAction('/admin/settings', ['method' => 'get']);
		debug($result);
	}
}
