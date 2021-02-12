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
 * ContentsQuestion Model
 *
 * @property Group $Group
 * @property Content $Content
 */
class ContentsQuestion extends AppModel
{
	/**
	 * バリデーションルール
	 * https://book.cakephp.org/2/ja/models/data-validation.html
	 * @var array
	 */
	public $validate = [
		'content_id' => [
			'numeric' => [
				'rule' => ['numeric']
			]
		],
		'question_type' => [
			'notBlank' => [
				'rule' => ['notBlank']
			]
		],
		'body' => [
			'notBlank' => [
				'rule' => ['notBlank']
			]
		],
		'score' => [
			'numeric' => [
				'rule' => ['range', -1, 101],
				'message' => '0-100の整数で入力して下さい。',
			]
		],
		'sort_no' => [
			'numeric' => [
				'rule' => ['numeric']
			]
		],
		'option_list' => [
			'rule' => ['multiple', ['min' => 1,]],
			'message' => '正解を選択してください'
		]
	];
	
	/**
	 * アソシエーションの設定
	 * https://book.cakephp.org/2/ja/models/associations-linking-models-together.html
	 * @var array
	 */
	public $belongsTo = [
		'Content' => [
			'className' => 'Content',
			'foreignKey' => 'content_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		]
	];

	/**
	 * 問題の並べ替え
	 * 
	 * @param array $id_list 問題のIDリスト（並び順）
	 */
	public function setOrder($id_list)
	{
		for($i=0; $i< count($id_list); $i++)
		{
			$sql = "UPDATE ib_contents_questions SET sort_no = :sort_no WHERE id = :id";

			$params = [
				'sort_no' => ($i + 1),
				'id' => $id_list[$i]
			];

			$this->query($sql, $params);
		}
	}

	/**
	 * 新規追加時の問題のソート番号を取得
	 * 
	 * @param array $content_id コンテンツ(テスト)のID
	 * @return int ソート番号
	 */
	public function getNextSortNo($content_id)
	{
		$data = $this->find()
			->select('MAX(ContentsQuestion.sort_no) as sort_no')
			->where(['ContentsQuestion.content_id' => $content_id])
			->first();
		
		$sort_no = $data[0]['sort_no'] + 1;
		
		return $sort_no;
	}
}
