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
 * Content Model
 *
 * @property Group $Group
 * @property Course $Course
 * @property User $User
 * @property Record $Record
 */
class Content extends AppModel
{
	/**
	 * バリデーションルール
	 * https://book.cakephp.org/2/ja/models/data-validation.html
	 * @var array
	 */
	public $validate = [
		'course_id' => [
			'numeric' => [
				'rule' => ['numeric']
			]
		],
		'user_id' => [
			'numeric' => [
				'rule' => ['numeric']
			]
		],
		'title' => [
			'notBlank' => [
				'rule' => ['notBlank']
			]
		],
		'status' => [
			'notBlank' => [
				'rule' => ['notBlank']
			]
		],
		'timelimit' => [
			'numeric' => [
			'rule' => ['range', 0, 101],
			'message' => '1-100の整数で入力して下さい。',
			'allowEmpty' => true,
			]
		],
		'pass_rate' => [
			'numeric' => [
			'rule' => ['range', 0, 101],
			'message' => '1-100の整数で入力して下さい。',
			'allowEmpty' => true,
			]
		],
		'question_count' => [
			'numeric' => [
			'rule' => ['range', 0, 101],
			'message' => '1-100の整数で入力して下さい。',
			'allowEmpty' => true,
			]
		],
		'kind' => [
			'notBlank' => [
				'rule' => ['notBlank']
			]
		],
		'sort_no' => [
			'numeric' => [
				'rule' => ['numeric']
			]
		],
	];

	/**
	 * アソシエーションの設定
	 * https://book.cakephp.org/2/ja/models/associations-linking-models-together.html
	 * @var array
	 */
	public $belongsTo = [
		'Course' => [
			'className' => 'Course',
			'foreignKey' => 'course_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		],
		'User' => [
			'className' => 'User',
			'foreignKey' => 'user_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		]
	];

	/**
	 * 学習履歴付きコンテンツ一覧を取得
	 * 
	 * @param int $user_id   取得対象のユーザID
	 * @param int $course_id 取得対象のコースID
	 * @param string $role   取得者の権限（admin の場合、非公開のコンテンツも取得）
	 * @var array 学習履歴付きコンテンツ一覧
	 */
	public function getContentRecord($user_id, $course_id, $role = 'user')
	{
		$sql = <<<EOF
 SELECT Content.*, first_date, last_date, record_id, Record.study_sec, Record.study_count,
       (SELECT understanding
          FROM ib_records h1
         WHERE h1.id = Record.record_id
         ORDER BY created
          DESC LIMIT 1) as understanding,
       (SELECT ifnull(is_passed, 0)
          FROM ib_records h2
         WHERE h2.id = Record.record_id
         ORDER BY created
          DESC LIMIT 1) as is_passed,
        CompleteRecord.is_complete
   FROM ib_contents Content
   LEFT OUTER JOIN # 全ての学習履歴の集計
       (SELECT h.content_id, h.user_id,
               MAX(DATE_FORMAT(created, '%Y/%m/%d')) as last_date,
               MIN(DATE_FORMAT(created, '%Y/%m/%d')) as first_date,
               MAX(id) as record_id,
               SUM(ifnull(study_sec, 0)) as study_sec,
               COUNT(*) as study_count
          FROM ib_records h
         WHERE h.user_id    =:user_id
           AND h.course_id  =:course_id
         GROUP BY h.content_id) Record
     ON Record.content_id  = Content.id
   LEFT OUTER JOIN # 完了した学習履歴の集計
       (SELECT r.content_id, 1 as is_complete #学習履歴をコンテンツ別に集計
          FROM ib_records r
         INNER JOIN ib_contents c ON r.content_id = c.id AND r.course_id = c.course_id
         WHERE r.user_id    = :user_id
           AND r.course_id  =:course_id
           AND c.status = 1
           AND (
                 (c.kind != 'test' AND r.is_complete = 1) OR 
                 (c.kind  = 'test' AND r.is_passed   = 1)
               ) #学習コンテンツが受講済、もしくはテストが合格済の場合
         GROUP BY r.content_id) as CompleteRecord
     ON CompleteRecord.content_id = Content.id
  WHERE Content.course_id  =:course_id
    AND (status = 1 OR 'admin' = :role)
  ORDER BY Content.sort_no
EOF;

		$params = [
			'user_id' => $user_id,
			'course_id' => $course_id,
			'role' => $role
		];

		$data = $this->query($sql, $params);

		return $data;
	}

	/**
	 * コンテンツの並べ替え
	 * 
	 * @param array $id_list コンテンツのIDリスト（並び順）
	 */
	public function setOrder($id_list)
	{
		for($i=0; $i< count($id_list); $i++)
		{
			$sql = "UPDATE ib_contents SET sort_no = :sort_no WHERE id = :id";

			$params = [
				'sort_no' => ($i + 1),
				'id' => $id_list[$i]
			];

			$this->query($sql, $params);
		}
	}

	/**
	 * 新規追加時のコンテンツのソート番号を取得
	 * 
	 * @param array $course_id コースID
	 * @return int ソート番号
	 */
	public function getNextSortNo($course_id)
	{
		$data = $this->find()
			->select('MAX(Content.sort_no) as sort_no')
			->where(['Content.course_id' => $course_id])
			->first();
		
		$sort_no = $data[0]['sort_no'] + 1;
		
		return $sort_no;
	}
}
