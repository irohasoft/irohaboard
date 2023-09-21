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

App::uses("Controller", "Controller");
App::uses("PrgComponent", "Search.Controller/Component");
App::uses("Router", "Routing");

/**
 * Post-Redirect-Get: Transfers POST Requests to GET Requests tests
 *
 */
class Post extends CakeTestModel
{
    /**
     * Behaviors
     *
     * @var array
     */
    public $actsAs = ["Search.Searchable"];
}

/**
 * Posts Test Controller
 */
class PostsTestController extends Controller
{
    /**
     * Models to use
     *
     * @var array
     */
    public $uses = ["Post"];

    /**
     * Components
     *
     * @var array
     */
    public $components = [
        "Search.Prg" => [
            "commonProcess" => ["paramType" => "named"],
            "presetForm" => ["paramType" => "named"],
        ],
        "Session",
    ];

    /**
     * beforeFilter
     *
     * @return void
     */
    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->Prg->actions = [
            "search" => [
                "controller" => "Posts",
                "action" => "result",
            ],
        ];
    }

    /**
     * Overwrite redirect
     *
     * @param string $url The URL to redirect to
     * @param string $status Not used
     * @param bool|string $exit Not used
     * @return void
     */
    public function redirect($url, $status = null, $exit = true)
    {
        $this->redirectUrl = $url;
    }
}

/**
 * Posts Options Test Controller
 */
class PostOptionsTestController extends PostsTestController
{
    /**
     * Components
     *
     * @var array
     */
    public $components = [
        "Search.Prg" => [
            "commonProcess" => [
                "form" => "Post",
                "modelMethod" => false,
                "allowedParams" => ["lang"],
            ],
        ],
        "Session",
    ];
}

/**
 * PRG Component Test
 */
class PrgComponentTest extends CakeTestCase
{
    /**
     * Load relevant fixtures
     *
     * @var array
     */
    public $fixtures = ["plugin.search.post"];

    /**
     * Setup test controller
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        Configure::delete("Search");

        $this->Controller = new PostsTestController(
            new CakeRequest(),
            new CakeResponse()
        );
        $this->Controller->constructClasses();
        $this->Controller->startupProcess();
        $this->Controller->request->params = [
            "named" => [],
            "pass" => [],
            "url" => [],
        ];
        $this->Controller->request->query = [];
    }

    /**
     * Release test controller
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Controller);
        ClassRegistry::flush();

        parent::tearDown();
    }

    /**
     * Test options
     *
     * @return void
     */
    public function testOptions()
    {
        $this->Controller->presetVars = [];
        $this->Controller->action = "search";
        $this->Controller->request->data = [
            "Post" => [
                "title" => "test",
            ],
        ];

        $this->Controller->Prg->commonProcess("Post");
        $expected = [
            "title" => "test",
            "action" => "search",
        ];
        $this->assertEquals($expected, $this->Controller->redirectUrl);

        $this->Controller->request->params = array_merge(
            $this->Controller->request->params,
            [
                "lang" => "en",
            ]
        );
        $this->Controller->Prg->commonProcess("Post", [
            "allowedParams" => ["lang"],
        ]);
        $expected = [
            "title" => "test",
            "action" => "search",
            "lang" => "en",
        ];
        $this->assertEquals($expected, $this->Controller->redirectUrl);

        $this->Controller->presetVars = [
            ["field" => "title", "type" => "value"],
        ];
        $this->Controller->Prg->commonProcess("Post", [
            "paramType" => "querystring",
        ]);
        $expected = ["action" => "search", "?" => ["title" => "test"]];
        $this->assertEquals($expected, $this->Controller->redirectUrl);
    }

