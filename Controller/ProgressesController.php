<?php
/*
 * Ripple  Project
 *
 * @author        Enfu Guo
 * @copyright     NPO Organization uec support
 * @link          http://uecsupport.dip.jp/
 * @license       http://www.gnu.org/licenses/gpl-3.0.en.html GPL License
*/

App::uses('AppController',   'Controller');
App::uses('User',            'User');

class ProgressesController extends AppController{
  
  // 成果発表一覧の表示
  public function admin_index(){
    $progress_list = $this->Progress->find('all',array(
      'order' => array('Progress.id' => 'desc')
    ));
    $this->log($progress_list);
    $this->set(compact('progress_list'));
  }

  // 成果発表の追加
  public function admin_add(){
    $this->admin_edit();
		$this->render('admin_edit');
  }

  // 成果発表の修正
  public function admin_edit($progress_id = null){

    if ($this->action == 'edit' && ! $this->Progress->exists($progress_id))
		{
			throw new NotFoundException(__('Invalid Progress'));
    }

    if ($this->request->is(array(
			'post',
			'put'
		)))
		{
      $this->log($this->request->data);
      if($this->Progress->save($this->request->data)){
        $this->Flash->success(__('成果発表が保存されました'));
				return $this->redirect(array(
					'action' => 'index'
				));
      }else{
				$this->Flash->error(__('The progress could not be saved. Please, try again.'));
			}
    }else{
      $options = array(
				'conditions' => array(
					'Progress.' . $this->Progress->primaryKey => $progress_id
				)
			);
			$this->request->data = $this->Progress->find('first', $options);
    }

  }

  /**
	 * 成果発表の削除
	 * @param int $course_id コースID
	 */
	public function admin_delete($progress_id = null)
	{

		$this->Progress->id = $progress_id;
		if (! $this->Progress->exists())
		{
			throw new NotFoundException(__('Invalid progress'));
		}

		$this->request->allowMethod('post', 'delete');
		$this->Progress->deleteProgress($progress_id);
		$this->Flash->success(__('成果発表が削除されました'));

		return $this->redirect(array(
				'action' => 'index'
		));
	}
}
?>