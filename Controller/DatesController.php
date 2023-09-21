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

class DatesController extends AppController
{
    public $helpers = ["Html", "Form"];
    public $components = [
        "Paginator",
        "Security" => [
            "csrfUseOnce" => false,
        ],
    ];

    // 授業日一覧を表示
    public function admin_index()
    {
        $this->Date->recursive = -1;
        $this->Paginator->settings = [
            "fields" => ["*"],
            "order" => [
                "date" => "DESC",
            ],
            "limit" => 1000,
        ];
        $this->set("dates", $this->Paginator->paginate());
    }

    // 授業日を追加
    public function admin_add()
    {
        $this->admin_edit();
        $this->render("admin_edit");
    }

    /**
     * 授業日の編集
     * @param int $date_id 編集する授業日のID
     */
    public function admin_edit($date_id = null)
    {
        $this->loadModel("Attendance");
        if ($this->action == "edit" && !$this->Date->exists($date_id)) {
            throw new NotFoundException(
                __("指定された授業日は登録されていません")
            );
        }
        if ($this->request->is(["post", "put"])) {
            if ($this->Date->save($this->request->data)) {
                $date_id = $this->Date->id;
                $this->Attendance->setAttendanceInfo($date_id);

                $this->Flash->success(__("授業日を保存しました"));
                return $this->redirect([
                    "action" => "index",
                ]);
            } else {
                $this->Flash->error(
                    __("授業日を保存できませんでした．もう一度お試しください")
                );
            }
        } else {
            $this->request->data = $this->Date->find("first", [
                "conditions" => [
                    "id" => $date_id,
                ],
                "recursive" => -1,
            ]);
        }
    }

    /**
     * ４回分の授業日を追加
     * @param string $type 授業のタイプ
     */
    public function admin_add_quadruple($type)
    {
        $this->loadModel("Attendance");
        $this->loadModel("Lesson");

        $is_online = $type === "online" ? 1 : 0;
        $last_date_info = $this->Date->find("first", [
            "order" => "id desc",
        ]);
        $last_date = $last_date_info["Date"]["date"];

        for ($i = 1; $i <= 4; $i++) {
            $tmp_date = date(
                "Y-m-d",
                strtotime($last_date . " +" . $i * 7 . " day")
            );
            $dates_save_data = [];
            $dates_save_data["Date"] = [
                "id" => "",
                "date" => $tmp_date,
                "online" => $is_online,
            ];
            if ($this->Date->save($dates_save_data)) {
                $this->Date->create(false); //これがないと，ループ内での保存はできない

                // 保存した情報を検索し，IDを取得
                $tmp_date_info = $this->Date->find("first", [
                    "order" => "id desc",
                ]);
                $date_id = $tmp_date_info["Date"]["id"];

                // 出席情報をセット
                $this->Attendance->setAttendanceInfo($date_id);

                // 時限登録
                // １限
                $period_0 = [
                    "id" => "",
                    "date_id" => $date_id,
                    "period" => 0,
                    "start" => [
                        "hour" => "09",
                        "min" => "00",
                    ],
                    "end" => [
                        "hour" => "10",
                        "min" => "30",
                    ],
                ];

                //　２限
                $period_1 = [
                    "id" => "",
                    "date_id" => $date_id,
                    "period" => 1,
                    "start" => [
                        "hour" => "11",
                        "min" => "00",
                    ],
                    "end" => [
                        "hour" => "12",
                        "min" => "30",
                    ],
                ];

                if ($this->Lesson->save($period_0)) {
                    $this->Lesson->create(false);
                    if ($this->Lesson->save($period_1)) {
                    }
                } else {
                    $this->Flash->error(
                        __("追加は失敗しました、もう一回やってください。")
                    );
                }

                continue;
            }
            $this->Flash->error(
                __("追加は失敗しました、もう一回やってください。")
            );
        }
        return $this->redirect(["action" => "admin_index"]);
    }

    /**
     * 授業日の削除
     * @param int $date_id 削除する授業日のID
     */
    public function admin_delete($date_id = null)
    {
        $this->Date->id = $date_id;
        if (!$this->Date->exists()) {
            throw new NotFoundException(
                __("指定された授業日は登録されていません")
            );
        }
        $this->request->allowMethod("post", "delete");
        if ($this->Date->delete()) {
            $this->Flash->success(__("授業日を削除しました"));
        } else {
            $this->Flash->error(
                __("授業日を削除できませんでした．もう一度お試しください")
            );
        }
        return $this->redirect([
            "action" => "index",
        ]);
    }
}
