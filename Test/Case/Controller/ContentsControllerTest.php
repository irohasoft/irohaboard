<?php
App::uses('ContentsController', 'Controller');
App::uses('AppControllerTestCase', 'Test/Case/Controller');

/**
 * ContentsController Test Case
 */
class ContentsControllerTest extends AppControllerTestCase
{
	/**
	 * Fixtures
	 *
	 * @var array
	 */
	public $fixtures = [
		'app.course',
		'app.content',
		'app.contents_question',
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

	// コントローラーのテスト
	// https://book.cakephp.org/2/ja/development/testing.html#id23
	
	public function testIndex()
	{
		$result = $this->testAction('/contents/index/1', ['method' => 'get']);
		debug($result);
	}

	public function testView()
	{
		$result = $this->testAction('/contents/view/1', ['method' => 'get']);
		debug($result);
	}

	public function testAdminIndex()
	{
		$result = $this->testAction('/admin/contents/index/1', ['method' => 'get']);
		debug($result);
	}

	public function testAdminAdd()
	{
		$result = $this->testAction('/admin/contents/add/1', ['method' => 'get']);
		debug($result);
	}

	public function testAdminEdit()
	{
		$result = $this->testAction('/admin/contents/edit/1/1', ['method' => 'get']);
		debug($result);
	}

	public function testAdminDelete()
	{
		$result = $this->testAction('/admin/contents/delete/1', ['method' => 'delete']);
		debug($result);
	}

	public function testAdminUpload()
	{
		$result = $this->testAction('/admin/contents/upload/file', ['method' => 'get']);
		debug($result);
	}

	public function testAdminUploadImage()
	{
		/*
		$result = $this->testAction('/admin/contents/upload_image', ['method' => 'post', 'data' => $data]);
		debug($result);
		*/
	}

	public function testAdminOrder()
	{
		$result = $this->testAction('/admin/contents/order/1', ['method' => 'post']);
		debug($result);
	}

	public function testAdminRecord()
	{
		$result = $this->testAction('/admin/contents/record/1/1', ['method' => 'get']);
		debug($result);
	}

	public function testAdminCopy()
	{
		$result = $this->testAction('/admin/contents/copy/1/1', ['method' => 'get']);
		debug($result);
	}
}
