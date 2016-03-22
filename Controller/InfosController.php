<?php
/**
 * iroha Board Project
 *
 * @author        Kotaro Miura
 * @copyright     2015-2016 iroha Soft, Inc. (http://irohasoft.jp)
 * @link          http://irohasoft.jp/irohaboard
 * @license       http://www.gnu.org/licenses/gpl-3.0.en.html GPL License
 */

App::uses('AppController', 'Controller');

/**
 * Infos Controller
 *
 * @property Info $Info
 * @property PaginatorComponent $Paginator
 */
class InfosController extends AppController
{

	/**
	 * Components
	 *
	 * @var array
	 */
	public $components = array(
			'Paginator'
	);

	/**
	 * index method
	 *
	 * @return void
	 */
	public function index()
	{
		$this->Info->recursive = 0;
		$this->set('infos', $this->Paginator->paginate());
	}

	/**
	 * view method
	 *
	 * @throws NotFoundException
	 * @param string $id        	
	 * @return void
	 */
	public function view($id = null)
	{
		if (! $this->Info->exists($id))
		{
			throw new NotFoundException(__('Invalid info'));
		}
		$options = array(
				'conditions' => array(
						'Info.' . $this->Info->primaryKey => $id
				)
		);
		$this->set('info', $this->Info->find('first', $options));
	}

	public function admin_index()
	{
		$this->Paginator->settings = array(
			'limit' => 10,
			'order' => 'Info.created desc',
		);
		
		$result = $this->paginate();
		$this->set('infos', $result);
	}

	public function admin_add()
	{
		$this->admin_edit();
		$this->render('admin_edit');
	}

	/**
	 * edit method
	 *
	 * @throws NotFoundException
	 * @param string $id        	
	 * @return void
	 */
	public function admin_edit($id = null)
	{
		if ($this->action == 'admin_edit' && ! $this->Info->exists($id))
		{
			throw new NotFoundException(__('Invalid info'));
		}
		if ($this->request->is(array(
				'post',
				'put'
		)))
		{
			if ($this->Info->save($this->request->data))
			{
				$this->Flash->success(__('お知らせが保存されました'));
				return $this->redirect(array(
						'action' => 'index'
				));
			}
			else
			{
				$this->Flash->error(__('The info could not be saved. Please, try again.'));
			}
		}
		else
		{
			$options = array(
					'conditions' => array(
							'Info.' . $this->Info->primaryKey => $id
					)
			);
			$this->request->data = $this->Info->find('first', $options);
		}
		$users = $this->Info->User->find('list');
	}

	/**
	 * delete method
	 *
	 * @throws NotFoundException
	 * @param string $id        	
	 * @return void
	 */
	public function admin_delete($id = null)
	{
		$this->Info->id = $id;
		if (! $this->Info->exists())
		{
			throw new NotFoundException(__('Invalid info'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->Info->delete())
		{
			$this->Flash->success(__('お知らせが削除されました'));
		}
		else
		{
			$this->Flash->error(__('The info could not be deleted. Please, try again.'));
		}
		return $this->redirect(array(
				'action' => 'index'
		));
	}
}
