<?php
/**
 * iroha Board Project
 *
 * @author        Kotaro Miura
 * @copyright     2015-2016 iroha Soft, Inc. (http://irohasoft.jp)
 * @link          http://irohaboard.irohasoft.jp
 * @license       http://www.gnu.org/licenses/gpl-3.0.en.html GPL License
 */

App::uses("AppModel", "Model");
App::uses("BlowfishPasswordHasher", "Controller/Component/Auth");

/**
 * User Model
 *
 * @property Group $Group
 * @property Content $Content
 * @property Course $Course
 * @property Group $Group
 */
class User extends AppModel
{
    public $order = "User.name";

    public $validate = [
        "username" => [
            [
                "rule" => "isUnique",
                "message" => "ログインIDが重複しています",
            ],
            [
                "rule" => "alphaNumeric",
                "message" => "ログインIDは英数字で入力して下さい",
            ],
            [
                "rule" => ["between", 4, 32],
                "message" => "ログインIDは4文字以上32文字以内で入力して下さい",
            ],
        ],
        "name" => [
            "notBlank" => [
                "rule" => ["notBlank"],
                "message" => "氏名が入力されていません",
            ],
        ],
        "role" => [
            "notBlank" => [
                "rule" => ["notBlank"],
                "message" => "権限が指定されていません",
            ],
        ],
        "password" => [
            [
                "rule" => "alphaNumeric",
                "message" => "パスワードは英数字で入力して下さい",
            ],
            [
                "rule" => ["between", 4, 32],
                "message" => "パスワードは4文字以上32文字以内で入力して下さい",
            ],
        ],
        "new_password" => [
            [
                "rule" => "alphaNumeric",
                "message" => "パスワードは英数字で入力して下さい",
                "allowEmpty" => true,
            ],
            [
                "rule" => ["between", 4, 32],
                "message" => "パスワードは4文字以上32文字以内で入力して下さい",
                "allowEmpty" => true,
            ],
        ],
    ];

    // The Associations below have been created with all possible keys, those
    // that are not needed can be removed

    /**
     * belongsTo associations
     *
     * @var array
     */
    public $belongsTo = [];

