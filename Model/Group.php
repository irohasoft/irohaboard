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
 * Group Model
 *
 * @property Content $Content
 * @property ContentsQuestion $ContentsQuestion
 * @property Course $Course
 * @property Record $Record
 * @property User $User
 */
class Group extends AppModel
{
	public $order = "Group.title";  

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
			'status' => array(
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
	 * hasMany associations
	 *
	 * @var array
	 */
	/*
	public $hasMany = array(
			'User' => array(
					'className' => 'User',
					'foreignKey' => 'group_id',
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
	*/
	public $hasAndBelongsToMany = array(
			'Course' => array(
					'className' => 'Course',
					'joinTable' => 'groups_courses',
					'foreignKey' => 'group_id',
					'associationForeignKey' => 'course_id',
					'unique' => 'keepExisting',
					'conditions' => '',
					'fields' => '',
					'order' => '',
					'limit' => '',
					'offset' => '',
					'finderQuery' => ''
			),
	);
	
	/**
	 * 指定したグループに所属するユーザIDリストを取得
	 * 
	 * @param int $group_id グループID
	 * @return array ユーザIDリスト
	 */
	public function getUserIdByGroupID($group_id)
	{
		$sql = "SELECT user_id FROM ib_users_groups WHERE group_id = :group_id";
		
		$params = array('group_id' => $group_id);
		
		$data = $this->query($sql, $params);
		
		$list = array();
		
		for($i=0; $i< count($data); $i++)
		{
			$list[$i] = $data[$i]['ib_users_groups']['user_id'];
		}
		
		return $list;
	}
	
	/**
	 * グループ一覧を取得
	 * 
	 * @return array グループ一覧
	 */
	public function getGroupList()
	{
		$groups = $this->find('all');
		$data   = array("0" => "全て");
		
		for($i=0; $i< count($groups); $i++)
		{
			$data[''.$groups[$i]['Group']['id']] = $groups[$i]['Group']['title'];
		}
		
		return $data;
	}
}
