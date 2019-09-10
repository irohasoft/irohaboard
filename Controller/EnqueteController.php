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
App::uses('Sanitize', 'Utility');

class EnqueteController extends AppController{
  public $helpers = array('Html', 'Form');
  public function admin_index(){

  }
  public function admin_find_by_group(){
    $groupData = $this->Soap->findGroup();
    $this->set('groupData', $groupData);
  }
  public function admin_find_by_student(){
    $this->loadModel('User');

    if($this->request->is('post')){
      $conditions = $this->request->data;
      $username = $conditions['Search']['username'];
      $name = $conditions['Search']['name'];

      //$this->log($name);
      $user_list = $this->Soap->findUserList($username, $name);

    }else{
      $user_list = $this->Soap->getUserList();
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

      $soap_tmp = Sanitize::clean($soaps, array('encode' => false));
      //$this->log($soap_tmp);

      $created = $today_date['year']."-".$today_date['month']."-".$today_date['day'];
      foreach($soaps as &$soap){
        if($soap['S'] == '' || $soap['O'] == ''){
          continue;
        }

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
}


?>
