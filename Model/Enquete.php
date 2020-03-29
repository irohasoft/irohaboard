<?php
/*
 * Ripple  Project
 *
 * @author        Enfu Guo
 * @copyright     NPO Organization uec support
 * @link          http://uecsupport.dip.jp/
 * @license       http://www.gnu.org/licenses/gpl-3.0.en.html GPL License
*/

App::uses('AppModel', 'Model');

/**
 * Enquete Model
 *
 * @property User $User
 * @property Course $Course
 */
class Enquete extends AppModel
{

	/**
	 * Validation rules
	 *
	 * @var array
	 */
	public $validate = array(
		'group_id' => array(
			'notBlank' => array(
				'rule' => array(
						'notBlank'
				),
				'message' => '今日の授業の中で，一番多く指導してくれた講師を選択してください．'
			)
		),
		/*
		'before_goal_cleared' => array(
			'notBlank' => array(
				'rule' => array(
						'notBlank'
				),
				'message' => '前回に設定したゴールは達成できたかどうかを選択してください．'
			)
		),
		*/
		'today_goal' => array(
			'notBlank' => array(
				'rule' => array(
						'notBlank'
				),
				'message' => '今日のゴールを書いてください．'
			),
			'Maxlength' => array(
				'rule' => array('maxLength', 200),
				'message' => '入力は200文字以下にしてください．'
			)
		),
		/*
		'today_goal_cleared' => array(
			'notBlank' => array(
				'rule' => array(
						'notBlank'
				),
				'message' => '今日のゴールを達成できたどうかを選択してください．'
			)
		),
		*/
		'next_goal' => array(
			/*
			'notBlank' => array(
				'rule' => array(
						'notBlank'
				),
				'message' => '次回までのゴールを記入してください．'
			),*/
			'Maxlength' => array(
				'rule' => array('maxLength', 200),
				'message' => '入力は200文字以下にしてください．'
			)
		),

		'today_impressions' => array(
			/*
			'notBlank' => array(
				'rule' => array(
						'notBlank'
				),
				'message' => '今日の感想を記入してください．'
			),
			*/
			'Maxlength' => array(
				'rule' => array('maxLength', 200),
				'message' => '入力は200文字以下にしてください．'
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
			'Group' => array(
					'className' => 'Group',
					'foreignKey' => 'group_id',
					'conditions' => '',
					'fields' => '',
					'order' => ''
			)
	);
	/**
	 * 検索用
	 */
	public $actsAs = array(
		'Search.Searchable'
	);

	public $filterArgs = array(
	);

	public function findCurrentNextGoal($user_id){
		$data = $this->find('first', array(
			'fields' => array('next_goal'),
			'conditions' => array('user_id' => $user_id),
			'order' => array('created' => 'desc'),
			'recursive' => -1
		));
		$next_goal = $data['Enquete']['next_goal'];
		return $next_goal;
	}

	public function findPreviousNextGoal($user_id){
		$today = date('Y-m-d');
		$data = $this->find('first', array(
			'fields' => array('next_goal'),
			'conditions' => array(
				'user_id' => $user_id,
				'created < ?' => $today.' 00:00:00'
			),
			'order' => array('created' => 'desc'),
			'recursive' => -1
		));
		$next_goal = $data['Enquete']['next_goal'];
		return $next_goal;
	}

	public function findTodayGoal($user_id){
		$today = date('Y-m-d');
		$data = $this->find('first', array(
			'fields' => array('today_goal'),
			'conditions' => array(
				'user_id' => $user_id,
				'created >= ?' => $today.' 00:00:00'
			),
			'order' => array('created' => 'desc'),
			'recursive' => -1
		));
		$today_goal = $data['Enquete']['today_goal'];
		return $today_goal;
	}

}
