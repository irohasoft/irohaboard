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
 * Record Model
 *
 * @property Group $Group
 * @property Course $Course
 * @property User $User
 * @property Content $Content
 */
class Record extends AppModel
{
	/**
	 * バリデーションルール
	 * https://book.cakephp.org/2/ja/models/data-validation.html
	 * @var array
	 */
	public $validate = [
		'course_id'  => ['numeric' => ['rule' => ['numeric']]],
		'user_id'    => ['numeric' => ['rule' => ['numeric']]],
		'content_id' => ['numeric' => ['rule' => ['numeric']]]
	];

	/**
	 * アソシエーションの設定
	 * https://book.cakephp.org/2/ja/models/associations-linking-models-together.html
	 * @var array
	 */
	public $hasMany = [
		'RecordsQuestion' => [
			'className' => 'RecordsQuestion',
			'foreignKey' => 'record_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => 'RecordsQuestion.id',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		]
	];

	public $belongsTo = [
		'Course' => [
			'className' => 'Course',
			'foreignKey' => 'course_id',
			'type'=>'inner',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		],
		'User' => [
			'className' => 'User',
			'foreignKey' => 'user_id',
			'type'=>'inner',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		],
		'Content' => [
			'className' => 'Content',
			'foreignKey' => 'content_id',
			'type'=>'inner',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		]
	];
	
	/**
	 * 検索用
	 */
	public $actsAs = [
		'Search.Searchable'
	];

	/**
	 * 検索条件
	 * https://github.com/CakeDC/search/blob/master/Docs/Home.md
	 */
	public $filterArgs = [
		'course_id' => [
			'type' => 'value',
			'field' => 'course_id'
		],
		'content_title' => [
			'type' => 'like',
			'field' => 'Content.title'
		],
		'username' => [
			'type' => 'like',
			'field' => 'User.username'
		],
		'name' => [
			'type' => 'like',
			'field' => 'User.name'
		],
	];
}
