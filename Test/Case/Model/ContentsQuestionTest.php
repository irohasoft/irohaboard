<?php
App::uses('ContentsQuestion', 'Model');

/**
 * ContentsQuestion Test Case
 */
class ContentsQuestionTest extends CakeTestCase
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
		$this->ContentsQuestion = ClassRegistry::init('ContentsQuestion');
	}

	/**
	 * tearDown method
	 *
	 * @return void
	 */
	public function tearDown()
	{
		unset($this->ContentsQuestion);
		parent::tearDown();
	}

	public function testSetOrder()
	{
		$id_list = [2, 1];
		
		$this->ContentsQuestion->setOrder($id_list);
		
		$list = $this->ContentsQuestion->find()->where(['ContentsQuestion.id IN ' => $id_list])->order('ContentsQuestion.sort_no')->all();
		
		$result = [];
		
		foreach($list as $row)
		{
			$result[] = $row['ContentsQuestion']['id'];
		}
		
		$this->assertEquals($id_list, $result);
	}

	public function testGetNextSortNo()
	{
		$next_no = $this->ContentsQuestion->getNextSortNo(2);
		
		$this->assertEquals($next_no, 3);
	}
}
