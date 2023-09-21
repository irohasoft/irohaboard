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

App::uses("Model", "Model");
App::uses("ModelBehavior", "Model");

/**
 * FilterBehavior class
 *
 * Contains a filter condition for the query test
 * testQueryWithBehaviorCallCondition.
 */
class FilterBehavior extends ModelBehavior
{
    /**
     * mostFilterConditions
     *
     * @param Model $Model
     * @param array $data
     * @return array
     */
    public function mostFilterConditions(Model $Model, $data = [])
    {
        $filter = $data["filter"];
        if (!in_array($filter, ["views", "comments"])) {
            return [];
        }
        switch ($filter) {
            case "views":
                $cond = $Model->alias . ".views > 10";
                break;
            case "comments":
                $cond = $Model->alias . ".comments > 10";
                break;
        }
        return (array) $cond;
    }
}

/**
 * Tag test model
 */
class Tag extends CakeTestModel
{
}

/**
 * Tagged test model
 */
class Tagged extends CakeTestModel
{
    /**
     * Table to use
     *
     * @var string
     */
    public $useTable = "tagged";

    /**
     * Belongs To Associations
     *
     * @var array
     */
    public $belongsTo = ["Tag"];
}

/**
 * Article test model
 *
 * Contains various find and condition methods used by the tests below.
 */
class Article extends CakeTestModel
{
    /**
     * Attach the SearchableBehavior by default
     *
     * @var array
     */
    public $actsAs = ["Search.Searchable"];

    /**
     * HABTM associations
     *
     * @var array
     */
    public $hasAndBelongsToMany = ["Tag" => ["with" => "Tagged"]];

    /**
     * Find by tags
     *
     * @param array $data
     * @return array
     */
    public function findByTags($data = [])
    {
        $this->Tagged->Behaviors->attach("Containable", [
            "autoFields" => false,
        ]);
        $this->Tagged->Behaviors->attach("Search.Searchable");
        $conditions = [];
        if (!empty($data["tags"])) {
            $conditions = ["Tag.name" => $data["tags"]];
        }
        $this->Tagged->order = null;
        $query = $this->Tagged->getQuery("all", [
            "conditions" => $conditions,
            "fields" => ["foreign_key"],
            "contain" => ["Tag"],
        ]);
        return $query;
    }

    /**
     * Makes an array of range numbers that matches the ones on the interface.
     *
     * @param $data
     * @param null $field
     * @return array
     */
    public function makeRangeCondition($data, $field = null)
    {
        if (is_string($data)) {
            $input = $data;
        }
        if (is_array($data)) {
            if (!empty($field["name"])) {
                $input = $data[$field["name"]];
            } else {
                $input = $data["range"];
            }
        }
        switch ($input) {
            case "10":
                return [0, 10];
            case "100":
                return [11, 100];
            case "1000":
                return [101, 1000];
            default:
                return [0, 0];
        }
    }

    /**
     * orConditions
     *
     * @param array $data
     * @return array
     */
    public function orConditions($data = [])
    {
        $filter = $data["filter"];
        $cond = [
            "OR" => [
                $this->alias . ".title LIKE" => "%" . $filter . "%",
                $this->alias . ".body LIKE" => "%" . $filter . "%",
            ],
        ];
        return $cond;
    }

    public function or2Conditions($data = [])
    {
        $filter = $data["filter2"];
        $cond = [
            "OR" => [
                $this->alias . ".field1 LIKE" => "%" . $filter . "%",
                $this->alias . ".field2 LIKE" => "%" . $filter . "%",
            ],
        ];
        return $cond;
    }
}

/**
 * SearchableTestCase
 */
class SearchableBehaviorTest extends CakeTestCase
{
    /**
     * Article test model
     *
     * @var
     */
    public $Article;

    /**
     * Load relevant fixtures
     *
     * @var array
     */
    public $fixtures = [
        "plugin.search.article",
        "plugin.search.tag",
        "plugin.search.tagged",
        "core.user",
    ];

