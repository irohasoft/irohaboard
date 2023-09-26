<?php
/**
 * Ripple Project
 *
 * @author        Osamu Miyazawa
 * @copyright     NPO Organization uec support
 * @link          http://uecsupport.dip.jp/
 * @license       http://www.gnu.org/licenses/gpl-3.0.en.html GPL License
 */

App::uses("AppController", "Controller");
App::uses("RecordsQuestion", "RecordQuestion");
App::uses("UsersGroup", "UsersGroup");
App::uses("Course", "Course");
App::uses("User", "User");
App::uses("Group", "Group");
App::uses("Soap", "Soap");

/**
 * @property PaginatorComponent $Paginator
 */
class RecentStatesController extends AppController
{
    public $components = ["Paginator"];

    public $paginate = [
        "maxLimit" => 100,
    ];

    public $helpers = ["Html", "Form"];

    public function admin_index()
    {
    }

    public function admin_find_by_group()
    {
        $this->loadModel("Group");
        $this->loadModel("Soap");
        $groupData = $this->Group->findGroup();
        $this->set("groupData", $groupData);
    }

    public function admin_find_by_student()
    {
        $this->loadModel("User");
        $this->loadModel("Soap");

        if ($this->request->is("post")) {
            $conditions = $this->request->data;
            $username = $conditions["Search"]["username"];
            $name = $conditions["Search"]["name"];

            //$this->log($name);
            $user_list = $this->User->findUserList($username, $name);
        } else {
            $user_list = $this->User->getUserList();
        }
        $this->set("user_list", $user_list);
    }

    public function admin_student_view($user_id)
    {
        $this->loadModel("User");
        $this->loadModel("Group");
        $this->loadModel("Course");
        $this->loadModel("Content");
        $this->loadModel("Soap");

        $this->set("user_id", $user_id);

        $user_info = $this->User->findUserInfo($user_id);
        $this->set("user_info", $user_info);

        $group_list = $this->Group->find("list", [
            "recursive" => -1,
        ]);
        $this->set("group_list", $group_list);

        // 受講コース情報の取得
        $cleared_rates = $this->Course->findClearedRate($user_id);
        $this->set("cleared_rates", $cleared_rates);

        // 過去四回のSOAPを検索
        $recent_soaps = $this->Soap->findRecentSoaps($user_id);
        $this->set("recent_soaps", $recent_soaps);
    }

    public function admin_group_view($group_id)
    {
        $this->loadModel("User");
        $this->loadModel("Group");
        $this->loadModel("Course");
        $this->loadModel("Content");
        $this->loadModel("Soap");

        $members_info = $this->User->findAllUserInfoInGroup($group_id);
        $this->set("members_info", $members_info);

        $group_list = $this->Group->find("list");
        $this->set("group_list", $group_list);

        $content_list = $this->Content->find("list");
        $this->set("content_list", $content_list);

        $members = $this->User->findAllStudentInGroup($group_id);
        $this->set("members", $members);

        // user_idとコース名・合格率の配列
        $members_cleared_rates = $this->Course->findGroupClearedRate($members);
        $this->set("members_cleared_rates", $members_cleared_rates);

        // user_idと過去4回分SOAPの配列を作る
        $members_recent_soaps = $this->Soap->findGroupRecentSoaps($members);
        $this->set("members_recent_soaps", $members_recent_soaps);
    }

    public function admin_all_view()
    {
        $this->loadModel("User");
        $this->loadModel("Group");
        $this->loadModel("Course");
        $this->loadModel("Content");
        $this->loadModel("Soap");

        $group_list = $this->Group->find("list");
        $this->set("group_list", $group_list);

        $content_list = $this->Content->find("list");
        $this->set("content_list", $content_list);

        $this->Paginator->settings["fields"] = [
            "id", "group_id", "username", "name", "birthyear", "pic_path",
        ];
        $this->Paginator->settings["conditions"] = [
            "role" => "user",
        ];

        $this->Paginator->settings["limit"] = 20;
        $this->Paginator->settings["maxLimit"] = 20;
        $this->User->recursive = 0;

        try {
            $members = $this->paginate("User");
        } catch (Exception $e) {
            $this->request->params["named"]["page"] = 1;
            $members = $this->paginate("User");
        }
        $this->set("members", $members);

        // user_idと学年(grade)の配列
        $members_grades = $this->User->findGroupGrade($members);
        $this->set("members_grades", $members_grades);

        // user_idとコース名・合格率の配列
        $members_cleared_rates = $this->Course->findGroupClearedRate($members);
        $this->set("members_cleared_rates", $members_cleared_rates);
        $this->log($members_cleared_rates);

        // user_idと過去4回分SOAPの配列を作る
        $members_recent_soaps = $this->Soap->findGroupRecentSoaps($members);
        $this->set("members_recent_soaps", $members_recent_soaps);
    }
}
