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
 * Post Fixture
 */
class PostFixture extends CakeTestFixture
{
    /**
     * Fields
     *
     * @var array $fields
     */
    public $fields = [
        "id" => ["type" => "integer", "key" => "primary"],
        "title" => ["type" => "string", "null" => false],
        "slug" => ["type" => "string", "null" => false],
        "views" => ["type" => "integer", "null" => false],
        "comments" => [
            "type" => "integer",
            "null" => false,
            "default" => "0",
            "length" => 10,
        ],
        "created" => ["type" => "datetime", "null" => true, "default" => null],
        "updated" => ["type" => "datetime", "null" => true, "default" => null],
    ];

    /**
     * Records
     *
     * @var array $records
     */
    public $records = [
        [
            "id" => 1,
            "title" => "First Post",
            "slug" => "first_post",
            "views" => 2,
            "comments" => 1,
            "created" => "2007-03-18 10:39:23",
            "updated" => "2007-03-18 10:41:31",
        ],
        [
            "id" => 2,
            "title" => "Second Post",
            "slug" => "second_post",
            "views" => 1,
            "comments" => 2,
            "created" => "2007-03-18 10:41:23",
            "updated" => "2007-03-18 10:43:31",
        ],
        [
            "id" => 3,
            "title" => "Third Post",
            "slug" => "third_post",
            "views" => 2,
            "comments" => 3,
            "created" => "2007-03-18 10:43:23",
            "updated" => "2007-03-18 10:45:31",
        ],
    ];
}
