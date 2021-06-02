<?php
/**
 * Content Fixture
 */
class ContentFixture extends CakeTestFixture
{
	/**
	 * Fields
	 *
	 * @var array
	 */
	public $fields = [
		'id' => ['type' => 'integer', 'null' => false, 'default' => null, 'length' => 8, 'unsigned' => false, 'key' => 'primary'],
		'course_id' => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 8, 'unsigned' => false],
		'user_id' => ['type' => 'integer', 'null' => false, 'default' => null, 'length' => 8, 'unsigned' => false],
		'title' => ['type' => 'string', 'null' => false, 'length' => 200, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'],
		'url' => ['type' => 'string', 'null' => true, 'default' => null, 'length' => 200, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'],
		'file_name' => ['type' => 'string', 'null' => true, 'default' => null, 'length' => 200, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'],
		'kind' => ['type' => 'string', 'null' => false, 'length' => 20, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'],
		'body' => ['type' => 'text', 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'],
		'timelimit' => ['type' => 'integer', 'null' => true, 'default' => null, 'length' => 8, 'unsigned' => false],
		'pass_rate' => ['type' => 'integer', 'null' => true, 'default' => null, 'length' => 8, 'unsigned' => false],
		'question_count' => ['type' => 'integer', 'null' => true, 'default' => null, 'length' => 8, 'unsigned' => false],
		'wrong_mode' => ['type' => 'integer', 'null' => false, 'default' => '1', 'length' => 1, 'unsigned' => false],
		'status' => ['type' => 'integer', 'null' => false, 'default' => '1', 'length' => 1, 'unsigned' => false],
		'opened' => ['type' => 'datetime', 'null' => true, 'default' => null],
		'created' => ['type' => 'datetime', 'null' => false, 'default' => null],
		'modified' => ['type' => 'datetime', 'null' => true, 'default' => null],
		'deleted' => ['type' => 'datetime', 'null' => true, 'default' => null],
		'sort_no' => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 8, 'unsigned' => false],
		'comment' => ['type' => 'text', 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'],
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
			'course_id' => '1',
			'user_id' => '1',
			'title' => '学習コンテンツ1',
			'url' => '',
			'file_name' => '',
			'kind' => 'html',
			'body' => '<p>test</p>',
			'timelimit' => null,
			'pass_rate' => null,
			'question_count' => null,
			'wrong_mode' => '2',
			'status' => '1',
			'opened' => null,
			'created' => '2021-05-21 18:36:58',
			'modified' => '2021-05-21 18:36:58',
			'deleted' => null,
			'sort_no' => '1',
			'comment' => ''
		],
		[
			'id' => '2',
			'course_id' => '1',
			'user_id' => '1',
			'title' => 'テスト1',
			'url' => '',
			'file_name' => '',
			'kind' => 'test',
			'body' => '',
			'timelimit' => '10',
			'pass_rate' => '100',
			'question_count' => '1',
			'wrong_mode' => '2',
			'status' => '1',
			'opened' => null,
			'created' => '2021-05-21 18:37:29',
			'modified' => '2021-05-21 18:37:29',
			'deleted' => null,
			'sort_no' => '2',
			'comment' => ''
		],
	];

}
