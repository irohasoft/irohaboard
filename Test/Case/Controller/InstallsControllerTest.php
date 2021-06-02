<?php
App::uses('InstallsController', 'Controller');
App::uses('AppControllerTestCase', 'Test/Case/Controller');

/**
 * InstallsController Test Case
 */
class InstallsControllerTest extends AppControllerTestCase
{
	/**
	 * Fixtures
	 *
	 * @var array
	 */
	public $fixtures = [
	];

	public function testIndex()
	{
		$data = [
			'User' => [
				'password'  => 'password',
				'password2' => 'password',
			]
		];

		$result = $this->testAction('/install/index/test', ['method' => 'post', 'data' => $data]);
		debug($result);
	}

	public function testInstalled()
	{
		$result = $this->testAction('/install/installed', ['method' => 'get']);
		debug($result);
	}

	public function testComplete()
	{
		$result = $this->testAction('/install/complete', ['method' => 'get']);
		debug($result);
	}

	public function testError()
	{
		$result = $this->testAction('/install/error', ['method' => 'get']);
		debug($result);
	}
}
