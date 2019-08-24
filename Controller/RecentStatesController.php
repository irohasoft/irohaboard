<?php
/*
 * Ripple  Project
 *
 * @author        Osamu Miyazawa
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
App::uses('Soap',            'Soap');

class RecentStatesController extends AppController{
  public $helpers = array('Html', 'Form');
  public function admin_index(){

  }
  public function admin_find_by_group(){
    $groupData = $this->RecentState->findGroup();
    $this->set('groupData', $groupData);
  }
  public function admin_find_by_student(){
    //$this->log("Studnet");
    $this->loadModel('User');

    if($this->request->is('post')){
      $conditions = $this->request->data;
      //$this->log($conditions);
      $username = $conditions['Search']['username'];
      //$this->log($username);
      $name = $conditions['Search']['name'];
      $this->log($name);
      $user_list = $this->RecentState->findUserList($username, $name);
      //$this->log($user_list);
    }else{
      $user_list = $this->RecentState->getUserList();
      //$this->log($user_list);
      //$user_list = $this->User->find('list');
    }
    $this->set('user_list', $user_list);
  }

  public function admin_group_view($group_id){
  }

  public function admin_student_view($user_id){
  }
  /*
    @param int $group_id グループID
    グループでSOAPを記入する
  */
  /*
  public function admin_group_edit($group_id){
    $this->loadModel('Course');
    $this->loadModel('User');
    //メンバーリスト

    $user_list = $this->User->find('list');
    //$this->log($user_list);
    $this->set('user_list', $user_list);

    //グループ内のメンバーを探す
    $members = $this->Soap->findAllUserInGroup($group_id);
    $this->set('members',$members);

    //グループ一覧を作り，配列の形を整形する

    //$this->log($this->Group->find('list'));
    $group_list = $this->Group->find('list');
    $this->set('group_list',$group_list);
    //日付リスト
    $today_date = (isset($this->request->query['today_date'])) ?
      $this->request->query['today_date']:
        array('year' => date('Y'), 'month' => date('m'), 'day' => date('d'));

    $this->set('today_date',$today_date);

    //教材現状
    $course_list = $this->Course->find('list');
    $this->set('course_list', $course_list);
    //$this->log($current_status);

    //登録
    if($this->request->is('post')){
      $soaps = $this->request->data;
      $created = $today_date['year']."-".$today_date['month']."-".$today_date['day'];
      foreach($soaps as &$soap){
        if($this->Soap->save($soap)){
          $this->Soap->create(false); //これがないと，ループ内での保存はできない
          continue;
        }
        $this->Flash->error(__('提出は失敗しました、もう一回やってください。'));
      }
      $this->Flash->success(__('提出しました、ありがとうございます'));
      return $this->redirect(array('action' => 'index'));
    }
  }*/
  /*
  public function admin_student_edit($user_id){
    $this->loadModel('Course');
    $this->loadModel('User');
    //メンバーリスト

    $user_list = $this->User->find('list');
    //$this->log($user_list);
    $this->set('user_list', $user_list);

    //メンバーのグループを探す
    $group_id = $this->Soap->findUserGroup($user_id);
    $this->log($group_id);
    $this->set('group_id',$group_id);
    $this->set('user_id',$user_id);
    //グループ一覧を作り，配列の形を整形する

    //$this->log($this->Group->find('list'));
    $group_list = $this->Group->find('list');
    $this->set('group_list',$group_list);
    //日付リスト
    $today_date = (isset($this->request->query['today_date'])) ?
      $this->request->query['today_date']:
        array('year' => date('Y'), 'month' => date('m'), 'day' => date('d'));

    $this->set('today_date',$today_date);

    //教材現状
    $course_list = $this->Course->find('list');
    $this->set('course_list', $course_list);
    //$this->log($current_status);

    //登録
    if($this->request->is('post')){
      $soaps = $this->request->data;
      //$this->log($soaps);
      $created = $today_date['year']."-".$today_date['month']."-".$today_date['day'];
      foreach($soaps as &$soap){
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
  */
}

?>
