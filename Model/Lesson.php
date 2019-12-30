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

	// The Associations below have been created with all possible keys, those
	// that are not needed can be removed

	/**
	 * belongsTo associations
	 *
	 * @var array
	 */
	public $belongsTo = array(
			'Date' => array(
					'className' => 'Date',
					'foreignKey' => 'date_id',
					'conditions' => '',
					'fields' => '',
					'order' => ''
			)
	);

  /**
	 * 検索用
	 */
	public $actsAs = array(
		'Search.Searchable'
	);

	public $filterArgs = array(
	);

	public function findLessons($date_id){
		$data = $this->find('all', array(
			'fields' => array('id', 'start', 'end', 'period'),
			'conditions' => array('date_id' => $date_id),
			'recursive' => -1
		));
		return $data;
	}

}