    /**
     * Load Article test model
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        $this->Article = ClassRegistry::init("Article");
    }

    /**
     * Release Article test model
     *
     * @return void
     */
    public function tearDown()
    {
        parent::tearDown();

        unset($this->Article);
    }

    /**
     * Test getWildcards()
     *
     * @return void
     */
    public function testGetWildcards()
    {
        $result = $this->Article->getWildcards();
        $expected = ["any" => "*", "one" => "?"];
        $this->assertSame($expected, $result);

        $this->Article->Behaviors->Searchable->settings["Article"][
            "wildcardAny"
        ] = false;
        $this->Article->Behaviors->Searchable->settings["Article"][
            "wildcardOne"
        ] = false;
        $result = $this->Article->getWildcards();
        $expected = ["any" => false, "one" => false];
        $this->assertSame($expected, $result);

        $this->Article->Behaviors->Searchable->settings["Article"][
            "wildcardAny"
        ] = "%";
        $this->Article->Behaviors->Searchable->settings["Article"][
            "wildcardOne"
        ] = "_";
        $result = $this->Article->getWildcards();
        $expected = ["any" => "%", "one" => "_"];
        $this->assertSame($expected, $result);
    }

    /**
     * Test 'value' filter type
     *
     * @return void
     * @link http://github.com/CakeDC/Search/issues#issue/3
     */
    public function testValueCondition()
    {
        $this->Article->filterArgs = [["name" => "slug", "type" => "value"]];
        $this->Article->Behaviors->load("Search.Searchable");
        $data = [];
        $result = $this->Article->parseCriteria($data);
        $this->assertEquals([], $result);

        $data = ["slug" => "first_article"];
        $result = $this->Article->parseCriteria($data);
        $expected = ["Article.slug" => "first_article"];
        $this->assertEquals($expected, $result);

        $this->Article->filterArgs = [
            [
                "name" => "fakeslug",
                "type" => "value",
                "field" => "Article2.slug",
            ],
        ];
        $this->Article->Behaviors->load("Search.Searchable");
        $data = ["fakeslug" => "first_article"];
        $result = $this->Article->parseCriteria($data);
        $expected = ["Article2.slug" => "first_article"];
        $this->assertEquals($expected, $result);

        // Testing http://github.com/CakeDC/Search/issues#issue/3
        $this->Article->filterArgs = [["name" => "views", "type" => "value"]];
        $this->Article->Behaviors->load("Search.Searchable");
        $data = ["views" => "0"];
        $result = $this->Article->parseCriteria($data);
        $this->assertEquals(["Article.views" => 0], $result);

        $this->Article->filterArgs = [["name" => "views", "type" => "value"]];
        $this->Article->Behaviors->load("Search.Searchable");
        $data = ["views" => 0];
        $result = $this->Article->parseCriteria($data);
        $this->assertEquals(["Article.views" => 0], $result);

        $this->Article->filterArgs = [["name" => "views", "type" => "value"]];
        $this->Article->Behaviors->load("Search.Searchable");
        $data = ["views" => ""];
        $result = $this->Article->parseCriteria($data);
        $this->assertEquals([], $result);

        // Multiple fields + cross model searches
        $this->Article->filterArgs = [
            "faketitle" => [
                "type" => "value",
                "field" => ["title", "User.name"],
            ],
        ];
        $this->Article->Behaviors->load("Search.Searchable");
        $data = ["faketitle" => "First"];
        $result = $this->Article->parseCriteria($data);
        $expected = [
            "OR" => ["Article.title" => "First", "User.name" => "First"],
        ];
        $this->assertEquals($expected, $result);

        // Multiple select dropdown
        $this->Article->filterArgs = [
            "fakesource" => ["type" => "value"],
        ];
        $this->Article->Behaviors->load("Search.Searchable");
        $data = ["fakesource" => [5, 9]];
        $result = $this->Article->parseCriteria($data);
        $expected = ["Article.fakesource" => [5, 9]];
        $this->assertEquals($expected, $result);
    }

