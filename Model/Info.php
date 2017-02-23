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
 * Info Model
 *
 * @property User $User
 * @property Group $Group
 */
class Info extends AppModel
{

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
	);
	
	// The Associations below have been created with all possible keys, those
	// that are not needed can be removed
	
	/**
	 * belongsTo associations
	 *
	 * @var array
	 */
	public $hasAndBelongsToMany = array(
			'Group' => array(
					'className' => 'Group',
					'joinTable' => 'infos_groups',
					'foreignKey' => 'info_id',
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
	
	/*
	public function getGroupInfos($user_id)
	{
		$sql = <<<EOF
		SELECT *, ig.group_id info_group_id
		  FROM ib_infos i
		  LEFT OUTER JOIN ib_infos_groups ig on i.id = ig.info_id
		 WHERE ig.group_id IS NULL OR ig.group_id IN (select group_id from ib_users_groups where user_id = :user_id)
EOF;

		$params = array(
				'user_id' => $user_id
		);

		$data = $this->query($sql, $params);

		return $data;
	}
	*/
}
