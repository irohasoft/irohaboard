<?php
/**
 * Ripple  Project
 *
 * @author        Osamu Miyazawa
 * @copyright     NPO Organization uec support
 * @link          http://uecsupport.dip.jp/
 * @license       http://www.gnu.org/licenses/gpl-3.0.en.html GPL License
 */

App::uses('AppModel', 'Model');

class Lesson extends AppModel
{
  /**
	 * 検索用
	 */
	public $actsAs = array(
		'Search.Searchable'
	);

	public $filterArgs = array(
	);

}