    /**
     * Test 'like' filter type
     *
     * @return void
     */
    public function testLikeCondition()
    {
        $this->Article->filterArgs = [["name" => "title", "type" => "like"]];
        $this->Article->Behaviors->load("Search.Searchable");

        $data = [];
        $result = $this->Article->parseCriteria($data);
        $this->assertEquals([], $result);

        $data = ["title" => "First"];
        $result = $this->Article->parseCriteria($data);
        $expected = ["Article.title LIKE" => "%First%"];
        $this->assertEquals($expected, $result);

        $this->Article->filterArgs = [
            [
                "name" => "faketitle",
                "type" => "like",
                "field" => "Article.title",
            ],
        ];
        $this->Article->Behaviors->load("Search.Searchable");

        $data = ["faketitle" => "First"];
        $result = $this->Article->parseCriteria($data);
        $expected = ["Article.title LIKE" => "%First%"];
        $this->assertEquals($expected, $result);

        // Wildcards should be treated as normal text
        $this->Article->filterArgs = [
            [
                "name" => "faketitle",
                "type" => "like",
                "field" => "Article.title",
            ],
        ];
        $this->Article->Behaviors->load("Search.Searchable");
        $data = ["faketitle" => "%First_"];
        $result = $this->Article->parseCriteria($data);
        $expected = ["Article.title LIKE" => "%\%First\_%"];
        $this->assertEquals($expected, $result);

        // Working with like settings
        $this->Article->Behaviors->Searchable->settings["Article"]["like"][
            "before"
        ] = false;
        $result = $this->Article->parseCriteria($data);
        $expected = ["Article.title LIKE" => "\%First\_%"];
        $this->assertEquals($expected, $result);

        $this->Article->Behaviors->Searchable->settings["Article"]["like"][
            "after"
        ] = false;
        $result = $this->Article->parseCriteria($data);
        $expected = ["Article.title LIKE" => "\%First\_"];
        $this->assertEquals($expected, $result);

        // Now custom like should be possible
        $data = ["faketitle" => "*First?"];
        $this->Article->Behaviors->Searchable->settings["Article"]["like"][
            "after"
        ] = false;
        $result = $this->Article->parseCriteria($data);
        $expected = ["Article.title LIKE" => "%First_"];
        $this->assertEquals($expected, $result);

        $data = ["faketitle" => "F?rst"];
        $this->Article->Behaviors->Searchable->settings["Article"]["like"][
            "before"
        ] = true;
        $this->Article->Behaviors->Searchable->settings["Article"]["like"][
            "after"
        ] = true;
        $result = $this->Article->parseCriteria($data);
        $expected = ["Article.title LIKE" => "%F_rst%"];
        $this->assertEquals($expected, $result);

        $data = ["faketitle" => "F*t"];
        $this->Article->Behaviors->Searchable->settings["Article"]["like"][
            "before"
        ] = true;
        $this->Article->Behaviors->Searchable->settings["Article"]["like"][
            "after"
        ] = true;
        $result = $this->Article->parseCriteria($data);
        $expected = ["Article.title LIKE" => "%F%t%"];
        $this->assertEquals($expected, $result);

        // now we try the default wildcards % and _
        $data = ["faketitle" => "*First?"];
        $this->Article->Behaviors->Searchable->settings["Article"]["like"][
            "before"
        ] = false;
        $this->Article->Behaviors->Searchable->settings["Article"]["like"][
            "after"
        ] = false;
        $this->Article->Behaviors->Searchable->settings["Article"][
            "wildcardAny"
        ] = "%";
        $this->Article->Behaviors->Searchable->settings["Article"][
            "wildcardOne"
        ] = "_";
        $result = $this->Article->parseCriteria($data);
        $expected = ["Article.title LIKE" => "*First?"];
        $this->assertEquals($expected, $result);

        // Now it is possible and makes sense to allow wildcards in between (custom wildcard use case)
        $data = ["faketitle" => "%Fi_st_"];
        $result = $this->Article->parseCriteria($data);
        $expected = ["Article.title LIKE" => "%Fi_st_"];
        $this->assertEquals($expected, $result);

        // Shortcut disable/enable like before/after
        $data = ["faketitle" => "%First_"];
        $this->Article->Behaviors->Searchable->settings["Article"][
            "like"
        ] = false;
        $result = $this->Article->parseCriteria($data);
        $expected = ["Article.title LIKE" => "%First_"];
        $this->assertEquals($expected, $result);

        // Multiple OR fields per field
        $this->Article->filterArgs = [
            [
                "name" => "faketitle",
                "type" => "like",
                "field" => ["title", "descr"],
            ],
        ];
        $this->Article->Behaviors->load("Search.Searchable");
        $data = ["faketitle" => "First"];
        $this->Article->Behaviors->Searchable->settings["Article"][
            "like"
        ] = true;
        $result = $this->Article->parseCriteria($data);
        $expected = [
            "OR" => [
                "Article.title LIKE" => "%First%",
                "Article.descr LIKE" => "%First%",
            ],
        ];
        $this->assertEquals($expected, $result);

        // Set before => false dynamically
        $this->Article->filterArgs = [
            [
                "name" => "faketitle",
                "type" => "like",
                "field" => ["title", "descr"],
                "before" => false,
            ],
        ];
        $this->Article->Behaviors->load("Search.Searchable");
        $data = ["faketitle" => "First"];
        $result = $this->Article->parseCriteria($data);
        $expected = [
            "OR" => [
                "Article.title LIKE" => "First%",
                "Article.descr LIKE" => "First%",
            ],
        ];
        $this->assertEquals($expected, $result);

        // Manually define the before/after type
        $this->Article->filterArgs = [
            [
                "name" => "faketitle",
                "type" => "like",
                "field" => ["title"],
                "before" => "_",
                "after" => "_",
            ],
        ];
        $this->Article->Behaviors->load("Search.Searchable");
        $data = ["faketitle" => "First"];
        $result = $this->Article->parseCriteria($data);
        $expected = ["Article.title LIKE" => "_First_"];
        $this->assertEquals($expected, $result);

        // Cross model searches + named keys (shorthand)
        $this->Article->bindModel(["belongsTo" => ["User"]]);
        $this->Article->filterArgs = [
            "faketitle" => [
                "type" => "like",
                "field" => ["title", "User.name"],
                "before" => false,
                "after" => true,
            ],
        ];
        $this->Article->Behaviors->load("Search.Searchable");
        $data = ["faketitle" => "First"];
        $result = $this->Article->parseCriteria($data);
        $expected = [
            "OR" => [
                "Article.title LIKE" => "First%",
                "User.name LIKE" => "First%",
            ],
        ];
        $this->assertEquals($expected, $result);

        // With already existing or conditions + named keys (shorthand)
        $this->Article->filterArgs = [
            "faketitle" => [
                "type" => "like",
                "field" => ["title", "User.name"],
                "before" => false,
                "after" => true,
            ],
            "otherfaketitle" => [
                "type" => "like",
                "field" => ["descr", "comment"],
                "before" => false,
                "after" => true,
            ],
        ];
        $this->Article->Behaviors->load("Search.Searchable");

        $data = ["faketitle" => "First", "otherfaketitle" => "Second"];
        $result = $this->Article->parseCriteria($data);
        $expected = [
            "OR" => [
                "Article.title LIKE" => "First%",
                "User.name LIKE" => "First%",
            ],
            [
                "OR" => [
                    "Article.descr LIKE" => "Second%",
                    "Article.comment LIKE" => "Second%",
                ],
            ],
        ];
        $this->assertEquals($expected, $result);

        // Wildcards and and/or connectors
        $this->Article->Behaviors->unload("Search.Searchable");
        $this->Article->filterArgs = [
            [
                "name" => "faketitle",
                "type" => "like",
                "field" => "Article.title",
                "connectorAnd" => "+",
                "connectorOr" => ",",
                "before" => true,
                "after" => true,
            ],
        ];
        $this->Article->Behaviors->load("Search.Searchable");
        $data = ["faketitle" => "First%+Second%, Third%"];
        $result = $this->Article->parseCriteria($data);
        $expected = [
            0 => [
                "OR" => [
                    [
                        "AND" => [
                            ["Article.title LIKE" => "%First\%%"],
                            ["Article.title LIKE" => "%Second\%%"],
                        ],
                    ],
                    ["AND" => [["Article.title LIKE" => "%Third\%%"]]],
                ],
            ],
        ];
        $this->assertEquals($expected, $result);
    }

