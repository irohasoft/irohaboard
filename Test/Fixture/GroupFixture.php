<?php
/**
 * Group Fixture
 */
class GroupFixture extends CakeTestFixture
{
	/**
	 * Fields
	 *
	 * @var array
	 */
	public $fields = [
		'id' => ['type' => 'integer', 'null' => false, 'default' => null, 'length' => 8, 'unsigned' => false, 'key' => 'primary'],
		'title' => ['type' => 'string', 'null' => false, 'length' => 200, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'],
		'comment' => ['type' => 'text', 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'],
		'created' => ['type' => 'datetime', 'null' => false, 'default' => null],
		'modified' => ['type' => 'datetime', 'null' => true, 'default' => null],
		'deleted' => ['type' => 'datetime', 'null' => true, 'default' => null],
		'status' => ['type' => 'integer', 'null' => false, 'default' => '1', 'length' => 1, 'unsigned' => false],
		'logo' => ['type' => 'string', 'null' => true, 'default' => null, 'length' => 200, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'],
		'copyright' => ['type' => 'string', 'null' => true, 'default' => null, 'length' => 200, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'],
		'module' => ['type' => 'string', 'null' => true, 'default' => '00000000', 'length' => 50, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'],
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
			'title' => 'グループ1',
			'comment' => '',
			'created' => '2021-05-21 18:36:28',
			'modified' => '2021-05-21 19:20:42',
			'deleted' => null,
			'status' => '1',
			'logo' => null,
			'copyright' => null,
			'module' => '00000000'
		],
	];

}
