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
					'dependent' => true,
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
					'foreignKey' => 'course_id',
					'dependent' => true,
					'conditions' => '',
					'fields' => '',
					'order' => '',
					'limit' => '',
					'offset' => '',
					'exclusive' => '',
					'finderQuery' => '',
					'counterQuery' => ''
			),
			'ClearedContent' => array(
					'className' => 'ClearedContent',
					'foreignKey' => 'course_id',
					'dependent' => true
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
			$data = array('id' => $id_list[$i], 'sort_no' => ($i+1));
			$this->save($data);
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
		if($data[0][0]["cnt"] > 0){ return true; }

		$sql = <<<EOF
SELECT count(*) as cnt
  FROM ib_groups_courses gc
 INNER JOIN ib_users u ON gc.group_id = u.group_id OR gc.group_id = u.last_group AND u.id   = :user_id
 WHERE gc.course_id = :course_id
EOF;
		$data = $this->query($sql, $params);
		if($data[0][0]["cnt"] > 0){ return true; }

		return false;
	}

	// コースの削除
	public function deleteCourse($course_id){
		$this->deleteAll(array('Course.id' => $course_id), true);
	}

  public function getCourseInfo($course_id){
		$data = $this->find('first', array(
			'conditions' => array('id' => $course_id),
			'recursive' => -1
		));
		//$this->log($data);
    return $data[0];
  }

  public function getCourseList(){
		$data = $this->find('all', array(
			'fields' => array(
				'id', 'title'
			),
			'order' => array('id' => 'ASC'),
			'recursive' => -1
		));
    $course_list = [];
    foreach($data as $row){
      $id = $row['Course']['id'];
      $title = $row['Course']['title'];
      $course_list[$id] = $title;
    }
    return $course_list;
  }



  public function goToNextCourse($user_id, $before_course_id, $now_course_id){
    //前提となるコースのコンテンツ数をカウントする．
		App::import('Model', 'Content');
		$this->Content = new Content();
		$total_content = $this->Content->find('count', array(
			'conditions' => array(
				'course_id' => $before_course_id,
				'kind'      => 'test'
			),
			'recursive' => -1
		));

    //前提となるコースのクリアしたコンテンツ数をカウントする．
		App::import('Model', 'ClearedContent');
		$this->ClearedContent = new ClearedContent();
		$cleared_content = $this->ClearedContent->find('count', array(
			'conditions' => array(
				'course_id' => $before_course_id,
				'user_id'   => $user_id
			),
			'recursive' => -1
		));

    //もし，クリア >= 全て
    if($cleared_content >= $total_content){
      return true;
    }else{
      return false;
    }
  }

  public function existCleared($user_id, $now_course_id){
		App::import('Model', 'ClearedContent');
		$this->ClearedContent = new ClearedContent();
		$cleared_course = $this->ClearedContent->find('count', array(
			'conditions' => array(
				'course_id' => $now_course_id,
				'user_id'   => $user_id
			),
			'recursive' => -1
		));
    if($cleared_course > 0){
      return true;
    }else{
      return false;
    }
  }

	public function calcClearedRate($user_id, $course_id){
		// 学習中コースの全コンテンツ数
		App::import('Model', 'Content');
		$this->Content = new Content();
		$total_content = $this->Content->find('count', array(
			'conditions' => array(
				'course_id' => $course_id,
				'kind'      => 'test'
			),
			'recursive' => -1
		));
		if($total_content == 0){ return null; }  // コンテンツが存在しない場合

		// 合格したコンテンツ数
		App::import('Model', 'ClearedContent');
		$this->ClearedContent = new ClearedContent();
		$cleared_course = $this->ClearedContent->find('count', array(
      'conditions' => array(
				'course_id' => $course_id,
				'user_id'   => $user_id
			),
			'recursive' => -1
    ));

		// 合格したコンテンツの割合を計算
		$cleared_rate = $cleared_course/$total_content;
		return $cleared_rate;
	}

	public function findClearedRate($user_id){
		$cleared_rates = array();
		App::import('Model', 'UsersCourse');
		App::import('Model', 'Record');
		$this->UsersCourse = new UsersCourse();
		$this->Record = new Record();
		$all_courses = $this->UsersCourse->getCourseRecord($user_id);
		foreach($all_courses as $course){
			$course_id    = $course['Course']['id'];
			$course_title = $course['Course']['title'];
			$start_date   = $this->Record->findStartDate($user_id, $course_id);
			$last_date    = $this->Record->findLastDate($user_id, $course_id);
			$cleared_rate = $this->calcClearedRate($user_id, $course_id);
			$cleared_rates[] = [
				'course_title' => $course_title,
				'start_date'   => $start_date,
				'last_date'    => $last_date,
				'cleared_rate' => $cleared_rate
			];
		}
		return $cleared_rates;
	}

	// user_idとコース名・合格率の配列を作る
	public function findGroupClearedRate($members){
		if (empty($members)){ return NULL; }
		$members_cleared_rates = array();
		foreach($members as $member){
			$user_id = $member['User']['id'];
			$cleared_rates = $this->findClearedRate($user_id);
			$members_cleared_rates += [$user_id => $cleared_rates];
		}
		return $members_cleared_rates;
	}

}
