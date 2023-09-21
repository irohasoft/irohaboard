<?php
/*
 * Ripple  Project
 *
 * @author        Enfu Guo
 * @copyright     NPO Organization uec support
 * @link          http://uecsupport.dip.jp/
 * @license       http://www.gnu.org/licenses/gpl-3.0.en.html GPL License
 */

App::uses("AppModel", "Model");

/**
 * Record Model
 *
 * @property Group $Group
 * @property Course $Course
 * @property User $User
 * @property Content $Content
 */
class Progress extends AppModel
{
    /**
     * Validation rules
     *
     * @var array
     */
    public $validate = [
        "title" => [
            "notBlank" => [
                "rule" => ["notBlank"],
                // 'message' => 'Your custom message here',
                // 'allowEmpty' => false,
                // 'required' => false,
                // 'last' => false, // Stop validation after this rule
                // 'on' => 'create', // Limit validation to 'create' or
                // 'update' operations
            ],
        ],
    ];

    // The Associations below have been created with all possible keys, those
    // that are not needed can be removed
    public $hasMany = [
        "ProgressesDetail" => [
            "className" => "ProgressesDetail",
            "foreignKey" => "progress_id",
            "dependent" => false,
            "conditions" => "",
            "fields" => "",
            "order" => "",
            "limit" => "",
            "offset" => "",
            "exclusive" => "",
            "finderQuery" => "",
            "counterQuery" => "",
        ],
    ];

    /**
     * belongsTo associations
     *
     * @var array
     */
    public $belongsTo = [
        // 'User' => array(
        // 		'className' => 'User',
        // 		'foreignKey' => 'user_id',
        // 		'conditions' => '',
        // 		'fields' => '',
        // 		'order' => ''
        // )
    ];

    /**
     * 検索用
     */
    public $actsAs = ["Search.Searchable"];

    public $filterArgs = [];

    public function deleteProgress($progress_id)
    {
        $this->deleteAll(
            [
                "Progress.id" => $progress_id,
            ],
            true
        );
    }
}
