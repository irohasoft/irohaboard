<?php
/**
 * RecordsQuestion Fixture
 */
class RecordsQuestionFixture extends CakeTestFixture
{
	/**
	 * Fields
	 *
	 * @var array
	 */
	public $fields = [
		'id' => ['type' => 'integer', 'null' => false, 'default' => null, 'length' => 8, 'unsigned' => false, 'key' => 'primary'],
		'record_id' => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 8, 'unsigned' => false],
		'question_id' => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 8, 'unsigned' => false],
		'answer' => ['type' => 'string', 'null' => true, 'default' => null, 'length' => 2000, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'],
		'correct' => ['type' => 'string', 'null' => true, 'default' => null, 'length' => 200, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'],
		'is_correct' => ['type' => 'smallinteger', 'null' => true, 'default' => '0', 'length' => 1, 'unsigned' => false],
		'score' => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 8, 'unsigned' => false],
		'created' => ['type' => 'datetime', 'null' => true, 'default' => null],
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
			'record_id' => '2',
			'question_id' => '2',
			'answer' => '1',
			'correct' => '2',
			'is_correct' => '0',
			'score' => '5',
			'created' => '2021-05-21 18:40:21'
		],
	];

}
