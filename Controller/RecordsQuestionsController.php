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
 * RecordsQuestions Controller
 *
 * @property RecordsQuestion $RecordsQuestion
 * @property PaginatorComponent $Paginator
 */
class RecordsQuestionsController extends AppController
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
		$this->RecordsQuestion->recursive = 0;
		$this->set('recordsQuestions', $this->Paginator->paginate());
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
		if (! $this->RecordsQuestion->exists($id))
		{
			throw new NotFoundException(__('Invalid records question'));
		}
		$options = array(
				'conditions' => array(
						'RecordsQuestion.' . $this->RecordsQuestion->primaryKey => $id
				)
		);
		$this->set('recordsQuestion', $this->RecordsQuestion->find('first', $options));
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
			$this->RecordsQuestion->create();
			if ($this->RecordsQuestion->save($this->request->data))
			{
				$this->Flash->success(__('The records question has been saved.'));
				return $this->redirect(array(
						'action' => 'index'
				));
			}
			else
			{
				$this->Flash->error(__('The records question could not be saved. Please, try again.'));
			}
		}
		$records = $this->RecordsQuestion->Record->find('list');
		$questions = $this->RecordsQuestion->Question->find('list');
		$this->set(compact('records', 'questions'));
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
		if (! $this->RecordsQuestion->exists($id))
		{
			throw new NotFoundException(__('Invalid records question'));
		}
		if ($this->request->is(array(
				'post',
				'put'
		)))
		{
			if ($this->RecordsQuestion->save($this->request->data))
			{
				$this->Flash->success(__('The records question has been saved.'));
				return $this->redirect(array(
						'action' => 'index'
				));
			}
			else
			{
				$this->Flash->error(__('The records question could not be saved. Please, try again.'));
			}
		}
		else
		{
			$options = array(
					'conditions' => array(
							'RecordsQuestion.' . $this->RecordsQuestion->primaryKey => $id
					)
			);
			$this->request->data = $this->RecordsQuestion->find('first', $options);
		}
		$records = $this->RecordsQuestion->Record->find('list');
		$questions = $this->RecordsQuestion->Question->find('list');
		$this->set(compact('records', 'questions'));
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
		$this->RecordsQuestion->id = $id;
		if (! $this->RecordsQuestion->exists())
		{
			throw new NotFoundException(__('Invalid records question'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->RecordsQuestion->delete())
		{
			$this->Flash->success(__('The records question has been deleted.'));
		}
		else
		{
			$this->Flash->error(__('The records question could not be deleted. Please, try again.'));
		}
		return $this->redirect(array(
				'action' => 'index'
		));
	}
}
