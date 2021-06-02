<?php
App::uses('Setting', 'Model');

/**
 * Setting Test Case
 */
class SettingTest extends CakeTestCase
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

	/**
	 * setUp method
	 *
	 * @return void
	 */
	public function setUp()
	{
		parent::setUp();
		$this->Setting = ClassRegistry::init('Setting');
	}

	/**
	 * tearDown method
	 *
	 * @return void
	 */
	public function tearDown()
	{
		unset($this->Setting);
		parent::tearDown();
	}

	public function testGetSettings()
	{
		$result = $this->Setting->getSettings();
		$this->assertEquals($result['title'], 'iroha Board');
	}

	public function testSetSettings()
	{
		$result = $this->Setting->getSettings();
		$result['title'] = 'test';
		
		// システム名を変更
		$this->Setting->setSettings($result);
		
		$result = $this->Setting->find()->where(['setting_key' => 'title'])->all();
		$this->assertEquals($result[0]['Setting']['setting_value'], 'test');
	}

}
