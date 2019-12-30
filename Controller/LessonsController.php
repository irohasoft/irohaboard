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

  // 時限一覧を表示
  public function admin_index($date_id){
    $this->set('date_id', $date_id);
    $this->loadModel('Date');
    $date = $this->Date->getDate($date_id, 'Y年m月d日');
    $this->set('date', $date);

    $this->Lesson->recursive = -1;
    $this->Paginator->settings = array(
      'fields' => array('*'),
      'conditions' => array('date_id' => $date_id),
      'order'  => array(
        'period' => 'ASC'
      ),
      'limit'  => 1000
    );
    $this->set('lessons', $this->Paginator->paginate());
  }

  // 時限を追加
  public function admin_add($date_id){
    $this->admin_edit($date_id);
    $this->render('admin_edit');
  }

  /**
	 * 時限の編集
	 * @param int $lesson_id 編集する時限のID
	 */
  public function admin_edit($date_id, $lesson_id=null){
    $this->loadModel('Date');

    if($this->action == 'edit' && !$this->Date->exists($date_id) &&
       !$this->Lesson->exists($lesson_id))
    {
			throw new NotFoundException(__('指定された時限は登録されていません'));
		}
		if($this->request->is(array(
				'post',
				'put'
		)))
    {
			if ($this->Lesson->save($this->request->data))
      {
				$this->Flash->success(__('時限を保存しました'));
				return $this->redirect(array(
						'action' => 'index',
            $date_id
				));
			}
      else
      {
				$this->Flash->error(__('時限を保存できませんでした．もう一度お試しください'));
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
      $date = $this->Date->getDate($date_id, 'Y年m月d日');
      $this->set('date', $date);
      $this->set('date_id', $date_id);
		}
  }

  /**
   * 時限の削除
   * @param int $lesson_id 削除する時限のID
   */
  public function admin_delete($lesson_id=null){
    $this->Lesson->id = $lesson_id;
		if (!$this->Lesson->exists())
    {
			throw new NotFoundException(__('指定された時限は登録されていません'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->Lesson->delete())
    {
			$this->Flash->success(__('時限を削除しました'));
		}
    else
    {
			$this->Flash->error(__('時限を削除できませんでした．もう一度お試しください'));
		}
		return $this->redirect(array(
				'action' => 'index'
		));
  }
}
