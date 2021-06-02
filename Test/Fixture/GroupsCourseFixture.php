<?php
/**
 * GroupsCourse Fixture
 */
class GroupsCourseFixture extends CakeTestFixture
{
	/**
	 * Fields
	 *
	 * @var array
	 */
	public $fields = [
		'id' => ['type' => 'integer', 'null' => false, 'default' => null, 'length' => 8, 'unsigned' => false, 'key' => 'primary'],
		'group_id' => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 8, 'unsigned' => false],
		'course_id' => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 8, 'unsigned' => false],
		'started' => ['type' => 'date', 'null' => true, 'default' => null],
		'ended' => ['type' => 'date', 'null' => true, 'default' => null],
		'created' => ['type' => 'datetime', 'null' => true, 'default' => null],
		'modified' => ['type' => 'datetime', 'null' => true, 'default' => null],
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
			'group_id' => '1',
			'course_id' => '1',
			'started' => null,
			'ended' => null,
			'created' => null,
			'modified' => null,
			'comment' => null
		],
	];

}