    /**
     * Test presetForm()
     *
     * @return void
     */
    public function testPresetForm()
    {
        $this->Controller->presetVars = [
            [
                "field" => "title",
                "type" => "value",
            ],
            [
                "field" => "checkbox",
                "type" => "checkbox",
            ],
            [
                "field" => "lookup",
                "type" => "lookup",
                "formField" => "lookup_input",
                "modelField" => "title",
                "model" => "Post",
            ],
        ];
        $this->Controller->passedArgs = [
            "title" => "test",
            "checkbox" => "test|test2|test3",
            "lookup" => "1",
        ];
        $this->Controller->beforeFilter();

        $this->Controller->Prg->presetForm("Post");
        $expected = [
            "Post" => [
                "title" => "test",
                "checkbox" => [
                    0 => "test",
                    1 => "test2",
                    2 => "test3",
                ],
                "lookup" => 1,
                "lookup_input" => "First Post",
            ],
        ];
        $this->assertEquals($expected, $this->Controller->request->data);

        $this->Controller->data = [];
        $this->Controller->passedArgs = [];
        $this->Controller->request->query = [
            "title" => "test",
            "checkbox" => "test|test2|test3",
            "lookup" => "1",
        ];
        $this->Controller->beforeFilter();

        $this->Controller->Prg->presetForm([
            "model" => "Post",
            "paramType" => "querystring",
        ]);
        $this->assertTrue($this->Controller->Prg->isSearch);
        $this->assertEquals(
            $expected["Post"],
            $this->Controller->Prg->parsedParams()
        );

        // Deprecated
        $this->assertEquals($expected, $this->Controller->data);
    }

    /**
     * Test presetForm() when passed args are empty
     *
     * @return void
     */
    public function testPresetFormEmpty()
    {
        $this->Controller->presetVars = [
            [
                "field" => "title",
                "type" => "value",
            ],
            [
                "field" => "checkbox",
                "type" => "checkbox",
            ],
            [
                "field" => "lookup",
                "type" => "lookup",
                "formField" => "lookup_input",
                "modelField" => "title",
                "model" => "Post",
            ],
        ];
        $this->Controller->passedArgs = [
            "page" => "2",
        ];
        $this->Controller->beforeFilter();

        $this->Controller->Prg->presetForm("Post");
        $expected = [
            "Post" => [],
        ];
        $this->assertEquals($expected, $this->Controller->request->data);

        $this->Controller->data = [];
        $this->Controller->passedArgs = [];
        $this->Controller->request->query = [
            "page" => "2",
        ];
        $this->Controller->beforeFilter();

        $this->Controller->Prg->presetForm([
            "model" => "Post",
            "paramType" => "querystring",
        ]);
        $this->assertFalse($this->Controller->Prg->isSearch);
        $this->assertEquals(
            $expected["Post"],
            $this->Controller->Prg->parsedParams()
        );

        // Deprecated
        $this->assertEquals($expected, $this->Controller->data);
    }

    /**
     * Test search on integer when zero is entered
     *
     * This test checks that the search on an integer type field in the database
     * works correctly when a 0 (zero) is entered in the form.
     *
     * @return void
     * @link http://github.com/CakeDC/Search/issues#issue/3
     */
    public function testPresetFormWithIntegerField()
    {
        $this->Controller->presetVars = [
            [
                "field" => "views",
                "type" => "value",
            ],
        ];
        $this->Controller->passedArgs = [
            "views" => "0",
        ];
        $this->Controller->beforeFilter();

        $this->Controller->Prg->presetForm("Post");
        $expected = [
            "Post" => [
                "views" => "0",
            ],
        ];
        $this->assertEquals($expected, $this->Controller->request->data);
        $this->assertTrue($this->Controller->Prg->isSearch);
        $this->assertEquals(
            $expected["Post"],
            $this->Controller->Prg->parsedParams()
        );
    }

    /**
     * Test serializeParams()
     *
     * @return void
     */
    public function testSerializeParams()
    {
        $this->Controller->presetVars = [
            [
                "field" => "options",
                "type" => "checkbox",
            ],
        ];
        $testData = [
            "options" => [
                0 => "test1",
                1 => "test2",
                2 => "test3",
            ],
        ];
        $result = $this->Controller->Prg->serializeParams($testData);
        $this->assertEquals(["options" => "test1|test2|test3"], $result);

        $testData = ["options" => ""];

        $result = $this->Controller->Prg->serializeParams($testData);
        $this->assertEquals(["options" => ""], $result);

        $testData = [];
        $result = $this->Controller->Prg->serializeParams($testData);
        $this->assertEquals(["options" => ""], $result);
    }

    /**
     * Test connectNamed()
     *
     * @return void
     */
    public function testConnectNamed()
    {
        $this->Controller->passedArgs = [
            "title" => "test",
        ];
        $this->assertNull($this->Controller->Prg->connectNamed());
        $this->assertNull($this->Controller->Prg->connectNamed(1));
    }