    /**
     * Test 'subquery' filter type
     *
     * @return void
     */
    public function testSubQueryCondition()
    {
        if ($this->db->config["datasource"] !== "Database/Mysql") {
            $this->markTestSkipped("Test requires mysql db.");
        }
        $database = $this->db->config["database"];

        $this->Article->filterArgs = [
            [
                "name" => "tags",
                "type" => "subquery",
                "method" => "findByTags",
                "field" => "Article.id",
            ],
        ];

        $data = [];
        $result = $this->Article->parseCriteria($data);
        $this->assertEquals([], $result);

        $data = ["tags" => "Cake"];
        $result = $this->Article->parseCriteria($data);
        $expression = $this->Article
            ->getDatasource()
            ->expression(
                "Article.id in (SELECT `Tagged`.`foreign_key` FROM `" .
                    $database .
                    "`.`" .
                    $this->Article->tablePrefix .
                    "tagged` AS `Tagged` LEFT JOIN `" .
                    $database .
                    "`.`" .
                    $this->Article->tablePrefix .
                    'tags` AS `Tag` ON (`Tagged`.`tag_id` = `Tag`.`id`)  WHERE `Tag`.`name` = \'Cake\')'
            );
        $expected = [$expression];
        $this->assertEquals($expected, $result);
    }

