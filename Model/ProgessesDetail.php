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
class ProgressesDetail extends AppModel
{
    /**
     * Validation rules
     *
     * @var array
     */
    public $validate = [];

    // The Associations below have been created with all possible keys, those
    // that are not needed can be removed
    public $hasMany = [];

    /**
     * belongsTo associations
     *
     * @var array
     */
    public $belongsTo = [
        "User" => [
            "className" => "User",
            "foreignKey" => "user_id",
            "conditions" => "",
            "fields" => "",
            "order" => "",
        ],
    ];

    /**
     * 検索用
     */
    public $actsAs = ["Search.Searchable"];

    public $filterArgs = [];
}
