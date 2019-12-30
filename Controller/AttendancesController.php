<?php
/*
 * Ripple  Project
 *
 * @author        Enfu Guo
 * @copyright     NPO Organization uec support
 * @link          http://uecsupport.dip.jp/
 * @license       http://www.gnu.org/licenses/gpl-3.0.en.html GPL License
*/

App::uses('AppController',   'Controller');
App::uses('RecordsQuestion', 'RecordQuestion');
App::uses('UsersGroup',      'UsersGroup');
App::uses('Course',          'Course');
App::uses('User',            'User');
App::uses('Group',           'Group');
App::uses('Enquete',         'Enquete');
App::uses('Attendance',      'Attendance');
App::uses('Date',            'Date');


class AttendancesController extends AppController{
  public $helpers = array('Html', 'Form');

  public $components = array(
    'Paginator',
    'Search.Prg'
);

  //public $presetVars = true;

  public $paginate = array(
    'maxLimit' => 1000
  );

  public $presetVars = array(
    array(
      'name' => 'name',
      'type' => 'value',
      'field' => 'User.name'
    ),
    array(
      'name' => 'username',
      'type' => 'like',
      'field' => 'User.username'
    )
  );

  public function index(){
  }

  public function records(){
  }

  public function admin_index(){
    $this->loadModel('User');
    $this->loadModel('Date');

    $members = $this->User->getAllStudent();
    $period1_members = $this->User->find('all',array(
      'conditions' => array(
        'User.role' => 'user',
        'User.period' => 0
      ),
      'order' => 'User.username ASC'
    ));

    $period2_members = $this->User->find('all',array(
      'conditions' => array(
        'User.role' => 'user',
        'User.period' => 1
      ),
      'order' => 'User.username ASC'
    ));

    $this->set(compact("period1_members", "period2_members"));

    $attendance_list = $this->Attendance->findAllUserAttendances();
    //$this->log($attendance_list);
    $name_list = $this->User->find('list',array(
      'fields' => array(
        'User.id',
        'User.name'
      ),
      'conditions' => array(
        'role' => 'user'
      ),
      'order' => 'User.id ASC'
    ));
    $username_list = $this->User->find('list',array(
      'fields' => array(
        'User.id',
        'User.username'
      ),
      'conditions' => array(
        'role' => 'user'
      ),
      'order' => 'User.id ASC'
    ));

    $date_list = $this->Date->getDateListUntilToday('m月d日');

    $last_day = $this->Date->getLastClassDate('Y-m-d');

    $last_class_date_id = $this->Date->getLastClassId();
    $rows = $this->Attendance->find('all', array(
      'conditions' => array('date_id' => $last_class_date_id),
      'order' => 'User.username ASC'
    ));

    $now = 1;
    $abs_1 = $att_1 = $abs_2 = $att_2 = $cnt_1 = $cnt_2 = 0;
    foreach($rows as $row){
      $now = preg_match("/^2[0-9]/",$row['User']['username']) ? 2 : 1;
      if($now == 1){
        if($row['Attendance']['status'] == 1){
          $att_1++;
        }else{
          $abs_1++;
        }
        $cnt_1++;
      }else{
        if($row['Attendance']['status'] == 1){
          $att_2++;
        }else{
          $abs_2++;
        }
        $cnt_2++;
      }
    }

    $this->set(compact("abs_1", "abs_2", "att_1", "att_2", "cnt_1", "cnt_2", "last_day"));

    $this->set('members', $members);
    $this->set('name_list', $name_list);
    $this->set('username_list', $username_list);
    $this->set('attendance_list', $attendance_list);
    $this->set('date_list', $date_list);
  }

  public function admin_edit($user_id, $attendance_id){
    $this->loadModel('User');
    $name = $this->User->find('first', array(
      'fields' => array('id', 'name'),
			'conditions' => array(
				'id'     => $user_id
			),
			'recursive' => -1
		))['User']['name'];
    $this->set('name', $name);

    $date = $this->Attendance->findAttendanceDate($attendance_id, 'm月d日');
    $this->set('date', $date);

    $attendance_status = $this->Attendance->find('first', array(
      'fields' => array('status'),
      'conditions' => array(
        'id' => $attendance_id
      ),
      'recursive' => -1
    ))['Attendance']['status'];
    $this->set('attendance_status', $attendance_status);

    $login_time = $this->Attendance->findLoginTime($attendance_id);
    $this->set('login_time', $login_time);

    if($this->request->is('post')){
      $request_data = $this->request->data;
      $edited_status = $request_data['Attendance']['status'];
      if($edited_status == 0 or $edited_status == 2){  // 欠席または未定
        $edited_login_time = null;
        $late_time = null;
      }else{
        $edited_hour   = $request_data['Attendance']['edited_login_time']['hour'];
        $edited_minute = $request_data['Attendance']['edited_login_time']['min'];
        $login_date = $this->Attendance->findAttendanceDate($attendance_id);
        $edited_login_time = $login_date.' '.$edited_hour.':'.$edited_minute.':00';

        $date_id = $this->Attendance->find('first', array(
          'fields' => array('date_id'),
          'conditions' => array('id' => $attendance_id),
          'recursive' => -1
        ))['Attendance']['date_id'];
        $late_time = $this->Attendance->calcLateTime($date_id, $edited_login_time);

        if($late_time === null){
          $this->Flash->error(__('ログイン時間がどの時限とも整合しません。ログイン時間を確認してください。また、授業時間が正しく設定されているか確認してください。'));
          return;
        }
      }

      $this->Attendance->read(null, $attendance_id);
      $this->Attendance->set(array(
        'status'     => $edited_status,
        'login_time' => $edited_login_time,
        'late_time'  => $late_time
      ));
      if($this->Attendance->save()){
        $this->Flash->success(__('編集が完了しました。'));
        return $this->redirect(array('action' => 'index'));
      }
      $this->Flash->error(__('編集に失敗しました、もう一回やってください。'));
    }
  }
}
?>