    /**
     * Test 'subquery' filter type when 'allowEmpty' = true
     *
     * @return void
     */
    public function testSubQueryEmptyCondition()
    {
        if ($this->db->config["datasource"] !== "Database/Mysql") {
            $this->markTestSkipped("Test requires mysql db.");
        }
        $database = $this->db->config["database"];

        // Old syntax
        $this->Article->filterArgs = [
            [
                "name" => "tags",
                "type" => "subquery",
                "method" => "findByTags",
                "field" => "Article.id",
                "allowEmpty" => true,
            ],
        ];

        $data = ["tags" => "Cake"];
        $this->Article->parseCriteria($data);
        $expression = $this->Article
            ->getDatasource()
            ->expression(
                "Article.id in (SELECT `Tagged`.`foreign_key` FROM `" .
                    $database .
                    "`.`" .
                    $this->Article->tablePrefix .
                    "tagged` AS `Tagged` LEFT JOIN `" .
                    $database .
                    "`.`" .
                    $this->Article->tablePrefix .
                    'tags` AS `Tag` ON (`Tagged`.`tag_id` = `Tag`.`id`)  WHERE `Tag`.`name` = \'Cake\')'
            );
        $expected = [$expression];

        // New syntax
        $this->Article->filterArgs = [
            "tags" => [
                "type" => "subquery",
                "method" => "findByTags",
                "field" => "Article.id",
                "allowEmpty" => true,
            ],
        ];
        $this->Article->Behaviors->load("Search.Searchable");

        $result = $this->Article->parseCriteria($data);
        $this->assertEquals($expected, $result);
    }

    /**
     * Test 'query' filter type with one orConditions method
     *
     * Uses ``Article::orConditions()``.
     *
     * @return void
     */
    public function testQueryOneOrConditions()
    {
        $this->Article->filterArgs = [
            ["name" => "filter", "type" => "query", "method" => "orConditions"],
        ];

        $data = [];
        $result = $this->Article->parseCriteria($data);
        $this->assertEquals([], $result);

        $data = ["filter" => "ticl"];
        $result = $this->Article->parseCriteria($data);
        $expected = [
            "OR" => [
                "Article.title LIKE" => "%ticl%",
                "Article.body LIKE" => "%ticl%",
            ],
        ];
        $this->assertEquals($expected, $result);
    }