    /**
     * hasMany associations
     *
     * @var array
     */
    public $hasMany = [
        "Content" => [
            "className" => "Content",
            "foreignKey" => "user_id",
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
        "Record" => [
            "className" => "Record",
            "foreignKey" => "user_id",
            "dependent" => true,
        ],
        "ClearedContent" => [
            "className" => "ClearedContent",
            "foreignKey" => "user_id",
            "dependent" => true,
        ],
        "Soap" => [
            "className" => "Soap",
            "foreignKey" => "user_id",
            "dependent" => true,
        ],
        "Enquete" => [
            "className" => "Enquete",
            "foreignKey" => "user_id",
            "dependent" => true,
        ],
        "Attendance" => [
            "className" => "Attendance",
            "foreignKey" => "user_id",
            "order" => "created ASC",
            "dependent" => true,
        ],
    ];

    /**
     * hasAndBelongsToMany associations
     *
     * @var array
     */
    public $hasAndBelongsToMany = [
        "Course" => [
            "className" => "Course",
            "joinTable" => "users_courses",
            "foreignKey" => "user_id",
            "associationForeignKey" => "course_id",
            "unique" => "keepExisting",
            "conditions" => "",
            "fields" => "",
            "order" => "",
            "limit" => "",
            "offset" => "",
            "finderQuery" => "",
        ],
        "Group" => [
            "className" => "Group",
            "joinTable" => "users_groups",
            "foreignKey" => "user_id",
            "associationForeignKey" => "group_id",
            "unique" => "keepExisting",
            "conditions" => "",
            "fields" => "",
            "order" => "",
            "limit" => "",
            "offset" => "",
            "finderQuery" => "",
        ],
    ];

    public function beforeSave($options = [])
    {
        if (isset($this->data[$this->alias]["password"])) {
            $this->data[$this->alias]["password"] = AuthComponent::password(
                $this->data[$this->alias]["password"]
            );
        }
        return true;
    }

    /**
     * 検索用
     */
    public $actsAs = ["Search.Searchable"];

    public $filterArgs = [
        "username" => [
            "type" => "like",
            "field" => "User.username",
        ],
        /*
		'name' => array(
			'type' => 'like',
			'field' => 'User.name'
		),
		*/
        "course_id" => [
            "type" => "like",
            "field" => "course_id",
        ],
    ];

    /**
     * 学習履歴の削除
     *
     * @param int array $user_id 学習履歴を削除するユーザのID
     */
    public function deleteUserRecords($user_id)
    {
        App::import("Model", "Record");
        $this->Record = new Record();
        $this->Record->deleteAll(["Record.user_id" => $user_id], true);
    }

    public function getUserList()
    {
        $data = $this->find("all", [
            "fields" => ["id", "username", "name", "pic_path"],
            "conditions" => ["role" => "user"],
            "order" => ["username" => "ASC"],
            "recursive" => -1,
        ]);
        return $data;
    }

    // ユーザ名で検索
    public function findUserList($username, $name)
    {
        if ($username != null) {
            $username = "%" . $username . "%";
        }
        if ($name != null) {
            $name = "%" . $name . "%";
        }
        if ($username == null && $name == null) {
            $username = "%" . $username . "%";
            $name = "%" . $name . "%";
        }

        $data = $this->find("all", [
            "fields" => ["id", "username", "name", "pic_path"],
            "conditions" => [
                "role" => "user",
                "OR" => [
                    "username LIKE" => $username,
                    "name LIKE" => $name,
                    "name_furigana LIKE" => $name,
                ],
            ],
            "order" => ["username" => "ASC"],
            "recursive" => -1,
        ]);
        return $data;
    }

    public function findUserGroup($user_id)
    {
        $data = $this->find("all", [
            "fields" => ["group_id"],
            "conditions" => ["id" => $user_id],
            "recursive" => -1,
        ]);
        //$this->log($data);
        return $data[0]["User"]["group_id"];
    }

    // role == 'user' のユーザのみ
    public function getAllStudent()
    {
        $data = $this->find("all", [
            "conditions" => [
                "role" => "user",
            ],
            "order" => ["username" => "ASC"],
            "recursive" => -1,
        ]);
        return $data;
    }

    public function findAllUserInGroup($group_id)
    {
        $data = $this->find("all", [
            "fields" => ["id", "group_id"],
            "conditions" => [
                "OR" => [
                    "group_id" => $group_id,
                    "last_group" => $group_id,
                ],
            ],
            "order" => ["username" => "ASC"],
            "recursive" => -1,
        ]);
        return $data;
    }

    // role == 'user' のユーザのみ
    public function findAllStudentInGroup($group_id)
    {
        $data = $this->find("all", [
            "fields" => ["id", "group_id", "last_group"],
            "conditions" => [
                "OR" => [
                    "group_id" => $group_id,
                    "last_group" => $group_id,
                ],
                "role" => "user",
            ],
            "order" => ["username" => "ASC"],
            "recursive" => -1,
        ]);
        return $data;
    }

    // role == 'admin' のユーザのみ
    public function findAllAdminInGroup($group_id)
    {
        $data = $this->find("all", [
            "fields" => ["id", "group_id"],
            "conditions" => [
                "group_id" => $group_id,
                "role" => "admin",
            ],
            "order" => ["username" => "ASC"],
            "recursive" => -1,
        ]);
        return $data;
    }

    ///写真パスを更新する
    public function updatePicPath($user_id, $newPath)
    {
        $data = ["id" => $user_id, "pic_path" => $newPath];
        $this->save($data);
        return 1;
    }

    //全て写真パスをGet
    public function getAllPicPath()
    {
        $data = $this->find("all", [
            "fields" => ["id", "pic_path"],
            "recursive" => -1,
        ]);
        return $data;
    }

    public function findUserPicPath($user_id)
    {
        if ($user_id == null) {
            return "student_img/noPic.png";
        }
        $data = $this->find("first", [
            "fields" => ["id", "pic_path"],
            "conditions" => ["id" => $user_id],
            "recursive" => -1,
        ]);
        $pic_path = $data["User"]["pic_path"];
        if (
            $pic_path === null or
            $pic_path === "" or
            $pic_path === "student_img/"
        ) {
            return "student_img/noPic.png";
        }
        return $pic_path;
    }

    public function findGroupPicPaths($members)
    {
        if (empty($members)) {
            return null;
        }

        $conditions = [];
        foreach ($members as $member):
            $user_id = $member["User"]["id"];
            array_push($conditions, ["id" => $user_id]);
        endforeach;

        $data = $this->find("all", [
            "fields" => ["id", "pic_path"],
            "conditions" => ["OR" => $conditions],
            "recursive" => -1,
        ]);

        $result = [];
        foreach ($data as $datum) {
            $user_id = $datum["User"]["id"];
            $pic_path = $datum["User"]["pic_path"];
            if (
                $pic_path === null or
                $pic_path === "" or
                $pic_path === "student_img/"
            ) {
                $pic_path = "student_img/noPic.png";
            }
            $result += [$user_id => $pic_path];
        }
        //$this->log($result);
        return $result;
    }

    public function getOsList()
    {
        $sql = "SELECT * FROM ib_os_types";
        $data = $this->query($sql);
        return $data;
    }

    public function calcGrade($birthyear)
    {
        if ($birthyear <= 0) {
            return "生年度未設定";
        }
        $this_year = date("Y");
        $this_fiscal_year =
            strtotime(date("Y-m-d")) < strtotime($this_year . "-04-01")
                ? $this_year - 1
                : $this_year;
        $age = $this_fiscal_year - $birthyear;

        if ($age <= 6) {
            $grade = "未就学";
        } elseif ($age <= 12) {
            $grade = "小学" . ($age - 6) . "年";
        } elseif ($age <= 15) {
            $grade = "中学" . ($age - 12) . "年";
        } elseif ($age <= 18) {
            $grade = "高校" . ($age - 15) . "年";
        } else {
            $grade = "高卒以上";
        }
        return $grade;
    }

    public function calcGradeSimply($birthyear)
    {
        if ($birthyear <= 0) {
            return "-";
        }
        $this_year = date("Y");
        $this_fiscal_year =
            strtotime(date("Y-m-d")) < strtotime($this_year . "-04-01")
                ? $this_year - 1
                : $this_year;
        $age = $this_fiscal_year - $birthyear;

        if ($age <= 6) {
            $grade = "未就学";
        } elseif ($age <= 12) {
            $grade = "小" . ($age - 6);
        } elseif ($age <= 15) {
            $grade = "中" . ($age - 12);
        } elseif ($age <= 18) {
            $grade = "高" . ($age - 15);
        } else {
            $grade = "高卒以上";
        }
        return $grade;
    }

    public function findUserGrade($user_id)
    {
        $data = $this->find("all", [
            "fields" => ["id", "birthyear"],
            "conditions" => ["id" => $user_id],
            "recursive" => -1,
        ]);
        $birthyear = $data["0"]["User"]["birthyear"];
        $grade = $this->calcGrade($birthyear);
        return $grade;
    }

    public function findGroupGrade($members)
    {
        if (empty($members)) {
            return null;
        }

        $result = [];
        foreach ($members as $member):
            $user_id = $member["User"]["id"];
            $birthyear = $member["User"]["birthyear"];
            $grade = $this->calcGrade($birthyear);
            $result += [$user_id => $grade];
        endforeach;
        return $result;
    }

    public function generatePassword($size = 10)
    {
        $password_chars =
            "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
        $password_chars_count = strlen($password_chars);
        $data = random_bytes($size);
        $pin = "";
        for ($n = 0; $n < $size; $n++) {
            $pin .= substr(
                $password_chars,
                ord(substr($data, $n, 1)) % $password_chars_count,
                1
            );
        }
        return $pin;
    }

    // IDからユーザ情報を取得
    public function findUserInfo($user_id)
    {
        $data = $this->find("first", [
            "fields" => ["username", "name", "birthyear", "pic_path"],
            "conditions" => ["id" => $user_id],
            "recursive" => -1,
        ]);

        $grade = $this->calcGrade($data["User"]["birthyear"]);

        $info = [
            "username" => $data["User"]["username"],
            "name" => $data["User"]["name"],
            "grade" => $grade,
            "pic_path" => $data["User"]["pic_path"],
        ];
        return $info;
    }

    // グループに属するユーザ情報を取得
    public function findAllUserInfoInGroup($group_id)
    {
        $data = $this->find("all", [
            "fields" => ["id", "group_id", "username", "name", "birthyear", "pic_path"],
            "conditions" => [
                "role" => "user",
                "OR" => [
                    "group_id" => $group_id,
                    "last_group" => $group_id,
                ],
            ],
            "recursive" => -1,
        ]);
        return array_map(function($user_info){
            return [
                "id" =>$user_info["User"]["id"],
                "group_id" => $user_info["User"]["group_id"],
                "username" => $user_info["User"]["username"],
                "name" => $user_info["User"]["name"],
                "grade" => $this->calcGrade($user_info["User"]["birthyear"]),
                "pic_path" => $user_info["User"]["pic_path"],
            ];
        }, $data);
    }

    // 指定した時限に属するユーザ情報を取得
    public function findAllUserInfoInPeriod($period)
    {
        $data = $this->find("all", [
            "fields" => ["User.id", "User.name", "User.username", "User.birthyear", "User.pic_path"],
            "conditions" => [
                "User.role" => "user",
                "User.period" => $period,
            ],
            "order" => "User.username ASC",
        ]);

        return array_map(function($user_info){
            return [
                "id" =>$user_info["User"]["id"],
                "username" => $user_info["User"]["username"],
                "name" => $user_info["User"]["name"],
                "grade" => $this->calcGradeSimply($user_info["User"]["birthyear"]),
                "pic_path" => $user_info["User"]["pic_path"],
            ];
        }, $data);
    }
}
