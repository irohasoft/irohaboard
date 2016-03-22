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

/**
 * UsersGroups Controller
 *
 * @property UsersGroup $UsersGroup
 * @property PaginatorComponent $Paginator
 */
class UsersGroupsController extends AppController
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
		$this->UsersGroup->recursive = 0;
		$this->set('usersGroups', $this->Paginator->paginate());
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
		if (! $this->UsersGroup->exists($id))
		{
			throw new NotFoundException(__('Invalid users group'));
		}
		$options = array(
				'conditions' => array(
						'UsersGroup.' . $this->UsersGroup->primaryKey => $id
				)
		);
		$this->set('usersGroup', $this->UsersGroup->find('first', $options));
	}

	/**
	 * add method
	 *
	 * @return void
	 */
	public function add()
	{
		if ($this->request->is('post'))
		{
			$this->UsersGroup->create();
			if ($this->UsersGroup->save($this->request->data))
			{
				$this->Flash->success(__('The users group has been saved.'));
				return $this->redirect(array(
						'action' => 'index'
				));
			}
			else
			{
				$this->Flash->error(__('The users group could not be saved. Please, try again.'));
			}
		}
		$users = $this->UsersGroup->User->find('list');
		$groups = $this->UsersGroup->Group->find('list');
		$this->set(compact('users', 'groups'));
	}

	/**
	 * edit method
	 *
	 * @throws NotFoundException
	 * @param string $id        	
	 * @return void
	 */
	public function edit($id = null)
	{
		if (! $this->UsersGroup->exists($id))
		{
			throw new NotFoundException(__('Invalid users group'));
		}
		if ($this->request->is(array(
				'post',
				'put'
		)))
		{
			if ($this->UsersGroup->save($this->request->data))
			{
				$this->Flash->success(__('The users group has been saved.'));
				return $this->redirect(array(
						'action' => 'index'
				));
			}
			else
			{
				$this->Flash->error(__('The users group could not be saved. Please, try again.'));
			}
		}
		else
		{
			$options = array(
					'conditions' => array(
							'UsersGroup.' . $this->UsersGroup->primaryKey => $id
					)
			);
			$this->request->data = $this->UsersGroup->find('first', $options);
		}
		$users = $this->UsersGroup->User->find('list');
		$groups = $this->UsersGroup->Group->find('list');
		$this->set(compact('users', 'groups'));
	}

	/**
	 * delete method
	 *
	 * @throws NotFoundException
	 * @param string $id        	
	 * @return void
	 */
	public function delete($id = null)
	{
		$this->UsersGroup->id = $id;
		if (! $this->UsersGroup->exists())
		{
			throw new NotFoundException(__('Invalid users group'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->UsersGroup->delete())
		{
			$this->Flash->success(__('The users group has been deleted.'));
		}
		else
		{
			$this->Flash->error(__('The users group could not be deleted. Please, try again.'));
		}
		return $this->redirect(array(
				'action' => 'index'
		));
	}
}