    /**
     * Test 'query' filter type with two orConditions methods
     *
     * Uses ``Article::orConditions()`` and ``Article::or2Conditions()``.
     *
     * @return void
     */
    public function testQueryOrTwoOrConditions()
    {
        $this->Article->filterArgs = [
            ["name" => "filter", "type" => "query", "method" => "orConditions"],
            [
                "name" => "filter2",
                "type" => "query",
                "method" => "or2Conditions",
            ],
        ];

        $data = [];
        $result = $this->Article->parseCriteria($data);
        $this->assertEquals([], $result);

        $data = ["filter" => "ticl", "filter2" => "test"];
        $result = $this->Article->parseCriteria($data);
        $expected = [
            "OR" => [
                "Article.title LIKE" => "%ticl%",
                "Article.body LIKE" => "%ticl%",
                "Article.field1 LIKE" => "%test%",
                "Article.field2 LIKE" => "%test%",
            ],
        ];
        $this->assertEquals($expected, $result);
    }

    /**
     * Test 'query' filter type with behavior condition method
     *
     * Uses ``FilterBehavior::FilterBehavior::mostFilterConditions()``.
     *
     * @return void
     */
    public function testQueryWithBehaviorCondition()
    {
        $this->Article->Behaviors->load("Filter");
        $this->Article->filterArgs = [
            [
                "name" => "filter",
                "type" => "query",
                "method" => "mostFilterConditions",
            ],
        ];

        $data = [];
        $result = $this->Article->parseCriteria($data);
        $this->assertEquals([], $result);

        $data = ["filter" => "views"];
        $result = $this->Article->parseCriteria($data);
        $expected = ["Article.views > 10"];
        $this->assertEquals($expected, $result);
    }

    /**
     * Test 'expression' filter type
     *
     * Uses ``Article::makeRangeCondition()`` and
     * a non-existent one.
     *
     * @return void
     */
    public function testExpressionCallCondition()
    {
        $this->Article->filterArgs = [
            [
                "name" => "range",
                "type" => "expression",
                "method" => "makeRangeCondition",
                "field" => "Article.views BETWEEN ? AND ?",
            ],
        ];
        $data = [];
        $result = $this->Article->parseCriteria($data);
        $this->assertEquals([], $result);

        $data = ["range" => "10"];
        $result = $this->Article->parseCriteria($data);
        $expected = ["Article.views BETWEEN ? AND ?" => [0, 10]];
        $this->assertEquals($expected, $result);

        $this->Article->filterArgs = [
            [
                "name" => "range",
                "type" => "expression",
                "method" => "testThatInBehaviorMethodNotDefined",
                "field" => "Article.views BETWEEN ? AND ?",
            ],
        ];
        $data = ["range" => "10"];
        $result = $this->Article->parseCriteria($data);
        $this->assertEquals([], $result);
    }

    /**
     * Test 'query' filter type with 'defaultValue' set
     *
     * @return void
     */
    public function testDefaultValue()
    {
        $this->Article->filterArgs = [
            "range" => [
                "type" => "expression",
                "defaultValue" => "100",
                "method" => "makeRangeCondition",
                "field" => "Article.views BETWEEN ? AND ?",
            ],
        ];
        $this->Article->Behaviors->load("Search.Searchable");

        $data = [];
        $result = $this->Article->parseCriteria($data);
        $expected = [
            "Article.views BETWEEN ? AND ?" => [11, 100],
        ];
        $this->assertEquals($expected, $result);
    }

    /**
     * Test unbindAllModels()
     *
     * @return void
     */
    public function testUnbindAll()
    {
        $this->Article->unbindAllModels();
        $this->assertEquals([], $this->Article->belongsTo);
        $this->assertEquals([], $this->Article->hasMany);
        $this->assertEquals([], $this->Article->hasAndBelongsToMany);
        $this->assertEquals([], $this->Article->hasOne);
    }