    /**
     * Test exclude()
     *
     * @return void
     */
    public function testExclude()
    {
        $this->Controller->request->params["named"] = [];

        $array = ["foo" => "test", "bar" => "test", "test" => "test"];
        $exclude = ["bar", "test"];
        $result = $this->Controller->Prg->exclude($array, $exclude);
        $this->assertEquals(["foo" => "test"], $result);

        $array = [
            "foo" => "test",
            "bar" => "test",
            "test" => "test",
            0 => "passed",
            1 => "passed_again",
        ];
        $exclude = ["bar", "test"];
        $result = $this->Controller->Prg->exclude($array, $exclude);
        $this->assertEquals(
            ["foo" => "test", 0 => "passed", 1 => "passed_again"],
            $result
        );
    }

    /**
     * Test commonProcess()
     *
     * @return void
     */
    public function testCommonProcess()
    {
        $this->Controller->request->params["named"] = [];
        $this->Controller->presetVars = [];
        $this->Controller->action = "search";
        $this->Controller->request->data = [
            "Post" => [
                "title" => "test",
            ],
        ];
        $this->Controller->Prg->commonProcess("Post", [
            "form" => "Post",
            "modelMethod" => false,
        ]);
        $expected = [
            "title" => "test",
            "action" => "search",
        ];
        $this->assertEquals($expected, $this->Controller->redirectUrl);

        $this->Controller->Prg->commonProcess(null, [
            "modelMethod" => false,
        ]);
        $expected = [
            "title" => "test",
            "action" => "search",
        ];
        $this->assertEquals($expected, $this->Controller->redirectUrl);

        $this->Controller->Post->filterArgs = [
            ["name" => "title", "type" => "value"],
        ];
        $this->Controller->Prg->commonProcess("Post");
        $expected = [
            "title" => "test",
            "action" => "search",
        ];
        $this->assertEquals($expected, $this->Controller->redirectUrl);
    }

    /**
     * Test commonProcess() with presetVars not empty
     *
     * Fixing warning when checking undefined $presetVar['name'].
     *
     * @return void
     */
    public function testCommonProcessWithPresetVarsNotEmpty()
    {
        $this->Controller->request->params["named"] = [];
        $this->Controller->presetVars = ["title" => ["type" => "value"]];

        $this->Controller->action = "search";
        $this->Controller->request->data = [
            "Post" => [
                "title" => "test",
            ],
        ];
        $this->Controller->Prg->commonProcess("Post");
        $expected = [
            "title" => "test",
            "action" => "search",
        ];
        $this->assertEquals($expected, $this->Controller->redirectUrl);
    }

    /**
     * Test commonProcess() with 'allowedParams' set
     *
     * @return void
     */
    public function testCommonProcessAllowedParams()
    {
        $this->Controller->request->params = array_merge(
            $this->Controller->request->params,
            [
                "named" => [],
                "lang" => "en",
            ]
        );
        $this->Controller->presetVars = [];
        $this->Controller->action = "search";
        $this->Controller->request->data = [
            "Post" => [
                "title" => "test",
            ],
        ];
        $this->Controller->Prg->commonProcess("Post", [
            "form" => "Post",
            "modelMethod" => false,
            "allowedParams" => ["lang"],
        ]);
        $expected = [
            "title" => "test",
            "action" => "search",
            "lang" => "en",
        ];
        $this->assertEquals($expected, $this->Controller->redirectUrl);
    }

    /**
     * Test commonProcess() when resetting 'named'
     *
     * @return void
     */
    public function testCommonProcessResetNamed()
    {
        $this->Controller->request->params = array_merge(
            $this->Controller->request->params,
            [
                "named" => [
                    "page" => 2,
                    "sort" => "name",
                    "direction" => "asc",
                ],
                "lang" => "en",
            ]
        );
        $this->Controller->presetVars = [];
        $this->Controller->action = "search";
        $this->Controller->request->data = [
            "Post" => [
                "title" => "test",
                "foo" => "",
                "bar" => "",
            ],
        ];
        $this->Controller->Prg->commonProcess("Post", [
            "form" => "Post",
            "modelMethod" => false,
            "allowedParams" => ["lang"],
        ]);
        $expected = [
            "sort" => "name",
            "direction" => "asc",
            "title" => "test",
            "foo" => "",
            "bar" => "",
            "action" => "search",
            "lang" => "en",
        ];
        $this->assertEquals($expected, $this->Controller->redirectUrl);
    }

