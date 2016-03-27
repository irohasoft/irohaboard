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

	public function getCourseRecord($user_id)
	{
		$sql = <<<EOF
 SELECT UsersCourse.*, Course.id, Course.title, first_date, last_date,
       (ifnull(content_cnt, 0) - ifnull(study_cnt, 0) ) as left_cnt,
       (SELECT understanding
          FROM ib_records h1
         WHERE h1.course_id = UsersCourse.course_id
           AND h1.user_id     	=:user_id
         ORDER BY created
          DESC LIMIT 1) as understanding
   FROM ib_users_courses UsersCourse
  INNER JOIN ib_courses Course
		ON Course.id = UsersCourse.course_id
   LEFT OUTER JOIN
       (SELECT h.course_id, h.user_id,
               MAX(DATE_FORMAT(created, '%Y/%m/%d')) as last_date,
               MIN(DATE_FORMAT(created, '%Y/%m/%d')) as first_date
          FROM ib_records h
         WHERE h.user_id =:user_id
         GROUP BY h.course_id, h.user_id) Record
     ON Record.course_id   = UsersCourse.course_id
    AND Record.user_id     =:user_id
   LEFT OUTER JOIN
		(SELECT course_id, COUNT(*) as study_cnt
		   FROM
			(SELECT r.course_id, r.content_id, COUNT(*)
			   FROM ib_records r
			  INNER JOIN ib_contents c ON r.content_id = c.id
			  WHERE r.user_id = :user_id
			  GROUP BY r.course_id, r.content_id) as c
		 GROUP BY course_id) StudyCount
     ON StudyCount.course_id   = UsersCourse.course_id
   LEFT OUTER JOIN
		(SELECT course_id, COUNT(*) as content_cnt
		   FROM ib_contents
		  WHERE kind NOT IN ('label', 'file')
		  GROUP BY course_id) ContentCount
     ON ContentCount.course_id   = UsersCourse.course_id
  WHERE UsersCourse.user_id     =:user_id
  ORDER BY UsersCourse.course_id
EOF;
		// debug($user_id);

		$params = array(
				'user_id' => $user_id
		);

		$data = $this->query($sql, $params);

		return $data;
	}
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
}
