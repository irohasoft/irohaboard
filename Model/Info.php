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
	
	/**
	 * お知らせ一覧を取得（エイリアス）
	 * 
	 * @param int $user_id ユーザID
	 * @param int $limit 取得件数
	 * @return array お知らせ一覧
	 */
	public function getInfos($user_id, $limit = null)
	{
		$infos = $this->find('all', $this->getInfoOption($user_id, $limit));
		return $infos;
	}
	
	/**
	 * お知らせ一覧を取得
	 * 
	 * @param int $user_id ユーザID
	 * @param int $limit 取得件数
	 * @return array お知らせ一覧
	 */
	public function getInfoOption($user_id, $limit = null)
	{
		App::import('Model', 'UsersGroup');
		$this->UsersGroup = new UsersGroup();
		
		$groups = $this->UsersGroup->find('all', array(
			'conditions' => array(
				'user_id' => $user_id
			)
		));
		
		// 自分自身が所属するグループのIDの配列を作成
		$group_id_list = array();
		
		foreach ($groups as $group)
		{
			$group_id_list[count($group_id_list)] = $group['Group']['id'];
		}
		
		$option = array(
			'fields' => array('Info.id', 'Info.title', 'Info.created'),
			'conditions' => array('OR' => array(
				array('InfoGroup.group_id' => null), 
				array('InfoGroup.group_id' => $group_id_list)
			)),
			'joins' => array(
				array(
					'type' => 'LEFT OUTER',
					'alias' => 'InfoGroup',
					'table' => 'ib_infos_groups',
					'conditions' => 'Info.id = InfoGroup.info_id'
				),
			),
			'group' => array('Info.id', 'Info.title', 'Info.created'),
			'order' => array('Info.created' => 'desc'),
		);
		
		if($limit)
			$option['limit'] = $limit;
		
		return $option;
	}
}