    /**
     * Test commonProcess() when 'filterEmpty' = true
     *
     * @return void
     */
    public function testCommonProcessFilterEmpty()
    {
        $this->Controller->request->params = array_merge(
            $this->Controller->request->params,
            [
                "named" => [],
                "lang" => "en",
            ]
        );
        $this->Controller->presetVars = [];
        $this->Controller->action = "search";
        $this->Controller->request->data = [
            "Post" => [
                "title" => "test",
                "foo" => "",
                "bar" => "",
            ],
        ];
        $this->Controller->Prg->commonProcess("Post", [
            "form" => "Post",
            "modelMethod" => false,
            "filterEmpty" => true,
            "allowedParams" => ["lang"],
        ]);
        $expected = [
            "title" => "test",
            "action" => "search",
            "lang" => "en",
        ];
        $this->assertEquals($expected, $this->Controller->redirectUrl);
    }

    /**
     * Test commonProcess() with special characters
     *
     * @return void
     */
    public function testCommonProcessSpecialChars()
    {
        $this->Controller->request->params = array_merge(
            $this->Controller->request->params,
            [
                "named" => [],
                "lang" => "en",
            ]
        );
        $this->Controller->presetVars = [];
        $this->Controller->action = "search";
        $this->Controller->request->data = [
            "Post" => [
                "title" => "test/slashes?!",
                "foo" => "",
                "bar" => "",
            ],
        ];
        $this->Controller->Prg->commonProcess("Post", [
            "form" => "Post",
            "modelMethod" => false,
            "filterEmpty" => true,
            "allowedParams" => ["lang"],
        ]);
        $expected = [
            "title" => "test/slashes?!",
            "action" => "search",
            "lang" => "en",
        ];
        $this->assertEquals($expected, $this->Controller->redirectUrl);

        $url = Router::url($this->Controller->redirectUrl);
        $expected = "/search/title:test%2Fslashes%3F%21/lang:en";
        $this->assertEquals($expected, $url);
    }

    /**
     * Test commonProcess() with 'paramType' = 'querystring'
     *
     * @return void
     */
    public function testCommonProcessQuerystring()
    {
        $this->Controller->request->params = array_merge(
            $this->Controller->request->params,
            [
                "named" => [],
                "lang" => "en",
            ]
        );
        $this->Controller->presetVars = [];
        $this->Controller->action = "search";
        $this->Controller->request->data = [
            "Post" => [
                "title" => "test",
                "foo" => "",
                "bar" => "",
            ],
        ];
        $this->Controller->Prg->commonProcess("Post", [
            "form" => "Post",
            "modelMethod" => false,
            "paramType" => "querystring",
            "allowedParams" => ["lang"],
        ]);
        $expected = [
            "?" => ["title" => "test", "foo" => "", "bar" => ""],
            "action" => "search",
            "lang" => "en",
        ];
        $this->assertEquals($expected, $this->Controller->redirectUrl);
    }

    /**
     * Test commonProcess() with 'paramType' = 'querystring' and special characters
     *
     * @return void
     */
    public function testCommonProcessQuerystringSpecialChars()
    {
        $this->Controller->request->params = array_merge(
            $this->Controller->request->params,
            [
                "named" => [],
                "lang" => "en",
            ]
        );
        $this->Controller->presetVars = [];
        $this->Controller->action = "search";
        $this->Controller->request->data = [
            "Post" => [
                "title" => "test/slashes?!",
                "foo" => "",
                "bar" => "",
            ],
        ];
        $this->Controller->Prg->commonProcess("Post", [
            "form" => "Post",
            "modelMethod" => false,
            "filterEmpty" => true,
            "paramType" => "querystring",
            "allowedParams" => ["lang"],
        ]);
        $expected = [
            "?" => ["title" => "test/slashes?!"],
            "action" => "search",
            "lang" => "en",
        ];
        $this->assertEquals($expected, $this->Controller->redirectUrl);

        $url = Router::url($this->Controller->redirectUrl);
        $expected = "/search/lang:en?title=test%2Fslashes%3F%21";
        $this->assertEquals($expected, $url);
    }

