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
	public $order = "Group.title"; // デフォルトのソート条件

	/**
	 * バリデーションルール
	 * https://book.cakephp.org/2/ja/models/data-validation.html
	 * @var array
	 */
	public $validate = [
		'title'  => ['notBlank' => ['rule' => ['notBlank']]],
		'status' => ['numeric'  => ['rule' => ['numeric']]]
	];

	/**
	 * アソシエーションの設定
	 * https://book.cakephp.org/2/ja/models/associations-linking-models-together.html
	 * @var array
	 */
	public $hasAndBelongsToMany = [
		'Course' => [
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
		],
	];
	
	/**
	 * 指定したグループに所属するユーザIDリストを取得
	 * 
	 * @param int $group_id グループID
	 * @return array ユーザIDリスト
	 */
	public function getUserIdByGroupID($group_id)
	{
		$sql = "SELECT user_id FROM ib_users_groups WHERE group_id = :group_id";
		
		$params = ['group_id' => $group_id];
		
		$data = $this->query($sql, $params);
		
		$list = [];
		
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
		$groups = $this->find()-all();
		$data   = ["0" => "全て"];
		
		for($i=0; $i< count($groups); $i++)
		{
			$data[''.$groups[$i]['Group']['id']] = $groups[$i]['Group']['title'];
		}
		
		return $data;
	}
}
