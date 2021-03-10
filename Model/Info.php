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
 * Info Model
 *
 * @property User $User
 * @property Group $Group
 */
class Info extends AppModel
{
	/**
	 * バリデーションルール
	 * https://book.cakephp.org/2/ja/models/data-validation.html
	 * @var array
	 */
	public $validate = [
		'title'   => ['notBlank' => ['rule' => ['notBlank']]],
		'user_id' => ['numeric'  => ['rule' => ['numeric']]],
	];
	
	/**
	 * アソシエーションの設定
	 * https://book.cakephp.org/2/ja/models/associations-linking-models-together.html
	 * @var array
	 */
	public $hasAndBelongsToMany = [
		'Group' => [
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
	 	]
	];
	
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
		// 閲覧可能なお知らせを取得
		$info_id_list = $this->getInfoIdList($user_id, $limit);

		$option = [
			'fields' => ['Info.id', 'Info.title', 'Info.created'],
			'conditions' => ['Info.id IN' => $info_id_list],
			'order' => ['Info.created' => 'desc'],
		];
		
		if($limit)
			$option['limit'] = $limit;
		
		return $option;
	}

	/**
	 * お知らせへのアクセス権限チェック
	 * 
	 * @param int $user_id   アクセス者のユーザID
	 * @param int $course_id アクセス先のコースのID
	 * @return bool true: アクセス可能, false : アクセス不可
	 */
	public function hasRight($user_id, $info_id)
	{
		$info_id_list = $this->getInfoIdList($user_id);
		
		return in_array($info_id, $info_id_list);
	}
	
	/**
	 * 閲覧可能なお知らせのIDリストを取得
	 * @param int $user_id ユーザID
	 * @return array お知らせIDリスト
	 */
	private function getInfoIdList($user_id, $limit = null)
	{
		$sql = <<<EOF
	SELECT
		Info.id
	FROM
		ib_infos AS Info
		LEFT OUTER JOIN ib_infos_groups AS InfoGroup ON ( Info.id = InfoGroup.info_id ) 
	WHERE
		InfoGroup.group_id IS NULL 
		OR InfoGroup.group_id IN ( SELECT group_id FROM ib_users_groups WHERE user_id = :user_id ) 
	GROUP BY
		Info.id
	ORDER BY Info.created desc
EOF;
		if($limit)
			$sql .= ' LIMIT '.intval($limit);

		$params = [
			'user_id' => $user_id,
		];
		
		$infos = $this->query($sql, $params);
		
		$info_id_list = [];
		
		foreach ($infos as $info)
		{
			$info_id_list[] = $info['Info']['id'];
		}
		
		// 該当するお知らせIDが1件も存在しない場合、エラー防止のため、ダミーIDを追加
		if(count($info_id_list) == 0)
			$info_id_list[] = 0;
		
		return $info_id_list;
	}
}
