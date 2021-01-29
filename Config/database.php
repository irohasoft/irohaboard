<?php
/**
 * iroha Board Project
 *
 * @author        Kotaro Miura
 * @copyright     2015-2021 iroha Soft, Inc. (https://irohasoft.jp)
 * @link          https://irohaboard.irohasoft.jp
 * @license       https://www.gnu.org/licenses/gpl-3.0.en.html GPL License
 */

class DATABASE_CONFIG {

	public $default = [
		'datasource' => 'Database/Mysql',
		'persistent' => true,
		'host' => 'localhost',
		'login' => 'root',
		'password' => '',
		'database' => 'irohaboard',
		'prefix' => 'ib_',
		'encoding' => 'utf8'
	];
}
