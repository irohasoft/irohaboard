<?php
/**
 * Ripple  Project
 *
 * @author        Osamu Miyazawa
 * @copyright     NPO Organization uec support
 * @link          http://uecsupport.dip.jp/
 * @license       http://www.gnu.org/licenses/gpl-3.0.en.html GPL License
 */

App::uses('AppModel', 'Model');

/**
 * Record Model
 *
 * @property Course $Course
 * @property User $User
 * @property Content $Content
 */
class ClearedContent extends AppModel
{

  /**
   * Validation rules
   *
   * @var array
   */
  public $validate = array(
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
      ),
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
      'content_id' => array(
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
			'Course' => array(
					'className' => 'Course',
					'foreignKey' => 'course_id',
					'conditions' => '',
					'fields' => '',
					'order' => ''
			),
			'User' => array(
					'className' => 'User',
					'foreignKey' => 'user_id',
					'conditions' => '',
					'fields' => '',
					'order' => ''
			),
			'Content' => array(
					'className' => 'Content',
					'foreignKey' => 'content_id',
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

}
