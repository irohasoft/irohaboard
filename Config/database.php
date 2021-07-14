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
}
