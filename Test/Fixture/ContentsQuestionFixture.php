<?php
/**
 * ContentsQuestion Fixture
 */
class ContentsQuestionFixture extends CakeTestFixture
{
	/**
	 * Fields
	 *
	 * @var array
	 */
	public $fields = [
		'id' => ['type' => 'integer', 'null' => false, 'default' => null, 'length' => 8, 'unsigned' => false, 'key' => 'primary'],
		'content_id' => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 8, 'unsigned' => false],
		'question_type' => ['type' => 'string', 'null' => false, 'length' => 20, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'],
		'title' => ['type' => 'string', 'null' => false, 'length' => 200, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'],
		'body' => ['type' => 'text', 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'],
		'image' => ['type' => 'string', 'null' => true, 'default' => null, 'length' => 200, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'],
		'options' => ['type' => 'string', 'null' => true, 'default' => null, 'length' => 2000, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'],
		'correct' => ['type' => 'string', 'null' => false, 'length' => 200, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'],
		'score' => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 8, 'unsigned' => false],
		'explain' => ['type' => 'text', 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'],
		'comment' => ['type' => 'text', 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'],
		'created' => ['type' => 'datetime', 'null' => false, 'default' => null],
		'modified' => ['type' => 'datetime', 'null' => true, 'default' => null],
		'sort_no' => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 8, 'unsigned' => false],
		'indexes' => [
			'PRIMARY' => ['column' => 'id', 'unique' => 1]
		],
		'tableParameters' => ['charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB']
	];

	/**
	 * Records
	 *
	 * @var array
	 */
	public $records = [
		[
			'id' => '1',
			'content_id' => '2',
			'question_type' => '',
			'title' => '問題1',
			'body' => '<p>問題文1</p>',
			'image' => null,
			'options' => '選択肢1|選択肢2',
			'correct' => '1',
			'score' => '5',
			'explain' => '<p>解説1</p>',
			'comment' => '',
			'created' => '2021-05-21 18:38:26',
			'modified' => '2021-05-21 18:38:26',
			'sort_no' => '1'
		],
		[
			'id' => '2',
			'content_id' => '2',
			'question_type' => '',
			'title' => '問題2',
			'body' => '<p>問題2</p>',
			'image' => null,
			'options' => '選択肢1|選択肢2',
			'correct' => '2',
			'score' => '5',
			'explain' => '<p>解説2</p>',
			'comment' => '',
			'created' => '2021-05-21 18:38:51',
			'modified' => '2021-05-21 18:39:00',
			'sort_no' => '2'
		],
	];

}
