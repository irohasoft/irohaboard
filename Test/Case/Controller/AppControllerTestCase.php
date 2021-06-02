<?php
App::uses('CakeSession', 'Model/Datasource');

class AppControllerTestCase extends ControllerTestCase
{
	public function setUp()
	{
		parent::setUp();
		
		$user = [
			'id' => 1,
			'username' => 'user1',
			'name' => 'ユーザ1',
			'role' => 'admin'
		];
		CakeSession::write('Auth.User', $user);
		
		$result = $this->testAction('/update/index/test', ['method' => 'get']);
	}
	
	public function testDummy()
	{
	}
}
