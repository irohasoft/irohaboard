<?php
/**
 * RecordsQuestion Fixture
 */
class RecordsQuestionFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 8, 'unsigned' => false, 'key' => 'primary'),
		'record_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 8, 'unsigned' => false),
		'question_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 8, 'unsigned' => false),
		'answer' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 200, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'correct' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 200, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'is_correct' => array('type' => 'integer', 'null' => true, 'default' => '0', 'length' => 1, 'unsigned' => false),
		'score' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 8, 'unsigned' => false),
		'created' => array('type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'MyISAM')
	);

/**
 * Records
 *
 * @var array
 */
	public $records = array(
		array(
			'id' => 1,
			'record_id' => 1,
			'question_id' => 1,
			'answer' => 'Lorem ipsum dolor sit amet',
			'correct' => 'Lorem ipsum dolor sit amet',
			'is_correct' => 1,
			'score' => 1,
			'created' => '2016-01-04 08:07:27'
		),
	);

}
