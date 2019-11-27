<?php
/**
 * Ripple Project
 *
 * @author        Osamu Miyazawa
 * @copyright     NPO Organization uec support
 * @link          http://uecsupport.dip.jp/
 * @license       http://www.gnu.org/licenses/gpl-3.0.en.html GPL License
 */

App::uses('AppController', 'Controller');

class LessonsController extends AppController{
  public $helpers = array('Html', 'Form');
  public $components = array(
    'Paginator',
    'Security' => array(
      'csrfUseOnce' => false,
    ),
  );

  // 授業日時一覧を表示
  public function admin_index(){
    $this->Lesson->recursive = -1;
    $this->Paginator->settings = array(
      'fields' => array('*'),
      'order'  => array(
        'date'   => 'DESC',
        'period' => 'ASC'
      ),
      'limit'  => 1000
    );
    $this->set('lessons', $this->Paginator->paginate());
  }

  // 授業日時を追加
  public function admin_add(){
    $this->admin_edit();
    $this->render('admin_edit');
  }

  /**
	 * 授業日時の編集
	 * @param int $lesson_id 編集する授業日時のID
	 */
  public function admin_edit($lesson_id=null){
    if($this->action == 'edit' && !$this->Lesson->exists($lesson_id))
    {
			throw new NotFoundException(__('指定された授業日時は登録されていません'));
		}
		if($this->request->is(array(
				'post',
				'put'
		)))
    {
			if ($this->Lesson->save($this->request->data))
      {
				$this->Flash->success(__('授業日程を保存しました'));
				return $this->redirect(array(
						'action' => 'index'
				));
			}
      else
      {
				$this->Flash->error(__('授業日時を保存できませんでした．もう一度お試しください'));
			}
		}
    else
    {
			$this->request->data = $this->Lesson->find('first', array(
        'conditions' => array(
          'id' => $lesson_id
        ),
        'recursive' => -1
      ));
      $this->log($this->request->data);
		}
  }

  /**
   * 授業日時の削除
   * @param int $lesson_id 削除する授業日時のID
   */
  public function admin_delete($lesson_id=null){
    $this->Lesson->id = $lesson_id;
		if (!$this->Lesson->exists())
    {
			throw new NotFoundException(__('指定された授業日時は登録されていません'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->Lesson->delete())
    {
			$this->Flash->success(__('授業日時を削除しました'));
		}
    else
    {
			$this->Flash->error(__('授業日時を削除できませんでした．もう一度お試しください'));
		}
		return $this->redirect(array(
				'action' => 'index'
		));
  }
}