    /**
     * Test commonProcess() with 'paramType' = 'querystring' and pagination
     *
     * @return void
     */
    public function testCommonProcessQuerystringPagination()
    {
        $this->Controller->request->query = [
            "sort" => "created",
            "direction" => "asc",
            "page" => 3,
        ];
        $this->Controller->request->params = array_merge(
            $this->Controller->request->params,
            [
                "named" => [],
                "lang" => "en",
            ]
        );
        $this->Controller->presetVars = [];
        $this->Controller->action = "search";
        $this->Controller->request->data = [
            "Post" => [
                "title" => "test",
                "foo" => "",
                "bar" => "",
            ],
        ];
        $this->Controller->Prg->commonProcess("Post", [
            "form" => "Post",
            "modelMethod" => false,
            "paramType" => "querystring",
            "allowedParams" => ["lang"],
        ]);
        $expected = [
            "?" => [
                "title" => "test",
                "foo" => "",
                "bar" => "",
                "sort" => "created",
                "direction" => "asc",
            ],
            "action" => "search",
            "lang" => "en",
        ];
        $this->assertEquals($expected, $this->Controller->redirectUrl);
    }

    /**
     * Test commonProcess() with 'paramType' = 'querystring' and 'filterEmpty' = true
     *
     * @return void
     */
    public function testCommonProcessQuerystringFilterEmpty()
    {
        $this->Controller->request->params = array_merge(
            $this->Controller->request->params,
            [
                "named" => [],
                "lang" => "en",
            ]
        );
        $this->Controller->presetVars = [];
        $this->Controller->action = "search";
        $this->Controller->request->data = [
            "Post" => [
                "title" => "test",
                "foo" => "",
                "bar" => "",
            ],
        ];

        $this->Controller->Prg->commonProcess("Post", [
            "form" => "Post",
            "modelMethod" => false,
            "filterEmpty" => true,
            "paramType" => "querystring",
            "allowedParams" => ["lang"],
        ]);
        $expected = [
            "?" => ["title" => "test"],
            "action" => "search",
            "lang" => "en",
        ];
        $this->assertEquals($expected, $this->Controller->redirectUrl);
    }

    /**
     * Test commonProcess() with GET
     *
     * @return void
     */
    public function testCommonProcessGet()
    {
        $this->Controller->action = "search";
        $this->Controller->presetVars = [
            ["field" => "title", "type" => "value"],
        ];
        $this->Controller->request->data = [];
        $this->Controller->Post->filterArgs = [
            ["name" => "title", "type" => "value"],
        ];
        $this->Controller->request->params["named"] = ["title" => "test"];
        $this->Controller->passedArgs = array_merge(
            $this->Controller->request->params["named"],
            $this->Controller->request->params["pass"]
        );
        $this->Controller->Prg->commonProcess("Post");

        $this->assertTrue($this->Controller->Prg->isSearch);
        $expected = ["Post" => ["title" => "test"]];
        $this->assertEquals(
            $expected["Post"],
            $this->Controller->Prg->parsedParams()
        );
        $this->assertEquals($expected, $this->Controller->request->data);
    }

    /**
     * Test commonProcess() with GET and string keys
     *
     * @return void
     */
    public function testCommonProcessGetWithStringKeys()
    {
        $this->Controller->action = "search";
        $this->Controller->presetVars = [
            "title" => ["type" => "value"],
        ];
        $this->Controller->Post->filterArgs = [
            "title" => ["type" => "value"],
        ];

        $this->Controller->Prg->__construct($this->Controller->Components, []);
        $this->Controller->Prg->initialize($this->Controller);
        $this->Controller->request->data = [];

        $this->Controller->request->params["named"] = ["title" => "test"];
        $this->Controller->passedArgs = array_merge(
            $this->Controller->request->params["named"],
            $this->Controller->request->params["pass"]
        );
        $this->Controller->Prg->commonProcess("Post");
        $expected = ["Post" => ["title" => "test"]];
        $this->assertEquals(
            $expected["Post"],
            $this->Controller->Prg->parsedParams()
        );
        $this->assertEquals($expected, $this->Controller->request->data);
    }

