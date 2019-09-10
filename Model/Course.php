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

/**
 * Course Model
 *
 * @property Group $Group
 * @property Content $Content
 * @property ContentsQuestion $ContentsQuestion
 * @property Record $Record
 * @property User $User
 */
class Course extends AppModel
{
	public $order = "Course.sort_no";

	/**
	 * Validation rules
	 *
	 * @var array
	 */
	public $validate = array(
			'title' => array(
					'notBlank' => array(
							'rule' => array(
									'notBlank'
							)
					// 'message' => 'Your custom message here',
					// 'allowEmpty' => false,
					// 'required' => false,
					// 'last' => false, // Stop validation after this rule
					// 'on' => 'create', // Limit validation to 'create' or
					// 'update' operations
										)
			),
			'sort_no' => array(
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
					'foreignKey' => 'course_id',
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
	 /*
	public $hasAndBelongsToMany = array(
			'User' => array(
					'className' => 'User',
					'joinTable' => 'users_courses',
					'foreignKey' => 'course_id',
					'associationForeignKey' => 'user_id',
					'unique' => 'keepExisting',
					'conditions' => '',
					'fields' => '',
					'order' => '',
					'limit' => '',
					'offset' => '',
					'finderQuery' => ''
			)
	);
	*/

	/**
	 * コースの並べ替え
	 *
	 * @param array $id_list コースのIDリスト（並び順）
	 */
	public function setOrder($id_list)
	{
		for($i=0; $i< count($id_list); $i++)
		{
			$sql = "UPDATE ib_courses SET sort_no = :sort_no WHERE id= :id";

			$params = array(
					'sort_no' => ($i+1),
					'id' => $id_list[$i]
			);

			$this->query($sql, $params);
		}
	}

	/**
	 * コースへのアクセス権限チェック
	 *
	 * @param int $user_id   アクセス者のユーザID
	 * @param int $course_id アクセス先のコースのID
	 * @return bool          true: アクセス可能, false : アクセス不可
	 */
	public function hasRight($user_id, $course_id)
	{
		$has_right = false;

		$params = array(
			'user_id'   => $user_id,
			'course_id' => $course_id
		);

		$sql = <<<EOF
SELECT count(*) as cnt
  FROM ib_users_courses
 WHERE course_id = :course_id
   AND user_id   = :user_id
EOF;
		$data = $this->query($sql, $params);

		if($data[0][0]["cnt"] > 0)
			$has_right = true;

		$sql = <<<EOF
SELECT count(*) as cnt
  FROM ib_groups_courses gc
 INNER JOIN ib_users u ON gc.group_id = u.group_id AND u.id   = :user_id
 WHERE gc.course_id = :course_id
EOF;
		$data = $this->query($sql, $params);

		if($data[0][0]["cnt"] > 0)
			$has_right = true;

		return $has_right;
	}

	// コースの削除
	public function deleteCourse($course_id)
	{
		$params = array(
			'course_id' => $course_id
		);

		// テスト問題の削除
		$sql = "DELETE FROM ib_contents_questions WHERE content_id IN (SELECT id FROM  ib_contents WHERE course_id = :course_id);";
		$this->query($sql, $params);

		// コンテンツの削除
		$sql = "DELETE FROM ib_contents WHERE course_id = :course_id;";
		$this->query($sql, $params);

		// コースの削除
		$sql = "DELETE FROM ib_courses WHERE id = :course_id;";
		$this->query($sql, $params);
	}

  public function getCourseInfo($course_id){
    $sql = "SELECT * FROM ib_courses WHERE id = $course_id";
    $data = $this->query($sql);
    return $data[0];
  }

  public function getCourseList(){
    $sql = "SELECT id, title
      FROM ib_courses
      ORDER BY id ASC";
    $data = $this->query($sql);
    //$this->log($data);
    $course_list = [];
    foreach($data as $row){
      $id = $row['ib_courses']['id'];
      $title = $row['ib_courses']['title'];
      $course_list[$id] = $title;
    }
    return $course_list;
  }



  public function goToNextCourse($user_id, $before_course_id, $now_course_id){
    //前提となるコースのコンテンツ数をカウントする．
    $sql = "SELECT count(*) as cnt
      FROM ib_contents
      WHERE
        course_id = $before_course_id
      AND
        kind = 'test' ";
    $data = $this->query($sql);
    $total_content = $data[0][0]["cnt"];
    //$this->log(array($total_content, $before_course_id));
    //前提となるコースのクリアしたコンテンツ数をカウントする．
    $sql = "SELECT count(*) as cnt
      FROM ib_cleared
      WHERE
        course_id = $before_course_id
      AND
        user_id = $user_id";
    $data = $this->query($sql);
    $cleared_content = $data[0][0]["cnt"];

    //もし，クリア >= 全て
    if($cleared_content >= $total_content){
      $sql = "INSERT INTO ib_cleared (id, user_id, course_id, content_id, created, modfied) VALUES (NULL, $user_id, $now_course_id, NULL, CURRENT_TIME(), CURRENT_TIME())";
      $data = $this->query($sql);
      return true;
    }else{
      return false;
    }
  }

  public function existCleared($user_id, $now_course_id){
    $sql = "SELECT count(*) as cnt
      FROM ib_cleared
      WHERE
        course_id = $now_course_id
      AND
        user_id = $user_id";
    $data = $this->query($sql);
    $cleared_course = $data[0][0]["cnt"];
    if($cleared_course > 0){
      return true;
    }else{
      return false;
    }
  }

	public function calcClearedRate($user_id, $course_id){
		// 学習中コースの全コンテンツ数
		$sql = "SELECT count(*) as cnt
			FROM ib_contents
			WHERE
				course_id = $course_id
			AND
				kind = 'test' ";
		$data = $this->query($sql);
		$total_content = $data[0][0]["cnt"];
		if($total_content == 0){ return 0; }

		// 合格したコンテンツ数
		$sql = "SELECT count(*) as cnt
			FROM ib_cleared
			WHERE
				course_id = $course_id
			AND
				user_id = $user_id";
		$data = $this->query($sql);
		$cleared_course = $data[0][0]["cnt"];

		// 合格したコンテンツの割合を計算
		$cleared_rate = $cleared_course/$total_content;
		//$this->log($cleared_rate);
		return $cleared_rate;
	}

	public function findClearedRate($user_id){
		$cleared_rates = array();
		App::import('Model', 'UsersCourse');
		$this->UsersCourse = new UsersCourse();
		$all_courses = $this->UsersCourse->getCourseRecord($user_id);
		foreach($all_courses as $course){
			$course_id    = $course['Course']['id'];
			$course_title = $course['Course']['title'];
			$cleared_rate = $this->calcClearedRate($user_id, $course_id);
			$cleared_rates[] = ['course_title' => $course_title, 'cleared_rate' => $cleared_rate];
		}
		return $cleared_rates;
	}

	// user_idとコース名・合格率の配列を作る
	public function findGroupClearedRate($members){
		if (empty($members)){ return NULL; }
		$members_cleared_rates = array();
		foreach($members as $member){
			$user_id = $member['ib_users']['id'];
			$cleared_rates = $this->findClearedRate($user_id);
			$members_cleared_rates += [$user_id => $cleared_rates];
		}
		return $members_cleared_rates;
	}

}
