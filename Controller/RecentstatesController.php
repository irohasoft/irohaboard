<?php
/**
 * Ripple Project
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

  public function admin_index(){}

  public function admin_find_by_group(){
    $this->loadModel('Group');
    $this->loadModel('Soap');
    $groupData = $this->Group->findGroup();
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
      $user_list = $this->User->findUserList($username, $name);

    }else{
      $user_list = $this->User->getUserList();
    }
    $this->set('user_list', $user_list);
  }

  public function admin_student_view($user_id){
    $this->loadModel('User');
    $this->loadModel('Group');
    $this->loadModel('Course');
    $this->loadModel('Content');
    $this->loadModel('Soap');

    $this->set('user_id', $user_id);

    $name_list = $this->User->find('list');
    $this->set('name_list', $name_list);

    $username_list = $this->User->find('list', array(
      'fields' => 'User.username'
    ));
    $this->set('username_list', $username_list);

    $group_list = $this->Group->find('list');
    $this->set('group_list', $group_list);

    $content_list = $this->Content->find('list');
    $this->set('content_list', $content_list);

    $pic_path = $this->User->findUserPicPath($user_id);
    $this->set('pic_path', $pic_path);

    $grade = $this->User->findUserGrade($user_id);
    $this->set('grade', $grade);

    // 受講コース情報の取得
    $cleared_rates = $this->Course->findClearedRate($user_id);
    $this->set('cleared_rates', $cleared_rates);

    // 過去四回のSOAPを検索
    $recent_soaps = $this->Soap->findRecentSoaps($user_id);
    $this->set('recent_soaps', $recent_soaps);
  }

  public function admin_group_view($group_id){
    $this->loadModel('User');
    $this->loadModel('Group');
    $this->loadModel('Course');
    $this->loadModel('Content');
    $this->loadModel('Soap');

    $name_list = $this->User->find('list');
    $this->set('name_list', $name_list);

    $username_list = $this->User->find('list', array(
      'fields' => 'User.username'
    ));
    $this->set('username_list', $username_list);

    $group_list = $this->Group->find('list');
    $this->set('group_list', $group_list);

    $content_list = $this->Content->find('list');
    $this->set('content_list', $content_list);

    $members = $this->User->findAllStudentInGroup($group_id);
    $this->set('members', $members);

    // user_idとpic_pathの配列
    $group_pic_paths = $this->User->findGroupPicPaths($members);
    $this->set('group_pic_paths', $group_pic_paths);

    // user_idと学年(grade)の配列
    $members_grades = $this->User->findGroupGrade($members);
    $this->set('members_grades', $members_grades);

    // user_idとコース名・合格率の配列
    $members_cleared_rates = $this->Course->findGroupClearedRate($members);
    $this->set('members_cleared_rates', $members_cleared_rates);

    // user_idと過去4回分SOAPの配列を作る
    $members_recent_soaps = $this->Soap->findGroupRecentSoaps($members);
    $this->set('members_recent_soaps', $members_recent_soaps);
  }

  public function admin_all_view(){
    $this->loadModel('User');
    $this->loadModel('Group');
    $this->loadModel('Course');
    $this->loadModel('Content');
    $this->loadModel('Soap');

    $name_list = $this->User->find('list');
    $this->set('name_list', $name_list);

    $username_list = $this->User->find('list', array(
      'fields' => 'User.username'
    ));
    $this->set('username_list', $username_list);

    $group_list = $this->Group->find('list');
    $this->set('group_list', $group_list);

    $content_list = $this->Content->find('list');
    $this->set('content_list', $content_list);

    $members = $this->User->getAllStudent();
    $this->set('members', $members);

    // user_idとpic_pathの配列
    $group_pic_paths = $this->User->findGroupPicPaths($members);
    $this->set('group_pic_paths', $group_pic_paths);

    // user_idと学年(grade)の配列
    $members_grades = $this->User->findGroupGrade($members);
    $this->set('members_grades', $members_grades);

    // user_idとコース名・合格率の配列
    $members_cleared_rates = $this->Course->findGroupClearedRate($members);
    $this->set('members_cleared_rates', $members_cleared_rates);

    // user_idと過去4回分SOAPの配列を作る
    $members_recent_soaps = $this->Soap->findGroupRecentSoaps($members);
    $this->set('members_recent_soaps', $members_recent_soaps);
  }

}
