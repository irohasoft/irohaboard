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
 * RecordsQuestion Model
 *
 * @property Record $Record
 * @property Question $Question
 */
class RecordsQuestion extends AppModel
{
	public $order = "RecordsQuestion.id";  

	/**
	 * バリデーションルール
	 * https://book.cakephp.org/2/ja/models/data-validation.html
	 * @var array
	 */
	public $validate = [
		'record_id'   => ['numeric' => ['rule' => ['numeric']]],
		'question_id' => ['numeric' => ['rule' => ['numeric']]],
		'score'       => ['numeric' => ['rule' => ['numeric']]]
	];
	
	/**
	 * アソシエーションの設定
	 * https://book.cakephp.org/2/ja/models/associations-linking-models-together.html
	 * @var array
	 */
	public $belongsTo = [
		'Record' => [
			'className' => 'Record',
			'foreignKey' => 'record_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		],
		'ContentsQuestion' => [
			'className' => 'ContentsQuestion',
			'foreignKey' => 'question_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		]
	];
}
