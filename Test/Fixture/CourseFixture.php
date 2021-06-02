<?php
/**
 * Course Fixture
 */
class CourseFixture extends CakeTestFixture
{
	/**
	 * Fields
	 *
	 * @var array
	 */
	public $fields = [
		'id' => ['type' => 'integer', 'null' => false, 'default' => null, 'length' => 8, 'unsigned' => false, 'key' => 'primary'],
		'title' => ['type' => 'string', 'null' => false, 'length' => 200, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'],
		'introduction' => ['type' => 'text', 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'],
		'opened' => ['type' => 'datetime', 'null' => true, 'default' => null],
		'created' => ['type' => 'datetime', 'null' => false, 'default' => null],
		'modified' => ['type' => 'datetime', 'null' => true, 'default' => null],
		'deleted' => ['type' => 'datetime', 'null' => true, 'default' => null],
		'sort_no' => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 8, 'unsigned' => false],
		'comment' => ['type' => 'text', 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'],
		'user_id' => ['type' => 'integer', 'null' => false, 'default' => null, 'length' => 8, 'unsigned' => false],
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
			'title' => 'コース1',
			'introduction' => '',
			'opened' => null,
			'created' => '2021-05-21 18:36:40',
			'modified' => '2021-05-21 18:36:40',
			'deleted' => null,
			'sort_no' => '0',
			'comment' => '',
			'user_id' => '1'
		],
		[
			'id' => '2',
			'title' => 'コース2',
			'introduction' => '',
			'opened' => null,
			'created' => '2021-05-21 18:36:40',
			'modified' => '2021-05-21 18:36:40',
			'deleted' => null,
			'sort_no' => '0',
			'comment' => '',
			'user_id' => '1'
		],
	];

}
