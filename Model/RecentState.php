<?php
/*
 * Ripple  Project
 *
 * @author        Osamu Miyazawa
 * @copyright     NPO Organization uec support
 * @link          http://uecsupport.dip.jp/
 * @license       http://www.gnu.org/licenses/gpl-3.0.en.html GPL License
 */

App::uses('AppModel', 'Model');

/**
 * Record Model
 *
 * @property Group $Group
 * @property Course $Course
 * @property User $User
 * @property Content $Content
 * @property Soap $Soap
 */
class RecentState extends AppModel
{

  /**
	 * 検索用
	 */
	public $actsAs = array(
		'Search.Searchable'
	);

	public $filterArgs = array(
	);

  public function findGroup(){
    $sql = "SELECT id,title FROM ib_groups";
    $data = $this->query($sql);
    //$this->log($data);
    return $data;
  }

  public function findAllUserInGroup( $group_id ){
    $sql = "SELECT id, user_id, group_id FROM ib_users_groups
            WHERE group_id = $group_id";
    $data = $this->query($sql);
    return $data;
  }

  public function getGroupList(){
    $sql = "SELECT id,title FROM ib_groups ";
    $data = $this->query($sql);
    return $data;
  }

  public function getUserList(){
    $sql = "SELECT id, username, name FROM ib_users WHERE role = 'user' ORDER BY username ASC";
    $data = $this->query($sql);
    return $data;
  }

  public function findUserGroup($user_id){
    $sql = "SELECT group_id FROM ib_users_groups WHERE user_id = $user_id";
    $data = $this->query($sql);
    //$this->log($data);
    return $data[0]['ib_users_groups']['group_id'];
  }

  public function findUserList($username, $name){
    if($username != null){
      $username = "%".$username."%";
    }
    if($name != null){
      $name = "%".$name."%";
    }
    if($username == null && $name == null){
      $username = "%".$username."%";
      $name = "%".$name."%";
    }
    $sql = "SELECT id,username, name FROM ib_users
        WHERE (username LIKE '$username'
          OR name LIKE '$name')
          AND (role = 'user')
        GROUP BY username
        ORDER BY username ASC";
    $data = $this->query($sql);
    return $data;
  }

}
