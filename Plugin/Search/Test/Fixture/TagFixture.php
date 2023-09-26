<?php
/**
 * Copyright 2009 - 2014, Cake Development Corporation (http://cakedc.com)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright 2009 - 2014, Cake Development Corporation (http://cakedc.com)
 * @license http://www.opensource.org/licenses/mit-license.php MIT License
 */

/**
 * Tag Fixture
 */
class TagFixture extends CakeTestFixture
{
    /**
     * Table
     *
     * @var string $table
     */
    public $table = "tags";

    /**
     * Fields
     *
     * @var array $fields
     */
    public $fields = [
        "id" => [
            "type" => "string",
            "null" => false,
            "default" => null,
            "length" => 36,
            "key" => "primary",
        ],
        "identifier" => [
            "type" => "string",
            "null" => true,
            "default" => null,
            "length" => 30,
            "key" => "index",
        ],
        "name" => [
            "type" => "string",
            "null" => false,
            "default" => null,
            "length" => 30,
        ],
        "keyname" => [
            "type" => "string",
            "null" => false,
            "default" => null,
            "length" => 30,
        ],
        "weight" => [
            "type" => "integer",
            "null" => false,
            "default" => 0,
            "length" => 2,
        ],
        "created" => ["type" => "datetime", "null" => true, "default" => null],
        "modified" => ["type" => "datetime", "null" => true, "default" => null],
        "indexes" => [
            "PRIMARY" => ["column" => "id", "unique" => 1],
            "UNIQUE_TAG" => [
                "column" => ["identifier", "keyname"],
                "unique" => 1,
            ],
        ],
    ];

    /**
     * Records
     *
     * @var array $records
     */
    public $records = [
        [
            "id" => 1,
            "identifier" => null,
            "name" => "CakePHP",
            "keyname" => "cakephp",
            "weight" => 2,
            "created" => "2008-06-02 18:18:11",
            "modified" => "2008-06-02 18:18:37",
        ],
        [
            "id" => 2,
            "identifier" => null,
            "name" => "CakeDC",
            "keyname" => "cakedc",
            "weight" => 2,
            "created" => "2008-06-01 18:18:15",
            "modified" => "2008-06-01 18:18:15",
        ],
        [
            "id" => 3,
            "identifier" => null,
            "name" => "CakeDC",
            "keyname" => "cakedc",
            "weight" => 2,
            "created" => "2008-06-01 18:18:15",
            "modified" => "2008-06-01 18:18:15",
        ],
    ];
}