    /**
     * Test commonProcess() with GET and string keys (short notation)
     *
     * @return void
     */
    public function testCommonProcessGetWithStringKeysShort()
    {
        $this->Controller->action = "search";
        $this->Controller->presetVars = [
            "title" => true,
        ];
        $this->Controller->Post->filterArgs = [
            "title" => ["type" => "value"],
        ];

        $this->Controller->Prg->__construct($this->Controller->Components, []);
        $this->Controller->Prg->initialize($this->Controller);
        $this->Controller->request->data = [];

        $this->Controller->request->params["named"] = ["title" => "test"];
        $this->Controller->passedArgs = array_merge(
            $this->Controller->request->params["named"],
            $this->Controller->request->params["pass"]
        );
        $this->Controller->Prg->commonProcess("Post");
        $expected = ["Post" => ["title" => "test"]];
        $this->assertEquals(
            $expected["Post"],
            $this->Controller->Prg->parsedParams()
        );
        $this->assertEquals($expected, $this->Controller->request->data);
    }

    /**
     * Test serializeParams() with encoding
     *
     * @return void
     */
    public function testSerializeParamsWithEncoding()
    {
        $this->Controller->action = "search";
        $this->Controller->presetVars = [
            ["field" => "title", "type" => "value", "encode" => true],
        ];
        $this->Controller->request->data = [];
        $this->Controller->Post->filterArgs = [
            ["name" => "title", "type" => "value"],
        ];
        $this->Controller->Prg->encode = true;
        $test = ["title" => "Something new"];
        $result = $this->Controller->Prg->serializeParams($test);
        $this->assertEquals(
            $this->_urlEncode("Something new"),
            $result["title"]
        );

        $test = ["title" => "ef?"];
        $result = $this->Controller->Prg->serializeParams($test);
        $this->assertEquals($this->_urlEncode("ef?"), $result["title"]);
    }

    /**
     * Replace the base64encoded values
     *
     * Replace the base64encoded values that could harm the url (/ and =) with harmless characters
     *
     * @param $str
     * @return string
     */
    protected function _urlEncode($str)
    {
        return str_replace(["/", "="], ["-", "_"], base64_encode($str));
    }

    /**
     * Test serializeParams() with encoding
     *
     * @return void
     */
    public function testSerializeParamsWithEncodingAndSpace()
    {
        $this->Controller->action = "search";
        $this->Controller->presetVars = [
            ["field" => "title", "type" => "value", "encode" => true],
        ];
        $this->Controller->request->data = [];
        $this->Controller->Post->filterArgs = [
            ["name" => "title", "type" => "value"],
        ];
        $this->Controller->Prg->encode = true;
        $testData = $test = ["title" => "Something new"];
        $result = $this->Controller->Prg->serializeParams($test);
        $this->assertEquals(
            $this->_urlEncode("Something new"),
            $result["title"]
        );

        $this->Controller->passedArgs = $result;
        $this->Controller->Prg->presetForm("Post");
        $expected = ["Post" => $testData];
        $this->assertEquals(
            $expected["Post"],
            $this->Controller->Prg->parsedParams()
        );
        $this->assertEquals($expected, $this->Controller->request->data);
    }

    /**
     * Test presetForm() with encoded parameters
     *
     * @return void
     */
    public function testPresetFormWithEncodedParams()
    {
        $this->Controller->presetVars = [
            [
                "field" => "title",
                "type" => "value",
            ],
            [
                "field" => "checkbox",
                "type" => "checkbox",
            ],
            [
                "field" => "lookup",
                "type" => "lookup",
                "formField" => "lookup_input",
                "modelField" => "title",
                "model" => "Post",
            ],
        ];
        $this->Controller->passedArgs = [
            "title" => $this->_urlEncode("test"),
            "checkbox" => $this->_urlEncode("test|test2|test3"),
            "lookup" => $this->_urlEncode("1"),
        ];

        $this->Controller->beforeFilter();

        $this->Controller->Prg->encode = true;
        $this->Controller->Prg->presetForm("Post");
        $expected = [
            "Post" => [
                "title" => "test",
                "checkbox" => [
                    0 => "test",
                    1 => "test2",
                    2 => "test3",
                ],
                "lookup" => 1,
                "lookup_input" => "First Post",
            ],
        ];
        $this->assertEquals($expected, $this->Controller->request->data);
        $this->assertTrue($this->Controller->Prg->isSearch);
        $this->assertEquals(
            $expected["Post"],
            $this->Controller->Prg->parsedParams()
        );
    }

