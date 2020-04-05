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
			),
			'Record' => array(
					'className' => 'Record',
					'foreignKey' => 'user_id',
					'dependent' => true
			),
			'ClearedContent' => array(
					'className' => 'ClearedContent',
					'foreignKey' => 'user_id',
					'dependent' => true
			),
			'Soap' => array(
					'className' => 'Soap',
					'foreignKey' => 'user_id',
					'dependent' => true
			),
			'Enquete' => array(
					'className' => 'Enquete',
					'foreignKey' => 'user_id',
					'dependent' => true
			),
			'Attendance' => array(
				'className' => 'Attendance',
				'foreignKey' => 'user_id',
				'order' => 'created ASC',
				'dependent' => true
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
		/*
		'name' => array(
			'type' => 'like',
			'field' => 'User.name'
		),
		*/
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
	public function deleteUserRecords($user_id){
		App::import('Model', 'Record');
		$this->Record = new Record();
		$this->Record->deleteAll(array('Record.user_id' => $user_id), true);
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
					"name LIKE"     => $name,
					'name_furigana LIKE' => $name,
			)),
			'order' => array('username' => 'ASC'),
			'recursive' => -1
		));
		return $data;
	}

	public function findUserGroup($user_id){
		$data = $this->find('all', array(
			'fields' => array('group_id'),
			'conditions' => array('id' => $user_id),
			'recursive' => -1
		));
		//$this->log($data);
		return $data[0]['User']['group_id'];
	}

	// role == 'user' のユーザのみ
	public function getAllStudent(){
		$data = $this->find('all', array(
			'conditions' => array(
				'role'     => 'user'
			),
			'order' => array('username' => 'ASC'),
			'recursive' => -1
		));
		return $data;
	}

	public function findAllUserInGroup($group_id){
		$data = $this->find('all', array(
			'fields' => array('id', 'group_id'),
			'conditions' => array(
				'OR' => array(
					'group_id' => $group_id,
					'last_group' => $group_id,
				)
			),
			'order' => array('username' => 'ASC'),
			'recursive' => -1
		));
		return $data;
	}

	// role == 'user' のユーザのみ
	public function findAllStudentInGroup($group_id){
		$data = $this->find('all', array(
			'fields' => array('id', 'group_id','last_group'),
			'conditions' => array(
				'OR' => array(
					'group_id' => $group_id,
					'last_group' => $group_id,
				),
				'role'     => 'user'
			),
			'order' => array('username' => 'ASC'),
			'recursive' => -1
		));
		return $data;
	}

	// role == 'admin' のユーザのみ
	public function findAllAdminInGroup($group_id){
		$data = $this->find('all', array(
			'fields' => array('id', 'group_id'),
			'conditions' => array(
				'group_id' => $group_id,
				'role'     => 'admin'
			),
			'order' => array('username' => 'ASC'),
			'recursive' => -1
		));
		return $data;
	}

  ///写真パスを更新する
  public function updatePicPath($user_id, $newPath){
		$data = array('id' => $user_id, 'pic_path' => $newPath);
		$this->save($data);
    return 1;
  }

  //全て写真パスをGet
  public function getAllPicPath(){
		$data = $this->find('all', array(
			'fields' => array('id', 'pic_path'),
			'recursive' => -1
		));
    return $data;
  }

	public function findUserPicPath($user_id){
		if($user_id == NULL){ return 'student_img/noPic.png'; }
		$data = $this->find('first', array(
			'fields' => array('id', 'pic_path'),
			'conditions' => array('id' => $user_id),
			'recursive' => -1
		));
		$pic_path = $data['User']['pic_path'];
		if($pic_path === null or $pic_path === '' or $pic_path === 'student_img/'){
			return 'student_img/noPic.png';
		}
		//$this->log($pic_path);
		return $pic_path;
	}

	public function findGroupPicPaths($members){
		if (empty($members)){ return NULL; }

		$conditions = array();
		foreach($members as $member):
			$user_id = $member['User']['id'];
			array_push($conditions, array('id' => $user_id));
		endforeach;

		$data = $this->find('all', array(
			'fields' => array('id', 'pic_path'),
			'conditions' => array('OR' => $conditions),
			'recursive' => -1
		));

		$result = array();
		foreach ($data as $datum) {
			$user_id = $datum['User']['id'];
			$pic_path = $datum['User']['pic_path'];
			if($pic_path === null or $pic_path === '' or $pic_path === 'student_img/'){
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
		$this_fiscal_year = (strtotime(date("Y-m-d")) < strtotime($this_year."-04-01")) ? $this_year - 1 : $this_year;
		$age = $this_fiscal_year - $birthyear;

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
		$data = $this->find('all', array(
			'fields' => array('id', 'birthyear'),
			'conditions' => array('id' => $user_id),
			'recursive' => -1
		));
		$birthyear = $data['0']['User']['birthyear'];
		$grade = $this->calcGrade($birthyear);
		return $grade;
	}

	public function findGroupGrade($members){
		if (empty($members)){ return NULL; }

		$conditions = array();
		foreach($members as $member):
			$user_id = $member['User']['id'];
			array_push($conditions, array('id' => $user_id));
		endforeach;

		$data = $this->find('all', array(
			'fields' => array('id', 'birthyear'),
			'conditions' => array('OR' => $conditions),
			'recursive' => -1
		));

		$result = array();
		foreach ($data as $datum) {
			$user_id = $datum['User']['id'];
			$birthyear = $datum['User']['birthyear'];
			$grade = $this->calcGrade($birthyear);
			$result += [$user_id => $grade];
		}
		//$this->log($result);
		return $result;
	}

	public function generatePassword($size=10){
		$password_chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
		$password_chars_count = strlen($password_chars);
		$data = random_bytes($size);
		$pin = '';
		for ($n = 0; $n < $size; $n++){
			$pin .= substr($password_chars, (ord(substr($data, $n, 1)) % $password_chars_count), 1);
		}
		return $pin;
	}

}
