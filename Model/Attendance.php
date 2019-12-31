<?php
/*
 * Ripple  Project
 *
 * @author        Enfu Guo
 * @copyright     NPO Organization uec support
 * @link          http://uecsupport.dip.jp/
 * @license       http://www.gnu.org/licenses/gpl-3.0.en.html GPL License
*/
App::uses('AppModel', 'Model');
App::import('Model', 'User');
App::import('Model', 'Date');
App::import('Model', 'Lesson');
/**
 * Attendance Model
 *
 * @property User $User
 * @property Date $Date
 * @property Lesson $Lesson
 */
class Attendance extends AppModel {


	// The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'user_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
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


	public function isExistAttendanceInfo($date_id){
		$data = $this->find('first', array(
			'conditions' => array('date_id' => $date_id)
		));
		if($data){ return true; }
		return false;
	}

	public function getAttendanceInfo(){
		//今日の日付を生成
    $today = date("Y-m-d");
		$this->set('today', $today);

		$conditions = [];
		$conditions['Attendance.created BETWEEN ? AND ?'] = array(
			$today,
			$today.' 23:59:59'
    );
		$attendanceinfo = $this->find('all',array(
			'conditions' => $conditions
		));

		$isInfoSet = $attendanceinfo != NULL ? true : false;

		return $isInfoSet;
	}

	public function setAttendanceInfo($date_id){
		//$user_model = new User();
		$user_list = $this->User->find('all',array(
			'conditions' => array(
				'User.role' => 'user'
			),
			'order' => 'User.id ASC'
		));
		foreach($user_list as $user){
			$init_info = [];
			$init_info = array(
				'user_id' => $user['User']['id'],
				'period'  => $user['User']['period'],
				'date_id' => $date_id,
				'status'  => 2
			);
			$this->create();
			$this->save($init_info);
		}
	}

	public function getAllTimeAttendances($user_id){
		$data = $this->find('all',array(
			'conditions' => array(
				'Attendance.user_id' => $user_id
			),
			'order' => array(
				'Date.date' => 'DESC'
			),
			'limit' => 8,
			'recursive' => 0
		));
		return $data;
	}

	public function findRecentAttendances($user_id){
		$today=date('Y-m-d');
		$data = $this->find('all',array(
			'conditions' => array(
				'Attendance.user_id' => $user_id,
				'Date.date < ?' => $today.' 23:59:59'
			),
			'order' => array(
				'Date.date' => 'DESC'
			),
			'limit' => 8,
			'recursive' => 0
		));
		//$this->log($data);
		return $data;
	}

	// user_idと過去8回分出欠席の配列を作る
	public function findAllUserAttendances(){
		$user_list = $this->User->find('all',array(
			'conditions' => array(
				'User.role' => 'user',
				''
			),
			'order' => 'User.id ASC'
		));
		$attendance_list = array();
		foreach($user_list as $user){
			$user_id = $user['User']['id'];
			$recent_attendance = $this->findRecentAttendances($user_id);
			$attendance_list += [$user_id => $recent_attendance];
		}
		return $attendance_list;
	}

	public function findAttendanceDate($attendance_id, $format_str='Y-m-d'){
		$data = $this->find('first', array(
			'conditions' => array('Attendance.id' => $attendance_id),
			'recursive' => 0
		));
		$attendance_date = (new DateTime($data['Date']['date']))->format($format_str);
		return $attendance_date;
	}

	public function findLoginTime($attendance_id, $format_str='H:i:s'){
		$data = $this->find('first', array(
			'fields' => array('id', 'login_time'),
			'conditions' => array('id' => $attendance_id),
			'recursive' => -1
		));
		$login_time = $data['Attendance']['login_time'];
		if($login_time == null){return null;}
		return (new DateTime($login_time))->format($format_str);
	}

	public function calcLateTime($date_id, $login_time){
		$this->Date   = new Date();
		$this->Lesson = new Lesson();

		$login_time = (int)strtotime($login_time);
		$lesson_date = $this->Date->getDate($date_id);
		$lessons = $this->Lesson->findLessons($date_id);

		foreach($lessons as $lesson){
			$period = $lesson['Lesson']['period'];
			$start  = (int)strtotime($lesson_date.' '.$lesson['Lesson']['start']);
			$end    = (int)strtotime($lesson_date.' '.$lesson['Lesson']['end']);

			if($login_time <= $start){
				$late_time = 0;
				return $late_time;
			} else if($login_time <= $end){
				$late_time = (int)(($login_time - $start) / 60);
				return $late_time;
			}
		}
		$late_time = null;
		return $late_time;
	}
}
