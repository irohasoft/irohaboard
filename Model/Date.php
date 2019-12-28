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

class Date extends AppModel
{

  // The Associations below have been created with all possible keys, those
  // that are not needed can be removed

  /**
	 * hasMany associations
	 *
	 * @var array
	 */
	public $hasMany = array(
		'Lesson' => array(
				'className' => 'Lesson',
				'foreignKey' => 'date_id',
				'dependent' => true,
				'conditions' => '',
				'fields' => '',
				'order' => '',
				'limit' => '',
				'offset' => '',
				'exclusive' => '',
				'finderQuery' => '',
				'counterQuery' => ''
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

  public function getDate($date_id){
    $data = $this->find('first', array(
      'fields' => array('id', 'date'),
      'conditions' => array('id' => $date_id),
      'recursive' => -1
    ));
    $lesson_date = new DateTime($data['Date']['date']);
    $formatted_lesson_date = $lesson_date->format('Y年m月d日');
    return $formatted_lesson_date;
  }

}
