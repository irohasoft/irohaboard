<?php
/**
 * Info Fixture
 */
class InfoFixture extends CakeTestFixture
{
	/**
	 * Fields
	 *
	 * @var array
	 */
	public $fields = [
		'id' => ['type' => 'integer', 'null' => false, 'default' => null, 'length' => 8, 'unsigned' => false, 'key' => 'primary'],
		'title' => ['type' => 'string', 'null' => false, 'default' => null, 'length' => 200, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'],
		'body' => ['type' => 'text', 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'],
		'opened' => ['type' => 'datetime', 'null' => true, 'default' => null],
		'closed' => ['type' => 'datetime', 'null' => true, 'default' => null],
		'created' => ['type' => 'datetime', 'null' => true, 'default' => null],
		'modified' => ['type' => 'datetime', 'null' => false, 'default' => null],
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
			'title' => 'お知らせ1',
			'body' => '',
			'opened' => null,
			'closed' => null,
			'created' => '2021-05-21 18:39:31',
			'modified' => '2021-05-21 18:39:31',
			'user_id' => '1'
		],
	];

}
