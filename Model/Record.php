<?php
/**
 * iroha Board Project
 *
 * @author        Kotaro Miura
 * @copyright     2015-2021 iroha Soft, Inc. (https://irohasoft.jp)
 * @link          https://irohaboard.irohasoft.jp
 * @license       https://www.gnu.org/licenses/gpl-3.0.en.html GPL License
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
	public $validate = [
			'course_id' => [
					'numeric' => [
							'rule' => [
									'numeric'
							]
					// 'message' => 'Your custom message here',
					// 'allowEmpty' => false,
					// 'required' => false,
					// 'last' => false, // Stop validation after this rule
					// 'on' => 'create', // Limit validation to 'create' or
					// 'update' operations
										]
			],
			'user_id' => [
					'numeric' => [
							'rule' => [
									'numeric'
							]
					// 'message' => 'Your custom message here',
					// 'allowEmpty' => false,
					// 'required' => false,
					// 'last' => false, // Stop validation after this rule
					// 'on' => 'create', // Limit validation to 'create' or
					// 'update' operations
										]
			],
			'content_id' => [
					'numeric' => [
							'rule' => [
									'numeric'
							]
					// 'message' => 'Your custom message here',
					// 'allowEmpty' => false,
					// 'required' => false,
					// 'last' => false, // Stop validation after this rule
					// 'on' => 'create', // Limit validation to 'create' or
					// 'update' operations
										]
			]
	];
	
	// The Associations below have been created with all possible keys, those
	// that are not needed can be removed
	public $hasMany = [
			'RecordsQuestion' => [
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
			]
	];

	/**
	 * belongsTo associations
	 *
	 * @var array
	 */
	public $belongsTo = [
			'Course' => [
					'className' => 'Course',
					'foreignKey' => 'course_id',
					'type'=>'inner',
					'conditions' => '',
					'fields' => '',
					'order' => ''
			],
			'User' => [
					'className' => 'User',
					'foreignKey' => 'user_id',
					'type'=>'inner',
					'conditions' => '',
					'fields' => '',
					'order' => ''
			],
			'Content' => [
					'className' => 'Content',
					'foreignKey' => 'content_id',
					'type'=>'inner',
					'conditions' => '',
					'fields' => '',
					'order' => ''
			]
	];
	
	/**
	 * 検索用
	 */
	public $actsAs = [
		'Search.Searchable'
	];

	public $filterArgs = [
	];
}
