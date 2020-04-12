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

class DatesController extends AppController{
  public $helpers = array('Html', 'Form');
  public $components = array(
    'Paginator',
    'Security' => array(
      'csrfUseOnce' => false,
    ),
  );

  // 授業日一覧を表示
  public function admin_index(){
    $this->Date->recursive = -1;
    $this->Paginator->settings = array(
      'fields' => array('*'),
      'order'  => array(
        'date'   => 'DESC'
      ),
      'limit'  => 1000
    );
    $this->set('dates', $this->Paginator->paginate());
  }

  // 授業日を追加
  public function admin_add(){
    $this->admin_edit();
    $this->render('admin_edit');
  }

  /**
 	* 授業日の編集
 	* @param int $date_id 編集する授業日のID
 	*/
  public function admin_edit($date_id=null){
    $this->loadModel('Attendance');
    if($this->action == 'edit' && !$this->Date->exists($date_id))
    {
 		  throw new NotFoundException(__('指定された授業日は登録されていません'));
 		}
 		if($this->request->is(array(
 				'post',
 				'put'
 		)))
    {
 			if ($this->Date->save($this->request->data))
      {
        $date_id = $this->Date->id;
        $this->Attendance->setAttendanceInfo($date_id);

 				$this->Flash->success(__('授業日を保存しました'));
 				return $this->redirect(array(
 						'action' => 'index'
 				));
 			}
      else
      {
 				$this->Flash->error(__('授業日を保存できませんでした．もう一度お試しください'));
 			}
 		}
    else
    {
 			$this->request->data = $this->Date->find('first', array(
         'conditions' => array(
           'id' => $date_id
         ),
         'recursive' => -1
      ));
 		}
   }

  /**
  * 授業日の削除
  * @param int $date_id 削除する授業日のID
  */
  public function admin_delete($date_id=null){
    $this->Date->id = $date_id;
 		if (!$this->Date->exists())
    {
 			throw new NotFoundException(__('指定された授業日は登録されていません'));
 		}
 		$this->request->allowMethod('post', 'delete');
 		if ($this->Date->delete())
    {
 			$this->Flash->success(__('授業日を削除しました'));
 		}
    else
    {
 			$this->Flash->error(__('授業日を削除できませんでした．もう一度お試しください'));
 		}
 		return $this->redirect(array(
 				'action' => 'index'
 		));
  }
}
