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

	public function isDuringOnlineLessonHour($date_id){
		$this->Date = new Date();

		$lesson_date = $this->Date->getDate($date_id);
		$now_time = (int)strtotime(date('H:i:s'));
		$lessons = $this->findLessons($date_id);

		foreach($lessons as $lesson){
			$period = $lesson['Lesson']['period'];
			$start                  = (int)strtotime($lesson_date.' '.$lesson['Lesson']['start']);
			$half_hour_before_start = (int)strtotime($lesson_date.' '.$lesson['Lesson']['start'].' -30 minute');
			$half_hour_after_start  = (int)strtotime($lesson_date.' '.$lesson['Lesson']['start'].' +30 minute');
			$end                    = (int)strtotime($lesson_date.' '.$lesson['Lesson']['end']);
			if($half_hour_before_start <= $now_time &&  $now_time < $end){ return true; }
		}
		return false;
	}

	public function checkLessonCode($input_code){
		$today = date("Y-m-d");
		$data = $this->find('all', array(
			'fields' => array('Lesson.id', 'Lesson.code'),
			'conditions' => array('Date.date' => $today),
			'recursive' => 0
		));
		foreach($data as $datum){
			if($datum['Lesson']['code'] == $input_code){ return true; }
		}
		return false;
	}

}
