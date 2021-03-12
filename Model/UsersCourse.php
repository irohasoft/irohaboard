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
 * UsersCourse Model
 *
 * @property User $User
 * @property Course $Course
 */
class UsersCourse extends AppModel
{
	/**
	 * バリデーションルール
	 * https://book.cakephp.org/2/ja/models/data-validation.html
	 * @var array
	 */
	public $validate = [
		'user_id'   => ['numeric' => ['rule' => ['numeric']]],
		'course_id' => ['numeric' => ['rule' => ['numeric']]]
	];

	/**
	 * アソシエーションの設定
	 * https://book.cakephp.org/2/ja/models/associations-linking-models-together.html
	 * @var array
	 */
	public $belongsTo = [
		'User' => [
			'className' => 'User',
			'foreignKey' => 'user_id',
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

	/**
	 * 学習履歴付き受講コース一覧を取得
	 * 
	 * @param int $user_id ユーザのID
	 * @return array 受講コース一覧
	 */
	public function getCourseRecord($user_id)
	{
		$sql = <<<EOF
 SELECT Course.*, Record.first_date, Record.last_date,
       (ifnull(ContentCount.content_cnt, 0) - ifnull(CompleteCount.complete_cnt, 0)) as left_cnt #未受講コンテンツ数
   FROM ib_courses Course
   LEFT OUTER JOIN
       (SELECT h.course_id, h.user_id,
               MAX(DATE_FORMAT(created, '%Y/%m/%d')) as last_date,
               MIN(DATE_FORMAT(created, '%Y/%m/%d')) as first_date
          FROM ib_records h
         WHERE h.user_id =:user_id
         GROUP BY h.course_id, h.user_id) Record
     ON Record.course_id   = Course.id
    AND Record.user_id     =:user_id
   LEFT OUTER JOIN
        (SELECT course_id, COUNT(*) as complete_cnt #受講済コンテンツ数をコース別に集計
           FROM
            (SELECT r.course_id, r.content_id, COUNT(*) as cnt #学習履歴をコンテンツ別に集計
               FROM ib_records r
              INNER JOIN ib_contents c ON r.content_id = c.id AND r.course_id = c.course_id
              WHERE r.user_id = :user_id
                AND c.status = 1
                AND (
                      (c.kind != 'test' AND r.is_complete = 1) OR 
                      (c.kind  = 'test' AND r.is_passed   = 1)
                    ) #学習コンテンツが受講済、もしくはテストが合格済の場合
              GROUP BY r.course_id, r.content_id) as c
          GROUP BY course_id) CompleteCount
     ON CompleteCount.course_id = Course.id
   LEFT OUTER JOIN
        (SELECT course_id, COUNT(*) as content_cnt #コンテンツ数をコース別に集計
           FROM ib_contents
          WHERE kind NOT IN ('label', 'file')
            AND status = 1
          GROUP BY course_id) ContentCount
     ON ContentCount.course_id   = Course.id
  WHERE id IN (SELECT course_id FROM ib_users_groups ug INNER JOIN ib_groups_courses gc ON ug.group_id = gc.group_id WHERE user_id = :user_id) #グループ受講登録
     OR id IN (SELECT course_id FROM ib_users_courses WHERE user_id = :user_id) #個人別受講登録
  ORDER BY Course.sort_no asc
EOF;

		$params = [
			'user_id' => $user_id
		];

		$data = $this->query($sql, $params);

		return $data;
	}
}
