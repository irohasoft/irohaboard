<?php
/**
 * iroha Board Project
 *
 * @author        Kotaro Miura
 * @copyright     2015-2021 iroha Soft, Inc. (https://irohasoft.jp)
 * @link          https://irohaboard.irohasoft.jp
 * @license       https://www.gnu.org/licenses/gpl-3.0.en.html GPL License
 */

App::uses('Model', 'Model');

/**
 * Application model for Cake.
 *
 * Add your application-wide methods in the class below, your models
 * will inherit them.
 *
 * @package app.Model
 */
class AppModel extends Model
{
	/**
	 * 英数字チェック（マルチバイト対応）
	 */
	public function alphaNumericMB($check)
	{
		$value = array_values($check);
		$value = $value[0];
		
		return preg_match('/^[a-zA-Z0-9]+$/', $value);
	}
}
