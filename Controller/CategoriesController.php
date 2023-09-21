<?php
/*
 * Ripple  Project
 *
 * @author        Enfu Guo
 * @copyright     NPO Organization uec support
 * @link          http://uecsupport.dip.jp/
 * @license       http://www.gnu.org/licenses/gpl-3.0.en.html GPL License
 */

App::uses("AppController", "Controller");
App::uses("Course", "Course");

class CategoriesController extends AppController
{
    public $helpers = ["Html", "Form", "Image"];

    public $components = ["Paginator", "Search.Prg"];

    //public $presetVars = true;

    public $paginate = [
        "maxLimit" => 1000,
    ];

    public $presetVars = [
        [
            "name" => "name",
            "type" => "value",
            "field" => "User.name",
        ],
        [
            "name" => "username",
            "type" => "like",
            "field" => "User.username",
        ],
    ];

    /**
     * カテゴリ一覧を表示
     */
    public function admin_index()
    {
        $categories = $this->Category->find("all", [
            "order" => ["Category.sort_no" => "asc"],
        ]);
        $this->set(compact("categories"));
    }

    /**
     * コースの追加
     */
    public function admin_add()
    {
        $this->admin_edit();
        $this->render("admin_edit");
    }

    /**
     * カテゴリの編集
     * @param int $category カテゴリID
     */
    public function admin_edit($category_id = null)
    {
        if ($this->action == "edit" && !$this->Category->exists($category_id)) {
            throw new NotFoundException(__("Invalid category"));
        }
        if ($this->request->is(["post", "put"])) {
            if (Configure::read("demo_mode")) {
                return;
            }

            // 作成者を設定
            // $this->request->data['Category']['user_id'] = $this->Auth->user('id');

            if ($this->Category->save($this->request->data)) {
                $this->Flash->success(__("カテゴリが保存されました"));
                return $this->redirect([
                    "action" => "index",
                ]);
            } else {
                $this->Flash->error(
                    __("The category could not be saved. Please, try again.")
                );
            }
        } else {
            $options = [
                "conditions" => [
                    "Category." . $this->Category->primaryKey => $category_id,
                ],
            ];
            $this->request->data = $this->Category->find("first", $options);
        }
    }

    /**
     * カテゴリの削除
     * @param int $category_id カテゴリID
     */
    public function admin_delete($category_id = null)
    {
        $this->Category->id = $category_id;
        if (!$this->Category->exists()) {
            throw new NotFoundException(__("Invalid course"));
        }

        $this->request->allowMethod("post", "delete");
        $this->Category->deleteCategory($category_id);
        $this->Flash->success(__("コースが削除されました"));

        return $this->redirect([
            "action" => "index",
        ]);
    }

    /**
     * Ajax によるコースの並び替え
     *
     * @return string 実行結果
     */
    public function admin_order()
    {
        $this->autoRender = false;
        if ($this->request->is("ajax")) {
            $this->Category->setOrder($this->data["id_list"]);
            return "OK";
        }
    }
}
?>
