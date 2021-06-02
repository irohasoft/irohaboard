<?php
/**
 * Record Fixture
 */
class RecordFixture extends CakeTestFixture
{
	/**
	 * Fields
	 *
	 * @var array
	 */
	public $fields = [
		'id' => ['type' => 'integer', 'null' => false, 'default' => null, 'length' => 8, 'unsigned' => false, 'key' => 'primary'],
		'course_id' => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 8, 'unsigned' => false, 'key' => 'index'],
		'user_id' => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 8, 'unsigned' => false],
		'content_id' => ['type' => 'integer', 'null' => false, 'default' => null, 'length' => 8, 'unsigned' => false],
		'full_score' => ['type' => 'integer', 'null' => true, 'default' => '0', 'length' => 3, 'unsigned' => false],
		'pass_score' => ['type' => 'integer', 'null' => true, 'default' => null, 'length' => 3, 'unsigned' => false],
		'score' => ['type' => 'integer', 'null' => true, 'default' => null, 'length' => 3, 'unsigned' => false],
		'is_passed' => ['type' => 'smallinteger', 'null' => true, 'default' => '0', 'length' => 1, 'unsigned' => false],
		'is_complete' => ['type' => 'smallinteger', 'null' => true, 'default' => null, 'length' => 1, 'unsigned' => false],
		'progress' => ['type' => 'smallinteger', 'null' => true, 'default' => '0', 'length' => 1, 'unsigned' => false],
		'understanding' => ['type' => 'smallinteger', 'null' => true, 'default' => null, 'length' => 1, 'unsigned' => false],
		'study_sec' => ['type' => 'integer', 'null' => true, 'default' => null, 'length' => 3, 'unsigned' => false],
		'created' => ['type' => 'datetime', 'null' => false, 'default' => null, 'key' => 'index'],
		'indexes' => [
			'PRIMARY' => ['column' => 'id', 'unique' => 1],
			'idx_course_user_content_id' => ['column' => ['course_id', 'user_id', 'content_id'], 'unique' => 0],
			'idx_created' => ['column' => 'created', 'unique' => 0]
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
			'content_id' => '1',
			'full_score' => '0',
			'pass_score' => null,
			'score' => null,
			'is_passed' => '-1',
			'is_complete' => '1',
			'progress' => '0',
			'understanding' => '4',
			'study_sec' => '1',
			'created' => '2021-05-21 18:40:08'
		],
		[
			'id' => '2',
			'course_id' => '1',
			'user_id' => '1',
			'content_id' => '2',
			'full_score' => '5',
			'pass_score' => '5',
			'score' => '0',
			'is_passed' => '0',
			'is_complete' => '1',
			'progress' => '0',
			'understanding' => null,
			'study_sec' => '10',
			'created' => '2021-05-21 18:40:21'
		],
	];

}
