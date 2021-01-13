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
 * ContentsQuestion Model
 *
 * @property Group $Group
 * @property Content $Content
 */
class ContentsQuestion extends AppModel
{

	/**
	 * Validation rules
	 *
	 * @var array
	 */
	public $validate = [
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
			],
			'question_type' => [
					'notBlank' => [
							'rule' => [
									'notBlank'
							]
					// 'message' => 'Your custom message here',
					// 'allowEmpty' => false,
					// 'required' => false,
					// 'last' => false, // Stop validation after this rule
					// 'on' => 'create', // Limit validation to 'create' or
					// 'update' operations
										]
			],
			'body' => [
					'notBlank' => [
							'rule' => [
									'notBlank'
							]
					// 'message' => 'Your custom message here',
					// 'allowEmpty' => false,
					// 'required' => false,
					// 'last' => false, // Stop validation after this rule
					// 'on' => 'create', // Limit validation to 'create' or
					// 'update' operations
										]
			],
			/*
			'correct' => array(
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
			*/
			'score' => [
					'numeric' => [
							'rule' => [
									'range', -1, 101
							],
					'message' => '0-100の整数で入力して下さい。',
					// 'allowEmpty' => false,
					// 'required' => false,
					// 'last' => false, // Stop validation after this rule
					// 'on' => 'create', // Limit validation to 'create' or
					// 'update' operations
										]
			],
			'sort_no' => [
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
			'option_list' => [
				'rule' => ['multiple', [
					'min' => 1,
				]],
				'message' => '正解を選択してください'
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
			$sql = "UPDATE ib_contents_questions SET sort_no = :sort_no WHERE id= :id";

			$params = [
					'sort_no' => ($i+1),
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
		$options = [
			'fields' => 'MAX(ContentsQuestion.sort_no) as sort_no',
			'conditions' => [
				'ContentsQuestion.content_id' => $content_id
			]
		];
		
		$data = $this->find('first', $options);
		
		$sort_no = $data[0]['sort_no'] + 1;
		
		return $sort_no;
	}
}
