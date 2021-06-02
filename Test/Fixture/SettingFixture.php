<?php
/**
 * Setting Fixture
 */
class SettingFixture extends CakeTestFixture
{
	/**
	 * Fields
	 *
	 * @var array
	 */
	public $fields = [
		'id' => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'],
		'setting_key' => ['type' => 'string', 'null' => false, 'default' => null, 'length' => 100, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'],
		'setting_name' => ['type' => 'string', 'null' => false, 'default' => null, 'length' => 100, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'],
		'setting_value' => ['type' => 'string', 'null' => false, 'default' => null, 'length' => 1000, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'],
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
			'setting_key' => 'title',
			'setting_name' => 'システム名',
			'setting_value' => 'iroha Board'
		],
		[
			'id' => '2',
			'setting_key' => 'copyright',
			'setting_name' => 'コピーライト',
			'setting_value' => 'Copyright (C) 2016-2021 iroha Soft Co.,Ltd. All rights reserved.'
		],
		[
			'id' => '3',
			'setting_key' => 'color',
			'setting_name' => 'テーマカラー',
			'setting_value' => '#337ab7'
		],
		[
			'id' => '4',
			'setting_key' => 'information',
			'setting_name' => 'お知らせ',
			'setting_value' => '全体のお知らせを表示します。
このお知らせは管理機能の「システム設定」にて変更可能です。'
		],
	];

}
