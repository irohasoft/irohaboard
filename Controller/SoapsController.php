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

class SoapsController extends AppController{
  public $helpers = array('Html', 'Form');

  public function admin_index(){}

  public function admin_find_by_group(){
    $this->loadModel('Group');
    $groupData = $this->Group->findGroup();
    $this->set('groupData', $groupData);
  }

  public function admin_find_by_student(){
    $this->loadModel('User');

    if($this->request->is('post')){
      $conditions = $this->request->data;
      $username = $conditions['Search']['username'];
      $name = $conditions['Search']['name'];

      $user_list = $this->User->findUserList($username, $name);

    }else{
      $user_list = $this->User->getUserList();
    }
    $this->set('user_list', $user_list);
  }
  /*
    @param int $group_id グループID
    グループでSOAPを記入する
  */
  public function admin_group_edit($group_id){
    $this->loadModel('Course');
    $this->loadModel('User');
    $this->loadModel('Enquete');
    $this->loadModel('Attendance');
    $this->loadModel('Date');

    //日付リスト
    $today_date = (isset($this->request->query['today_date'])) ?
      $this->request->query['today_date']:
        array('year' => date('Y'), 'month' => date('m'), 'day' => date('d'));

    $this->set('today_date',$today_date);

    //提出したアンケートを検索（今日の日付）

    $conditions = [];
    $conditions['Enquete.group_id'] = $group_id;


    $conditions['Enquete.created BETWEEN ? AND ?'] = array(
			$today_date['year']."-".$today_date['month']."-".$today_date['day'],
			$today_date['year']."-".$today_date['month']."-".$today_date['day'].' 23:59:59'
    );


    $enquete_history = $this->Enquete->find('all',array(
      'conditions' => $conditions
    ));

    $enquete_inputted = [];
    foreach($enquete_history as $history){
      $his_user_id = $history['Enquete']['user_id'];
      $enquete_inputted["$his_user_id"] = $history['Enquete'];
    }

    $this->set('enquete_inputted',$enquete_inputted);

    //メンバーリスト

    $user_list = $this->User->find('list');
    $this->set('user_list', $user_list);

    //グループ内のメンバーを探す
    $members = $this->User->findAllStudentInGroup($group_id);
    $this->set('members',$members);

    // user_idとpic_pathの配列
    $group_pic_paths = $this->User->findGroupPicPaths($members);
    $this->set('group_pic_paths', $group_pic_paths);

    //グループ一覧を作り，配列の形を整形する
    $group_list = $this->Group->find('list');
    $this->set('group_list',$group_list);


    //入力したSOAPを検索（前回の授業から）
    $conditions = [];

    $attendance_info = $this->Attendance->find('first',array(
      'conditions' => array(

      ),
      'order' => 'Attendance.created DESC'
    ));

    $today=date('Y-m-d');
    $fdate = date('w') == 0 ? $today : date('Y-m-d', strtotime(" last sunday ",strtotime($today)));

    $lecture_date_info = $this->Date->find('first',array(
      'fields' => array('id','date'),
      'conditions' => array(
        'date >= ' =>  $fdate
      ),
      'recursive' => -1
    ));

    $created_day = $lecture_date_info['Date']['date'];

    $edate = date('Y-m-d', strtotime(" next saturday ",strtotime($created_day)));

    $conditions['Soap.created BETWEEN ? AND ?'] = array(
      $created_day,
			$edate.' 23:59:59'
    );


    $soap_history = $this->Soap->find('all',array(
      'conditions' => $conditions
    ));
    $soap_inputted = [];
    foreach($soap_history as $history){
      $his_user_id = $history['Soap']['user_id'];
      $soap_inputted["$his_user_id"] = $history['Soap'];
    }
    $this->set('soap_inputted',$soap_inputted);


    //教材現状
    $course_list = $this->Course->find('list');
    $this->set('course_list', $course_list);

    //登録
    if($this->request->is('post')){
      $soaps = $this->request->data;

      $created = $today_date['year']."-".$today_date['month']."-".$today_date['day'];

      foreach($soaps as &$soap){
        if($soap['S'] == '' && $soap['O'] == '' && $soap['A'] == '' && $soap['P'] == ''){
          continue;
        }
        $inputed = $soap['today_date'];
        $input_date = $inputed['year']."-".$inputed['month']."-".$inputed['day'];
        $input_date = date('w',strtotime($input_date)) == 0 ? $input_date : date('Y-m-d', strtotime(" last sunday ",strtotime($input_date)));

        $soap['created'] = $input_date.date(' H:i:s');

        if($this->Soap->save($soap)){
          $this->Soap->create(false); //これがないと，ループ内での保存はできない
          continue;
        }
        $this->Flash->error(__('提出は失敗しました、もう一回やってください。'));
      }
      $this->Flash->success(__('提出しました、ありがとうございます'));
      return $this->redirect(array('action' => 'index'));
    }
  }

