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
 * GroupsCourse Model
 *
 * @property Group $Group
 * @property Course $Course
 */
class GroupsCourse extends AppModel
{

	/**
	 * Validation rules
	 *
	 * @var array
	 */
	public $validate = [
			'group_id' => [
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
			]
	];

	// The Associations below have been created with all possible keys, those
	// that are not needed can be removed

	/**
	 * belongsTo associations
	 *
	 * @var array
	 */
	public $belongsTo = [
			'Group' => [
					'className' => 'Group',
					'foreignKey' => 'group_id',
					'conditions' => '',
					'fields' => '',
					'order' => ''
			],
			'Course' => [
					'className' => 'Course',
					'foreignKey' => 'course_id',
					'conditions' => '',
					'fields' => '',
					'order' => ''
			]
	];
}
