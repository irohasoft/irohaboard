<?php
/**
 * iroha Board Project
 *
 * @author        Kotaro Miura
 * @copyright     2015-2016 iroha Soft, Inc. (http://irohasoft.jp)
 * @link          http://irohaboard.irohasoft.jp
 * @license       http://www.gnu.org/licenses/gpl-3.0.en.html GPL License
 */

App::uses("AppController", "Controller");

/**
 * UsersCourses Controller
 *
 * @property UsersCourse $UsersCourse
 * @property PaginatorComponent $Paginator
 */
class UsersCoursesController extends AppController
{
    public $components = [];

    /**
     * 受講コース一覧（ホーム画面）を表示
     */
    public function index()
    {
        $this->loadModel("Group");
        $this->loadModel("User");
        $this->loadModel("Attendance");
        $this->loadModel("Date");
        $this->loadModel("Enquete");
        $this->loadModel("Category");
        $this->loadModel("Course");
        $this->loadModel("Content");
        $this->loadModel("Record");

        $user_id = $this->Auth->user("id");

        $role = $this->Auth->user("role");
        $this->set("role", $role);

        // 全体のお知らせの取得
        App::import("Model", "Setting");
        $this->Setting = new Setting();

        $data = $this->Setting->find("all", [
            "conditions" => [
                "Setting.setting_key" => "information",
            ],
            "recursive" => -1,
        ]);

        $info = $data[0]["Setting"]["setting_value"];

        // お知らせ一覧を取得
        $this->loadModel("Info");
        $this->loadModel("Course");
        $infos = $this->Info->getInfos($user_id, 2);

        $no_info = "";

        // 全体のお知らせが存在しない場合
        if ($info == "") {
            $no_info = __("お知らせはありません");
        }

        // 次回までのゴールを取得
        $next_goal = $this->Enquete->findCurrentNextGoal($user_id);
        $this->set("next_goal", $next_goal);

        // 受講コース情報の取得
        //$courses = $this->UsersCourse->getCourseRecord($user_id);
        // $all_courses = $this->UsersCourse->getCourseRecord($user_id);
        $all_courses = [];

        $in_category_courses_list = $this->Category->find("all", [
            "order" => "Category.sort_no asc",
        ]);

        $out_category_courses = $this->Course->find("all", [
            "conditions" => [
                "Course.category_id" => null,
            ],
            "order" => ["Course.sort_no" => "asc"],
        ]);

        foreach ($in_category_courses_list as $category_info) {
            $tmp_arr = [];
            $tmp_arr["Category"] = $category_info["Category"];
            $tmp_arr["Course"] = $category_info["Course"];
            foreach ($tmp_arr["Course"] as &$course) {
                $course_id = $course["id"];
                // 学習開始日
                $first_date = $this->Record->find("first", [
                    "conditions" => [
                        "Record.course_id" => $course_id,
                        "Record.user_id" => $user_id,
                    ],
                    "order" => ["Record.created asc"],
                    "recursive" => -1,
                ])["Record"]["created"];
                // 学習最終日
                $latest_date = $this->Record->find("first", [
                    "conditions" => [
                        "Record.course_id" => $course_id,
                        "Record.user_id" => $user_id,
                    ],
                    "order" => ["Record.created desc"],
                    "recursive" => -1,
                ])["Record"]["created"];

                $sum_content_cnt = $this->Content->find("count", [
                    "conditions" => [
                        "Content.course_id" => $course_id,
                    ],
                    "recursive" => -1,
                ]);
                $did_content_cnt = $this->Record->find("count", [
                    "conditions" => [
                        "Record.course_id" => $course_id,
                        "Record.user_id" => $user_id,
                    ],
                    "fields" => "DISTINCT Record.content_id",
                    "recursive" => -1,
                ]);
                $course["add_info"] = [
                    "sum_cnt" => $sum_content_cnt,
                    "did_cnt" => $did_content_cnt,
                    "first_date" => $first_date,
                    "last_date" => $latest_date,
                ];
            }
            array_push($all_courses, $tmp_arr);
        }

        $other_course = [];
        $other_course["Category"] = [
            "id" => 0,
            "title" => "未分類",
        ];

        $other_course["Course"] = [];
        foreach ($out_category_courses as $category_info) {
            $tmp_arr = $category_info["Course"];
            $course_id = $tmp_arr["id"];
            // 学習開始日
            $first_date = $this->Record->find("first", [
                "conditions" => [
                    "Record.course_id" => $course_id,
                    "Record.user_id" => $user_id,
                ],
                "order" => ["Record.created asc"],
                "recursive" => -1,
            ])["Record"]["created"];
            // 学習最終日
            $latest_date = $this->Record->find("first", [
                "conditions" => [
                    "Record.course_id" => $course_id,
                    "Record.user_id" => $user_id,
                ],
                "order" => ["Record.created desc"],
                "recursive" => -1,
            ])["Record"]["created"];
            $sum_content_cnt = $this->Content->find("count", [
                "conditions" => [
                    "Content.course_id" => $course_id,
                ],
                "recursive" => -1,
            ]);
            $did_content_cnt = $this->Record->find("count", [
                "conditions" => [
                    "Record.course_id" => $course_id,
                    "Record.user_id" => $user_id,
                ],
                "fileds" => "DISTINCT Record.content_id",
                "recursive" => -1,
            ]);
            $tmp_arr["add_info"] = [
                "sum_cnt" => $sum_content_cnt,
                "did_cnt" => $did_content_cnt,
            ];
            array_push($other_course["Course"], $tmp_arr);
        }

        array_push($all_courses, $other_course);

        $courses = [];
        // 管理者の場合，コースを全部表示
        if ($role === "admin") {
            $courses = $all_courses;
        } else {
            //受講生の場合
            foreach ($all_courses as &$course) {
                $new_course = [];
                foreach ($course["Course"] as $old_course) {
                    //もし,コースが非公開設定になっている場合
                    if ($old_course["status"] == 0) {
                        continue;
                    }

                    $before_course_id = $course["before_course"];
                    $now_course_id = $course["id"];

                    // 前提コースが無いか，既にクリアしたコンテンツが一つ以上ある
                    if (
                        $this->Course->existCleared($user_id, $now_course_id) ||
                        $before_course_id === null
                    ) {
                        array_push($new_course, $old_course);
                    } else {
                        $result = $this->Course->goToNextCourse(
                            $user_id,
                            $before_course_id,
                            $now_course_id
                        );
                        if ($result) {
                            array_push($new_course, $old_course);
                        } else {
                            continue;
                        }
                    }
                }
                $course["Course"] = $new_course;
            }
            $courses = $all_courses;
        }

        $no_record = "";

        if (count($courses) == 0) {
            $no_record = __("受講可能なコースはありません");
        }

        $this->set(compact("courses", "no_record", "info", "infos", "no_info"));

        // role == 'user'の出席情報を取る
        $user_info = [];
        if ($role === "user" && $this->Date->isClassDate()) {
            $user_ip = $this->request->ClientIp();
            $have_to_write_today_goal = $this->Attendance->takeAttendance(
                $user_id,
                $user_ip
            );
            $this->set("have_to_write_today_goal", $have_to_write_today_goal);

            $group_list = $this->Group->find("list");
            $this->set("group_list", $group_list);
            $group_id = $this->User->findUserGroup($user_id);
            $this->set("group_id", $group_id);
            $user_info = $this->Attendance->getAllTimeAttendances($user_id);
        }
        $this->set(compact("user_info"));
    }
}