    /**
     * Test commonProcess() with empty value
     *
     * @return void
     */
    public function testCommonProcessGetWithEmptyValue()
    {
        $this->Controller->request->params = array_merge(
            $this->Controller->request->params,
            [
                "named" => [],
                "category_id" => "0",
            ]
        );
        $this->Controller->presetVars = [
            [
                "field" => "category_id",
                "name" => "category_id",
                "type" => "value",
                "allowEmpty" => true,
                "emptyValue" => "0",
            ],
            [
                "field" => "checkbox",
                "name" => "checkbox",
                "type" => "checkbox",
            ],
        ];
        $this->Controller->action = "search";
        $this->Controller->request->data = [
            "Post" => [
                "category_id" => "0",
                "foo" => "",
            ],
        ];
        $this->Controller->Prg->commonProcess("Post", [
            "form" => "Post",
            "modelMethod" => false,
            "filterEmpty" => true,
        ]);
        $expected = [
            "action" => "search",
            "category_id" => null,
        ];
        $this->assertEquals($expected, $this->Controller->redirectUrl);
    }

    /**
     * Test commonProcess() with empty value
     *
     * @return void
     */
    public function testCommonProcessGetWithEmptyValueQueryStrings()
    {
        $this->Controller->presetVars = [
            [
                "field" => "category_id",
                "name" => "category_id",
                "type" => "value",
                "allowEmpty" => true,
                "emptyValue" => "0",
            ],
            [
                "field" => "checkbox",
                "name" => "checkbox",
                "type" => "checkbox",
            ],
        ];

        $this->Controller->action = "search";
        $this->Controller->request->data = [
            "Post" => [
                "category_id" => "0",
                "checkbox" => "x",
            ],
        ];

        $this->Controller->Prg->commonProcess("Post", [
            "form" => "Post",
            "paramType" => "querystring",
            "filterEmpty" => true,
        ]);

        $expected = [
            "?" => [
                "category_id" => null,
                "checkbox" => "x",
            ],
            "action" => "search",
        ];
        $this->assertEquals($expected, $this->Controller->redirectUrl);
    }

    /**
     * Test presetForm() with empty value
     *
     * @return void
     */
    public function testPresetFormWithEmptyValue()
    {
        $this->Controller->presetVars = [
            [
                "field" => "category_id",
                "type" => "value",
                "allowEmpty" => true,
                "emptyValue" => "0",
            ],
            [
                "field" => "checkbox",
                "type" => "checkbox",
                "allowEmpty" => true,
            ],
        ];
        $this->Controller->passedArgs = [
            "category_id" => "",
        ];
        $this->Controller->beforeFilter();

        $this->Controller->Prg->encode = true;
        $this->Controller->Prg->presetForm(["model" => "Post"]);
        $expected = [
            "Post" => [
                "category_id" => "0",
            ],
        ];
        $this->assertEquals($expected, $this->Controller->request->data);
        $this->assertFalse($this->Controller->Prg->isSearch);

        $expected = [
            "Post" => [
                "category_id" => "",
            ],
        ];
        $this->assertEquals(
            $expected["Post"],
            $this->Controller->Prg->parsedParams()
        );
    }

    /**
     * Test presetForm() with empty value and
     *
     * @return void
     */
    public function testPresetFormWithEmptyValueAndIsSearch()
    {
        $this->Controller->presetVars = [
            [
                "field" => "category_id",
                "type" => "value",
                "allowEmpty" => true,
                "emptyValue" => "0",
            ],
            [
                "field" => "checkbox",
                "type" => "checkbox",
            ],
        ];
        $this->Controller->passedArgs = [
            "category_id" => "",
            "checkbox" => $this->_urlEncode("test|test2|test3"),
        ];
        $this->Controller->beforeFilter();

        $this->Controller->Prg->encode = true;
        $this->Controller->Prg->presetForm(["model" => "Post"]);
        $expected = [
            "Post" => [
                "category_id" => "0",
                "checkbox" => [
                    0 => "test",
                    1 => "test2",
                    2 => "test3",
                ],
            ],
        ];
        $this->assertEquals($expected, $this->Controller->request->data);
        $this->assertTrue($this->Controller->Prg->isSearch);

        $expected = [
            "Post" => [
                "category_id" => "",
                "checkbox" => [
                    0 => "test",
                    1 => "test2",
                    2 => "test3",
                ],
            ],
        ];
        $this->assertEquals(
            $expected["Post"],
            $this->Controller->Prg->parsedParams()
        );
    }
}