    /**
     * Test validateSearch()
     *
     * @return void
     */
    public function testValidateSearch()
    {
        $this->Article->filterArgs = [];
        $data = ["Article" => ["title" => "Last Article"]];
        $this->Article->set($data);
        $this->Article->validateSearch();
        $this->assertEquals($data, $this->Article->data);

        $this->Article->validateSearch($data);
        $this->assertEquals($data, $this->Article->data);

        $data = ["Article" => ["title" => ""]];
        $this->Article->validateSearch($data);
        $expected = ["Article" => []];
        $this->assertEquals($expected, $this->Article->data);
    }

    /**
     * Test passedArgs()
     *
     * @return void
     */
    public function testPassedArgs()
    {
        $this->Article->filterArgs = [["name" => "slug", "type" => "value"]];
        $data = ["slug" => "first_article", "filter" => "myfilter"];
        $result = $this->Article->passedArgs($data);
        $expected = ["slug" => "first_article"];
        $this->assertEquals($expected, $result);
    }

    /**
     * Test getQuery()
     *
     * @return void
     */
    public function testGetQuery()
    {
        if ($this->db->config["datasource"] !== "Database/Mysql") {
            $this->markTestSkipped("Test requires mysql db.");
        }
        $database = $this->db->config["database"];

        $conditions = ["Article.id" => 1];
        $result = $this->Article->getQuery("all", [
            "conditions" => $conditions,
            "order" => "title",
            "page" => 2,
            "limit" => 2,
            "fields" => ["id", "title"],
        ]);
        $expected =
            "SELECT `Article`.`id`, `Article`.`title` FROM `" .
            $database .
            "`.`" .
            $this->Article->tablePrefix .
            "articles` AS `Article`   WHERE `Article`.`id` = 1   ORDER BY `title` ASC  LIMIT 2, 2";
        $this->assertEquals($expected, $result);

        $this->Article->Tagged->Behaviors->attach("Search.Searchable");
        $conditions = ["Tagged.tag_id" => 1];
        $this->Article->Tagged->recursive = -1;
        $order = ["Tagged.id" => "ASC"];
        $result = $this->Article->Tagged->getQuery(
            "first",
            compact("conditions", "order")
        );
        $expected =
            "SELECT `Tagged`.`id`, `Tagged`.`foreign_key`, `Tagged`.`tag_id`, " .
            "`Tagged`.`model`, `Tagged`.`language`, `Tagged`.`created`, `Tagged`.`modified` " .
            "FROM `" .
            $database .
            "`.`" .
            $this->Article->tablePrefix .
            'tagged` AS `Tagged`   WHERE `Tagged`.`tag_id` = \'1\'   ORDER BY `Tagged`.`id` ASC  LIMIT 1';
        $this->assertEquals($expected, $result);
    }

    /**
     * Test whether 'allowEmpty' will be respected
     *
     * @return void
     */
    public function testAllowEmptyWithNullValues()
    {
        // Author is just empty, created will be mapped against schema default (NULL)
        // and slug omitted as its NULL already
        $this->Article->filterArgs = [
            "title" => [
                "name" => "title",
                "type" => "like",
                "field" => "Article.title",
                "allowEmpty" => true,
            ],
            "author" => [
                "name" => "author",
                "type" => "value",
                "field" => "Article.author",
                "allowEmpty" => true,
            ],
            "created" => [
                "name" => "created",
                "type" => "value",
                "field" => "Article.created",
                "allowEmpty" => true,
            ],
            "slug" => [
                "name" => "slug",
                "type" => "value",
                "field" => "Article.slug",
                "allowEmpty" => true,
            ],
        ];
        $data = [
            "title" => "first",
            "author" => "",
            "created" => "",
            "slug" => null,
        ];
        $expected = [
            "Article.title LIKE" => "%first%",
            "Article.author" => "",
            "Article.created" => null,
        ];
        $result = $this->Article->parseCriteria($data);
        $this->assertSame($expected, $result);
    }
}
