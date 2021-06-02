<?php
/**
 * CakeSession Fixture
 */
class CakeSessionFixture extends CakeTestFixture
{
	/**
	 * Fields
	 *
	 * @var array
	 */
	public $fields = [
		'id' => ['type' => 'string', 'null' => false, 'key' => 'primary', 'collate' => 'utf8_general_ci', 'charset' => 'utf8'],
		'data' => ['type' => 'text', 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'],
		'expires' => ['type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false],
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
	];

}
