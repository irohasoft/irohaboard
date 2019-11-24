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

    $this->set(compact("period1_members","period2_members"));

    $last_class_date = $this->Attendance->getLastClassDate('m月d日');
    $this->set('last_class_date', $last_class_date);

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

    $date_list = array();
    $date_list_search = array();

    foreach($attendance_list as $info){
      foreach($info as $row){
        $created = new DateTime($row['Attendance']['created']);
        $created_day = $created->format('m月d日');
        $date_list[] = $created_day;

        $created_day = $created->format('m-d');
        $date_list_search[] = $created_day;
      }
      break;
    }

    foreach($attendance_list as $info){
      foreach($info as $row){
        $tmp = new DateTime($row['Attendance']['created']);
        $last_day = $tmp->format('Y-m-d');
        break;
      }
      break;
    }

    $conditions['Attendance.created BETWEEN ? AND ?'] = array(
			$last_day,
			$last_day.' 23:59:59'
    );

    $rows = $this->Attendance->find('all',array(
      'conditions' => $conditions,
      'order' => 'User.username ASC'
    ));

    $now = 1;
    $abs_1 = $att_1 = $abs_2 = $att_2 = $cnt_1 = $cnt_2 = 0;
    foreach($rows as $row){
      $now = preg_match("/^2[0-9]/",$row['User']['username']) ? 2 : 1;
      //$this->log($row);
      //$this->log($now);
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

    //標準時間を設定
    $period1_from	= (isset($this->request->data['Attendance']['period1_from'])) ?
			$this->request->data['Attendance']['period1_from'] :
        array( 'hour' => '9', 'min' => '0');

    $period1_to	= (isset($this->request->data['Attendance']['period1_to'])) ?
      $this->request->data['Attendance']['period1_to'] :
      array( 'hour' => '10', 'min' => '30');

    $period2_from	= (isset($this->request->data['Attendance']['period2_from'])) ?
			$this->request->data['Attendance']['period2_from'] :
        array( 'hour' => '11', 'min' => '0');

    $period2_to	= (isset($this->request->data['Attendance']['period2_to'])) ?
      $this->request->data['Attendance']['period2_to'] :
      array( 'hour' => '12', 'min' => '30');

    //$this->log($period1_from);
    $this->set('period1_from',$period1_from);
    $this->set('period1_to',$period1_to);
    $this->set('period2_from',$period2_from);
    $this->set('period2_to',$period2_to);

    $this->set('members', $members);
    $this->set('name_list', $name_list);
    $this->set('username_list', $username_list);
    $this->set('attendance_list', $attendance_list);
    $this->set('date_list', $date_list);

    if ($this->request->is(array('post','put'))){
      $request_data = $this->request->data;
      $target_date = $date_list_search[$request_data['Attendance']['target_date']];

      foreach ($attendance_list as $attendance_info){
        foreach ($attendance_info as $row){
          if(strpos($row['Attendance']['created'],(string)$target_date) === false){

          }else{
            $save_data = $row['Attendance'];
            $this->log($save_data);
            if($save_data['login_time'] !== null){
              $login_time = (int)strtotime($save_data['login_time']);

              $created_y = new DateTime($save_data['created']);
              $created_year = $created_y->format('y-m-d');
              $period1_from_standard = (int)strtotime($created_year.' '.$period1_from['hour'].':'.$period1_from['min']);
              $period1_to_standard = (int)strtotime($created_year.' '.$period1_to['hour'].':'.$period1_to['min']);
              $period2_from_standard = (int)strtotime($created_year.' '.$period2_from['hour'].':'.$period2_from['min']);


              if($login_time <= $period1_from_standard){
                $save_data['late_time'] = 0;
              }else if($login_time <= $period1_to_standard){
                $save_data['late_time'] = (int)(($login_time - $period1_from_standard) / 60);
              }else if($login_time <= $period2_from_standard){
                $save_data['late_time'] = 0;
              }else{
                $save_data['late_time'] = (int)(($login_time - $period2_from_standard) / 60);
              }

              //$this->log($save_data);
              $this->Attendance->save($save_data);
              $attendance_list = $this->Attendance->findAllUserAttendances();
              $this->set('attendance_list',$attendance_list);
            }
          }
        }
      }
    }
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
      if($edited_status){
        $edited_hour   = $request_data['Attendance']['edited_login_time']['hour'];
        $edited_minute = $request_data['Attendance']['edited_login_time']['min'];
        $login_date = $this->Attendance->findAttendanceDate($attendance_id);
        $edited_login_time = $login_date.' '.$edited_hour.':'.$edited_minute.':00';
      }else{
        $edited_login_time = null;
      }
      $this->Attendance->read(null, $attendance_id);
      $this->Attendance->set(array(
        'status'     => $edited_status,
        'login_time' => $edited_login_time
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
