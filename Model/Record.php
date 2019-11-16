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
 * Record Model
 *
 * @property Group $Group
 * @property Course $Course
 * @property User $User
 * @property Content $Content
 */
class Record extends AppModel
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
					'dependent' => true,
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
			'Course' => array(
					'className' => 'Course',
					'foreignKey' => 'course_id',
					'conditions' => '',
					'fields' => '',
					'order' => ''
			),
			'User' => array(
					'className' => 'User',
					'foreignKey' => 'user_id',
					'conditions' => '',
					'fields' => '',
					'order' => ''
			),
			'Content' => array(
					'className' => 'Content',
					'foreignKey' => 'content_id',
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

	// コースの開始日を返す
	public function findStartDate($user_id, $course_id){
		$data = $this->find('first', array(
			'conditions' => array(
				'user_id'   => $user_id,
				'course_id' => $course_id
			),
			'order' => array(
				'created' => 'ASC'
			),
			'recursive' => -1
		));
		if($data == NULL){ return NULL; }
		$start_date = (new DateTime($data['Record']['created']))->format('Y/m/d');
		return $start_date;
	}

	// コースを最後に学習した日を返す
	public function findLastDate($user_id, $course_id){
		$data = $this->find('first', array(
			'conditions' => array(
				'user_id'   => $user_id,
				'course_id' => $course_id
			),
			'order' => array(
				'created' => 'DESC'
			),
			'recursive' => -1
		));
		if($data == NULL){ return NULL; }
		$last_date = (new DateTime($data['Record']['created']))->format('Y/m/d');
		return $last_date;
	}

	// 指定日の最後に学んだコンテンツを返す．不合格のものは含めない
	public function studiedContentOnTheDate($user_id, $date){
		//$this->log($date);
		$result = $this->find('first', array(
			'fields' => array('content_id'),
			'conditions' => array(
				'user_id'      => $user_id,
				'is_passed !=' => 0,
				'created LIKE' => $date."%"),
			'order' => array('created' => 'desc'),
			'recursive'=>-1
		));
		$content_id = $result['Record']['content_id'];
		//$this->log($content_id);
		return $content_id;
	}
}
