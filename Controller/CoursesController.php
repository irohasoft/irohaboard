<?php
/**
 * iroha Board Project
 *
 * @author        Kotaro Miura
 * @copyright     2015-2016 iroha Soft, Inc. (http://irohasoft.jp)
 * @link          http://irohaboard.irohasoft.jp
 * @license       http://www.gnu.org/licenses/gpl-3.0.en.html GPL License
 */

App::uses('AppController', 'Controller');

class CoursesController extends AppController
{

	/**
	 * Components
	 *
	 * @var array
	 */
	public $components = array(
		'Security' => array(
			'unlockedActions' => array('admin_order')
		),
	);

	public function admin_index()
	{
		$this->set('courses', $this->Course->find('all', array('order' => array('Course.sort_no' => 'asc'))));
	}

	public function admin_add()
	{
		$this->admin_edit();
		$this->render('admin_edit');
	}

	public function admin_edit($id = null)
	{
		if ($this->action == 'edit' && ! $this->Course->exists($id))
		{
			throw new NotFoundException(__('Invalid course'));
		}
		if ($this->request->is(array(
				'post',
				'put'
		)))
		{
			if(Configure::read('demo_mode'))
				return;
			
			// 作成者を設定
			$this->request->data['Course']['user_id'] = $this->Session->read('Auth.User.id');
			
			if ($this->Course->save($this->request->data))
			{
				$this->Flash->success(__('コースが保存されました'));
				return $this->redirect(array(
					'action' => 'index'
				));
			}
			else
			{
				$this->Flash->error(__('The course could not be saved. Please, try again.'));
			}
		}
		else
		{
			$options = array(
				'conditions' => array(
					'Course.' . $this->Course->primaryKey => $id
				)
			);
			$this->request->data = $this->Course->find('first', $options);
		}
	}

	public function admin_delete($id = null)
	{
		if(Configure::read('demo_mode'))
			return;
		
		$this->Course->id = $id;
		if (! $this->Course->exists())
		{
			throw new NotFoundException(__('Invalid course'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->Course->delete())
		{
			$this->Flash->success(__('コースが削除されました'));
		}
		else
		{
			$this->Flash->error(__('The course could not be deleted. Please, try again.'));
		}
		return $this->redirect(array(
				'action' => 'index'
		));
	}

	public function admin_order()
	{
		$this->autoRender = FALSE;
		if($this->request->is('ajax'))
		{
			$this->Course->setOrder($this->data['id_list']);
			return "OK";
		}
	}
}
