<?php
App::uses('ContentsQuestionsController', 'Controller');
App::uses('AppControllerTestCase', 'Test/Case/Controller');

/**
 * ContentsQuestionsController Test Case
 */
class ContentsQuestionsControllerTest extends AppControllerTestCase
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
		$result = $this->testAction('/contents_questions/index/1', ['method' => 'get']);
		debug($result);
	}

	public function testRecord()
	{
		$result = $this->testAction('/contents_questions/record/1/1', ['method' => 'get']);
		debug($result);
	}

	public function testAdminRecord()
	{
		$result = $this->testAction('/admin/contents_questions/index/1', ['method' => 'get']);
		debug($result);
	}

	public function testAdminIndex()
	{
		$result = $this->testAction('/admin/contents_questions/index/1', ['method' => 'get']);
		debug($result);
	}

	public function testAdminAdd()
	{
		$result = $this->testAction('/admin/contents_questions/index/1', ['method' => 'get']);
		debug($result);
	}

	public function testAdminEdit()
	{
		$result = $this->testAction('/admin/contents_questions/index/1', ['method' => 'get']);
		debug($result);
	}

	public function testAdminDelete()
	{
		$result = $this->testAction('/admin/contents_questions/index/1', ['method' => 'delete']);
		debug($result);
	}

	public function testAdminOrder()
	{
		$result = $this->testAction('/admin/contents_questions/order/1', ['method' => 'post']);
		debug($result);
	}
}
