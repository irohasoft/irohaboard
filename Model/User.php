<?php
/**
 * iroha Board Project
 *
 * @author        Kotaro Miura
 * @copyright     2015-2016 iroha Soft, Inc. (http://irohasoft.jp)
 * @link          http://irohaboard.irohasoft.jp
 * @license       http://www.gnu.org/licenses/gpl-3.0.en.html GPL License
 */

App::uses('AppModel', 'Model');
App::uses('BlowfishPasswordHasher', 'Controller/Component/Auth');

/**
 * User Model
 *
 * @property Group $Group
 * @property Content $Content
 * @property Course $Course
 * @property Group $Group
 */
class User extends AppModel
{
	public $order = "User.name";

	public $validate = array(
		'username' => array(
				array(
						'rule' => 'isUnique',
						'message' => 'ログインIDが重複しています'
				),
				array(
						'rule' => 'alphaNumeric',
						'message' => 'ログインIDは英数字で入力して下さい'
				),
				array(
						'rule' => array(
								'between',
								4,
								32
						),
						'message' => 'ログインIDは4文字以上32文字以内で入力して下さい'
				)
		),
		'name' => array(
			'notBlank' => array(
				'rule' => array(
						'notBlank'
				),
				'message' => '氏名が入力されていません'
			)
		),
		'role' => array(
			'notBlank' => array(
				'rule' => array(
						'notBlank'
				),
				'message' => '権限が指定されていません'
			)
		),
		'password' => array(
				array(
						'rule' => 'alphaNumeric',
						'message' => 'パスワードは英数字で入力して下さい'
				),
				array(
						'rule' => array(
								'between',
								4,
								32
						),
						'message' => 'パスワードは4文字以上32文字以内で入力して下さい'
				)
		),
		'new_password' => array(
				array(
						'rule' => 'alphaNumeric',
						'message' => 'パスワードは英数字で入力して下さい',
						'allowEmpty' => true
				),
				array(
						'rule' => array(
								'between',
								4,
								32
						),
						'message' => 'パスワードは4文字以上32文字以内で入力して下さい',
						'allowEmpty' => true
				)
		)
	);

	// The Associations below have been created with all possible keys, those
	// that are not needed can be removed

	/**
	 * belongsTo associations
	 *
	 * @var array
	 */
	public $belongsTo = array(
	);

