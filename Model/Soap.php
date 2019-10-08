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

	// 過去4回分のSOAPを取得
	public function findRecentSoaps($user_id){
		$data = $this->find('all', array(
			'fields' => array(
				'id', 'user_id', 'group_id', 'current_status', 'studied_content', 'S', 'O', 'A', 'P', 'created'
			),
			'conditions' => array('user_id' => $user_id),
			'order' => array('created' => 'desc'),
			'limit' => 4,
			'recursive' => -1
		));
		//$this->log($data);
		return $data;
	}

	// user_idと過去4回分SOAPの配列を作る
	public function findGroupRecentSoaps($members){
		if (empty($members)){ return NULL; }
		$members_recent_soaps = array();
		foreach($members as $member):
			$user_id = $member['User']['id'];
			$recent_soaps = $this->findRecentSoaps($user_id);
			$members_recent_soaps += [$user_id => $recent_soaps];
		endforeach;
		return $members_recent_soaps;
	}

	public function getOldestCreatedYear(){
		$data = $this->find('first', array(
			'fields' => array(
				'MIN(created) AS oldest_created_time',
			),
			'recursive' => -1
		));
		$oldest_created_year = (new DateTime($data[0]['oldest_created_time']))->format('Y');
		//$this->log($oldest_created_year);
		return $oldest_created_year;
	}

}
