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
App::import('Model','User');
/**
 * Category Model
 *
 * @property User $User
 */
class Category extends AppModel {

  public $validate = array(
			'title' => array(
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
			'sort_no' => array(
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
  
  /**
   * belongsTo associations
   *
   * @var array
   */
	public $belongsTo = array(
	);

	/**
	 * 検索用
	 */
	public $actsAs = array(
		'Search.Searchable'
	);

	public $filterArgs = array(
  );
  
  /**
	 * hasMany associations
	 *
	 * @var array
	 */
	public $hasMany = array(
			'Course' => array(
					'className' => 'Course',
					'foreignKey' => 'category_id',
					'dependent' => true,
					'conditions' => '',
					'fields' => '',
					'order' => array('sort_no' => 'asc'),
					'limit' => '',
					'offset' => '',
					'exclusive' => '',
					'finderQuery' => '',
					'counterQuery' => ''
			)
  );
  
  /**
	 * カテゴリの並べ替え
	 *
	 * @param array $id_list カテゴリのIDリスト（並び順）
	 */
	public function setOrder($id_list)
	{
		for($i=0; $i< count($id_list); $i++)
		{
			$data = array('id' => $id_list[$i], 'sort_no' => ($i+1));
			$this->save($data);
		}
  }
  
  // カテゴリの削除
	public function deleteCategory($category_id){
		$this->deleteAll(array('Category.id' => $category_id), true);
	}
}
