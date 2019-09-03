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
    $this->loadModel('Soap');
    $groupData = $this->Soap->findGroup();
    $this->set('groupData', $groupData);
  }
  public function admin_find_by_student(){
    $this->loadModel('User');
    $this->loadModel('Soap');

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

  public function admin_group_view($group_id){
    $this->loadModel('User');
    $this->loadModel('Soap');

    $user_list = $this->User->find('list');
    //$this->log($user_list);
    $this->set('user_list', $user_list);

    $members = $this->User->findAllStudentInGroup($group_id);
    //$this->log($members);
    $this->set('members', $members);

    // user_idとpic_pathの配列
    $group_pic_paths = $this->User->findGroupPicPaths($members);
    //$this->log($group_pic_paths);
    $this->set('group_pic_paths', $group_pic_paths);

    // user_idと学年(grade)の配列
    $members_grades = $this->User->findGroupGrade($members);
    $this->log($members_grades);
    $this->set('members_grades', $members_grades);

    // user_idと過去4回分SOAPの配列を作る
    $members_recent_soaps = $this->Soap->findGroupRecentSoaps($members);
    //$this->log($members_recent_soaps);
    $this->set('members_recent_soaps', $members_recent_soaps);
  }

  public function admin_student_view($user_id){
    $this->loadModel('User');
    $this->loadModel('Soap');

    $user_list = $this->User->find('list');
    //$this->log($user_list);
    $this->set('user_list', $user_list);
    $this->set('user_id', $user_id);

    $pic_path = $this->User->findUserPicPath($user_id);
    $this->set('pic_path', $pic_path);

    $grade = $this->User->findUserGrade($user_id);
    $this->set('grade', $grade);

    // 過去四回のSOAPを検索
    $recent_soaps = $this->Soap->findRecentSoaps($user_id);
    //$this->log($recent_soaps);
    $this->set('recent_soaps', $recent_soaps);
  }

}
?>
