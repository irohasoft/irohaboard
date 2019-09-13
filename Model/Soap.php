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

/**
 * Record Model
 *
 * @property Group $Group
 * @property Course $Course
 * @property User $User
 * @property Content $Content
 */
class Soap extends AppModel
{

	/**
	 * Validation rules
	 *
	 * @var array
	 */
	public $validate = array(
			'course_id' => array(
					'numeric' => array(
							'rule' => array(
									'numeric'
							)
					// 'message' => 'Your custom message here',
					// 'allowEmpty' => false,
					// 'required' => false,
					// 'last' => false, // Stop validation after this rule
					// 'on' => 'create', // Limit validation to 'create' or
					// 'update' operations
										)
			),
			'user_id' => array(
					'numeric' => array(
							'rule' => array(
									'numeric'
							)
					// 'message' => 'Your custom message here',
					// 'allowEmpty' => false,
					// 'required' => false,
					// 'last' => false, // Stop validation after this rule
					// 'on' => 'create', // Limit validation to 'create' or
					// 'update' operations
										)
			),
			'content_id' => array(
					'numeric' => array(
							'rule' => array(
									'numeric'
							)
					// 'message' => 'Your custom message here',
					// 'allowEmpty' => false,
					// 'required' => false,
					// 'last' => false, // Stop validation after this rule
					// 'on' => 'create', // Limit validation to 'create' or
					// 'update' operations
										)
			)
	);

	// The Associations below have been created with all possible keys, those
	// that are not needed can be removed
	public $hasMany = array(
			'RecordsQuestion' => array(
					'className' => 'RecordsQuestion',
					'foreignKey' => 'record_id',
					'dependent' => false,
					'conditions' => '',
					'fields' => '',
					'order' => '',
					'limit' => '',
					'offset' => '',
					'exclusive' => '',
					'finderQuery' => '',
					'counterQuery' => ''
			)
	);

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
			'Group' => array(
					'className' => 'Group',
					'foreignKey' => 'group_id',
					'conditions' => '',
					'fields' => '',
					'order' => ''
			),
			'Course' => array(
					'className' => 'Course',
					'foreignKey' => 'current_status',
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

  public function findGroup(){
    $sql = "SELECT id,title FROM ib_groups";
    $data = $this->query($sql);
    //$this->log($data);
    return $data;
  }

  public function findAllUserInGroup( $group_id ){
    $sql = "SELECT id, group_id FROM ib_users
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
    $sql = "SELECT id, username, name, pic_path FROM ib_users WHERE role = 'user' ORDER BY username ASC";
    $data = $this->query($sql);
    return $data;
  }

  public function findUserGroup($user_id){
    $sql = "SELECT group_id FROM ib_users WHERE id = $user_id";
    $data = $this->query($sql);
    //$this->log($data);
    return $data[0]['ib_users']['group_id'];
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
    $sql = "SELECT id, username, name, pic_path FROM ib_users
        WHERE (username LIKE '$username'
          OR name LIKE '$name')
          AND (role = 'user')
        GROUP BY username
        ORDER BY username ASC";
    $data = $this->query($sql);
    return $data;
  }

	public function findRecentSoaps($user_id){
		$sql = "SELECT id, user_id, current_status, S, O, A, P, created FROM ib_soaps
				WHERE (user_id = '$user_id')
				ORDER BY created DESC
				LIMIT 4";
		$data = $this->query($sql);
		return $data;
	}

	// user_idと過去4回分SOAPの配列を作る
	public function findGroupRecentSoaps($members){
		if (empty($members)){ return NULL; }
		$members_recent_soaps = array();
		foreach($members as $member):
			$user_id = $member['ib_users']['id'];
			$recent_soaps = $this->findRecentSoaps($user_id);
			$members_recent_soaps += [$user_id => $recent_soaps];
		endforeach;
		return $members_recent_soaps;
	}

	public function getOldestCreatedYear(){
		$sql = "SELECT MIN(created) AS oldest_created_time FROM ib_soaps";
		$data = $this->query($sql);
		$oldest_created_year = (new DateTime($data[0]['ib_soaps']['oldest_created_time']))->format('Y');
		//$this->log($oldest_created_year);
		return $oldest_created_year;
	}

}
