<?php
/**
 * User Fixture
 */
class UserFixture extends CakeTestFixture
{
	/**
	 * Fields
	 *
	 * @var array
	 */
	public $fields = [
		'id' => ['type' => 'integer', 'null' => false, 'default' => null, 'length' => 20, 'unsigned' => false, 'key' => 'primary'],
		'username' => ['type' => 'string', 'null' => false, 'length' => 50, 'key' => 'unique', 'collate' => 'utf8_general_ci', 'charset' => 'utf8'],
		'password' => ['type' => 'string', 'null' => false, 'length' => 200, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'],
		'name' => ['type' => 'string', 'null' => false, 'length' => 50, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'],
		'role' => ['type' => 'string', 'null' => false, 'length' => 20, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'],
		'email' => ['type' => 'string', 'null' => false, 'length' => 50, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'],
		'comment' => ['type' => 'text', 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'],
		'last_logined' => ['type' => 'datetime', 'null' => true, 'default' => null],
		'started' => ['type' => 'datetime', 'null' => true, 'default' => null],
		'ended' => ['type' => 'datetime', 'null' => true, 'default' => null],
		'created' => ['type' => 'datetime', 'null' => true, 'default' => null],
		'modified' => ['type' => 'datetime', 'null' => true, 'default' => null],
		'deleted' => ['type' => 'datetime', 'null' => true, 'default' => null],
		'indexes' => [
			'PRIMARY' => ['column' => 'id', 'unique' => 1],
			'login_id' => ['column' => 'username', 'unique' => 1]
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
			'username' => 'root',
			'password' => 'fc3214a1e8b4582361ec9d2414ffdc52d6c117b2',
			'name' => 'root',
			'role' => 'admin',
			'email' => 'info@example.com',
			'comment' => '',
			'last_logined' => '2021-05-21 19:18:36',
			'started' => null,
			'ended' => null,
			'created' => '2021-05-21 18:36:10',
			'modified' => '2021-05-21 19:18:36',
			'deleted' => null
		],
	];

}
