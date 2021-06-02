<?php
class DATABASE_CONFIG
{
	// iroha Board で使用するデータベース
	public $default = [
		'datasource' => 'Database/Mysql', // 変更しないでください
		'persistent' => true,
		'host' => 'localhost', // MySQLサーバのホスト名
		'login' => 'root', // ユーザ名
		'password' => '', // パスワード
		'database' => 'irohaboard', // データベース名
		'prefix' => 'ib_', // 変更しないでください
		'encoding' => 'utf8'
	];

/*
	// ユニットテストで使用するデータベース（注意：データが初期化されます）
	public $test = [
		'datasource' => 'Database/Mysql',
		'persistent' => true,
		'host' => 'localhost',
		'login' => 'root',
		'password' => '',
		'database' => 'irohaboard_test',
		'prefix' => 'ib_',
		'encoding' => 'utf8'
	];
*/
}