	/**
	 * hasMany associations
	 *
	 * @var array
	 */
	public $hasMany = array(
			'Content' => array(
					'className' => 'Content',
					'foreignKey' => 'user_id',
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
	 * hasAndBelongsToMany associations
	 *
	 * @var array
	 */
	public $hasAndBelongsToMany = array(
			'Course' => array(
					'className' => 'Course',
					'joinTable' => 'users_courses',
					'foreignKey' => 'user_id',
					'associationForeignKey' => 'course_id',
					'unique' => 'keepExisting',
					'conditions' => '',
					'fields' => '',
					'order' => '',
					'limit' => '',
					'offset' => '',
					'finderQuery' => ''
			),
			'Group' => array(
					'className' => 'Group',
					'joinTable' => 'users_groups',
					'foreignKey' => 'user_id',
					'associationForeignKey' => 'group_id',
					'unique' => 'keepExisting',
					'conditions' => '',
					'fields' => '',
					'order' => '',
					'limit' => '',
					'offset' => '',
					'finderQuery' => ''
	 		)
	);

	public function beforeSave($options = array())
	{
		if (isset($this->data[$this->alias]['password']))
		{
			$this->data[$this->alias]['password'] = AuthComponent::password($this->data[$this->alias]['password']);
		}
		return true;
	}

	/**
	 * 検索用
	 */
	public $actsAs = array(
		'Search.Searchable'
	);

	public $filterArgs = array(
		'username' => array(
			'type' => 'like',
			'field' => 'User.username'
		),
		'name' => array(
			'type' => 'like',
			'field' => 'User.name'
		),
		'course_id' => array(
			'type' => 'like',
			'field' => 'course_id'
		),
	);

	/**
	 * 学習履歴の削除
	 *
	 * @param int array $user_id 学習履歴を削除するユーザのID
	 */
	public function deleteUserRecords($user_id)
	{
		$sql = 'DELETE FROM ib_records_questions WHERE record_id IN (SELECT id FROM ib_records WHERE user_id = :user_id)';

		$params = array(
			'user_id' => $user_id,
		);

		$this->query($sql, $params);

		App::import('Model', 'Record');
		$this->Record = new Record();
		$this->Record->deleteAll(array('Record.user_id' => $user_id), false);
	}

	public function getUserList(){
		$data = $this->find('all', array(
			'fields' => array(
				'id', 'username', 'name', 'pic_path'
			),
			'conditions' => array('role' => 'user'),
			'order' => array('username' => 'ASC'),
			'recursive' => -1
		));
		$this->log($data);
		return $data;
	}

	// ユーザ名で検索
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

		$data = $this->find('all', array(
			'fields' => array(
				'id', 'username', 'name', 'pic_path'
			),
			'conditions' => array(
				'role' => 'user',
				'OR' => array(
					"username LIKE" => $username,
					"name LIKE"     => $name
			)),
			'order' => array('username' => 'ASC'),
			'recursive' => -1
		));
		//$this->log($data);
		return $data;
	}

	public function findAllUserInGroup($group_id){
		$data = $this->find('all', array(
			'fields' => array('id', 'group_id'),
			'conditions' => array(
				'group_id' => $group_id
			),
			'recursive' => -1
		));
		//$this->log($data);
		return $data;
	}

	// role == 'user' のユーザのみ
	public function findAllStudentInGroup( $group_id ){
		$sql = "SELECT id, group_id FROM ib_users
						WHERE group_id = $group_id
							AND role = 'user'";
		$data = $this->query($sql);
		return $data;
	}

	// role == 'admin' のユーザのみ
	public function findAllAdminInGroup( $group_id ){
		$sql = "SELECT id, group_id FROM ib_users
						WHERE group_id = $group_id
							AND role = 'admin'";
		$data = $this->query($sql);
		return $data;
	}

  ///写真パスを更新する
  public function updatePicPath($user_id,$newPath){
    $sql = "UPDATE ib_users SET pic_path = '$newPath' WHERE id = $user_id";
    $this->query($sql);
    return 1;
  }

  //全て写真パスをGet
  public function getAllPicPath(){
    $sql = "SELECT id, pic_path FROM ib_users";
    $data = $this->query($sql);
    return $data;
  }

	public function findUserPicPath($user_id){
		if($user_id == NULL){ return 'student_img/noPic.png'; }
		$sql = "SELECT id, pic_path FROM ib_users WHERE id = $user_id";
		$data = $this->query($sql)['0']['ib_users']['pic_path'];
		if($data === '' or $data === 'student_img/'){
			$data = 'student_img/noPic.png';
		}
		return $data;
	}

	public function findGroupPicPaths($members){
		if (empty($members)){ return NULL; }

		$conditions = array();
		foreach($members as $member):
			$user_id = $member['ib_users']['id'];
			array_push($conditions, "id=$user_id");
		endforeach;
		$where_clause = join(' or ', $conditions);

		$sql = "SELECT id, pic_path FROM ib_users WHERE $where_clause";
		//$this->log($sql);
		$data = $this->query($sql);
		//$this->log($data);

		$result = array();
		foreach ($data as $datum) {
			$user_id = $datum['ib_users']['id'];
			$pic_path = $datum['ib_users']['pic_path'];
			if($pic_path === '' or $pic_path === 'student_img/'){
				$pic_path = 'student_img/noPic.png';
			}
			$result += [$user_id => $pic_path];
		}
		//$this->log($result);
		return $result;
	}

  public function getOsList(){
    $sql = "SELECT * FROM ib_os_types";
    $data = $this->query($sql);
    return $data;
  }

	public function calcGrade($birthyear){
		if ($birthyear <= 0){ return "生年度未設定"; }
		$this_year = date("Y");
		$age = $this_year - $birthyear;

		if($age <= 6) {
			$grade = "未就学";
		} elseif($age <= 12) {
			$grade = "小学" . ($age - 6) . "年";
		} elseif($age <= 15) {
			$grade = "中学" . ($age - 12) . "年";
		} elseif($age <= 18) {
			$grade = "高校" . ($age - 15) . "年";
		} else {
			$grade = "高卒以上";
		}
		return $grade;
	}

	public function findUserGrade($user_id){
		$sql = "SELECT id, birthyear FROM ib_users WHERE id = $user_id";
		$birthyear = $this->query($sql)['0']['ib_users']['birthyear'];
		$grade = $this->calcGrade($birthyear);
		return $grade;
	}

	public function findGroupGrade($members){
		if (empty($members)){ return NULL; }

		$conditions = array();
		foreach($members as $member):
			$user_id = $member['ib_users']['id'];
			array_push($conditions, "id=$user_id");
		endforeach;
		$where_clause = join(' or ', $conditions);

		$sql = "SELECT id, birthyear FROM ib_users WHERE $where_clause";
		//$this->log($sql);
		$data = $this->query($sql);
		//$this->log($data);

		$result = array();
		foreach ($data as $datum) {
			$user_id = $datum['ib_users']['id'];
			$birthyear = $datum['ib_users']['birthyear'];
			$grade = $this->calcGrade($birthyear);
			$result += [$user_id => $grade];
		}
		//$this->log($result);
		return $result;
	}
}
