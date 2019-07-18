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
 * UsersCourse Model
 *
 * @property User $User
 * @property Course $Course
 */
class UsersCourse extends AppModel
{

	/**
	 * Validation rules
	 *
	 * @var array
	 */
	public $validate = array(
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
			'course_id' => array(
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
	 * belongsTo associations
	 *
	 * @var array
	 */
	public $belongsTo = array(
			'User' => array(
					'className' => 'User',
					'foreignKey' => 'user_id',
					'conditions' => '',
					'fields' => '',
					'order' => ''
			),
			'Course' => array(
					'className' => 'Course',
					'foreignKey' => 'course_id',
					'conditions' => '',
					'fields' => '',
					'order' => ''
			)
	);


	/**
	 * 学習履歴付き受講コース一覧を取得
	 * 
	 * @param int $user_id ユーザのID
	 * @return array 受講コース一覧
	 */
	public function getCourseRecord($user_id)
	{
		$sql = <<<EOF
 SELECT Course.*, Course.id, Course.title, first_date, last_date,
       (ifnull(content_cnt, 0) - ifnull(study_cnt, 0) ) as left_cnt
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
		(SELECT course_id, COUNT(*) as study_cnt
		   FROM
			(SELECT r.course_id, r.content_id, COUNT(*)
			   FROM ib_records r
			  INNER JOIN ib_contents c ON r.content_id = c.id AND r.course_id = c.course_id
			  WHERE r.user_id = :user_id
			    AND status = 1
			  GROUP BY r.course_id, r.content_id) as c
		 GROUP BY course_id) StudyCount
     ON StudyCount.course_id   = Course.id
   LEFT OUTER JOIN
		(SELECT course_id, COUNT(*) as content_cnt
		   FROM ib_contents
		  WHERE kind NOT IN ('label', 'file')
		    AND status = 1
		  GROUP BY course_id) ContentCount
     ON ContentCount.course_id   = Course.id
  WHERE id IN (SELECT course_id FROM ib_users_groups ug INNER JOIN ib_groups_courses gc ON ug.group_id = gc.group_id WHERE user_id = :user_id)
     OR id IN (SELECT course_id FROM ib_users_courses WHERE user_id = :user_id)
  ORDER BY Course.sort_no asc
EOF;

		$params = array(
			'user_id' => $user_id
		);

		$data = $this->query($sql, $params);

		return $data;
	}
}
