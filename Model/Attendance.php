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
App::import('Model','User');
/**
 * Attendance Model
 *
 * @property User $User
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


	public function getAttendanceInfo(){
		//今日の日付を生成
    $today = date("Y/m/d");
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

	public function setAttendanceInfo(){
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
				'period' => $user['User']['period'],
				'status' => 0
			);
			$this->create();
			$this->save($init_info);
		}
	}

	public function findRecentAttendances($user_id){
		$data = $this->find('all', array(
			'conditions' => array(
				'Attendance.user_id' => $user_id
			),
			'order' => array(
				'Attendance.created' => 'DESC'
			),
			'limit' => 8,
			'recursive' => -1
		));
		return $data;
	}

	// user_idと過去8回分出欠席の配列を作る
	public function findAllUserAttendances(){

		$user_list = $this->User->find('all',array(
			'conditions' => array(
				'User.role' => 'user'
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

	public function getLastClassDate($format_str='Y-m-d'){
		$data = $this->find('first', array(
			'fields' => array('created'),
			'order' => 'created ASC',
			'recursive' => -1
		));
		$last_class_date = (new DateTime($data['Attendance']['created']))->format($format_str);
		return $last_class_date;
	}

	public function findAttendanceDate($attendance_id, $format_str='Y-m-d'){
		$data = $this->find('first', array(
			'fields' => array('id', 'created'),
			'conditions' => array(
				'id' => $attendance_id
			),
			'recursive' => -1
		));
		$created = new DateTime($data['Attendance']['created']);
		$attendance_date = $created->format($format_str);
		return $attendance_date;
	}

	public function findLoginTime($attendance_id, $format_str='H:i:s'){
		$data = $this->find('first', array(
			'fields' => array('id', 'login_time'),
			'conditions' => array(
				'id' => $attendance_id
			),
			'recursive' => -1
		));
		$login_time = $data['Attendance']['login_time'];
		if($login_time == null){return null;}
		return (new DateTime($login_time))->format($format_str);
	}
}