  public function admin_student_edit($user_id){
    $this->loadModel('Course');
    $this->loadModel('User');
    $this->loadModel('Enquete');
    $this->loadModel('Attendance');
    $this->loadModel('Date');


    $pic_path = $this->User->findUserPicPath($user_id);
    $this->set('pic_path', $pic_path);

    //日付リスト
    $today_date = (isset($this->request->query['today_date'])) ?
      $this->request->query['today_date']:
        array('year' => date('Y'), 'month' => date('m'), 'day' => date('d'));
    $this->set('today_date',$today_date);

    //提出したアンケートを検索（今日の日付）
    $conditions = [];
    $conditions['Enquete.user_id'] = $user_id;
    $conditions['Enquete.created BETWEEN ? AND ?'] = array(
			$today_date['year']."-".$today_date['month']."-".$today_date['day'],
			$today_date['year']."-".$today_date['month']."-".$today_date['day'].' 23:59:59'
    );
    $enquete_history = $this->Enquete->find('all',array(
      'conditions' => $conditions
    ));

    $enquete_inputted = [];
    foreach($enquete_history as $history){
      $his_user_id = $history['Enquete']['user_id'];
      $enquete_inputted["$his_user_id"] = $history['Enquete'];
    }

    $this->set('enquete_inputted',$enquete_inputted);
    //メンバーリスト

    $user_list = $this->User->find('list');
    $this->set('user_list', $user_list);

    //メンバーのグループを探す
    $group_id = $this->User->findUserGroup($user_id);

    $this->set('user_id', $user_id);

    //グループ一覧を作り，配列の形を整形する
    $group_list = $this->Group->find('list');
    $this->set('group_list',$group_list);

    $this->set('today_date',$today_date);

    //入力したSOAPを検索（先週の授業から）
    $conditions = [];
    $conditions['Soap.user_id'] = $user_id;

    $attendance_info = $this->Attendance->find('first',array(
      'conditions' => array(

      ),
      'order' => 'Attendance.created DESC'
    ));

    $today=date('Y-m-d');
    $fdate = date('w') == 0 ? $today : date('Y-m-d', strtotime(" last sunday ",strtotime($today)));

    $lecture_date_info = $this->Date->find('first',array(
      'fields' => array('id','date'),
      'conditions' => array(
        'date >= ' =>  $fdate
      ),
      'recursive' => -1
    ));

    $created_day = $lecture_date_info['Date']['date'];

    $edate = date('Y-m-d', strtotime(" next saturday ",strtotime($created_day)));

    $conditions['Soap.created BETWEEN ? AND ?'] = array(
      $created_day,
			$edate.' 23:59:59'
    );

    $soap_history = $this->Soap->find('all',array(
      'conditions' => $conditions
    ));

    $soap_inputted = [];
    foreach($soap_history as $history){
      $his_user_id = $history['Soap']['user_id'];
      $soap_inputted["$his_user_id"] = $history['Soap'];
      $group_id = $history['Soap']['group_id'];
    }
    $this->set('soap_inputted',$soap_inputted);
    $this->set('group_id',$group_id);

    //教材現状
    $course_list = $this->Course->find('list');
    $this->set('course_list', $course_list);

    //登録
    if($this->request->is('post')){
      $this->loadModel('Record');
      $soaps = $this->request->data;
      $created = $today_date['year']."-".$today_date['month']."-".$today_date['day'];
      foreach($soaps as &$soap){
        if($soap['S'] == '' && $soap['O'] == '' && $soap['A'] == '' && $soap['P'] == ''){
          continue;
        }
        // SOAP記入日で最後に勉強した教材を取得
        $inputed = $soap['today_date'];
        $input_date = $inputed['year']."-".$inputed['month']."-".$inputed['day'];
        $soap['studied_content'] = $this->Record->studiedContentOnTheDate($soap['user_id'], $input_date);
        $soap['created'] = $input_date.date(' H:i:s');
        if($this->Soap->save($soap)){
          $this->Soap->create(false); //これがないと，ループ内での保存はできない
          continue;
        }
        $this->Flash->error(__('提出は失敗しました、もう一回やってください。'));
      }
      $this->Flash->success(__('提出しました、ありがとうございます'));
      return $this->redirect(array('action' => 'index'));
    }
  }
}

?>
